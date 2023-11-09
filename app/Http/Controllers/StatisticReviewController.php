<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\StatisticReview;
use Illuminate\Http\Request;

class StatisticReviewController extends Controller
{
    public function getStatisticReviewByClassRoomId(Request $request)
    {
        $request->validate([
            'id_class_room' => 'required|integer',
        ]);
        $classRoomId = $request->input('id_class_room');
        $classRoom = StatisticReview::where('id_class_room', $classRoomId);
        if (!$classRoom) {
            return response()->json(['message' => 'Class room not found'], 404);
        }
        $getClassRoom = $classRoom->select('total_testimonies', 'total_rating', 'average_rating')->first();
        return response()->json($getClassRoom, 200);
    }
}
