<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryDeduction extends Model
{
    use HasFactory;
    protected $fillable=['history_id','deduction_id','amount','staff_name','staff_number','date_month'];
}
