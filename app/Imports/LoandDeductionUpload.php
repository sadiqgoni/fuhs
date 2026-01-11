<?php

namespace App\Imports;

use App\Models\EmployeeProfile;
use App\Models\LoanDeductionCountdown;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithValidation;

class LoandDeductionUpload implements ToModel, WithHeadingRow, WithBatchInserts, WithUpserts, WithValidation, SkipsOnFailure
{
    use Importable, SkipsFailures;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public $data;
    public function __construct($data){
        $this->data=$data;
    }
    public function model(array $row)
    {
       $data=$this->data;
       $emp=EmployeeProfile::where('payroll_number',$row['payroll_number'])->first();
       $exists=LoanDeductionCountdown::where('deduction_id',$data['deduction'])->where('employee_id',$emp->id)->exists();
       if ($exists){

       }else {
           return new LoanDeductionCountdown([
               'employee_id' => $emp->id,
               'start_month' => Carbon::parse($data['date'])->toDateString(),
               'deduction_id' => $data['deduction'],
               'installment_amount' => $row['installment_amount'],
               'no_of_installment' => $row['no_of_installment'],
               'last_pay_month_year' => Carbon::parse($data['date'])->toDateString(),
               'ded_countdown' => $row['no_of_installment'],
               'status' => 0,
               'deduction_status' => 0
           ]);
       }
    }


    public function batchSize(): int
    {
        return 100;
    }
    public function uniqueBy()
    {
//        return 'staff_number';
    }
    public function rules(): array
    {
        return [
            'payroll_number' => 'required|exists:employee_profiles,payroll_number',
//            'salary_structure'=>'exists:salary_structures,name'
//            'payroll_number' => ['required',unique],
            // Above is alias for as it always validates in batches
//            '*.1' => Rule::in(['patrick@maatwebsite.nl']),

            // Can also use callback validation rules
//            '0' => function($attribute, $value, $onFailure) {
//                if ($value !== 'Patrick Brouwers') {
//                    $onFailure('Name is not Patrick Brouwers');
//                }
//            }
        ];
    }
    public function customValidationMessages()
    {
        return [
            'payroll_number.exists' => 'This payroll number does not exists in employees profile.',
        ];
    }
}
