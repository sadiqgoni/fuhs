<?php

namespace App\Exports;

use App\Models\SalaryHistory;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class PayrollExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $payroll, $summary, $name, $name_search;
    public function __construct($payroll,$name,$name_search)
    {
        $this->payroll=$payroll;
        $this->name=$name;
        $this->name_search=$name_search;
    }

    public function view():View
    {
        return view('exports.payrollexcelfile', [
            'payrolls' => $this->payroll,
            'name' => $this->name,
            'name_search' => $this->name_search,
        ]);
    }
}
