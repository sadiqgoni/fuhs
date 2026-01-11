<?php

namespace App\Livewire\Reports;

use App\Models\ActivityLog;
use App\Models\Department;
use App\Models\EmployeeProfile;
use App\Models\EmploymentType;
use App\Models\SalaryStructure;
use App\Models\StaffCategory;
use App\Models\Unit;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class ContractTermination extends Component
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
        $status,$employees;
    use LivewireAlert;
    public function generate()
    {
        set_time_limit(2000);
        $threeMonthsFromNow = Carbon::now()->addMonths(3)->toDateString();
        $this->employees=EmployeeProfile::when($this->salary_structure,function ($query){
            return $query->where('salary_structure',$this->salary_structure);
        })
            ->when($this->employee_type,function ($query){
                return $query->where('employment_type',$this->employee_type);
            })
            -> when($this->staff_category,function ($query){
                return $query->where('staff_category',$this->staff_category);
            })
            ->when($this->unit,function ($query){
                return $query->where('unit',$this->unit);
            })
            ->when($this->department,function ($query){
                return $query->where('department',$this->department);
            })
            ->when($this->status,function ($query){
                return $query->where('status',$this->status);
            })
            ->when($this->grade_level_from,function ($query){
                return $query->whereBetween('grade_level',[$this->grade_level_from,$this->grade_level_to]);
            })
            ->whereNotNull('contract_termination_date')
            ->whereDate('contract_termination_date','<=', $threeMonthsFromNow)
//            ->whereYear('contract_termination_date','=>',Carbon::now()->format('Y'))
            ->orderBy('contract_termination_date','asc')
            ->get();
        if ($this->employees->count()>0){
            $this->employees;
        }else{
            $this->alert('warning',no_record(),['timer'=>9200]);
        }
    }
    public function export()
    {
        set_time_limit(2000);

        $employees=EmployeeProfile::when($this->salary_structure,function ($query){
            return $query->where('salary_structure',$this->salary_structure);
        })
            ->when($this->employee_type,function ($query){
                return $query->where('employment_type',$this->employee_type);
            })
            -> when($this->staff_category,function ($query){
                return $query->where('staff_category',$this->staff_category);
            })
            ->when($this->unit,function ($query){
                return $query->where('unit',$this->unit);
            })
            ->when($this->department,function ($query){
                return $query->where('department',$this->department);
            })
            ->when($this->status,function ($query){
                return $query->where('status',$this->status);
            })
            ->when($this->grade_level_from,function ($query){
                return $query->whereBetween('grade_level',[$this->grade_level_from,$this->grade_level_to]);
            })
            ->whereNotNull('contract_termination_date')
            ->get();
        if ($employees->count()>0) {
            $this->alert('success', 'Exported successfully');
            $user = Auth::user();
            $log = new ActivityLog();
            $log->user_id = $user->id;
            $log->action = "Exported retired staff record";
            $log->save();

            return Excel::download(new \App\Exports\RetiredStaff($employees), 'Retirement list.xlsx');
        }else{
            $this->alert('warning',no_record(),['timer'=>9200]);
        }
    }
    public function mount()
    {
        $this->departments=[];
        $this->employees=[];
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
        return view('livewire.reports.contract-termination')->extends('components.layouts.app');
    }
}
