<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\GroupSubscribersController;
use App\Http\Controllers\SubscribersController;
use App\Http\Controllers\MailController;


Route::group(['prefix' => 'user', 'middleware' => ['jwt.verify']], function(){
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


//Group Controller
Route::group(['prefix' => 'group', 'middleware' => ['jwt.verify']], function() {
    Route::get('/read', [GroupController::class, 'index']);
    Route::post('/create', [GroupController::class, 'store']);
    Route::post('/update/{id}', [GroupController::class, 'update']);
    Route::delete('/delete/{id}', [GroupController::class, 'destroy']);
});

//GroupSubscribers Controller
Route::group(['prefix' => 'group-subscribers', 'middleware' => ['jwt.verify']], function() {
    Route::get('/read', [GroupSubscribersController::class, 'index']);
    Route::post('/create', [GroupSubscribersController::class, 'store']);
    Route::post('/update/{id}', [GroupSubscribersController::class, 'update']);
    Route::delete('/delete/{id}', [GroupSubscribersController::class, 'destroy']);
});

//Subscribers Controller
Route::group(['prefix' => 'subscribers', 'middleware' => ['jwt.verify']], function() {
    Route::get('/read', [SubscribersController::class, 'index']);
    Route::post('/create', [SubscribersController::class, 'store']);
    Route::post('/update/{id}', [SubscribersController::class, 'update']);
    Route::delete('/delete/{id}', [SubscribersController::class, 'destroy']);
});

//Mail Controller
Route::group(['prefix' => 'mymail', 'middleware' => ['jwt.verify'] ], function() {
    Route::get('/', [MailController::class, 'index']);
    Route::get('/{id}', [MailController::class, 'show']);
    Route::delete('/delete/{id}', [MailController::class, 'destroy']);
});

