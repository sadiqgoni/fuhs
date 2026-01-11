<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryDeductionTemplate extends Model
{
    use HasFactory;
    protected $fillable=[
        'id',
        'salary_structure_id',
        'grade_level_from',
        'grade_level_to',
        'deduction_id',
        'deduction_type',
        'value',
        'created_at',
        'updated_at',
    ];
}
