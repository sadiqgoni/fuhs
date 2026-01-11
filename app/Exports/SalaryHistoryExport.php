<?php

namespace App\Exports;

use App\Models\SalaryHistory;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SalaryHistoryExport implements FromView, WithHeadingRow
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
        $histories=$this->data;
        return view('exports.salary_history_export',compact('histories'));
    }

}
