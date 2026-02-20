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

    public function continues_deduction($employee, $statutory_deductionId, $salary_update)
    {
        $salary_structure = $employee['salary_structure'];
        $grade_level = $employee['grade_level'];
        $step = $employee['step'];
        //        try {
        $salary = SalaryStructureTemplate::where('salary_structure_id', $salary_structure)
            ->where('grade_level', $grade_level)
            ->first();
        $annual_salary = $salary["Step" . $step];
        $basic_salary = round($annual_salary / 12, 2);

        $deduction_templates = SalaryDeductionTemplate::where('salary_structure_id', $salary_structure)
            ->whereRaw('? between grade_level_from and grade_level_to', [$grade_level])
            ->get();
        $deductions = Deduction::where('status', 1)->get();
        if ($salary) {
            $total_deduct = 0;
            foreach ($deductions as $key => $deduction) {
                $deduction_template = SalaryDeductionTemplate::where('salary_structure_id', $salary_structure)
                    ->whereRaw('? between grade_level_from and grade_level_to', [$grade_level])
                    ->where('deduction_id', $deduction->id)
                    ->first();
                if ($deduction->id == 1) {
                    $amount = $this->paye_calculation1($basic_salary, $statutory_deductionId);
                } elseif (UnionDeduction::where('deduction_id', $deduction->id)->exists()) {

                    $amount = employee_union($employee['staff_union'], $deduction_template, $basic_salary);
                } elseif ($deduction_template != null) {

                    if ($deduction->deduction_type == 1) {
                        $amount = round($basic_salary / 100 * $deduction_template->value, 2);
                    } else {
                        $amount = $deduction_template->value;
                    }
                    if ($deduction_template->deduction_id == 2 || $deduction_template->deduction_id == 3) {
                        if (none_pension($employee['id']) == 10) {
                            $amount = 0;
                        }
                    }
                }
                $total_deduct += round($amount);
                $salary_update["D$deduction->id"] = $amount;
                $salary_update->save();
            }
            $total = 0;
            foreach (Deduction::where('status', 1)->get() as $deduction) {
                $total += round($salary_update["D$deduction->id"], 2);
            }
            $salary_update->total_deduction = $total;
            $salary_update->save();

        }
        //        }catch (\Exception $e){
//                dd($e);
//        }


    }

    public function paye_calculation1($basic_salary, $statutory_deductionId)
    {

        $allowances = \App\Models\Allowance::leftJoin('salary_allowance_templates', 'salary_allowance_templates.allowance_id', 'allowances.id')
            ->select('salary_allowance_templates.*', 'allowances.taxable', 'allowances.status')
            ->where('taxable', 1)
            ->where('status', 1)
            ->get();
        $total = 0;

        foreach ($allowances as $allowance) {
            try {
                if ($allowance->allowance_type == 1) {
                    $amount = round($basic_salary / 100 * $allowance->value, 2);
                } else {
                    $amount = $allowance->value;
                }
                $total += round($amount, 2);
            } catch (\Exception $e) {
                continue;
            }
        }
        $total_allow = $total;
        $annual_basic = round($basic_salary * 12, 2);
        $annual_allowance = round($total_allow * 12, 2);
        $annual_gross = round($annual_basic + $annual_allowance, 2);

        $agp = round((20 / 100) * $annual_gross, 2);
        $consolidated_relief = 200000.00 + $agp;


        //get Statutory Deduction
        $statutory_deduction = statutory_deduction($statutory_deductionId);
        if ($statutory_deduction == 1) {
            $pension = round((8 / 100) * $annual_basic, 2);
            $nhf = round((2.5 / 100) * $annual_basic, 2);
            $nhis = round((0.05 / 100) * $annual_basic, 2);
            $national_pension = 0;
            $gratuity = 0;
        } else {
            $pension = round((8 / 100) * $annual_gross, 2);
            $nhf = round((2.5 / 100) * $annual_gross, 2);
            $nhis = round((0.05 / 100) * $annual_gross, 2);
            $national_pension = 0;
            $gratuity = 0;
        }

        $total_relief = round($consolidated_relief + $pension + $nhf + $nhis + $national_pension + $gratuity, 2);
        $taxable_income = round($annual_gross - $total_relief, 2);
        //Now compute tax
        return $this->compute_tax($taxable_income);
    }
    public function paye_calculation2($basic_salary, $statutory_deductionId)
    {
        $allowances = \App\Models\Allowance::join('salary_allowance_templates', 'salary_allowance_templates.allowance_id', 'allowances.id')
            ->select('salary_allowance_templates.*', 'allowances.taxable', 'allowances.status')
            ->where('taxable', 1)
            ->where('status', 1)
            ->get();
        $total = 0;
        foreach ($allowances as $allowance) {
            try {
                if ($allowance->deduction_type == 1) {
                    $amount = round($basic_salary / 100 * $allowance->value, 2);
                } else {
                    $amount = $allowance->value;
                }
                $total += round($amount);
            } catch (\Exception $e) {
                continue;
            }
        }
        $total_allow = $total;
        $annual_basic = round($basic_salary * 12);

        $monthly_gross = $basic_salary + $total_allow;

        //statutory deductions
        $statutory_deduction = statutory_deduction($statutory_deductionId);
        if ($statutory_deduction == 1) {
            $pension = round((8 / 100) * $basic_salary, 2);
            $nhf = round((2.5 / 100) * $basic_salary, 2);
            $nhis = round((0.5 / 100) * $basic_salary, 2);
        } else {
            $pension = round((8 / 100) * $monthly_gross, 2);
            $nhf = round((2.5 / 100) * $monthly_gross, 2);
            $nhis = round((0.5 / 100) * $monthly_gross, 2);
        }


        $net_pay = round($monthly_gross - $nhf - $pension - $nhis, 2);
        $bi = round($net_pay / 2, 2);
        $annual_gross = round($bi * 12, 2);
        $relief = round($annual_gross * 0.2 + (16666.6666 * 12), 2);
        $taxable_income = round($annual_gross - $relief, 2);

        //Now compute tax

        return $this->compute_tax($taxable_income);

    }

    public function compute_tax($basic_salary, $monthly_taxable_allowances = 0.0)
    {
        // Try to use dynamic tax bracket first
        try {
            $activeBracket = \App\Models\TaxBracket::active()->first();

            if ($activeBracket && $activeBracket->tax_brackets) {

                // ── Annual figures ───────────────────────────────────────────
                // Annual basic salary
                $annual_basic = round($basic_salary * 12, 2);

                // Annual gross = (monthly basic + monthly taxable allowances) × 12
                // Caller must pass in the actual taxable allowances already computed
                // for this specific employee (excluding A1 / Responsibility).
                $annual_gross = round(($basic_salary + $monthly_taxable_allowances) * 12, 2);

                // ── Pull relief settings from DB ─────────────────────────────
                // The client stores:
                //   consolidated_rent_relief → { fixed: 200000 }   ← ₦200k part of CRA
                //   nhis_contribution        → { percentage: 20 }  ← % part of CRA
                //   pension_contribution     → { percentage: 8  }
                //   nhf_contribution         → { percentage: 2.5}
                $reliefs = $activeBracket->reliefs ?? [];

                // CRA fixed amount (default ₦200,000)
                $cra_fixed = isset($reliefs['consolidated_rent_relief']['fixed'])
                    ? (float) $reliefs['consolidated_rent_relief']['fixed']
                    : 200000.00;

                // CRA percentage (stored under nhis_contribution key; default 20%)
                $cra_pct = isset($reliefs['nhis_contribution']['percentage'])
                    ? (float) $reliefs['nhis_contribution']['percentage']
                    : 20.0;

                // Pension percentage (default 8%)
                $pension_pct = isset($reliefs['pension_contribution']['percentage'])
                    ? (float) $reliefs['pension_contribution']['percentage']
                    : 8.0;

                // NHF percentage (default 2.5%)
                $nhf_pct = isset($reliefs['nhf_contribution']['percentage'])
                    ? (float) $reliefs['nhf_contribution']['percentage']
                    : 2.5;

                // ── Apply CRA formula ────────────────────────────────────────
                // CRA = ₦[cra_fixed] + [cra_pct]% of Annual Gross
                $cra = round($cra_fixed + ($cra_pct / 100) * $annual_gross, 2);
                $pension_relief = round(($pension_pct / 100) * $annual_basic, 2);
                $nhf_relief = round(($nhf_pct / 100) * $annual_basic, 2);

                $total_relief = round($cra + $pension_relief + $nhf_relief, 2);
                $taxable_income = max(0, round($annual_gross - $total_relief, 2));

                // ── Apply progressive tax brackets ────────────────────────────
                $annual_tax = $activeBracket->calculateTax($taxable_income);
                return round($annual_tax / 12, 2);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Dynamic tax calculation failed: ' . $e->getMessage());
        }

        // Fallback: hardcoded CRA = ₦200,000 + 20% Gross, Pension 8%, NHF 2.5%
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
            $tax = number_format($tax + (7 / 100) * 300000, 2, '.', '');
            $balance = number_format($balance - 300000, 2, '.', '');
        } else {
            $tax = number_format($tax + (7 / 100) * $balance, 2, '.', '');
            return round($tax / 12, 2);
        }

        if ($balance > 300000) {
            $tax = number_format($tax + (11 / 100) * 300000, 2, '.', '');
            $balance = number_format($balance - 300000, 2, '.', '');
        } else {
            $tax = number_format($tax + (11 / 100) * $balance, 2, '.', '');
            return round($tax / 12, 2);
        }

        if ($balance > 500000) {
            $tax = number_format($tax + (15 / 100) * 500000, 2, '.', '');
            $balance = number_format($balance - 500000, 2, '.', '');
        } else {
            $tax = number_format($tax + (15 / 100) * $balance, 2, '.', '');
            return round($tax / 12, 2);
        }

        if ($balance > 500000) {
            $tax = number_format($tax + (19 / 100) * 500000, 2, '.', '');
            $balance = number_format($balance - 500000, 2, '.', '');
        } else {
            $tax = number_format($tax + (19 / 100) * $balance, 2, '.', '');
            return round($tax / 12, 2);
        }

        if ($balance > 1600000) {
            $tax = number_format($tax + (21 / 100) * 1600000, 2, '.', '');
            $balance = number_format($balance - 1600000, 2, '.', '');
        } else {
            $tax = number_format($tax + (21 / 100) * $balance, 2, '.', '');
            return round($tax / 12, 2);
        }

        $tax = $tax + (24 / 100) * $balance;
        return round($tax / 12, 2);
    }
    public function total_deduction($total)
    {
        return $total;
    }
}
