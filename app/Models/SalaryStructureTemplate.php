<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryStructureTemplate extends Model
{
    use HasFactory;
    protected $fillable=[
        'id',
        'salary_structure_id',
        'grade_level',
        'grade_level_from',
        'grade_level_to',
        'no_of_grade_steps',
        'Step1',
        'Step2',
        'Step3',
        'Step4',
        'Step5',
        'Step6',
        'Step7',
        'Step8',
        'Step9',
        'Step10',
        'Step11',
        'Step12',
        'Step13',
        'Step14',
        'Step15',
        'Step16',
        'Step17',
        'Step18',
        'Step19',
        'Step20',
        'created_at',
        'updated_at'
    ];
}
