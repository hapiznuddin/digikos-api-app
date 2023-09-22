<?php

use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\OcCitizenshipDocController;
use App\Http\Controllers\RoomController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OccupantController;

//* Route Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group( function () {
    //* Route Occupants
    Route::get('/occupant', [OccupantController::class, 'getOccupantDetail']);
    Route::post('/occupant', [OccupantController::class, 'createOccupant']); 

    //* Route Occupant Files
    Route::get('/occupant/ktp-doc', [OcCitizenshipDocController::class, 'getKtp']);
    Route::post('/occupant/ktp-doc', [OcCitizenshipDocController::class, 'uploadKtp']);

    //* Route Class Rooms
    Route::get('/class-room', [ClassRoomController::class, 'getClassroom']);
    Route::post('/class-room/create', [ClassRoomController::class, 'createClassRoom']);
    Route::post('/class-room/create/images', [ClassRoomController::class, 'createImageRoom']);
    
    //* Route Rooms
    Route::get('/room', [RoomController::class, 'getSelectClassroom']);
    Route::get('/room/detail', [RoomController::class, 'getDetailClassroom']);
    Route::post('/room/create', [RoomController::class, 'createRoom']);
    

});
