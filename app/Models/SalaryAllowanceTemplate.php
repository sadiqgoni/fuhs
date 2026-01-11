<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryAllowanceTemplate extends Model
{
    use HasFactory;
    protected $fillable=[
        'id',
        'salary_structure_id',
        'grade_level_from',
        'grade_level_to',
        'allowance_id',
        'allowance_type',
        'value',
        'created_at',
        'updated_at',
    ];
}
