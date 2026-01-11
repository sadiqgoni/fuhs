<?php

namespace App\Exports;

use App\Models\SalaryUpdate;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class SalaryUpdateExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $data;
    public function __construct($data)
    {
        $this->data=$data;
    }
    public function view():View
    {
        $salaries=$this->data;
        return view('exports.salary_update_export',compact('salaries'));
    }
}
