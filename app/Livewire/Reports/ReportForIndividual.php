<?php

namespace App\Livewire\Reports;

use App\Mail\Payslip;
use App\Models\Deduction;
use App\Models\EmployeeProfile;
use App\Models\SalaryHistory;
use App\Models\TemporaryDeduction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use RealRashid\SweetAlert\Facades\Alert;

class ReportForIndividual extends Component
{
    public $report_type,$month_from,$month_to,$payroll_number,$order='asc',$order_by='date_month';
    public $payslips,$banks,$deductions,$deduction;
    protected $listeners=['confirmed'];
    use LivewireAlert;
    public function mount()
    {
        $this->payslips=[];
        $this->banks=[];
        $this->deductions=[];
    }
    public function generate()
    {
        $this->authorize('can_report');
        $this->validate([
            'payroll_number'=>'required',
            'report_type'=>'required',
            'month_from'=>'required',
            'month_to'=>'required'
        ]);
        if ($this->report_type==1){
            $this->payslip();
        }elseif ($this->report_type==2){
            $this->bank_pay();
        }elseif($this->report_type==3){
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
        $this->alert('success','Individual payslip have been generated Successfully');
    }
    public function bank_pay()
    {
        $startDate = Carbon::parse ($this->month_from)->format('Y-m-d');
        $endDate = Carbon::parse($this->month_to)->format('Y-m-d');
        $this->banks=SalaryHistory::where('ip_number',$this->payroll_number)
            ->whereBetween('date_month',[$startDate,$endDate])
            ->orderBy($this->order_by,$this->order)
            ->get();
        $this->alert('success','Individual bank payment have been generated Successfully');

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
        if ($reports->count()>0) {
            $insertion_data = array();
            foreach ($reports as $report) {
                $staff = EmployeeProfile::where('payroll_number', $report->ip_number)->first();
                $deductions = Deduction::when($this->deduction, function ($query) {
                    return $query->where('id', $this->deduction);
                })->get();
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
            $deduct = TemporaryDeduction::get();
            $this->deductions = array();
            foreach ($deduct->unique('deduction_id') as $de) {
                array_push($this->deductions, $de);
            }
//        dd($this->deductions);
            $this->alert('success', 'Individual deduction details have been generated Successfully');
        }else{
            Alert::warning('No record found');
        }

    }

    public function sendMail()
    {
        $this->authorize('can_mail');
        $this->validate([
            'payroll_number'=>'required|exists:employee_profiles,payroll_number',
            'month_from'=>'required',
            'month_to'=>'required',
        ]);
        $this->alert('warning','Are you sure you want to send mail',[
            'showConfirmButton'=>true,
            'onConfirmed'=>'confirmed',
            'showCancelButton'=>true,
           'timer'=>90000
        ]);
    }
    public function confirmed()
    {

        $startDate = Carbon::parse ($this->month_from)->format('Y-m-d');
        $endDate = Carbon::parse($this->month_to)->format('Y-m-d');
        $this->payslips=SalaryHistory::where('ip_number',$this->payroll_number)
            ->whereBetween('date_month',[$startDate,$endDate])
            ->orderBy($this->order_by,$this->order)->get();
        $data=[
            'month_from'=>$startDate,
            'month_to'=>$endDate,
            'payroll_number'=>$this->payroll_number
        ];
//        try {
            $emp=EmployeeProfile::where('payroll_number',$this->payroll_number)->first();
            if ( !empty($emp->email)){
                Mail::send(new Payslip($data));
                $this->alert('success','Payslip have been sent');
            }else{
                $this->alert('warning','Employee has no email address');

            }

//        }catch (\Exception $e)
//        {
//            $this->alert('warning','Failed to send email please Check your internet connection!',[
//                'timer'=>9000
//            ]);
//        }

    }
    public function render()
    {
        return view('livewire.reports.report-for-individual')->extends('components.layouts.app');
    }
}
