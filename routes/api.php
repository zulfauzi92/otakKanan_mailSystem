<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\GroupSubscribersController;
use App\Http\Controllers\SubscribersController;


//Group Controller
Route::group(['prefix' => 'group'], function() {
    Route::get('/read', [GroupController::class, 'index']);
    Route::post('/create', [GroupController::class, 'store']);
    Route::post('/update/{id}', [GroupController::class, 'update']);
    Route::delete('/delete/{id}', [GroupController::class, 'destroy']);
});

//GroupSubscribers Controller
Route::group(['prefix' => 'group-subscribers'], function() {
    Route::get('/read', [GroupSubscribersController::class, 'index']);
    Route::post('/create', [GroupSubscribersController::class, 'store']);
    Route::post('/update/{id}', [GroupSubscribersController::class, 'update']);
    Route::delete('/delete/{id}', [GroupSubscribersController::class, 'destroy']);
});

//Subscribers Controller
Route::group(['prefix' => 'subscribers'], function() {
    Route::get('/read', [SubscribersController::class, 'index']);
    Route::post('/create', [SubscribersController::class, 'store']);
    Route::post('/update/{id}', [SubscribersController::class, 'update']);
    Route::delete('/delete/{id}', [SubscribersController::class, 'destroy']);
});