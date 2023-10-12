<?php

use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\OcCitizenshipDocController;
use App\Http\Controllers\RoomController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OccupantController;
use App\Http\Controllers\RentController;
use App\Http\Controllers\TestimonialController;

//* Route Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/class-room-landingpage', [ClassRoomController::class, 'getClassRoomLandingPage']);
Route::get('/class-room-detail-landingpage', [ClassRoomController::class, 'getDetailClassroomlandingPage']);
Route::get('/class-room/image', [ClassRoomController::class, 'getImageRoom']);
Route::get('/facility-landingpage', [ClassRoomController::class, 'getFacilityLandingPage']);
Route::get('/number-room', [RoomController::class, 'getNumberRoomWithFloor']);

Route::middleware('auth:sanctum')->group( function () {
    Route::get('/user', [AuthController::class, 'getUser']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    //* Route Occupants
    Route::get('/occupant', [OccupantController::class, 'getOccupantDetail']);
    Route::post('/occupant', [OccupantController::class, 'createOccupant']); 
    Route::put('/occupant', [OccupantController::class, 'updateOccupant']);

    //* Route Occupant Files
    Route::get('/occupant/ktp-doc', [OcCitizenshipDocController::class, 'getKtp']);
    Route::post('/occupant/ktp-doc', [OcCitizenshipDocController::class, 'uploadKtp']);
    Route::get('/occupant/family-doc', [OcCitizenshipDocController::class, 'getFamilyDoc']);
    Route::post('/occupant/family-doc', [OcCitizenshipDocController::class, 'createFamilyDoc']);
    Route::get('/occupant/profile-pic', [OcCitizenshipDocController::class, 'getProfilePic']);
    Route::post('/occupant/profile-pic', [OcCitizenshipDocController::class, 'createProfilePic']);

    //* Route Class Rooms
    Route::get('/class-room', [ClassRoomController::class, 'getClassroom']);
    Route::post('/class-room', [ClassRoomController::class, 'createClassRoom']);
    Route::post('/class-room/image', [ClassRoomController::class, 'createImageRoom']);
    
    //* Route Rooms
    Route::get('/room/select-class', [RoomController::class, 'getSelectClassroom']);
    Route::get('/room/detail-class', [RoomController::class, 'getDetailClassroom']);
    Route::get('/room/detail', [ClassRoomController::class, 'getDetailRoom']);
    Route::get('/room', [RoomController::class, 'getRoom']);
    Route::post('/room', [RoomController::class, 'createRoom']);
    
    //* Route Rents
    Route::post('/rent-stage-1', [RentController::class, 'createRentStage1']);
    Route::get('/rent-stage-1', [RentController::class, 'getRentStage1']);
    
    Route::post('/testimonial', [TestimonialController::class, 'createTestimonial']);
    
});
