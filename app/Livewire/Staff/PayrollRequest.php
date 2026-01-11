<?php

namespace App\Livewire\Staff;

use App\Models\Deduction;
use App\Models\EmployeeProfile;
use App\Models\SalaryHistory;
use App\Models\TemporaryDeduction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class PayrollRequest extends Component
{
    public $report_type,$month_from,$month_to,$payroll_number,$order='asc',$order_by='date_month';
    public $payslips,$banks,$deductions,$deduction;
    protected $listeners=['confirmed'];
    use LivewireAlert;

    public function mount()
    {
        $this->payroll_number=Auth::user()->username;

        $this->payslips=[];
        $this->banks=[];
        $this->deductions=[];
    }
    public function generate()
    {
        $this->validate([
            'month_from'=>'required',
            'month_to'=>'required',
        ]);
        $this->payroll_number=Auth::user()->username;
        if ($this->report_type==1){
            $this->payslip();
        }elseif ($this->report_type==2){
            $this->bank_pay();
        }else{
            $this->deduction();
        }
    }
    public function payslip()
    {
        $startDate = Carbon::parse ($this->month_from)->format('Y-m-d');
        $endDate = Carbon::parse($this->month_to)->format('Y-m-d');
        $this->payslips=SalaryHistory::where('ip_number',$this->payroll_number)
            ->whereBetween('date_month',[$startDate,$endDate])
            ->orderBy($this->order_by,$this->order)->get();
        $this->alert('success','Your payslip have been generated Successfully');
    }
    public function bank_pay()
    {
        $startDate = Carbon::parse ($this->month_from)->format('Y-m-d');
        $endDate = Carbon::parse($this->month_to)->format('Y-m-d');
        $this->banks=SalaryHistory::where('ip_number',$this->payroll_number)
            ->whereBetween('date_month',[$startDate,$endDate])
            ->orderBy($this->order_by,$this->order)
            ->get();
        $this->alert('success','Your bank payment have been generated Successfully');

    }
    public function deduction()
    {

        $startDate = Carbon::parse ($this->month_from)->format('Y-m-d');
        $endDate = Carbon::parse($this->month_to)->format('Y-m-d');
        TemporaryDeduction::query()->truncate();
        $reports= SalaryHistory::where('ip_number',$this->payroll_number)
            ->whereBetween('date_month',[$startDate,$endDate])
            ->orderBy($this->order_by,$this->order)
            ->get();
        $insertion_data = array();
        foreach ($reports as $report) {
            $staff = EmployeeProfile::where('payroll_number', $report->ip_number)->first();
            $deductions = Deduction::when($this->deduction,function ($query){return $query->where('id',$this->deduction);})->get();
            foreach ($deductions as $index => $deduction) {
                $new_data = [
                    'history_id' => $report->id,
                    'deduction_id' => $deduction->id,
                    'staff_number' => $report->ip_number,
                    'staff_name' => $report->full_name,
                    'date_month' => $report->date_month,
                    'amount' => $report['D' . $deduction->id],
                ];
                $insertion_data[] = $new_data;
            }
        }
        $insertion_data = collect($insertion_data);
        $data_to_insert = $insertion_data->chunk(10);
        foreach ($data_to_insert as $key => $data) {
            try {
                DB::table('temporary_deductions')->insert($data
                    ->toArray());
            } catch (\Illuminate\Database\QueryException $e) {
                $error = $e->getMessage();
                echo $error;
            }
        }
        $deduct=TemporaryDeduction::get();
        $this->deductions=array();
        foreach ($deduct->unique('deduction_id') as $de){
            array_push($this->deductions,$de);
        }
//        dd($this->deductions);
        $this->alert('success','Your deduction details have been generated Successfully');

    }
    public function render()
    {
        return view('livewire.staff.payroll-request')->extends('components.layouts.app')->section('body');
    }
}
