<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\GroupSubscribersController;
use App\Http\Controllers\SubscribersController;


//Group Controller
Route::group(['prefix' => 'group'], function() {
    Route::get('/read', [GaleryController::class, 'index']);
    Route::post('/create', [GaleryController::class, 'store']);
    Route::post('/update/{id}', [GaleryController::class, 'update']);
    Route::delete('/delete/{id}', [GaleryController::class, 'destroy']);
});

//GroupSubscribers Controller
Route::group(['prefix' => 'group-subscribers'], function() {
    Route::get('/read', [GaleryController::class, 'index']);
    Route::post('/create', [GaleryController::class, 'store']);
    Route::post('/update/{id}', [GaleryController::class, 'update']);
    Route::delete('/delete/{id}', [GaleryController::class, 'destroy']);
});

//Subscribers Controller
Route::group(['prefix' => 'subscribers'], function() {
    Route::get('/read', [GaleryController::class, 'index']);
    Route::post('/create', [GaleryController::class, 'store']);
    Route::post('/update/{id}', [GaleryController::class, 'update']);
    Route::delete('/delete/{id}', [GaleryController::class, 'destroy']);
});