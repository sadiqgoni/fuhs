<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnualSalaryIncrement extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'increment_month',
        'increment_year',
        'month_year',
        'salary_structure',
        'grade_level',
        'old_grade_step',
        'new_grade_step',
        'status',
        'current_salary',
        'new_salary',
        'arrears_months'
    ];
}
