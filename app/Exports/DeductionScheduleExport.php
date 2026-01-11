<?php

namespace App\Exports;

use App\Models\SalaryHistory;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class DeductionScheduleExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $payroll,$data;
    public function __construct($payroll,$data)
    {
        $this->data=$data;
        $this->payroll=$payroll;
    }
    public function view():View
    {
        return view('exports.deductionscheduleexport', [
            'reports' => $this->payroll,
            'date_from' => $this->data,

        ]);
    }
}
