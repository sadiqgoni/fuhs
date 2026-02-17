<?php

namespace App\Livewire\Pages;

use App\Exports\AnnualIncrement;
use App\Exports\LoadDeduction;
use App\Models\ActivityLog;
use App\Models\AnnualSalaryIncrement;
use App\Models\Allowance;
use App\Models\Deduction;
use App\Models\EmployeeProfile;
use App\Models\SalaryAllowanceTemplate;
use App\Models\SalaryDeductionTemplate;
use App\Models\SalaryUpdate;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class AnnualIncrementHistory extends Component
{
    public $perpage = 25, $month, $status;
    public $revertId = null;

    use WithPagination;
    public $date_from, $date_to;
    use WithoutUrlPagination, LivewireAlert;

    protected $listeners = ['revertIncrement'];

    public function mount()
    {
        $this->date_from=Carbon::now()->format('m/d/Y');
        $this->date_to=Carbon::now()->format('m/d/Y');
    }
    public function confirmRevertIncrement(int $id): void
    {
        $this->revertId = $id;
        $this->alert('question', 'Revert this annual increment? Employee step and salary will be restored to previous values.', [
            'showConfirmButton' => true,
            'showCancelButton' => true,
            'onConfirmed' => 'revertIncrement',
            'timer' => 90000,
            'position' => 'center',
            'confirmButtonText' => 'Yes, revert',
        ]);
    }

    /**
     * Revert a single annual (step) increment: restore employee step and salary to previous values.
     */
    public function revertIncrement(): void
    {
        $id = $this->revertId;
        $this->revertId = null;
        if (!$id) {
            return;
        }
        $this->authorize('can_save');
        $increment = AnnualSalaryIncrement::where('id', $id)->where('status', 1)->first();
        if (!$increment) {
            $this->alert('warning', 'Increment record not found or cannot be reverted.', ['timer' => 5000]);
            return;
        }
        if ($increment->current_salary === null) {
            $this->alert('warning', 'Revert not available: previous salary was not stored for this record.', ['timer' => 5000]);
            return;
        }
        $employee = EmployeeProfile::find($increment->employee_id);
        $salary_update = SalaryUpdate::where('employee_id', $increment->employee_id)->first();
        if (!$employee || !$salary_update) {
            $this->alert('warning', 'Employee or salary record not found.', ['timer' => 5000]);
            return;
        }
        $old_basic_salary = $increment->current_salary ?? 0;
        $employee->step = $increment->old_grade_step;
        $employee->save();
        $salary_update->basic_salary = $old_basic_salary;
        foreach (SalaryAllowanceTemplate::where('salary_structure_id', $employee->salary_structure)
            ->whereRaw('? between grade_level_from and grade_level_to', [$employee->grade_level])
            ->where('allowance_type', 1)->get() as $allowance) {
            $salary_update["A{$allowance->allowance_id}"] = round($old_basic_salary / 100 * $allowance->value, 2);
        }
        $salary_update->save();
        foreach (SalaryDeductionTemplate::where('salary_structure_id', $employee->salary_structure)
            ->whereRaw('? between grade_level_from and grade_level_to', [$employee->grade_level])
            ->where('deduction_type', 1)->get() as $deduction) {
            $salary_update["D{$deduction->deduction_id}"] = round($old_basic_salary / 100 * $deduction->value, 2);
        }
        $salary_update->save();
        $total_allowance = 0;
        $total_deduction = 0;
        foreach (Allowance::all() as $allow) {
            $total_allowance += round($salary_update['A' . $allow->id] ?? 0, 2);
        }
        foreach (Deduction::all() as $ded) {
            $total_deduction += round($salary_update['D' . $ded->id] ?? 0, 2);
        }
        $salary_update->gross_pay = round($old_basic_salary + $total_allowance, 2);
        $salary_update->net_pay = round($salary_update->gross_pay - $total_deduction, 2);
        $salary_update->save();
        $increment->delete();
        $this->alert('success', 'Annual increment reverted successfully.');
    }

    public function export()
    {
        $histories=AnnualSalaryIncrement::when($this->status,function ($query){
            return $query->where('status',$this->status);
        })
            ->when($this->date_from,function ($query){
                $date_from = Carbon::parse($this->date_from)->format('Y-m-d');
                $date_to = Carbon::parse($this->date_to)->format('Y-m-d');
                return $query ->whereBetween('month_year',[$date_from,$date_to]);
            })->get();
        if ($histories->count()>0) {

            $this->alert('success', 'Exported successfully');
            $user = Auth::user();
            $log = new ActivityLog();
            $log->user_id = $user->id;
            $log->action = "Exported $this->month annual increment record";
            $log->save();
            return Excel::download(new AnnualIncrement($histories), 'annual_increments.xlsx');
        }
        else{
            $this->alert('warning',"No record found",['timer'=>9200]);
        }
    }
    public function render()
    {
        $histories=AnnualSalaryIncrement::when($this->status,function ($query){
            return $query->where('status',$this->status);
        })

            ->when($this->date_from,function ($query){
                $date_from = Carbon::parse($this->date_from)->format('Y-m-d');
                $date_to = Carbon::parse($this->date_to)->format('Y-m-d');
                return $query ->whereBetween('month_year',[$date_from,$date_to]);
            })

            ->paginate($this->perpage);
//        dd($histories);
        return view('livewire.pages.annual-increment-history',compact('histories'))->extends('components.layouts.app');
    }
}
