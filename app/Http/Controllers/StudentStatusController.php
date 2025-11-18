<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Evaluation;
use App\Models\Survey;

class StudentStatusController extends Controller
{
    /**
     * Return completion status for the authenticated student for the current semester.
     */
    public function status(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $student = Student::where('user_id', $user->id)->first();
        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Student record not found'], 404);
        }

        // Courses use string like "Semester 1"; evaluations/surveys store semester as string as well.
        $semesterString = 'Semester ' . $student->semester;

        // Evaluations status
        $evaluationQuery = Evaluation::where('student_id', $student->id)
            ->where('semester', $semesterString);
        $evaluationDone = $evaluationQuery->exists();
        $evaluatedLecturerIds = (clone $evaluationQuery)
            ->whereNotNull('lecturer_id')
            ->distinct()
            ->pluck('lecturer_id')
            ->filter()
            ->values();

        // Surveys status (course-scoped)
        $surveyQuery = Survey::where('student_id', $student->id)
            ->where('semester', $semesterString);
        $surveyDone = $surveyQuery->exists();
        $surveyedCourseIds = (clone $surveyQuery)
            ->whereNotNull('course_id')
            ->distinct()
            ->pluck('course_id')
            ->filter()
            ->values();

        return response()->json([
            'success' => true,
            'semester' => $semesterString,
            'evaluation_done' => $evaluationDone,
            'survey_done' => $surveyDone,
            // New fields to help frontend disable completed items
            'evaluations_count' => (int) ($evaluationDone ? $evaluationQuery->count() : 0),
            'surveys_count' => (int) ($surveyDone ? $surveyQuery->count() : 0),
            'evaluated_lecturer_ids' => $evaluatedLecturerIds,
            'surveyed_course_ids' => $surveyedCourseIds,
        ]);
    }
}
