# Implementation Guide: Parsing & Report Dynamics Refactoring

**Objective:**
Apply fixes for "Salary Update Center" navigation errors ("Attempt to read property on null"), refactor all payroll reports to be dynamic (removing hardcoded A1-A14 columns), and fix data type issues in Allowance Template creation.

---

## 1. Fix Salary Update Center Navigation (The "Next/Next" Error)
**Context:** Use of `next` and `previous` buttons causes crashes if ID gaps exist or logic relies on simple arithmetic increments.

### File: `app/Livewire/Forms/SalaryUpdateCenter.php`
- **Action:** Ensure `$next_id` and `$previous_id` are calculated using database queries, not `+1` or `-1`.
- **Logic:**
  ```php
  // In your mount or specific navigation method
  $this->next_id = EmployeeProfile::where('id', '>', $this->current_id)->min('id');
  $this->previous_id = EmployeeProfile::where('id', '<', $this->current_id)->max('id');
  ```
- **Action:** Add `null` checks in the `render` or `view` method safely handling cases where an employee is not found.

### File: `resources/views/livewire/forms/salary-update-center.blade.php`
- **Action:** Update buttons to use the computed `$next_id` / `$previous_id`.
- **Constraint:** Only show "Next" if `$next_id` is not null.

---

## 2. Refactor Reports to be Dynamic (Open/Closed Principle)
**Context:** Reports currently have hardcoded headers (A1, A2... A14) and cells. This fails when new allowances are added (e.g., A15).

### Target Files to Refactor:
1. `resources/views/reports/payroll_report.blade.php` (PDF)
2. `resources/views/reports/payroll_dompdf.blade.php` (DOMPDF)
3. `resources/views/livewire/reports/includes/payroll.blade.php` (Livewire Table)
4. `resources/views/reports/pay_slip.blade.php` (Standard Payslip)
5. `resources/views/reports/individual/payslip.blade.php` (Individual PDF)
6. `resources/views/livewire/reports/includes/pay_slip.blade.php` (Livewire Payslip)
7. `resources/views/exports/salary_history_export.blade.php` (Excel Export)

### Implementation Logic (Standard Pattern):
**Step A: Fetch Models**
At the top of the blade file (or passed from controller), fetch active allowances/deductions:
```php
@php
    $allowances = \App\Models\Allowance::where('status', 1)->get();
    $deductions = \App\Models\Deduction::where('status', 1)->get();
@endphp
```

**Step B: Replace Hardcoded Headers**
Remove: `<th>A1</th>...<th>A14</th>`
Replace with:
```blade
@foreach($allowances as $allowance)
    <th>{{ $allowance->allowance_name }}</th>
@endforeach
```

**Step C: Replace Hardcoded Body Cells**
In the main data loop, remove `<td>{{ $report->A1 }}</td>`.
Replace with:
```blade
@foreach($allowances as $allowance)
    <td>{{ number_format($report->{'A'.$allowance->id}, 2) }}</td>
@endforeach
```
*(Apply the same logic for Deductions 'D').*

---

## 3. Allowance Template Logic Fix (Record Exists Error)
**Context:** Creating a range like "Grade 6 to 12" fails if "Grade 1 to 5" exists because string comparison treats "5" > "12" as true.

### File: `app/Livewire/Forms/AllowanceTemplate.php`
- **Action:** Cast inputs to **integers** before validation/querying.
- **Code Change:**
  ```php
  $data = [
      'level_from' => (int) $this->grade_level_from, // Cast to int
      'level_to' => (int) $this->grade_level_to,     // Cast to int
  ];
  ```

### File: `resources/views/livewire/forms/allowance-template.blade.php`
- **Action:** Ensure dropdowns don't show duplicates.
- **Query Update:** Use `->distinct()->orderBy('grade_level')` when fetching grade levels for the select options.

---
**Summary for Execution:**
1. Fix navigation referencing (null safety + DB-based cursors).
2. Eradicate all hardcoded "A1...A14" columns in Blade files; use `foreach($all_allowances)` loops.
3. Enforce strict integer typing on Grade Level inputs in the Allowance Template form.
