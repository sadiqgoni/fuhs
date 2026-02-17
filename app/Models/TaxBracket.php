<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxBracket extends Model
{
    use HasFactory;

    protected $fillable = [
        'version_name',
        'effective_date',
        'is_active',
        'tax_brackets',
        'reliefs',
        'description'
    ];

    protected $casts = [
        'effective_date' => 'date',
        'is_active' => 'boolean',
        'tax_brackets' => 'array',
        'reliefs' => 'array'
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeEffective($query, $date = null)
    {
        $date = $date ?? now();
        return $query->where('effective_date', '<=', $date)
                    ->orderBy('effective_date', 'desc');
    }

    // Methods
    /**
     * Calculate tax using progressive tax brackets (like climbing stairs).
     * Each bracket applies only to the portion of income that falls within its range.
     * 
     * Example: Taxable Income = ₦1,454,815.85
     * - First ₦300k → 7% = ₦21,000 (remaining: ₦1,154,815.85)
     * - Next ₦300k → 11% = ₦33,000 (remaining: ₦854,815.85)
     * - Next ₦500k → 15% = ₦75,000 (remaining: ₦354,815.85)
     * - Next ₦500k → 19% = ₦67,415.01 (only ₦354,815.85 remains, so tax that amount)
     * Total = ₦196,415.01
     */
    public function calculateTax($annualTaxableIncome)
    {
        if (!$this->tax_brackets || $annualTaxableIncome <= 0) {
            return 0;
        }

        $tax = 0;
        $remainingIncome = $annualTaxableIncome;

        // Sort brackets by min to ensure correct order
        $sortedBrackets = collect($this->tax_brackets)->sortBy('min')->values()->all();

        foreach ($sortedBrackets as $bracket) {
            if ($remainingIncome <= 0) break;

            $min = (float)($bracket['min'] ?? 0);
            $max = isset($bracket['max']) ? (float)$bracket['max'] : null;
            $rate = (float)($bracket['rate'] ?? 0);

            // Calculate how much of remaining income falls in this bracket
            if ($max === null) {
                // Last bracket (no upper limit) - tax all remaining income
                $taxableInBracket = $remainingIncome;
            } else {
                // Calculate bracket width (how much income this bracket covers)
                $bracketWidth = $max - $min;
                // Take the minimum of: remaining income OR bracket width
                $taxableInBracket = min($remainingIncome, $bracketWidth);
            }

            if ($taxableInBracket > 0) {
                $tax += $taxableInBracket * ($rate / 100);
                $remainingIncome -= $taxableInBracket;
            }
        }

        return round($tax, 2);
    }

    public function calculateMonthlyTax($annualTaxableIncome)
    {
        return round($this->calculateTax($annualTaxableIncome) / 12, 2);
    }

    public function getTotalReliefs($basicSalary = 100000, $housingAllowance = 0, $transportAllowance = 0)
    {
        $defaultReliefs = [
            'consolidated_rent_relief' => ['fixed' => 200000, 'description' => 'Fixed consolidated rent relief'],
            'pension_contribution' => ['percentage' => 8.0, 'base' => 'basic_housing_transport', 'description' => '8% of basic + housing + transport'],
            'nhf_contribution' => ['percentage' => 2.5, 'base' => 'basic', 'description' => '2.5% of basic salary'],
            'nhis_contribution' => ['percentage' => 0.5, 'base' => 'basic', 'description' => '0.5% of basic salary'],
        ];

        // Merge with saved reliefs, using defaults if not specified
        $reliefs = array_merge($defaultReliefs, $this->reliefs ?? []);

        $total = 0;
        $calculatedReliefs = [];

        foreach ($reliefs as $key => $relief) {
            if (isset($relief['fixed'])) {
                $amount = $relief['fixed'];
                $calculatedReliefs[$key] = array_merge($relief, ['calculated_amount' => $amount]);
                $total += $amount;
            } elseif (isset($relief['percentage'])) {
                $percentage = $relief['percentage'];
                $base = $relief['base'] ?? 'basic';

                // Calculate base amount
                switch ($base) {
                    case 'basic_housing_transport':
                        $baseAmount = $basicSalary + $housingAllowance + $transportAllowance;
                        break;
                    case 'basic':
                    default:
                        $baseAmount = $basicSalary;
                        break;
                }

                $amount = ($percentage / 100) * $baseAmount;
                $calculatedReliefs[$key] = array_merge($relief, ['calculated_amount' => round($amount, 2)]);
                $total += round($amount, 2);
            }
        }

        return [
            'reliefs' => $calculatedReliefs,
            'total' => round($total, 2),
            'breakdown' => [
                'basic_salary' => $basicSalary,
                'housing_allowance' => $housingAllowance,
                'transport_allowance' => $transportAllowance,
            ]
        ];
    }

    // Boot method to ensure only one active bracket
    protected static function booted()
    {
        static::saving(function ($bracket) {
            if ($bracket->is_active) {
                // Deactivate all other brackets
                static::where('id', '!=', $bracket->id)->update(['is_active' => false]);
            }
        });
    }

    // Helper methods
    public function getBracketSummary()
    {
        if (!$this->tax_brackets) return [];

        $summary = [];
        foreach ($this->tax_brackets as $bracket) {
            $min = number_format($bracket['min'] ?? 0);
            $max = $bracket['max'] ? number_format($bracket['max']) : '∞';
            $rate = $bracket['rate'] ?? 0;

            $summary[] = "₦{$min} - ₦{$max}: {$rate}%";
        }

        return $summary;
    }
}
