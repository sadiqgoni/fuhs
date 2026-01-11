<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanDeductionCountdownHistory extends Model
{
    use HasFactory;
    protected $table="loan_deduction_countdown_histories";
    protected $fillable=[
        'id',
        'employee_id',
        'start_month',
        'no_of_installment',
        'amount_paid',
        'pay_month_year',
        'ded_countdown',
        'created_at',
        'CreatedBy',
        'updated_at',
        'ModifiedBy',
    ];
}
