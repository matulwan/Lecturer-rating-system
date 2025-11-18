<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evaluation;
use App\Models\Student;
use Exception;
use Illuminate\Validation\ValidationException;

class EvaluationController extends Controller
{
    /**
     * Store a newly created evaluation in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'lecturer_id' => 'required|exists:lecturers,id',
                'course_id' => 'nullable|exists:courses,id',
                // Accept either precomputed rating OR detailed answers
                'rating' => 'nullable|integer|min:1|max:5',
                'answers' => 'nullable|array', // map of question_id => 1..5 (Q1..Q23)
                'answers.*' => 'nullable|integer|min:1|max:5',
                // Q24 suggestion (free-text)
                'suggestion' => 'nullable|string|max:2000',
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
                        'message' => 'Only students can submit evaluations. Current user role: ' . ($user->role ?? 'unknown')
                    ], 403);
                }
                
                // Auto-create Student record for users with student role (only if none exists)
                try {
                    $student = Student::firstOrCreate(
                        ['user_id' => $user->id],
                        ['semester' => 1] // Default semester, can be updated later
                    );
                    
                    if ($student->wasRecentlyCreated) {
                        \Log::info("Auto-created Student record for evaluation submission, user ID: {$user->id}");
                    } else {
                        \Log::info("Found existing Student record for evaluation submission, user ID: {$user->id}");
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
                \Log::info("Restored soft-deleted Student record for evaluation submission, user ID: {$student->user_id}");
            }
            // Determine rating: compute from answers if provided, else use provided rating
            $computedRating = null;
            if (!empty($validated['answers']) && is_array($validated['answers'])) {
                $vals = array_filter(array_map(function ($v) {
                    return is_numeric($v) ? (int)$v : null;
                }, $validated['answers']));
                if (count($vals) > 0) {
                    $computedRating = (int)round(array_sum($vals) / count($vals));
                }
            }
            $finalRating = $computedRating ?? ($validated['rating'] ?? null);
            if (is_null($finalRating)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Rating is required (provide answers to compute or a numeric rating).'
                ], 422);
            }

            // Prefer suggestion over comment if provided (Q24)
            $comment = $validated['suggestion'] ?? ($validated['comment'] ?? null);

            // Prevent duplicate submissions (same student, lecturer, semester, and course match)
            $dupQuery = Evaluation::where('student_id', $student->id)
                ->where('lecturer_id', $validated['lecturer_id'])
                ->where('semester', $validated['semester']);
            if (array_key_exists('course_id', $validated) && !is_null($validated['course_id'])) {
                $dupQuery->where('course_id', $validated['course_id']);
            } else {
                $dupQuery->whereNull('course_id');
            }
            if ($dupQuery->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already submitted an evaluation for this lecturer and semester.'
                ], 409);
            }

            $evaluation = Evaluation::create([
                'student_id' => $student->id,
                'lecturer_id' => $validated['lecturer_id'],
                'course_id' => $validated['course_id'] ?? null,
                'rating' => $finalRating,
                'comment' => $comment,
                'semester' => $validated['semester'],
            ]);

            return response()->json(['message' => 'Evaluation submitted successfully', 'evaluation' => $evaluation], 201);
        } catch (Exception $e) {
            \Log::error('Evaluation submission failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit evaluation',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
