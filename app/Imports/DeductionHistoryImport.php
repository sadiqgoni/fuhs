<?php

namespace App\Imports;

use App\Models\LoanDeductionCountdownHistory;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithValidation;

class DeductionHistoryImport implements ToModel, WithHeadingRow, WithBatchInserts, WithUpserts, WithValidation
{
    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new LoanDeductionCountdownHistory([
            'id'=>$row['id'],
            'employee_id'=>$row['employee_id'],
            'start_month'=>$row['start_month'],
            'no_of_installment'=>$row['no_of_installment'],
            'amount_paid'=>$row['amount_paid'],
            'pay_month_year'=>$row['pay_month_year'],
            'ded_countdown'=>$row['ded_countdown'],
            'created_at'=>$row['created_at'],
            'CreatedBy'=>$row['createdby'],
            'updated_at'=>$row['updated_at'],
            'ModifiedBy'=>$row['modifiedby'],
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
