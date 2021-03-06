<?php

namespace App\Http\Controllers;

use App\Models\AdresesIp;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LietotajiController extends Controller
{
    public function getAll(){
        $users = User::all();
        $a = AdresesIp::all();
        $ip = Setting::where('name', 'ip_filter')->get();
        $useIP = sizeof($ip) > 0 && intval($ip[0]->value) == 1;
        return response()->json(['lietotaji' => $users, 'adreses' => $a, 'useIP' => $useIP], 200);
    }

    public function create(Request $request){
        $user = new User();
        $user->vards = $request->vards;
        $user->uzvards = $request->uzvards;
        $user->email = $request->email;
        $user->password = Hash::make($request->parole);
        $user->tips = $request->tips;
        $user->last_login = now();

        $user->save();

        $users = User::all();
        return response()->json(['lietotaji' => $users], 200);
    }

    public function delete(Request $request){
        $user = User::find($request->id);
        $user->delete();

        $users = User::all();
        return response()->json(['lietotaji' => $users], 200);
    }

    public function update(Request $request){
        $user = User::find($request->id);
        $user->vards = $request->vards;
        $user->uzvards = $request->uzvards;
        $user->email = $request->email;
        $user->tips = $request->tips;
        if($request->parole != ''){
            $user->password = Hash::make($request->parole);
        }

        $user->save();

        $users = User::all();
        return response()->json(['lietotaji' => $users], 200);
    }
}
