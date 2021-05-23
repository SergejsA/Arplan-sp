<?php

namespace App\Http\Controllers;

use App\Models\AdresesIp;
use App\Models\Data;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;

class SettingsController extends Controller
{
    public function firstUser(Request $request){
        $user = new User();
        $user->vards = $request->vards;
        $user->uzvards = $request->uzvards;
        $user->email = $request->email;
        $user->password = Hash::make($request->parole);
        $user->tips = 'system_admin';
        $user->last_login = now();

        $user->save();

        $setting = new Setting();
        $setting->name = 'ip_filter';
        $setting->value = '0';
        $setting->save();

        $s_firma = new Setting();
        $s_firma->name = 'firma';
        $s_firma->value = $request->firma;
        $s_firma->save();
    }

    public function changeIPSetting(Request $request){
        $s = Setting::where('name', 'ip_filter')->get();
        if(sizeof($s) == 0){
            $setting = new Setting();
            $setting->name = 'ip_filter';
            $setting->value = $request->ip ? '1' : '0';
            $setting->save();
        }else{
            $s[0]->value = $request->ip ? '1' : '0';
            $s[0]->save();
        }

        if($request->ip){
            $a = AdresesIp::all();
            if(sizeof($a) == 0){
                $new_a = new AdresesIp();
                $new_a->adrese =  $request->ip();
                $new_a->save();
            }
        }

        $adds = AdresesIp::all();
        return response()->json(['adreses' => $adds], 200);
    }

}
