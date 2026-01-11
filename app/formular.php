<?php


use App\Models\SalaryAllowanceTemplate;
use App\Models\SalaryDeductionTemplate;
use App\Models\SalaryStructureTemplate;
use App\Models\UnionDeduction;


function formular_one($employee_id,$statutory_deduction)
{
    $salary_update=\App\Models\SalaryUpdate::where('employee_id',$employee_id)->first();

    $allowances=\App\Models\Allowance::where('taxable',1)
        ->get();
    $total=0;
    foreach ($allowances as $allowance){
        try {
            $total += $salary_update["A$allowance->id"];
        }catch (\Exception  $e){
            continue;
        }

    }
    $total_allow=$total;
    $annual_basic=$salary_update->basic_salary *12;
    $annual_allowance=$total_allow *12;
    $annual_gross=$annual_basic + $annual_allowance;

    $agp=(20/100) * $annual_gross;
    $consolidated_relief=200000.00 + $agp;


    //get Statutory Deduction
    if ($statutory_deduction==1){
        $pension= (8/100) * $annual_basic;
        $nhf= (2.5/100) * $annual_basic;
        $nhis= (0.5/100) * $annual_basic;
        $national_pension= 0;
        $gratuity= 0;
    }else{
        $pension= (8/100) * $annual_gross;
        $nhf= (2.5/100) * $annual_gross;
        $nhis= (0./100) * $annual_gross;
        $national_pension= 0;
        $gratuity= 0;
    }

    $total_relief=$consolidated_relief + $pension + $nhf + $nhis + $national_pension + $gratuity;
    $taxable_income=$annual_gross - $total_relief;

    //Now compute tax
    $tax_inc=$taxable_income;
    $balance=$tax_inc;
    $tax=0;
    $total_paye_per_month=0;
    if ($balance > 300000)
    {
        $tax=(7/100) * 300000;
        $balance=$balance - 300000;
    }
    else{
        $tax = (7/100) * 300000;
        $total_paye_per_month=$tax / 12;
    }
    if ($balance > 300000)
    {
        $tax = $tax + (11/100) * 300000;
        $balance=$balance - 300000;
    }
    else{
        $tax=$tax + (11/100) * $balance;
        $total_paye_per_month=$tax/12;
    }
    if($balance > 500000)
    {
        $tax = $tax + (15/100) * 500000;
        $balance = $balance - 500000;
    }
    else{
        $tax = $tax + (15/100) * $balance;
        $total_paye_per_month= $tax/12;
    }

    if($balance > 500000)
    {
        $tax = $tax + (19/100) * 500000;
        $balance = $balance - 500000;
    }
    else{
        $tax = $tax + (19/100) * $balance;
        $total_paye_per_month= $tax/12;
    }

    if($balance > 1600000)
    {
        $tax = $tax + (21/100) * 1600000;
        $balance = $balance - 1600000;
    }
    else{
        $tax = $tax + (21/100) * $balance;
        $total_paye_per_month= $tax/12;
    }
    $tax=$tax + (24/100) * $balance;
    $total_paye_per_month=round($tax/12,2);
    return $total_paye_per_month;

}

function formular_two($employee_id,$statutory_deduction)
{
    $salary_update=\App\Models\SalaryUpdate::where('employee_id',$employee_id)->first();

    $allowances=\App\Models\Allowance::where('taxable',1)
        ->get();
    $total=0;
    foreach ($allowances as $allowance){
        $total += $salary_update["A$allowance->id"];
    }
    $basic_salary=$salary_update->basic_salary;
    $total_allow=$total;
    $annual_basic=round($basic_salary * 12);

    $monthly_gross=$basic_salary + $total_allow;

    //statutory deductions

    if ($statutory_deduction == 1){
        $pension= (8/100) * $basic_salary;
        $nhf= (2.5/100) * $basic_salary;
        $nhis= (0.5/100) * $basic_salary;
    }else{
        $pension= (8/100) * $monthly_gross;
        $nhf= (2.5/100) * $monthly_gross;
        $nhis= (0.5/100) * $monthly_gross;
    }


    $net_pay=$monthly_gross - $nhf - $pension - $nhis;
    $bi=$net_pay/2;
    $annual_gross= $bi * 12;
    $relief= $annual_gross * 0.2 + (16666.6666 * 12);
    $taxable_income=$annual_gross- $relief;

    //Now compute tax

    $tax_inc=$taxable_income;
    $balance=$tax_inc;
    $tax=0;
    $total_paye_per_month=0;
    if ($balance > 300000)
    {
        $tax=(7/100) * 300000;
        $balance=$balance - 300000;
    }
    else{
        $tax = (7/100) * 300000;
        $total_paye_per_month=$tax / 12;
    }
    if ($balance > 300000)
    {
        $tax = $tax + (11/100) * 300000;
        $balance=$balance - 300000;
    }
    else{
        $tax=$tax + (11/100) * $balance;
        $total_paye_per_month=$tax/12;
    }
    if($balance > 500000)
    {
        $tax = $tax + (15/100) * 500000;
        $balance = $balance - 500000;
    }
    else{
        $tax = $tax + (15/100) * $balance;
        $total_paye_per_month= $tax/12;
    }

    if($balance > 500000)
    {
        $tax = $tax + (19/100) * 500000;
        $balance = $balance - 500000;
    }
    else{
        $tax = $tax + (19/100) * $balance;
        $total_paye_per_month= $tax/12;
    }

    if($balance > 1600000)
    {
        $tax = $tax + (21/100) * 1600000;
        $balance = $balance - 1600000;
    }
    else{
        $tax = $tax + (21/100) * $balance;
        $total_paye_per_month= $tax/12;
    }
    $tax=$tax + (24/100) * $balance;
    $total_paye_per_month=round($tax/12,2);

    return $total_paye_per_month;

}
function backup_es_()
{

    $employees=\App\Models\EmployeeProfile::join('salary_updates','salary_updates.employee_id','employee_profiles.id')->get();
    $insertion_data = array();
    $serial=\Illuminate\Support\Str::random('6').now();
    foreach ($employees as $index => $employee) {
        $new_data = [
            'backup_id'=>$serial,
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
            'tax_id'=>$employee->tax_id,
            'bvn'=>$employee->bvn,
            'staff_union'=>$employee->staff_union,
            'name_of_next_of_kin'=>$employee->name_of_next_of_kin,
            'next_of_kin_phone_number'=>$employee->next_of_kin_phone_number,
            'relationship'=>$employee->relationship,
            'address'=>$employee->address,
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
            'salary_areas'=>$employee->salary_areas,
            'gross_pay'=>$employee->gross_pay,
            'total_allowance'=>$employee->total_allowance,
            'total_deduction'=>$employee->total_deduction,
            'net_pay'=>$employee->net_pay,
            'nhis'=>$employee->nhis,
            'employer_pension'=>$employee->employer_pension,
            'deduction_countdown'=>$employee->deduction_countdown,
            'created_at'=>\Illuminate\Support\Carbon::now()
        ];
        $insertion_data[] = $new_data;
    }
    $insertion_data = collect($insertion_data);
    $data_to_insert = $insertion_data->chunk(100);
    foreach ($data_to_insert as $key => $data) {
        try {
            \Illuminate\Support\Facades\DB::table('employee_salaries')->insert($data
                ->toArray());
        } catch (\Illuminate\Database\QueryException $e) {
            $error = $e->getMessage();
            echo $error;
        }
    }

}
function backup_es($name)
{

    $employees=\App\Models\EmployeeProfile::join('salary_updates','salary_updates.employee_id','employee_profiles.id')->get();
    $insertion_data = array();
    $serial=\Illuminate\Support\Str::random('6').now();
    foreach ($employees as $index => $employee) {
        if($employee->date_of_first_appointment ==0 ){
            $employee->date_of_first_appointment=null;
        }
        if($employee->date_of_last_appointment ==0 ){
            $employee->date_of_last_appointment=null;
        }
        if($employee->date_of_retirement ==0 ){
            $employee->date_of_retirement=null;
        }
        if($employee->contract_termination_date ==0 ){
            $employee->contract_termination_date=null;
        }
        $new_data = [
            'action'=>$name,
            'backup_id'=>$serial,
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
            'contract_termination_date'=>$employee->contract_termination_date,
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
            'tax_id'=>$employee->tax_id,
            'bvn'=>$employee->bvn,
            'staff_union'=>$employee->staff_union,
            'name_of_next_of_kin'=>$employee->name_of_next_of_kin,
            'next_of_kin_phone_number'=>$employee->next_of_kin_phone_number,
            'relationship'=>$employee->relationship,
            'address'=>$employee->address,
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
            'salary_areas'=>$employee->salary_areas,
            'gross_pay'=>$employee->gross_pay,
            'total_allowance'=>$employee->total_allowance,
            'total_deduction'=>$employee->total_deduction,
            'net_pay'=>$employee->net_pay,
            'nhis'=>$employee->nhis,
            'employer_pension'=>$employee->employer_pension,
            'deduction_countdown'=>$employee->deduction_countdown,
            'created_at'=>\Illuminate\Support\Carbon::now()
        ];
        $insertion_data[] = $new_data;
    }
    $insertion_data = collect($insertion_data);
    $data_to_insert = $insertion_data->chunk(100);
    foreach ($data_to_insert as $key => $data) {
        try {
            \Illuminate\Support\Facades\DB::table('employee_salaries')->insert($data
                ->toArray());
        } catch (\Illuminate\Database\QueryException $e) {
            dd($e);

            $error = $e->getMessage();
            echo $error;
        }
    }

}


function none_pension($id)
{
    try {
        $emp=\App\Models\EmployeeProfile::find($id);
        return $emp->pfa;
    }catch (\Exception $e){

    }
}

function deduction_cal($d,$id,$basic_salary,$salary_update)
{
    $total_deduct=0;
    foreach ($d as $key=>$deduct){
        $employee=\App\Models\EmployeeProfile::where('id',$id)->select('id','staff_union')->first();
        if ($deduct->deduction_id ==1){
            if (app_settings()->paye_calculation == 2){
                $amount=formular_one($employee->id,1);
//                $salary_update["D1"] = $amount;

//                $salary_update->save();
            }elseif (app_settings()->paye_calculation==3){
                $amount=formular_two($employee->id,2);
//                $salary_update["D1"] = $amount;
//                $salary_update->save();
            }
        }
        elseif (UnionDeduction::where('deduction_id',$deduct->deduction_id)->get()->count() > 0 ){
            if (UnionDeduction::where('deduction_id',$deduct->deduction_id)->where('union_id',$employee->staff_union)->exists()){
                if ($deduct->deduction_type==1){
                    $amount=round($basic_salary/100 * $deduct->value,2);
                }else{
                    $amount=$deduct->value;
                }
            }else{
                $amount=0.00;
            }

        }else{
            if ($deduct->deduction_type==1){
                $amount=round($basic_salary/100 * $deduct->value,2);
            }else{
                $amount=$deduct->value;
            }
            if ($deduct->deduction_id==2 || $deduct->deduction_id==3){
                if (none_pension($id) == 10)
                {
                    $amount=0;
                }
            }
        }
        $salary_update["D$deduct->deduction_id"]=$amount;
        $total_deduct +=round($amount,2);
        $salary_update->save();
    }

}

function checkTerminatingEmployees()
{
//    $threeMonthsFromNow = now()->addMonths(3)->addDays(2)->toDateString();

    $employees = \App\Models\EmployeeProfile::whereDate('contract_termination_date', now()->toDateString())->get();
   if (!is_null($employees)){
       foreach ($employees as $employee){
           $terminationMonth=now()->toDateString();
           if ($employee->contract_termination_date == $terminationMonth){
               $employee->status=2;
               $employee->save();
               $user=\App\Models\User::where('email',$employee->email)->first();
               $user->password=encrypt('contactadmin');
               $user->save();
           }
       }
   }


    // Your action here (e.g., send email, flag, notify)
}

function statutory_deduction($statutory_deductionId)
{
    if (is_null($statutory_deductionId)){
        return app_settings()->statutory_deduction;
    }else{
        return $statutory_deductionId;
    }
}
function employee_union($union,$deduction,$basic_salary)
{
    if (!is_null($deduction)){
        if (UnionDeduction::where('deduction_id',$deduction->deduction_id)->get()->count() > 0 ){
            if (UnionDeduction::where('deduction_id',$deduction->deduction_id)->where('union_id',$union)->exists()){
                if ($deduction->deduction_type==1){
                    $amount=round($basic_salary/100 * $deduction->value,2);
                }else{
                    $amount=$deduction->value;
                }
            }else{
                $amount=0.00;
            }
            return $amount;

        }
    }

}
