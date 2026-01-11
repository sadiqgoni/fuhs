<?php

namespace App\Livewire\Reports;

use App\Exports\DeductionExport;
use App\Exports\DeductionScheduleExport;
use App\Exports\EmployeeExport;
use App\Exports\PaymentExport;
use App\Exports\PayrollExport;
use App\Exports\PfaExport;
use App\Mail\Payslip;
use App\Models\ActivityLog;
use App\Models\Bank;
use App\Models\Deduction;
use App\Models\Department;
use App\Models\EmployeeProfile;
use App\Models\EmploymentType;
use App\Models\SalaryHistory;
use App\Models\SalaryStructure;
use App\Models\StaffCategory;
use App\Models\TemporaryBankPaymentSummary;
use App\Models\TemporaryDeduction;
use App\Models\TemporatyBankPaymentReport;
use App\Models\Unit;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use function Monolog\toArray;

class PayrollReportCenter extends Component
{
    public $employee_type,$staff_category,$unit,$department,$month,$year,$salary_structure,$grade_level_from,$grade_level_to;
    public $report_type=1,  $group_by, $order_by="id", $orderAsc='asc',$show_group_by;
    public $date_from,$date_to;
    public $types,$categories,$units,$salary_structures,$departments;
    public $reports,$paySlips,$payment_reports,$bank_sum_reports,$journals,$payrolls,$pfa_payment_schedules,$nhis,$employer_pension;
    public $single_employee=false,$emp_search=false,$report_for,$staff_number,$individ=true;
    use LivewireAlert;
    protected $rules=[
        'date_from'=>'required',
        'date_to'=>'required',
    ];
    protected $listeners=['confirmed','updatedReportType'=>'refresh'];
    public function mount()
    {
       $this->empty_record();
        $this->show_group_by=true;
    }
    public function generate($id)
    {
        if ($this->date_from ==''){
            return redirect()->route('payroll.report.center');
        }
        $this->authorize('can_report');
        $this->validate();
        if ($id==1){
            $this->empty_record();
            $this->payroll();
        }elseif ($id==2){
            $this->empty_record();

            $this->pay_slip();
        }elseif ($id==3){
            $this->empty_record();

            $this->bank_payment();
        }elseif ($id==4){
            $this->empty_record();
            $this->deduction_schedule();
        }elseif ($id==7){
            $this->empty_record();
            $this->journal();
        }elseif($id==5){
            $this->empty_record();
            $this->deduction_summary();
        }elseif($id==6){
            $this->empty_record();
            $this->bank_summary();

        }elseif($id==8){
            $this->empty_record();
            $this->pfa();
        }elseif($id==9){
            $this->empty_record();
            $this->nhis();
        }elseif($id==10){
            $this->empty_record();
            $this->employer_pension();
        }
    }
    public function empty_record()
    {
        $this->departments=[];
        $this->journals=[];
        $this->reports=[];
        $this->payrolls=[];
        $this->paySlips=[];
        $this->bank_sum_reports=[];
        $this->payment_reports=[];
        $this->pfa_payment_schedules=[];
    }
    public function payroll()
    {

        $arrange="department";
        if ($this->order_by ==''){
            $this->order_by="id";
        }
        if ($this->group_by == "unit"){
            $arrange="unit";
        }elseif($this->group_by =="department"){
            $arrange="department";
        }

        $reports=SalaryHistory::when($this->salary_structure,function ($query){
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
//            ->where(DB::raw("CONCAT(salary_year, ' ', salary_month) as month_year"))
            ->whereBetween('salary_year', [Carbon::parse($this->date_from)->format('Y'), Carbon::parse($this->date_to)->format('Y')])
            ->whereBetween('salary_month', [Carbon::parse($this->date_from)->format('F'), Carbon::parse($this->date_to)->format('F')])
//            ->whereBetween('salary_month', [Carbon::parse($this->date_from)->format('F'), Carbon::parse($this->date_to)->format('F')])
            ->orderBy($this->order_by,$this->orderAsc)
            ->orderBy($arrange,$this->orderAsc)
//            ->limit(10)
            ->get();
        if ($reports->count()>0){
            $payrolls=array();
            foreach ($reports as $report)
            {
                array_push($payrolls,$report);
            }
            $this->payrolls=collect($payrolls);
            $this->alert('success','Payroll have been generated successfully');
        }else{
            $this->alert('warning',no_record(),['timer'=>9100]);

        }


    }
    public function pay_slip()
    {
        $this->paySlips=SalaryHistory::when($this->salary_structure,function ($query,){
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
            ->orderBy("$this->order_by",$this->orderAsc)
            ->get();
        if ($this->paySlips->count()>0){
            $this->alert('success','Payslip have been generated successfully');

        }else{
            $this->alert('warning',no_record(),['timer'=>9200]);
        }

    }
    public function confirmed(){
        $datas=SalaryHistory::when($this->salary_structure,function ($query,){
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
            ->orderBy("$this->order_by",$this->orderAsc)
            ->get();
        if ($datas->count() >0) {
            $startDate = Carbon::parse($this->date_from)->format('Y-m-d');
            $endDate = Carbon::parse($this->date_to)->format('Y-m-d');
            foreach ($datas as $data) {
                $a = [
                    'month_from' => $startDate,
                    'month_to' => $endDate,
                    'payroll_number' => $data->ip_number
                ];
                try {
                    Mail::send(new Payslip($a));
                } catch (\Exception $e) {
                    continue;
                }
            }
            $this->alert('success', 'Payslip have been sent to email');
        }else{
            $this->alert('warning', no_record(),['timer'=>9200]);

        }
    }
    public function sendMail()
    {
        $this->alert('question','Are you sure you want to send mail',[
            'showConfirmButton'=>true,
            'showCancelButton'=>true,
            'onConfirmed'=>'confirmed',
            'onDismissed' => 'cancelled',
            'timer'=>90000,
//            'timerProgressBar'=>true,
            'position' => 'center',
            'confirmButtonText' => 'Yes',
        ]);
    }
    public function bank_payment(){
        TemporatyBankPaymentReport::query()->truncate();
        TemporaryBankPaymentSummary::query()->truncate();
        ini_set('memory_limit', '2048M');
        set_time_limit(2000);
        $histories=SalaryHistory::when($this->group_by,function ($query){
            return $query->where('bank_code',$this->group_by);
        })
            ->when($this->salary_structure,function ($query,){
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
            ->when($this->staff_number,function ($query){
                return $query->where('pf_number',$this->staff_number);
            })
            ->when($this->unit,function ($query){
                return $query->where('unit',$this->unit);
            })
            ->when($this->grade_level_from,function ($query){
                return $query->whereBetween('grade_level',[$this->grade_level_from,$this->grade_level_to]);
            })
            ->when($this->group_by,function ($query){
                return $query->where('bank_code',$this->group_by);
            })
            ->whereBetween('salary_month', [Carbon::parse($this->date_from)->format('F'),Carbon::parse($this->date_to)->format('F')])
            ->whereBetween('salary_year', [Carbon::parse($this->date_from)->format('Y'),Carbon::parse($this->date_to)->format('Y')])
            ->orderBy("pf_number",'asc')
            ->get()->groupBy('bank_code');

        if ($histories->count() > 0){
            foreach ($histories as $index=>$salaryObj) {
                $bank = Bank::find($index);
                $a = array();
                foreach ($salaryObj as $key => $item) {

                    $b = [
                        'account_number' => $item->account_number,
                        'amount' => $item->net_pay,
                        'bank' => $item->bank_name,
                        'branch' => $bank->branch??"",
                        'sort_code' => $bank->sort_code??"",
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
                if (is_null($this->staff_number)){

                    foreach ($deductions as $deduction){
                        $salObj=SalaryHistory::when($this->salary_structure,function ($query,){
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
                            ->when($this->staff_number,function ($query){
                                return $query->where('pf_number',$this->staff_number);
                            })
                            ->when($this->unit,function ($query){
                                return $query->where('unit',$this->unit);
                            })
                            ->when($this->grade_level_from,function ($query){
                                return $query->whereBetween('grade_level',[$this->grade_level_from,$this->grade_level_to]);
                            })
                            ->when($this->group_by,function ($query){
                                return $query->where('bank_code',$this->group_by);
                            })
                            ->whereBetween('salary_month', [Carbon::parse($this->date_from)->format('F'),Carbon::parse($this->date_to)->format('F')])
                            ->whereBetween('salary_year', [Carbon::parse($this->date_from)->format('Y'),Carbon::parse($this->date_to)->format('Y')]);
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
                $salaryDObj=SalaryHistory::when($this->salary_structure,function ($query,){
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
                    ->when($this->staff_number,function ($query){
                        return $query->where('pf_number',$this->staff_number);
                    })
                    ->when($this->unit,function ($query){
                        return $query->where('unit',$this->unit);
                    })
                    ->when($this->grade_level_from,function ($query){
                        return $query->whereBetween('grade_level',[$this->grade_level_from,$this->grade_level_to]);
                    })
                    ->when($this->group_by,function ($query){
                        return $query->where('bank_code',$this->group_by);
                    })
                    ->whereBetween('salary_month', [Carbon::parse($this->date_from)->format('F'),Carbon::parse($this->date_to)->format('F')])
                    ->whereBetween('salary_year', [Carbon::parse($this->date_from)->format('Y'),Carbon::parse($this->date_to)->format('Y')])->get();
                $salaryDObjCheck=SalaryHistory::when($this->salary_structure,function ($query,){
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
                    ->when($this->staff_number,function ($query){
                        return $query->where('pf_number',$this->staff_number);
                    })
                    ->when($this->unit,function ($query){
                        return $query->where('unit',$this->unit);
                    })
                    ->when($this->grade_level_from,function ($query){
                        return $query->whereBetween('grade_level',[$this->grade_level_from,$this->grade_level_to]);
                    })
                    ->when($this->group_by,function ($query){
                        return $query->where('bank_code',$this->group_by);
                    })
                    ->whereBetween('salary_month', [Carbon::parse($this->date_from)->format('F'),Carbon::parse($this->date_to)->format('F')])
                    ->whereBetween('salary_year', [Carbon::parse($this->date_from)->format('Y'),Carbon::parse($this->date_to)->format('Y')]);
                if ($salaryDObjCheck->where('bank_code',$deduction->bank_code)->exists()){
                    continue;
                }else{
                    if ($this->group_by == null){
                        if ($this->staff_number == null){
                            $total_deduction= $salaryDObj->sum('D'.$deduction->id);
                            $bank=Bank::find($deduction->bank_code);
                            $tempo=new TemporatyBankPaymentReport();
                            $tempo->account_number=$deduction->account_no;
                            $tempo->amount=$total_deduction;
                            $tempo->bank=$bank->bank_name??'';
                            $tempo->bank_code=$bank->bank_code??null;
                            $tempo->branch="";
                            $tempo->sort_code="";
                            $tempo->remark=$salaryDObj->first()->salary_remark;
                            $tempo->staff_number="";
                            $tempo->staff_name=$deduction->deduction_name;
                            $tempo->save();
                        }

                    }

                }
            }

            $banks=Bank::get();
            foreach ($banks as $bank)
            {
                $tempo_bank=TemporatyBankPaymentReport::where('bank_code',$bank->id)->get();
                $summary=new TemporaryBankPaymentSummary();
                $summary->bank_code=$bank->code??"";
                $summary->bank_name=$bank->bank_name??"";
                $summary->amount=$tempo_bank->sum('amount');
                $summary->branch=$bank->branch?? '';
                $summary->save();
            }
            $this->payment_reports=TemporatyBankPaymentReport::all();
            $this->alert('success','Bank payment have been generated successfully');
        }else{
            $this->alert('warning',no_record(),['timer'=>9200]);

        }



    }
    public function export_bank_payment()
    {
        TemporatyBankPaymentReport::query()->truncate();
        TemporaryBankPaymentSummary::query()->truncate();
        ini_set('memory_limit', '2048M');
        set_time_limit(2000);
        $histories=SalaryHistory::when($this->group_by,function ($query){
            return $query->where('bank_code',$this->group_by);
        })
            ->when($this->salary_structure,function ($query,){
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
            ->when($this->staff_number,function ($query){
                return $query->where('pf_number',$this->staff_number);
            })
            ->when($this->unit,function ($query){
                return $query->where('unit',$this->unit);
            })
            ->when($this->grade_level_from,function ($query){
                return $query->whereBetween('grade_level',[$this->grade_level_from,$this->grade_level_to]);
            })
            ->when($this->group_by,function ($query){
                return $query->where('bank_code',$this->group_by);
            })
            ->whereBetween('salary_month', [Carbon::parse($this->date_from)->format('F'),Carbon::parse($this->date_to)->format('F')])
            ->whereBetween('salary_year', [Carbon::parse($this->date_from)->format('Y'),Carbon::parse($this->date_to)->format('Y')])
            ->orderBy("pf_number",'asc')
            ->get()->groupBy('bank_code');
        if ($histories->count() > 0){
            foreach ($histories as $index=>$salaryObj) {
                $bank = Bank::find($index);
                $a = array();
                foreach ($salaryObj as $key => $item) {

                    $b = [
                        'account_number' => $item->account_number,
                        'amount' => $item->net_pay,
                        'bank' => $item->bank_name??"",
                        'branch' => $bank->branch??"",
                        'sort_code' => $bank->sort_code??"",
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

                $deductions=Deduction::where('bank_code',$index)
//                ->where('code','>',0)
                    ->get();
                if (is_null($this->staff_number)){

                    foreach ($deductions as $deduction){
                        $salObj=SalaryHistory::when($this->salary_structure,function ($query,){
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
                            ->when($this->staff_number,function ($query){
                                return $query->where('pf_number',$this->staff_number);
                            })
                            ->when($this->unit,function ($query){
                                return $query->where('unit',$this->unit);
                            })
                            ->when($this->grade_level_from,function ($query){
                                return $query->whereBetween('grade_level',[$this->grade_level_from,$this->grade_level_to]);
                            })
                            ->when($this->group_by,function ($query){
                                return $query->where('bank_code',$this->group_by);
                            })
                            ->whereBetween('salary_month', [Carbon::parse($this->date_from)->format('F'),Carbon::parse($this->date_to)->format('F')])
                            ->whereBetween('salary_year', [Carbon::parse($this->date_from)->format('Y'),Carbon::parse($this->date_to)->format('Y')]);
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
            $banks_only=Deduction::where('status',1)->get();
            foreach ($banks_only as $deduction){
                $salaryDObj=SalaryHistory::when($this->salary_structure,function ($query,){
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
                    ->when($this->staff_number,function ($query){
                        return $query->where('pf_number',$this->staff_number);
                    })
                    ->when($this->unit,function ($query){
                        return $query->where('unit',$this->unit);
                    })
                    ->when($this->grade_level_from,function ($query){
                        return $query->whereBetween('grade_level',[$this->grade_level_from,$this->grade_level_to]);
                    })
                    ->when($this->group_by,function ($query){
                        return $query->where('bank_code',$this->group_by);
                    })
                    ->whereBetween('salary_month', [Carbon::parse($this->date_from)->format('F'),Carbon::parse($this->date_to)->format('F')])
                    ->whereBetween('salary_year', [Carbon::parse($this->date_from)->format('Y'),Carbon::parse($this->date_to)->format('Y')])->get();
                $salaryDObjCheck=SalaryHistory::when($this->salary_structure,function ($query,){
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
                    ->when($this->staff_number,function ($query){
                        return $query->where('pf_number',$this->staff_number);
                    })
                    ->when($this->unit,function ($query){
                        return $query->where('unit',$this->unit);
                    })
                    ->when($this->grade_level_from,function ($query){
                        return $query->whereBetween('grade_level',[$this->grade_level_from,$this->grade_level_to]);
                    })
                    ->when($this->group_by,function ($query){
                        return $query->where('bank_code',$this->group_by);
                    })
                    ->whereBetween('salary_month', [Carbon::parse($this->date_from)->format('F'),Carbon::parse($this->date_to)->format('F')])
                    ->whereBetween('salary_year', [Carbon::parse($this->date_from)->format('Y'),Carbon::parse($this->date_to)->format('Y')]);
                if ($salaryDObjCheck->where('bank_code',$deduction->bank_code)->exists()){
                    continue;
                }else{
                    if ($this->group_by == null){
                        if ($this->staff_number == null){
                            $total_deduction= $salaryDObj->sum('D'.$deduction->id);
                            $bank=Bank::find($deduction->bank_code);
                            $tempo=new TemporatyBankPaymentReport();
                            $tempo->account_number=$deduction->account_no;
                            $tempo->amount=$total_deduction;
                            $tempo->bank=$bank->bank_name??"";
                            $tempo->bank_code=$bank->bank_code??null;
                            $tempo->branch="";
                            $tempo->sort_code="";
                            $tempo->remark=$salaryDObj->first()->salary_remark;
                            $tempo->staff_number="";
                            $tempo->staff_name=$deduction->deduction_name;
                            $tempo->save();
                        }

                    }

                }
            }

            $banks=Bank::get();
            foreach ($banks as $bank)
            {
                $tempo_bank=TemporatyBankPaymentReport::where('bank_code',$bank->id)->get();
                $summary=new TemporaryBankPaymentSummary();
                $summary->bank_code=$bank->code??'';
                $summary->bank_name=$bank->bank_name??'';
                $summary->amount=$tempo_bank->sum('amount');
                $summary->branch=$bank->branch?? '';
                $summary->save();
            }
            $payment_reports=TemporatyBankPaymentReport::get();

            $this->alert('success','Bank payment have been exported successfully');
            $name=report_file_name()."_Bank_payments_".Carbon::parse($this->date_from)->format('F Y');

            return Excel::download(new PaymentExport($payment_reports), $name.'.xlsx');
        }else{
            $this->alert('warning',no_record(),['timer'=>9200]);
        }


    }
    public function export_payroll()
    {

        ini_set('memory_limit', '2048M');
        set_time_limit(2000);
        if ($this->order_by==''){
            $this->order_by="id";
        }
        if ($this->group_by=="unit"){
                $arrange = "unit";
        }elseif ($this->group_by=="department"){

            $arrange = "department";
        }else{
            $arrange="id";
        }

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
//            ->orderBy("$this->order_by",$this->orderAsc)
//            ->orderBy($this->group_by? $arrange: "id",$this->orderAsc)
            ->orderBy($this->group_by? $arrange : $this->order_by,$this->orderAsc)

            //            ->orderBy($arrange,$this->orderAsc)
            ->get()->groupBy($this->group_by);
        if (!empty($reports)){
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

            $file_name=report_file_name()."_Payroll_".Carbon::parse($this->date_from)->format('F Y');

            return Excel::download(new PayrollExport($payrolls,$name,$name_search), $file_name.'.xlsx');
        }else{
            $this->alert('warning',no_record(),['timer'=>9200]);
        }


    }
    public function export_deduction()

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
        if ($reports->count() > 0) {
            $insertion_data = array();
            foreach ($reports as $report) {
                $staff = EmployeeProfile::where('staff_number', $report->pf_number)->first();
                $deductions = Deduction::where('status', 1)->get();
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
            if ($this->order_by != '') {
                $this->orderBy = $this->order_by;
            }
            $reports_data = TemporaryDeduction::
//        when($this->group_by,function ($query){
//            return $query->where('deduction_id',$this->group_by);
//        })

            orderBy("$this->orderBy", $this->orderAsc)
                ->get()
                ->groupBy('deduction_id');


            $date_from = $this->date_from;
            $user = Auth::user();
            $log = new ActivityLog();
            $log->user_id = $user->id;
            $log->action = "Have generated deduction detail report";
            $log->save();
            $name = report_file_name()."_Deduction_schedule_" . Carbon::parse($this->date_from)->format('F Y');

            return Excel::download(new DeductionScheduleExport($reports_data, $date_from), $name . '.xlsx');
        }else{
            $this->alert('warning',no_record(),['timer'=>9200]);
        }

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
//           ->limit('2')
            ->get();
        if ($reports->count()>0) {
            $insertion_data = array();
            foreach ($reports as $report) {
                $staff = EmployeeProfile::where('staff_number', $report->pf_number)->first();
                $deductions = Deduction::get();
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
                    $data = $data->toArray();
                    DB::table('temporary_deductions')->insert($data);
                } catch (\Illuminate\Database\QueryException $e) {
                    $error = $e->getMessage();
                    continue;
//                echo $error;
                }
            }
            $this->reports = TemporaryDeduction::
            when($this->group_by, function ($query) {
                return $query->where('deduction_id', $this->group_by);
            })
                ->orderBy("$this->order_by", $this->orderAsc)
                ->get()
                ->groupBy('deduction_id')->collect();

            $user = Auth::user();
            $log = new ActivityLog();
            $log->user_id = $user->id;
            $log->action = "Have generated deduction schedule report";
            $log->save();

            $this->alert('success', 'Deduction details have been generated successfully');
        }else{
            $this->alert('warning',no_record(),['timer'=>9200]);
        }
    }
    public function deduction_summary()
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(2000);
        $salaryObjs = SalaryHistory::whereBetween('salary_month', [Carbon::parse($this->date_from)->format('F'), Carbon::parse($this->date_to)->format('F')])
            ->whereBetween('salary_year', [Carbon::parse($this->date_from)->format('Y'), Carbon::parse($this->date_to)->format('Y')])
            ->get();
if ($salaryObjs->count()>0) {
    TemporaryDeduction::query()->truncate();
    $insertion_data = array();
    foreach ($salaryObjs as $salaryObj) {
        $deductions = Deduction::get();
        foreach ($deductions as $index => $deduction) {
            $new_data = [
                'history_id' => $salaryObj['id'],
                'deduction_id' => $deduction['id'],
                'amount' => $salaryObj["D$deduction->id"],
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
    $this->reports = SalaryHistory::join('temporary_deductions', 'temporary_deductions.history_id', 'salary_histories.id')
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
        ->when($this->department, function ($query) {
            return $query->where('salary_histories.department', dept($this->department));
        })
        ->when($this->employee_type, function ($query) {
            return $query->where('salary_histories.employment_type', emp_type($this->employee_type));
        })
        ->when($this->staff_category, function ($query) {
            return $query->where('salary_histories.staff_category', staff_cat($this->staff_category));
        })
        ->when($this->staff_number, function ($query) {
            return $query->where('salary_histories.pf_number', $this->staff_number);
        })
        ->when($this->unit, function ($query) {
            return $query->where('salary_histories.unit', $this->unit);
        })
        ->when($this->group_by, function ($query) {
            return $query->where('temporary_deductions.deduction_id', $this->group_by);
        })
        ->when($this->grade_level_from, function ($query) {
            return $query->whereBetween('salary_histories.grade_level', [$this->grade_level_from, $this->grade_level_to]);
        })
        ->whereBetween('salary_month', [Carbon::parse($this->date_from)->format('F'), Carbon::parse($this->date_to)->format('F')])
        ->whereBetween('salary_year', [Carbon::parse($this->date_from)->format('Y'), Carbon::parse($this->date_to)->format('Y')])
//            ->orderBy($this->order_by,$this->order)
        ->get()->groupBy('deduction_id')->all();
    $this->alert('success', 'Deduction summary have been generated successfully');
}else{
    $this->alert('warning',no_record(),['timer'=>9200]);
}

    }
    public function journal()
    {
        $this->journals=SalaryHistory::when($this->salary_structure,function ($query,){
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

            ->when($this->unit,function ($query){
                return $query->where('unit',$this->unit);
            })
            ->when($this->grade_level_from,function ($query){
                return $query->whereBetween('grade_level',[$this->grade_level_from,$this->grade_level_to]);
            })
            ->whereBetween('salary_month', [Carbon::parse($this->date_from)->format('F'),Carbon::parse($this->date_to)->format('F')])
            ->whereBetween('salary_year', [Carbon::parse($this->date_from)->format('Y'),Carbon::parse($this->date_to)->format('Y')])
            ->orderBy("$this->order_by",$this->orderAsc ? 'asc' : 'desc')
            ->get();
        if ( $this->journals->count()>0){
            $this->alert('success','Salary Journal have been generated successfully');

        }else{
            $this->alert('warning',no_record(),['timer'=>9200]);

        }

    }
    public function pfa()
    {
        $this->pfa_payment_schedules=SalaryHistory::when($this->salary_structure,function ($query,){
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

            ->when($this->unit,function ($query){
                return $query->where('unit',$this->unit);
            })
            ->when($this->grade_level_from,function ($query){
                return $query->whereBetween('grade_level',[$this->grade_level_from,$this->grade_level_to]);
            })
            ->whereBetween('salary_month', [Carbon::parse($this->date_from)->format('F'),Carbon::parse($this->date_to)->format('F')])
            ->whereBetween('salary_year', [Carbon::parse($this->date_from)->format('Y'),Carbon::parse($this->date_to)->format('Y')])
            ->orderBy("$this->order_by",$this->orderAsc ? 'asc' : 'desc')
            ->get();
        if ($this->pfa_payment_schedules->count()>0){
        $this->alert('success','PFAs  have been generated successfully');
        }else{
            $this->alert('warning',no_record(),['timer'=>9200]);

        }
    }
    public function pfa_export()
    {
        $this->pfa_payment_schedules=SalaryHistory::when($this->salary_structure,function ($query,){
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

            ->when($this->unit,function ($query){
                return $query->where('unit',$this->unit);
            })
            ->when($this->grade_level_from,function ($query){
                return $query->whereBetween('grade_level',[$this->grade_level_from,$this->grade_level_to]);
            })
            ->whereBetween('salary_month', [Carbon::parse($this->date_from)->format('F'),Carbon::parse($this->date_to)->format('F')])
            ->whereBetween('salary_year', [Carbon::parse($this->date_from)->format('Y'),Carbon::parse($this->date_to)->format('Y')])
            ->orderBy("$this->order_by",$this->orderAsc ? 'asc' : 'desc')
            ->get();
        if ($this->pfa_payment_schedules->count()>0){


        $this->alert('success','PFAs  have been exported successfully');
        $name=report_file_name()."_Pfa_export_".Carbon::parse($this->date_from)->format('F Y');

        return Excel::download(new PfaExport($this->pfa_payment_schedules, $this->date_from), $name.'.xlsx');
        }else{
            $this->alert('warning',no_record(),['timer'=>9200]);

        }

    }
    public function bank_summary()
    {
        TemporatyBankPaymentReport::query()->truncate();
        TemporaryBankPaymentSummary::query()->truncate();
        ini_set('memory_limit', '2048M');
        set_time_limit(2000);
        $histories=SalaryHistory::when($this->group_by,function ($query){
            return $query->where('bank_code',$this->group_by);
        })
            ->when($this->salary_structure,function ($query,){
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
            ->when($this->staff_number,function ($query){
                return $query->where('pf_number',$this->staff_number);
            })
            ->when($this->unit,function ($query){
                return $query->where('unit',$this->unit);
            })
            ->when($this->grade_level_from,function ($query){
                return $query->whereBetween('grade_level',[$this->grade_level_from,$this->grade_level_to]);
            })
            ->when($this->group_by,function ($query){
                return $query->where('bank_code',$this->group_by);
            })
            ->whereBetween('salary_month', [Carbon::parse($this->date_from)->format('F'),Carbon::parse($this->date_to)->format('F')])
            ->whereBetween('salary_year', [Carbon::parse($this->date_from)->format('Y'),Carbon::parse($this->date_to)->format('Y')])
            ->orderBy("bank_name",'asc')
            ->get()->groupBy('bank_code');

        if($histories->count() > 0){
            foreach ($histories as $index=>$salaryObj) {
                $bank = Bank::find($index);
                $a = array();
                foreach ($salaryObj as $key => $item) {

                    $b = [
                        'account_number' => $item->account_number,
                        'amount' => $item->net_pay,
                        'bank' => $item->bank_name,
                        'branch' => $bank->branch??"",
                        'sort_code' => $bank->sort_code??"",
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
                if (is_null($this->staff_number)){

                    foreach ($deductions as $deduction){
                        $salObj=SalaryHistory::when($this->salary_structure,function ($query,){
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
                            ->when($this->staff_number,function ($query){
                                return $query->where('pf_number',$this->staff_number);
                            })
                            ->when($this->unit,function ($query){
                                return $query->where('unit',$this->unit);
                            })
                            ->when($this->grade_level_from,function ($query){
                                return $query->whereBetween('grade_level',[$this->grade_level_from,$this->grade_level_to]);
                            })
                            ->when($this->group_by,function ($query){
                                return $query->where('bank_code',$this->group_by);
                            })
                            ->whereBetween('salary_month', [Carbon::parse($this->date_from)->format('F'),Carbon::parse($this->date_to)->format('F')])
                            ->whereBetween('salary_year', [Carbon::parse($this->date_from)->format('Y'),Carbon::parse($this->date_to)->format('Y')]);
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
                $salaryDObj=SalaryHistory::when($this->salary_structure,function ($query,){
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
                    ->when($this->staff_number,function ($query){
                        return $query->where('pf_number',$this->staff_number);
                    })
                    ->when($this->unit,function ($query){
                        return $query->where('unit',$this->unit);
                    })
                    ->when($this->grade_level_from,function ($query){
                        return $query->whereBetween('grade_level',[$this->grade_level_from,$this->grade_level_to]);
                    })
                    ->when($this->group_by,function ($query){
                        return $query->where('bank_code',$this->group_by);
                    })
                    ->whereBetween('salary_month', [Carbon::parse($this->date_from)->format('F'),Carbon::parse($this->date_to)->format('F')])
                    ->whereBetween('salary_year', [Carbon::parse($this->date_from)->format('Y'),Carbon::parse($this->date_to)->format('Y')])->get();
                $salaryDObjCheck=SalaryHistory::when($this->salary_structure,function ($query,){
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
                    ->when($this->staff_number,function ($query){
                        return $query->where('pf_number',$this->staff_number);
                    })
                    ->when($this->unit,function ($query){
                        return $query->where('unit',$this->unit);
                    })
                    ->when($this->grade_level_from,function ($query){
                        return $query->whereBetween('grade_level',[$this->grade_level_from,$this->grade_level_to]);
                    })
                    ->when($this->group_by,function ($query){
                        return $query->where('bank_code',$this->group_by);
                    })
                    ->whereBetween('salary_month', [Carbon::parse($this->date_from)->format('F'),Carbon::parse($this->date_to)->format('F')])
                    ->whereBetween('salary_year', [Carbon::parse($this->date_from)->format('Y'),Carbon::parse($this->date_to)->format('Y')]);
                if ($salaryDObjCheck->where('bank_code',$deduction->bank_code)->exists()){
                    continue;
                }else{
                    if ($this->group_by == null){
                        if ($this->staff_number == null){
                            $total_deduction= $salaryDObj->sum('D'.$deduction->id);
                            $bank=Bank::find($deduction->bank_code);
                            $tempo=new TemporatyBankPaymentReport();
                            $tempo->account_number=$deduction->account_no;
                            $tempo->amount=$total_deduction;
                            $tempo->bank=$bank->bank_name??'';
                            $tempo->bank_code=$bank->bank_code??null;
                            $tempo->branch="";
                            $tempo->sort_code="";
                            $tempo->remark=$salaryDObj->first()->salary_remark;
                            $tempo->staff_number="";
                            $tempo->staff_name=$deduction->deduction_name;
                            $tempo->save();
                        }

                    }

                }
            }

            $banks=Bank::get();
            foreach ($banks as $bank)
            {
                $tempo_bank=TemporatyBankPaymentReport::where('bank_code',$bank->id)->get();
                $summary=new TemporaryBankPaymentSummary();
                $summary->bank_code=$bank->code??"";
                $summary->bank_name=$bank->bank_name??"";
                $summary->amount=$tempo_bank->sum('amount');
                $summary->branch=$bank->branch?? '';
                $summary->save();
            }
            $this->bank_sum_reports=TemporaryBankPaymentSummary::get();
            $this->alert('success','Bank summary have been generated successfully');
        }else{
            $this->alert('warning',no_record(),['timer'=>9200]);

        }
    }
    public function nhis()
    {
        $this->nhis=SalaryHistory::when($this->salary_structure,function ($query,){
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

            ->when($this->unit,function ($query){
                return $query->where('unit',$this->unit);
            })
            ->when($this->grade_level_from,function ($query){
                return $query->whereBetween('grade_level',[$this->grade_level_from,$this->grade_level_to]);
            })
            ->whereBetween('salary_month', [Carbon::parse($this->date_from)->format('F'),Carbon::parse($this->date_to)->format('F')])
            ->whereBetween('salary_year', [Carbon::parse($this->date_from)->format('Y'),Carbon::parse($this->date_to)->format('Y')])
            ->orderBy("$this->order_by",$this->orderAsc ? 'asc' : 'desc')
            ->get();
        if($this->nhis->count()>0){
        $this->alert('success','NHIS  have been generated successfully');
        }else{
            $this->alert('warning',no_record(),['timer'=>9200]);

        }
    }
    public function employer_pension()
    {
        $this->employer_pension=SalaryHistory::when($this->salary_structure,function ($query,){
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

            ->when($this->unit,function ($query){
                return $query->where('unit',$this->unit);
            })
            ->when($this->grade_level_from,function ($query){
                return $query->whereBetween('grade_level',[$this->grade_level_from,$this->grade_level_to]);
            })
            ->whereBetween('salary_month', [Carbon::parse($this->date_from)->format('F'),Carbon::parse($this->date_to)->format('F')])
            ->whereBetween('salary_year', [Carbon::parse($this->date_from)->format('Y'),Carbon::parse($this->date_to)->format('Y')])
            ->orderBy("$this->order_by",$this->orderAsc ? 'asc' : 'desc')
            ->get();
        if ($this->employer_pension->count()>0){

        $this->alert('success','Employer pension  have been generated successfully');
        }else{
            $this->alert('warning',no_record(),['timer'=>9200]);

        }
    }


    public function updatedUnit(){
        if ($this->unit != ''){
            $this->departments=Department::where('unit_id',$this->unit)->get();
        }else{
            $this->departments=[];
        }
    }
    public function updatedReportType()
    {
        $this->change();
        $this->dispatch('$refresh');
    }
    public function change()
    {
        if ($this->report_type==1 || $this->report_type==2){
            $this->show_group_by=true;
        }
        else{
            $this->show_group_by=false;
        }
        if ($this->report_type ==5 || $this->report_type==6 || $this->report_type==7){
            $this->show_group_by=false;
            $this->individ=false;

        }elseif($this->report_type==4 || $this->report_type==3){
            $this->individ=true;
        }

    }


    public function render()
    {

        $this->types=EmploymentType::all();
        $this->categories=StaffCategory::all();
        $this->salary_structures=SalaryStructure::where('status',1)->get();
        $banks=Bank::get();
        $this->units=Unit::where('status',1)->get();
        $deductions=Deduction::get();

        return view('livewire.reports.payroll-report-center',compact('banks','deductions'))->extends('components.layouts.app');
    }
}
