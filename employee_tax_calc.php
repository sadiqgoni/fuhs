<?php

// Specific calculation for Hamidu Mubarak Hamzat (TI220674)

echo "=== EMPLOYEE: Hamidu Mubarak Hamzat (TI220674) ===\n\n";

// From the payslip
$basicSalary = 175441.50; // Monthly basic salary
$grossPay = 205441.50;   // Monthly gross pay
$currentTax = 17427.27;  // Current PAYE tax
$allowances = $grossPay - $basicSalary; // Total allowances

echo "Monthly Basic Salary: ₦" . number_format($basicSalary, 2) . "\n";
echo "Monthly Gross Pay: ₦" . number_format($grossPay, 2) . "\n";
echo "Monthly Allowances: ₦" . number_format($allowances, 2) . "\n";
echo "Current PAYE Tax (Monthly): ₦" . number_format($currentTax, 2) . "\n\n";

// Convert to annual figures (what the tax calculation uses)
$annualBasic = $basicSalary * 12;
$annualGross = $grossPay * 12;

echo "Annual Basic Salary: ₦" . number_format($annualBasic, 2) . "\n";
echo "Annual Gross Pay: ₦" . number_format($annualGross, 2) . "\n\n";

// Calculate tax reliefs (simplified version)
$consolidatedRelief = 200000; // Fixed consolidated rent relief
$pension = ($annualBasic * 0.08); // 8% of basic salary
$nhf = ($annualBasic * 0.025); // 2.5% of basic salary
$nhis = ($annualBasic * 0.005); // 0.5% of basic salary

$totalReliefs = $consolidatedRelief + $pension + $nhf + $nhis;
$chargeableIncome = $annualGross - $totalReliefs;

echo "=== TAX RELIEFS CALCULATION ===\n";
echo "Consolidated Rent Relief: ₦" . number_format($consolidatedRelief, 2) . "\n";
echo "Pension (8% of basic): ₦" . number_format($pension, 2) . "\n";
echo "NHF (2.5% of basic): ₦" . number_format($nhf, 2) . "\n";
echo "NHIS (0.5% of basic): ₦" . number_format($nhis, 2) . "\n";
echo "Total Tax Reliefs: ₦" . number_format($totalReliefs, 2) . "\n\n";

echo "Chargeable Income (Taxable): ₦" . number_format($chargeableIncome, 2) . "\n\n";

echo "=== TAX CALCULATION COMPARISON ===\n";

// OLD SYSTEM (currently in your app)
echo "OLD SYSTEM (Current App):\n";
$oldAnnualTax = calculateOldTax($chargeableIncome);
$oldMonthlyTax = $oldAnnualTax / 12;
echo "Annual Tax: ₦" . number_format($oldAnnualTax, 2) . "\n";
echo "Monthly Tax: ₦" . number_format($oldMonthlyTax, 2) . "\n";
echo "Current Payslip Shows: ₦" . number_format($currentTax, 2) . "\n\n";

// NEW SYSTEM (2026)
echo "NEW SYSTEM (2026 CSV):\n";
$newAnnualTax = calculateNewTax($chargeableIncome);
$newMonthlyTax = $newAnnualTax / 12;
echo "Annual Tax: ₦" . number_format($newAnnualTax, 2) . "\n";
echo "Monthly Tax: ₦" . number_format($newMonthlyTax, 2) . "\n\n";

// DIFFERENCE
echo "=== THE DIFFERENCE ===\n";
$annualDifference = $oldAnnualTax - $newAnnualTax;
$monthlyDifference = $oldMonthlyTax - $newMonthlyTax;
echo "Annual Tax Savings: ₦" . number_format($annualDifference, 2) . "\n";
echo "Monthly Tax Savings: ₦" . number_format($monthlyDifference, 2) . "\n";
echo "Percentage Reduction: " . number_format(($annualDifference / $oldAnnualTax) * 100, 1) . "%\n\n";

echo "=== WHAT THIS MEANS FOR THIS EMPLOYEE ===\n";
echo "Currently takes home: ₦" . number_format($grossPay - $currentTax, 2) . " per month\n";
echo "Should take home: ₦" . number_format($grossPay - $newMonthlyTax, 2) . " per month\n";
echo "Monthly increase: ₦" . number_format($monthlyDifference, 2) . "\n";
echo "Annual increase: ₦" . number_format($annualDifference, 2) . "\n\n";

function calculateOldTax($taxableIncome) {
    $tax = 0;
    $balance = $taxableIncome;

    // OLD BRACKETS (current in system)
    if ($balance > 300000) {
        $tax += (7/100) * 300000;
        $balance -= 300000;
    } else {
        $tax += (7/100) * $balance;
        return $tax;
    }

    if ($balance > 300000) {
        $tax += (11/100) * 300000;
        $balance -= 300000;
    } else {
        $tax += (11/100) * $balance;
        return $tax;
    }

    if ($balance > 500000) {
        $tax += (15/100) * 500000;
        $balance -= 500000;
    } else {
        $tax += (15/100) * $balance;
        return $tax;
    }

    if ($balance > 500000) {
        $tax += (19/100) * 500000;
        $balance -= 500000;
    } else {
        $tax += (19/100) * $balance;
        return $tax;
    }

    if ($balance > 1600000) {
        $tax += (21/100) * 1600000;
        $balance -= 1600000;
    } else {
        $tax += (21/100) * $balance;
        return $tax;
    }

    $tax += (24/100) * $balance;
    return $tax;
}

function calculateNewTax($taxableIncome) {
    $tax = 0;
    $balance = $taxableIncome;

    // NEW 2026 BRACKETS (from CSV)
    if ($balance > 800000) {
        // First ₦800,000 = 0%
        $balance -= 800000;
    } else {
        return 0; // No tax on first ₦800,000
    }

    if ($balance > 2200000) {
        $tax += (15/100) * 2200000; // Next ₦2,200,000 = 15%
        $balance -= 2200000;
    } else {
        $tax += (15/100) * $balance;
        return $tax;
    }

    if ($balance > 9000000) {
        $tax += (18/100) * 9000000; // Next ₦9,000,000 = 18%
        $balance -= 9000000;
    } else {
        $tax += (18/100) * $balance;
        return $tax;
    }

    if ($balance > 13000000) {
        $tax += (21/100) * 13000000; // Next ₦13,000,000 = 21%
        $balance -= 13000000;
    } else {
        $tax += (21/100) * $balance;
        return $tax;
    }

    if ($balance > 25000000) {
        $tax += (23/100) * 25000000; // Next ₦25,000,000 = 23%
        $balance -= 25000000;
    } else {
        $tax += (23/100) * $balance;
        return $tax;
    }

    $tax += (25/100) * $balance; // Above ₦50,000,000 = 25%
    return $tax;
}