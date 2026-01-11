<?php

namespace App\Exports;

use App\Models\LoanDeductionHistory;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class DeductionExport implements FromView
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
        return view('exports.deduction_history_export',compact('deductions'));
    }
}
