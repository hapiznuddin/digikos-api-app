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

	public function getDetailClassroom(Request $request)
	{
		$request->validate([
			'id' => 'required|string',
	]);
	$classRoom = ClassRoom::select('id', 'room_size', 'room_price')->find($request->id);

	if (!$classRoom) {
			return response()->json(['message' => 'Tipe Kamar tidak ditemukan'], 404);
	}

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

	public function getNumberRoomWithFloor(Request $request)
	{
		$request->validate([
			'floor' => 'required|string',
			'id' => 'required|string',
		]);
		$rooms = Room::where('number_floor', $request->floor)
			->where('id_class_room', $request->id)
			->where('status_room', 'Tidak Terisi')
			->pluck('number_room');

		return response()->json(['number_room' => $rooms], 200);
	}

	// * Get All Room
	public function getAllRoomsByFloorAndFilterName(Request $request)
	{
		$request->validate([
			'floor' => 'required|integer',
		]);

		$floor = $request->input('floor');
    $id = $request->input('id', null); // Mengambil 'id' dari permintaan atau set ke null jika tidak ada.

    $rooms = Room::where('number_floor', $floor);

    if (!is_null($id)) {
        $classRoom = ClassRoom::find($id);
        if (!$classRoom) {
            return response()->json(['message' => 'Tipe Kamar tidak ditemukan'], 404);
        }
        $rooms->where('id_class_room', $classRoom->id);
    }

		$rooms = $rooms-> select('id', 'number_room', 'number_floor', 'room_size', 'room_price', 'status_room', 'id_class_room')->with(['classRoom:id,room_name'])
        ->paginate(3);

		return response()->json($rooms, 200);
	}

	// * Delete Room
	public function deleteRoom(Request $request)
	{
		$request->validate([
			'id' => 'required|integer', 
		]);
		$room = Room::find($request->id);
		if (!$room) {
			return response()->json(['message' => 'Kamar tidak ditemukan'], 404);
		}
		$room->delete();
		return response()->json(['message'=> 'Berhasil'],200);
	}

}
