<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Lecturer;

class LecturerCourseController extends Controller
{
    /**
     * Display a listing of the courses for the authenticated lecturer.
     */
    public function index()
    {
        try {
            $user = Auth::user();
            if ($user->role !== 'lecturer') {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            $lecturer = $user->lecturer;
            if (!$lecturer) {
                return response()->json(['message' => 'Lecturer profile not found'], 404);
            }

            $courses = $lecturer->courses; // Assuming Lecturer model has a 'courses' relationship

            // Attach a dynamic students_count based on semester match
            $courses = $courses->map(function ($course) {
                // Normalize semester to integer (handles values like "4" or "Semester 4")
                $semesterRaw = $course->semester;
                if (is_numeric($semesterRaw)) {
                    $semesterInt = (int) $semesterRaw;
                } else {
                    $semesterInt = null;
                    if (is_string($semesterRaw)) {
                        if (preg_match('/\\d+/', $semesterRaw, $m)) {
                            $semesterInt = (int) $m[0];
                        }
                    }
                }

                $studentsCount = 0;
                if (!is_null($semesterInt)) {
                    $studentsCount = \App\Models\Student::where('semester', $semesterInt)->count();
                }
                // Make sure the attribute is visible in the JSON
                $course->setAttribute('students_count', $studentsCount);
                return $course;
            });

            return response()->json(['courses' => $courses]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve courses for lecturer',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get evaluation and survey statistics for the authenticated lecturer.
     */
    public function getStatistics()
    {
        try {
            $user = Auth::user();
            if ($user->role !== 'lecturer') {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            $lecturer = $user->lecturer;
            if (!$lecturer) {
                return response()->json(['message' => 'Lecturer profile not found'], 404);
            }

            // Get all evaluations for this lecturer (lecturer-only evaluations allowed, course_id may be null)
            $evaluations = \App\Models\Evaluation::where('lecturer_id', $lecturer->id)->get();

            // Get all surveys for this lecturer
            $surveys = \App\Models\Survey::whereHas('course', function($query) use ($lecturer) {
                $query->whereHas('lecturers', function($q) use ($lecturer) {
                    $q->where('lecturer_id', $lecturer->id);
                });
            })->get();

            // Calculate evaluation statistics
            $evaluationStats = [
                'average_rating' => $evaluations->count() > 0 ? round($evaluations->avg('rating'), 1) : null,
                'responses' => $evaluations->count(),
                'last_updated' => $evaluations->count() > 0 ? $evaluations->max('updated_at') : null
            ];

            // Calculate survey statistics (support legacy question_* fields and new clo_ratings array)
            $surveyStats = [
                'average_rating' => null,
                'responses' => $surveys->count(),
                'last_updated' => $surveys->count() > 0 ? $surveys->max('updated_at') : null
            ];

            if ($surveys->count() > 0) {
                // Try legacy fields first
                $avgQ1 = $surveys->avg('question_1_rating');
                $avgQ2 = $surveys->avg('question_2_rating');
                $avgQ3 = $surveys->avg('question_3_rating');
                $legacyFieldsPresent = !is_null($avgQ1) || !is_null($avgQ2) || !is_null($avgQ3);

                if ($legacyFieldsPresent) {
                    // Only average over fields that exist
                    $values = array_values(array_filter([$avgQ1, $avgQ2, $avgQ3], function($v){ return !is_null($v); }));
                    if (count($values) > 0) {
                        $surveyStats['average_rating'] = round(array_sum($values) / count($values), 1);
                    }
                } else {
                    // Fallback: average all numeric CLO ratings across surveys
                    $allRatings = [];
                    foreach ($surveys as $s) {
                        $clo = $s->clo_ratings;
                        if (is_array($clo)) {
                            foreach ($clo as $val) {
                                if (is_numeric($val)) {
                                    $allRatings[] = (float)$val;
                                }
                            }
                        }
                    }
                    if (count($allRatings) > 0) {
                        $surveyStats['average_rating'] = round(array_sum($allRatings) / count($allRatings), 1);
                    }
                }
            }

            return response()->json([
                'evaluation' => $evaluationStats,
                'survey' => $surveyStats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Not needed for this specific problem, lecturers don't create courses
        return response()->json(['message' => 'Not implemented'], 405);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Not needed for this specific problem, lecturers view their own courses index
        return response()->json(['message' => 'Not implemented'], 405);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Not needed for this specific problem, lecturers don't update courses directly
        return response()->json(['message' => 'Not implemented'], 405);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Not needed for this specific problem, lecturers don't delete courses
        return response()->json(['message' => 'Not implemented'], 405);
    }
}
