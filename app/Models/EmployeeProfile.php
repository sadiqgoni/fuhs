<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeProfile extends Model
{
    use HasFactory;
    protected $fillable=[
        'id',
        'employment_id',
        'full_name',
        'department',
        'staff_category',
        'employment_type',
        'staff_number',
        'payroll_number',
        'status',
        'salary_structure',
        'date_of_first_appointment',
        'date_of_last_appointment',
        'date_of_retirement',
        'post_held',
        'grade_level',
        'step',
        'rank',
        'unit',
        'phone_number',
        'whatsapp_number',
        'email',
        'bank_name',
        'account_number',
        'bank_code',
        'pfa_name',
        'pension_pin',
        'date_of_birth',
        'gender',
        'religion',
        'tribe',
        'marital_status',
        'nationality',
        'state_of_origin',
        'local_government',
        'profile_picture',
        'name_of_next_of_kin',
        'next_of_kin_phone_number',
        'relationship',
        'address',
        'created_at',
        'updated_at',
        'bvn',
        'tax_id',
        'staff_union',
        'tax_id',
        'bvn',
        'staff_union',
        'contract_termination_date',
    ];
}
