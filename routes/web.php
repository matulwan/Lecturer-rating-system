<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController; 
use App\Http\Controllers\StudentController;
use App\Http\Controllers\LecturerEvaluationController;
use App\Http\Controllers\LecturerSurveyController;
use App\Http\Controllers\StudentStatusController;
use App\Http\Controllers\DashboardController;

// Main page
Route::get('/', function () {
    return view('welcome');
});

// Routes
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);

// Protected routes
Route::middleware(['auth'])->group(function () {
    // Logout route
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // API route to get authenticated user
    Route::get('/api/user', [AuthController::class, 'user']);

    // Evaluation and Survey Submission Routes
    Route::post('/evaluations', [App\Http\Controllers\EvaluationController::class, 'store']);
    Route::post('/surveys', [App\Http\Controllers\SurveyController::class, 'store']);
    Route::get('/courses/{id}/clos', [App\Http\Controllers\SurveyController::class, 'getCourseCLOs']);
    
    // Debug route for survey issues
    Route::get('/debug/user-status', [App\Http\Controllers\SurveyController::class, 'debugUserStatus']);

    // Student routes
    Route::get('/student/page', function () {
        return view('student.page');
    })->name('student.page')->middleware('role:student');
    Route::get('/student/assigned-data', [App\Http\Controllers\StudentLecturerController::class, 'index'])->middleware('role:student');
    // Student status (completion) endpoint
    Route::get('/student/status', [StudentStatusController::class, 'status'])->middleware('role:student');
    // Student evaluation full page
    Route::get('/student/evaluation', function () {
        return view('student.evaluation');
    })->name('student.evaluation')->middleware('role:student');

    // Lecturer routes
    Route::get('/lecturer/page', function () {
        return view('lecturer.page');
    })->name('lecturer.page')->middleware('role:lecturer');

    // Lecturer evaluations and surveys
    Route::get('/lecturer/evaluations', [App\Http\Controllers\LecturerEvaluationController::class, 'index'])->name('lecturer.evaluations')->middleware('role:lecturer');
    // Evaluation questions for dynamic form
    Route::get('/lecturer/evaluation/questions', [App\Http\Controllers\LecturerEvaluationController::class, 'getQuestions'])->middleware('role:lecturer');
    // Student-accessible evaluation questions endpoint
    Route::get('/evaluation/questions', [App\Http\Controllers\LecturerEvaluationController::class, 'getQuestions'])->middleware('role:student');

    // Lecturer question management
    Route::get('/lecturer/questions', [App\Http\Controllers\LecturerEvaluationQuestionController::class, 'index'])->name('lecturer.questions')->middleware('role:lecturer');
    Route::get('/lecturer/api/questions', [App\Http\Controllers\LecturerEvaluationQuestionController::class, 'list'])->middleware('role:lecturer');
    Route::post('/lecturer/api/questions', [App\Http\Controllers\LecturerEvaluationQuestionController::class, 'store'])->middleware('role:lecturer');
    Route::put('/lecturer/api/questions/{question}', [App\Http\Controllers\LecturerEvaluationQuestionController::class, 'update'])->middleware('role:lecturer');
    Route::delete('/lecturer/api/questions/{question}', [App\Http\Controllers\LecturerEvaluationQuestionController::class, 'destroy'])->middleware('role:lecturer');
    // API endpoints
    Route::get('/lecturer/api/evaluations/data', [App\Http\Controllers\LecturerEvaluationController::class, 'getEvaluationData'])->middleware('role:lecturer');
    Route::post('/lecturer/api/evaluations/remind', [App\Http\Controllers\LecturerEvaluationController::class, 'sendReminder'])->middleware('role:lecturer');
    Route::get('/lecturer/api/evaluations/export', [App\Http\Controllers\LecturerEvaluationController::class, 'export'])->middleware('role:lecturer');
    // Aliases expected by the current frontend
    Route::get('/lecturer/evaluations/data', [App\Http\Controllers\LecturerEvaluationController::class, 'getEvaluationData'])->middleware('role:lecturer');
    Route::post('/lecturer/evaluations/remind', [App\Http\Controllers\LecturerEvaluationController::class, 'sendReminder'])->middleware('role:lecturer');
    Route::get('/lecturer/evaluations/export', [App\Http\Controllers\LecturerEvaluationController::class, 'export'])->middleware('role:lecturer');
    // Friendly short link for cards
    Route::get('/lecturer-evaluation', function () {
        return redirect()->route('lecturer.evaluations');
    })->middleware('role:lecturer');
    // End of Course Survey short link
    Route::get('/course-survey', function () {
        return redirect()->route('lecturer.surveys');
    })->middleware('role:lecturer');
    
    Route::get('/lecturer/surveys', [App\Http\Controllers\LecturerSurveyController::class, 'index'])->name('lecturer.surveys')->middleware('role:lecturer');
    Route::get('/lecturer/api/surveys/data', [App\Http\Controllers\LecturerSurveyController::class, 'getSurveyData'])->middleware('role:lecturer');
    Route::get('/lecturer/api/surveys/export', [App\Http\Controllers\LecturerSurveyController::class, 'export'])->middleware('role:lecturer');

    Route::get('/lecturer/courses', [App\Http\Controllers\LecturerCourseController::class, 'index'])->middleware('role:lecturer');
    Route::get('/lecturer/statistics', [App\Http\Controllers\LecturerCourseController::class, 'getStatistics'])->middleware('role:lecturer');
    Route::get('/course/{id}', function ($id) {
        return view('lecturer.course-manage');
    })->middleware('role:lecturer');
    
    // Lecturer course management routes
    Route::middleware(['auth', 'role:lecturer'])->group(function () {
        Route::get('/lecturer/courses/{id}', [App\Http\Controllers\CourseController::class, 'show']);
        Route::put('/lecturer/courses/{id}', [App\Http\Controllers\CourseController::class, 'update']);
    });

    // CLO Rating routes
    Route::post('/clo-ratings', [App\Http\Controllers\CLORatingController::class, 'store'])->middleware('role:student');
    Route::get('/course/{id}/clo-ratings', [App\Http\Controllers\CLORatingController::class, 'getCourseRatings'])->middleware('role:lecturer');
    Route::get('/course/{id}/my-clo-ratings', [App\Http\Controllers\CLORatingController::class, 'getStudentRatings'])->middleware('role:student');
    Route::get('/course/{id}/rate-clos', function ($id) {
        return view('student.clo-rating');
    })->middleware('role:student');

    // Super Admin routes
    Route::get('/admin/page', function () {
        return view('admin.page');
    })->name('admin.page')->middleware('role:super_admin');

    // Admin API routes
    Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->group(function () {
        // Student management routes
        Route::get('/students', [App\Http\Controllers\Admin\StudentController::class, 'index']);
        Route::post('/students', [App\Http\Controllers\Admin\StudentController::class, 'store']);
        Route::get('/students/{id}', [App\Http\Controllers\Admin\StudentController::class, 'show']);
        Route::put('/students/{id}', [App\Http\Controllers\Admin\StudentController::class, 'update']);
        Route::delete('/students/{id}', [App\Http\Controllers\Admin\StudentController::class, 'destroy']);
        Route::post('/students/import', [App\Http\Controllers\Admin\StudentController::class, 'import']);
        
        // Lecturer management routes
        Route::get('/lecturers', [App\Http\Controllers\Admin\LecturerController::class, 'index']);
        Route::post('/lecturers', [App\Http\Controllers\Admin\LecturerController::class, 'store']);
        Route::get('/lecturers/{id}', [App\Http\Controllers\Admin\LecturerController::class, 'show']);
        Route::put('/lecturers/{id}', [App\Http\Controllers\Admin\LecturerController::class, 'update']);
        Route::delete('/lecturers/{id}', [App\Http\Controllers\Admin\LecturerController::class, 'destroy']);
        Route::post('/lecturers/import', [App\Http\Controllers\Admin\LecturerController::class, 'import']);
        Route::post('/lecturers/{lecturer}/assign-courses', [App\Http\Controllers\Admin\LecturerController::class, 'assignCourses']);
        Route::post('/lecturers/{id}/login-as', [App\Http\Controllers\Admin\LecturerController::class, 'loginAsLecturer']);
        
        // Course management routes
        Route::resource('/courses', App\Http\Controllers\CourseController::class);
        
        // Analytics routes
        Route::get('/analytics', [App\Http\Controllers\Admin\LecturerController::class, 'getAnalytics']);
        
        // Dashboard counts route
        Route::get('/dashboard/counts', [App\Http\Controllers\DashboardController::class, 'getCounts']);
    });
});