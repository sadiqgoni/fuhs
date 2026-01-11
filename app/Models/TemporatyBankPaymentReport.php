<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporatyBankPaymentReport extends Model
{
    use HasFactory;
    protected $table="temporary_bank_payments";
    protected $fillable=[
        'account_number',
        'amount',
        'bank',
        'branch',
        'sort_code',
        'remark',
        'staff_number',
        'ipp_no',
        'staff_name',
        'bank_code'
    ];
}
