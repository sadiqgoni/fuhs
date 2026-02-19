<?php

namespace App\Livewire\Forms;

use App\DeductionCalculation;
use App\Models\ActivityLog;
use App\Models\Allowance;
use App\Models\Deduction;
use App\Models\Department;
use App\Models\EmploymentType;
use App\Models\SalaryAllowanceTemplate;
use App\Models\StepAllowanceTemplate;
use App\Models\SalaryDeductionTemplate;
use App\Models\SalaryStructure;
use App\Models\SalaryStructureTemplate;
use App\Models\SalaryUpdate;
use App\Models\StaffCategory;
use App\Models\UnionDeduction;
use App\Models\Unit;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class GroupSalaryUpdate extends Component
{
    public $departments,
    $types,
    $categories,
    $salary_structures,
    $units,
    $failed_records = [];
    public $orderBy, $orderAsc = true;
    public $employee_type,
    $staff_category,
    $unit,
    $department,
    $salary_structure,
    $grade_level_from,
    $grade_level_to,
    $status,
    $update_allow_deduct,
    $selected_allow_deduct,
    $percentage_of_basic,
    $fixed_amount,
    $amount = 0.00,
    $paye_calculation,
    $statutory_deduction;
    public $pc = false;
    public $specific_employee_ids = [];
    use LivewireAlert;

    protected function rules()
    {
        return [
            'selected_allow_deduct' => 'required',
            'specific_employee_ids' => 'required|array|min:1',
        ];
    }

    protected function messages()
    {
        return [
            'specific_employee_ids.required' => 'Please select at least one employee for the group update.',
            'specific_employee_ids.min' => 'Please select at least one employee for the group update.',
        ];
    }
    protected $listeners = ['confirmed', 'canceled'];
    public function updated($prop)
    {
        $this->validateOnly($prop);
    }
    public function confirm()
    {
        // Always require at least one employee to be selected — show a toast so it's visible even when scrolled
        if (empty($this->specific_employee_ids)) {
            $this->alert('warning', 'Please select at least one employee before applying the update.', [
                'position' => 'center',
                'timer' => 6000,
                'toast' => false,
            ]);
            return;
        }

        if ($this->update_allow_deduct == 2 && $this->selected_allow_deduct == 1) {
            // PAYE logic is automatic, no extra inputs needed
        } else {
            $this->validate();
            $this->validate(
                [
                    'selected_allow_deduct' => 'required',
                    'fixed_amount' => 'required_without:percentage_of_basic|regex:/^\d*(\.\d{2})?$/|',
                    'percentage_of_basic' => ['required_without:fixed_amount', 'regex:/^\d{1,2}(\.\d{1,2})?$|^100(\.00?)?$/']
                ],
                [
                    //            'percentage_of_basic.regex'=>'Value for percentage of basic field must be between 1-100',
                    'percentage_of_basic' => ['regex' => 'Value for percentage of basic field must be between 1-100'],
                    'fixed_amount' => ['regex' => 'Negative value not allowed'],


                ]
            );
        }
        $this->alert('question', 'Are you sure you want to update these record', [
            'showConfirmButton' => true,
            'showCancelButton' => true,
            'onConfirmed' => 'confirmed',
            'onDismissed' => 'cancelled',
            'timer' => 90000,
            //            'timerProgressBar'=>true,
            'position' => 'center',
            'confirmButtonText' => 'Yes',
        ]);
    }
    public function confirmed()
    {
        $this->store();
    }

    /**
     * Toggle-select all employees currently listed in the filtered candidates.
     */
    public function selectAllEmployees(array $ids): void
    {
        $this->specific_employee_ids = array_unique(array_merge($this->specific_employee_ids, $ids));
    }

    public function deselectAllEmployees(array $ids): void
    {
        $this->specific_employee_ids = array_values(array_diff($this->specific_employee_ids, $ids));
    }
    public function store_bj()
    {

        $this->failed_records = [];
        set_time_limit(2000);

        $employees = $this->employee();
        if ($employees->count() > 0) {

            foreach ($employees as $employee) {
                $salary_update = SalaryUpdate::where('employee_id', $employee->id)->first();
                if ($this->update_allow_deduct == 2 && $this->selected_allow_deduct == 1 && $this->paye_calculation != 1) {

                    $paye = app(DeductionCalculation::class);
                    // Use dynamic tax calculation system
                    $this->amount = $paye->compute_tax($salary_update->basic_salary);
                    $salary_update["D$this->selected_allow_deduct"] = $this->amount;
                    $salary_update->save();
                } elseif (($this->update_allow_deduct == 2 || $this->update_allow_deduct == 3)) {
                    if ($employee->pfa_name == 10) {
                        $this->amount = 0;
                    } else {
                        if ($this->percentage_of_basic != '') {
                            $this->amount = round($salary_update->basic_salary / 100 * $this->percentage_of_basic, 2);
                        } elseif ($this->fixed_amount != '') {
                            $this->amount = $this->fixed_amount;
                        }
                    }
                    $salary_update["D$this->selected_allow_deduct"] = $this->amount;
                    $salary_update->save();
                } elseif (UnionDeduction::where('deduction_id', $this->update_allow_deduct)->exists()) {
                    if (UnionDeduction::where('deduction_id', $this->update_allow_deduct)->where('union_id', $employee->staff_union)->exists()) {
                        if ($this->percentage_of_basic != '') {
                            $this->amount = round($salary_update->basic_salary / 100 * $this->percentage_of_basic, 2);
                            $salary_update["D$this->selected_allow_deduct"] = $this->amount;
                            $salary_update->save();
                        } elseif ($this->fixed_amount != '') {
                            $this->amount = $this->fixed_amount;
                            $salary_update["D$this->selected_allow_deduct"] = $this->amount;
                            $salary_update->save();
                        }
                    } else {
                        $this->amount = 0.00;
                        $salary_update["D$this->selected_allow_deduct"] = $this->amount;
                        $salary_update->save();
                    }
                } else {

                    if ($this->percentage_of_basic != '') {
                        if (is_null($this->percentage_of_basic)) {
                            $this->amount = 0;
                        } else {
                            $this->amount = $this->percentage_of_basic;
                        }
                        if ($this->selected_allow_deduct == 5 && $this->update_allow_deduct == 2) {
                            $this->amount = round($salary_update->gross_pay / 100 * $this->percentage_of_basic);

                        } else {
                            $this->amount = round($salary_update->basic_salary / 100 * $this->percentage_of_basic);
                        }
                    } elseif ($this->fixed_amount != '') {

                        if (is_null($this->fixed_amount)) {

                            $this->amount = 0;
                        } else {
                            $this->amount = $this->fixed_amount;
                        }
                        $this->amount = $this->fixed_amount;
                    }
                    if ($this->update_allow_deduct == 1) {
                        if (is_null($this->amount)) {
                            $this->amount = 0;
                        }
                        $salary_update["A$this->selected_allow_deduct"] = $this->amount;
                        $salary_update->save();
                    }
                    if ($this->update_allow_deduct == 2) {
                        if (is_null($this->amount)) {
                            $this->amount = 0;
                        }
                        if ($this->selected_allow_deduct == 2) {
                            if (none_pension($employee->id) == 10) {
                                $this->amount = 0;
                            }
                        }
                        //                    if (UnionDeduction::where('deduction_id',$this->selected_allow_deduct)->exists()){
//                        union_deduction($this->selected_allow_deduct,$basic_salary);
//                    }
                        $salary_update["D$this->selected_allow_deduct"] = $this->amount;
                        $salary_update->save();
                    }
                }
                $total_allowance = 0;
                $total_deduction = 0;
                foreach (Allowance::all() as $allowance) {
                    $total_allowance += round($salary_update['A' . $allowance->id], 2);
                }
                foreach (Deduction::all() as $deduction) {
                    $total_deduction += round($salary_update['D' . $deduction->id], 2);
                }
                $intp = $salary_update->net_pay;
                $total_earning = round($salary_update->basic_salary + $total_allowance + $salary_update->salary_arears, 2);
                $gross_pay = $total_earning;
                $total_deduction = $total_deduction;
                $net_pay = round($gross_pay - $total_deduction, 2);

                $salary_update->gross_pay = $gross_pay;
                $salary_update->net_pay = $net_pay;
                $salary_update->total_deduction = $total_deduction;
                $salary_update->total_allowance = $total_allowance;
                $nhis = (0.5 / 100) * $gross_pay;
                $employer_pension = (10 / 100) * $gross_pay;
                $salary_update->nhis = round($nhis, 2);
                $salary_update->employer_pension = round($employer_pension, 2);
                if ($net_pay < 0) {
                    $failed = [
                        'full_name' => $employee->full_name,
                        'staff_number' => $employee->staff_number,
                        'net_pay' => $intp,
                        'new_net_pay' => $net_pay,
                    ];
                    array_push($this->failed_records, $failed);
                    $user = Auth::user();
                    $log = new ActivityLog();
                    $log->user_id = $user->id;
                    $log->action = "Deduction have not been applied to " . $employee->full_name . " ($employee->staff_number)";
                    $log->save();
                } else {
                    $salary_update->save();

                }
            }
            $count = $employees->count();

            $this->alert('success', "Group update performed successfully, $count  records have been updated", ['timer' => 9100]);
            $user = Auth::user();
            $log = new ActivityLog();
            $log->user_id = $user->id;
            $log->action = "Made a group salary update ";
            $log->save();
        } else {
            $this->alert(
                'warning',
                'There is no staff that matches your selection criteria, please check and try again',
                ['timer' => 9100]
            );
        }

    }
    public function store()
    {
        $name = "Group Update";
        backup_es($name);

        $this->failed_records = [];
        set_time_limit(2000);
        $employees = $this->employee();
        if ($employees->count() > 0) {

            try {
                foreach ($employees as $employee) {
                    $salary_update = SalaryUpdate::where('employee_id', $employee->id)->first();
                    if (!$salary_update) {
                        continue;
                    }
                    if ($this->percentage_of_basic != '') {
                        if ($this->selected_allow_deduct == 5 && $this->update_allow_deduct == 2) {
                            $this->amount = round($salary_update->gross_pay / 100 * $this->percentage_of_basic);
                        } else {
                            $this->amount = round($salary_update->basic_salary / 100 * $this->percentage_of_basic);
                        }
                    } elseif ($this->fixed_amount != '') {
                        $this->amount = $this->fixed_amount;
                    }

                    if ($this->update_allow_deduct == 1) {
                        //allowance
                        $salary_update["A$this->selected_allow_deduct"] = $this->amount;
                        $salary_update->save();
                    } else {
                        //deductions
                        if ($this->selected_allow_deduct == 1) {
                            $paye = app(DeductionCalculation::class);
                            // Use dynamic tax calculation system
                            $this->amount = $paye->compute_tax($salary_update->basic_salary);
                        } elseif (($this->selected_allow_deduct == 2 || $this->selected_allow_deduct == 3)) {

                            if ($employee->pfa_name == 10) {
                                $this->amount = 0;
                            }
                        } elseif (UnionDeduction::where('deduction_id', $this->selected_allow_deduct)->exists()) {

                            if (UnionDeduction::where('deduction_id', $this->selected_allow_deduct)->where('union_id', $employee->staff_union)->exists()) {
                                $this->amount = $this->amount;
                            } else {
                                $this->amount = 0.00;
                            }
                        } else {

                            $this->amount = $this->amount;
                        }
                        $salary_update["D$this->selected_allow_deduct"] = $this->amount;
                        $salary_update->save();
                    }
                    $total_allowance = 0;
                    $total_deduction = 0;
                    foreach (Allowance::all() as $allowance) {
                        $total_allowance += round($salary_update['A' . $allowance->id], 2);
                    }
                    foreach (Deduction::all() as $deduction) {
                        $total_deduction += round($salary_update['D' . $deduction->id], 2);
                    }
                    $intp = $salary_update->net_pay;
                    $total_earning = round($salary_update->basic_salary + $total_allowance + $salary_update->salary_arears, 2);
                    $gross_pay = $total_earning;
                    $net_pay = round($gross_pay - $total_deduction, 2);

                    $salary_update->gross_pay = $gross_pay;
                    $salary_update->net_pay = $net_pay;
                    $salary_update->total_deduction = $total_deduction;
                    $salary_update->total_allowance = $total_allowance;
                    $nhis = (0.5 / 100) * $gross_pay;
                    $employer_pension = (10 / 100) * $gross_pay;
                    $salary_update->nhis = round($nhis, 2);
                    $salary_update->employer_pension = round($employer_pension, 2);
                    if ($net_pay < 0) {
                        $failed = [
                            'full_name' => $employee->full_name,
                            'staff_number' => $employee->staff_number,
                            'net_pay' => $intp,
                            'new_net_pay' => $net_pay,
                        ];
                        array_push($this->failed_records, $failed);
                        $user = Auth::user();
                        $log = new ActivityLog();
                        $log->user_id = $user->id;
                        $log->action = "Deduction have not been applied to " . $employee->full_name . " ($employee->staff_number)";
                        $log->save();
                    } else {
                        $salary_update->save();

                    }
                }

                $count = $employees->count();
                $this->alert('success', "Group update performed successfully. $count record(s) updated.", ['timer' => 9100]);
                $user = Auth::user();
                $log = new ActivityLog();
                $log->user_id = $user->id;
                $log->action = "Made a group salary update ";
                $log->save();
                $this->specific_employee_ids = [];

            } catch (\Exception $e) {
                $this->alert('error', 'An error occurred while applying the update: ' . $e->getMessage(), ['timer' => 9100]);
            }
        } else {
            $this->alert('warning', 'There is no staff that matches your selection criteria, please check and try again', ['timer' => 9100]);
        }
    }
    /**
     * Base query with current filters (used for both criteria and for narrowing specific list).
     */
    protected function filteredEmployeeQuery()
    {
        return \App\Models\EmployeeProfile::when($this->salary_structure, function ($query) {
            return $query->where('salary_structure', $this->salary_structure);
        })
            ->when($this->employee_type, function ($query) {
                return $query->where('employment_type', $this->employee_type);
            })
            ->when($this->staff_category, function ($query) {
                return $query->where('staff_category', $this->staff_category);
            })
            ->when($this->unit, function ($query) {
                return $query->where('unit', $this->unit);
            })
            ->when($this->department, function ($query) {
                return $query->where('department', $this->department);
            })
            ->when($this->status, function ($query) {
                return $query->where('status', $this->status);
            })
            ->when($this->grade_level_from, function ($query) {
                return $query->whereBetween('grade_level', [$this->grade_level_from, $this->grade_level_to]);
            });
    }

    public function employee()
    {
        if (!empty($this->specific_employee_ids)) {
            return $this->filteredEmployeeQuery()
                ->whereIn('id', $this->specific_employee_ids)
                ->get();
        }
        return collect();
    }
    public function updatedUpdateAllowDeduct()
    {

    }

    public function updatedPercentageOfBasic()
    {
        $this->fixed_amount = '';
        //        $this->amount=$this->fixed_amount;
        $this->validate(
            [
                'percentage_of_basic' => ['required_without:fixed_amount', 'regex:/^\d{1,2}(\.\d{1,2})?$|^100(\.00?)?$/', 'between:1,100', 'min:1']
            ],
            [
                //            'percentage_of_basic.regex'=>'Value for percentage of basic field must be between 1-100',
                'percentage_of_basic' => ['regex' => 'Value for percentage of basic field must be between 1-100'],


            ]
        );
    }
    public function updatedFixedAmount()
    {
        $this->percentage_of_basic = '';
        //        $this->amount=$this->percentage_of_basic;
        $this->validate(
            [
                'fixed_amount' => 'required_without:percentage_of_basic|regex:/^\d*(\.\d{2})?$/|',
            ],
            [
                'fixed_amount' => 'Negative value not allowed',


            ]
        );
    }

    public function mount()
    {
        $this->departments = [];
        $this->percentage_of_basic = 1;
        $this->statutory_deduction = app_settings()->statutory_deduction;
        $this->paye_calculation = app_settings()->paye_calculation;

    }
    public function updatedUnit()
    {
        if ($this->unit != '') {
            $this->departments = Department::where('unit_id', $this->unit)->get();
        } else {
            $this->departments = [];
        }
    }
    public function render()
    {
        $this->types = EmploymentType::all();
        $this->categories = StaffCategory::all();
        $this->salary_structures = SalaryStructure::where('status', 1)->get();
        $this->units = Unit::where('status', 1)->get();
        $deductions = Deduction::all();

        $specific_candidates = $this->filteredEmployeeQuery()
            ->select('id', 'full_name', 'staff_number', 'grade_level', 'step')
            ->orderBy('grade_level')
            ->orderBy('step')
            ->orderBy('full_name')
            ->get()
            ->groupBy(function ($item) {
                return 'Grade Level ' . $item->grade_level . ' - Step ' . $item->step;
            });

        return view('livewire.forms.group-salary-update', [
            'specific_candidates' => $specific_candidates,
        ])->extends('components.layouts.app');
    }
}
