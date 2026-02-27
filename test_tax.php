<?php
require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$paye = app(\App\DeductionCalculation::class);
$basic_salary = 871605.50;
$monthly_taxable_allowances = 426125.09;

$activeBracket = \App\Models\TaxBracket::active()->first();
$annual_basic = round($basic_salary * 12, 2);
$annual_gross = round(($basic_salary + $monthly_taxable_allowances) * 12, 2);

$reliefs = $activeBracket->reliefs ?? [];

$cra_fixed = isset($reliefs['consolidated_rent_relief']['fixed']) ? (float) $reliefs['consolidated_rent_relief']['fixed'] : 200000.00;
$cra_pct = isset($reliefs['nhis_contribution']['percentage']) ? (float) $reliefs['nhis_contribution']['percentage'] : 20.0;
$pension_pct = isset($reliefs['pension_contribution']['percentage']) ? (float) $reliefs['pension_contribution']['percentage'] : 8.0;
$nhf_pct = isset($reliefs['nhf_contribution']['percentage']) ? (float) $reliefs['nhf_contribution']['percentage'] : 2.5;

$cra = round($cra_fixed + ($cra_pct / 100) * $annual_gross, 2);
$pension_relief = round(($pension_pct / 100) * $annual_gross, 2);
$nhf_relief = round(($nhf_pct / 100) * $annual_gross, 2);

$total_relief = round($cra + $pension_relief + $nhf_relief, 2);
$taxable_income = max(0, round($annual_gross - $total_relief, 2));
$annual_tax = $activeBracket->calculateTax($taxable_income);

echo "Annual Basic: $annual_basic\n";
echo "Annual Gross: $annual_gross\n";
echo "CRA Fixed: $cra_fixed\n";
echo "CRA Pct: $cra_pct -> $cra\n";
echo "Pension Pct: $pension_pct -> $pension_relief\n";
echo "NHF Pct: $nhf_pct -> $nhf_relief\n";
echo "Total Relief: $total_relief\n";
echo "Taxable Income: $taxable_income\n";
echo "Annual Tax: $annual_tax\n";
echo "Monthly Tax: " . round($annual_tax / 12, 2) . "\n";
