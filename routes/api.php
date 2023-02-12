<?php

use App\Http\Controllers\AppControllers\AccessController;
use App\Http\Controllers\AppControllers\AppUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test', function(Request $request) {
    echo 'You entered '.$request->test;
});

Route::post('/user/create', [AppUserController::class,'createRecord']); 
Route::post('/user/update', [AppUserController::class,'updateRecord']);
Route::post('/user/delete', [AppUserController::class,'deleteRecord']);

Route::post('/access/login', [AccessController::class,'userLogin']);
