<?php

// Test script to show old vs new PAYE calculations

// Sample employee data from CSV
$basicSalary = 110525.64; // Monthly basic
$housingAllowance = 8259.92; // Monthly housing
$transportAllowance = 16520.68; // Annual transport
$peculiarAllowance = 20453.14; // Monthly peculiar

echo "=== EMPLOYEE SALARY BREAKDOWN ===\n";
echo "Basic Salary (Monthly): ₦" . number_format($basicSalary, 2) . "\n";
echo "Housing Allowance (Monthly): ₦" . number_format($housingAllowance, 2) . "\n";
echo "Transport Allowance (Annual): ₦" . number_format($transportAllowance, 2) . "\n";
echo "Peculiar Allowance (Monthly): ₦" . number_format($peculiarAllowance, 2) . "\n\n";

$monthlyGross = $basicSalary + $housingAllowance + $peculiarAllowance;
$annualGross = $monthlyGross * 12 + $transportAllowance;

echo "Monthly Gross Pay: ₦" . number_format($monthlyGross, 2) . "\n";
echo "Annual Gross Pay: ₦" . number_format($annualGross, 2) . "\n\n";

echo "=== TAX RELIEFS ===\n";
$consolidatedRelief = 200000;
$pension = ($basicSalary + $housingAllowance + $transportAllowance/12) * 0.08 * 12;
$totalReliefs = $consolidatedRelief + $pension;

echo "Consolidated Rent Relief: ₦" . number_format($consolidatedRelief, 2) . "\n";
echo "Pension Contribution (8%): ₦" . number_format($pension, 2) . "\n";
echo "Total Tax Reliefs: ₦" . number_format($totalReliefs, 2) . "\n\n";

$chargeableIncome = $annualGross - $totalReliefs;
echo "Chargeable Income: ₦" . number_format($chargeableIncome, 2) . "\n\n";

echo "=== OLD vs NEW TAX CALCULATION ===\n";

// OLD SYSTEM (current in your app)
echo "OLD SYSTEM (Current App):\n";
$oldTax = calculateOldTax($chargeableIncome);
echo "Annual Tax: ₦" . number_format($oldTax, 2) . "\n";
echo "Monthly Tax: ₦" . number_format($oldTax/12, 2) . "\n\n";

// NEW SYSTEM (2026 from CSV)
echo "NEW SYSTEM (2026 CSV):\n";
$newTax = calculateNewTax($chargeableIncome);
echo "Annual Tax: ₦" . number_format($newTax, 2) . "\n";
echo "Monthly Tax: ₦" . number_format($newTax/12, 2) . "\n\n";

echo "=== DIFFERENCE ===\n";
$difference = $oldTax - $newTax;
echo "Tax Difference: ₦" . number_format($difference, 2) . "\n";
echo "Monthly Difference: ₦" . number_format($difference/12, 2) . "\n";
echo "Percentage Change: " . number_format(($difference/$oldTax)*100, 1) . "%\n\n";

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