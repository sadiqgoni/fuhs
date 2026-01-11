<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanDeductionCountdown extends Model
{
    use HasFactory;
    protected $fillable=[
        'employee_id',
        'start_month',
        'deduction_id',
        'installment_amount',
        'no_of_installment',
        'last_pay_month_year',
        'ded_countdown',
        'status',
        'deduction_status',
    ];
}
