<?php

namespace App\Imports;

use App\Models\Allowance;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AllowImport implements ToModel, WithHeadingRow
{
    use Importable;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Allowance([
            'code'=>$row['code'],
            'allowance_name'=>$row['allowance_name'],
            'description'=>$row['description'],
            'taxable'=>$row['taxable'],
            'status'=>$row['status'],
        ]);
    }
}
