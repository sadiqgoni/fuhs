<?php

namespace App\Livewire\Forms;

use App\Models\ActivityLog;
use App\Models\Bank;
use App\Models\Deduction;
use App\Models\SalaryHistory;
use App\Models\TemporaryBankPaymentSummary;
use App\Models\TemporaryDeduction;
use App\Models\TemporatyBankPaymentReport;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class SalaryLedgerPosting extends Component
{
    public $month,$year,$description,$ids;
    use LivewireAlert;
    public function getListeners()
    {
        return['confirmed', 'dismissed'];
    }

    protected $rules=[
        'month'=>'required',
        'year'=>'required',
        'description'=>'required',
    ];
    public function mount()
    {
        $this->year = date('Y');
        $this->month = date('F');
        $this->generateDescription();
    }

    public function generateDescription()
    {
        if ($this->month || $this->year) {
            $institutionName = strtoupper(app_settings()->name ?? 'INSTITUTION');
            $month = $this->month ?: date('F');
            $year = $this->year ?: date('Y');
            $this->description = $institutionName . ' SALARY ' . strtoupper($month) . ' ' . $year;
        }
    }

    public function updatedMonth()
    {
        $this->generateDescription();
    }

    public function updatedYear()
    {
        $this->generateDescription();
    }
    public function store()
    {

        $this->validate();
        if (SalaryHistory::where('salary_month',$this->month)->where('salary_year',$this->year)->exists()){
            $this->alert('warning','“Record Exist for '.$this->month. ' '. $this->year.' Do you want to overwrite?',[
                'showConfirmButton' => true,
                'confirmButtonText' => 'Yes',
                'onConfirmed' => 'confirmed',
                'showCancelButton' => true,
                'onDismissed' => 'cancelled',
                'position' => 'center',
                'timer'=>90000,
//                'timerProgressBar'=>true,
                'toast' => true,
            ]);
        }else {
           $this->store_record();
        }
    }
    public function store_record()
    {
        set_time_limit(2000);

        $this->months=Carbon::parse($this->month)->format('F');
        $employees=\App\Models\EmployeeProfile::join('salary_updates','salary_updates.employee_id','employee_profiles.id')
            ->select([
                'salary_updates.*',
                'employee_profiles.full_name',
                'employee_profiles.staff_number',
                'employee_profiles.payroll_number',
                'employee_profiles.department',
                'employee_profiles.staff_category',
                'employee_profiles.employment_type',
                'employee_profiles.phone_number',
                'employee_profiles.status',
                'employee_profiles.bank_code',
                'employee_profiles.account_number',
                'employee_profiles.bank_name',
                'employee_profiles.pfa_name',
                'employee_profiles.pension_pin',
                'employee_profiles.grade_level',
                'employee_profiles.unit',
                'employee_profiles.salary_structure',
                'employee_profiles.step',

            ])
            ->where('employee_profiles.status',1)
//            ->limit(900)
            ->get();
        foreach ($employees as $employee) {
            $description=$this->description;
            $salary = new SalaryHistory();
            $salary->salary_month = $this->months;
            $salary->salary_year = $this->year;

            $salary->pf_number = $employee->staff_number;
            $salary->ip_number = $employee->payroll_number;
            $salary->full_name = $employee->full_name;
            $salary->department = dept($employee->department);
            $salary->staff_category = staff_cat($employee->staff_category);
            $salary->phone_number = $employee->phone_number;
            $salary->employment_type = emp_type($employee->employment_type);
            $salary->employment_status = emp_status($employee->status);
            $salary->salary_structure = ss($employee->salary_structure);
            $salary->grade_level = $employee->grade_level;
            $salary->step = $employee->step;
            $salary->unit = unit_name($employee->unit);
            $salary->bank_code = $employee->bank_code;
            $salary->account_number = $employee->account_number;
            $salary->bank_name = $employee->bank_name;
            $salary->pfa_name = $employee->pfa_name;
            $salary->pension_pin = $employee->pension_pin;
            $salary->basic_salary = round($employee->basic_salary,2);
            $salary->A1 = $employee->A1;
            $salary->A2 = $employee->A2;
            $salary->A3 = $employee->A3;
            $salary->A4 = $employee->A4;
            $salary->A5 = $employee->A5;
            $salary->A6 = $employee->A6;
            $salary->A7 = $employee->A7;
            $salary->A8 = $employee->A8;
            $salary->A9 = $employee->A9;
            $salary->A10 = $employee->A10;
            $salary->A11 = $employee->A11;
            $salary->A12 = $employee->A12;
            $salary->A13 = $employee->A13;
            $salary->A14 = $employee->A14;
            $salary->D1 = $employee->D1;
            $salary->D2 = $employee->D2;
            $salary->D3 = $employee->D3;
            $salary->D4 = $employee->D4;
            $salary->D5 = $employee->D5;
            $salary->D6 = $employee->D6;
            $salary->D7 = $employee->D7;
            $salary->D8 = $employee->D8;
            $salary->D9 = $employee->D9;
            $salary->D10 = $employee->D10;
            $salary->D11 = $employee->D11;
            $salary->D12 = $employee->D12;
            $salary->D13 = $employee->D13;
            $salary->D14 = $employee->D14;
            $salary->D15 = $employee->D15;
            $salary->D16 = $employee->D16;
            $salary->D17 = $employee->D17;
            $salary->D18 = $employee->D18;
            $salary->D19 = $employee->D19;
            $salary->D20 = $employee->D20;
            $salary->D21 = $employee->D21;
            $salary->D22 = $employee->D22;
            $salary->D23 = $employee->D23;
            $salary->D24 = $employee->D24;
            $salary->D25 = $employee->D25;
            $salary->D26 = $employee->D26;
            $salary->D27 = $employee->D27;
            $salary->D28 = $employee->D28;
            $salary->D29 = $employee->D29;
            $salary->D30 = $employee->D30;
            $salary->D31 = $employee->D31;
            $salary->D32 = $employee->D32;
            $salary->D33 = $employee->D33;
            $salary->D34 = $employee->D34;
            $salary->D35 = $employee->D35;
            $salary->D36 = $employee->D36;
            $salary->D37 = $employee->D37;
            $salary->D38 = $employee->D38;
            $salary->D39 = $employee->D39;
            $salary->D40 = $employee->D40;
            $salary->D41 = $employee->D41;
            $salary->D42 = $employee->D42;
            $salary->D43 = $employee->D43;
            $salary->D44 = $employee->D44;
            $salary->D45 = $employee->D45;
            $salary->D46 = $employee->D46;
            $salary->D47 = $employee->D47;
            $salary->D48 = $employee->D48;
            $salary->D49 = $employee->D49;
            $salary->D50 = $employee->D50;
            $salary->salary_areas = $employee->salary_arears;
            $salary->gross_pay = $employee->gross_pay;
            $salary->total_deduction = $employee->total_deduction;
            $salary->total_allowance = $employee->total_allowance;
            $salary->net_pay = $employee->net_pay;
            $salary->deduction_countdown = $employee->deduction_countdown;
            $salary->nhis = $employee->nhis;
            $salary->employer_pension = $employee->employer_pension;
            $salary->salary_remark = $description;

            $date_month=$this->month."-".$this->year;
            $salary->date_month = Carbon::parse($date_month)->format('Y-m-d');
            $salary->save();

            $this->alert('success','Record have been posted successfully');
            $user=Auth::user();
            $log=new ActivityLog();
            $log->user_id=$user->id;
            $log->action="Posted to ledger ";
            $log->save();

        }

    }
    public function update_record()
    {

        set_time_limit(2000);
        $salaries = SalaryHistory::where('salary_month',$this->month)->where('salary_year',$this->year)
            ->get();
        foreach ($salaries as $salary){
            try
            {
                $ids = explode(",", $salary->id);
                // call delete on the query builder (no get())
                SalaryHistory::destroy($ids);
            }catch (\Exception){}
        }
        $this->store_record();
        $this->alert('success','Salary ledger for the month of '.$this->month.' '.$this->year.' have been updated');
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $log->action="updated ledger record ";
        $log->save();
    }


    public function updated($pop){
        $this->validateOnly($pop);
    }
    public function confirmed()
    {
        $this->update_record();
    }
    public function render()
    {
        $recentSalaries = SalaryHistory::select('salary_month', 'salary_year', DB::raw('COUNT(*) as staff_count'))
            ->groupBy('salary_month', 'salary_year')
            ->orderBy('salary_year', 'desc')
            ->orderBy('salary_month', 'desc')
            ->limit(10)
            ->get();

        return view('livewire.forms.salary-ledger-posting', compact('recentSalaries'))->extends('components.layouts.app');
    }
}
