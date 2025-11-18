<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Evaluation;
use App\Models\Survey;
use App\Models\User;
use App\Models\EvaluationQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LecturerEvaluationController extends Controller
{
    /**
     * Show the lecturer evaluations dashboard
     */
    public function index()
    {
        return view('lecturer.evaluations');
    }

    /**
     * Provide the lecturer evaluation questions grouped by section.
     * Q1–Q23 numeric (1-5), Q24 is a free-text suggestion.
     */
    public function getQuestions()
    {
        // Try DB-backed questions first
        try {
            if (Schema::hasTable('evaluation_questions')) {
                $questions = EvaluationQuestion::where('active', true)
                    ->orderBy('section')
                    ->orderBy('number')
                    ->get(['id', 'section', 'number', 'text', 'type']);

                if ($questions->count() > 0) {
                    $grouped = $questions->groupBy('section')->map(function ($items) {
                        return $items->map(function ($q) {
                            return [
                                'id' => $q->id,
                                'number' => $q->number,
                                'text' => $q->text,
                                'type' => $q->type,
                            ];
                        })->values();
                    });

                    return response()->json(['sections' => $grouped]);
                }
            }
        } catch (\Throwable $e) {
            // Fall through to static fallback
        }

        // Static fallback (2 sections, Q1–Q23 scale, Q24 text)
        $sectionA = [];
        for ($i = 1; $i <= 12; $i++) {
            $sectionA[] = [
                'id' => $i,
                'number' => $i,
                'text' => "Question {$i} (Section A)",
                'type' => 'scale',
            ];
        }

        $sectionB = [];
        for ($i = 13; $i <= 23; $i++) {
            $sectionB[] = [
                'id' => $i,
                'number' => $i - 12,
                'text' => "Question {$i} (Section B)",
                'type' => 'scale',
            ];
        }
        $sectionB[] = [
            'id' => 24,
            'number' => 12,
            'text' => 'Suggestion',
            'type' => 'text',
        ];

        return response()->json([
            'sections' => [
                'Section A' => $sectionA,
                'Section B' => $sectionB,
            ]
        ]);
    }

    /**
     * Get lecturer's assigned courses
     */
    public function getCourses()
    {
        $user = Auth::user();
        $lecturer = $user->lecturer;
        if (!$lecturer) {
            return response()->json(['error' => 'Lecturer profile not found'], 404);
        }

        $courses = $lecturer->courses()
            ->select('courses.id', 'courses.code', 'courses.name', 'courses.semester')
            ->get();

        return response()->json([
            'courses' => $courses
        ]);
    }

    /**
     * Get evaluation data for the selected course/semester
     */
    public function getEvaluationData(Request $request)
    {
        try {
            $request->validate([
                'course_id' => 'nullable|exists:courses,id',
                'semester' => 'nullable|string'
            ]);

            $user = Auth::user();
            $lecturer = $user->lecturer; // Ensure we have the Lecturer profile
            if (!$lecturer) {
                return response()->json(['error' => 'Lecturer profile not found'], 404);
            }
            $courseId = $request->query('course_id');
            $semester = $request->query('semester');

            // Base query for courses taught by the lecturer
            $coursesQuery = $lecturer->courses();
            
            if ($courseId) {
                $coursesQuery->where('courses.id', $courseId);
            }
            
            if ($semester) {
                $coursesQuery->where('courses.semester', $semester);
            }

            // Get all matching courses
            $courses = $coursesQuery->get(['courses.id', 'courses.code', 'courses.name', 'courses.semester']);
            
            if ($courses->isEmpty()) {
                // If a specific course was requested but not found, return 404
                if ($courseId) {
                    return response()->json([
                        'error' => 'No courses found for the selected criteria'
                    ], 404);
                }
                // Otherwise, allow lecturer-wide evaluations (no courses, surveys, or enrolled students)
            }

            $courseIds = $courses->pluck('id');

            // Get total students enrolled in these courses (probe pivot names)
            if ($courseIds->isEmpty()) {
                $totalStudents = 0;
            } elseif (Schema::hasTable('course_user')) {
                $totalStudents = DB::table('course_user')
                    ->whereIn('course_id', $courseIds)
                    ->count();
            } elseif (Schema::hasTable('course_student')) {
                $totalStudents = DB::table('course_student')
                    ->whereIn('course_id', $courseIds)
                    ->count();
            } else {
                $totalStudents = 0;
            }

            // Get evaluation data by lecturer (course filter optional)
            $evalQuery = Evaluation::where('lecturer_id', $lecturer->id)
                ->with(['student.user:id,name', 'course:id,code,name,semester']);
            if ($courseId) {
                $evalQuery->where('course_id', $courseId);
            }
            if ($semester) {
                $evalQuery->where('semester', $semester);
            }
            $evaluations = $evalQuery->get();

            // Get survey data (always course-scoped because surveys are course-specific)
            $surveys = $courseIds->isEmpty()
                ? collect([])
                : Survey::whereIn('course_id', $courseIds)
                    ->with(['student.user:id,name', 'course:id,code,name,semester'])
                    ->get();

            // Process submitted evaluations (guard null student)
            $submitted = $evaluations->filter(function($evaluation) {
                return !is_null($evaluation->student) && !is_null(optional($evaluation->student)->user);
            })->map(function($evaluation) {
                return [
                    'id' => optional($evaluation->student->user)->id,
                    'name' => optional($evaluation->student->user)->name,
                    'submitted_at' => optional($evaluation->created_at)->toDateTimeString(),
                    'rating' => $evaluation->rating,
                    'comment' => $evaluation->comment
                ];
            })->values();

            // Get pending students (enrolled but not submitted)
            $submittedStudentIds = $evaluations->pluck('student_id')->filter()->unique();

            $pendingRaw = collect();
            if (Schema::hasTable('course_user')) {
                $pendingRaw = DB::table('course_user')
                    ->join('users', 'course_user.user_id', '=', 'users.id')
                    ->whereIn('course_user.course_id', $courseIds)
                    ->when($submittedStudentIds->isNotEmpty(), function ($q) use ($submittedStudentIds) {
                        $q->whereNotIn('users.id', $submittedStudentIds);
                    })
                    ->select('users.id', 'users.name', 'users.created_at')
                    ->distinct()
                    ->get();
            } elseif (Schema::hasTable('course_student')) {
                $pendingRaw = DB::table('course_student')
                    ->join('users', 'course_student.student_id', '=', 'users.id')
                    ->whereIn('course_student.course_id', $courseIds)
                    ->when($submittedStudentIds->isNotEmpty(), function ($q) use ($submittedStudentIds) {
                        $q->whereNotIn('users.id', $submittedStudentIds);
                    })
                    ->select('users.id', 'users.name', 'users.created_at')
                    ->distinct()
                    ->get();
            }

            $pendingStudents = $pendingRaw->map(function($student) {
                return [
                    'id' => $student->id,
                    'name' => $student->name,
                    'days_since_start' => now()->diffInDays($student->created_at)
                ];
            });

            // Calculate rating distribution
            $ratingDistribution = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
            $evaluations->each(function($eval) use (&$ratingDistribution) {
                $rating = (int) round($eval->rating);
                if (isset($ratingDistribution[$rating])) {
                    $ratingDistribution[$rating]++;
                }
            });

            // Calculate submission timeline (last 7 days)
            $timeline = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');
                $count = $evaluations->filter(function($eval) use ($date) {
                    return optional($eval->created_at)->format('Y-m-d') === $date;
                })->count();
                
                $timeline[] = [
                    'date' => now()->subDays($i)->format('M d'),
                    'count' => $count
                ];
            }

            // Calculate response rate
            $responseRate = $totalStudents > 0 
                ? round(($submitted->count() / $totalStudents) * 100, 1)
                : 0;

            // Calculate average rating
            $averageRating = $evaluations->avg('rating');

            return response()->json([
                'total_students' => $totalStudents,
                'total_responses' => $submitted->count(),
                'response_rate' => $responseRate,
                'average_rating' => $averageRating ? round($averageRating, 1) : null,
                'rating_distribution' => $ratingDistribution,
                'timeline' => $timeline,
                'submitted' => $submitted,
                'pending' => $pendingStudents,
                'courses' => $courses
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Failed to load evaluation data',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send reminder to student
     */
    public function sendReminder(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id'
        ]);

        $student = User::findOrFail($request->student_id);
        
        // In a real app, you would send an email or notification here
        // For now, we'll just return a success message
        
        return response()->json([
            'success' => true,
            'message' => 'Reminder sent to ' . $student->name
        ]);
    }

    /**
     * Export evaluation data
     */
    public function export(Request $request)
    {
        $request->validate([
            'course_id' => 'nullable|exists:courses,id',
            'semester' => 'nullable|string'
        ]);

        $user = Auth::user();
        $lecturer = $user->lecturer;
        if (!$lecturer) {
            return response()->json(['error' => 'Lecturer profile not found'], 404);
        }
        $courseId = $request->query('course_id');
        $semester = $request->query('semester');

        // Query evaluations by lecturer (optionally filter by course and semester)
        $query = Evaluation::where('lecturer_id', $lecturer->id);
        if ($courseId) {
            $query->where('course_id', $courseId);
        }
        if ($semester) {
            $query->where('semester', $semester);
        }

        $evaluations = $query->with(['student.user', 'course'])->orderByDesc('created_at')->get();

        return view('lecturer.evaluations_print', [
            'lecturer' => $lecturer,
            'semester' => $semester,
            'evaluations' => $evaluations,
        ]);
    }
}
