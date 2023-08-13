<?php

use App\Http\Controllers\OcCitizenshipDocController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OccupantController;

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group( function () {
    Route::post('/occupant', [OccupantController::class, 'createOccupant']); 
    Route::get('/occupant', [OccupantController::class, 'getOccupantDetail']);
    Route::post('/occupant/ktp-doc', [OcCitizenshipDocController::class, 'uploadKtp']);
});
