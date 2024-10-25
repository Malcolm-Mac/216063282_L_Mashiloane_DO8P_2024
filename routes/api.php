<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/agent/report', [AgentController::class, 'report']);
Route::get('/agent/command/{os}', [AgentController::class, 'getCommand']);

