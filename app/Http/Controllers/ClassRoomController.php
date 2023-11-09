<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\AllClassRoomLandingPageResource;
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

    // * Edit tipe kamar
    public function updateClassRoom(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'name' => 'string|max:255',
            'description' => 'string',
            'size' => 'string|max:255',
            'price' => 'integer',
            'deposit' => 'integer',
            'facilities_ac' => 'boolean',
            'facilities_meja' => 'boolean',
            'facilities_wifi' => 'boolean',
            'facilities_lemari' => 'boolean',
            'facilities_kasur' => 'boolean',
            'facilities_km_luar' => 'boolean',
            'facilities_km_dalam' => 'boolean',
        ]);

        $classRoom = ClassRoom::find($request->id);

        $facility = ClassRoom::find($request->id)->facility;

        // * update fasilitas
        if (!is_null($request->facilities_ac)) {
            $facility->update([
                'ac' => $request->facilities_ac,
            ]);
        }

        if (!is_null($request->facilities_meja)) {
            $facility->update([
                'meja' => $request->facilities_meja,
            ]);
        }

        if (!is_null($request->facilities_wifi)) {
            $facility->update([
                'wifi' => $request->facilities_wifi,
            ]);
        }

        if (!is_null($request->facilities_lemari)) {
            $facility->update([
                'lemari' => $request->facilities_lemari,
            ]);
        }

        if (!is_null($request->facilities_kasur)) {
            $facility->update([
                'kasur' => $request->facilities_kasur,
            ]);
        }

        if (!is_null($request->facilities_km_luar)) {
            $facility->update([
                'km_luar' => $request->facilities_km_luar,
            ]);
        }

        if (!is_null($request->facilities_km_dalam)) {
            $facility->update([
                'km_dalam' => $request->facilities_km_dalam,
            ]);
        }

        // * update data kamar
        if (!is_null($request->name)) {
            $classRoom->update([
                'room_name' => $request->name,
            ]);
        }

        if (!is_null($request->size)) {
            $classRoom->update([
                'room_size' => $request->size,
            ]);
        }

        if (!is_null($request->description)) {
            $classRoom->update([
                'room_description' => $request->description,
            ]);
        }

        if (!is_null($request->price)) {
            $classRoom->update([
                'room_price' => $request->price,
            ]);
        }

        if (!is_null($request->deposit)) {
            $classRoom->update([
                'room_deposite' => $request->deposit,
            ]);
        }
        return response()->json([
            'message' => 'Update successful'
        ], 200);
    }

    // * hapus tipe kamar
    public function deleteClassRoom(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
        ]);

        $classRoom = ClassRoom::find($request->id);
        $facility = ClassRoom::find($request->id)->facility;

        if (!$classRoom) {
            return response()->json(['message' => 'Tipe kamar tidak ditemukan'], 404);
        }
        $classRoom->delete();
        $facility->delete();
        return response()->json([
            'message' => 'Tipe kamar berhasil dihapus'
        ], 200);
    }

    // * mengambil data tipe kamar
    public function getClassroom()
    {
        $classRoom = ClassRoom::select('id', 'id_facility', 'room_name', 'room_description', 'room_size', 'room_price', 'room_deposite')->get();
        return response()->json($classRoom->loadMissing('facility:id,ac,meja,wifi,lemari,kasur,km_luar,km_dalam'), 200);
    }

    // * mengambil detail data tipe kamar
    public function getDetailClassroom(Request $request)
    {
        $request->validate([
            'id' => 'required|string',
        ]);

        $classroom = ClassRoom::where('id', $request->id)
            ->select('id', 'id_facility', 'room_name', 'room_description', 'room_size', 'room_price', 'room_deposite')
            ->first();

        $classroomImages = RoomImage::where('id_class_room', $classroom->id)->get();
        $facility = Facility::where('id', $classroom->id_facility)->get();

        return response()->json([
            'classroom' => $classroom,
            'images' => $classroomImages,
            'facility' => $facility
        ], 200);
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

    // * mengedit foto kamar
    public function updateImageRoom(Request $request)
    {
        $request->validate([
            'id' => 'required|string',
            'room_id' => 'required|string',
            'image_room' => 'required|file|mimes:png,jpg,jpeg|max:4096',
        ]);

        $currentImages = RoomImage::whereId($request->id)->first();

        if ($currentImages) {
            Storage::delete(str_replace('storage', 'public', $currentImages->path));
            $currentImages->delete();
        }
        $imageRoom = $request->file('image_room');
        $newImage = new RoomImage([
            'original_name' => $imageRoom->getClientOriginalName(),
            'path' => Storage::url($imageRoom->store('public')),
            'id_class_room' => $request->room_id, // Masukkan room_id
        ]);

        $newImage->save();

        return response()->json([
            'message' => 'Berhasil'
        ], 200);
    }

    // * menghapus foto kamar
    public function deleteImageRoom(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
        ]);

        $image = RoomImage::find($request->id);
        if (!$image) {
            return response()->json(['message' => 'Foto kamar tidak ditemukan'], 404);
        }

        Storage::delete(str_replace('storage', 'public', $image->path));
        $image->delete();

        return response()->json([
            'message' => 'Foto kamar berhasil dihapus'
        ], 200);
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
        $classrooms = Classroom::get();
        return AllClassRoomLandingPageResource::collection($classrooms);
    }

    public function getDetailClassroomlandingPage(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
        ]);
        $classroom = Classroom::where('id', $request->id)
            ->select('id', 'id_facility', 'room_name', 'room_description', 'room_size', 'room_price', 'room_deposite')
            ->first();

        return response()->json($classroom, 200);
    }

    public function getFacilityLandingPage(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
        ]);
        $facilities = Facility::where('id', $request->id)->select('id', 'ac', 'meja', 'wifi', 'lemari', 'kasur', 'km_luar', 'km_dalam')->first();
        return response()->json($facilities, 200);
    }
}
