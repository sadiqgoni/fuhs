<?php

namespace App\Livewire\Reports;

use App\Exports\EmployeeExport;
use App\Models\ActivityLog;
use App\Models\Bank;
use App\Models\Deduction;
use App\Models\Department;
use App\Models\EmployeeProfile;
use App\Models\EmploymentType;
use App\Models\ReportColumn;
use App\Models\SalaryStructure;
use App\Models\StaffCategory;
use App\Models\Unit;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeReportCenter extends Component
{
    public $departments,
            $types,
            $categories,
            $salary_structures,
            $units;
    public $order_by='id',$orderAsc='asc';
    public $employee_type,
            $staff_category,
            $unit,
            $department,
            $salary_structure,
            $grade_level_from,
            $grade_level_to,
            $status;
    public $report_column=[],$report_title,$sub_title,$reports,$columns,$report_col;
    public $record=false;
    use LivewireAlert;
    protected $rules=[
        'report_title'=>'required',
        'sub_title'=>'nullable',
    ];
    public function updated($prop){
        $this->validateOnly($prop);
    }
    public function mount()
    {
        $this->departments=[];

    }
    public function generate()
    {
        $this->validate();
        ReportColumn::query()->truncate();
        if (empty($this->report_column)){
           $this->alert('warning',"Choose at least one (1) Report Column");
        }else {

            $employee = new ReportColumn();
            $employee->selected_columns = json_encode($this->report_column);
            $employee->report_title = $this->report_title;
            $employee->subtitle = $this->sub_title;
            $employee->save();
            $this->reports = EmployeeProfile::when($this->staff_category, function ($query) {
                return $query->where('staff_category', $this->staff_category);
            })
                ->when($this->unit, function ($query) {
                    return $query->where('unit', $this->unit);
                })
                ->when($this->employee_type, function ($query) {
                    return $query->where('employment_type', $this->employee_type);
                })
                ->when($this->salary_structure, function ($query) {
                    return $query->where('salary_structure', $this->salary_structure);
                })
                ->when($this->grade_level_from, function ($query) {
                    return $query->whereBetween('grade_level', [$this->grade_level_from, $this->grade_level_to]);

                })
                ->when($this->status, function ($query) {
                    return $query->where('status', $this->status);
                })
                ->orderBy($this->order_by, $this->orderAsc)
                ->get();
            if ($this->reports->count() > 0) {
                $this->columns = ReportColumn::first();
                $col_json = ReportColumn::first();
                $this->report_col = json_decode($col_json->selected_columns);

                $this->record = true;
                $this->alert('success', 'Nominal report have been generated successfully');
            } else {
                $this->alert('warning', no_record(), ['timer' => 9200]);
            }
        }
    }
    public function export()
    {
        $this->validate();
        ReportColumn::query()->truncate();
        $employee=new ReportColumn();
        $employee->selected_columns=json_encode($this->report_column);
        $employee->report_title=$this->report_title;
        $employee->subtitle=$this->sub_title;
        $employee->save();
        $this->reports=EmployeeProfile::when($this->staff_category,function ($query){
            return $query->where('staff_category',$this->staff_category);
        })
            ->when($this->unit,function ($query){
                return $query->where('unit',$this->unit);
            })
            ->when($this->employee_type,function ($query){
                return $query->where('employment_type',$this->employee_type);
            })
            ->when($this->salary_structure,function ($query){
                return $query->where('salary_structure',$this->salary_structure);
            })
            ->when($this->grade_level_from,function ($query){
                return $query->whereBetween('grade_level',[$this->grade_level_from,$this->grade_level_to]);

            })
            ->when($this->status,function ($query){
                return $query->where('status',$this->status);
            })
            ->orderBy($this->order_by,$this->orderAsc)
            ->get();
        if ($this->reports->count()>0) {
            $this->columns = ReportColumn::first();
            $col_json = ReportColumn::first();
            $this->report_col = json_decode($col_json->selected_columns);
            $array_merged = array($this->reports, $this->report_col);
            $this->alert('success', 'Exported successfully');
            $user = Auth::user();
            $log = new ActivityLog();
            $log->user_id = $user->id;
            $log->action = "Exported Employee record";
            $log->save();
            $name = report_file_name()."_Nominal_roll_".Carbon::now()->format('F Y');

            return Excel::download(new EmployeeExport($array_merged), $name . '.xlsx');
        }
        else{
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
    public function render()
    {
        $this->types=EmploymentType::all();
        $this->categories=StaffCategory::all();
        $this->salary_structures=SalaryStructure::where('status',1)->get();
        $this->units=Unit::where('status',1)->get();
        $deductions=Deduction::all();
        return view('livewire.reports.employee-report-center')->extends('components.layouts.app');
    }
}
