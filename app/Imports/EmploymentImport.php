<?php

namespace App\Imports;

use App\Models\EmploymentType;
use Maatwebsite\Excel\Concerns\ToModel;

class EmploymentImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new EmploymentType([
            //
        ]);
    }
}
