<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Deduction;
use App\Models\EmployeeProfile;
use App\Models\SalaryHistory;
use App\Models\TemporaryDeduction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;

class StaffPayroll extends Controller
{

    public function generate(Request $request)
    {
        $this->validate($request,[
            'month_from'=>'required',
            'month_to'=>'required',
        ]);

        if($request->report_type==1){
            return $this->payslip($request);
        }elseif ($request->report_type==2){
            return $this->bank_pay($request);
        }else{
            return $this->deduction($request);
        }
    }

    public function payslip(Request $request)
    {
        $user=Auth::user()->username;
        $user=EmployeeProfile::where('email',$user)->first()->payroll_number;
        $payroll=$user;
        $startDate = Carbon::parse ($request->month_from)->format('Y-m-d');
        $endDate = Carbon::parse($request->month_to)->format('Y-m-d');
        $payslips=SalaryHistory::where('ip_number',$user)
            ->whereBetween('date_month',[$startDate,$endDate])
           ->get();
        $date_from=$request->month_from;
        $date_to=$request->month_to;
        if ($payslips->count()>0) {
            $pdf = Pdf::loadView('reports.individual.payslip', compact('payslips', 'date_to', 'date_from'))
                ->setPaper('A4', 'portrait');
            $user = Auth::user();
            $log = new ActivityLog();
            $log->user_id = $user->id;
            $log->action = "Have generated individual payslip report for $request->payroll_number";
            $log->save();
            $removed = Str::remove('/', $payroll);

            $name = "$removed _Payslip_" . Carbon::parse($request->month_from)->format('F Y');

            return $pdf->stream($name . '.pdf');
        }else{
            Alert::warning("No record found");
            return  back();
        }
    }
    public function bank_pay(Request $request)
    {
        $user=Auth::user()->username;
        $user=EmployeeProfile::where('email',$user)->first()->payroll_number;

        $payroll=$user;
        $startDate = Carbon::parse ($request->month_from)->format('Y-m-d');
        $endDate = Carbon::parse($request->month_to)->format('Y-m-d');
        $banks=SalaryHistory::where('ip_number',$user)
            ->whereBetween('date_month',[$startDate,$endDate])
            ->get();
        if ($banks->count()>0){
        $pdf=Pdf::loadView('reports.individual.bank_payment',compact('banks'),['date_from'=>$request->month_from,'date_to'=>$request->month_to])
            ->setPaper('A4','portrait');
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $log->action="Have generated individual bank payment report for $request->payroll_number";
        $log->save();
        $removed = Str::remove('/', $payroll);

        $name="$removed _Bank_payment_".Carbon::parse($request->month_from)->format('F Y');

        return $pdf->stream($name.'.pdf');
        }else{
            Alert::warning("No record found");
            return  back();
        }
    }
    public function deduction(Request $request)
    {
        $user=Auth::user()->username;
        $user=EmployeeProfile::where('email',$user)->first()->payroll_number;

        $payroll=$user;

        $startDate = Carbon::parse ($request->month_from)->format('Y-m-d');
        $endDate = Carbon::parse($request->month_to)->format('Y-m-d');
        TemporaryDeduction::query()->truncate();
        $reports= SalaryHistory::where('ip_number',$user)
            ->whereBetween('date_month',[$startDate,$endDate])
            ->get();
        if ($reports->count()>0){
        $insertion_data = array();
        foreach ($reports as $report) {
            $staff = EmployeeProfile::where('payroll_number', $report->ip_number)->first();
            $deductions = Deduction::when($request->deduction,function ($query){return $query->where('id',request()->deduction);})->get();
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
        $deductions=array();
        foreach ($deduct->unique('deduction_id') as $de){
            array_push($deductions,$de);
        }
        $date_from=$request->month_from;
        $date_to=$request->month_to;
        $pdf=Pdf::loadView('reports.individual.deductions',compact('deductions','date_from','date_to'))
            ->setPaper('A4','portrait');
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $log->action="Have generated individual deduction details for $request->payroll_number";
        $log->save();
        $removed = Str::remove('/', $payroll);

        $name="$removed _Deduction_".Carbon::parse($request->month_from)->format('F Y');

        return $pdf->stream($name.'.pdf');
        }else{
            Alert::warning("No record found");
            return  back();
        }
    }
}
