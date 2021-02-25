<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CampaignController;

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

Route::group(['middleware' => 'jwt.verify'], function(){
    Route::post('logout', [UserController::class, 'logout']);
    Route::post('/editProfile', [UserController::class, 'update']);
});

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);

Route::group(['prefix' => 'campaign',  'middleware' => ['jwt.verify']], function() {
    Route::get('/show', [CampaignController::class, 'index']);
    Route::get('/show/{id}', [CampaignController::class, 'show']);
    Route::post('/create', [CampaignController::class, 'store']);
    Route::put('/update/{id}', [CampaignController::class, 'update']);
    Route::delete('/delete/{id}', [CampaignController::class, 'destroy']);
});

