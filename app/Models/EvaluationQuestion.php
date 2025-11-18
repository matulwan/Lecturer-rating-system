<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EvaluationQuestion extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'section',   // e.g., Section A, Section B
        'number',    // position within section
        'text',      // question text
        'type',      // 'scale' or 'text'
        'active',    // boolean
        'created_by' // user_id of creator (lecturer)
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
}
