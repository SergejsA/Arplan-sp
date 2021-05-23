<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\LietotajiController;
use App\Http\Controllers\ProjektiController;
use App\Http\Controllers\SettingsController;
use App\Mail\ResetPasswordMailable;
use App\Models\User;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('auth')->group(function(){
    Route::post('init', [AppController::class, 'init']);

    Route::post('login', [AppController::class, 'login']);
    Route::post('register', [AppController::class, 'register']);
    Route::post('logout', [AppController::class, 'logout']);
    Route::post('send-token', [AppController::class, 'sendToken']);
    Route::post('validate-token', [AppController::class, 'validateToken']);
    Route::post('reset-password', [AppController::class, 'resetPassword']);
    Route::post('changePass', [AppController::class, 'changePass']);
});

Route::prefix('lietotaji')->group(function(){
    Route::get('getAll', [LietotajiController::class, 'getAll']);
    Route::post('create', [LietotajiController::class, 'create']);
    Route::post('delete', [LietotajiController::class, 'delete']);
    Route::post('update', [LietotajiController::class, 'update']);
});

Route::prefix('projekti')->group(function(){
    Route::get('getAll', [ProjektiController::class, 'getAll']);
    Route::post('create', [ProjektiController::class, 'create']);
    Route::post('delete', [ProjektiController::class, 'delete']);
    Route::post('update', [ProjektiController::class, 'update']);
    Route::get('getActive', [ProjektiController::class, 'getActive']);

    Route::post('newdefault', [ProjektiController::class, 'newDefault']);
    Route::post('newparasti', [ProjektiController::class, 'newParasti']);
    Route::post('deletedefault', [ProjektiController::class, 'deleteDefault']);
    Route::post('deleteparasti', [ProjektiController::class, 'deleteParasti']);
});

Route::prefix('data')->group(function(){
    Route::get('getforUser', [DataController::class, 'getforUser']);
    Route::post('add', [DataController::class, 'add']);
    Route::post('delete', [DataController::class, 'delete']);
    Route::post('edit', [DataController::class, 'edit']);
    Route::post('getAll', [DataController::class, 'getAll']);
    Route::post('getChartInfo', [DataController::class, 'getChartInfo']);
    Route::post('newAdrese', [DataController::class, 'newAdrese']);
    Route::post('deleteAdrese', [DataController::class, 'deleteAdrese']);
});

Route::prefix('settings')->group(function(){
    Route::post('firstuser', [SettingsController::class, 'firstUser']);
    Route::post('changeIPSetting', [SettingsController::class, 'changeIPSetting']);
});

