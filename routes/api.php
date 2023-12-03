<?php

use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\OcCitizenshipDocController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoomController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ComplaintMessageController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\OccupantController;
use App\Http\Controllers\RentController;
use App\Http\Controllers\StatisticReviewController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\UserController;

//* Route Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/class-room-landingpage', [ClassRoomController::class, 'getClassRoomLandingPage']);
Route::get('/class-room-detail-landingpage', [ClassRoomController::class, 'getDetailClassroomlandingPage']);
Route::get('/class-room/image', [ClassRoomController::class, 'getImageRoom']);
Route::get('/facility-landingpage', [ClassRoomController::class, 'getFacilityLandingPage']);
Route::get('/number-room', [RoomController::class, 'getNumberRoomWithFloor']);
Route::get('/testimonial', [TestimonialController::class, 'getTestimonial']);
Route::get('/testimonial-random', [TestimonialController::class, 'getTestimonialRandom']);
Route::get('/statistic-by-classroom', [StatisticReviewController::class, 'getStatisticReviewByClassRoomId']);

Route::middleware('auth:sanctum')->group( function () {
    Route::get('/user', [AuthController::class, 'getUser']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    Route::post('/edit-password', [AuthController::class, 'editPassword']);

    
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
    Route::post('/occupant/profile-pic/update', [OcCitizenshipDocController::class, 'updateProfilePic']);

    //* Route Class Rooms
    Route::get('/class-room', [ClassRoomController::class, 'getClassroom']);
    Route::get('/class-room/detail', [ClassRoomController::class, 'getDetailClassroom']);
    Route::post('/class-room', [ClassRoomController::class, 'createClassRoom']);
    Route::put('/class-room', [ClassRoomController::class, 'updateClassRoom']);
    Route::delete('/class-room', [ClassRoomController::class, 'deleteClassRoom']);
    Route::post('/class-room/image', [ClassRoomController::class, 'createImageRoom']);
    Route::post('/class-room/image/update', [ClassRoomController::class, 'updateImageRoom']);
    Route::delete('/class-room/image', [ClassRoomController::class, 'deleteImageRoom']);
    
    //* Route Rooms
    Route::get('/room/select-class', [RoomController::class, 'getSelectClassroom']);
    Route::get('/room/detail-class', [RoomController::class, 'getDetailClassroom']);
    Route::get('/room/detail', [ClassRoomController::class, 'getDetailRoom']);
    Route::post('/room', [RoomController::class, 'createRoom']);
    Route::get('/room', [RoomController::class, 'getAllRoomsByFloorAndFilterName']);
    Route::put('/room', [RoomController::class, 'updateRoom']);
    Route::delete('/room', [RoomController::class, 'deleteRoom']);

    
    //* Route Rents
    Route::post('/rent-stage-1', [RentController::class, 'createRentStage1']);
    Route::get('/rent-stage-1', [RentController::class, 'getRentStage1']);
    Route::put('/rent-stage-2', [RentController::class, 'updateRentStage2']);
    Route::get('/rent', [RentController::class, 'getAllRent']);
    Route::get('/rent-history', [RentController::class, 'getHistoryRent']);
    Route::get('/rent/detail', [RentController::class,'getDetailRent']);
    Route::get('/rent/detail/user', [RentController::class,'getDetailRentByUser']);
    Route::get('/rent-history-roomid', [RentController::class, 'getRentHistoryByRoomId']);
    Route::get('/rentByUserId', [RentController::class, 'getRentByUserId']);

    Route::post('/rent-approval/admin', [RentController::class, 'approvalRent']);
    Route::post('/rent-approval/check-in', [RentController::class, 'approvalCheckIn']);
    Route::get('/rent/statistic', [RentController::class, 'getStatisticRoom']);
    
    //* Route Payments
    Route::post('/first-payment', [PaymentController::class, 'createPayment']);
    Route::post('/webhook-payment', [PaymentController::class, 'webhookPayment']);
    Route::get('/payment-history', [PaymentController::class, 'getHistoryPayment']);
    
    // ** Route Testimonial
    Route::post('/testimonial', [TestimonialController::class, 'createTestimonial']);

    // ** Route Pengeluaran
    Route::post('/expense', [ExpenseController::class, 'createExpense']);
    Route::get('/expense', [ExpenseController::class, 'getExpense']);

    // ** Route Laporan
    Route::get('/report', [ReportController::class, 'getReport']);

    // ** Route Management User
    Route::get('/management-user', [UserController::class, 'getAllUser']);
    Route::put('/management-user/promote', [UserController::class, 'promoteAdmin']);
    Route::delete('/management-user/delete', [UserController::class, 'deleteUser']);

    // ** Route Complaint Message
    Route::post('/user/message', [ComplaintMessageController::class, 'createMessage']);
    Route::get('/user/message', [ComplaintMessageController::class, 'getMessageByUser']);
    Route::get('/admin/message', [ComplaintMessageController::class, 'getMessageByAdmin']);
    Route::get('/admin/message/detail', [ComplaintMessageController::class, 'getDetailMessage']);
    Route::post('/admin/message/approve', [ComplaintMessageController::class, 'approveMessage']);
    
    // ** Route Invoice
    Route::post('/invoice', [InvoiceController ::class, 'createInvoice']);
    Route::get('/invoice/status', [InvoiceController ::class, 'getInvoiceByStatus']);
    Route::get('/invoice/check', [InvoiceController ::class, 'getCheckInvoice']);
    
});
