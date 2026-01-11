<?php

namespace App\Imports;

use App\Models\SalaryStructure;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SalaryStructureImport implements ToModel, WithHeadingRow
{
    use Importable;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new SalaryStructure([
            'name'=>$row['name'],
            'status'=>$row['status'],
            'no_of_grade'=>$row['no_of_grade'],
        ]);
    }
}
