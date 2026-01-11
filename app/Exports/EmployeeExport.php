<?php

namespace App\Exports;

use App\Models\EmployeeProfile;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class EmployeeExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $report_col;
    public $data;
    public function __construct($data)
    {
        $this->data=$data;
    }
    public function view():View
    {
        return view('exports.employee', [
            'reports' => [$this->data[0]],
            'report_col' => [$this->data[1]],
        ]);
    }
}
