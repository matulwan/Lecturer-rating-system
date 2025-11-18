<?php

namespace App\Http\Controllers;

use App\Models\EvaluationQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LecturerEvaluationQuestionController extends Controller
{
    // Render management UI
    public function index()
    {
        return view('lecturer.questions');
    }

    // List questions (JSON)
    public function list(Request $request)
    {
        $user = Auth::user();
        // Lecturers can view all for now; later filter by created_by if required
        $questions = EvaluationQuestion::orderBy('section')
            ->orderBy('number')
            ->get();
        return response()->json(['questions' => $questions]);
    }

    // Create
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'section' => 'required|string|max:100',
                'number' => 'required|integer|min:1',
                'text' => 'required|string|max:2000',
                'type' => 'required|in:scale,text',
            ]);
        } catch (ValidationException $e) {
            \Log::error('Question validation failed', ['errors' => $e->errors()]);
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        }

        try {
            $data['created_by'] = Auth::id();
            $data['active'] = true; // Always set to active
            
            \Log::info('Creating question with data', $data);
            $question = EvaluationQuestion::create($data);
            
            return response()->json([
                'success' => true, 
                'question' => $question,
                'message' => 'Question created successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Question creation failed', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            
            return response()->json([
                'success' => false, 
                'message' => 'Failed to create question: ' . $e->getMessage()
            ], 500);
        }
    }

    // Update
    public function update(Request $request, EvaluationQuestion $question)
    {
        try {
            $data = $request->validate([
                'section' => 'sometimes|string|max:100',
                'number' => 'sometimes|integer|min:1',
                'text' => 'sometimes|string|max:2000',
                'type' => 'sometimes|in:scale,text',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        }

        $data['active'] = true; // Always keep active
        $question->update($data);
        return response()->json(['success' => true, 'question' => $question]);
    }

    // Soft delete
    public function destroy(EvaluationQuestion $question)
    {
        $question->delete();
        return response()->json(['success' => true]);
    }
}
