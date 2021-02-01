<?php

namespace App\Http\Controllers;

use App\Models\AdresesIp;
use App\Models\Data;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DataController extends Controller
{
    private function sumIlgumi($a, $b){
        $a = array_slice(explode(";", $a), 0, 7);
        $b = array_slice(explode(";", $b), 0, 7);
        $a = array_map(function($v){
            return explode("=", $v)[1];
        }, $a);
        $b = array_map(function($v){
            return explode("=", $v)[1];
        }, $b);
        $rez = "";
        for($i = 0; $i < 7; $i++){
            $rez .= ($i+1)."=".($a[$i]+$b[$i]).";";
        }
        return $rez;
    }

    public function getforUser(){

        $dati = Data::where('daritaja_id', Auth::user()->id)->with('project')->orderBy('darbs')->get();

        return response()->json(['data' => $dati]);
    }

    public function add(Request $request){
        $data = Data::where('daritaja_id', Auth::user()->id)->where('datums', $request->datums)->where('project_id', $request->project_id)->where('darbs', $request->job)->get();
        if(sizeof($data) == 0){
            $d = new Data();
            $d->project_id = $request->project_id;
            $d->darbs = $request->job;
            $d->daritaja_id = Auth::user()->id;
            $d->datums = $request->datums;
            $d->ilgums = $request->ilgums;
            $d->save();
        }else{
            //Jāskaita
            $d = $data[0];
            $d->ilgums = $this->sumIlgumi($d->ilgums, $request->ilgums);
            $d->save();
        }

        $dati = Data::where('daritaja_id', Auth::user()->id)->with('project')->orderBy('darbs')->get();

        return response()->json(['data' => $dati]);
    }

    public function delete(Request $request){
        Data::find($request->id)->delete();

        $dati = Data::where('daritaja_id', Auth::user()->id)->with('project')->orderBy('darbs')->get();

        return response()->json(['data' => $dati]);
    }

    public function edit(Request $request){
        $d = Data::find($request->id);
        $d->ilgums = $request->ilgums;
        $d->save();

        $dati = Data::where('daritaja_id', Auth::user()->id)->with('project')->orderBy('darbs')->get();

        return response()->json(['data' => $dati]);
    }

    private function getDienuSkaits($m, $g){
        if($m == 2){
            if($g % 4 == 0){
                return 29;
            }else{
                return 28;
            }
        }else{
            if($m < 8){
                if($m % 2 == 0){
                    return 30;
                }else{
                    return 31;
                }
            }else{
                if($m % 2 == 0){
                    return 31;
                }else{
                    return 30;
                }
            }
        }
    }

    public function getAll(Request $request){
        $users = User::where('tips', '!=', 'system_admin')->where('tips', '!=', 'deactive')->orderBy('uzvards', 'asc')->get();
        $date = date('Y-m-d', strtotime($request->datums));
        $m = date('m', strtotime($date));
        $g = date('Y', strtotime($date));
        $data = array();

        foreach($users as $u){
            $data[$u->id] = array();
            $kopa = 0;
            for($i = 0; $i < $this->getDienuSkaits($m, $g); $i++){
                $datumaNr = date("N", strtotime(($i+1).".".$m.".".$g));
                $mekletDatums = date("Y-m-d", strtotime((1-$datumaNr)." days", strtotime(($i+1).".".$m.".".$g)));
                $d = Data::where('datums', $mekletDatums)->where('daritaja_id', $u->id)->get();
                $stundasKopa = 0;
                foreach($d as $row){
                    $stundasKopa += floatval(explode("=", explode(";", $row->ilgums)[$datumaNr-1])[1]);
                }
                $data[$u->id][$i] = array($datumaNr, $stundasKopa);
                $kopa += $stundasKopa;
            }
            array_push($data[$u->id], $kopa);
        }

        return response()->json(['lietotaji' => $users, 'data' => $data], 200);

    }

    public function getChartInfo(Request $request){
        $data = Data::where('project_id', $request->project)->get();
        if($request->user != "visi"){
            $data = Data::where('project_id', $request->project)->where('daritaja_id', $request->user)->get();
        }
        $labels = array();
        $datavalues = array();
        if($request->no != null){
            $dateNo = date('Y-m-d', strtotime($request->no));
            $datumaNoNr = date("N", strtotime($dateNo));
            $mekletNoDatums = date("Y-m-d", strtotime((1-$datumaNoNr)." days", strtotime($dateNo)));
            if($request->lidz != null){
                //Abi iestatīti
                $dateLidz = date('Y-m-d', strtotime($request->lidz));
                $datumaLidzNr = date("N", strtotime($dateLidz));
                $mekletLidzDatums = date("Y-m-d", strtotime((1-$datumaLidzNr)." days", strtotime($dateLidz)));
                if($mekletNoDatums == $mekletLidzDatums){
                    // Viena nedēļa
                    for($i = 0; $i < sizeof($data); $i++){
                        if($data[$i]->datums == $mekletNoDatums){
                            if(!in_array($data[$i]->darbs, $labels)){
                                array_push($labels, $data[$i]->darbs);
                                $datavalues[$data[$i]->darbs] = 0;
                            }
                            $ilgumi = explode(";", $data[$i]->ilgums);
                            for($j = $datumaNoNr-1; $j < $datumaLidzNr; $j++){
                                $datavalues[$data[$i]->darbs] += floatval(explode("=", $ilgumi[$j])[1]);
                            }
                        }
                    }
                }else{
                    for($i = 0; $i < sizeof($data); $i++){
                        if($data[$i]->datums == $mekletNoDatums){
                            if(!in_array($data[$i]->darbs, $labels)){
                                array_push($labels, $data[$i]->darbs);
                                $datavalues[$data[$i]->darbs] = 0;
                            }
                            $ilgumi = explode(";", $data[$i]->ilgums);
                            for($j = $datumaNoNr-1; $j < 7; $j++){
                                $datavalues[$data[$i]->darbs] += floatval(explode("=", $ilgumi[$j])[1]);
                            }
                        }else if($data[$i]->datums == $mekletLidzDatums){
                            if(!in_array($data[$i]->darbs, $labels)){
                                array_push($labels, $data[$i]->darbs);
                                $datavalues[$data[$i]->darbs] = 0;
                            }
                            $ilgumi = explode(";", $data[$i]->ilgums);
                            for($j = 0; $j < $datumaLidzNr; $j++){
                                $datavalues[$data[$i]->darbs] += floatval(explode("=", $ilgumi[$j])[1]);
                            }
                        }else if($data[$i]->datums > $mekletNoDatums && $data[$i]->datums < $mekletLidzDatums){
                            if(!in_array($data[$i]->darbs, $labels)){
                                array_push($labels, $data[$i]->darbs);
                                $datavalues[$data[$i]->darbs] = 0;
                            }
                            $ilgumi = explode(";", $data[$i]->ilgums);
                            for($j = 0; $j < 7; $j++){
                                $datavalues[$data[$i]->darbs] += floatval(explode("=", $ilgumi[$j])[1]);
                            }
                        }
                    }
                }
            }else{
                //No datuma
                for($i = 0; $i < sizeof($data); $i++){
                    if($data[$i]->datums == $mekletNoDatums){
                        if(!in_array($data[$i]->darbs, $labels)){
                            array_push($labels, $data[$i]->darbs);
                            $datavalues[$data[$i]->darbs] = 0;
                        }
                        $ilgumi = explode(";", $data[$i]->ilgums);
                        for($j = $datumaNoNr-1; $j < 7; $j++){
                            $datavalues[$data[$i]->darbs] += floatval(explode("=", $ilgumi[$j])[1]);
                        }
                    }else if($data[$i]->datums > $mekletNoDatums){
                        if(!in_array($data[$i]->darbs, $labels)){
                            array_push($labels, $data[$i]->darbs);
                            $datavalues[$data[$i]->darbs] = 0;
                        }
                        $ilgumi = explode(";", $data[$i]->ilgums);
                        for($j = 0; $j < 7; $j++){
                            $datavalues[$data[$i]->darbs] += floatval(explode("=", $ilgumi[$j])[1]);
                        }
                    }
                }
            }
        }else{
            if($request->lidz != null){
                $dateLidz = date('Y-m-d', strtotime($request->lidz));
                $datumaLidzNr = date("N", strtotime($dateLidz));
                $mekletLidzDatums = date("Y-m-d", strtotime((1-$datumaLidzNr)." days", strtotime($dateLidz)));
                for($i = 0; $i < sizeof($data); $i++){
                    if($data[$i]->datums == $mekletLidzDatums){
                        if(!in_array($data[$i]->darbs, $labels)){
                            array_push($labels, $data[$i]->darbs);
                            $datavalues[$data[$i]->darbs] = 0;
                        }
                        $ilgumi = explode(";", $data[$i]->ilgums);
                        for($j = 0; $j < $datumaLidzNr; $j++){
                            $datavalues[$data[$i]->darbs] += floatval(explode("=", $ilgumi[$j])[1]);
                        }
                    }else if($data[$i]->datums < $mekletLidzDatums){
                        if(!in_array($data[$i]->darbs, $labels)){
                            array_push($labels, $data[$i]->darbs);
                            $datavalues[$data[$i]->darbs] = 0;
                        }
                        $ilgumi = explode(";", $data[$i]->ilgums);
                        for($j = 0; $j < 7; $j++){
                            $datavalues[$data[$i]->darbs] += floatval(explode("=", $ilgumi[$j])[1]);
                        }
                    }
                }
            }else{
                //Viss periods
                for($i = 0; $i < sizeof($data); $i++){
                    if(!in_array($data[$i]->darbs, $labels)){
                        array_push($labels, $data[$i]->darbs);
                        $datavalues[$data[$i]->darbs] = 0;
                    }
                    $ilgumi = explode(";", $data[$i]->ilgums);
                    for($j = 0; $j < 7; $j++){
                        $datavalues[$data[$i]->darbs] += floatval(explode("=", $ilgumi[$j])[1]);
                    }
                }
            }
        }
        $values = array();
        for($i = 0; $i < sizeof($labels); $i++){
            array_push($values, $datavalues[$labels[$i]]);
        }

        return response()->json(['labels' => $labels, 'values' => $values]);
    }

    public function newAdrese(Request $request){
        $a = new AdresesIp();
        $a->adrese = $request->adrese;
        $a->save();

        $add = AdresesIp::all();

        return response()->json(['adreses' => $add]);
    }

    public function deleteAdrese(Request $request){
        $a = AdresesIp::find($request->id);
        $a->delete();

        $add = AdresesIp::all();

        return response()->json(['adreses' => $add]);
    }

}
