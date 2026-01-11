<?php

namespace App\Livewire\Forms;

use App\DeductionCalculation;
use App\Imports\PromotionImport;
use App\Models\ActivityLog;
use App\Models\Allowance;
use App\Models\Deduction;
use App\Models\SalaryAllowanceTemplate;
use App\Models\SalaryDeductionTemplate;
use App\Models\SalaryStructureTemplate;
use App\Models\SalaryUpdate;
use App\Models\StaffPromotion;
use App\Models\UnionDeduction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class EmployeePromotion extends Component
{
    public $search,$perpage;
    public $record=true,$create,$edit,$ids;
    public $payroll_number,  $salary_structure,  $level,$ledger_fails,  $step, $staff_number,$staff_name,$status,$importFilePath,$importFile,$file_name;
    use LivewireAlert,WithFileUploads,WithPagination,WithoutUrlPagination;
    public $upload_errors;
    protected $listeners=['delete','confirm','clearAll'];
    protected $rules=[
        'payroll_number'=>'required',
        'salary_structure'=>'required',
        'level'=>'required|',
        'step'=>'required|',
        'staff_number'=>'required|',
        'staff_name'=>'required|',
//        'status'=>'required|',
    ];
//    public function updatedImportFile(){
//        $this->file_name=$this->document->getClientOriginalName();
//    }
    public function create_record()
    {
        $this->clear();
        $this->record=false;
        $this->edit=false;
        $this->create=true;
    }
    public function store()
    {
        $this->validate();
        $promotionObj=new StaffPromotion();
        $promotionObj->payroll_number=$this->payroll_number;
        $promotionObj->salary_structure=$this->salary_structure;
        $promotionObj->level=$this->level;
        $promotionObj->step=$this->step;
//        $promotionObj->staff_name=$this->staff_name;
//        $promotionObj->staff_number=$this->staff_number;
//        $promotionObj->status=$this->status;
        $promotionObj->save();
        $this->clear();

        $this->alert('success','Staff Promotion have been successfully added',['timer'=>9000]);

    }
    public function clear()
    {
        $this->payroll_number='';
        $this->salary_structure='';
        $this->level='';
        $this->step='';
        $this->staff_number='';
        $this->staff_name='';
        $this->status='';
    }
    public function close()
    {
        $this->ledger_fails=[];
        $this->record=true;
        $this->edit=false;
        $this->create=false;
    }
    public function edit_record($id)
    {
        $this->ids=$id;
        $this->record=false;
        $this->edit=true;
        $this->create=false;
        $promotionObj=StaffPromotion::findOrFail($id);
        $user=\App\Models\EmployeeProfile::where('payroll_number',$promotionObj->payroll_number)->first();
        $this->payroll_number=$promotionObj->payroll_number;
        $this->salary_structure=$promotionObj->salary_structure;
        $this->level=$promotionObj->level;
        $this->step=$promotionObj->step;
        $this->status=$promotionObj->status;
        $this->staff_number=$user?$user->staff_number:'';
        $this->staff_name=$user?$user->full_name:'';
    }
    public function update($id)
    {
        $promotionObj=StaffPromotion::findOrFail($id);
        $promotionObj->payroll_number=$this->payroll_number;
        $promotionObj->salary_structure=$this->salary_structure;
        $promotionObj->level=$this->level;
        $promotionObj->step=$this->step;
//        $promotionObj->staff_name=$this->staff_name;
//        $promotionObj->staff_number=$this->staff_number;
        $promotionObj->status=$this->status;
        $promotionObj->save();
        $this->alert('success','Staff Promotion have been successfully updated',['timer'=>9000]);
        $this->close();
    }
    public function deleteId($id){
        $this->ids=$id;
        $this->alert('warning','Are you sure you want to delete this record?',[
            'showConfirmButton'=>true,
            'onConfirmed'=>'delete',
            'showCancelButton'=>true,
            'timer'=>90000,
            'position'=>'center'
        ]);
    }
    public function delete()
    {
        $promotionObj=StaffPromotion::findOrFail($this->ids);
        $promotionObj->delete();
        $this->alert('success','Record have been deleted');
    }
    public function updatedPayrollNumber()
    {
        if ($this->payroll_number !=''){
            $user=\App\Models\EmployeeProfile::where('payroll_number',$this->payroll_number)->first();
            if (!is_null($user)){
                $this->staff_name=$user->full_name;
                $this->staff_number=$user->staff_number;
                $this->level=$user->grade_level;
                $this->step=$user->step;
                $this->salary_structure=$user->salary_structure;
            }else{
                $this->clear();
            }

        }

    }
    public function uploadFile()
    {
        $this->validate([
            'importFile'=>'required|mimes:xlsx',
        ]);
        $this->importFilePath=$this->importFile->store('imports');
//        Excel::import(new PromotionImport, $this->importFilePath);

            $import = new PromotionImport();
            $import->import($this->importFilePath);
        $this->upload_errors=$import->failures();
//        dd($import->failures()->collect());
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
    }
    public function post_to_ledger()
    {
        $this->ledger_fails=[];

        $this->alert('warning','Are you sure you want promote these staffs',[
            'showConfirmButton'=>true,
            'onConfirmed'=>'confirm',
            'showCancelButton'=>true,
            'timer'=>90000,
            'position'=>'center'
        ]);
    }

    public function confirm()
    {
        $name="Staff Promotion";
        backup_es($name);

        $promotions=StaffPromotion::all();
        $this->ledger_fails=array();

        foreach ($promotions as $promotion){
           $employee=\App\Models\EmployeeProfile::where('payroll_number',$promotion->payroll_number)->first();
//           $employeevv=\App\Models\EmployeeProfile::where('payroll_number',"SSE/CA/PF/014")->first();
       if (!is_null($employee)){



           $salary=SalaryStructureTemplate::where('salary_structure_id',$promotion->salary_structure)->where('grade_level',$promotion->level)->first();
           $a=SalaryAllowanceTemplate::where('salary_structure_id',$promotion->salary_structure)->whereRaw('? between grade_level_from and grade_level_to', [$promotion->level])->get();
           $d=SalaryDeductionTemplate::where('salary_structure_id',$promotion->salary_structure)->whereRaw('? between grade_level_from and grade_level_to', [$promotion->level])->get();

           if(!empty($salary)) {
               $annual_salary = $salary["Step" . $promotion->step];

               $basic_salary = round($annual_salary / 12, 2);

               if (SalaryUpdate::where('employee_id', $employee->id)->exists()) {
                   $salary_update = SalaryUpdate::where('employee_id', $employee->id)->first();
                   $total_allow = 0;
                   foreach ($a as $key => $allow) {
                       if ($allow->allowance_type == 1) {
                           $amount = round($basic_salary / 100 * $allow->value, 2);
                       } else {
                           $amount = $allow->value;
                       }
                       $salary_update["A$allow->allowance_id"] = $amount;
                       $total_allow += round($amount, 2);
                       $salary_update->save();
                   }
                   //deduction
                   $total_deduct = 0;
                   foreach (Deduction::where('status',1)->get() as $deduction)
                   {
//                       $basic_salary=$basic_salary;
                       if($deduction->id == 1){
                           $paye=app(DeductionCalculation::class);
                           $default_paye_calculation=app_settings()->paye_calculation;
                           $default_statutory_calculation=app_settings()->statutory_deduction;
                           if ($default_paye_calculation == 2){
                               $amount= $paye->paye_calculation1($basic_salary,$default_statutory_calculation);
                           }else{
                               $amount= $paye->paye_calculation2($basic_salary,$default_statutory_calculation);
                           }

                       }
                       else{

                           $dedTemp=SalaryDeductionTemplate::where('salary_structure_id',$promotion->salary_structure)
                               ->whereRaw('? between grade_level_from and grade_level_to', [$promotion->level])
                               ->where('deduction_id',$deduction->id)->first();
                           //check if percentage of basic
                           if(!is_null($dedTemp)){
                               if ($dedTemp->deduction_type==1){
                                   $amount=round($basic_salary/100 * $dedTemp->value,2);
                               }else{
                                   $amount=$dedTemp->value;
                               }
                               //check if employee has pension
                               if ($dedTemp->deduction_id ==2 || $dedTemp->deduction_id==3){
                                   if ($employee->pfa_name == 10)
                                   {
                                       $amount=0.00;
                                   }
                               }
                               //check union
                               elseif(UnionDeduction::where('deduction_id',$dedTemp->deduction_id)->get()->count()>0){
                                   if (UnionDeduction::where('deduction_id',$dedTemp->deduction_id)->where('union_id',$this->staff_union)->get()->count() > 0){
                                       $amount=  $amount;
                                   }else{
                                       $amount=0.00;
                                   }
                               }
                           }else{
                               $amount=$salary["D$deduction->id"];
                           }
                       }
                       $salary_update["D$deduction->id"]=$amount;
                       $total_deduct += round($amount,2);
                       $salary->save();
                   }

                   $employee->grade_level=$promotion->level;
                   $employee->step=$promotion->step;
                   $employee->salary_structure=$promotion->salary_structure;
                   $employee->save();
                   $salary_update->basic_salary = $basic_salary;
                   $salary_update->total_allowance = $total_allow;
                   $salary_update->total_deduction = $total_deduct;
                   $total_earning = round($basic_salary + $total_allow, 2);
                   $gross_pay = $total_earning;
                   $net_pay = round($gross_pay - $total_deduct, 2);
                   $salary_update->gross_pay = $gross_pay;
                   $salary_update->net_pay = $net_pay;
                   $salary_update->save();
                   $promotion->status=1;
                   $promotion->save();
               }
           }else{
               $data=[
                   'ss'=>$promotion->salary_structure,
                   'l'=>$promotion->level,
                   'id'=>$employee->id,
               ];
               array_push($this->ledger_fails,$data);
           }

       }
       }
        $user = Auth::user();
        $log = new ActivityLog();
        $log->user_id = $user->id;
        $log->action = "Promoted ".StaffPromotion::get()->count()." staffs";
        $log->save();
        $this->alert('success','Record have been successfully posted to ledger');
    }

    public function clear_record()
    {
        $this->alert('warning','Are you sure you want to clear all records',[
            'showConfirmButton'=>true,
            'onConfirmed'=>'clearAll',
            'showCancelButton'=>true,
            'timer'=>90000,
            'position'=>'center'
        ]);
    }
    public function clearAll()
    {
        StaffPromotion::query()->truncate();
        $this->alert('success','Record have been cleared successfully');
    }


    public function render()
    {
        $promotions=StaffPromotion::paginate(50);

        return view('livewire.forms.employee-promotion',compact('promotions'))->extends('components.layouts.app');
    }
}
