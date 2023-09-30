<?php

namespace App\Http\Controllers;

use App\Http\Resources\OccupantDetailResource;
use App\Models\User;
use App\Models\Occupant;
use Illuminate\Http\Request;

class OccupantController extends Controller
{
    public function createOccupant(Request $request)
    {
        $user = User::whereId(auth()->user()->id)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $request->validate([
            'name' => 'required|string',
            'date_birth' => 'required|date',
            'gender' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'occupation' => 'required|string'
        ]);

        $user->occupant()->create([
            'name' => $request->name,
            'date_birth' => $request->date_birth,
            'gender' => $request->gender,
            'phone' => $request->phone,
            'address' => $request->address,
            'occupation' => $request->occupation
        ]);

        return response()->json(['message' => 'Berhasil'], 201);
    }

    public function getOccupantDetail(Request $request) {
        $occupantId = (auth()->user()->role->user_management)? $request->query('occupant_id') : auth()->user()->occupant->id;
        if ($occupantId) {
            // Jika ada 'occupant_id' dalam parameter URL, ambil data berdasarkan ID tersebut
            $occupant = Occupant::find($occupantId);
    
            if (!$occupant) {
                return response()->json(['message' => 'Occupant not found'], 404);
            }
    
            return response()->json($occupant, 200);
        } else {
            // Jika tidak ada 'occupant_id' dalam parameter URL, ambil semua data
            $occupants = Occupant::all();
    
            return response()->json($occupants, 200);
        }
    }
}
