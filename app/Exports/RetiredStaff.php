<?php

namespace App\Exports;

use App\Models\EmployeeProfile;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class RetiredStaff implements FromView
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
        $employees=$this->data;
        return view('exports.retired_staff',compact('employees'));
    }
}
