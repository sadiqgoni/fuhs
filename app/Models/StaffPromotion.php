<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffPromotion extends Model
{
    use HasFactory;
    protected $fillable = [
        'payroll_number',
        'salary_structure',
        'level',
        'step',
        'status',
        'arrears_months'
    ];
}
