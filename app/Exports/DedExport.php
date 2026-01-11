<?php

namespace App\Exports;

use App\Models\Deduction;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class DedExport implements FromView
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
        $exports=$this->data;
        return view('exports.deductionExport',compact('exports'));
    }
}
