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
    public function salary_structure()
    {
        return $this->belongsTo(SalaryStructure::class, 'salary_structure_id');
    }

    public function allowance()
    {
        // Assuming your Allowance model uses 'id' as primary key
        // and step_allowance_templates uses 'allowance_id'
        return $this->belongsTo(Allowance::class, 'allowance_id');
    }
}

