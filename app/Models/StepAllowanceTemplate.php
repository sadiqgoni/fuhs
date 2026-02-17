<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StepAllowanceTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'salary_structure_id',
        'grade_level',
        'step',
        'allowance_id',
        'value',
    ];
}

