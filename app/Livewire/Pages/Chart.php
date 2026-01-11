<?php

namespace App\Livewire\Pages;

use App\Models\Department;
use App\Models\SalaryHistory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Chart extends Component
{
    public $year;

    public function render()
    {
        $date=date('Y');
        $history=new SalaryHistory();
        $month_array = $history->getMonthlyData($date);

        $permanent = SalaryHistory::select(
            DB::raw('year(date_month) as year'),
            DB::raw('month(date_month) as month'),
            DB::raw('count(employment_type) as total'),
        ) ->where( DB::raw('YEAR(date_month)'), '=', $date )
            ->orderBy('month')
            ->groupBy('year','month')
            ->get();
        return view('livewire.pages.chart')->extends('components.layouts.app');
    }
}
