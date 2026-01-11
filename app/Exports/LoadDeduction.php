<?php

namespace App\Exports;

use App\Models\LoanDeductionCountDown;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class LoadDeduction implements FromView
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
        return view('exports.loan_deduction',compact('deductions'));
    }
}
