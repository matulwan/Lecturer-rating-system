<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CLORating extends Model
{
    use HasFactory;

    protected $table = 'clo_ratings';

    protected $fillable = [
        'course_id',
        'student_id',
        'clo_number',
        'rating',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
