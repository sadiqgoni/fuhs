<?php

namespace App\Imports;

use App\Models\SalaryStructure;
use App\Models\StaffPromotion;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithValidation;

class PromotionImport implements ToModel, WithHeadingRow, WithBatchInserts, WithUpserts, WithValidation, SkipsOnFailure
{
    use Importable, SkipsFailures;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
//    public $data;
//    public function __construct($data)
//    {
//        $this->data=$data;
//    }

    public function model(array $row)
    {
        $ss=SalaryStructure::where('name',$row['salary_structure'])->first();
        return new StaffPromotion([
            'payroll_number'=>$row['payroll_number'],
            'salary_structure'=>$ss->id,
            'level'=>$row['grade_level'],
            'step'=>$row['step'],

        ]);
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
            'payroll_number' => 'required|unique:staff_promotions,payroll_number|exists:employee_profiles,payroll_number',
            'salary_structure'=>'exists:salary_structures,name'
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
            'payroll_number.unique' => 'Staff with the same payroll number exists.',
            'salary_structure.exists'=>'this salary structure does not exists'
        ];
    }
}
