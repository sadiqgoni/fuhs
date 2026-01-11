<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;

use App\Models\ActivityLog;
use App\Models\AnnualSalaryIncrement;
use App\Models\Bank;
use App\Models\Deduction;
use App\Models\EmployeeProfile;
use App\Models\LoanDeductionCountdownHistory;
use App\Models\PFA;
use App\Models\ReportRepository;
use App\Models\SalaryHistory;
use App\Models\TemporaryBankPaymentSummary;
use App\Models\TemporaryDeduction;
use App\Models\TemporatyBankPaymentReport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use function Laravel\Prompts\select;


class ReportController extends Controller
{
public   $total_deduct=0;
public $orderBy='id';


    public function report(Request $request)
    {
        $this->authorize('can_report');
        $this->validate($request,[
            'report_type'=>'required|present',
            'date_from'=>'required|present',
            'date_to'=>'required|present'
        ]);

        if ($request->report_type ==1){
            return
            $this->payroll_report($request);
        }elseif ($request->report_type==2){
            return
            $this->pay_slip($request);
        }elseif ($request->report_type==3){
            return
            $this->bank_payment_report($request);
        }elseif ($request->report_type==4){
            return
            $this->deduction_schedule($request);
        }elseif ($request->report_type==5){
            return
            $this->salary_deduction_summary($request);
        }elseif ($request->report_type==6){
            return
            $this->bank_summary($request);
        }elseif ($request->report_type==7){
            return
            $this->salary_journal($request);
        }elseif ($request->report_type==8){
            return
                $this->pfa($request);
        }elseif ($request->report_type==9){
            return
                $this->nhis($request);
        }elseif ($request->report_type==10){
            return
                $this->employer_pension($request);
        }
    }
    public function payroll_report(Request $request)
    {
        if ($request->order_by != ''){
            $this->orderBy=$request->order_by;
        }
        $arrange="department";
        if ($request->order_by ==''){
            $request->orderBy="id";
        }
        if ($request->group_by == "unit"){
            $arrange="unit";
        }elseif($request->group_by =="department"){
            $arrange="department";
        }
        ini_set('memory_limit', '2048M');
        set_time_limit(2000);

        $reports=SalaryHistory::when($request->salary_structure,function ($query){
            return $query->where('salary_structure',ss(request()->salary_structure));
        })
            ->when($request->department,function ($query){
                return $query->where('department',dept(request()->department));
            })
            ->when($request->employee_type,function ($query){
                return $query->where('employment_type',emp_type(request()->employee_type));
            })
            ->when($request->staff_category,function ($query){
                return $query->where('staff_category',staff_cat(request()->staff_category));
            })

            ->when($request->grade_level_from,function ($query){
                return $query->whereBetween('grade_level',[request()->grade_level_from,request()->grade_level_to]);
            })
            ->when($request->staff_number,function ($query){
                return $query->where('pf_number',request()->staff_number);
            })

            ->whereBetween('salary_month', [Carbon::parse($request->date_from)->format('F'),Carbon::parse($request->date_to)->format('F')])
            ->whereBetween('salary_year', [Carbon::parse($request->date_from)->format('Y'),Carbon::parse($request->date_to)->format('Y')])
            ->orderBy($request->group_by)->orderBy($this->orderBy,$request->order)->get()->groupBy($request->group_by)->sortKeys();




        $summaries=SalaryHistory::when($request->salary_structure,function ($query,){
            return $query->where('salary_structure',ss(request()->salary_structure));
        })
            ->when($request->department,function ($query){
                return $query->where('department',dept(request()->department));
            })
            ->when($request->employee_type,function ($query){
                return $query->where('employment_type',emp_type(request()->employee_type));
            })
            ->when($request->staff_category,function ($query){
                return $query->where('staff_category',staff_cat(request()->staff_category));
            })

            ->when($request->grade_level_from,function ($query){
                return $query->whereBetween('grade_level',[request()->grade_level_from,request()->grade_level_to]);
            })
            ->when($request->staff_number,function ($query){
                return $query->where('pf_number',request()->staff_number);
            })
            ->whereBetween('salary_month', [Carbon::parse($request->date_from)->format('F'),Carbon::parse($request->date_to)->format('F')])
            ->whereBetween('salary_year', [Carbon::parse($request->date_from)->format('Y'),Carbon::parse($request->date_to)->format('Y')])
           ->get();
        if ($reports->count()>0){
            $payrolls=array();
            foreach ($reports as $report)
            {
                array_push($payrolls,$report);
            }
            $payrolls=collect($payrolls);

            $name_search=$request->group_by;
            $name=$request->group_by;
//        dd($payrolls);
            $document= Pdf::loadView('reports.payroll_dompdf',compact('payrolls','name_search','name','summaries'))
                ->setPaper('a3','landscape');

            $file_name=report_file_name()."_Payroll_".Carbon::parse($request->date_from)->format('F Y');

            $content = $document->download()->getOriginalContent();
            Storage::put($file_name.'.pdf',$content);
            if(ReportRepository::where('date',$request->date_from)->where('report_type',$request->report_type)->exists())
            {

            }else {
                $repo = new ReportRepository();
                $repo->report_type=$request->report_type;
                $repo->date=$request->date_from;
                $repo->report_name="Payroll Report";
                $repo->location=$file_name.'.pdf';
                $repo->save();
            }

            $user=Auth::user();
            $log=new ActivityLog();
            $log->user_id=$user->id;
            $log->action="Have generated payroll report";
            $log->save();
            $pdfContent = $document->output();
            Storage::disk('public')->put("$file_name.pdf", $pdfContent);
            return    $document->stream($file_name."pdf");
        }else{
            Alert::warning(no_record());
            return back();
        }



//        return view('reports.payroll_report',compact('payrolls','name_search','name','summaries'));
//        return \Spatie\LaravelPdf\Facades\Pdf::view('reports.payroll_report',compact('payrolls','name_search','name','summaries'))
//            ->format('a4')
//            ->name('payroll.pdf');
//        $pdf=Pdf::loadView('reports.payroll_report',compact('payrolls','name_search','name'))
//            ->setPaper('a3','landscape');
//        $pdf->stream('payroll_report'. "pdf");

    }
    public function pay_slip(Request $request)
    {
        if ($request->order_by != ''){
            $this->orderBy=$request->order_by;
        }
        ini_set('memory_limit', '2048M');
        set_time_limit(2000);

        $paySlips=SalaryHistory::when($request->salary_structure,function ($query,){
            return $query->where('salary_structure',ss(request()->salary_structure));
        })
            ->when($request->department,function ($query){
                return $query->where('department',dept(request()->department));
            })
            ->when($request->employee_type,function ($query){
                return $query->where('employment_type',emp_type(request()->employee_type));
            })
            ->when($request->staff_category,function ($query){
                return $query->where('staff_category',staff_cat(request()->staff_category));
            })

            ->when($request->grade_level_from,function ($query){
                return $query->whereBetween('grade_level',[request()->grade_level_from,request()->grade_level_to]);
            })
            ->when($request->staff_number,function ($query){
                return $query->where('pf_number',request()->staff_number);
            })
            ->whereBetween('salary_month', [Carbon::parse($request->date_from)->format('F'),Carbon::parse($request->date_to)->format('F')])

            ->whereBetween('salary_year', [Carbon::parse($request->date_from)->format('Y'),Carbon::parse($request->date_to)->format('Y')])
//            ->limit(4)
            ->get()->groupBy($request->group_by);
        if ($paySlips->count()>0){
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $log->action="Have generated payslip";
        $log->save();
//        {{dd()}}
//        return view('reports.pay_slip',compact('paySlips',));
        $a= Pdf::loadView('reports.pay_slip',compact('paySlips'))
            ->setPaper('a4','portrait');
        $name=report_file_name()."_Pay_slip_".Carbon::parse($request->date_from)->format('F Y');

//            $content = $a->download()->getOriginalContent();
//            Storage::put($name.'.pdf',$content);
            Storage::put('public/repository/'.$name.'.pdf', $a->output());
            if(ReportRepository::where('date',$request->date_from)->where('report_type',$request->report_type)->exists())
            {

            }else {
                $repo = new ReportRepository();
                $repo->report_type=$request->report_type;
                $repo->date=$request->date_from;
                $repo->report_name="Payslip";
                $repo->location='public/repository/'.$name.'.pdf';
                $repo->save();
            }
        return $a->stream($name.'.pdf');
        }else{
            Alert::warning(no_record());
            return back();
        }
    }
    public function deduction_schedule(Request $request)
    {
        if ($request->order_by != ''){
            $this->orderBy=$request->order_by;
        }
        ini_set('memory_limit', '2048M');
        set_time_limit(2000);
        TemporaryDeduction::query()->truncate();
        $reports = SalaryHistory::when($request->salary_structure, function ($query,) {
            return $query->where('salary_structure', ss(request()->salary_structure));
        })
            ->when($request->department, function ($query) {
                return $query->where('department', dept(request()->department));
            })
            ->when($request->employee_type, function ($query) {
                return $query->where('employment_type', emp_type(request()->employee_type));
            })
            ->when($request->staff_category, function ($query) {
                return $query->where('staff_category', staff_cat(request()->staff_category));
            })
            ->when($request->grade_level_from, function ($query) {
                return $query->whereBetween('grade_level', [request()->grade_level_from, request()->grade_level_to]);
            })

            ->whereBetween('salary_month', [Carbon::parse($request->date_from)->format('F'), Carbon::parse($request->date_to)->format('F')])
            ->whereBetween('salary_year', [Carbon::parse($request->date_from)->format('Y'), Carbon::parse($request->date_to)->format('Y')])
//           ->limit('100')
            ->get();
        if ($reports->count()>0){
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
        if ($request->order_by !=''){
            $this->orderBy=$request->order_by;
        }
        $reports_data=TemporaryDeduction::
        when($request->group_by,function ($query){
            return $query->where('deduction_id',request()->group_by);
        })

        ->orderBy("$this->orderBy", $request->order)
            ->get()
            ->groupBy('deduction_id');


        $date_from=$request->date_from;
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $log->action="Have generated deduction detail report";
        $log->save();
        $document= Pdf::loadView('reports.deduction_schedule',['reports'=>$reports_data,'date_from'=>$date_from])
            ->setPaper('a4','portrait');
        $name=report_file_name()."_Deduction_schedule_".Carbon::parse($request->date_from)->format('F Y');

            Storage::put('public/repository/'.$name.'.pdf', $document->output());
            if(ReportRepository::where('date',$request->date_from)->where('report_type',$request->report_type)->exists())
            {

            }else {
                $repo = new ReportRepository();
                $repo->report_type=$request->report_type;
                $repo->date=$request->date_from;
                $repo->report_name="Deduction Schedule";
                $repo->location='public/repository/'.$name.'.pdf';
                $repo->save();
            }
        return $document->stream($name.'.pdf');
//        return view('reports.deduction_schedule',['reports'=>$reports_data,'date_from'=>$date_from]);
        }else{
            Alert::warning(no_record());
            return back();
        }

    }


    public function salary_deduction_summary(Request $request)
    {
        if ($request->order_by != ''){
            $this->orderBy=$request->order_by;
        }
        ini_set('memory_limit', '2048M');
        set_time_limit(2000);
        TemporaryDeduction::query()->truncate();
        $reports = SalaryHistory::when($request->salary_structure, function ($query,) {
            return $query->where('salary_structure', ss(request()->salary_structure));
        })
            ->when($request->department, function ($query) {
                return $query->where('department', dept(request()->department));
            })
            ->when($request->employee_type, function ($query) {
                return $query->where('employment_type', emp_type(request()->employee_type));
            })
            ->when($request->staff_category, function ($query) {
                return $query->where('staff_category', staff_cat(request()->staff_category));
            })
            ->when($request->grade_level_from, function ($query) {
                return $query->whereBetween('grade_level', [request()->grade_level_from, request()->grade_level_to]);
            })
            ->when($request->staff_number, function ($query) {
                return $query->where('pf_number', request()->staff_number);
            })
            ->whereBetween('salary_month', [Carbon::parse($request->date_from)->format('F'), Carbon::parse($request->date_to)->format('F')])
            ->whereBetween('salary_year', [Carbon::parse($request->date_from)->format('Y'), Carbon::parse($request->date_to)->format('Y')])
//           ->limit('2')
            ->get();
        if ($reports->count()>0){
        $insertion_data = array();
        foreach ($reports as $report) {
            $staff = EmployeeProfile::where('staff_number', $report->pf_number)->first();
            $deductions =Deduction::where('status',1)->get();

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
        $data_to_insert = $insertion_data->chunk(1000);
        foreach ($data_to_insert as $key => $data) {
            try {
                DB::table('temporary_deductions')->insert($data
                    ->toArray());
            } catch (\Illuminate\Database\QueryException $e) {
                $error = $e->getMessage();
//                echo $error;
            }
        }
            $reports = SalaryHistory::join('temporary_deductions', 'temporary_deductions.history_id', 'salary_histories.id')
                ->select([
                    'salary_histories.pf_number',
                    'salary_histories.salary_month',
                    'salary_histories.salary_year',
                    'salary_histories.full_name',
                    'salary_histories.id as history_id',
                    'salary_histories.department',
                    'salary_histories.employment_type',
                    'salary_histories.staff_category',
                    'salary_histories.unit',
                    'salary_histories.salary_structure',
                    'salary_histories.grade_level',
                    'temporary_deductions.deduction_id',
                    'temporary_deductions.amount',
                ])

                ->whereBetween('salary_month', [Carbon::parse($request->date_from)->format('F'),Carbon::parse($request->date_to)->format('F')])

                ->whereBetween('salary_year', [Carbon::parse($request->date_from)->format('Y'),Carbon::parse($request->date_to)->format('Y')])

                //            ->orderBy("$request->order_by",$request->order ? 'asc' : 'desc')
                ->get()->groupBy('deduction_id')->all();
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $log->action="Have generated deduction summary report";
        $log->save();
            $document= Pdf::loadView('reports.salary_deduction_summary',compact('reports'),['date_from'=>$request->date_from,'date_to'=>$request->date_to])
                ->setPaper('a4','portrait');

        $name=report_file_name()."_Deduction_schedule_summary_".Carbon::parse($request->date_from)->format('F Y');
            Storage::put('public/repository/'.$name.'.pdf', $document->output());
            if(ReportRepository::where('date',$request->date_from)->where('report_type',$request->report_type)->exists())
            {

            }else {
                $repo = new ReportRepository();
                $repo->report_type=$request->report_type;
                $repo->date=$request->date_from;
                $repo->report_name="Deduction Summary";
                $repo->location='public/repository/'.$name.'.pdf';
                $repo->save();
            }
        return $document->stream($name.'.pdf');
        }else{
            Alert::warning(no_record());
            return back();
        }
    }
    public function salary_journal(Request $request)
    {
        if ($request->order_by != ''){
            $this->orderBy=$request->order_by;
        }
        $reports=SalaryHistory::when($request->salary_structure,function ($query,){
            return $query->where('salary_structure',ss(request()->salary_structure));
        })
            ->when($request->department,function ($query){
                return $query->where('department',dept(request()->department));
            })
            ->when($request->employee_type,function ($query){
                return $query->where('employment_type',emp_type(request()->employee_type));
            })
            ->when($request->staff_category,function ($query){
                return $query->where('staff_category',staff_cat(request()->staff_category));
            })

            ->when($request->unit,function ($query){
                return $query->where('unit',request()->unit);
            })
            ->when($request->grade_level_from,function ($query){
                return $query->whereBetween('grade_level',[request()->grade_level_from,request()->grade_level_to]);
            })

            ->whereBetween('salary_month', [Carbon::parse($request->date_from)->format('F'),Carbon::parse($request->date_to)->format('F')])

            ->whereBetween('salary_year', [Carbon::parse($request->date_from)->format('Y'),Carbon::parse($request->date_to)->format('Y')])

//            ->orderBy("$request->order_by",$request->order ? 'asc' : 'desc')
            ->get();

//        return view('reports.salary_journal',compact('reports'));
        if($reports->count() > 0){
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $log->action="Have generated salary journal report";
        $log->save();
        $document= Pdf::loadView('reports.salary_journal',compact('reports'))
            ->setPaper('a4','portrait');

        $name=report_file_name()."_Salary_journal_".Carbon::parse($request->date_from)->format('F Y');
            Storage::put('public/repository/'.$name.'.pdf', $document->output());
            if(ReportRepository::where('date',$request->date_from)->where('report_type',$request->report_type)->exists())
            {

            }else {
                $repo = new ReportRepository();
                $repo->report_type=$request->report_type;
                $repo->date=$request->date_from;
                $repo->report_name="Salary Journal";
                $repo->location='public/repository/'.$name.'.pdf';
                $repo->save();
            }
        return $document->stream($name.'.pdf');
        }else{
            Alert::warning(no_record());
            return back();
        }
    }
    public function bank_summary(Request $request)
    {
        if ($request->order_by != ''){
            $this->orderBy=$request->order_by;
        }
        TemporatyBankPaymentReport::query()->truncate();
        TemporaryBankPaymentSummary::query()->truncate();
        ini_set('memory_limit', '2048M');
        set_time_limit(2000);
        $histories=SalaryHistory::
   whereBetween('salary_month', [Carbon::parse($request->date_from)->format('F'),Carbon::parse($request->date_to)->format('F')])
            ->whereBetween('salary_year', [Carbon::parse($request->date_from)->format('Y'),Carbon::parse($request->date_to)->format('Y')])
            ->orderBy("bank_name",'asc')
            ->get()->groupBy('bank_code');
            if ($histories->count()>0){
        foreach ($histories as $index=>$salaryObj) {
            $bank = Bank::find($index);
            $a = array();
            foreach ($salaryObj as $key => $item) {

                $b = [
                    'account_number' => $item->account_number,
                    'amount' => $item->net_pay,
                    'bank' => $item->bank_name,
                    'branch' => $bank->branch??null,
                    'sort_code' => $bank->sort_code??null,
                    'remark' => $item->salary_remark,
                    'staff_number' => $item->pf_number,
                    'ipp_no' => $item->ip_number,
                    'staff_name' => $item->full_name,
                    'bank_code' => $item->bank_code,
                ];
                $a[] = $b;
            }
            $a = collect($a);
            $data_to_insert = $a->chunk(100);
            foreach ($data_to_insert as $key => $data) {
                try {
                    DB::table('temporary_bank_payments')->insert($data
                        ->toArray());
                } catch (\Illuminate\Database\QueryException $e) {
                    $error = $e->getMessage();
                    echo $error;
                }
            }

            $deductions=Deduction::where('bank_code',$index)->where('visibility',1)
//                ->where('code','>',0)
                ->get();
            if (is_null($request->staff_number)){

                foreach ($deductions as $deduction){
                    $salObj=SalaryHistory::

                    whereBetween('salary_month', [Carbon::parse($request->date_from)->format('F'),Carbon::parse($request->date_to)->format('F')])
                        ->whereBetween('salary_year', [Carbon::parse($request->date_from)->format('Y'),Carbon::parse($request->date_to)->format('Y')])
                    ->get();
                    $total_deduction= $salObj->sum('D'.$deduction->id);
                    $tempo=new TemporatyBankPaymentReport();
                    $tempo->account_number=$deduction->account_no;
                    $tempo->amount=$total_deduction;
                    $tempo->bank=$salObj->where('bank_code',$deduction->bank_code)->first()->bank_name;
                    $tempo->branch="";
                    $tempo->sort_code="";
                    $tempo->bank_code=$deduction->bank_code;
                    $tempo->remark=$salObj->where('bank_code',$deduction->bank_code)->first()->salary_remark;
                    $tempo->staff_number="";
                    $tempo->staff_name=$deduction->deduction_name;
                    $tempo->save();
                }
            }

        }
        $banks_only=Deduction::where('visibility',1)->get();
        foreach ($banks_only as $deduction){
            $salaryDObj=SalaryHistory::

                whereBetween('salary_month', [Carbon::parse($request->date_from)->format('F'),Carbon::parse($request->date_to)->format('F')])
                ->whereBetween('salary_year', [Carbon::parse($request->date_from)->format('Y'),Carbon::parse($request->date_to)->format('Y')])->get();
            $salaryDObjCheck=SalaryHistory::

                whereBetween('salary_month', [Carbon::parse($request->date_from)->format('F'),Carbon::parse($request->date_to)->format('F')])
                ->whereBetween('salary_year', [Carbon::parse($request->date_from)->format('Y'),Carbon::parse($request->date_to)->format('Y')]);
            if ($salaryDObjCheck->where('bank_code',$deduction->bank_code)->exists()){
                continue;
            }else{
                if ($request->group_by == null){
                        $total_deduction= $salaryDObj->sum('D'.$deduction->id);
                        try {
                            $bank=Bank::find($deduction->bank_code);
                            $tempo=new TemporatyBankPaymentReport();
                            $tempo->account_number=$deduction->account_no;
                            $tempo->amount=$total_deduction;
                            $tempo->bank=$bank->bank_name??null;
                            $tempo->bank_code=$bank->bank_code??null;
                            $tempo->branch="";
                            $tempo->sort_code="";
                            $tempo->remark=$salaryDObj->first()->salary_remark;
                            $tempo->staff_number="";
                            $tempo->staff_name=$deduction->deduction_name;
                            $tempo->save();
                        }catch (\Exception $e)
                        {}

                }

            }

        }

        $banks=Bank::where('status',1)->get();
        foreach ($banks as $bank)
        {
            $tempo_bank=TemporatyBankPaymentReport::where('bank_code',$bank->id)->get();
            $summary=new TemporaryBankPaymentSummary();
            $summary->bank_code=$bank->code??null;
            $summary->bank_name=$bank->bank_name??null;
            $summary->amount=$tempo_bank->sum('amount');
            $summary->branch=$bank->branch??null;
            $summary->save();
        }
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $log->action="Have generated bank summary report";
        $log->save();
        $reports=TemporaryBankPaymentSummary::orderBy('bank_name','asc')->get();
        $a= Pdf::loadView('reports.bank_summary',compact('reports'),['date_from'=>$request->date_from,'date_to'=>$request->date_to])
            ->setPaper('a4','portrait');

        $name=report_file_name()."_Bank_summary_".Carbon::parse($request->date_from)->format('F Y');
                Storage::put('public/repository/'.$name.'.pdf', $a->output());
                if(ReportRepository::where('date',$request->date_from)->where('report_type',$request->report_type)->exists())
                {

                }else {
                    $repo = new ReportRepository();
                    $repo->report_type=$request->report_type;
                    $repo->date=$request->date_from;
                    $repo->report_name="Bank Summary";
                    $repo->location='public/repository/'.$name.'.pdf';
                    $repo->save();
                }
        return $a->stream($name.'.pdf');
            }else{
                Alert::warning(no_record());
                return back();
            }
    }
    public function bank_payment_report(Request $request){
        if ($request->order_by != ''){
            $this->orderBy=$request->order_by;
        }
        TemporatyBankPaymentReport::query()->truncate();
        TemporaryBankPaymentSummary::query()->truncate();
        ini_set('memory_limit', '2048M');
        set_time_limit(2000);
        $histories=SalaryHistory::when($request->group_by,function ($query){
            return $query->where('bank_code',request()->group_by);
        })
            ->when($request->salary_structure,function ($query,){
                return $query->where('salary_structure',ss(request()->salary_structure));
            })
            ->when($request->department,function ($query){
                return $query->where('department',dept(request()->department));
            })
            ->when($request->employee_type,function ($query){
                return $query->where('employment_type',emp_type(request()->employee_type));
            })
            ->when($request->staff_category,function ($query){
                return $query->where('staff_category',staff_cat(request()->staff_category));
            })
            ->when($request->staff_number,function ($query){
                return $query->where('pf_number',request()->staff_number);
            })
            ->when($request->unit,function ($query){
                return $query->where('unit',request()->unit);
            })
            ->when($request->grade_level_from,function ($query){
                return $query->whereBetween('grade_level',[request()->grade_level_from,request()->grade_level_to]);
            })
            ->when($request->group_by,function ($query){
                return $query->where('bank_code',request()->group_by);
            })
            ->whereBetween('salary_month', [Carbon::parse($request->date_from)->format('F'),Carbon::parse($request->date_to)->format('F')])
            ->whereBetween('salary_year', [Carbon::parse($request->date_from)->format('Y'),Carbon::parse($request->date_to)->format('Y')])
            ->orderBy("bank_name",'asc')
            ->get()->groupBy('bank_code');
        if ($histories->count()>0){
        $un_found_ded=array();
            foreach ($histories as $index=>$history){
               foreach ($history as $history){
                   $bank=Bank::find($history->bank_code);
                   $temporary_bank_payment=new TemporatyBankPaymentReport();
                   $temporary_bank_payment->account_number=$history->account_number;
                   $temporary_bank_payment->amount=$history->net_pay;
                   $temporary_bank_payment->bank=$history->bank_name;
                   $temporary_bank_payment->branch=$bank->branch_name??'';
                   $temporary_bank_payment->sort_code='';
                   $temporary_bank_payment->bank_code=$history->bank_code;
                   $temporary_bank_payment->remark=$history->salary_remark;
                   $temporary_bank_payment->staff_number=$history->pf_number;
                   $temporary_bank_payment->ipp_no=$history->ip_number;
                   $temporary_bank_payment->staff_name=$history->full_name;
                   $temporary_bank_payment->save();
               }
                $deductions=Deduction::where('bank_code',$index)->where('visibility',1)->get();
                foreach ($deductions as $deduction){
                    $salaryObj=SalaryHistory::sum("D$deduction->id");
                    $bank=Bank::find($deduction->bank_code);
                    if ($salaryObj >0 ){
                        $temporary_bank_payment=new TemporatyBankPaymentReport();
                        $temporary_bank_payment->account_number=$deduction->account_no;
                        $temporary_bank_payment->amount=$salaryObj;
                        $temporary_bank_payment->bank=$bank?$bank->bank_name:'';
                        $temporary_bank_payment->branch=$bank?$bank->bank_branch:'';
                        $temporary_bank_payment->sort_code='';
                        $temporary_bank_payment->bank_code=$history->bank_code;
                        $temporary_bank_payment->remark=$history->salary_remark;
                        $temporary_bank_payment->staff_number='';
                        $temporary_bank_payment->ipp_no='';
                        $temporary_bank_payment->staff_name=$deduction->deduction_name;
                        $temporary_bank_payment->save();
                    }
                }
                $deductionObj=Deduction::where('bank_code','!=',$index)->get();
                array_push($un_found_ded,$deductionObj);


            }
//        foreach ($un_found_ded as $index=>$item)
//        {
//            try {
//                $id=($item[$index]['id']);
//                $id="D".$id;
//                $salaryObj=SalaryHistory::sum($id);
//                $bank=Bank::find($item[$index]["bank_code"]);
//                if ($salaryObj > 0 ){
//                    $temporary_bank_payment=new TemporatyBankPaymentReport();
//                    $temporary_bank_payment->account_number=$item[$index]["account_no"];
//                    $temporary_bank_payment->amount=$salaryObj;
//                    $temporary_bank_payment->bank=$bank?$bank->bank_name:'';
//                    $temporary_bank_payment->branch=$bank?$bank->bank_branch:'';
//                    $temporary_bank_payment->sort_code='';
//                    $temporary_bank_payment->bank_code=$history->bank_code;
//                    $temporary_bank_payment->remark=$history->salary_remark;
//                    $temporary_bank_payment->staff_number='';
//                    $temporary_bank_payment->ipp_no='';
//                    $temporary_bank_payment->staff_name=$item[$index]["deduction_name"];
//                    $temporary_bank_payment->save();
//                }
//
//            }catch (\Exception $e){}
//        }

        if ($request->order_by ==''){
            $ord="bank";
        }else{
            $ord=$request->order_by;
        }
        $reports=TemporatyBankPaymentReport::orderBy("$ord","$request->order")->get();

        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $log->action="Have generated bank payment report";
        $log->save();
        $document= Pdf::loadView('reports.bank_payment_report',compact('reports'),['date_from'=>$request->date_from,'date_to'=>$request->date_to])
            ->setPaper('a4','landscape');

        $name=report_file_name()."_Bank_payment_report_".Carbon::parse($request->date_from)->format('F Y');

            Storage::put('public/repository/'.$name.'.pdf', $document->output());
            if(ReportRepository::where('date',$request->date_from)->where('report_type',$request->report_type)->exists())
            {

            }else {
                $repo = new ReportRepository();
                $repo->report_type=$request->report_type;
                $repo->date=$request->date_from;
                $repo->report_name="Bank Payment";
                $repo->location='public/repository/'.$name.'.pdf';
                $repo->save();
            }
        return $document->stream($name.'.pdf');
        }else{
            Alert::warning(no_record());
            return back();
        }
    }

    public function bank_payment_report_bckp(Request $request){
        if ($request->order_by != ''){
            $this->orderBy=$request->order_by;
        }
        TemporatyBankPaymentReport::query()->truncate();
        TemporaryBankPaymentSummary::query()->truncate();
        ini_set('memory_limit', '2048M');
        set_time_limit(2000);
        $histories=SalaryHistory::when($request->group_by,function ($query){
            return $query->where('bank_code',request()->group_by);
        })
            ->when($request->salary_structure,function ($query,){
                return $query->where('salary_structure',ss(request()->salary_structure));
            })
            ->when($request->department,function ($query){
                return $query->where('department',dept(request()->department));
            })
            ->when($request->employee_type,function ($query){
                return $query->where('employment_type',emp_type(request()->employee_type));
            })
            ->when($request->staff_category,function ($query){
                return $query->where('staff_category',staff_cat(request()->staff_category));
            })
            ->when($request->staff_number,function ($query){
                return $query->where('pf_number',request()->staff_number);
            })
            ->when($request->unit,function ($query){
                return $query->where('unit',request()->unit);
            })
            ->when($request->grade_level_from,function ($query){
                return $query->whereBetween('grade_level',[request()->grade_level_from,request()->grade_level_to]);
            })
            ->when($request->group_by,function ($query){
                return $query->where('bank_code',request()->group_by);
            })
            ->whereBetween('salary_month', [Carbon::parse($request->date_from)->format('F'),Carbon::parse($request->date_to)->format('F')])
            ->whereBetween('salary_year', [Carbon::parse($request->date_from)->format('Y'),Carbon::parse($request->date_to)->format('Y')])
            ->orderBy("bank_name",'asc')
            ->get()->groupBy('bank_code');

        foreach ($histories as $index=>$salaryObj) {
            $bank = Bank::find($index);
            $a = array();
            foreach ($salaryObj as $key => $item) {

                $b = [
                    'account_number' => $item->account_number,
                    'amount' => $item->net_pay,
                    'bank' => $item->bank_name,
                    'branch' => $bank->branch??null,
                    'sort_code' => $bank->sort_code??null,
                    'remark' => $item->salary_remark,
                    'staff_number' => $item->pf_number,
                    'ipp_no' => $item->ip_number,
                    'staff_name' => $item->full_name,
                    'bank_code' => $item->bank_code,
                ];
                $a[] = $b;
            }
            $a = collect($a);
            $data_to_insert = $a->chunk(100);
            foreach ($data_to_insert as $key => $data) {
                try {
                    DB::table('temporary_bank_payments')->insert($data
                        ->toArray());
                } catch (\Illuminate\Database\QueryException $e) {
                    $error = $e->getMessage();
//                    echo $error;
                }
            }

            $deductions=Deduction::where('bank_code',$index)->where('visibility',1)
//                ->where('code','>',0)
                ->get();
            foreach ($deductions as $deduction){
                $salObj=SalaryHistory::when($request->salary_structure,function ($query,){
                    return $query->where('salary_structure',ss(request()->salary_structure));
                })
                    ->when($request->department,function ($query){
                        return $query->where('department',dept(request()->department));
                    })
                    ->when($request->employee_type,function ($query){
                        return $query->where('employment_type',emp_type(request()->employee_type));
                    })
                    ->when($request->staff_category,function ($query){
                        return $query->where('staff_category',staff_cat(request()->staff_category));
                    })
                    ->when($request->staff_number,function ($query){
                        return $query->where('pf_number',request()->staff_number);
                    })
                    ->when($request->unit,function ($query){
                        return $query->where('unit',request()->unit);
                    })
                    ->when($request->grade_level_from,function ($query){
                        return $query->whereBetween('grade_level',[request()->grade_level_from,request()->grade_level_to]);
                    })
                    ->when($request->group_by,function ($query){
                        return $query->where('bank_code',request()->group_by);
                    })
                    ->whereBetween('salary_month', [Carbon::parse($request->date_from)->format('F'),Carbon::parse($request->date_to)->format('F')])
                    ->whereBetween('salary_year', [Carbon::parse($request->date_from)->format('Y'),Carbon::parse($request->date_to)->format('Y')])
                    ->get();
                //anyi gyara anan
                $total_deduction= $salObj->sum('D'.$deduction->id);
                $tempo=new TemporatyBankPaymentReport();
                $tempo->account_number=$deduction->account_no;
                $tempo->amount=$total_deduction;
                $tempo->bank=$salObj->where('bank_code',$deduction->bank_code)->first()->bank_name;
                $tempo->branch="";
                $tempo->sort_code="";
                $tempo->bank_code=$deduction->bank_code;
                $tempo->remark=$salObj->where('bank_code',$deduction->bank_code)->first()?$salObj->where('bank_code',$deduction->bank_code)->first()->salary_remark:'n/a';
                $tempo->staff_number="";
                $tempo->staff_name=$deduction->deduction_name;
                $tempo->save();
            }

        }
        $banks_only=Deduction::where('visibility',1)->get();
        foreach ($banks_only as $deduction){
            $salaryDObj=SalaryHistory::when($request->salary_structure,function ($query,){
                return $query->where('salary_structure',ss(request()->salary_structure));
            })
                ->when($request->department,function ($query){
                    return $query->where('department',dept(request()->department));
                })
                ->when($request->employee_type,function ($query){
                    return $query->where('employment_type',emp_type(request()->employee_type));
                })
                ->when($request->staff_category,function ($query){
                    return $query->where('staff_category',staff_cat(request()->staff_category));
                })
                ->when($request->staff_number,function ($query){
                    return $query->where('pf_number',request()->staff_number);
                })
                ->when($request->unit,function ($query){
                    return $query->where('unit',request()->unit);
                })
                ->when($request->grade_level_from,function ($query){
                    return $query->whereBetween('grade_level',[request()->grade_level_from,request()->grade_level_to]);
                })
                ->when($request->group_by,function ($query){
                    return $query->where('bank_code',request()->group_by);
                })
                ->whereBetween('salary_month', [Carbon::parse($request->date_from)->format('F'),Carbon::parse($request->date_to)->format('F')])
                ->whereBetween('salary_year', [Carbon::parse($request->date_from)->format('Y'),Carbon::parse($request->date_to)->format('Y')])
                ->get()->groupBy('bank_code');
            $salaryDObjCheck=SalaryHistory::when($request->salary_structure,function ($query,){
                return $query->where('salary_structure',ss(request()->salary_structure));
            })
                ->when($request->department,function ($query){
                    return $query->where('department',dept(request()->department));
                })
                ->when($request->employee_type,function ($query){
                    return $query->where('employment_type',emp_type(request()->employee_type));
                })
                ->when($request->staff_category,function ($query){
                    return $query->where('staff_category',staff_cat(request()->staff_category));
                })
                ->when($request->staff_number,function ($query){
                    return $query->where('pf_number',request()->staff_number);
                })
                ->when($request->unit,function ($query){
                    return $query->where('unit',request()->unit);
                })
                ->when($request->grade_level_from,function ($query){
                    return $query->whereBetween('grade_level',[request()->grade_level_from,request()->grade_level_to]);
                })
                ->when($request->group_by,function ($query){
                    return $query->where('bank_code',request()->group_by);
                })
                ->whereBetween('salary_month', [Carbon::parse($request->date_from)->format('F'),Carbon::parse($request->date_to)->format('F')])
                ->whereBetween('salary_year', [Carbon::parse($request->date_from)->format('Y'),Carbon::parse($request->date_to)->format('Y')])
//            ->get()
            ;
            //anyi gyara
            if ($salaryDObjCheck->where('bank_code',$deduction->bank_code)->exists()){
                continue;
            }else{
                if ($request->group_by == null){
                    $total_deduction= $salaryDObj->sum('D'.$deduction->id);
                    $bank=Bank::find($deduction->bank_code);
                    $tempo=new TemporatyBankPaymentReport();
                    $tempo->account_number=$deduction->account_no;
                    $tempo->amount=$total_deduction;
                    $tempo->bank=$bank->bank_name??null;
                    $tempo->bank_code=$bank->bank_code??null;
                    $tempo->branch="";
                    $tempo->sort_code="";
                    $tempo->remark=$salaryDObj->first()?$salaryDObj->first()->salary_remark:"N/A";
                    $tempo->staff_number="";
                    $tempo->staff_name=$deduction->deduction_name;
                    $tempo->save();

                }

            }

        }

        $banks=Bank::where('status',1)->get();
        foreach ($banks as $bank)
        {
            $tempo_bank=TemporatyBankPaymentReport::where('bank_code',$bank->id)->get();
            $summary=new TemporaryBankPaymentSummary();
            $summary->bank_code=$bank->code;
            $summary->bank_name=$bank->bank_name;
            $summary->amount=$tempo_bank->sum('amount');
            $summary->branch=$bank->branch??null;
            $summary->save();
        }
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $log->action="Have generated bank payment report";
        $log->save();
        if ($request->order_by ==''){
            $ord="bank";
        }else{
            $ord=$request->order_by;
        }
        $reports=TemporatyBankPaymentReport::orderBy("$ord","$request->order")->get();

        $document= Pdf::loadView('reports.bank_payment_report',compact('reports'),['date_from'=>$request->date_from,'date_to'=>$request->date_to])
            ->setPaper('a4','landscape');

        $content = $document->download()->getOriginalContent();
        $name="public/repository/bank_payment".$request->date_from.'.pdf';
        Storage::put($name,$content);
        if(ReportRepository::where('date',$request->date_from)->where('report_type',3)->exists())
        {

        }else {
            $repo = new ReportRepository();
            $repo->report_type=3;
            $repo->date=$request->date_from;
            $repo->report_name="Bank Payment";
            $repo->location=$name;
            $repo->save();
        }
        $name=report_file_name()."_Bank_payment_report_".Carbon::parse($request->date_from)->format('F Y');

        return $document->stream($name.'.pdf');
    }

    public function pfa(Request $request)
    {
        if ($request->order_by != ''){
            $this->orderBy=$request->order_by;
        }
        $reports=SalaryHistory::join('p_f_a_s','salary_histories.pfa_name','p_f_a_s.id')
            ->select('salary_histories.*','p_f_a_s.name')
        ->when($request->salary_structure,function ($query,){
            return $query->where('salary_structure',ss(request()->salary_structure));
        })
            ->when($request->department,function ($query){
                return $query->where('department',dept(request()->department));
            })
            ->when($request->employee_type,function ($query){
                return $query->where('employment_type',emp_type(request()->employee_type));
            })
            ->when($request->staff_category,function ($query){
                return $query->where('staff_category',staff_cat(request()->staff_category));
            })

            ->when($request->unit,function ($query){
                return $query->where('unit',request()->unit);
            })
            ->when($request->grade_level_from,function ($query){
                return $query->whereBetween('grade_level',[request()->grade_level_from,request()->grade_level_to]);
            })

            ->whereBetween('salary_month', [Carbon::parse($request->date_from)->format('F'),Carbon::parse($request->date_to)->format('F')])

            ->whereBetween('salary_year', [Carbon::parse($request->date_from)->format('Y'),Carbon::parse($request->date_to)->format('Y')])

            ->where('pfa_name','!=',10)
            ->orderBy('p_f_a_s.name', 'asc')

            ->get()->groupBy('pfa_name');

        if ($reports->count()>0){
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $log->action="Have generated PFAs report";
        $log->save();
        $document= Pdf::loadView('reports.pfa',compact('reports'),['date'=>$request->date_from])
            ->setPaper('a4','portrait');

        $name=report_file_name()."_Pfa_payment_schedule_".Carbon::parse($request->date_from)->format('F Y');
            Storage::put('public/repository/'.$name.'.pdf', $document->output());
            if(ReportRepository::where('date',$request->date_from)->where('report_type',$request->report_type)->exists())
            {

            }else {
                $repo = new ReportRepository();
                $repo->report_type=$request->report_type;
                $repo->date=$request->date_from;
                $repo->report_name="PFAs";
                $repo->location='public/repository/'.$name.'.pdf';
                $repo->save();
            }
        return $document->stream($name.'.pdf');
        }else{
            Alert::warning(no_record());
            return back();
        }
    }
    public function nhis(Request $request)
    {
        if ($request->order_by != ''){
            $this->orderBy=$request->order_by;
        }
        $reports=SalaryHistory::when($request->salary_structure,function ($query,){
            return $query->where('salary_structure',ss(request()->salary_structure));
        })
            ->when($request->department,function ($query){
                return $query->where('department',dept(request()->department));
            })
            ->when($request->employee_type,function ($query){
                return $query->where('employment_type',emp_type(request()->employee_type));
            })
            ->when($request->staff_category,function ($query){
                return $query->where('staff_category',staff_cat(request()->staff_category));
            })

            ->when($request->unit,function ($query){
                return $query->where('unit',request()->unit);
            })
            ->when($request->grade_level_from,function ($query){
                return $query->whereBetween('grade_level',[request()->grade_level_from,request()->grade_level_to]);
            })

            ->whereBetween('salary_month', [Carbon::parse($request->date_from)->format('F'),Carbon::parse($request->date_to)->format('F')])

            ->whereBetween('salary_year', [Carbon::parse($request->date_from)->format('Y'),Carbon::parse($request->date_to)->format('Y')])

            ->orderBy("$this->orderBy",$request->order ? 'asc' : 'desc')
            ->get();

//        return view('reports.salary_journal',compact('reports'));
        if ($reports->count()>0){
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $log->action="Have generated NHIS report";
        $log->save();
        $document= Pdf::loadView('reports.nhif',compact('reports'),['date'=>$request->date_from])
            ->setPaper('a4','portrait');


        $name=report_file_name()."_Nhis_".Carbon::parse($request->date_from)->format('F Y');
            Storage::put('public/repository/'.$name.'.pdf', $document->output());
            if(ReportRepository::where('date',$request->date_from)->where('report_type',$request->report_type)->exists())
            {

            }else {
                $repo = new ReportRepository();
                $repo->report_type=$request->report_type;
                $repo->date=$request->date_from;
                $repo->report_name="NHIS";
                $repo->location='public/repository/'.$name.'.pdf';
                $repo->save();
            }
        return $document->stream($name.'.pdf');
        }else{
            Alert::warning(no_record());
            return back();
        }
    }
    public function employer_pension(Request $request)
    {
        if ($request->order_by != ''){
            $this->orderBy=$request->order_by;
        }
        $reports=SalaryHistory::when($request->salary_structure,function ($query,){
            return $query->where('salary_structure',ss(request()->salary_structure));
        })
            ->when($request->department,function ($query){
                return $query->where('department',dept(request()->department));
            })
            ->when($request->employee_type,function ($query){
                return $query->where('employment_type',emp_type(request()->employee_type));
            })
            ->when($request->staff_category,function ($query){
                return $query->where('staff_category',staff_cat(request()->staff_category));
            })

            ->when($request->unit,function ($query){
                return $query->where('unit',request()->unit);
            })
            ->when($request->grade_level_from,function ($query){
                return $query->whereBetween('grade_level',[request()->grade_level_from,request()->grade_level_to]);
            })

            ->whereBetween('salary_month', [Carbon::parse($request->date_from)->format('F'),Carbon::parse($request->date_to)->format('F')])

            ->whereBetween('salary_year', [Carbon::parse($request->date_from)->format('Y'),Carbon::parse($request->date_to)->format('Y')])

            ->orderBy("$request->order_by",$request->order ? 'asc' : 'desc')
            ->get();

//        return view('reports.salary_journal',compact('reports'));
        if ($reports->count()>0){
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $log->action="Have generated employer pension";
        $log->save();
        $document= Pdf::loadView('reports.employer_pension',compact('reports'),['date'=>$request->date_from])
            ->setPaper('a4','portrait');


        $name=report_file_name()."_employer_pension_".Carbon::parse($request->date_from)->format('F Y');
            Storage::put('public/repository/'.$name.'.pdf', $document->output());
            if(ReportRepository::where('date',$request->date_from)->where('report_type',$request->report_type)->exists())
            {

            }else {
                $repo = new ReportRepository();
                $repo->report_type=$request->report_type;
                $repo->date=$request->date_from;
                $repo->report_name="Employer Pension";
                $repo->location='public/repository/'.$name.'.pdf';
                $repo->save();
            }
        return $document->stream($name.'.pdf');
        }else{
            Alert::warning(no_record());
            return back();
        }
    }


    public function loan_deduction_report(Request $request)
{

    ini_set('memory_limit', '2048M');
    set_time_limit(2000);
    $deductions=LoanDeductionCountdownHistory::join('loan_deduction_countdowns','loan_deduction_countdowns.id','loan_deduction_countdown_histories.employee_id')->
    select([
        'loan_deduction_countdown_histories.*',
        'loan_deduction_countdowns.deduction_id'
    ])
        -> when($request->deduction,function($query){
            return $query->where('loan_deduction_countdowns.deduction_id',request()->deduction);
        })

        ->when($request->date_from,function ($query){
            $date_from = Carbon::parse(request()->date_from)->format('Y-m-d');
            $date_to = Carbon::parse(request()->date_to)->format('Y-m-d');
            return $query->whereBetween('loan_deduction_countdown_histories.pay_month_year',[$date_from,$date_to]);
        })->get();
    if ($deductions->count()>0){
    $month=$request->month;
    $pdf=Pdf::loadView('reports.load_deduction_report',compact('deductions','month'))
        ->setPaper('A4','portrait');
    $file_name="loan_deduction".'pdf';
//    return mb_convert_encoding($pdf->stream($file_name),"UTF-8", "UTF-8");
    $user=Auth::user();
    $log=new ActivityLog();
    $log->user_id=$user->id;
    $log->action="Have generated loan deduction report";
    $log->save();
        $name=report_file_name()."_Pfa_loan_deduction_".Carbon::parse($request->date_from)->format('F Y');

        return $pdf->stream($name.'.pdf');
    }else{
        Alert::warning('No record  found');
        return back();
    }
}
    public function annual_increment_report(Request $request)
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(2000);
        $histories=AnnualSalaryIncrement::when($request->status,function ($query){
            return $query->where('status',request()->status);
        })

            ->when($request->date_from,function ($query){
                $date_from = Carbon::parse(request()->date_from)->format('Y-m-d');
                $date_to = Carbon::parse(request()->date_to)->format('Y-m-d');
                return $query ->whereBetween('month_year',[$date_from,$date_to]);
            })->get();
        $month=$request->month;
        if ($histories->count()>0){
        $pdf=Pdf::loadView('reports.annual_increment_report',compact('histories','month'))
            ->setPaper('A4','portrait');
        $file_name="annual_increment".'pdf';
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $log->action="Annual salary report report";
        $log->save();
//    return mb_convert_encoding($pdf->stream($file_name),"UTF-8", "UTF-8");
        $name=report_file_name()."_Ffa_Annual_increment_".Carbon::parse($request->date_from)->format('F Y');

        return $pdf->stream($name.'.pdf');
        }else{
            Alert::warning('No record is fount');
            return back();
        }
    }

    public function retired_staff_report(Request $request)
    {
        set_time_limit(2000);

        $employees=EmployeeProfile::when($request->salary_structure,function ($query){
            return $query->where('salary_structure',request()->salary_structure);
        })
            ->when($request->employee_type,function ($query){
                return $query->where('employment_type',request()->employee_type);
            })
            -> when($request->staff_category,function ($query){
                return $query->where('staff_category',request()->staff_category);
            })
            ->when($request->unit,function ($query){
                return $query->where('unit',request()->unit);
            })
            ->when($request->department,function ($query){
                return $query->where('department',request()->department);
            })
            ->when($request->status,function ($query){
                return $query->where('status',request()->status);
            })
            ->when($request->grade_level_from,function ($query){
                return $query->whereBetween('grade_level',[request()->grade_level_from,request()->grade_level_to]);
            })
            ->whereNotNull('date_of_retirement')
            ->get();
        if ($employees->count()>0){
        $pdf=Pdf::loadView('reports.retired_staff',compact('employees'))
            ->setPaper('A4','portrait');
        $file_name="retired_staff".'pdf';
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $log->action="retired staff report";
        $log->save();
//    return mb_convert_encoding($pdf->stream($file_name),"UTF-8", "UTF-8");
        $name=report_file_name()."_Retired_staff_".Carbon::parse($request->date_from)->format('F Y');

        return $pdf->stream($name.'.pdf');
        }else{
            Alert::warning(no_record());
            return back();
        }

    }
    public function termination_list(Request $request)
    {
        set_time_limit(2000);
        $threeMonthsFromNow = Carbon::now()->addMonths(3)->toDateString();

        $employees=EmployeeProfile::when($request->salary_structure,function ($query){
            return $query->where('salary_structure',request()->salary_structure);
        })
            ->when($request->employee_type,function ($query){
                return $query->where('employment_type',request()->employee_type);
            })
            -> when($request->staff_category,function ($query){
                return $query->where('staff_category',request()->staff_category);
            })
            ->when($request->unit,function ($query){
                return $query->where('unit',request()->unit);
            })
            ->when($request->department,function ($query){
                return $query->where('department',request()->department);
            })
            ->when($request->status,function ($query){
                return $query->where('status',request()->status);
            })
            ->when($request->grade_level_from,function ($query){
                return $query->whereBetween('grade_level',[request()->grade_level_from,request()->grade_level_to]);
            })
            ->whereNotNull('contract_termination_date')
            ->whereDate('contract_termination_date','<=', $threeMonthsFromNow)
//            ->whereYear('contract_termination_date','=>',Carbon::now()->format('Y'))
            ->orderBy('contract_termination_date','asc')
            ->get();
        if ($employees->count()>0){
        $pdf=Pdf::loadView('reports.contract_termination',compact('employees'))
            ->setPaper('A4','portrait');
        $file_name="termination_list".'pdf';
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $log->action="retired staff report";
        $log->save();
//    return mb_convert_encoding($pdf->stream($file_name),"UTF-8", "UTF-8");
        $name=report_file_name()."_Terminated_staffs_".Carbon::parse($request->date_from)->format('F Y');

        return $pdf->stream($name.'.pdf');
        }else{
            Alert::warning(no_record());
            return back();
        }
    }


    public function saveDomFile(Request $request)
    {
        $content = $request->input('content');
        $filename = $request->input('filename', 'default.html');

        // Save to storage/app/public or other disk
//        Storage::disk('local')->put($filename, $content);
//        dd($filename);
        Storage::disk('public')->put($filename, $content);

        return response()->json(['message' => 'File saved successfully']);
    }
    public function download($file){
        $file=ReportRepository::find($file);
//        return response()->download(public_path('storage/'.$file->location));
        $path = $file->location;
        if (!Storage::exists($path)) {
            abort(404);
        }

        return Storage::download($path);
    }

}
