<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Deduction;
use App\Models\EmployeeProfile;
use App\Models\ReportColumn;
use App\Models\SalaryHistory;
use App\Models\TemporaryDeduction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;

class EmployeeReport extends Controller
{

    public function report(Request $request)
    {
        $this->authorize('can_report');
        $this->validate($request,[
            'report_title'=>'required',
            'sub_title'=>'nullable',
        ]);
        ini_set('memory_limit', '2048M');
        set_time_limit(2000);
        ReportColumn::query()->truncate();
        $employee=new ReportColumn();
        $employee->selected_columns=json_encode($request->report_column);
        $employee->report_title=$request->report_title;
        $employee->subtitle=$request->sub_title;
        $employee->save();
        if (!empty($request->report_column)) {
            $reports = EmployeeProfile::when($request->staff_category, function ($query) {
                return $query->where('staff_category', request()->staff_category);
            })
                ->when($request->unit, function ($query) {
                    return $query->where('unit', request()->unit);
                })
                ->when($request->employee_type, function ($query) {
                    return $query->where('employment_type', request()->employee_type);
                })
                ->when($request->salary_structure, function ($query) {
                    return $query->where('salary_structure', request()->salary_structure);
                })
                ->when($request->grade_level_from, function ($query) {
                    return $query->whereBetween('grade_level', [request()->grade_level_from, request()->grade_level_to]);

                })
                ->when($request->status, function ($query) {
                    return $query->where('status', request()->status);
                })
                ->orderBy($request->order_by, $request->orderAsc)
                ->get();
            if ($reports->count() > 0) {
                $columns = ReportColumn::first();
                $col_json = ReportColumn::first();
                $report_col = json_decode($col_json->selected_columns);

                if (count($report_col) <= 8) {
                    $pdf = Pdf::loadView('reports.employee_report', compact('reports', 'report_col', 'columns'))
                        ->setPaper('A4', 'portrait');
                } else {
                    $pdf = Pdf::loadView('reports.employee_report', compact('reports', 'report_col', 'columns'))
                        ->setPaper('A4', 'landscape');
                }
                $user = Auth::user();
                $log = new ActivityLog();
                $log->user_id = $user->id;
                $log->action = "Have generated employee report";
                $log->save();
                $name = report_file_name() . "_Employee_report_" . Carbon::parse($request->date_from)->format('F Y');

                return $pdf->stream($name . '.pdf');
            } else {
                Alert::warning(no_record(),['timer'=>9200]);
                return back();
            }
        }else{
            Alert::warning("At least one (1) or more column must be selected");
            return back();

        }
    }

    public function generate(Request $request)
    {
        $this->authorize('can_report');
        $this->validate($request,[
            'payroll_number'=>'required',
            'report_type'=>'required',
            'month_from'=>'required',
            'month_to'=>'required'
        ]);
        if($request->report_type==1){
            return $this->payslip($request);
        }elseif ($request->report_type==2){
            return $this->bank_pay($request);
        }elseif($request->report_type==3){
            return $this->deduction($request);
        }else{

        }
    }

    public function payslip(Request $request)
    {
        $startDate = Carbon::parse ($request->month_from)->format('Y-m-d');
        $endDate = Carbon::parse($request->month_to)->format('Y-m-d');
        $payslips=SalaryHistory::where('ip_number',$request->payroll_number)
            ->whereBetween('date_month',[$startDate,$endDate])
            ->orderBy($request->order_by,$request->order)->get();
        if ($payslips->count()>0) {
            $date_from = $request->month_from;
            $date_to = $request->month_to;
            $pdf = Pdf::loadView('reports.individual.payslip', compact('payslips', 'date_to', 'date_from'))
                ->setPaper('A4', 'portrait');
            $user = Auth::user();
            $log = new ActivityLog();
            $log->user_id = $user->id;
            $log->action = "Have generated individual payslip report for $request->payroll_number";
            $log->save();

            $removed = Str::remove('/', $request->payroll_number);
            $name = "$removed _Payslip_" . Carbon::parse($request->month_from)->format('F Y');
            return $pdf->stream($name . '.pdf');
        }
        else{
            Alert::warning("no record found for ".$request->payroll_number);
            return back();
        }
    }
    public function bank_pay(Request $request)
    {
        $startDate = Carbon::parse ($request->month_from)->format('Y-m-d');
        $endDate = Carbon::parse($request->month_to)->format('Y-m-d');
        $banks=SalaryHistory::where('ip_number',$request->payroll_number)
            ->whereBetween('date_month',[$startDate,$endDate])
            ->orderBy($request->order_by,$request->order)
            ->get();
        if ($banks->count()>0){
        $pdf=Pdf::loadView('reports.individual.bank_payment',compact('banks'),['date_from'=>$request->month_from,'date_to'=>$request->month_to])
            ->setPaper('A4','portrait');
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $log->action="Have generated individual bank payment report for $request->payroll_number";
        $log->save();

        $removed = Str::remove('/', $request->payroll_number);

        $name="$removed _Bank_payment_".Carbon::parse($request->month_from)->format('F Y');

        return $pdf->stream($name.'.pdf');
        }
        else{
            Alert::warning("no record found for".$request->payroll_number);
            return back();

        }
    }
    public function deduction(Request $request)
    {

        $startDate = Carbon::parse ($request->month_from)->format('Y-m-d');
        $endDate = Carbon::parse($request->month_to)->format('Y-m-d');
        TemporaryDeduction::query()->truncate();
        $reports= SalaryHistory::where('ip_number',$request->payroll_number)
            ->whereBetween('date_month',[$startDate,$endDate])
            ->orderBy($request->order_by,$request->order)
            ->get();
        if ($reports->count()>0) {
            $insertion_data = array();
            foreach ($reports as $report) {
                $staff = EmployeeProfile::where('payroll_number', $report->ip_number)->first();
                $deductions = Deduction::when($request->deduction, function ($query) {
                    return $query->where('id', request()->deduction);
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
            $deductions = array();
            foreach ($deduct->unique('deduction_id') as $de) {
                array_push($deductions, $de);
            }
            $date_from = $request->month_from;
            $date_to = $request->month_to;
            $pdf = Pdf::loadView('reports.individual.deductions', compact('deductions', 'date_from', 'date_to'))
                ->setPaper('A4', 'portrait');
            $user = Auth::user();
            $log = new ActivityLog();
            $log->user_id = $user->id;
            $log->action = "Have generated individual deduction details for $request->payroll_number";
            $log->save();
            $removed = Str::remove('/', $request->payroll_number);

            $name = "$removed _Deduction_" . Carbon::parse($request->month_from)->format('F Y');

            return $pdf->stream($name . '.pdf');
        }else{
            Alert::warning('No record found for ',$request->payroll_number);
            return back();
        }
    }

    public function payslipMail($payroll_number, $month_from, $month_to)
    {
        $payroll_number=decrypt($payroll_number);
        $month_from=decrypt($month_from);
        $month_to=decrypt($month_to);
        $payslips=SalaryHistory::where('ip_number',$payroll_number)
            ->whereBetween('date_month',[$month_from,$month_to])
           ->get();
        $date_from=$month_from;
        $date_to=$month_to;
        $pdf=Pdf::loadView('reports.individual.payslip',compact('payslips','date_to','date_from'))
            ->setPaper('A4','portrait');
        $removed = Str::remove('/', $payroll_number);

        $name="$removed _Payslip_".$date_from;

        return $pdf->download($name.'.pdf');
    }
    public function download($file){

        return response()->download(storage_path('/app/export/'.$file));
    }
}
