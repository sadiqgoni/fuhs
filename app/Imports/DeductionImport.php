<?php

namespace App\Imports;

use App\Models\SalaryDeductionTemplate;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithValidation;

class DeductionImport implements ToModel, WithHeadingRow, WithBatchInserts, WithUpserts, WithValidation
{
    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new SalaryDeductionTemplate([
            'id'=>$row['id'],
            'salary_structure_id'=>$row['salary_structure_id'],
            'grade_level_from'=>$row['grade_level_from'],
            'grade_level_to'=>$row['grade_level_to'],
            'deduction_id'=>$row['deduction_id'],
            'deduction_type'=>$row['deduction_type'],
            'value'=>$row['value'],
//            'created_at'=>$row['created_at'],
//            'updated_at'=>$row['updated_at'],
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
