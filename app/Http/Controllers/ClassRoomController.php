<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClassRoomLandingPagelResource;
use App\Models\ClassRoom;
use App\Models\Facility;
use App\Models\Room;
use App\Models\RoomImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClassRoomController extends Controller
{
    // * membuat tipe kamar
    public function createClassRoom(Request $request)
    {
        $permission = auth()->user()->role->create_room;
        if (!$permission) {
            return response()->json([
                'message' => 'forbidden'
            ], 403);
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'size' => 'required|string|max:255',
            'price' => 'required|integer',
            'deposit' => 'required|integer',
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

    // * mengambil data tipe kamar
    public function getClassroom()
    {
        $classRoom = ClassRoom::select('id', 'id_facility', 'room_name', 'room_description', 'room_size', 'room_price', 'room_deposite')->get();
        return response()->json($classRoom->loadMissing(['facility:id,ac,meja,wifi,lemari,kasur,km_luar,km_dalam']), 200);
    }

    // * membuat foto kamar
    public function createImageRoom(Request $request)
    {
        $permission = auth()->user()->role->create_room;
        if (!$permission) {
            return response()->json([
                'message' => 'forbidden'
            ], 403);
        }
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

    // * mengambil foto kamar
    public function getImageRoom(Request $request)
    {
        $request->validate([
            'id' => 'required|string',
        ]);
        $classRoom = RoomImage::whereIdClassRoom($request->id)->get();
        if (!$classRoom) {
            return response()->json(['message' => 'Gambar kamar tidak ditemukan'], 404);
        }
        return response()->json($classRoom, 200);
    }

    // * mengambil detail kamar
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

    public function getClassRoomLandingPage()
    {
        $classrooms = Classroom::select('id', 'id_facility', 'room_name', 'room_description', 'room_size', 'room_price', 'room_deposite')
        ->with('firstImageRoom') // Memuat ImageRoom untuk setiap Classroom
        ->get();
        return response()->json($classrooms, 200);
    }

    public function getDetailClassroomlandingPage(Request $request)
    {
        $request->validate([
            'room' => 'required|integer',
        ]);
        $classroom = Classroom::with('facility', 'images')
        ->where('id', $request->room)
        ->select('id', 'id_facility', 'room_name', 'room_description', 'room_size', 'room_price', 'room_deposite')
        ->first();

    return response()->json($classroom, 200);
    }
}
