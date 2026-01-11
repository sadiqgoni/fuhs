<?php

namespace App\Livewire\Forms;

use App\Models\ActivityLog;
use App\Models\Allowance;
use App\Models\Deduction;
use App\Models\Department;
use App\Models\EmploymentType;
use App\Models\SalaryAllowanceTemplate;
use App\Models\SalaryDeductionTemplate;
use App\Models\SalaryStructure;
use App\Models\SalaryStructureTemplate;
use App\Models\SalaryUpdate;
use App\Models\StaffCategory;
use App\Models\Unit;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class AnnualSalaryIncrement extends Component
{
    public $departments,
    $types,
    $categories,
    $salary_structures,
    $units;
    public $orderBy,$orderAsc=true;
    public $employee_type,
        $staff_category,
        $unit,
        $department,
        $salary_structure,
        $grade_level_from,
        $grade_level_to,
        $status;
    public $number_of_increment,$increment_date,$count;
    use LivewireAlert;
    protected $rules=[
        'number_of_increment'=>'required|max:1|min:1|gt:0',
        'increment_date'=>'required'
    ];
    public function updated($pro)
    {
        $this->validateOnly($pro);
    }
    protected $listeners=['confirmed','canceled'];
    public function confirm()
    {
        $this->validate();
        $this->alert('question',' This will increment salaries of the selected employees, do you want to continue?',[
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
    public function confirmed()
    {
        $this->store();

    }
    public function store()
    {
        $this->authorize('can_save');
        $this->validate();

        set_time_limit(2000);
        $name="Annual Salary Increment";
        backup_es($name);

        $employees=\App\Models\EmployeeProfile::when($this->salary_structure,function ($query){
            return $query->where('salary_structure',$this->salary_structure);
        })

            ->when($this->grade_level_from,function ($query){
                return $query->whereBetween('grade_level',[$this->grade_level_from,$this->grade_level_to]);
            })
            ->when($this->employee_type,function ($query){
                return $query->where('employment_type',$this->employee_type);
            })
            ->when($this->staff_category,function ($query){
                return $query->where('staff_category',$this->staff_category);
            })
            ->when($this->status,function ($query){
                return $query->where('status',$this->status);
            })
            ->when($this->unit,function ($query){
                return $query->where('unit',$this->unit);
            })
            ->when($this->department,function ($query){
                return $query->where('department',$this->department);
            })
            ->get();
       $this->count=$employees->count();
       if ($employees->count()>0) {
           foreach ($employees as $employee) {
               $salary_structure = SalaryStructureTemplate::where('salary_structure_id', $employee->salary_structure)
                   ->where('grade_level', $employee->grade_level)
                   ->first();

               if (!\App\Models\AnnualSalaryIncrement::where('employee_id', $employee->id)->whereDate('month_year', Carbon::parse($this->increment_date)->format('Y-m-d'))->exists()) {
                   if ($salary_structure != null) {
                       if ($employee->step < $salary_structure->no_of_grade_steps) {
                           if ($employee->step + $this->number_of_increment <= $salary_structure->no_of_grade_steps) {
                               $grade_step = $employee->step + $this->number_of_increment;
                           } elseif ($employee->step + $this->number_of_increment > $salary_structure->no_of_grade_steps) {
                               $grade_step = $salary_structure->no_of_grade_steps;
                           }
                           $salary_update = SalaryUpdate::where('employee_id', $employee->id)->first();
                           $old_salary = $salary_update->basic_salary;
                           $old_grade_step = $employee->step;
                           $annual_salary = $salary_structure["Step" . $grade_step];
                           $basic_salary = round($annual_salary / 12, 2);

                           $salary_update->basic_salary = $basic_salary;
                           foreach (SalaryAllowanceTemplate::where('salary_structure_id', $employee->salary_structure)
                                        ->whereRaw('? between grade_level_from and grade_level_to', [$employee->grade_level])
                                        ->where('allowance_type', 1)->get() as $allowance) {
                               $salary_update["A$allowance->allowance_id"] = round($basic_salary / 100 * $allowance->value);
                               $salary_update->save();
                           }
                           foreach (SalaryDeductionTemplate::where('salary_structure_id', $employee->salary_structure)
                                        ->whereRaw('? between grade_level_from and grade_level_to', [$employee->grade_level])
                                        ->where('deduction_type', 1)->get() as $deduction)
                           {
                               $salary_update["D$deduction->deduction_id"] = round($basic_salary / 100 * $deduction->value);
                               $salary_update->save();
                           }
                           $total_allowance = 0;
                           $total_deduction = 0;
                           foreach (Allowance::all() as $allow) {
                               $total_allowance += round($salary_update['A' . $allow->id], 2);
                           }
                           foreach (Deduction::all() as $ded) {
                               $total_deduction += round($salary_update['D' . $ded->id], 2);
                           }
                           $total_earning = round($basic_salary + $total_allowance, 2);
                           $gross_pay = $total_earning;
                           $net_pay = round($gross_pay - $total_deduction, 2);
                           $salary_update->gross_pay = $gross_pay;
                           $salary_update->net_pay = $net_pay;
                           $salary_update->save();
                           $employee->step = $grade_step;
                           $employee->save();
                           $incrementObj = new \App\Models\AnnualSalaryIncrement();
                           $incrementObj->employee_id = $employee->id;
                           $incrementObj->increment_month = Carbon::parse($this->increment_date)->format('F');
                           $incrementObj->increment_year = Carbon::parse($this->increment_date)->format('Y');
                           $incrementObj->month_year = Carbon::parse($this->increment_date)->format('Y-m-d');
                           $incrementObj->salary_structure = $employee->salary_structure;
                           $incrementObj->grade_level = $employee->grade_level;
                           $incrementObj->old_grade_step = $old_grade_step;
                           $incrementObj->new_grade_step = $grade_step;
                           $incrementObj->status = 1;
                           $incrementObj->current_salary = $old_salary;
                           $incrementObj->new_salary = $basic_salary;
                           $incrementObj->save();
                       } else {
                           $incrementObj = new \App\Models\AnnualSalaryIncrement();
                           $salary_update = SalaryUpdate::where('employee_id', $employee->id)->first();
                           $incrementObj->employee_id = $employee->id;
                           $incrementObj->increment_month = Carbon::parse($this->increment_date)->format('F');
                           $incrementObj->increment_year = Carbon::parse($this->increment_date)->format('Y');
                           $incrementObj->month_year = Carbon::parse($this->increment_date)->format('Y-m-d');
                           $incrementObj->salary_structure = $employee->salary_structure;
                           $incrementObj->grade_level = $employee->grade_level;
                           $incrementObj->old_grade_step = $employee->step;
                           $incrementObj->new_grade_step = $employee->step;
                           $incrementObj->status = 0;
                           $incrementObj->current_salary = $salary_update->basic_salary;
                           $incrementObj->new_salary = $salary_update->basic_salary;
                           $incrementObj->save();
                       }
                   } else {
                       continue;
                   }
               }


           }
           $this->alert('success', 'Successful', [
               "timer" => 9000
           ]);
           $user = Auth::user();
           $log = new ActivityLog();
           $log->user_id = $user->id;
           $log->action = "Incremented $this->count employees salary";
           $log->save();
       }else{
           $this->alert('warning',no_record(),['timer'=>9200]);
       }
    }
    public function mount()
    {
        $this->departments=[];
    }
    public function updatedUnit(){
        if ($this->unit != ''){
            $this->departments=Department::where('unit_id',$this->unit)->get();
        }else{
            $this->departments=[];
        }
    }
    public function render()
    {
        $this->types=EmploymentType::all();
        $this->categories=StaffCategory::all();
        $this->salary_structures=SalaryStructure::where('status',1)->get();
        $this->units=Unit::where('status',1)->get();
        $deductions=Deduction::all();
        return view('livewire.forms.annual-salary-increment')->extends('components.layouts.app');
    }
}
