<?php

namespace App\Http\Controllers;

use App\Models\OcCitizenshipDoc;
use App\Models\Occupant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OcCitizenshipDocController extends Controller
{
    public function uploadKtp(Request $request)
    {

        $occupant = Occupant::whereUserId(auth()->user()->id)->first();
        if (!$occupant) {
            return response()->json(['message' => 'Penghuni tidak ditemukan'], 404);
        }

        $request->validate([
            'ktp_file' => 'required|file|mimes:png,jpg,jpeg|max:4096',
        ]);

        $ktp = $request->file('ktp_file');
        $occupant->ktpDoc()->create([
            'original_name' => $ktp->getClientOriginalName(),
            'path' => Storage::url($ktp->store('public')),
        ]);

        return response()->json(['message' => 'Berhasil'], 201);
    }

    public function getKtp(Request $request)
    {
        $request->validate([
            'id' => 'required|string',
        ]);
        $ktp = OcCitizenshipDoc::whereOccupantId($request->id)->get();
        if (!$ktp) {
            return response()->json(['message' => 'Data KTP tidak ditemukan'], 404);
        }
        return response()->json($ktp, 200);
    }
}
