<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Occupant;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function createTestimonial(Request $request)
    {
        $request->validate([
            'occupant_id' => 'required|string',
            'rating' => 'required|integer',
            'description' => 'required|string',
        ]);
        $occupant = Occupant::whereId($request->occupant_id)->first();
        if (!$occupant) {
            return response()->json([
                'message' => 'Occupant not found'
            ], 404);
        }
        $testimonial = $occupant->testimonial()->create([
            'rating' => $request->rating,
            'description' => $request->description,
        ]);
        return response()->json([
            'message' => 'Berhasil'
        ], 201);
    }


}
