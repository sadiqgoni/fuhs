# Salary Arrears Implementation Guide

This document documents the complete implementation of the **Salary Arrears** functionality across the **Annual Salary Increment**, **Employee Promotion**, and **Employee Profile** modules. It also details the removal of the "Force / Overwrite" functionality from the Annual Salary Increment feature.

---

## 1. Database Migrations

### 1.1. Add `arrears_months` to `staff_promotions` table
Create a new migration file (e.g., `2026_02_11_184324_add_arrears_fields_to_staff_promotions_table.php`) to add the `arrears_months` column.

```php
public function up(): void
{
    Schema::table('staff_promotions', function (Blueprint $table) {
        $table->integer('arrears_months')->default(0)->after('step');
    });
}

public function down(): void
{
    Schema::table('staff_promotions', function (Blueprint $table) {
        $table->dropColumn('arrears_months');
    });
}
```

### 1.2. Add `arrears_months` to `annual_salary_increments` table
Create a new migration file (e.g., `2026_02_11_184302_add_arrears_fields_to_annual_salary_increments_table.php`) to add the `arrears_months` column.

```php
public function up(): void
{
    Schema::table('annual_salary_increments', function (Blueprint $table) {
        $table->integer('arrears_months')->default(0)->after('status');
    });
}

public function down(): void
{
    Schema::table('annual_salary_increments', function (Blueprint $table) {
        $table->dropColumn('arrears_months');
    });
}
```

---

## 2. Models Updates

### 2.1. `app/Models/StaffPromotion.php`
Add `arrears_months` to the `$fillable` array.

```php
protected $fillable = [
    // ... existing fields
    'arrears_months'
];
```

### 2.2. `app/Models/AnnualSalaryIncrement.php`
Add `arrears_months` to the `$fillable` array.

```php
protected $fillable = [
    // ... existing fields
    'arrears_months'
];
```

### 2.3. `app/Models/SalaryUpdate.php`
Ensure the correct column name is used. In this project, the column in the database is named `salary_arears` (with a typo in "arears"). Ensure the model uses `salary_arears` in `$fillable` and logic.

```php
protected $fillable = [
    // ...
    'salary_arears', 
    // ...
];
```

---

## 3. Livewire Components Updates

### 3.1. `app/Livewire/Forms/EmployeePromotion.php`

**Properties:**
- Add `public $arrears_months;`

**Rules:**
- Add `'arrears_months' => 'nullable|numeric|min:0',`

**Methods:**
- **`store()`**: Save `arrears_months` to the `StaffPromotion` model.
- **`confirm()`**: Implement the arrears calculation logic.

```php
// In confirm() logic:
$old_gross = $salary_update->gross_pay; // Capture OLD gross pay

// ... perform salary updates ...

// Calculate Arrears
if ($promotion->arrears_months > 0) {
    $increment = $gross_pay - $old_gross;
    if ($increment > 0) {
        $arrears_amount = round($increment * $promotion->arrears_months, 2);
        // Add to existing arrears
        $salary_update->salary_arears = ($salary_update->salary_arears ?? 0) + $arrears_amount;
    }
}
$salary_update->save();
```

### 3.2. `app/Livewire/Forms/AnnualSalaryIncrement.php`

**Changes:**
1.  **Added Arrears Logic**: Validates and calculates arrears.
2.  **Removed Overwrite Logic**: The `$overwrite` property and associated logic have been removed. The system now strictly skips employees who have already received an increment for the current month.

**Properties:**
- Add `public $arrears_months;`
- **REMOVE** `public $overwrite;`

**Rules:**
- Add `'arrears_months' => 'nullable|numeric|min:0',`

**Methods:**
- **`store()` / `confirm()` logic**:

```php
// Check for existing increment
if ($existingIncrement) {
    // Overwrite logic REMOVED. Simply skip.
    $skipped++;
    continue;
}

// ... inside the processing loop ...

$old_gross_pay = $salary_update->gross_pay; // Capture OLD gross pay

// ... calculate new salary ...

// Calculate Arrears
if ($this->arrears_months > 0) {
    $increment_diff = $gross_pay - $old_gross_pay;
    if ($increment_diff > 0) {
        $arrears_val = round($increment_diff * $this->arrears_months, 2);
        $salary_update->salary_arears = ($salary_update->salary_arears ?? 0) + $arrears_val;
    }
}
$salary_update->save();
```

### 3.3. `app/Livewire/Forms/EmployeeProfile.php`

This component was updated to allow arrears calculation when manually editing an employee's Grade Level or Step.

**Properties:**
- Add `public $arrears_months;` to the class properties list.

**Rules:**
- Add `'arrears_months' => 'nullable|numeric|min:0',` to the validation rules (specifically for Step 3).

**Methods:**
- **`reset_field()`**: Reset `$this->arrears_months = '';`.
- **`update($id)`**:

```php
public function update($id)
{
    // ... validation ...
    
    $profileObj = \App\Models\EmployeeProfile::find($id);
    
    // ... update profile object properties ...

    // Check if Salary Structure, Grade, or Step is changing
    if (!\App\Models\EmployeeProfile::where('id', $profileObj->id)
        ->where('salary_structure', $this->salary_structure)
        ->where('grade_level', $this->grade_level)
        ->where('step', $this->step)
        ->exists()) {

        // 1. Capture Old Gross Pay
        $old_gross_pay = 0;
        $old_salary_record = \App\Models\SalaryUpdate::where('employee_id', $id)->first();
        if ($old_salary_record) {
            $old_gross_pay = $old_salary_record->gross_pay;
        }

        // 2. Perform Salary Update (calculates new gross pay)
        $this->salary_update();

        // 3. Calculate Arrears
        if ($this->arrears_months > 0) {
             $new_salary_record = \App\Models\SalaryUpdate::where('employee_id', $id)->first();
             if ($new_salary_record) {
                 $new_gross_pay = $new_salary_record->gross_pay;
                 $difference = $new_gross_pay - $old_gross_pay;
                 
                 if ($difference > 0) {
                     $arrears_amount = round($difference * $this->arrears_months, 2);
                     $new_salary_record->salary_arears = ($new_salary_record->salary_arears ?? 0) + $arrears_amount;
                     $new_salary_record->save();
                 }
             }
        }
    }
    
    $profileObj->save();
    // ... logging and alerts ...
}
```

---

## 4. Blade Views Updates

### 4.1. `resources/views/livewire/forms/employee-promotion.blade.php`
- Added "Arrears (Months)" input field to Create and Edit forms.
- Added "Arrears Months" column to the promotions list table.

### 4.2. `resources/views/livewire/forms/annual-salary-increment.blade.php`
- Added "Arrears (Months)" input field.
- **REMOVED** the "Force / Overwrite existing increments" checkbox and label.

### 4.3. `resources/views/livewire/forms/employee/edit.blade.php`
- Added "Arrears Months" input field to the "Salary & Pension Data" section (Step 3), adjacent to the Step dropdown.

```html
<div class="col-12 col-lg-6">
    @error('arrears_months')
    <strong class="text-danger d-block form-text">{{$message}}</strong>
    @enderror
    <div class="input-group form-group">
        <div class="input-group-prepend"><span class="input-group-text">Arrears Months</span></div>
        <input class="form-control @error('arrears_months') is-invalid @enderror" wire:model.blur="arrears_months" type="number" min="0">
        <div class="input-group-append"></div>
    </div>
</div>
```
