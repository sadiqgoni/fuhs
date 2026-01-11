<?php

namespace App\Livewire\Pages;

use App\Exports\EmployeeExport;
use App\Exports\LoadDeduction;
use App\Models\ActivityLog;
use App\Models\LoanDeductionCountdownHistory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class LoanDeductionHistory extends Component
{
    public $perpage=25,$search,$month,$deduction;
    use WithPagination,WithoutUrlPagination,LivewireAlert;
    public $date_from,$date_to;
    public function export()
    {

        $deductions=LoanDeductionCountdownHistory::join('loan_deduction_countdowns','loan_deduction_countdowns.id','loan_deduction_countdown_histories.employee_id')->
        select([
            'loan_deduction_countdown_histories.*',
            'loan_deduction_countdowns.deduction_id'
        ])
            -> when($this->deduction,function($query){
                return $query->where('loan_deduction_countdowns.deduction_id',$this->deduction);
            })

            ->when($this->date_from,function ($query){
                $date_from = Carbon::parse($this->date_from)->format('Y-m-d');
                $date_to = Carbon::parse($this->date_to)->format('Y-m-d');
                return $query->whereBetween('loan_deduction_countdown_histories.pay_month_year',[$date_from,$date_to]);
            })->get();
        $this->alert('success','Exported successfully');
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $log->action="Exported $this->month loan deduction record";
        $log->save();
        return Excel::download(new LoadDeduction($deductions), 'load_deductions.xlsx');

    }

    public function render()
    {
        $deductions=LoanDeductionCountdownHistory::join('loan_deduction_countdowns','loan_deduction_countdowns.id','loan_deduction_countdown_histories.employee_id')->
        select([
            'loan_deduction_countdown_histories.*',
            'loan_deduction_countdowns.deduction_id'
        ])
            -> when($this->deduction,function($query){
                return $query->where('loan_deduction_countdowns.deduction_id',$this->deduction);
            })

            ->when($this->date_from,function ($query){
                $date_from = Carbon::parse($this->date_from)->format('Y-m-d');
                $date_to = Carbon::parse($this->date_to)->format('Y-m-d');
                return $query->whereBetween('loan_deduction_countdown_histories.pay_month_year',[$date_from,$date_to]);
            })
            -> paginate($this->perpage);
        return view('livewire.pages.loan-deduction-history',compact('deductions'))->extends('components.layouts.app');
    }
}
