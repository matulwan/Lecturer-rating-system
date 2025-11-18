<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Lecturer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Validation\ValidationException;

class LecturerController extends Controller
{
    /**
     * Display a listing of lecturers
     */
    public function index()
    {
        try {
            $lecturers = Lecturer::with('user')->get()->map(function ($lecturer) {
                return [
                    'id' => $lecturer->id,
                    'name' => $lecturer->user ? $lecturer->user->name : 'N/A',
                    'ic_number' => $lecturer->user ? $lecturer->user->user_code : 'N/A',
                    'salary_number' => $lecturer->salary_number
                ];
            });

            return response()->json($lecturers);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve lecturers',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created lecturer
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'ic_number' => 'required|string|unique:users,user_code',
                'salary_number' => 'required|string',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $request->name,
                'user_code' => $request->ic_number,
                'ic_number' => $request->ic_number, // Also populate ic_number field
                'role' => 'lecturer',
                'password' => Hash::make($request->salary_number), // Password is salary number
            ]);

            Lecturer::create([
                'user_id' => $user->id,
                'salary_number' => $request->salary_number,
            ]);

            DB::commit();

            return response()->json(['message' => 'Lecturer added successfully']);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to add lecturer',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified lecturer
     */
    public function show($id)
    {
        try {
            $lecturer = Lecturer::with('user')->findOrFail($id);
            
            // Check if request expects JSON (for AJAX calls)
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'id' => $lecturer->id,
                        'name' => $lecturer->user ? $lecturer->user->name : 'N/A',
                        'ic_number' => $lecturer->user ? $lecturer->user->user_code : 'N/A',
                        'salary_number' => $lecturer->salary_number
                    ]
                ]);
            }
            
            // Return view for regular requests
            return view('admin.lecturer.show', compact('lecturer'));
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lecturer not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified lecturer
     */
    public function update(Request $request, $id)
    {
        $lecturer = Lecturer::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'ic_number' => 'required|string|unique:users,user_code,' . $lecturer->user_id,
            'salary_number' => 'required|string|unique:lecturers,salary_number,' . $id,
        ]);

        DB::beginTransaction();

        try {
            // Update lecturer record
            $lecturer->update([
                'salary_number' => $validated['salary_number']
            ]);

            // Update corresponding user record
            $user = $lecturer->user;
            if ($user) {
                $user->update([
                    'name' => $validated['name'],
                    'user_code' => $validated['ic_number'],
                    'password' => Hash::make($validated['salary_number'])
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Lecturer updated successfully',
                'data' => [
                    'id' => $lecturer->id,
                    'name' => $user->name,
                    'ic_number' => $user->user_code,
                    'salary_number' => $lecturer->salary_number
                ]
            ]);

        } catch (Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update lecturer',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified lecturer
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $lecturer = Lecturer::with('user')->findOrFail($id);
            
            // Delete corresponding user record
            if ($lecturer->user) {
                $lecturer->user->delete();
            }

            $lecturer->delete();

            DB::commit();

            return response()->json([
                'message' => 'Lecturer deleted successfully'
            ]);

        } catch (Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete lecturer',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Import lecturers via Excel/CSV
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $file = $request->file('file');

        try {
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray(null, true, true, true);

            if (count($rows) === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'The uploaded file is empty.'
                ], 422);
            }

            // Find header row
            $headerRowIndex = null;
            foreach ($rows as $index => $row) {
                $values = array_filter(array_map('trim', array_values($row)), fn($v) => $v !== null && $v !== '');
                if (!empty($values)) {
                    $headerRowIndex = $index;
                    break;
                }
            }

            if ($headerRowIndex === null) {
                return response()->json(['success' => false, 'message' => 'No header row found in the file.'], 422);
            }

            $rawHeaders = $rows[$headerRowIndex];
            $headers = [];
            foreach ($rawHeaders as $key => $val) {
                $headers[$key] = strtolower(trim((string) $val));
            }

            $required = ['name', 'ic_number', 'salary_number'];
            foreach ($required as $req) {
                if (!in_array($req, $headers, true)) {
                    return response()->json([
                        'success' => false,
                        'message' => "Missing required column: {$req}. Expected headers: name, ic_number, salary_number"
                    ], 422);
                }
            }

            // Map header name -> column key
            $colByName = [];
            foreach ($headers as $colKey => $name) {
                $colByName[$name] = $colKey;
            }

            $summary = [
                'total_rows' => 0,
                'created' => 0,
                'skipped' => 0,
                'errors' => [],
            ];

            foreach ($rows as $index => $row) {
                if ($index <= $headerRowIndex) continue;
                $summary['total_rows']++;

                $name = trim((string) ($row[$colByName['name']] ?? ''));
                $ic = trim((string) ($row[$colByName['ic_number']] ?? ''));
                $salary = trim((string) ($row[$colByName['salary_number']] ?? ''));

                if ($name === '' && $ic === '' && $salary === '') {
                    $summary['skipped']++;
                    continue;
                }

                if ($name === '' || $ic === '' || $salary === '') {
                    $summary['errors'][] = [
                        'row' => $index,
                        'message' => 'Missing required fields (name, ic_number, salary_number)'
                    ];
                    continue;
                }

                // Duplicate by user_code or salary_number
                if (User::where('user_code', $ic)->exists() || \App\Models\Lecturer::where('salary_number', $salary)->exists()) {
                    $summary['skipped']++;
                    continue;
                }

                try {
                    DB::transaction(function () use ($name, $ic, $salary) {
                        $user = User::create([
                            'name' => $name,
                            'user_code' => $ic,
                            'password' => Hash::make($ic),
                            'role' => 'lecturer'
                        ]);

                        Lecturer::create([
                            'user_id' => $user->id,
                            'salary_number' => $salary,
                        ]);
                    });
                    $summary['created']++;
                } catch (Exception $e) {
                    $summary['errors'][] = [
                        'row' => $index,
                        'message' => $e->getMessage()
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Import completed',
                'summary' => $summary
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to process file',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Assign courses to a lecturer.
     */
    public function assignCourses(Request $request, $id)
    {
        try {
            $request->validate([
                'course_ids' => 'required|array',
                'course_ids.*' => 'exists:courses,id',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }

        try {
            $lecturer = Lecturer::findOrFail($id);
            $lecturer->courses()->sync($request->input('course_ids'));

            return response()->json(['message' => 'Courses assigned successfully']);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to assign courses',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get analytics data for lecturers and courses.
     */
    public function getAnalytics(Request $request)
    {
        try {
            $lecturers = Lecturer::with(['user', 'courses.evaluations', 'courses.surveys'])->get();

            $analyticsData = [];

            foreach ($lecturers as $lecturer) {
                foreach ($lecturer->courses as $course) {
                    // Filter by request parameters
                    $filteredEvaluations = $course->evaluations->filter(function($evaluation) use ($lecturer, $request) {
                        $matchesLecturer = (!$request->has('lecturer_name') || $request->input('lecturer_name') === 'all' || $lecturer->user->name === $request->input('lecturer_name'));
                        $matchesCourse = (!$request->has('course_code') || $request->input('course_code') === 'all' || $evaluation->course->code === $request->input('course_code'));
                        $matchesSemester = (!$request->has('semester') || $request->input('semester') === 'all' || $evaluation->semester === $request->input('semester'));
                        return $matchesLecturer && $matchesCourse && $matchesSemester;
                    });

                    $filteredSurveys = $course->surveys->filter(function($survey) use ($lecturer, $request) {
                        $matchesLecturer = (!$request->has('lecturer_name') || $request->input('lecturer_name') === 'all' || $lecturer->user->name === $request->input('lecturer_name'));
                        $matchesCourse = (!$request->has('course_code') || $request->input('course_code') === 'all' || $survey->course->code === $request->input('course_code'));
                        $matchesSemester = (!$request->has('semester') || $request->input('semester') === 'all' || $survey->semester === $request->input('semester'));
                        return $matchesLecturer && $matchesCourse && $matchesSemester;
                    });

                    // Calculate averages and counts from real data
                    $evaluationCount = $filteredEvaluations->count();
                    $evaluationSum = $filteredEvaluations->sum('rating');
                    $evaluationAvg = $evaluationCount > 0 ? number_format($evaluationSum / $evaluationCount, 1) : 'N/A';

                    $surveyCount = $filteredSurveys->count();
                    $surveySum = $filteredSurveys->sum('question_1_rating') + $filteredSurveys->sum('question_2_rating') + $filteredSurveys->sum('question_3_rating');
                    $surveyAvg = $surveyCount > 0 ? number_format(($surveySum / ($surveyCount * 3)) * 20, 0) : 'N/A'; // Convert 1-5 scale to percentage (avg of 3 questions, times 20 for %) 
                    
                    $responses = $evaluationCount + $surveyCount;

                    // Use distinct semesters from evaluations or surveys for each lecturer-course pair
                    $semesters = $filteredEvaluations->pluck('semester')->merge($filteredSurveys->pluck('semester'))->unique();

                    // If no specific semesters, use the course's semester instead of "N/A"
                    if ($semesters->isEmpty()) {
                        $semesters = collect([$course->semester ?? '-']);
                    }

                    foreach ($semesters as $semester) {
                        // Recalculate based on current semester
                        $currentSemesterEvaluations = $filteredEvaluations->where('semester', $semester);
                        $currentSemesterSurveys = $filteredSurveys->where('semester', $semester);

                        $evaluationCount = $currentSemesterEvaluations->count();
                        $evaluationSum = $currentSemesterEvaluations->sum('rating');
                        $evaluationAvg = $evaluationCount > 0 ? number_format($evaluationSum / $evaluationCount, 1) : '-';

                        $surveyCount = $currentSemesterSurveys->count();
                        $surveySum = $currentSemesterSurveys->sum('question_1_rating') + $currentSemesterSurveys->sum('question_2_rating') + $currentSemesterSurveys->sum('question_3_rating');
                        $surveyAvg = $surveyCount > 0 ? number_format(($surveySum / ($surveyCount * 3)) * 20, 0) : '-';
                        
                        $responses = $evaluationCount + $surveyCount;

                        // Always add entry, even if responses are 0
                        $analyticsData[] = [
                            'lecturer_name' => $lecturer->user->name ?? '-',
                            'course_code' => $course->code,
                            'course_name' => $course->name,
                            'semester' => $semester, // Use actual semester from course or evaluations/surveys
                            'evaluation_avg' => "{$evaluationAvg}/5.0",
                            'survey_avg' => "{$surveyAvg}%",
                            'responses' => $responses,
                        ];
                    }
                }
            }

            return response()->json($analyticsData);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve analytics data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Allow admin to login as a lecturer
     */
    public function loginAsLecturer($id)
    {
        try {
            $lecturer = Lecturer::with('user')->findOrFail($id);
            
            if (!$lecturer->user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lecturer user account not found'
                ], 404);
            }

            // Store the original admin user ID in session for potential restoration
            session(['original_admin_id' => auth()->id()]);
            
            // Login as the lecturer
            auth()->login($lecturer->user);
            
            return response()->json([
                'success' => true,
                'message' => 'Successfully logged in as lecturer',
                'redirect_url' => '/lecturer/page'
            ]);
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to login as lecturer',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}