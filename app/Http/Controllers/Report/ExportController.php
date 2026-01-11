<?php

namespace App\Http\Controllers\Report;

use App\Exports\PayrollExport;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Deduction;
use App\Models\EmployeeProfile;
use App\Models\SalaryHistory;
use App\Models\TemporaryDeduction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{

    public function payroll_report()
    {

        ini_set('memory_limit', '2048M');
        set_time_limit(2000);

        $reports=SalaryHistory::when($this->salary_structure,function ($query,){
            return $query->where('salary_structure',ss($this->salary_structure));
        })
            ->when($this->department,function ($query){
                return $query->where('department',dept($this->department));
            })
            ->when($this->employee_type,function ($query){
                return $query->where('employment_type',emp_type($this->employee_type));
            })
            ->when($this->staff_category,function ($query){
                return $query->where('staff_category',staff_cat($this->staff_category));
            })

            ->when($this->grade_level_from,function ($query){
                return $query->whereBetween('grade_level',[$this->grade_level_from,$this->grade_level_to]);
            })
            ->when($this->staff_number,function ($query){
                return $query->where('pf_number',$this->staff_number);
            })
            ->whereBetween('salary_month', [Carbon::parse($this->date_from)->format('F'),Carbon::parse($this->date_to)->format('F')])
            ->whereBetween('salary_year', [Carbon::parse($this->date_from)->format('Y'),Carbon::parse($this->date_to)->format('Y')])
            ->orderBy("$this->order_by",$this->order)
            ->get()->groupBy($this->group_by);
        $summaries=SalaryHistory::when($this->salary_structure,function ($query,){
            return $query->where('salary_structure',ss($this->salary_structure));
        })
            ->when($this->department,function ($query){
                return $query->where('department',dept($this->department));
            })
            ->when($this->employee_type,function ($query){
                return $query->where('employment_type',emp_type($this->employee_type));
            })
            ->when($this->staff_category,function ($query){
                return $query->where('staff_category',staff_cat($this->staff_category));
            })

            ->when($this->grade_level_from,function ($query){
                return $query->whereBetween('grade_level',[$this->grade_level_from,$this->grade_level_to]);
            })
            ->when($this->staff_number,function ($query){
                return $query->where('pf_number',$this->staff_number);
            })
            ->whereBetween('salary_month', [Carbon::parse($this->date_from)->format('F'),Carbon::parse($this->date_to)->format('F')])
            ->whereBetween('salary_year', [Carbon::parse($this->date_from)->format('Y'),Carbon::parse($this->date_to)->format('Y')])
            ->get();
        $payrolls=array();
        foreach ($reports as $report)
        {
            array_push($payrolls,$report);
        }
        $payrolls=collect($payrolls);

        $name_search=$this->group_by;
        $name=$this->group_by;
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $log->action="Have exported payroll report";
        $log->save();
        return Excel::download(new PayrollExport([$payrolls,$summaries,$name,$name_search]), 'payroll.xlsx');

//        return view('reports.payroll_report',compact('payrolls','name_search','name','summaries'));


    }

    public function deduction_schedule()
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(2000);
        TemporaryDeduction::query()->truncate();
        $reports = SalaryHistory::when($this->salary_structure, function ($query,) {
            return $query->where('salary_structure', ss($this->salary_structure));
        })
            ->when($this->department, function ($query) {
                return $query->where('department', dept($this->department));
            })
            ->when($this->employee_type, function ($query) {
                return $query->where('employment_type', emp_type($this->employee_type));
            })
            ->when($this->staff_category, function ($query) {
                return $query->where('staff_category', staff_cat($this->staff_category));
            })
            ->when($this->grade_level_from, function ($query) {
                return $query->whereBetween('grade_level', [$this->grade_level_from, $this->grade_level_to]);
            })
            ->when($this->staff_number, function ($query) {
                return $query->where('pf_number', $this->staff_number);
            })
            ->whereBetween('salary_month', [Carbon::parse($this->date_from)->format('F'), Carbon::parse($this->date_to)->format('F')])
            ->whereBetween('salary_year', [Carbon::parse($this->date_from)->format('Y'), Carbon::parse($this->date_to)->format('Y')])
            ->limit('100')
            ->get();

        $insertion_data = array();
        foreach ($reports as $report) {
            $staff = EmployeeProfile::where('staff_number', $report->pf_number)->first();
            $deductions = Deduction::where('status',1)->get();
            foreach ($deductions as $index => $deduction) {
                $new_data = [
                    'history_id' => $report->id,
                    'deduction_id' => $deduction->id,
                    'staff_number' => $report->pf_number,
                    'staff_name' => $report->full_name,
                    'amount' => $report['D' . $deduction->id],
                ];
                $insertion_data[] = $new_data;
            }
        }
        $insertion_data = collect($insertion_data);
        $data_to_insert = $insertion_data->chunk(500);
        foreach ($data_to_insert as $key => $data) {
            try {
                DB::table('temporary_deductions')->insert($data
                    ->toArray());
            } catch (\Illuminate\Database\QueryException $e) {
                $error = $e->getMessage();
//                echo $error;
            }
        }
        if ($this->order_by !=''){
            $this->orderBy=$this->order_by;
        }
        $reports_data=TemporaryDeduction::
//        when($this->group_by,function ($query){
//            return $query->where('deduction_id',$this->group_by);
//        })

        orderBy("$this->orderBy", $this->order)
            ->get()
            ->groupBy('deduction_id');


        $date_from=$this->date_from;
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $log->action="Have generated deduction detail report";
        $log->save();

        return view('reports.deduction_schedule',['reports'=>$reports_data,'date_from'=>$date_from]);

    }

}
