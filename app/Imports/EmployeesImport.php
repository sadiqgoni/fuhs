<?php

namespace App\Imports;

use App\Models\EmployeeProfile;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithValidation;

class EmployeesImport implements ToModel, WithHeadingRow, WithBatchInserts, WithUpserts, WithValidation
{
    use Importable;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new EmployeeProfile([
            'employment_id'=>$row['employment_id'],
            'full_name'=>$row['full_name'],
            'department'=>$row['department'],
            'staff_category'=>$row['staff_category'],
            'employment_type'=>$row['mployment_type'],
            'staff_number'=>$row['staff_number'],
            'payroll_number'=>$row['payroll_number'],
            'status'=>$row['status'],
            'salary_structure'=>$row['salary_structure'],
            'date_of_first_appointment'=>$row['date_of_first_appointment'],
            'date_of_last_appointment'=>$row['date_of_last_appointment'],
            'post_held'=>$row['post_held'],
            'grade_level'=>$row['grade_level'],
            'step'=>$row['step'],
            'rank'=>$row['rank'],
            'unit'=>$row['unit'],
            'phone_number'=>$row['phone_number'],
            'whatsapp_number'=>$row['whatsapp_number'],
            'email'=>$row['email'],
            'bank_name'=>$row['bank_name'],
            'account_number'=>$row['account_number'],
            'bank_code'=>$row['bank_code'],
            'pfa_name'=>$row['pfa_name'],
            'pension_pin'=>$row['pension_pin'],
            'date_of_birth'=>$row['date_of_birth'],
            'gender'=>$row['gender'],
            'religion'=>$row['religion'],
            'tribe'=>$row['tribe'],
            'marital_status'=>$row['marital_status'],
            'nationality'=>$row['nationality'],
            'state_of_origin'=>$row['state_of_origin'],
            'local_government'=>$row['local_government'],
            'profile_picture'=>$row['profile_picture'],
            'name_of_next_of_kin'=>$row['name_of_next_of_kin'],
            'next_of_kin_phone_number'=>$row['next_of_kin_phone_number'],
            'relationship'=>$row['relationship'],
            'address'=>$row['address'],
            'bvn'=>$row['Bvn'],
            'tax_id'=>$row['Tax Id'],
            'union'=>$row['Union'],
        ]);
    }
    public function batchSize(): int
    {
        return 1000;
    }
    public function uniqueBy()
    {
        return 'staff_number';
    }
    public function rules(): array
    {
        return [
            '1' => Rule::in(['patrick@maatwebsite.nl']),

            // Above is alias for as it always validates in batches
            '*.1' => Rule::in(['patrick@maatwebsite.nl']),

            // Can also use callback validation rules
            '0' => function($attribute, $value, $onFailure) {
                if ($value !== 'Patrick Brouwers') {
                    $onFailure('Name is not Patrick Brouwers');
                }
            }
        ];
    }
}
