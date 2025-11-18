<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentLecturerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $user = auth()->user();
            
            // Get the student record
            $student = \App\Models\Student::where('user_id', $user->id)->first();
            
            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student record not found'
                ], 404);
            }

            // Get courses for the student's semester only
            $studentSemester = $student->semester;
            
            // Handle the format mismatch: student has integer (1) but courses have "Semester 1"
            $semesterString = "Semester " . $studentSemester;
            
            $semesterCourses = \App\Models\Course::where('semester', $semesterString)
                ->select('id', 'name', 'code', 'semester')
                ->get();

            // Get lecturers who teach courses in this semester
            $lecturers = \App\Models\Lecturer::whereHas('courses', function($query) use ($semesterString) {
                $query->where('semester', $semesterString);
            })
            ->with('user:id,name')
            ->get()
            ->map(function($lecturer) {
                return [
                    'id' => $lecturer->id,
                    'name' => $lecturer->user->name ?? 'Unknown',
                ];
            });

            // Let's also check what courses exist and their exact semester values
            $allCourses = \App\Models\Course::all();
            $courseDebug = $allCourses->map(function($course) {
                return [
                    'id' => $course->id,
                    'name' => $course->name,
                    'code' => $course->code,
                    'semester' => $course->semester,
                    'semester_type' => gettype($course->semester)
                ];
            });

            return response()->json([
                [
                    'semester' => $student->semester,
                    'courses' => $semesterCourses,
                    'lecturers' => $lecturers,
                    'debug' => [
                        'student_semester' => $studentSemester,
                        'student_semester_type' => gettype($studentSemester),
                        'filtered_courses_count' => $semesterCourses->count(),
                        'all_courses_debug' => $courseDebug,
                        'total_courses_in_db' => $allCourses->count(),
                        'student_semester_string' => (string)$studentSemester,
                        'student_semester_int' => (int)$studentSemester
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch student data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
