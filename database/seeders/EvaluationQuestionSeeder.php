<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EvaluationQuestion;

class EvaluationQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questions = [
            // Section A - Teaching Effectiveness
            [
                'section' => 'Section A',
                'number' => 1,
                'text' => 'The lecturer explains the subject matter clearly and effectively.',
                'type' => 'scale',
                'active' => true,
            ],
            [
                'section' => 'Section A',
                'number' => 2,
                'text' => 'The lecturer demonstrates thorough knowledge of the subject.',
                'type' => 'scale',
                'active' => true,
            ],
            [
                'section' => 'Section A',
                'number' => 3,
                'text' => 'The lecturer organizes and presents material in a logical sequence.',
                'type' => 'scale',
                'active' => true,
            ],
            [
                'section' => 'Section A',
                'number' => 4,
                'text' => 'The lecturer uses appropriate teaching methods and techniques.',
                'type' => 'scale',
                'active' => true,
            ],
            [
                'section' => 'Section A',
                'number' => 5,
                'text' => 'The lecturer encourages student participation and questions.',
                'type' => 'scale',
                'active' => true,
            ],
            [
                'section' => 'Section A',
                'number' => 6,
                'text' => 'The lecturer provides helpful feedback on assignments and exams.',
                'type' => 'scale',
                'active' => true,
            ],
            
            // Section B - Course Management
            [
                'section' => 'Section B',
                'number' => 1,
                'text' => 'The course objectives were clearly stated and achieved.',
                'type' => 'scale',
                'active' => true,
            ],
            [
                'section' => 'Section B',
                'number' => 2,
                'text' => 'The course content was well-organized and comprehensive.',
                'type' => 'scale',
                'active' => true,
            ],
            [
                'section' => 'Section B',
                'number' => 3,
                'text' => 'The workload was appropriate for the course level.',
                'type' => 'scale',
                'active' => true,
            ],
            [
                'section' => 'Section B',
                'number' => 4,
                'text' => 'The lecturer was punctual and well-prepared for classes.',
                'type' => 'scale',
                'active' => true,
            ],
            [
                'section' => 'Section B',
                'number' => 5,
                'text' => 'The lecturer was available for consultation and help.',
                'type' => 'scale',
                'active' => true,
            ],
            [
                'section' => 'Section B',
                'number' => 6,
                'text' => 'Overall, I would recommend this lecturer to other students.',
                'type' => 'scale',
                'active' => true,
            ],
            
            // Text question for suggestions
            [
                'section' => 'Section B',
                'number' => 7,
                'text' => 'Please provide any additional comments or suggestions for improvement.',
                'type' => 'text',
                'active' => true,
            ],
        ];

        foreach ($questions as $questionData) {
            EvaluationQuestion::create($questionData);
        }
    }
}
