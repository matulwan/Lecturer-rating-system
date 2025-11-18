<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use Exception;
use Illuminate\Validation\ValidationException;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $courses = Course::all();
            return response()->json($courses);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve courses',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'code' => 'required|string|unique:courses,code|max:255',
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
            $course = Course::create($request->all());
            return response()->json(['message' => 'Course created successfully', 'course' => $course], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create course',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $course = Course::findOrFail($id);
            return response()->json($course);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'code' => 'required|string|unique:courses,code,' . $id . '|max:255',
                'semester' => 'required|string|max:255',
                'description' => 'nullable|string',
                'course_outcomes' => 'nullable|array',
                'co_po_mapping' => 'nullable|array',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }

        try {
            $course = Course::findOrFail($id);
            $course->update($request->all());
            return response()->json(['message' => 'Course updated successfully', 'course' => $course]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update course',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $course = Course::findOrFail($id);
            $course->delete();
            return response()->json(['message' => 'Course deleted successfully']);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete course',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
