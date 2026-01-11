<?php

namespace App\Imports;

use App\Models\Bank;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BankImport implements ToModel, WithHeadingRow
{
    use Importable;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Bank([
            'bank_name'=>$row['bank_name'],
            'bank_code'=>$row['bank_code'],
            'bank_branch'=>$row['bank_branch'],
            'status'=>$row['status'],
        ]);
    }
}
