<?php

namespace App\Exports;

use App\Models\SalaryStructureTemplate;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class SalaryTemplateExport implements FromView
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
        return view('exports.salary_template_export',compact('salaries'));
    }
}
