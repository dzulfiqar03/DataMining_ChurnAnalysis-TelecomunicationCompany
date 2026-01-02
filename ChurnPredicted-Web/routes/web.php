<?php

use App\Http\Controllers\PredictedController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/home', [PredictedController::class, 'index']);

Route::get('/dashboard', function(){
    return view('dashboard');
})->name('dashboard');

Route::redirect('/', '/home')->name('home');