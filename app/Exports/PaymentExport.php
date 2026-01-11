<?php

namespace App\Exports;

use App\Models\TemporatyBankPaymentReport;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class PaymentExport implements FromView
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

        return view('exports.bank_payment', [
            'payment_reports' => $this->data,
        ]);
    }
}
