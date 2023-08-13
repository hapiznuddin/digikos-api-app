<?php

namespace App\Http\Controllers;

use App\Http\Resources\OccupantDetailResource;
use App\Models\User;
use App\Models\Occupant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OccupantController extends Controller
{
    public function createOccupant(Request $request)
    {
        $user = User::whereId(auth()->user()->id)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $request->validate([
            'phone' => 'required|string',
            'address' => 'required|string',
        ]);

        $user->occupant()->create([
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return response()->json(['message' => 'Berhasil'], 201);
    }

    public function getOccupantDetail(Request $request) {
        $occupantId = (auth()->user()->role->user_management)? $request->query('occupant_id') : auth()->user()->occupant->id;
        $occupant = Occupant::whereId($occupantId)->first();

        return new OccupantDetailResource($occupant);

    }
}
