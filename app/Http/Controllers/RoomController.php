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
    $id = $request->input('id', null);
		$status = $request->input('status', null); 
		$search = $request->input('search', null);

    $rooms = Room::where('number_floor', $floor);

    if (!is_null($id)) {
        $classRoom = ClassRoom::find($id);
        if (!$classRoom) {
            return response()->json(['message' => 'Tipe Kamar tidak ditemukan'], 404);
        }
        $rooms->where('id_class_room', $classRoom->id);
    }

		if (!is_null($status)) {
			$rooms->where('status_room', $status);
			if (!$status) {
				return response()->json(['message' => 'Status Kamar tidak ditemukan'], 404);
			}
			$rooms->where('status_room', $status);
		}

		if (!is_null($search)) {
			$rooms->where(function ($query) use ($search) {
				$query->where('number_room', 'like', '%' . $search . '%')
					->orWhere('number_floor', 'like', '%' . $search . '%')
					->orWhere('room_size', 'like', '%' . $search . '%')
					->orWhere('status_room', 'like', '%' . $search . '%')
					->orWhere('room_price', 'like', '%' . $search . '%')
					->orWhereHas('classRoom', function ($classRoomQuery) use ($search) {
						$classRoomQuery->where('room_name', 'like', '%' . $search . '%');
					});
			});
		}

		$rooms = $rooms-> select('id', 'number_room', 'number_floor', 'room_size', 'room_price', 'status_room', 'id_class_room')->with(['classRoom:id,room_name'])
        ->paginate(10);

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

	// * Update Room
	public function updateRoom(Request $request)
	{
		$request->validate([
			'id' => 'integer',
			'class_room_id' => 'integer',
			'number_room' => 'string',
			'number_floor' => 'string',
			'room_size' => 'string',
			'room_price' => 'integer',
		]);

		$room = Room::find($request->id);

		if (!$room) {
			return response()->json(['message' => 'Kamar tidak ditemukan'], 404);
		}
		$updates = [];

    // Periksa setiap bidang dan tambahkan ke $updates jika tersedia dalam permintaan
    if ($request->has('class_room_id')) {
        $updates['id_class_room'] = $request->class_room_id;
    }
    if ($request->has('number_room')) {
        $updates['number_room'] = $request->number_room;
    }
    if ($request->has('number_floor')) {
        $updates['number_floor'] = $request->number_floor;
    }
    if ($request->has('room_size')) {
        $updates['room_size'] = $request->room_size;
    }
    if ($request->has('room_price')) {
        $updates['room_price'] = $request->room_price;
    }

    // Lakukan pembaruan hanya pada bidang yang ada dalam $updates
    $room->update($updates);

		return response()->json(['message'=> 'Berhasil'],200);
	}

}
