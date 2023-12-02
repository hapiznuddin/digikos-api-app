<?php

namespace App\Http\Controllers;

use App\Models\FamilyDoc;
use App\Models\OcCitizenshipDoc;
use App\Models\Occupant;
use App\Models\ProfilePic;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OcCitizenshipDocController extends Controller
{
    // * Upload KTP
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
            'id' => 'nullable|string',
            'user_id' => 'nullable|string',
        ]);

        $id = $request->input('id');
        $userId = $request->input('user_id');

        if ($id) {
            $ktp = OcCitizenshipDoc::where('occupant_id', $id)->first();
            if (!$ktp) {
                return response()->json(['message' => 'Data KTP tidak ditemukan'], 404);
            }
            return response()->json($ktp, 200);
        }
        if ($userId) {
            $user = User::find($userId)->occupant()->first();
            if (!$user) {
                return response()->json(['message' => 'User tidak ditemukan'], 404);
            }
            $ktp = OcCitizenshipDoc::where('occupant_id', $user->id)->first();
            if (!$ktp) {
                return response()->json(['message' => 'Data KTP tidak ditemukan'], 404);
            }
            return response()->json($ktp, 200);
        }
        return response()->json(['message' => 'Parameter tidak valid'], 400);
    }


    // * Upload KK
    public function createFamilyDoc(Request $request)
    {
        $occupant = Occupant::whereUserId(auth()->user()->id)->first();
        if (!$occupant) {
            return response()->json(['message' => 'Penghuni tidak ditemukan'], 404);
        }
        $request->validate([
            'family_doc' => 'required|file|mimes:png,jpg,jpeg|max:4096',
        ]);
        $familyDoc = $request->file('family_doc');
        $occupant->familyDoc()->create([
            'original_name' => $familyDoc->getClientOriginalName(),
            'path' => Storage::url($familyDoc->store('public')),
        ]);
        return response()->json(['message' => 'Berhasil'], 201);
    }

    public function getFamilyDoc(Request $request)
    {
        $request->validate([
            'id' => 'nullable|string',
            'user_id' => 'nullable|string',
        ]);

        $id = $request->input('id');
        $userId = $request->input('user_id');

        if ($id) {
            $familyDoc = FamilyDoc::where('occupant_id', $id)->first();
            if (!$familyDoc) {
                return response()->json(['message' => 'File KK tidak ditemukan'], 404);
            }
            return response()->json($familyDoc, 200);
        }
        if ($userId) {
            $user = User::find($userId)->occupant()->first();
            if (!$user) {
                return response()->json(['message' => 'User tidak ditemukan'], 404);
            }
            $familyDoc = FamilyDoc::where('occupant_id', $user->id)->first();
            if (!$familyDoc) {
                return response()->json(['message' => 'File KK tidak ditemukan'], 404);
            }
            return response()->json($familyDoc, 200);
        }
        return response()->json(['message' => 'Parameter tidak valid'], 400);
    }


    // * Upload Profile Picture
    public function createProfilePic(Request $request)
    {
        $user = User::whereId(auth()->user()->id)->first();
        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }
        $request->validate([
            'profile_pic' => 'required|file|mimes:png,jpg,jpeg|max:4096',
        ]);
        $profilePic = $request->file('profile_pic');
        $user->profilePic()->create([
            'original_name' => $profilePic->getClientOriginalName(),
            'path' => Storage::url($profilePic->store('public')),
        ]);
        return response()->json(['message' => 'Berhasil'], 201);
    }

    public function updateProfilePic(Request $request)
    {
        $user = User::whereId(auth()->user()->id)->first();

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        $request->validate([
            'profile_pic' => 'required|file|mimes:png,jpg,jpeg|max:4096',
        ]);
        $currentProfilePic = $user->profilePic;

        if ($currentProfilePic) {
            // Hapus gambar profil yang lama dari penyimpanan
            Storage::delete($currentProfilePic->path);
            $currentProfilePic->delete();
        }

        $newProfilePic = $request->file('profile_pic');

        // Buat data gambar profil yang baru
        $user->profilePic()->create([
            'original_name' => $newProfilePic->getClientOriginalName(),
            'path' => Storage::url($newProfilePic->store('public')),
        ]);
        return response()->json(['message' => 'Profile picture updated'], 201);
    }

    public function getProfilePic()
    {
        $user = auth()->user();
        $profilePic = $user->profilePic;

        if (!$profilePic) {
            return response()->json(['message' => 'Profile picture not found'], 404);
        }
        return response()->json($profilePic, 200);
    }
}
