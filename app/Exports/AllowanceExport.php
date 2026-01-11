<?php

namespace App\Exports;

use App\Models\SalaryAllowanceTemplate;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class AllowanceExport implements FromView
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
        $allowances=$this->data;
        return view('exports.allowance_template_export',compact('allowances'));
    }
}
