<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_id',
        // Legacy fields kept for backward compatibility
        'question_1_rating',
        'question_2_rating',
        'question_3_rating',
        // New flexible structure to store dynamic CLO ratings
        'clo_ratings',
        'comment',
        'semester',
    ];

    protected $casts = [
        'clo_ratings' => 'array',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
