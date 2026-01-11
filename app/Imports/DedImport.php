<?php

namespace App\Imports;

use App\Models\Deduction;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DedImport implements ToModel, WithHeadingRow
{
    use Importable;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Deduction([
            'code'=>$row['code'],
            'deduction_name'=>$row['deduction_name'],
            'description'=>$row['description'],
            'tin_number'=>$row['tin_number'],
            'account_no'=>$row['account_no'],
            'account_name'=>$row['account_name'],
            'bank_code'=>$row['bank_code'],
            'visibility'=>$row['visibility'],
            'deduction_type'=>$row['deduction_type'],
            'status'=>$row['status']
        ]);
    }
}
