<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordMailable;
use App\Models\AdresesIp;
use App\Models\PasswordReset;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface;

class AppController extends Controller
{
    public function init(Request $request){

        $atlauts = true;
        $setting_ip = Setting::where('name', 'ip_filter')->get();
        if(sizeof($setting_ip) != 0 && intval($setting_ip[0]->value) == 1){
            $ip = $request->ip();
            $ips = AdresesIp::all();
            $atlauts = false;
            foreach($ips as $i){
                if($i->adrese == $ip){
                    $atlauts = true;
                    break;
                }
            }
        }

        $user = Auth::user();

        $users = User::all();
        $need_init = false;
        if(sizeof($users) == 0){
            $need_init = true;
        }

        $firma = Setting::where('name', 'firma')->get();
        if(sizeof($firma) > 0){
            $f_nos = $firma[0]->value;
        }else{
            $f_nos = "FIRMA";
        }

        return response()->json(['user' => $user, 'ip' => ($atlauts ? 'ir' : 'nav'), 'need_init' => $need_init, 'firma' => $f_nos], 200);
    }

    public function login(Request $request){
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password], true)){
            return response()->json(Auth::user(), 200);
        }else{
            return response()->json(['error', 'Could not log you in'], 401);
        }
    }

    public function register(Request $request){

        $user = User::where('email', $request->email)->first();
        if(isset($user->id)){
            return response()->json(['error' => 'User with this email is already registered'], 401);
        }

        $user = new User();

        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->email = $request->email;
        $user->competition_count = 8;
        $user->role = 'user';
        $user->password = Hash::make($request->password);
        $user->save();

        Auth::login($user);

        return response()->json($user, 200);
    }

    public function logout(){
        Auth::logout();
    }

    public function sendToken(Request $request){
        $user = User::where('email', $request->email)->where('tips', '!=', 'deactive')->first();
        if(!isset($user->id)){
            return response()->json(['error' => 'User_with_this_email_does_not_exist'], 401);
        }

        $token = Str::random(32);

        Mail::to($user)->send(new ResetPasswordMailable($token));

        $passwordReset = new PasswordReset();
        $passwordReset->email = $user->email;
        $passwordReset->token = $token;
        $passwordReset->save();
        // return response()->json($token, 200);
    }

    public function validateToken(Request $request)
    {
        $passwordReset = PasswordReset::where('token', $request->token)->first();
        if(!isset($passwordReset->email)){
            return response()->json(['error' => 'invalid_token'], 401);
        }

        $user = User::where('email', $passwordReset->email)->first();
        return response()->json($user, 200);
    }

    public function resetPassword(Request $request){
        $user = User::find($request->user_id);
        $passwordReset = PasswordReset::where('email', $user->email)->first();
        // return response()->json($passwordReset);
        $passwordReset->delete();

        $user->password = Hash::make($request->password);
        $user->save();
    }

    public function changePass(Request $request){
        $user = User::find(Auth::user()->id);
        if(Hash::check($request->veca, $user->password)){
            $user->password = Hash::make($request->jauna);
            $user->save();
            return response()->json(['mes' => 'OK']);
        }else{
            return response()->json(['mes' => 'nav_veca']);
        }
    }
}
