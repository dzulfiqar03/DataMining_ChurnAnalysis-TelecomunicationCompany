<?php

use App\Http\Controllers\PredictedController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/home', [PredictedController::class, 'index']);

Route::redirect('/', '/home')->name('home');