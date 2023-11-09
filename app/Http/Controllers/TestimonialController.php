<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\GetTestimonialByIdClassResource;
use App\Http\Resources\GetTestimonialRandomResource;
use App\Models\Rent;
use App\Models\StatisticReview;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function createTestimonial(Request $request)
    {
        $request->validate([
            'rent_id' => 'required|integer',
            'user_id' => 'required|string',
            'review' => 'required|string',
            'rating' => 'required|integer',
        ]);

        $testimonial = new Testimonial([
            'rent_id' => $request->rent_id,
            'user_id' => $request->user_id,
            'review' => $request->review,
            'rating' => $request->rating,
        ]);
        $testimonial->save();

        // Menghitung statistik review
    $rentId = $request->rent_id; // Anda harus menyesuaikan ini sesuai dengan kebutuhan Anda
    $statistics = Testimonial::where('rent_id', $rentId)
        ->selectRaw('COUNT(*) as total_reviews, SUM(rating) as total_rating, ROUND(AVG(rating), 1) as average_rating')
        ->first();

    // Menyimpan atau memperbarui statistik
    if ($statistics) {
        $classroomId = $testimonial->rent->room->id_class_room;
        StatisticReview::updateOrCreate(
            ['id_class_room' => $classroomId],
            [
                'total_testimonies' => $statistics->total_reviews,
                'total_rating' => $statistics->total_rating,
                'average_rating' => $statistics->average_rating,
            ]
        );
    }

        return response()->json([
            'message' => 'Berhasil'
        ], 201);
    }

    public function getTestimonial(Request $request)
    {
        $request->validate([
            'id_class_room' => 'required|integer',
        ]);

        $classroomId = $request->id_class_room;
        $testimonials = Testimonial::join('rents', 'testimonials.rent_id', '=', 'rents.id')
        ->join('rooms', 'rents.room_id', '=', 'rooms.id')
        ->select('testimonials.*') // Hanya memilih kolom-kolom dari tabel testimonials
        ->where('rooms.id_class_room', $classroomId)
        ->paginate(3);

    if ($testimonials) {
        return GetTestimonialByIdClassResource::collection($testimonials);
    }
    
    return response()->json([
        'message' => 'Testimonial not found',
    ], 404);
    }

    public function getTestimonialRandom()
    {
        $testimonials = Testimonial::inRandomOrder()->take(5)->get();
        return GetTestimonialRandomResource::collection($testimonials);
    }
}
