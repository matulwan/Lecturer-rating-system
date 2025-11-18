<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'semester',
        'description',
        'course_outcomes',
        'co_po_mapping',
    ];

    protected $casts = [
        'course_outcomes' => 'array',
        'co_po_mapping' => 'array',
    ];

    public function lecturers()
    {
        return $this->belongsToMany(Lecturer::class);
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    public function surveys()
    {
        return $this->hasMany(Survey::class);
    }
}
