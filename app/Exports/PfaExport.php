<?php

namespace App\Exports;

use App\Models\SalaryHistory;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class PfaExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $reports,$date;
    public function __construct($reports,$date)
    {
        $this->reports=$reports;
        $this->date=$date;
    }
    public function view():View
    {
        return view('exports.pfa_export', [
            'reports' => $this->reports,
            'date' => $this->date,

        ]);
    }
}
