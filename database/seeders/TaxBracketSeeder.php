<?php

namespace Database\Seeders;

use App\Models\TaxBracket;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaxBracketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing brackets (optional - comment out if you want to keep existing)
        // TaxBracket::query()->delete();

        // Create 2026 PAYE Tax Bracket based on the CSV data
        TaxBracket::create([
            'version_name' => 'PAYE 2026 Structure',
            'effective_date' => '2026-01-01',
            'is_active' => true, // Make this active by default
            'description' => 'New PAYE tax structure effective January 2026. Based on the provided CSV data.',
            'tax_brackets' => [
                [
                    'min' => 0,
                    'max' => 800000,
                    'rate' => 0.00,
                    'description' => 'First ₦800,000 (Tax-free)'
                ],
                [
                    'min' => 800000,
                    'max' => 3000000,
                    'rate' => 15.00,
                    'description' => 'Next ₦2,200,000 (15%)'
                ],
                [
                    'min' => 3000000,
                    'max' => 12000000,
                    'rate' => 18.00,
                    'description' => 'Next ₦9,000,000 (18%)'
                ],
                [
                    'min' => 12000000,
                    'max' => 25000000,
                    'rate' => 21.00,
                    'description' => 'Next ₦13,000,000 (21%)'
                ],
                [
                    'min' => 25000000,
                    'max' => 50000000,
                    'rate' => 23.00,
                    'description' => 'Next ₦25,000,000 (23%)'
                ],
                [
                    'min' => 50000000,
                    'max' => null, // No upper limit
                    'rate' => 25.00,
                    'description' => 'Above ₦50,000,000 (25%)'
                ]
            ],
            'reliefs' => [
                'consolidated_rent_relief' => [
                    'fixed' => 200000,
                    'description' => 'Fixed consolidated rent relief allowance'
                ],
                'pension_contribution' => [
                    'percentage' => 8.0,
                    'base' => 'basic_housing_transport',
                    'description' => '8% of basic + housing + transport'
                ],
                'nhf_contribution' => [
                    'percentage' => 2.5,
                    'base' => 'basic',
                    'description' => '2.5% of basic salary'
                ],
                'nhis_contribution' => [
                    'percentage' => 0.5,
                    'base' => 'basic',
                    'description' => '0.5% of basic salary'
                ]
            ]
        ]);

        // Optional: Create a legacy bracket for comparison
        TaxBracket::create([
            'version_name' => 'Legacy PAYE Structure (Pre-2026)',
            'effective_date' => '2020-01-01',
            'is_active' => false, // Keep inactive
            'description' => 'Old tax structure for comparison purposes only.',
            'tax_brackets' => [
                [
                    'min' => 0,
                    'max' => 300000,
                    'rate' => 7.00,
                    'description' => 'First ₦300,000 (7%)'
                ],
                [
                    'min' => 300000,
                    'max' => 600000,
                    'rate' => 11.00,
                    'description' => 'Next ₦300,000 (11%)'
                ],
                [
                    'min' => 600000,
                    'max' => 1100000,
                    'rate' => 15.00,
                    'description' => 'Next ₦500,000 (15%)'
                ],
                [
                    'min' => 1100000,
                    'max' => 1600000,
                    'rate' => 19.00,
                    'description' => 'Next ₦500,000 (19%)'
                ],
                [
                    'min' => 1600000,
                    'max' => 3200000,
                    'rate' => 21.00,
                    'description' => 'Next ₦1,600,000 (21%)'
                ],
                [
                    'min' => 3200000,
                    'max' => null,
                    'rate' => 24.00,
                    'description' => 'Above ₦3,200,000 (24%)'
                ]
            ],
            'reliefs' => [
                'consolidated_relief' => [
                    'percentage' => 20.0,
                    'description' => '20% of gross income or ₦200,000 (whichever lower)'
                ]
            ]
        ]);

        $this->command->info('Tax brackets seeded successfully!');
        $this->command->info('2026 PAYE structure is now active for all calculations.');
    }
}
