<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Facility;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClassRoomController extends Controller
{
    public function createClassRoom(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'size' => 'required|string|max:255',
            'price' => 'required|integer',
            'deposit' => 'required|string|max:255',
            'facilities_ac' => 'required|boolean',
            'facilities_meja' => 'required|boolean',
            'facilities_wifi' => 'required|boolean',
            'facilities_lemari' => 'required|boolean',
            'facilities_kasur' => 'required|boolean',
            'facilities_km_luar' => 'required|boolean',
            'facilities_km_dalam' => 'required|boolean',
        ]);

        $facility = Facility::create(
            [
                'ac' => $request->facilities_ac,
                'meja' => $request->facilities_meja,
                'wifi' => $request->facilities_wifi,
                'lemari' => $request->facilities_lemari,
                'kasur' => $request->facilities_kasur,
                'km_luar' => $request->facilities_km_luar,
                'km_dalam' => $request->facilities_km_dalam,

            ]
        );

        $facility->classRoom()->create([
            'room_name' => $request->name,
            'room_size' => $request->size,
            'room_description' => $request->description,
            'room_price' => $request->price,
            'room_deposite' => $request->deposit,
        ]);

        return response()->json([
            'message' => 'Berhasil'
        ], 201);
    }

    public function getClassroom()
    {
        $classRoom = ClassRoom::select('id', 'id_facility', 'room_name', 'room_description', 'room_size', 'room_price', 'room_deposite')->get();
        return response()->json($classRoom->loadMissing(['facility:id,ac,meja,wifi,lemari,kasur,km_luar,km_dalam']), 200);
    }

    public function createImageRoom(Request $request)
    {
        $request->validate([
            'room_id' => 'required|string',
            'image_room' => 'required|file|mimes:png,jpg,jpeg|max:4096',
        ]);
        $classRoom = ClassRoom::whereId($request->room_id)->first();

        $imageRoom = $request->file('image_room');
        $classRoom->images()->create([
            'original_name' => $imageRoom->getClientOriginalName(),
            'path' => Storage::url($imageRoom->store('public')),
        ]);

        return response()->json([
            'message' => 'Berhasil'
        ], 201);
    }

    public function getDetailRoom(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
        ]);

        $Room = $request->input('id');

        $rooms = Room::where('id', $Room)->select('id', 'id_class_room', 'number_room', 'number_floor', 'room_size', 'room_price', 'status_room')
            ->with(['classRoom:id,room_name'])
            ->get();

        $facilities = Facility::whereHas('classRoom', function ($query) use ($Room) {
            $query->whereHas('rooms', function ($query) use ($Room) {
                $query->where('id', $Room);
            });
        })->select('id', 'ac', 'meja', 'wifi', 'lemari', 'kasur', 'km_luar', 'km_dalam')->get();


        $responseData = [
            'rooms' => $rooms,
            'facilities' => $facilities,
        ];
        return response()->json($responseData, 200);
    }
}
