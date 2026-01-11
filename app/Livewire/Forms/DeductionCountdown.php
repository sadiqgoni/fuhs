<?php

namespace App\Livewire\Forms;

use App\Imports\LoandDeductionUpload;
use App\Models\ActivityLog;
use App\Models\Deduction;
use App\Models\LoanDeductionCountdown;
use App\Models\LoanDeductionCountdownHistory;
use App\Models\SalaryUpdate;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class DeductionCountdown extends Component
{
    public $record=true,$crate=false,$edit=false,$staff;
    public $search,$payroll_number,$deduction,$number_of_installment,$installment_amount,
    $start_month,$ids;
    public $deduction_name,$total_deduction_amount,$total_staff_count,$salary_month,$ddId,$importFilePath,$importFile;
    use LivewireAlert,WithPagination,WithoutUrlPagination;
    public $upload_errors;
use WithFileUploads;
    public function getListeners()
    {
        return['confirmed','resumeSingleStaff','confirmLedgerPosting', 'dismissed','clearStaff','deleteStaff','confirmPayMonth','confirmPosting','resumeStaff'];
    }
    protected $rules=[
        'deduction_name'=>'required',
        'number_of_installment'=>'required|numeric|min:1',
        'payroll_number'=>'required',
        'installment_amount'=>'required|regex:/^\d*(\.\d{2})?$/',
        'start_month'=>'required|date_equals:salary_month'
    ];
    public function create()
    {
        $this->validate([
            'deduction_name'=>'required',
            'salary_month'=>'required'
        ]);
        $this->record=false;
        $this->crate=true;
        $this->edit=false;
    }
    public function store()
    {
        $emp=\App\Models\EmployeeProfile::where('payroll_number',$this->payroll_number)->first();

        $this->validate();
        $start_month=Carbon::parse($this->start_month)->format('Y-m-d');
//        if (LoanDeductionCountdown::where('employee_id',$emp->id) ->where('deduction_id',$this->deduction_name)->where('start_month',$start_month)->exists()){
//            $this->alert('warning','there is an existing record for this selection do you want to override it?',[
//                'showConfirmButton'=>true,
//                'confirmButtonText'=>'Yes',
//                'onConfirmed'=>'confirmed',
//                'showCancelButton' => true,
//                'onDismissed' => 'dismissed',
//                'timer'=>9000,
//            ]);
//        }else {
            if (LoanDeductionCountdown::where('deduction_id',$this->deduction_name)->where('employee_id',$emp->id)->exists())
            {
                $this->alert('warning','Record exists');
            }else {
                $deductionObj = new LoanDeductionCountdown();
                $deductionObj->employee_id = $emp->id;
                $deductionObj->deduction_id = $this->deduction_name;
                $deductionObj->installment_amount = $this->installment_amount;
                $deductionObj->start_month = Carbon::parse($this->start_month)->format('Y-m-d');
                $deductionObj->no_of_installment = $this->number_of_installment;

                $deductionObj->ded_countdown = $this->number_of_installment;
                $deductionObj->last_pay_month_year = Carbon::parse($this->salary_month)->format('Y-m-d');
                $deductionObj->deduction_status=0;
                $deductionObj->status=0;
                $deductionObj->save();
                $this->payroll_number = '';
                $this->total_deduction_amount = '';
                $this->total_staff_count = '';
                $this->installment_amount = '';
                $this->number_of_installment = '';
                $this->start_month = '';
                $this->alert('success', 'Deduction has been added');
//            }
        }
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $name=deduction_name($this->deduction_name);
        $log->action="Added $name deduction to loan deduction countdown";
        $log->save();
    }
//    public function confirmed()
//    {
//        $emp=\App\Models\EmployeeProfile::where('payroll_number',$this->payroll_number)->first();
//        $start_month=Carbon::parse($this->start_month)->format('Y-m-d');
//        $deductionObj=LoanDeductionCountdown::where('employee_id',$emp->id)
//            ->where('start_month',$start_month)
//        ->where('deduction_id',$this->deduction)->first();
//        $deductionObj->deduction_id = $this->deduction_name;
//        $deductionObj->installment_amount = $this->installment_amount;
//        $deductionObj->start_month = Carbon::parse($this->start_month)->format('Y-m-d');
//        $deductionObj->last_pay_month_year = Carbon::parse($this->salary_month)->format('Y-m-d');
//        $deductionObj->no_of_installment = $this->number_of_installment;
//        $deductionObj->ded_countdown = $this->number_of_installment;
//        $deductionObj->save();
//        $this->alert('success', 'Deduction have been updated');
//        $user=Auth::user();
//        $log=new ActivityLog();
//        $log->user_id=$user->id;
//        $name=deduction_name($this->deduction_name);
//        $log->action="Updated $name deduction in loan deduction countdown";
//        $log->save();
//    }
    public function close()
    {
        $this->record=true;
        $this->crate=false;
        $this->edit=false;
    }
    public function updated($pop){
        return $this->validateOnly($pop);
    }
    public function updatedPayrollNumber()
    {
        $this->staff=\App\Models\EmployeeProfile::where('payroll_number',$this->payroll_number)->first();
        if ($this->staff !=null){
            $ldc=LoanDeductionCountdown::where('employee_id',$this->staff->id)
                ->where('deduction_id',$this->deduction)->first();
            if ($ldc != null){

                $this->number_of_installment=$ldc->no_of_installment;
                $this->installment_amount=$ldc->installment_amount;
                $this->start_month=$ldc->start_month;
//                $this->end_month=$ldc->end_month;
            }
        }

    }
    public function clear_all_staff()
    {
        $this->alert('warning','Are you sure you want suspend all staffs',[
            'showConfirmButton'=>true,
            'confirmButtonText'=>'suspend',
            'onConfirmed'=>'clearStaff',
            'showCancelButton' => true,
            'onDismissed' => 'dismissed',
            'timer'=>9000
        ]);
    }
    public function resume_all_staff()
    {
        $this->alert('warning','Are you sure you want resume all staffs',[
            'showConfirmButton'=>true,
            'confirmButtonText'=>'Resume',
            'onConfirmed'=>'resumeStaff',
            'showCancelButton' => true,
            'onDismissed' => 'dismissed',
            'timer'=>9000
        ]);
    }
    public function resume_single_staff($id)
    {
        $this->ddId=$id;
        $this->alert('warning','Are you sure you want resume this staff',[
            'showConfirmButton'=>true,
            'confirmButtonText'=>'Resume',
            'onConfirmed'=>'resumeSingleStaff',
            'showCancelButton' => true,
            'onDismissed' => 'dismissed',
            'timer'=>9000
        ]);
    }
    public function resumeSingleStaff()
    {
        $staff=LoanDeductionCountdown::find($this->ddId);
                $staff->deduction_status=0;
                $staff->status=0;
                $staff->save();
        $this->alert('success','Staff have been resumed');
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $name=deduction_name($this->deduction_name);
        $log->action="Resumed a staff for $name in loan deduction countdown";
        $log->save();
    }

    public function deleteId($id)
    {
        $this->ids=$id;
        $this->alert('warning','Are you sure you want to suspend this staff',[
            'showConfirmButton'=>true,
            'confirmButtonText'=>'Suspend',
            'onConfirmed'=>'deleteStaff',
            'showCancelButton' => true,
            'onDismissed' => 'dismissed',
        ]);
    }
    public function clearStaff()
    {
        $staffs=LoanDeductionCountdown::where('deduction_id',$this->deduction_name)->get();
        try {
            foreach ($staffs as $staff){

                $staff->deduction_status=1;
                $staff->save();
            }
        }catch(\Exception $e)
        {
            dd($e);
        }
        $deductionsObj=LoanDeductionCountdown::where('deduction_id',$this->deduction_name)
//            ->where('last_pay_month_year',Carbon::parse($this->salary_month)
//                ->format('Y-m-d'))
            ->get();
        foreach ($deductionsObj as $item) {
            $salaryUpdate = SalaryUpdate::where('employee_id', $item->employee_id)->first();
            $string = $salaryUpdate->deduction_countdown;
            if ($item->ded_countdown == 0) {
                $ded_sum=$item->ded_countdown + 1;
                if (Str::contains($string, "D$item->deduction_id($ded_sum)")) {
                    $a = str_replace("D$item->deduction_id($ded_sum)", '', $string);
                    $salaryUpdate->deduction_countdown =trim($a);
                    $salaryUpdate["D$item->deduction_id"]=0.00;
                    $salaryUpdate->save();
                }
            } else {

                if (!is_null($string)) {
                    $sum_count = $item->ded_countdown;
                    if (Str::contains($string, "D$item->deduction_id($sum_count)")) {
                        $a = str_replace("D$item->deduction_id($sum_count)", '', $string);
                        $salaryUpdate->deduction_countdown = $a;
                        $salaryUpdate["D$item->deduction_id"] =0.00;
                        $salaryUpdate->save();
                    }
                }
//                if (Str::contains($string, "D$item->deduction_id($item->ded_countdown)")) {
//                    continue;
//                } else {
//                    if (!is_null($salaryUpdate->deduction_countdown)) {
//                        $salaryUpdate->deduction_countdown = trim($salaryUpdate->deduction_countdown . " D$item->deduction_id($item->ded_countdown)");
//                    } else {
//                        $salaryUpdate->deduction_countdown = "D$item->deduction_id($item->ded_countdown)";
//                    }
//                    $salaryUpdate["D$item->deduction_id"] =0.00;
//                    $salaryUpdate->save();
//                }
            }
            $gross_pay=$salaryUpdate->gross_pay;
            $total_ded=0;
            foreach (Deduction::all() as $deduction)
            {
                $total_ded +=round($salaryUpdate['D'.$deduction->id],2);
            }

            $net_pay=round($gross_pay - $total_ded,2);
            $salaryUpdate->net_pay=$net_pay;
            $salaryUpdate->total_deduction=$total_ded;
            $salaryUpdate->save();
        }


        $this->alert('success','Staffs have been suspended');
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $name=deduction_name($this->deduction_name);
        $log->action="Suspend staffs for $name in loan deduction countdown";
        $log->save();
    }
    public function resumeStaff()
    {
        $staffs=LoanDeductionCountdown::where('deduction_id',$this->deduction_name)->where('deduction_status',1)->get();
        try {
            foreach ($staffs as $staff){
                $staff->deduction_status=0;
                $staff->status=0;
                $staff->save();
            }
        }catch(\Exception $e)
        {
            dd('ayyah');
        }
        $this->alert('success','Staffs have been resumed');
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $name=deduction_name($this->deduction_name);
        $log->action="Resumed staffs for $name in loan deduction countdown";
        $log->save();
    }
    public function deleteStaff()
    {
        $staff = LoanDeductionCountdown::find($this->ids);

        $staff->deduction_status = 1;
        $staff->save();
        $deductionsObj = LoanDeductionCountdown::find($this->ids);

        $salaryUpdate = SalaryUpdate::where('employee_id', $deductionsObj->employee_id)->first();
        $string = $salaryUpdate->deduction_countdown;
        if ($deductionsObj->ded_countdown == 0) {
            $ded_sum = $deductionsObj->ded_countdown + 1;
            if (Str::contains($string, "D$deductionsObj->deduction_id($ded_sum)")) {
                $a = str_replace("D$deductionsObj->deduction_id($ded_sum)", '', $string);
                $salaryUpdate->deduction_countdown = trim($a);
                $salaryUpdate["D$deductionsObj->deduction_id"] = 0.00;
                $salaryUpdate->save();
            }
        } else {
            if (!is_null($string)) {
                $sum_count = $deductionsObj->ded_countdown;
                if (Str::contains($string, "D$deductionsObj->deduction_id($sum_count)")) {
                    $a = str_replace("D$deductionsObj->deduction_id($sum_count)", '', $string);
                    $salaryUpdate->deduction_countdown = $a;
                    $salaryUpdate["D$deductionsObj->deduction_id"] = 0.00;
                    $salaryUpdate->save();
                }
            }
            $staff->deduction_status = 1;
            $staff->save();

            $this->alert('success', 'Staff have been deleted');
        }
        $gross_pay=$salaryUpdate->gross_pay;
        $total_ded=0;
        foreach (Deduction::all() as $deduction)
        {
            $total_ded +=round($salaryUpdate['D'.$deduction->id],2);
        }

        $net_pay=round($gross_pay - $total_ded,2);
        $salaryUpdate->net_pay=$net_pay;
        $salaryUpdate->total_deduction=$total_ded;
        $salaryUpdate->save();
    }
    public function edit_staff($id){
        $this->record=false;
        $this->crate=false;
        $this->edit=true;
        $this->ids=$id;
        $deductionObj=LoanDeductionCountdown::find($id);
        $emp=\App\Models\EmployeeProfile::find($deductionObj->employee_id);
        $this->payroll_number= $emp->payroll_number;
        $this->deduction= $deductionObj->deduction_id;
        $this->installment_amount= $deductionObj->installment_amount;

       $this->start_month= Carbon::parse($deductionObj->start_month)->format('F Y');
       $this->number_of_installment= $deductionObj->no_of_installment;

    }
    public function update($id){
        $deductionObj=LoanDeductionCountdown::find($id);
        $start_month=Carbon::parse($this->start_month)->format('Y-m-d');
//        $deductionObj->deduction_id = $this->deduction;
        $deductionObj->installment_amount = $this->installment_amount? $this->installment_amount :  $deductionObj->installment_amount ;
        $deductionObj->start_month = $this->start_month? $start_month : $deductionObj->start_month;
        $deductionObj->last_pay_month_year =  $this->salary_month? Carbon::parse($this->salary_month)->format('Y-m-d') : $deductionObj->salary_month;

        $deductionObj->no_of_installment = $this->number_of_installment? $this->number_of_installment :   $deductionObj->no_of_installment ;
        $deductionObj->save();
        $this->alert('success', 'Record updated successfully');
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $name=deduction_name($deductionObj->deduction_id);
        $log->action="Updated $name record in loan deduction countdown";
        $log->save();
    }

    public function updatePayMonth()
    {
        $this->validate([
            'salary_month'=>'required',
            'deduction_name'=>'required',
        ]);
        $deductions=LoanDeductionCountdown::where('deduction_id',$this->deduction_name)
            ->whereDate('last_pay_month_year',Carbon::parse($this->salary_month)->format('Y-m-d'))
            ->get();

        if ($deductions->count() == 0){

            $deductions=LoanDeductionCountdown::where('deduction_id',$this->deduction_name)->get();
            foreach ($deductions as $deduction)
            {
                   $ld=LoanDeductionCountdown::find($deduction->id);
                   $lpmy=Carbon::parse($ld->last_pay_month_year)->format('m');
                   $lm=Carbon::parse($this->salary_month)->format('m');
                   if ($lm >= $lpmy){
                       if ($deduction->ded_countdown - 1 >= 0){
                           $deduction->last_pay_month_year=Carbon::parse($this->salary_month)->format('Y-m-d');
                           $deduction->ded_countdown=$deduction->ded_countdown - 1;
                           if ($deduction->ded_countdown - 1 == 0)
                           {
                               $deduction->status=1;
                           }
                           $deduction->save();
//                        $this->alert('success','Salary month have been updated');
                       }
                   }else{
                      continue;
                   }



            }
        }else{
            $deducts=LoanDeductionCountdown::where('deduction_id',$this->deduction_name)
                ->whereDate('last_pay_month_year', '!=',Carbon::parse($this->salary_month)->format('Y-m-d'))
                ->get();

            foreach ($deducts as $deduction)
            {
                $ld=LoanDeductionCountdown::find($deduction->id);
                $lpmy=Carbon::parse($ld->last_pay_month_year)->format('m');
                $lm=Carbon::parse($this->salary_month)->format('m');
                if ($lm >= $lpmy){
                if ($deduction->ded_countdown - 1 >= 0){
                    $deduction->ded_countdown=$deduction->ded_countdown - 1;
                    $deduction->last_pay_month_year=Carbon::parse($this->salary_month)->format('Y-m-d');
                    if ($deduction->ded_countdown - 1 == 0)
                    {
                        $deduction->status=1;
                    }
                    $deduction->save();
                }
                }
            }
        }


        $this->alert('success','Salary month have been updated');
    }
    public function post_to_ledger()
    {
        if (LoanDeductionCountdown::where('last_pay_month_year',Carbon::parse($this->salary_month)->format('Y-m-d'))->exists()){
            if (LoanDeductionCountdownHistory::whereDate('pay_month_year',Carbon::parse($this->salary_month)->format('Y-m-d'))->exists()){
                $this->alert('warning','Record Exist for '.Carbon::parse($this->salary_month)->format('F Y').', Do you want to overwrite?',[
                    'showConfirmButton'=>true,
                    'onConfirmed'=>'confirmPosting',
                    'showCancelButton'=>true,
                    'onDismissed'=>'dismissed',
                    'timer'=>9000,
                    'position'=>'center'
                ]);
            }else{
                $this->alert('warning','Are you sure you want to post to ledger for the month of '.Carbon::parse($this->salary_month)->format('F Y'),[
                    'showConfirmButton'=>true,
                    'onConfirmed'=>'confirmLedgerPosting',
                    'showCancelButton'=>true,
                    'onDismissed'=>'dismissed',
                    'timer'=>9000,
                    'position'=>'center'
                ]);
            }
        }else{
            $this->alert('warning',"There is no pay month for ".Carbon::parse($this->salary_month)->format('F, Y')." please check your updates",[
                'timer'=>9000,
                'showCancelButton'=>true,
                'onDismissed'=>'dismissed'
            ]);
        }


    }
    public function confirmLedgerPosting()
    {
        $this->ledger();

    }

    public function ledger()
    {
        $name="Loan Deduction";
        backup_es($name);
        $start_month=Carbon::parse($this->salary_month)->format('Y-m-d');
        $deductions=LoanDeductionCountdown::where('last_pay_month_year',$start_month)
            ->where('status',0)
            ->where('deduction_status',0)->get();
        $insertion_data=[];
        foreach ($deductions as $deduction)
        {
                $new_data=[
                    'employee_id'=>$deduction->id,
                    'start_month'=>$deduction->start_month,
                    'no_of_installment'=>$deduction->no_of_installment,
                    'amount_paid'=>$deduction->installment_amount,
                    'pay_month_year'=>$deduction->last_pay_month_year,
                    'ded_countdown'=>$deduction->ded_countdown
                ];
                $insertion_data[]= $new_data;
            }

            $insertion_data = collect($insertion_data);
            $data_to_insert = $insertion_data->chunk(10);
            foreach ($data_to_insert as $key => $data) {
                try {
                    DB::table('loan_deduction_countdown_histories')->insert($data
                        ->toArray());
                } catch (\Illuminate\Database\QueryException $e) {
                    $error = $e->getMessage();
//                    dd($error);

                    echo $error;
                }
            }

            $this->salaryUpdate();

        $this->alert('success','Posted to ledger successfully');
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $name=deduction_name($this->deduction_name);
        $log->action="Posted $name to loan deduction history/Salary Update";
        $log->save();
    }
    public function salaryUpdate()
    {

        $deductionsObj=LoanDeductionCountdown::where('last_pay_month_year',Carbon::parse($this->salary_month)
            ->format('Y-m-d'))
            ->where('status',0)
            ->where('deduction_status',0)
            ->get();

        foreach ($deductionsObj as $item) {
            $salaryUpdate = SalaryUpdate::where('employee_id', $item->employee_id)
//                ->where('date_month',Carbon::parse($this->salary_month)
//                    ->format('Y-m-d'))
                ->first();
            $string = $salaryUpdate->deduction_countdown;

            if ($item->ded_countdown == 0) {
                    $ded_sum=$item->ded_countdown + 1;
                if (Str::contains($string, "D$item->deduction_id($ded_sum)")) {
                    $a = str_replace("D$item->deduction_id($ded_sum)", '', $string);
                    $salaryUpdate->deduction_countdown =trim($a);
                    $salaryUpdate["D$item->deduction_id"]=0.00;
                    $salaryUpdate->save();
                }
            } else {

                if (!is_null($string)) {
                    $sum_count = $item->ded_countdown + 1;
                    if (Str::contains($string, "D$item->deduction_id($sum_count)")) {
                        $a = str_replace("D$item->deduction_id($sum_count)", '', $string);
                        $salaryUpdate->deduction_countdown = $a;
                        $salaryUpdate->save();
                    }
                }
                if (Str::contains($string, "D$item->deduction_id($item->ded_countdown)")) {
                    continue;
                } else {
                    if (!is_null($salaryUpdate->deduction_countdown)) {
                        $salaryUpdate->deduction_countdown = trim($salaryUpdate->deduction_countdown . " D$item->deduction_id($item->ded_countdown)");
                    } else {
                        $salaryUpdate->deduction_countdown = "D$item->deduction_id($item->ded_countdown)";
                    }
                    $salaryUpdate["D$item->deduction_id"] = $item->installment_amount;
                    $salaryUpdate->save();
                }
            }

            $gross_pay=$salaryUpdate->gross_pay;
            $total_ded=0;
            foreach (Deduction::all() as $deduction)
            {
                $total_ded +=round($salaryUpdate['D'.$deduction->id],2);
            }

            $net_pay=round($gross_pay - $total_ded,2);
            $salaryUpdate->net_pay=$net_pay;
            $salaryUpdate->total_deduction=$total_ded;
            $salaryUpdate->save();
        }
    }
    public function confirmPosting()
    {
        $deletes_deductions=LoanDeductionCountdownHistory::whereDate('pay_month_year',Carbon::parse($this->salary_month)->format('Y-m-d'))->get();
        if (!is_null($deletes_deductions)){
            foreach ($deletes_deductions as $deduction){
                $deduction->delete();
            }
            $this->ledger();
        }
    }

    public function uploadFile()
    {

        $this->validate([
            'importFile'=>'required|mimes:xlsx',
            'salary_month'=>'required',
            'deduction_name'=>'required'
        ]);

        $this->importFilePath=$this->importFile->store('imports');
        $data=[
            'date'=>$this->salary_month,
            'deduction'=>$this->deduction_name
            ];
        $import = new LoandDeductionUpload($data);

        $import->import($this->importFilePath);
        $this->upload_errors=$import->failures();
        $failure_array=array();
        foreach ($import->failures() as $failure) {
            $failure->row(); // row that went wrong
            $failure->attribute(); // either heading key (if using heading row concern) or column index
            $failure->errors(); // Actual error messages from Laravel validator
            $failure->values(); // The values of the row that has failed.
            array_push($failure_array, [$failure->values()['payroll_number'],$failure->errors()[0]]);
        }
        $this->reset('importFile');
        $this->upload_errors=$failure_array;
        $this->alert('success','Records have been uploaded successfully');
    }
    public function render()
    {
        $deduction_records=Deduction::join('loan_deduction_countdowns','loan_deduction_countdowns.deduction_id','deductions.id')
        ->join('employee_profiles','employee_profiles.id','loan_deduction_countdowns.employee_id')
            ->select('loan_deduction_countdowns.*','employee_profiles.full_name','employee_profiles.payroll_number','employee_profiles.staff_number','deductions.deduction_name')
            ->where('deduction_id',$this->deduction_name)
//            ->where('loan_deduction_countdowns.deduction_status',null)
            ->paginate('25');
        $deduction_status=Deduction::join('loan_deduction_countdowns','loan_deduction_countdowns.deduction_id','deductions.id')
            ->join('employee_profiles','employee_profiles.id','loan_deduction_countdowns.employee_id')
            ->select('loan_deduction_countdowns.*','employee_profiles.full_name','employee_profiles.payroll_number','employee_profiles.staff_number','deductions.deduction_name')
            ->where('deduction_id',$this->deduction_name)
            ->where('loan_deduction_countdowns.deduction_status',0)
            ->paginate('25');
        return view('livewire.forms.deduction-countdown',compact('deduction_records','deduction_status'))->extends('components.layouts.app');
    }
}
