<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Lecturer;
use App\Models\Course;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getCounts()
    {
        $totalStudents = Student::count();
        $totalLecturers = Lecturer::count();
        $totalCourses = Course::count();

        return response()->json([
            'students' => $totalStudents,
            'lecturers' => $totalLecturers,
            'courses' => $totalCourses
        ]);
    }
}
