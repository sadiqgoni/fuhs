# Annual Salary Increment Logic Guide

This document outlines the detailed logic and implementation of the **Annual Salary Increment** module. This module allows administrators to bulk-update employee salary steps based on specific criteria or individual selection, calculate arrears, and manage the increment history.

## 1. Overview
The module acts as a bulk processor that:
1.  **Selects Employees**: Either through filtering (Department, Unit, Tenure, etc.) or manual selection.
2.  **Calculates New Salary**: Moves employees up by a specified number of steps (e.g., Step 2 -> Step 3).
3.  **Updates Financials**: Recalculates Basic Salary, Allowances, Deductions, and Net Pay efficiently.
4.  **Handles Arrears**: Optionally calculates and updates arrears if the increment is backdated.
5.  **Logs History**: Records the transaction in the `annual_salary_increments` table for audit and potential rollback.

---

## 2. Selection Logic (The "UI Logic")

The interface allows two distinct modes of operation, controlled by the `$selection_mode` property in the Livewire component.

### A. Criteria Based Selection (`criteria`)
In this mode, employees are filtered based on shared attributes. This is useful for general annual increments.

**Filters Available:**
-   **Organization**: Department, Unit.
-   **Employment**: Employment Type (Permanent, Contract), Staff Category (Junior, Senior).
-   **Status**: Active, Suspended, etc. (Default is usually Active).
-   **Salary Structure**: Specific structure or Grade Level range.
-   **Tenure (Min. Service Months)**: A calculated filter.
    -   *Logic*: The system compares the `date_of_first_appointment` with the selected `increment_date`.
    -   *Calculation*: `AppointmentDate->diffInMonths(IncrementDate) >= $min_service_months`.
    -   *Use Case*: "Only increment staff who have been employed for at least 6 months."

### B. Specific Employee Selection (`specific`)
In this mode, the admin manually searches and selects specific employees.

**Workflow:**
-   Admin selects "Specific Employees".
-   A search interface (often grouped by Department or Unit) allows checking specific boxes.
-   The IDs are stored in `$specific_employee_ids`.
-   This overrides all other filters.

---

## 3. Increment Processing Logic

Once employees are selected and the form is submitted (calling the `store()` method), the system processes them **sequentially**.

### Core Execution Flow

1.  **Backup**: A system backup (`backup_es()`) is triggered for safety.
2.  **Duplicate Check**:
    -   The system checks `annual_salary_increments` table.
    -   **Condition**: `employee_id` + `month_year` (derived from Increment Date).
    -   If a record exists, the employee is **skipped** to prevent double incrementing for the same period.

3.  **Step Calculation**:
    -   **Current Step**: `$employee->step`.
    -   **Increment Value**: Input from user (e.g., 1 step).
    -   **Max Steps**: Retrieved from `salary_structure_templates` for that Grade Level.
    -   **Formula**:
        ```php
        $new_step = $current_step + $increment_value;
        if ($new_step > $max_steps_defined_in_template) {
            $new_step = $max_steps_defined_in_template; // Cap at maximum
        }
        ```

4.  **Salary Re-computation**:
    -   The system **must** recalculate the entire salary breakdown because a change in Step changes the **Basic Salary**, which in turn affects percentage-based Allowances and Deductions (like Tax/Pension).
    -   **Fetch New Basic**: Get `Step X` value from `SalaryStructureTemplate`.
    -   **Update Allowances**: Loop through `SalaryAllowanceTemplate` -> Recalculate based on new Basic.
    -   **Update Deductions**: Loop through `SalaryDeductionTemplate` -> Recalculate based on new Basic.
    -   **Summation**: Update `total_allowance`, `total_deduction`, `gross_pay`, `net_pay`.

### Arrears Calculation Logic

If the admin inputs a value in **Arrears (Months)**, the system calculates the retroactive payment due.

-   **Pre-requisite**: Captured `$old_gross_pay` (before update) and `$new_gross_pay` (after update).
-   **Formula**:
    ```php
    $difference = $new_gross_pay - $old_gross_pay;
    if ($difference > 0) {
        $total_arrears = $difference * $arrears_months_input;
        // Append to existing arrears
        $salary_update->salary_arears = ($salary_update->salary_arears ?? 0) + $total_arrears;
    }
    ```
-   **Result**: The `SalaryUpdate` table is updated immediately with the new arrears debt.

---

## 4. Data Persistence & Logging

For every processed employee, a record is created in `annual_salary_increments`.

**Saved Data:**
-   `employee_id`
-   `increment_month` / `increment_year`: For reporting.
-   `old_grade_step` / `new_grade_step`: Critical for rollback.
-   `current_salary` (Old Basic) / `new_salary` (New Basic): Critical for audit.
-   `arrears_months`: For record keeping.

---

## 5. Revert / Rollback Logic

The module supports undoing an increment action (`performRevert()`).

**Logic:**
1.  **Identify Records**: Find entries in `annual_salary_increments` matching the date and criteria.
2.  **Restore Step**: Updates `employee_profile` step back to `old_grade_step`.
3.  **Restore Salary**:
    -   Sets `basic_salary` back to `current_salary` (the stored old value).
    -   **Triggers Re-computation**: Runs the exact same allowance/deduction loops as the increment phase, but using the *restored* basic salary.
4.  **Cleanup**: Deletes the log record from `annual_salary_increments`.

---

## 6. Database Schema Reference

### `annual_salary_increments`
| Column | Description |
| :--- | :--- |
| `employee_id` | FK to Employee Profile |
| `month_year` | The date effectively applied (e.g., 2024-01-01) |
| `old_grade_step` | Step before increment |
| `new_grade_step` | Step after increment |
| `current_salary` | Basic Salary BEFORE increment |
| `new_salary` | Basic Salary AFTER increment |
| `arrears_months` | Number of months calculated for arrears |

### `salary_updates`
| Column | Description |
| :--- | :--- |
| `basic_salary` | Updated dynamically |
| `gross_pay` | Updated dynamically |
| `salary_arears` | Accumulates the calculated arrears |
