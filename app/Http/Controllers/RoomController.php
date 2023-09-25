<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoomDetailResource;
use App\Models\ClassRoom;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
	public function getSelectClassroom()
	{
		$classRoom = ClassRoom::select('id', 'room_name')->get();
		return response()->json($classRoom, 200);
	}

	public function getDetailClassroom()
	{
		$classRoom = ClassRoom::whereId(request('id'))->first();
		if (!$classRoom) {
			return response()->json(['message' => 'Tipe Kamar tidak ditemukan'], 404);
		}

		$classRoom = ClassRoom::select('id', 'room_size', 'room_price')->get();
		return response()->json($classRoom, 200);
	}

	public function createRoom(Request $request)
	{
		$classRoom = ClassRoom::whereId($request->class_room_id)->first();
		if (!$classRoom) {
			return response()->json(['message' => 'Tipe Kamar tidak ditemukan'], 404);
		}

		$validate = $request->validate([
			'class_room_id' => 'required|integer',
			'number_room' => 'required|string',
			'number_floor' => 'required|string',
			'room_size' => 'required|string',
			'room_price' => 'required|integer',
			'status' => 'sometimes|string',
		]);
		$status = $request->input('status', 'Tidak Terisi');

		$classRoom->rooms()->create([
			'number_room' => $request->number_room,
			'number_floor' => $request->number_floor,
			'room_size' => $request->room_size,
			'room_price' => $request->room_price,
			'status_room' => $status,
		]);

		return response()->json([
			'message' => 'Berhasil'
		], 201);
	}

	public function getRoom(Request $request)
	{
		$request->validate([
			'floor' => 'required|string',
		]);

		$numberFloor = $request->input('floor');
		$rooms = Room::where('number_floor', $numberFloor)
			->select('id', 'number_room', 'number_floor', 'room_size', 'room_price', 'status_room')
			->get();

		return response()->json($rooms, 200);
	}
}
