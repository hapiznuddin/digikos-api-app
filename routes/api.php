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

Route::get('/class-room-landingpage', [ClassRoomController::class, 'getClassRoomLandingPage']);

Route::middleware('auth:sanctum')->group( function () {
    Route::get('/user', [AuthController::class, 'getUser']);
    //* Route Occupants
    Route::get('/occupant', [OccupantController::class, 'getOccupantDetail']);
    Route::post('/occupant', [OccupantController::class, 'createOccupant']); 

    //* Route Occupant Files
    Route::get('/occupant/ktp-doc', [OcCitizenshipDocController::class, 'getKtp']);
    Route::post('/occupant/ktp-doc', [OcCitizenshipDocController::class, 'uploadKtp']);

    //* Route Class Rooms
    Route::get('/class-room', [ClassRoomController::class, 'getClassroom']);
    Route::post('/class-room', [ClassRoomController::class, 'createClassRoom']);
    Route::get('/class-room/image', [ClassRoomController::class, 'getImageRoom']);
    Route::post('/class-room/image', [ClassRoomController::class, 'createImageRoom']);
    
    //* Route Rooms
    Route::get('/room/select-class', [RoomController::class, 'getSelectClassroom']);
    Route::get('/room/detail-class', [RoomController::class, 'getDetailClassroom']);
    Route::get('/room/detail', [ClassRoomController::class, 'getDetailRoom']);
    Route::get('/room', [RoomController::class, 'getRoom']);
    Route::post('/room', [RoomController::class, 'createRoom']);
    

});
