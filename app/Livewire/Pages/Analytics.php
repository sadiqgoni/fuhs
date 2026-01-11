<?php

namespace App\Livewire\Pages;

use App\Charts\PayrollChart;
use App\Models\SalaryHistory;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Analytics extends Component
{
    public $year;
//
    protected $listeners = [
        'year' => 'yearSelected',
    ];
    public function mount()
    {
        $this->year=date('Y');

    }
    public function yearSelected()
    {
        $date=$this->year;
        $history=new SalaryHistory();
        $basic_salary = $history->getMonthlyData($date);
        $basic = SalaryHistory::whereYear('date_month',$date)->select(
            DB::raw('month(date_month) as month'),
            DB::raw('sum(basic_salary) as total'),
            DB::raw('year(date_month) as year'),
        )
            ->orderBy('month')
            ->groupBy('year','month')
            ->get();

        $gross_pay = SalaryHistory::select(
            DB::raw('month(date_month) as month'),
            DB::raw('year(date_month) as year'),
            DB::raw('sum(gross_pay) as total'),
        )            ->where( DB::raw('YEAR(date_month)'), '=', $date )

            ->orderBy('month')
            ->groupBy('year','month')
            ->get();


        $total_allowance = SalaryHistory::select(
            DB::raw('year(date_month) as year'),
            DB::raw('month(date_month) as month'),
            DB::raw('sum(total_allowance) as total'),
        )            ->where( DB::raw('YEAR(date_month)'), '=', $date )

            ->orderBy('month')
            ->groupBy('year','month')
            ->get();
        $total_deduction = SalaryHistory::select(
            DB::raw('month(date_month) as month'),
            DB::raw('sum(total_deduction) as total'),
            DB::raw('year(date_month) as year'),
        )            ->where( DB::raw('YEAR(date_month)'), '=', $date )

            ->orderBy('month')
            ->groupBy('year','month')
            ->get();

        $net_pay = SalaryHistory::select(
            DB::raw('month(date_month) as month'),
            DB::raw('sum(net_pay) as total'),
            DB::raw('year(date_month) as year'),
        )            ->where( DB::raw('YEAR(date_month)'),'=', $date)

            ->orderBy('month')
            ->groupBy('year','month')
            ->get();
    }
    public function onYearChange()
    {
        $this->dispatch('year', $this->year);
    }
    public function render()
    {
        $date=$this->year;
        $history=new SalaryHistory();
        $basic_salary = $history->getMonthlyData($date);
        $basic = SalaryHistory::whereYear('date_month',$date)->select(
            DB::raw('month(date_month) as month'),
            DB::raw('sum(basic_salary) as total'),
            DB::raw('year(date_month) as year'),
        )
            ->orderBy('month')
            ->groupBy('year','month')
            ->get();

        $gross_pay = SalaryHistory::select(
            DB::raw('month(date_month) as month'),
            DB::raw('year(date_month) as year'),
            DB::raw('sum(gross_pay) as total'),
        )            ->where( DB::raw('YEAR(date_month)'), '=', $date )

            ->orderBy('month')
            ->groupBy('year','month')
            ->get();


        $total_allowance = SalaryHistory::select(
            DB::raw('year(date_month) as year'),
            DB::raw('month(date_month) as month'),
            DB::raw('sum(total_allowance) as total'),
        )            ->where( DB::raw('YEAR(date_month)'), '=', $date )

            ->orderBy('month')
            ->groupBy('year','month')
            ->get();
        $total_deduction = SalaryHistory::select(
            DB::raw('month(date_month) as month'),
            DB::raw('sum(total_deduction) as total'),
            DB::raw('year(date_month) as year'),
        )            ->where( DB::raw('YEAR(date_month)'), '=', $date )

            ->orderBy('month')
            ->groupBy('year','month')
            ->get();

        $net_pay = SalaryHistory::select(
            DB::raw('month(date_month) as month'),
            DB::raw('sum(net_pay) as total'),
            DB::raw('year(date_month) as year'),
        )            ->where( DB::raw('YEAR(date_month)'),'=', $date)

            ->orderBy('month')
            ->groupBy('year','month')
            ->get();
//        $this->dispatch('refresh-page');
//        $this->dispatch('contentChanged', [$basic,$basic_salary,$gross_pay,$total_allowance,$total_deduction,$net_pay]);
        return view('livewire.pages.analytics',compact('basic_salary','basic','total_deduction','net_pay','total_allowance','gross_pay'))->extends('components.layouts.app');
    }
}
