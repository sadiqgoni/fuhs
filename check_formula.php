<?php

require_once 'vendor/autoload.php';

// Test both formulas against the real employee payslip data

echo "=== CHECKING WHICH FORMULA MATCHES THE PAYSLIP ===\n\n";

// Real employee data from payslip
$basicSalary = 175441.50; // Monthly basic
$grossPay = 205441.50;    // Monthly gross
$actualTax = 17427.27;    // What payslip actually shows

echo "Employee: Hamidu Mubarak Hamzat (TI220674)\n";
echo "Monthly Basic: ₦" . number_format($basicSalary, 2) . "\n";
echo "Monthly Gross: ₦" . number_format($grossPay, 2) . "\n";
echo "Actual PAYE Tax: ₦" . number_format($actualTax, 2) . "\n\n";

// Test both calculation methods
$payeCalc = new App\DeductionCalculation();

echo "=== TESTING FORMULA 1 (paye_calculation1) ===\n";
try {
    $formula1Result = $payeCalc->paye_calculation1($basicSalary, 1); // statutory_deduction = 1
    echo "Formula 1 Result: ₦" . number_format($formula1Result, 2) . "\n";
    $diff1 = abs($formula1Result - $actualTax);
    echo "Difference from payslip: ₦" . number_format($diff1, 2) . "\n";
    echo "Match accuracy: " . number_format((1 - $diff1/$actualTax) * 100, 2) . "%\n\n";
} catch (Exception $e) {
    echo "Formula 1 Error: " . $e->getMessage() . "\n\n";
}

echo "=== TESTING FORMULA 2 (paye_calculation2) ===\n";
try {
    $formula2Result = $payeCalc->paye_calculation2($basicSalary, 1); // statutory_deduction = 1
    echo "Formula 2 Result: ₦" . number_format($formula2Result, 2) . "\n";
    $diff2 = abs($formula2Result - $actualTax);
    echo "Difference from payslip: ₦" . number_format($diff2, 2) . "\n";
    echo "Match accuracy: " . number_format((1 - $diff2/$actualTax) * 100, 2) . "%\n\n";
} catch (Exception $e) {
    echo "Formula 2 Error: " . $e->getMessage() . "\n\n";
}

// Check which one is closer
if (isset($diff1) && isset($diff2)) {
    echo "=== CONCLUSION ===\n";
    if ($diff1 < $diff2) {
        echo "🏆 FORMULA 1 matches better (closer to payslip amount)\n";
        echo "The system is likely using 'Formular 1' (value = 2)\n";
    } elseif ($diff2 < $diff1) {
        echo "🏆 FORMULA 2 matches better (closer to payslip amount)\n";
        echo "The system is likely using 'Formular 2' (value = 3)\n";
    } else {
        echo "Both formulas are equally close\n";
    }
}

// Check default app setting
echo "\n=== CURRENT APP SETTING ===\n";
try {
    $appSettings = app_settings();
    $currentSetting = $appSettings->paye_calculation ?? 'Not set';
    echo "Current paye_calculation setting: " . $currentSetting . "\n";

    if ($currentSetting == 2) {
        echo "Setting indicates: Using FORMULA 1 (paye_calculation1)\n";
    } elseif ($currentSetting == 3) {
        echo "Setting indicates: Using FORMULA 2 (paye_calculation2)\n";
    } else {
        echo "Setting indicates: Custom or unknown formula\n";
    }
} catch (Exception $e) {
    echo "Could not check app settings: " . $e->getMessage() . "\n";
}