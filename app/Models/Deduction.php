<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deduction extends Model
{
    use HasFactory;
    protected $fillable=[
        'code',
        'deduction_name',
        'description',
        'tin_number',
        'account_no',
        'account_name',
        'bank_code',
        'visibility',
        'deduction_type',
        'status',
        ];
}
