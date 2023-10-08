<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RentController extends Controller
{
    public function createRentStage1(Request $request)
    {
        $request->validate([
            'start_date' => 'required',
            'payment_term' => 'required',
            'total_payment' => 'required',
            'number_room' => 'required',
        ]);
        
        $numberRoom = $request->input('number_room');
        $room = Room::where('number_room', $numberRoom)->first();

        if (!$room) {
            return response()->json(['message' => 'Room not found'], 404);
        }

        $room->rent()->create([
            'start_date' => $request->start_date,
            'payment_term' => $request->payment_term,
            'total_payment' => $request->total_payment,
            'room_id' => $room->id
        ]);

        return response()->json([
            'message' => 'Berhasil'
        ], 201);

    }
}
