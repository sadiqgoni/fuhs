<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;

class StepMatrixImport implements ToCollection
{
    use Importable;

    /**
     * The raw rows from the uploaded sheet.
     *
     * @var Collection
     */
    public Collection $rows;

    public function collection(Collection $rows)
    {
        $this->rows = $rows;
    }
}

