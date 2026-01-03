<?php

use App\Http\Controllers\Api\DataController;
use App\Http\Controllers\Api\DataSenderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/data-sensor', action: [DataSenderController::class, 'index']);
Route::get('/getChurn', action: [DataController::class, 'getAll']);

Route::apiResource('predicted', DataSenderController::class);
// routes/api.php
Route::post('/cluster-summary', [DataController::class, 'store']);

Route::get('/cluster-summary2', function() {
    return response()->json([
        'tenure' => cache('tenure'),
        'online_security' => cache('online_security'),
        'tech_support' => cache('tech_support'),
        'prediction' => cache('prediction'),
        'cluster' => cache('cluster'),
        'cluster_data' => cache('cluster_data')
    ]);
});
