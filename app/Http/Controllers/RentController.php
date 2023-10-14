<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\RentResource;
use App\Models\Rent;
use App\Models\Room;
use Illuminate\Http\Request;

class RentController extends Controller
{
    public function createRentStage1(Request $request)
    {
        $request->validate([
            'start_date' => 'required',
            'payment_term' => 'required',
            'total_price' => 'required',
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
            'total_price' => $request->total_price,
            'room_id' => $room->id
        ]);

        $newRent = $room->rent()->latest()->first(); 

        return response()->json([
            'message' => 'Berhasil',
            'rent_id' => $newRent->id
        ], 201);
    }

    public function getRentStage1(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);
        $rent = Rent::find($request->id);
        if (!$rent) {
            return response()->json(['message' => 'Rent not found'], 404);
        }
        $rent->load('room.classroom');
        return new RentResource($rent);
    }

    public function updateRentStage2(Request $request)
    {
        $request->validate([
            'rent_id' => 'required',
            'occupant_id' => 'required',
            'total_payment' => 'required',
            'additional_occupant',
        ]);

        $status_id = 1;

        $rent = Rent::find($request->rent_id);
        if (!$rent) {
            return response()->json(['message' => 'Rent not found'], 404);
        }

        // Update data rent untuk tahap kedua
        $rent->update([
        'occupant_id' => $request->occupant_id,
        'status_id' => $status_id,
        'total_payment' => $request->total_payment,
        'additional_occupant' => $request->additional_occupant,
        ]);

        return response()->json([
            'message' => 'Berhasil',
        ], 201);

        


    }
}
