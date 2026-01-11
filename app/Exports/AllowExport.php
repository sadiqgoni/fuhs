<?php

namespace App\Exports;

use App\Models\Allowance;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class AllowExport implements FromView
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
        return view('exports.allowanceExport',compact('exports'));
    }
}
