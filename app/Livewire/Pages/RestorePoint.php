<?php

namespace App\Livewire\Pages;

use App\Models\EmployeeProfile;
use App\Models\EmployeeSalary;
use App\Models\SalaryHistory;
use App\Models\SalaryUpdate;
use Illuminate\Support\Carbon;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class RestorePoint extends Component
{
    public $perpage=50,$ids;
    protected $listeners=['confirm'];
    use LivewireAlert;
    public function store($id)
    {
        $restore=EmployeeSalary::where('backup_id',$id)->first();
        $date=Carbon::parse($restore->created_at)->format('D, M j, Y g:i A');
        $this->ids=$id;
        $this->alert('warning',"This is going to restore the payroll Database to its state before $restore->action operations of $date $restore->action Note that once you click on ok, you cannot undo this operation.
Do you want to continue?
",[
            'showConfirmButton'=>true,
            'onConfirmed'=>'confirm',
            'showCancelButton'=>true,
            'timer'=>90000,
            'position'=>'center'
        ]);
    }
    public function confirm()
    {
        EmployeeProfile::query()->truncate();
        SalaryUpdate::query()->truncate();
        $employees=EmployeeSalary::where('backup_id',$this->ids)->select([
            'created_at','updated_at','employment_id', 'full_name', 'department', 'staff_category', 'employment_type', 'staff_number', 'payroll_number', 'status', 'salary_structure', 'date_of_first_appointment', 'date_of_last_appointment', 'date_of_retirement', 'post_held', 'grade_level', 'step', 'rank', 'unit', 'phone_number', 'whatsapp_number', 'email', 'bank_name', 'account_number', 'bank_code', 'pfa_name', 'pension_pin', 'date_of_birth', 'gender', 'religion', 'tribe', 'marital_status', 'nationality', 'state_of_origin', 'local_government', 'profile_picture', 'tax_id', 'bvn', 'staff_union', 'name_of_next_of_kin', 'next_of_kin_phone_number', 'relationship', 'address',
        ])->get();
        $salaries=EmployeeSalary::where('backup_id',$this->ids)->select([
            'employee_id', 'basic_salary','A1', 'A2', 'A3', 'A4', 'A5', 'A6', 'A7', 'A8', 'A9', 'A10', 'A11', 'A12', 'A13', 'A14', 'D1', 'D2', 'D3', 'D4', 'D5', 'D6', 'D7', 'D8', 'D9', 'D10', 'D11', 'D12', 'D13', 'D14', 'D15', 'D16', 'D17', 'D18', 'D19', 'D20', 'D21', 'D22', 'D23', 'D24', 'D25', 'D26', 'D27', 'D28', 'D29', 'D30', 'D31', 'D32', 'D33', 'D34', 'D35', 'D36', 'D37', 'D38', 'D39', 'D40', 'D41', 'D42', 'D43', 'D44', 'D45', 'D46', 'D47', 'D48', 'D49', 'D50', 'salary_areas', 'gross_pay', 'total_allowance', 'total_deduction', 'net_pay', 'nhis', 'employer_pension', 'deduction_countdown',
        ])->get();

        $this->employee($employees);
        $this->salary($salaries);
        $this->alert('success','Payroll database has been restored successfully');
    }
    public function employee($restoreObj)
    {
        $insertion_data = array();
        foreach ($restoreObj as $index => $employee) {
            $new_data = [
                'employment_id'=>$employee->employment_id,
                'full_name'=>$employee->full_name,
                'department'=>$employee->department,
                'staff_category'=>$employee->staff_category,
                'employment_type'=>$employee->employment_type,
                'staff_number'=>$employee->staff_number,
                'payroll_number'=>$employee->payroll_number,
                'status'=>$employee->status,
                'salary_structure'=>$employee->salary_structure,
                'date_of_first_appointment'=>$employee->date_of_first_appointment,
                'date_of_last_appointment'=>$employee->date_of_last_appointment,
                'date_of_retirement'=>$employee->date_of_retirement,
                'post_held'=>$employee->post_held,
                'grade_level'=>$employee->grade_level,
                'step'=>$employee->step,
                'rank'=>$employee->rank,
                'unit'=>$employee->unit,
                'phone_number'=>$employee->phone_number,
                'whatsapp_number'=>$employee->whatsapp_number,
                'email'=>$employee->email,
                'bank_name'=>$employee->bank_name,
                'account_number'=>$employee->account_number,
                'bank_code'=>$employee->bank_code,
                'pfa_name'=>$employee->pfa_name,
                'pension_pin'=>$employee->pension_pin,
                'date_of_birth'=>$employee->date_of_birth,
                'gender'=>$employee->gender,
                'religion'=>$employee->religion,
                'tribe'=>$employee->tribe,
                'marital_status'=>$employee->marital_status,
                'nationality'=>$employee->nationality,
                'state_of_origin'=>$employee->state_of_origin,
                'local_government'=>$employee->local_government,
                'profile_picture'=>$employee->profile_picture,
//                'tax_id'=>$employee->tax_id,
//                'bvn'=>$employee->bvn,
//                'staff_union'=>$employee->staff_union,
                'name_of_next_of_kin'=>$employee->name_of_next_of_kin,
                'next_of_kin_phone_number'=>$employee->next_of_kin_phone_number,
                'relationship'=>$employee->relationship,
                'address'=>$employee->address,

            ];
            $insertion_data[] = $new_data;
        }
        $insertion_data = collect($insertion_data);
        $data_to_insert = $insertion_data->chunk(300);

        foreach ($data_to_insert as $key => $data) {
            try {
                \Illuminate\Support\Facades\DB::table('employee_profiles')->insert($data
                    ->toArray());
            } catch (\Illuminate\Database\QueryException $e) {
                $error = $e->getMessage();
                return "something went wrong contact the system developer";

            }
        }
    }
    public function salary($salary)
    {
        $insertion_data = array();
        foreach ($salary as $index => $employee) {
            $new_data = [
                'employee_id'=>$employee->employee_id,
                'basic_salary'=>$employee->basic_salary,
                'A1'=>$employee->A1,
                'A2'=>$employee->A2,
                'A3'=>$employee->A3,
                'A4'=>$employee->A4,
                'A5'=>$employee->A5,
                'A6'=>$employee->A6,
                'A7'=>$employee->A7,
                'A8'=>$employee->A8,
                'A9'=>$employee->A9,
                'A10'=>$employee->A10,
                'A11'=>$employee->A11,
                'A12'=>$employee->A12,
                'A13'=>$employee->A13,
                'A14'=>$employee->A14,
                'D1'=>$employee->D1,
                'D2'=>$employee->D2,
                'D3'=>$employee->D3,
                'D4'=>$employee->D4,
                'D5'=>$employee->D5,
                'D6'=>$employee->D6,
                'D7'=>$employee->D7,
                'D8'=>$employee->D8,
                'D9'=>$employee->D9,
                'D10'=>$employee->D10,
                'D11'=>$employee->D11,
                'D12'=>$employee->D12,
                'D13'=>$employee->D13,
                'D14'=>$employee->D14,
                'D15'=>$employee->D15,
                'D16'=>$employee->D16,
                'D17'=>$employee->D17,
                'D18'=>$employee->D18,
                'D19'=>$employee->D19,
                'D20'=>$employee->D20,
                'D21'=>$employee->D21,
                'D22'=>$employee->D22,
                'D23'=>$employee->D23,
                'D24'=>$employee->D24,
                'D25'=>$employee->D25,
                'D26'=>$employee->D26,
                'D27'=>$employee->D27,
                'D28'=>$employee->D28,
                'D29'=>$employee->D29,
                'D30'=>$employee->D30,
                'D31'=>$employee->D31,
                'D32'=>$employee->D32,
                'D33'=>$employee->D33,
                'D34'=>$employee->D34,
                'D35'=>$employee->D35,
                'D36'=>$employee->D36,
                'D37'=>$employee->D37,
                'D38'=>$employee->D38,
                'D39'=>$employee->D39,
                'D40'=>$employee->D40,
                'D41'=>$employee->D41,
                'D42'=>$employee->D42,
                'D43'=>$employee->D43,
                'D44'=>$employee->D44,
                'D45'=>$employee->D45,
                'D46'=>$employee->D46,
                'D47'=>$employee->D47,
                'D48'=>$employee->D48,
                'D49'=>$employee->D49,
                'D50'=>$employee->D50,
                'salary_arears'=>$employee->salary_areas,
                'gross_pay'=>$employee->gross_pay,
                'total_allowance'=>$employee->total_allowance,
                'total_deduction'=>$employee->total_deduction,
                'net_pay'=>$employee->net_pay,
//                'nhis'=>$employee->nhis,
//                'employer_pension'=>$employee->employer_pension,
                'deduction_countdown'=>$employee->deduction_countdown,
            ];
            $insertion_data[] = $new_data;
        }
        $insertion_data = collect($insertion_data);
        $data_to_insert = $insertion_data->chunk(300);
        foreach ($data_to_insert as $key => $data) {
            try {
                \Illuminate\Support\Facades\DB::table('salary_updates')->insert($data
                    ->toArray());
            } catch (\Illuminate\Database\QueryException $e) {
                $error = $e->getMessage();
               return "something went wrong contact the system developer";
            }
        }
    }
    public function render()
    {
        $restores=EmployeeSalary::orderBy('created_at','desc')->get()->unique('backup_id');

        return view('livewire.pages.restore-point',compact('restores'))->extends('components.layouts.app');
    }
}
