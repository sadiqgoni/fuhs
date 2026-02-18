<?php

namespace App\Livewire\Pages;

use App\Imports\StepMatrixImport;
use App\Models\ActivityLog;
use App\Models\Allowance;
use App\Models\SalaryStructure;
use App\Models\StepAllowanceTemplate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class StepAllowanceManager extends Component
{
    use LivewireAlert, WithFileUploads, WithPagination;

    // Filters
    public $filter_structure;
    public $filter_allowance;
    public $perpage = 20;

    // View State
    public $record = true;
    public $create = false;
    public $edit = false;

    // Import State
    public $importFile;

    // Edit State
    public $edit_grade_level;
    public $steps_data = []; // Stores step values for editing: [step_number => value]
    public $max_steps = 15; // Default max steps to show

    public function mount()
    {
        // Set defaults if available
        $firstStruct = SalaryStructure::first();
        if ($firstStruct) {
            $this->filter_structure = $firstStruct->id;
        }
        $firstAllow = Allowance::where('status', 1)->first();
        if ($firstAllow) {
            $this->filter_allowance = $firstAllow->id;
        }
    }

    public function render()
    {
        $gradeLevels = [];
        $stepDataValues = collect(); // Default empty collection


        // We only show data if filters are selected, to avoid massive unfocused queries
        if ($this->filter_structure && $this->filter_allowance) {

            // Get distinct grade levels available for this combo
            $query = StepAllowanceTemplate::where('salary_structure_id', $this->filter_structure)
                ->where('allowance_id', $this->filter_allowance)
                ->select('grade_level')
                ->distinct()
                ->orderBy('grade_level');

            $gradeLevels = $query->paginate($this->perpage);

            // OPTIMIZATION: Fetch all step values for these grade levels in one query
            // to avoid N+1 queries in the view loop.
            if ($gradeLevels->count() > 0) {
                $grades = $gradeLevels->pluck('grade_level')->toArray();

                $stepDataValues = StepAllowanceTemplate::where('salary_structure_id', $this->filter_structure)
                    ->where('allowance_id', $this->filter_allowance)
                    ->whereIn('grade_level', $grades)
                    ->get()
                    ->groupBy('grade_level');
            }

            // Determine max step for headers dynamically
            $maxStepInDb = StepAllowanceTemplate::where('salary_structure_id', $this->filter_structure)
                ->where('allowance_id', $this->filter_allowance)
                ->max('step');

            if ($maxStepInDb > 0) {
                $this->max_steps = $maxStepInDb;
            }
        } else {
            // Return empty pagination if filters missing
            $gradeLevels = StepAllowanceTemplate::where('id', -1)->paginate($this->perpage);
        }

        return view('livewire.pages.step-allowance-manager', [
            'gradeLevels' => $gradeLevels,
            'stepDataValues' => $stepDataValues,
            'salaryStructures' => SalaryStructure::all(),
            'allowances' => Allowance::where('status', 1)->get(),
        ])->extends('components.layouts.app');
    }

    // Helper to get value from the pre-fetched collection
    // This is now purely for fallback or could be removed if view logic changes
    public function getStepValue($grade, $step)
    {
        return 0; // Deprecated, use stepDataValues in view
    }

    public function create_mode()
    {
        $this->record = false;
        $this->create = true;
        $this->edit = false;
    }

    public function close()
    {
        $this->record = true;
        $this->create = false;
        $this->edit = false;
        $this->steps_data = [];
    }

    public function edit_grade($grade_level)
    {
        $this->edit_grade_level = $grade_level;
        $this->steps_data = [];

        // Fetch existing values
        $records = StepAllowanceTemplate::where('salary_structure_id', $this->filter_structure)
            ->where('allowance_id', $this->filter_allowance)
            ->where('grade_level', $grade_level)
            ->get();

        foreach ($records as $r) {
            $this->steps_data[$r->step] = $r->value;
        }

        // Ensure we have slots for all steps up to max
        for ($i = 1; $i <= $this->max_steps; $i++) {
            if (!isset($this->steps_data[$i])) {
                $this->steps_data[$i] = 0;
            }
        }

        $this->record = false;
        $this->edit = true;
        $this->create = false;
    }

    public function update_grade()
    {
        $this->validate([
            'edit_grade_level' => 'required|numeric',
            'steps_data.*' => 'numeric|min:0'
        ]);

        foreach ($this->steps_data as $step => $value) {
            // We save even if 0 to maintain the record, or we could delete. 
            // Updating existing or creating new.
            StepAllowanceTemplate::updateOrCreate(
                [
                    'salary_structure_id' => $this->filter_structure,
                    'grade_level' => $this->edit_grade_level,
                    'step' => $step,
                    'allowance_id' => $this->filter_allowance,
                ],
                [
                    'value' => $value
                ]
            );
        }

        // Ensure SalaryAllowanceTemplate exists for this grade to trigger calculation
        $this->ensureSalaryAllowanceTemplate($this->edit_grade_level);

        $this->alert('success', 'Grade Level updated successfully');
        $this->close();
    }

    public function import()
    {
        $this->validate([
            'importFile' => 'required|file|mimes:xlsx,xls,csv,txt',
            'filter_structure' => 'required',
            'filter_allowance' => 'required'
        ]);

        $path = $this->importFile->store('imports');
        $fullPath = Storage::path($path);

        $imported = 0;
        $gradesEncountered = [];
        $rows = [];

        try {
            // Use StepMatrixImport for robust naming/sheet handling if applicable, 
            // or just plain Excel::toArray which handles both CSV and Excel generally.
            // Using StepMatrixImport class is cleaner as it encapsulates the collection logic
            // and we are already using it elsewhere.
            $import = new StepMatrixImport();
            $import->import($fullPath);

            // $import->rows is a Collection of Collections (rows)
            // It might read the first sheet by default.
            $rows = $import->rows;
        } catch (\Exception $e) {
            // Fallback for plain text/csv if Excel reader fails or formatting is weird
            if (($handle = fopen($fullPath, 'r')) !== false) {
                while (($row = fgetcsv($handle)) !== false) {
                    $rows[] = $row;
                }
                fclose($handle);
            } else {
                $this->alert('error', 'Unable to parse file: ' . $e->getMessage());
                return;
            }
        }

        // Iterate over rows found (whether from Excel import or CSV fallback)
        foreach ($rows as $row) {
            // Convert to array if it's a collection
            if ($row instanceof \Illuminate\Support\Collection) {
                $row = $row->toArray();
            }

            if (count($row) < 2)
                continue;

            $first = trim((string) $row[0], " \t\n\r\0\x0B\"'");

            $grade = null;
            if (is_numeric($first)) {
                $grade = (int) $first;
            } else {
                $clean = preg_replace('/[^0-9\.]/', '', $first);
                if ($clean !== '' && is_numeric($clean)) {
                    $grade = (int) $clean;
                }
            }

            // STRICT VALIDATION: Grade Level must be reasonable (e.g. 1-30)
            // This prevents "7535900032233" errors.
            if (!$grade || $grade <= 0 || $grade > 30)
                continue;

            $gradesEncountered[] = $grade;

            // Iterate columns for steps
            // Column 1 is Step 1, Column 2 is Step 2, etc. (0-indexed)
            // Note: count($row) gives total columns. $i starts at 1.
            $colCount = count($row);
            for ($i = 1; $i < $colCount; $i++) {
                // Ensure index exists
                if (!isset($row[$i]))
                    continue;

                $raw = trim((string) $row[$i]);
                if ($raw === '' || $raw === '-' || $raw === '--')
                    continue;

                // Clean up numeric string (remove commas, quotes)
                $numericStr = str_replace([',', ' '], '', trim($raw, "\"' "));

                if (!is_numeric($numericStr))
                    continue;

                $val = (float) $numericStr;

                StepAllowanceTemplate::updateOrCreate(
                    [
                        'salary_structure_id' => $this->filter_structure,
                        'grade_level' => $grade,
                        'step' => $i,
                        'allowance_id' => $this->filter_allowance,
                    ],
                    [
                        'value' => $val
                    ]
                );
                $imported++;
            }
        }

        $uniqueGrades = array_unique($gradesEncountered);
        foreach ($uniqueGrades as $g) {
            $this->ensureSalaryAllowanceTemplate($g);
        }

        $this->alert('success', "Imported $imported records");
        $this->close();
    }

    private function ensureSalaryAllowanceTemplate($grade)
    {
        // Check if an allowance template exists that covers this grade range
        $exists = \App\Models\SalaryAllowanceTemplate::where('salary_structure_id', $this->filter_structure)
            ->where('allowance_id', $this->filter_allowance)
            ->where(function ($q) use ($grade) {
                // Check if grade falls within any existing range
                $q->whereRaw("? BETWEEN grade_level_from AND grade_level_to", [$grade]);
            })
            ->exists();

        if (!$exists) {
            // Create a specific template entry for this grade to ensure it's picked up by loop
            \App\Models\SalaryAllowanceTemplate::create([
                'salary_structure_id' => $this->filter_structure,
                'allowance_id' => $this->filter_allowance,
                'grade_level_from' => $grade,
                'grade_level_to' => $grade,
                'allowance_type' => 2, // Fixed
                'value' => 0 // Zero base value, to be overridden by Steps
            ]);
        }
    }
}
