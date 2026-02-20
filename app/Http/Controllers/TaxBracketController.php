<?php

namespace App\Http\Controllers;

use App\Models\TaxBracket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TaxBracketController extends Controller
{
    /**
     * Display a listing of tax brackets.
     */
    public function index()
    {
        $taxBrackets = TaxBracket::orderBy('created_at', 'desc')->paginate(15);
        $activeBracket = TaxBracket::active()->first();

        return view('admin.tax-brackets.index', compact('taxBrackets', 'activeBracket'));
    }

    /**
     * Show the form for creating a new tax bracket.
     */
    public function create()
    {
        return view('admin.tax-brackets.create');
    }

    /**
     * Store a newly created tax bracket.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'version_name' => 'required|string|max:255',
            'effective_date' => 'required|date|after_or_equal:today',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
            'tax_brackets' => 'required|array|min:1',
            'tax_brackets.*.min' => 'required|numeric|min:0',
            'tax_brackets.*.max' => 'nullable|numeric|min:0|gt:tax_brackets.*.min',
            'tax_brackets.*.rate' => 'required|numeric|min:0|max:100',
            'tax_brackets.*.description' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            // Create the bracket first without setting it as active
            $taxBracket = TaxBracket::create([
                'version_name' => $request->version_name,
                'effective_date' => $request->effective_date,
                'is_active' => false, // Don't set active yet
                'tax_brackets' => $request->tax_brackets,
                'reliefs' => $request->input('reliefs', []),
                'description' => $request->description,
            ]);

            // If requested to be active, deactivate all others and activate this one
            if ($request->is_active) {
                TaxBracket::where('id', '!=', $taxBracket->id)->update(['is_active' => false]);
                $taxBracket->update(['is_active' => true]);
            }

            DB::commit();

            return redirect()->route('tax-brackets.index')
                ->with('success', 'Tax bracket created successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Failed to create tax bracket: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified tax bracket.
     */
    public function show(TaxBracket $taxBracket)
    {
        return view('admin.tax-brackets.show', compact('taxBracket'));
    }

    /**
     * Show the form for editing the tax bracket.
     */
    public function edit(TaxBracket $taxBracket)
    {
        return view('admin.tax-brackets.edit', compact('taxBracket'));
    }

    /**
     * Update the specified tax bracket.
     */
    public function update(Request $request, TaxBracket $taxBracket)
    {
        $validator = Validator::make($request->all(), [
            'version_name' => 'required|string|max:255',
            'effective_date' => 'required|date',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
            'tax_brackets' => 'required|array|min:1',
            'tax_brackets.*.min' => 'required|numeric|min:0',
            'tax_brackets.*.max' => 'nullable|numeric|min:0|gt:tax_brackets.*.min',
            'tax_brackets.*.rate' => 'required|numeric|min:0|max:100',
            'tax_brackets.*.description' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            // If setting as active, deactivate all others
            if ($request->is_active && !$taxBracket->is_active) {
                TaxBracket::where('id', '!=', $taxBracket->id)->update(['is_active' => false]);
            }

            $taxBracket->update([
                'version_name' => $request->version_name,
                'effective_date' => $request->effective_date,
                'is_active' => $request->is_active ?? false,
                'tax_brackets' => $request->tax_brackets,
                'reliefs' => $request->input('reliefs', []),
                'description' => $request->description,
            ]);

            DB::commit();

            return redirect()->route('tax-brackets.index')
                ->with('success', 'Tax bracket updated successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Failed to update tax bracket: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Activate a tax bracket.
     */
    public function activate(TaxBracket $taxBracket)
    {
        DB::beginTransaction();
        try {
            // Deactivate all brackets
            TaxBracket::where('is_active', true)->update(['is_active' => false]);

            // Activate the selected bracket
            $taxBracket->update(['is_active' => true]);

            DB::commit();

            return redirect()->route('tax-brackets.index')
                ->with('success', 'Tax bracket activated successfully! All employee tax calculations will now use this bracket.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Failed to activate tax bracket: ' . $e->getMessage()]);
        }
    }

    /**
     * Test tax calculation with sample data.
     */
    public function test(TaxBracket $taxBracket, Request $request)
    {
        $annualIncome = $request->get('annual_income', 2105298); // Default to the employee we checked

        $tax = $taxBracket->calculateTax($annualIncome);
        $monthlyTax = $taxBracket->calculateMonthlyTax($annualIncome);

        return response()->json([
            'bracket' => $taxBracket->version_name,
            'annual_income' => number_format($annualIncome, 2),
            'annual_tax' => number_format($tax, 2),
            'monthly_tax' => number_format($monthlyTax, 2),
        ]);
    }

    /**
     * Remove the specified tax bracket.
     */
    public function destroy(TaxBracket $taxBracket)
    {
        if ($taxBracket->is_active) {
            return back()->withErrors(['error' => 'Cannot delete an active tax bracket. Please activate another bracket first.']);
        }

        $taxBracket->delete();

        return redirect()->route('tax-brackets.index')
            ->with('success', 'Tax bracket deleted successfully!');
    }
}
