<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\AllRentResource;
use App\Http\Resources\DetailRentResource;
use App\Http\Resources\GetRentHistoryByRoomIdResource;
use App\Http\Resources\HistoryRentResource;
use App\Http\Resources\RentResource;
use App\Models\Occupant;
use App\Models\Payment;
use App\Models\Rent;
use App\Models\Room;
use App\Models\User;
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

        $status_id = 2;

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


    public function approvalRent(Request $request)
    {
        $request->validate([
            'rent_id' => 'required',
        ]);
        $rent = Rent::find($request->rent_id);
        if (!$rent) {
            return response()->json(['message' => 'Rent tidak ditemukan'], 404);
        }
        $rent->status_id = 3;
        $rent->save();
        return response()->json(['message' => 'Rent disetujui'], 200);
    }


    public function approvalCheckIn(Request $request)
    {
        $request->validate([
            'rent_id' => 'required',
        ]);
        $rent = Rent::find($request->rent_id);
        if (!$rent) {
            return response()->json(['message' => 'Rent tidak ditemukan'], 404);
        }
        $room = $rent->room;
        $room->status_room = 'Terisi'; // Perbarui status kamar
        $room->save(); // Simpan perubahan status kamar

        $rent->status_id = 6; // Perbarui status sewa
        $rent->status_checkin = 1;
        $rent->save();

        return response()->json(['message' => 'Berhasil Check In'], 200);
    }


    public function getAllRent(Request $request)
    {
        // Menerima parameter status_id dari URL jika ada
        $statusId = $request->input('status_id', null);

        // Buat query awal untuk Rent
        $query = Rent::query();

        // Jika status_id disediakan, tambahkan klausa where untuk filter
        if ($statusId !== null) {
            $query->whereIn('status_id', explode(',', $statusId)); // Jika Anda ingin mengizinkan beberapa status_id dipisahkan oleh koma
        }

        // Ambil data sesuai dengan filter
        $rents = $query->get();
        return AllRentResource::collection($rents);
    }


    public function getHistoryRent()
    {
        $user = auth()->user();
        $rent = $user->occupant->rent;
        $rent->load('room.classroom');
        if (!$rent) {
            return response()->json(['message' => 'Pengajuan tidak ditemukan'], 404);
        }
        // Memuat relasi sesuai kebutuhan
        $rent->load('room.classroom');

        return HistoryRentResource::collection($rent);
    }


    public function getDetailRent(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        $rent = Rent::find($request->id);
        if (!$rent) {
            return response()->json(['message' => 'Rent not found'], 404);
        }
        $rent->load('room.classroom');
        return new DetailRentResource($rent);
    }

    public function getDetailRentByUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|string',
        ]);
        
        $user = User::find($request->user_id)->occupant()->first();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $rent = Rent::where('occupant_id', $user->id)->first();
        if (!$rent) {
            return response()->json(['message' => 'Rent not found'], 404);
        }
        $rent->load('room.classroom');
        
        return new DetailRentResource($rent);
    }


    public function getRentHistoryByRoomId(Request $request)
    {
        $request->validate([
            'room_id' => 'required|integer',
        ]);

        $rent = Rent::where('room_id', $request->room_id)->first();
        if (!$rent) {
            return response()->json(['message' => 'Rent not found'], 404);
        }

        $occupant = $rent->occupant;
        if (!$occupant) {
            return response()->json(['message' => 'Occupant not found'], 404);
        }

        $filteredRent = $occupant->rent->filter(function ($item) {
            return $item->status_id == 5;
        });

        return GetRentHistoryByRoomIdResource::collection($filteredRent);
    }

    public function getRentByUserId(Request $request)
    {
        $request->validate([
            'user_id' => 'required|string',
        ]);
        $rent = Rent::whereHas('occupant', function ($query) use ($request) {
            $query->where('user_id', $request->user_id);
        })->select('id', 'occupant_id', 'room_id', 'status_id')->first();
        if (!$rent) {
            return response()->json(['message' => 'Rent not found'], 404);
        }

        return response()->json($rent, 200);
    }

    public function getStatisticRoom()
    {
        $totalRoom = Room::count();
        $roomAvailable = Room::where('status_room', 'Tidak Terisi')->count();
        $roomFill = Room::where('status_room', 'Terisi')->count();
        $totalOccupant = Rent::where('status_id', '6')->count();

        $data = [
            'total_room' => $totalRoom,
            'room_available' => $roomAvailable,
            'room_filled' => $roomFill,
            'total_occupant' => $totalOccupant
        ];
        return response()->json($data, 200);
    }
}
