<?php

namespace App;

use App\Models\AppSetting;
use App\Models\Deduction;
use App\Models\EmployeeProfile;
use App\Models\SalaryDeductionTemplate;
use App\Models\SalaryStructureTemplate;
use App\Models\SalaryUpdate;
use App\Models\UnionDeduction;

class DeductionCalculation
{

    public function continues_deduction($employee,$statutory_deductionId,$salary_update)
    {
        $salary_structure=$employee['salary_structure'];
        $grade_level=$employee['grade_level'];
        $step=$employee['step'];
//        try {
            $salary=SalaryStructureTemplate::where('salary_structure_id',$salary_structure)
                ->where('grade_level',$grade_level)
                ->first();
            $annual_salary=$salary["Step".$step];
            $basic_salary=round($annual_salary/12,2);

            $deduction_templates=SalaryDeductionTemplate::where('salary_structure_id',$salary_structure)
                ->whereRaw('? between grade_level_from and grade_level_to', [$grade_level])
                ->get();
            $deductions=Deduction::where('status',1)->get();
            if ($salary){
                $total_deduct=0;
                foreach ($deductions as $key=>$deduction){
                    $deduction_template=SalaryDeductionTemplate::where('salary_structure_id',$salary_structure)
                        ->whereRaw('? between grade_level_from and grade_level_to', [$grade_level])
                        ->where('deduction_id',$deduction->id)
                        ->first();
                    if ($deduction->id == 1){
                        $amount= $this->paye_calculation1($basic_salary,$statutory_deductionId);
                    }
                    elseif(UnionDeduction::where('deduction_id',$deduction->id)->exists()){

                        $amount=employee_union($employee['staff_union'],$deduction_template,$basic_salary);
                    }elseif($deduction_template !=null){

                        if ($deduction->deduction_type==1){
                            $amount=round($basic_salary/100 * $deduction_template->value,2);
                        }else{
                            $amount=$deduction_template->value;
                        }
                        if ($deduction_template->deduction_id==2 || $deduction_template->deduction_id==3){
                            if (none_pension($employee['id']) == 10)
                            {
                                $amount=0;
                            }
                        }
                    }
                    $total_deduct +=round($amount);
                    $salary_update["D$deduction->id"] = $amount;
                    $salary_update->save();
                }
                $total=0;
                foreach (Deduction::where('status',1)->get() as $deduction){
                    $total +=round($salary_update["D$deduction->id"],2);
                }
                $salary_update->total_deduction=$total;
                $salary_update->save();

            }
//        }catch (\Exception $e){
//                dd($e);
//        }


    }

    public function paye_calculation1($basic_salary,$statutory_deductionId)
    {

        $allowances=\App\Models\Allowance::leftJoin('salary_allowance_templates','salary_allowance_templates.allowance_id','allowances.id')
            ->select('salary_allowance_templates.*','allowances.taxable','allowances.status')
            ->where('taxable',1)
            ->where('status',1)
            ->get();
        $total=0;

        foreach ($allowances as $allowance){
            try {
                if ($allowance->allowance_type==1){
                    $amount=round($basic_salary/100 * $allowance->value,2);
                }else{
                    $amount=$allowance->value;
                }
                $total += round($amount,2);
            }catch (\Exception  $e){
                continue;
            }
        }
        $total_allow=$total;
        $annual_basic=round($basic_salary *12,2);
        $annual_allowance=round($total_allow *12,2);
        $annual_gross=round($annual_basic + $annual_allowance,2);

        $agp=round((20/100) * $annual_gross,2);
        $consolidated_relief=200000.00 + $agp;


        //get Statutory Deduction
        $statutory_deduction=statutory_deduction($statutory_deductionId);
        if ($statutory_deduction==1){
            $pension=round( (8/100) * $annual_basic,2);
            $nhf=round( (2.5/100) * $annual_basic, 2);
            $nhis=round( (0.05/100) * $annual_basic, 2);
            $national_pension= 0;
            $gratuity= 0;
        }else{
            $pension=round( (8/100) * $annual_gross,2);
            $nhf=round( (2.5/100) * $annual_gross,2);
            $nhis=round( (0.05/100) * $annual_gross,2);
            $national_pension= 0;
            $gratuity= 0;
        }

        $total_relief=round($consolidated_relief + $pension + $nhf + $nhis + $national_pension + $gratuity,2);
        $taxable_income=round($annual_gross - $total_relief,2);
        //Now compute tax
       return $this->compute_tax($taxable_income);
    }
    public function paye_calculation2($basic_salary,$statutory_deductionId)
    {
        $allowances=\App\Models\Allowance::join('salary_allowance_templates','salary_allowance_templates.allowance_id','allowances.id')
            ->select('salary_allowance_templates.*','allowances.taxable','allowances.status')
            ->where('taxable',1)
            ->where('status',1)
            ->get();
        $total=0;
        foreach ($allowances as $allowance){
            try {
                if ($allowance->deduction_type==1){
                    $amount=round($basic_salary/100 * $allowance->value,2);
                }else{
                    $amount=$allowance->value;
                }
                $total += round($amount);
            }catch (\Exception  $e){
                continue;
            }
        }
        $total_allow=$total;
        $annual_basic=round($basic_salary * 12);

        $monthly_gross=$basic_salary + $total_allow;

        //statutory deductions
        $statutory_deduction=statutory_deduction($statutory_deductionId);
        if ($statutory_deduction == 1){
            $pension=round( (8/100) * $basic_salary,2);
            $nhf=round( (2.5/100) * $basic_salary,2);
            $nhis=round( (0.5/100) * $basic_salary,2);
        }else{
            $pension=round( (8/100) * $monthly_gross,2);
            $nhf=round( (2.5/100) * $monthly_gross,2);
            $nhis=round( (0.5/100) * $monthly_gross,2);
        }


        $net_pay=round($monthly_gross - $nhf - $pension - $nhis,2);
        $bi=round($net_pay/2,2);
        $annual_gross=round( $bi * 12,2);
        $relief=round( $annual_gross * 0.2 + (16666.6666 * 12),2);
        $taxable_income=round($annual_gross- $relief,2);

        //Now compute tax

      return $this->compute_tax($taxable_income);

    }

    public function compute_tax($basic_salary)
    {
        // Try to use dynamic tax bracket first
        try {
            $activeBracket = \App\Models\TaxBracket::active()->first();

            if ($activeBracket && $activeBracket->tax_brackets) {
                // Replicate the full calculation logic from paye_calculation1 but use dynamic brackets

                // Get taxable allowances (same as old system)
                $allowances = \App\Models\Allowance::leftJoin('salary_allowance_templates', 'salary_allowance_templates.allowance_id', 'allowances.id')
                    ->select('salary_allowance_templates.*', 'allowances.taxable', 'allowances.status')
                    ->where('taxable', 1)
                    ->where('status', 1)
                    ->get();

                $total_allow = 0;
                foreach ($allowances as $allowance) {
                    try {
                        if ($allowance->allowance_type == 1) {
                            $amount = round($basic_salary / 100 * $allowance->value, 2);
                        } else {
                            $amount = $allowance->value;
                        }
                        $total_allow += round($amount, 2);
                    } catch (\Exception $e) {
                        continue;
                    }
                }

                // Calculate annual figures
                $annual_basic = round($basic_salary * 12, 2);
                $annual_allowance = round($total_allow * 12, 2);
                $annual_gross = round($annual_basic + $annual_allowance, 2);

                // Calculate reliefs using dynamic bracket reliefs
                $total_relief = 0;
                if ($activeBracket->reliefs) {
                    foreach ($activeBracket->reliefs as $key => $relief) {
                        if (isset($relief['fixed'])) {
                            $total_relief += $relief['fixed'];
                        } elseif (isset($relief['percentage'])) {
                            $base = $relief['base'] ?? 'basic';
                            if ($base == 'basic_housing_transport') {
                                // For pension: basic + housing + transport
                                // We don't have housing/transport here, so approximate with basic + allowances
                                $base_amount = $annual_basic + $annual_allowance;
                            } else {
                                $base_amount = $annual_basic;
                            }
                            $amount = ($relief['percentage'] / 100) * $base_amount;
                            $total_relief += round($amount, 2);
                        }
                    }
                } else {
                    // Fallback to old system reliefs
                    // CRA = ₦200,000 + 20% of Gross (as requested)
                    $agp = round((20 / 100) * $annual_gross, 2);
                    $consolidated_relief = 200000.00 + $agp;
                    $pension = round((8 / 100) * $annual_basic, 2);
                    $nhf = round((2.5 / 100) * $annual_basic, 2);
                    $nhis = round((0.5 / 100) * $annual_basic, 2);
                    $total_relief = round($consolidated_relief + $pension + $nhf + $nhis, 2);
                }

                // Calculate taxable income
                $taxable_income = round($annual_gross - $total_relief, 2);
                $taxable_income = max(0, $taxable_income); // Ensure not negative

                // Apply dynamic tax brackets
                $annual_tax = $activeBracket->calculateTax($taxable_income);
                return round($annual_tax / 12, 2);
            }
        } catch (\Exception $e) {
            // Log error but continue with fallback
            \Illuminate\Support\Facades\Log::error('Dynamic tax calculation failed: ' . $e->getMessage());
        }

        // Fallback to old hardcoded method if no active bracket or error
        return $this->paye_calculation1($basic_salary, 1);
    }

    /**
     * Legacy tax calculation method (old hardcoded brackets)
     * Used as fallback when no dynamic bracket is available
     */
    public function compute_tax_legacy($taxable_income)
    {
        $tax_inc = $taxable_income;
        $balance = $tax_inc;
        $tax = 0;

        // OLD BRACKETS (pre-2026)
        if ($balance > 300000) {
            $tax = number_format($tax + (7/100) * 300000, 2, '.', '');
            $balance = number_format($balance - 300000, 2, '.', '');
        } else {
            $tax = number_format($tax + (7/100) * $balance, 2, '.', '');
            return round($tax / 12, 2);
        }

        if ($balance > 300000) {
            $tax = number_format($tax + (11/100) * 300000, 2, '.', '');
            $balance = number_format($balance - 300000, 2, '.', '');
        } else {
            $tax = number_format($tax + (11/100) * $balance, 2, '.', '');
            return round($tax / 12, 2);
        }

        if ($balance > 500000) {
            $tax = number_format($tax + (15/100) * 500000, 2, '.', '');
            $balance = number_format($balance - 500000, 2, '.', '');
        } else {
            $tax = number_format($tax + (15/100) * $balance, 2, '.', '');
            return round($tax / 12, 2);
        }

        if ($balance > 500000) {
            $tax = number_format($tax + (19/100) * 500000, 2, '.', '');
            $balance = number_format($balance - 500000, 2, '.', '');
        } else {
            $tax = number_format($tax + (19/100) * $balance, 2, '.', '');
            return round($tax / 12, 2);
        }

        if ($balance > 1600000) {
            $tax = number_format($tax + (21/100) * 1600000, 2, '.', '');
            $balance = number_format($balance - 1600000, 2, '.', '');
        } else {
            $tax = number_format($tax + (21/100) * $balance, 2, '.', '');
            return round($tax / 12, 2);
        }

        $tax = $tax + (24/100) * $balance;
        return round($tax / 12, 2);
    }
    public function total_deduction($total)
    {
            return $total;
    }
}
