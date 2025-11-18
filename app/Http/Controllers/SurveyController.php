<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survey;
use App\Models\Student;
use App\Models\Course;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Schema;

class SurveyController extends Controller
{
    /**
     * Debug endpoint to check user status
     */
    public function debugUserStatus()
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }
        
        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->first();
        
        return response()->json([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_role' => $user->role,
            'has_student_record' => $student ? true : false,
            'student_id' => $student ? $student->id : null,
            'student_semester' => $student ? $student->semester : null
        ]);
    }
    /**
     * Store a newly created survey in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'course_id' => 'required|exists:courses,id',
                'clo_ratings' => 'required|array',
                'clo_ratings.*' => 'required|integer|min:1|max:5',
                'comment' => 'nullable|string|max:1000',
                'semester' => 'required|string|max:255',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }

        try {
            // Check if user is authenticated
            if (!auth()->check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            // Get the authenticated user's student ID (including soft-deleted records)
            $student = Student::withTrashed()->where('user_id', auth()->id())->first();
            
            if (!$student) {
                // Check if the user has student role
                $user = auth()->user();
                if ($user->role !== 'student') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Only students can submit surveys. Current user role: ' . ($user->role ?? 'unknown')
                    ], 403);
                }
                
                // Auto-create Student record for users with student role (only if none exists)
                try {
                    $student = Student::firstOrCreate(
                        ['user_id' => $user->id],
                        ['semester' => 1] // Default semester, can be updated later
                    );
                    
                    if ($student->wasRecentlyCreated) {
                        \Log::info("Auto-created Student record for user ID: {$user->id}");
                    } else {
                        \Log::info("Found existing Student record for user ID: {$user->id}");
                    }
                } catch (Exception $createException) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to create student record. Please contact administrator.',
                        'error' => $createException->getMessage()
                    ], 500);
                }
            } elseif ($student->trashed()) {
                // If student record is soft-deleted, restore it
                $student->restore();
                \Log::info("Restored soft-deleted Student record for user ID: {$student->user_id}");
            }

            // Prevent duplicate submissions (same student, course, semester)
            $alreadySubmitted = Survey::where('student_id', $student->id)
                ->where('course_id', $validated['course_id'])
                ->where('semester', $validated['semester'])
                ->exists();
            if ($alreadySubmitted) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already submitted this survey for the selected course and semester.'
                ], 409);
            }

            // Prepare base attributes
            $attributes = [
                'student_id' => $student->id,
                'course_id' => $validated['course_id'],
                'comment' => $validated['comment'],
                'semester' => $validated['semester'],
            ];

            // Prefer saving full dynamic CLO ratings if the column exists
            if (Schema::hasColumn('surveys', 'clo_ratings')) {
                $attributes['clo_ratings'] = $validated['clo_ratings'];
            } elseif (
                Schema::hasColumn('surveys', 'question_1_rating') &&
                Schema::hasColumn('surveys', 'question_2_rating') &&
                Schema::hasColumn('surveys', 'question_3_rating')
            ) {
                // Map first three entries to legacy fixed columns
                $ratings = array_values($validated['clo_ratings']);
                $attributes['question_1_rating'] = isset($ratings[0]) ? (int) $ratings[0] : 0;
                $attributes['question_2_rating'] = isset($ratings[1]) ? (int) $ratings[1] : 0;
                $attributes['question_3_rating'] = isset($ratings[2]) ? (int) $ratings[2] : 0;
            }

            $survey = Survey::create($attributes);

            return response()->json(['message' => 'Survey submitted successfully', 'survey' => $survey], 201);
        } catch (Exception $e) {
            \Log::error('Survey submission failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit survey',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get course CLOs for survey
     */
    public function getCourseCLOs($courseId)
    {
        try {
            \Log::info("Fetching CLOs for course ID: " . $courseId);
            
            $course = Course::findOrFail($courseId);
            \Log::info("Course found: " . $course->name . " (ID: " . $course->id . ")");
            \Log::info("Raw course_outcomes field: " . $course->getRawOriginal('course_outcomes'));
            \Log::info("Parsed course_outcomes: " . json_encode($course->course_outcomes));
            
            // Check if course_outcomes column exists and has data
            $clos = [];
            if ($course->course_outcomes && is_array($course->course_outcomes)) {
                $clos = $course->course_outcomes;
                \Log::info("Found " . count($clos) . " CLOs from course_outcomes");
            }
            
            // If no CLOs found, return 204 (No Content) to indicate lecturer hasn't set CLOs
            if (empty($clos)) {
                \Log::info("No CLOs found for course ID {$course->id}; returning 204 No Content");
                return response()->noContent(204);
            } else {
                // Ensure each CLO has proper structure
                $clos = array_values(array_map(function($clo, $index) {
                    // Handle different possible formats
                    if (is_string($clo)) {
                        return [
                            'id' => $index + 1,
                            'description' => $clo
                        ];
                    } elseif (is_array($clo)) {
                        return [
                            'id' => $clo['id'] ?? ($index + 1),
                            'description' => $clo['description'] ?? $clo['text'] ?? $clo['outcome'] ?? 'Learning outcome ' . ($index + 1)
                        ];
                    }
                    return [
                        'id' => $index + 1,
                        'description' => 'Learning outcome ' . ($index + 1)
                    ];
                }, $clos, array_keys($clos)));
            }
            
            \Log::info("Final CLOs count: " . count($clos));
            \Log::info("Final CLOs structure: " . json_encode($clos));
            
            return response()->json([
                'success' => true,
                'clos' => $clos,
                'course' => [
                    'id' => $course->id,
                    'name' => $course->name,
                    'code' => $course->code
                ]
            ]);
        } catch (Exception $e) {
            \Log::error("Error fetching CLOs: " . $e->getMessage());
            \Log::error("Stack trace: " . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load course learning outcomes',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
