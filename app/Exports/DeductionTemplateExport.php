<?php

namespace App\Exports;

use App\Models\SalaryDeductionTemplate;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class DeductionTemplateExport implements FromView
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
        $deductions=$this->data;
        return view('exports.deduction_template_export',compact('deductions'));
    }
}
