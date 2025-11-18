<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CLORating;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Exception;

class CLORatingController extends Controller
{
    /**
     * Store CLO ratings from students
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'course_id' => 'required|exists:courses,id',
                'ratings' => 'required|array',
                'ratings.*.clo_number' => 'required|integer|min:1',
                'ratings.*.rating' => 'required|integer|min:1|max:5',
            ]);

            $user = Auth::user();
            if ($user->role !== 'student') {
                return response()->json(['message' => 'Only students can submit CLO ratings'], 403);
            }

            $courseId = $request->course_id;
            $ratings = $request->ratings;

            // Delete existing ratings for this student and course
            CLORating::where('course_id', $courseId)
                    ->where('student_id', $user->id)
                    ->delete();

            // Insert new ratings
            foreach ($ratings as $rating) {
                CLORating::create([
                    'course_id' => $courseId,
                    'student_id' => $user->id,
                    'clo_number' => $rating['clo_number'],
                    'rating' => $rating['rating'],
                ]);
            }

            return response()->json(['message' => 'CLO ratings submitted successfully']);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit CLO ratings',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get CLO ratings for a course
     */
    public function getCourseRatings($courseId)
    {
        try {
            $course = Course::findOrFail($courseId);
            
            $ratings = CLORating::where('course_id', $courseId)
                              ->with('student:id,name')
                              ->get()
                              ->groupBy('clo_number');

            $statistics = [];
            foreach ($ratings as $cloNumber => $cloRatings) {
                $statistics[$cloNumber] = [
                    'average_rating' => round($cloRatings->avg('rating'), 2),
                    'total_responses' => $cloRatings->count(),
                    'ratings_distribution' => $cloRatings->groupBy('rating')->map->count()
                ];
            }

            return response()->json([
                'course' => $course,
                'statistics' => $statistics,
                'total_students_rated' => CLORating::where('course_id', $courseId)->distinct('student_id')->count()
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve CLO ratings',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get student's own CLO ratings for a course
     */
    public function getStudentRatings($courseId)
    {
        try {
            $user = Auth::user();
            if ($user->role !== 'student') {
                return response()->json(['message' => 'Only students can view their ratings'], 403);
            }

            $ratings = CLORating::where('course_id', $courseId)
                               ->where('student_id', $user->id)
                               ->get()
                               ->keyBy('clo_number');

            return response()->json($ratings);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve student ratings',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
