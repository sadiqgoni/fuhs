<?php

namespace App\Livewire\Forms;

use App\DeductionCalculation;
use App\Models\ActivityLog;
use App\Models\Allowance;
use App\Models\Deduction;
use App\Models\Department;
use App\Models\SalaryAllowanceTemplate;
use App\Models\StepAllowanceTemplate;
use App\Models\SalaryDeductionTemplate;
use App\Models\SalaryStructure;
use App\Models\SalaryStructureTemplate;
use App\Models\SalaryUpdate;
use App\Models\UnionDeduction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class SalaryUpdateCenter extends Component
{
    use WithPagination, WithoutUrlPagination;
    public $salary_arears = 0, $salary_deduction = 0.00;
    public $employee_info;
    public $disabled = "disabled readonly";
    public $salary;
    public $singleResetId, $singleDeductResetId;
    public $record = true;
    use LivewireAlert;
    public $allowances, $deductions, $depts;
    public $ids, $si, $search_employee;
    public $search, $filter_dept, $filter_unit, $filter_type, $perpage = 12;
    public $next_id, $previous_id;
    public $bulkResetInProgress = false;

    protected $rules = [
        'salary_arears' => 'nullable|regex:/^\d*(\.\d{2})?$/',
        'salary_deduction' => ['regex:/^\d{1,2}(\.\d{1,2})?$|^100(\.00?)?$/'],
    ];
    protected $messages = [
        'salary_arears.regex' => '',
        'salary_deduction.regex' => ''
    ];

    public function getListeners()
    {
        return ['confirmed', 'dismissed', 'stored', 'bulkResetConfirmed'];

    }

    public $allow = [];
    public $salaryUpdate;
    public $inputs = [];
    public $fieldNames = [];

    public $deduct = [];
    public $fields = [];
    public $deductionNames = [];



    public function confirm($id)
    {
        $this->validate();
        $rules = [];
        foreach ($this->fieldNames as $field => $name) {
            $rules["inputs.$field"] = 'required|numeric|regex:/^\d+(\.\d{1,2})?$/';
        }
        foreach ($this->deductionNames as $field => $name) {
            $rules["fields.$field"] = 'required|numeric|regex:/^\d+(\.\d{1,2})?$/';
        }

        $this->validate($rules, $this->customMessages());

        $this->si = $id;
        $this->alert('warning', 'Are you sure you want to apply changes?', [
            'showConfirmButton' => true,
            'confirmButtonText' => 'Yes',
            'onConfirmed' => 'stored',
            'showCancelButton' => true,
            'onDismissed' => 'cancelled',
            'position' => 'center',
            'timer' => 90000,
            'toast' => true,
        ]);
    }

    public function stored()
    {
        $total_allow = 0;
        foreach ($this->inputs as $column => $value) {
            $this->salaryUpdate->$column = $value;
            $total_allow += floatval($value);
        }
        $total_deduct = 0;
        foreach ($this->fields as $column => $value) {
            $this->salaryUpdate->$column = $value;
            $total_deduct += floatval($value);

        }

        $this->salaryUpdate->save();

        // Only default to 0 if genuinely null/unset — never overwrite a real entered value
        $this->salary_arears = $this->salary_arears ?? 0.00;
        $this->salary_deduction = $this->salary_deduction ?? 0.00;
        $salary_update = $this->salary;




        $basic_salary = $salary_update->basic_salary;
        $total_earning = round($basic_salary + $total_allow + $this->salary_arears, 2);
        $gross_pay = $total_earning;

        $total_deduction = $total_deduct;
        $net_pay = round($gross_pay - $total_deduction, 2);

        $salary_update->salary_arears = $this->salary_arears;
        $salary_update->gross_pay = round($gross_pay, 2);
        $salary_update->net_pay = round($net_pay, 2);
        $salary_update->total_deduction = $total_deduct;
        $salary_update->total_allowance = $total_allow;
        $nhis = (0.5 / 100) * $gross_pay;
        $employer_pension = (10 / 100) * $gross_pay;
        $salary_update->nhis = round($nhis, 2);
        $salary_update->employer_pension = round($employer_pension, 2);
        $salary_update->save();

        $this->alert('success', 'Successful');

        $user = Auth::user();
        $log = new ActivityLog();
        $name = \App\Models\EmployeeProfile::find($salary_update->employee_id);
        $log->user_id = $user->id;
        $log->action = "Updated $name->full_name record in salary update";
        $log->save();
        $this->view($salary_update->employee_id);
    }
    public function updated($pro)
    {
        $this->validateOnly($pro);
    }
    public function close()
    {
        $this->record = true;
    }

    /**
     * Get next/previous employee IDs only among employees who have a salary record and still exist.
     * This avoids landing on an employee without a record when clicking Next/Previous
     * (e.g. after restore when IDs or salary_updates are out of sync).
     */
    protected function getNextPreviousIdsWithSalary(int $currentId): array
    {
        $employeeIdsWithSalary = \App\Models\EmployeeProfile::whereIn('id', SalaryUpdate::pluck('employee_id'))
            ->pluck('id')
            ->unique()
            ->sort()
            ->values()
            ->all();
        $next_id = null;
        $previous_id = null;
        foreach ($employeeIdsWithSalary as $eid) {
            if ($eid > $currentId) {
                $next_id = $eid;
                break;
            }
        }
        foreach (array_reverse($employeeIdsWithSalary) as $eid) {
            if ($eid < $currentId) {
                $previous_id = $eid;
                break;
            }
        }
        return ['next_id' => $next_id, 'previous_id' => $previous_id];
    }

    public function view($id)
    {
        $this->record = false;
        $this->ids = $id;
        $this->employee_info = \App\Models\EmployeeProfile::find($id);

        if (!$this->employee_info) {
            $this->alert('error', 'Employee not found');
            $this->record = true;
            return;
        }

        // Next/Previous only among employees that have a salary record (avoids "Salary record not found" when navigating)
        $nav = $this->getNextPreviousIdsWithSalary((int) $id);
        $this->next_id = $nav['next_id'];
        $this->previous_id = $nav['previous_id'];

        $this->salary = SalaryUpdate::where('employee_id', $id)->first();

        if (!$this->salary) {
            $this->record = true;
            // Open next or previous employee who has a record so user is not stuck (e.g. after restore when IDs are out of sync)
            if ($this->next_id !== null) {
                $this->alert('warning', 'Salary record not found for this employee. Opening next employee with a record.', ['timer' => 3500]);
                $this->view($this->next_id);
                return;
            }
            if ($this->previous_id !== null) {
                $this->alert('warning', 'Salary record not found for this employee. Opening previous employee with a record.', ['timer' => 3500]);
                $this->view($this->previous_id);
                return;
            }
            $this->alert('error', 'Salary record not found for this employee. No other employee with a salary record to open.');
            return;
        }

        $this->salaryUpdate = $this->salary;
        $this->allow = Allowance::where('status', 1)->get();
        $this->deduct = Deduction::where('status', 1)->get();

        foreach ($this->allow as $allowance) {
            $field = 'A' . $allowance->id;
            $this->inputs[$field] = number_format($this->salaryUpdate->$field, 2, '.', '');
            $this->fieldNames[$field] = $allowance->allowance_name;
        }
        foreach ($this->deduct as $deduction) {
            $field = 'D' . $deduction->id;
            $this->fields[$field] = number_format($this->salaryUpdate->$field, 2, '.', '');
            $this->deductionNames[$field] = $deduction->deduction_name;
        }

        if ($this->salary->D5 > 0) {
            $sd = round(($this->salary->D5 / $this->salary->gross_pay) * 100, 2);

        } else {
            $sd = 0;
        }
        $this->salary_deduction = $sd;
        try {
            $this->salary_arears = $this->salary->salary_arears;

        } catch (\Exception $exception) {
            $this->record = true;

        }
    }
    public function mount()
    {

        $this->depts = [];
        $this->salary_arears = 0;



    }
    public function updatedInputs($value, $key)
    {
        $this->validateOnly("inputs.$key", [
            "inputs.$key" => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
        ], $this->customMessages());

    }
    public function updatedFields($value, $key)
    {
        if ($key === 'D5') {
            $this->updatedSalaryDeduction();
            return;
        }
        $this->validateOnly("fields.$key", [
            "fields.$key" => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
        ], $this->customMessages());
    }



    public function updatedSalaryDeduction()
    {
        $this->validate(
            [
                'salary_deduction' => ['regex:/^\d{1,2}(\.\d{1,2})?$|^100(\.00?)?$/'],
            ],
            [
                'regex' => "Invalid percentage range"
            ]
        );
        $total_earning = round($this->salary->basic_salary + $this->salary->total_allowance + $this->salary_arears, 2);
        //        $sal_de=round($total_earning/100 *  $this->salary_deduction,2);
        if (array_key_exists('D5', $this->fields)) {
            $value = ($total_earning / 100) * $this->salary_deduction;
            $this->fields['D5'] = number_format($value, 2, '.', '');
        }


    }

    public function searchEmployee()
    {

        $emp = \App\Models\EmployeeProfile::where('payroll_number', $this->search_employee)->first();
        if ($emp) {
            $this->view($emp->id);
        }

    }
    public function updatedFilterUnit()
    {

        if ($this->filter_unit != '') {
            $this->depts = Department::where('unit_id', $this->filter_unit)->get();
        }
    }
    public function resetSalary()
    {
        $this->alert('warning', 'Are you sure you want to reset salary to default?', [
            'showConfirmButton' => true,
            'confirmButtonText' => 'Yes',
            'onConfirmed' => 'confirmed',
            'showCancelButton' => true,
            'onDismissed' => 'cancelled',
            'position' => 'center',
            'timer' => 90000,
            'timerProgressBar' => true,
            'toast' => true,
        ]);
    }

    /**
     * Reset salaries for all employees in the current list (filtered view).
     * This is useful after restore/import when some staff have no SalaryUpdate yet.
     */
    public function resetAllSalaries()
    {
        $this->alert('warning', 'Are you sure you want to reset salaries for all listed employees?', [
            'showConfirmButton' => true,
            'confirmButtonText' => 'Yes',
            'onConfirmed' => 'bulkResetConfirmed',
            'showCancelButton' => true,
            'onDismissed' => 'cancelled',
            'position' => 'center',
            'timer' => 90000,
            'timerProgressBar' => true,
            'toast' => true,
        ]);
    }

    public function bulkResetConfirmed()
    {
        $this->bulkResetInProgress = true;

        // Build the same employee list as in render(), but without pagination
        if ($this->search != '') {
            $query = \App\Models\EmployeeProfile::where('full_name', 'like', "%$this->search%")
                ->orWhere('phone_number', 'like', "%$this->search%")
                ->orWhere('payroll_number', 'like', "%$this->search%")
                ->orWhere('pension_pin', 'like', "%$this->search%")
                ->orWhere('staff_number', 'like', "%$this->search%");
        } else {
            $query = \App\Models\EmployeeProfile::when($this->filter_unit, function ($q) {
                return $q->where('unit', $this->filter_unit);
            })
                ->when($this->filter_dept, function ($q) {
                    return $q->where('department', $this->filter_dept);
                })
                ->when($this->filter_type, function ($q) {
                    return $q->where('employment_type', $this->filter_type);
                });
        }

        $employees = $query->get();

        foreach ($employees as $emp) {
            $this->employee_info = $emp;
            $salary = SalaryUpdate::firstOrCreate(
                ['employee_id' => $emp->id],
                ['basic_salary' => 0]
            );
            $this->salary = $salary;
            $this->ids = $emp->id;

            // Reuse the single-record reset logic
            $this->confirmed();
        }

        $this->bulkResetInProgress = false;
        $this->alert('success', 'Salaries have been reset for all listed employees.');
    }

    public function confirmed()
    {
        if ($this->employee_info && $this->employee_info->status == 1) {
            $salary = $this->salary;
            $emp = $this->employee_info;

            // Recalculate basic_salary from template based on current grade_level and step
            $salary_template = SalaryStructureTemplate::where('salary_structure_id', $emp->salary_structure)
                ->where('grade_level', $emp->grade_level)
                ->first();

            if (!$salary_template) {
                $this->alert('error', 'Salary template not found for current grade/step. Cannot reset.');
                return;
            }

            $annual_salary = $salary_template["Step" . $emp->step] ?? 0;
            $basic_salary = round($annual_salary / 12, 2);
            $salary->basic_salary = $basic_salary;

            $allow_temp = SalaryAllowanceTemplate::where('salary_structure_id', $emp->salary_structure)
                ->whereRaw('? between grade_level_from and grade_level_to', [$emp->grade_level])
                ->get();

            // Per-step overrides (e.g. Call Duty) for this employee
            $stepAllowances = StepAllowanceTemplate::where('salary_structure_id', $emp->salary_structure)
                ->where('grade_level', $emp->grade_level)
                ->where('step', $emp->step)
                ->get()
                ->keyBy('allowance_id');

            $allow_total = 0;
            foreach ($allow_temp as $item) {
                if (isset($stepAllowances[$item->allowance_id])) {
                    $amount = $stepAllowances[$item->allowance_id]->value;
                } else {
                    if ($item->allowance_type == 1) {
                        $amount = round($basic_salary / 100 * $item->value, 2);
                    } else {
                        $amount = $item->value;
                    }
                }
                $salary["A$item->allowance_id"] = $amount;
                $allow_total += round($amount, 2);
            }

            $deduct_total = 0;
            foreach (Deduction::where('status', 1)->get() as $deduction) {
                if ($deduction->id == 1) {
                    $paye = app(DeductionCalculation::class);
                    // Pass actual taxable allowances for this employee (total minus A1 = Responsibility, which is non-taxable)
                    $a1_amount = round((float) ($salary->A1 ?? 0), 2);
                    $taxable_allowances = max(0, round($allow_total - $a1_amount, 2));
                    $amount = $paye->compute_tax($basic_salary, $taxable_allowances);
                } else {
                    $dedTemp = SalaryDeductionTemplate::where('salary_structure_id', $emp->salary_structure)
                        ->whereRaw('? between grade_level_from and grade_level_to', [$emp->grade_level])
                        ->where('deduction_id', $deduction->id)->first();
                    //check if percentage of basic
                    if (!is_null($dedTemp)) {
                        if ($dedTemp->deduction_type == 1) {
                            $amount = round($basic_salary / 100 * $dedTemp->value, 2);
                        } else {
                            $amount = $dedTemp->value;
                        }
                        //check if employee has pension
                        if ($dedTemp->deduction_id == 2 || $dedTemp->deduction_id == 3) {
                            if ($emp->pfa_name == 10) {
                                $amount = 0.00;
                            }
                        }
                        //check union
                        elseif (UnionDeduction::where('deduction_id', $dedTemp->deduction_id)->exists()) {
                            if (
                                !UnionDeduction::where('deduction_id', $dedTemp->deduction_id)
                                    ->where('union_id', $emp->staff_union)->exists()
                            ) {
                                $amount = 0.00;
                            }
                        }
                    } else {
                        $amount = $salary["D$deduction->id"] ?? 0;
                    }
                }
                $salary["D$deduction->id"] = $amount;
                $deduct_total += round($amount, 2);
            }

            $total_earning = round($basic_salary + $allow_total + ($salary->salary_arears ?? 0), 2);
            $gross_pay = $total_earning;
            $total_deduction = $deduct_total;
            $deduct_salary_deduct = round($total_deduction + ($salary->D5 ?? 0), 2);
            $net_pay = round($gross_pay - $deduct_salary_deduct, 2);
            $nhis = (0.5 / 100) * $gross_pay;
            $employer_pension = (10 / 100) * $gross_pay;
            $salary->nhis = round($nhis, 2);
            $salary->employer_pension = round($employer_pension, 2);
            $salary->gross_pay = round($gross_pay, 2);
            $salary->net_pay = round($net_pay, 2);
            $salary->total_deduction = $deduct_total;
            $salary->total_allowance = $allow_total;
            $salary->save();

            $this->alert('success', 'Salary have been reset to default');
            $this->view($this->ids);

            $user = Auth::user();
            $log = new ActivityLog();
            $name = \App\Models\EmployeeProfile::find($this->salary->employee_id);
            $log->user_id = $user->id;
            $log->action = "Updated $name->full_name record in salary update";
            $log->save();
        }

    }

    private function customMessages()
    {
        $messages = [];
        foreach ($this->fieldNames as $field => $name) {
            $messages["inputs.$field.required"] = "$name is required.";
            $messages["inputs.$field.numeric"] = "$name must be a number.";
            $messages["inputs.$field.regex"] = "$name must have at most 2 decimal places.";
        }
        foreach ($this->deductionNames as $field => $name) {
            $messages["deductionInputs.$field.required"] = "$name is required.";
            $messages["deductionInputs.$field.numeric"] = "$name must be a number.";
            $messages["deductionInputs.$field.regex"] = "$name must have at most 2 decimal places.";
        }
        return $messages;
    }

    public function render()
    {
        if ($this->search != '') {
            $this->resetPage();
            $employees = \App\Models\EmployeeProfile::where('full_name', 'like', "%$this->search%")
                ->orWhere('phone_number', 'like', "%$this->search%")
                ->orWhere('payroll_number', 'like', "%$this->search%")
                ->orWhere('pension_pin', 'like', "%$this->search%")
                ->orWhere('staff_number', 'like', "%$this->search%")
                ->paginate($this->perpage);
        } else {
            $employees = \App\Models\EmployeeProfile::when($this->filter_unit, function ($query) {
                return $query->where('unit', $this->filter_unit);
            })
                ->when($this->filter_dept, function ($query) {
                    return $query->where('department', $this->filter_dept);
                })
                ->when($this->filter_type, function ($query) {
                    return $query->where('employment_type', $this->filter_type);
                })
                ->paginate($this->perpage);
        }
        $salary_structures = SalaryStructure::where('status', 1)->get();

        return view('livewire.forms.salary-update-center', compact('employees', 'salary_structures'))
            ->extends('components.layouts.app');
    }

}
