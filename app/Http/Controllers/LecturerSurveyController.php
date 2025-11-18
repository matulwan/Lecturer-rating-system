<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Survey;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class LecturerSurveyController extends Controller
{
    /**
     * Show the lecturer surveys dashboard
     */
    public function index()
    {
        return view('lecturer.surveys');
    }

    /**
     * Get survey data for the selected course/semester
     */
    public function getSurveyData(Request $request)
    {
        $request->validate([
            'course_id' => 'nullable|exists:courses,id',
            'semester' => 'nullable|string'
        ]);

        $user = Auth::user();
        $lecturer = $user->lecturer; // Use lecturer profile
        if (!$lecturer) {
            return response()->json(['error' => 'Lecturer profile not found'], 404);
        }
        $courseId = $request->query('course_id');
        $semester = $request->query('semester');

        // Base query for courses (via lecturer profile)
        $coursesQuery = $lecturer->courses();
        
        if ($courseId) {
            $coursesQuery->where('courses.id', $courseId);
        }
        
        if ($semester) {
            $coursesQuery->where('courses.semester', $semester);
        }

        // Get all matching courses (note: Course uses 'course_outcomes' field)
        $courses = $coursesQuery->get(['courses.id', 'courses.code', 'courses.name', 'courses.semester', 'courses.course_outcomes']);
        
        if ($courses->isEmpty()) {
            return response()->json([
                'error' => 'No courses found for the selected criteria'
            ], 404);
        }

        $courseIds = $courses->pluck('id');

        // Get total students enrolled in these courses (fallback if pivot doesn't exist)
        if (Schema::hasTable('course_user')) {
            $totalStudents = DB::table('course_user')
                ->whereIn('course_id', $courseIds)
                ->count();
        } else if (Schema::hasTable('course_student')) {
            $totalStudents = DB::table('course_student')
                ->whereIn('course_id', $courseIds)
                ->count();
        } else {
            // Fallback: unique students from submitted surveys
            $totalStudents = Survey::whereIn('course_id', $courseIds)->distinct('student_id')->count('student_id');
        }

        // Get survey data with CLO ratings
        $surveys = Survey::whereIn('course_id', $courseIds)
            ->with(['student.user', 'course:id,code,name,course_outcomes'])
            ->get();

        // Process submitted surveys
        $submitted = $surveys->map(function($survey) {
            return [
                'id' => optional($survey->student)->id,
                'name' => optional(optional($survey->student)->user)->name,
                'submitted_at' => $survey->created_at->toDateTimeString(),
                'comment' => $survey->comment
            ];
        });

        // Get pending students (enrolled but not submitted)
        $submittedStudentIds = $surveys->pluck('student_id')->unique();

        // Pending students: enrolled via pivot table 'course_user' but not in submitted list
        $pendingStudentsRaw = collect();
        if (Schema::hasTable('course_user')) {
            $pendingStudentsRaw = DB::table('course_user')
                ->join('users', 'course_user.user_id', '=', 'users.id')
                ->whereIn('course_user.course_id', $courseIds)
                ->when($submittedStudentIds->isNotEmpty(), function ($q) use ($submittedStudentIds) {
                    $q->whereNotIn('users.id', $submittedStudentIds);
                })
                ->select('users.id', 'users.name', 'users.created_at')
                ->distinct()
                ->get();
        } elseif (Schema::hasTable('course_student')) {
            $pendingStudentsRaw = DB::table('course_student')
                ->join('users', 'course_student.student_id', '=', 'users.id')
                ->whereIn('course_student.course_id', $courseIds)
                ->when($submittedStudentIds->isNotEmpty(), function ($q) use ($submittedStudentIds) {
                    $q->whereNotIn('users.id', $submittedStudentIds);
                })
                ->select('users.id', 'users.name', 'users.created_at')
                ->distinct()
                ->get();
        }
        $pendingStudents = $pendingStudentsRaw->map(function($student) {
            return [
                'id' => $student->id,
                'name' => $student->name,
                'days_since_start' => now()->diffInDays(Carbon::parse($student->created_at))
            ];
        });

        // Process CLO analytics
        $cloAnalytics = [];
        
        foreach ($courses as $course) {
            // Parse CLOs from course_outcomes field
            $clos = $this->parseLearningOutcomes($course->course_outcomes);
            $courseSurveys = $surveys->where('course_id', $course->id);
            
            $courseCloAnalytics = [];
            
            foreach ($clos as $index => $clo) {
                // Extract ratings from clo_ratings array by index
                $cloRatings = [];
                foreach ($courseSurveys as $sv) {
                    $ratings = $sv->clo_ratings;
                    if (is_array($ratings)) {
                        $key = $index; // zero-based index
                        // Support either zero-indexed arrays or associative arrays like ['clo_1'=>...]
                        if (array_key_exists($key, $ratings) && is_numeric($ratings[$key])) {
                            $cloRatings[] = (float)$ratings[$key];
                        } elseif (array_key_exists('clo_' . ($index + 1), $ratings) && is_numeric($ratings['clo_' . ($index + 1)])) {
                            $cloRatings[] = (float)$ratings['clo_' . ($index + 1)];
                        }
                    }
                }
                
                $totalRatings = count($cloRatings);
                $averageRating = $totalRatings > 0 ? array_sum($cloRatings) / $totalRatings : null;
                
                $courseCloAnalytics[] = [
                    'clo_number' => $index + 1,
                    'description' => $clo,
                    'average_rating' => $averageRating ? round($averageRating, 1) : null,
                    'total_ratings' => $totalRatings,
                    'rating_distribution' => $this->calculateRatingDistribution($cloRatings)
                ];
            }
            
            $cloAnalytics[$course->id] = $courseCloAnalytics;
        }

        // Calculate submission timeline (last 7 days)
        $timeline = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $count = $surveys->filter(function($survey) use ($date) {
                return $survey->created_at->format('Y-m-d') === $date;
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

        return response()->json([
            'total_students' => $totalStudents,
            'total_responses' => $submitted->count(),
            'response_rate' => $responseRate,
            'timeline' => $timeline,
            'submitted' => $submitted,
            'pending' => $pendingStudents,
            'clo_analytics' => $cloAnalytics,
            'courses' => $courses
        ]);
    }

    /**
     * Parse learning outcomes from string to array
     */
    private function parseLearningOutcomes($learningOutcomes)
    {
        if (empty($learningOutcomes)) {
            return [];
        }

        // If already an array (due to Eloquent cast), normalize and return
        if (is_array($learningOutcomes)) {
            return array_values($learningOutcomes);
        }

        // Try to parse as JSON first
        $decoded = json_decode($learningOutcomes, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            if (is_array($decoded)) {
                return array_values($decoded);
            } elseif (is_string($decoded)) {
                return [$decoded];
            }
        }

        // If not valid JSON, try to split by newlines
        $lines = preg_split('/\r\n|\r|\n/', $learningOutcomes);
        $lines = array_filter($lines, function($line) {
            return trim($line) !== '';
        });

        return array_values($lines);
    }

    /**
     * Calculate rating distribution for CLOs
     */
    private function calculateRatingDistribution($ratings)
    {
        $distribution = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
        
        foreach ($ratings as $rating) {
            $rounded = (int) round($rating);
            if (isset($distribution[$rounded])) {
                $distribution[$rounded]++;
            }
        }
        
        return $distribution;
    }

    /**
     * Export survey data
     */
    public function export(Request $request)
    {
        $request->validate([
            'course_id' => 'nullable|exists:courses,id',
            'semester' => 'nullable|string'
        ]);

        $user = Auth::user();
        $lecturer = $user->lecturer ?? $user; // tolerate either structure
        $courseId = $request->query('course_id');
        $semester = $request->query('semester');

        $query = Survey::whereHas('course', function($q) use ($lecturer, $courseId, $semester) {
            // course belongs to lecturer
            $q->when(property_exists($lecturer, 'id'), function($qq) use ($lecturer) {
                $qq->where('lecturer_id', $lecturer->id);
            });
            if ($courseId) {
                $q->where('id', $courseId);
            }
            if ($semester) {
                $q->where('semester', $semester);
            }
        });

        $surveys = $query->with(['student.user', 'course'])->orderByDesc('created_at')->get();

        return view('lecturer.surveys_print', [
            'lecturer' => $lecturer,
            'semester' => $semester,
            'surveys' => $surveys,
        ]);
    }
}
