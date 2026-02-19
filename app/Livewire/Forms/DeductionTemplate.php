<?php

namespace App\Livewire\Forms;

use App\Jobs\DeductionJob;
use App\Models\ActivityLog;
use App\Models\SalaryAllowanceTemplate;
use App\Models\SalaryDeductionTemplate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class DeductionTemplate extends Component
{
    public $salary_structure, $grade_level_from, $grade_level_to, $deduction, $deduction_type, $value;
    public $create, $edit, $record = true, $ids;
    use LivewireAlert, WithPagination, WithoutUrlPagination;
    public $perpage = 10, $filter_allow;
    use WithFileUploads;
    use WithPagination;
    use LivewireAlert;
    public $importFile;
    public $batchId;
    public $importing = false;
    public $importFilePath;
    public $importFinished = false;
    public $iteration;
    public $failures;
    public $amount;
    protected $listeners = ['confirmed'];

    public function store()
    {
        $this->validate([
            'salary_structure' => 'required',
            'deduction' => 'required',
            'grade_level_from' => 'required',
            'grade_level_to' => 'required|gte:grade_level_from',
            'deduction_type' => 'required',
            'amount' => 'required|regex:/^\d+(\.\d{1,4})?$/|gt:0',
        ]);
        if ($this->deduction_type == 1) {
            $this->validate(
                [
                    'amount' => ['regex:/^\d{1,2}(\.\d{1,2})?$|^100(\.00?)?$/']
                ],
                [
                    'regex' => "Invalid Percentage Value"
                ]
            );
        }


        $data = [
            'level_from' => $this->grade_level_from,
            'level_to' => $this->grade_level_to
        ];
        $exists = DB::table('salary_deduction_templates')
            ->where('salary_structure_id', $this->salary_structure)
            ->where('deduction_id', $this->deduction)
            ->where(function ($query) use ($data) {
                $query->whereBetween('grade_level_from', [$data['level_from'], $data['level_to']])
                    ->orWhereBetween('grade_level_to', [$data['level_from'], $data['level_to']])
                    ->orWhere(function ($q) use ($data) {
                        $q->where('grade_level_from', '<=', $data['level_from'])
                            ->where('grade_level_to', '>=', $data['level_to']);
                    });
            })
            ->exists();



        if ($exists) {
            $this->alert('warning', 'Deduction exists with same definition');
        } else {
            $allowance = new SalaryDeductionTemplate();
            $allowance->salary_structure_id = $this->salary_structure;
            $allowance->deduction_id = $this->deduction;
            $allowance->grade_level_from = $this->grade_level_from;
            $allowance->grade_level_to = $this->grade_level_to;
            $allowance->value = $this->amount;
            $allowance->deduction_type = $this->deduction_type;
            $allowance->save();
            $this->alert('success', 'New record have been added');
            $user = Auth::user();
            $log = new ActivityLog();
            $log->user_id = $user->id;
            $name = deduction_name($this->deduction);
            $log->action = "Added $name to deduction template";
            $log->save();
            $this->salary_structure = '';
            $this->deduction = '';
            $this->grade_level_from = '';
            $this->grade_level_to = '';
            $this->amount = '';
            $this->deduction_type = '';
            $this->record = true;
            $this->edit = false;
            $this->create = false;
        }
    }
    public function create_deduction()
    {
        $this->record = false;
        $this->edit = false;
        $this->create = true;
    }

    public function import()
    {
        $this->validate([
            'importFile' => 'required|mimes:xlsx'
        ]);
        $this->importing = true;
        $this->importFilePath = $this->importFile->store('imports');
        $batch = Bus::batch([
            new DeductionJob($this->importFilePath),
        ])->dispatch();
        $this->batchId = $batch->id;
        $this->alert('success', 'successful', [
            'timer' => 9000,
            'timerProgressBar' => true
        ]);
        $this->importFile = null;
        $this->iteration++;
    }
    public function getImportBatchProperty()
    {

        $this->importFile = '';
        $this->importFile = [];
        if (!$this->batchId) {
            return null;
        }
        return Bus::findBatch($this->batchId);
    }
    public function updateImportProgress()
    {

        $this->importFinished = $this->importBatch->finished();
        if ($this->importFinished) {
            Storage::delete($this->importFilePath);
            $this->importing = false;

        }
    }
    public function edit_deduction($id)
    {
        $this->record = false;
        $this->edit = true;
        $this->create = false;
        $this->ids = $id;
        $deduction = SalaryDeductionTemplate::find($id);
        $this->salary_structure = $deduction->salary_structure_id;
        $this->grade_level_from = $deduction->grade_level_from;
        $this->grade_level_to = $deduction->grade_level_to;
        $this->deduction = $deduction->deduction_id;
        $this->deduction_type = $deduction->deduction_type;
        $this->value = $deduction->value;
    }
    public function update($id)
    {
        $this->validate([
            'salary_structure' => 'required',
            'deduction' => 'required',
            'grade_level_from' => 'required',
            'grade_level_to' => 'required',
            'deduction_type' => 'required',
            'value' => 'required|regex:/^\d*(\.\d{2})?$/',
        ]);
        if ($this->deduction_type == 1) {
            $this->validate(
                [
                    'value' => ['regex:/^\d{1,2}(\.\d{1,2})?$|^100(\.00?)?$/']
                ],
                [
                    'regex' => "Invalid percentage Value"
                ]
            );
        }
        $data = [
            'level_from' => $this->grade_level_from,
            'level_to' => $this->grade_level_to
        ];
        $exists = DB::table('salary_deduction_templates')
            ->where('id', '!=', $id)
            ->where('salary_structure_id', $this->salary_structure)
            ->where('deduction_id', $this->deduction)
            ->where(function ($query) use ($data) {
                $query->whereBetween('grade_level_from', [$data['level_from'], $data['level_to']])
                    ->orWhereBetween('grade_level_to', [$data['level_from'], $data['level_to']])
                    ->orWhere(function ($q) use ($data) {
                        $q->where('grade_level_from', '<=', $data['level_from'])
                            ->where('grade_level_to', '>=', $data['level_to']);
                    });
            })
            ->exists();
        if ($exists) {
            $this->alert('warning', 'Deduction exists with same definition');

        } else {
            $allowance = SalaryDeductionTemplate::find($id);
            $allowance->grade_level_from = $this->grade_level_from;
            $allowance->grade_level_to = $this->grade_level_to;
            $allowance->deduction_type = $this->deduction_type;
            $allowance->deduction_id = $this->deduction;
            $allowance->value = $this->value;
            $allowance->save();
            $this->alert('success', 'Deduction have been updated');
            $user = Auth::user();
            $log = new ActivityLog();
            $log->user_id = $user->id;
            $name = deduction_name($this->deduction);
            $log->action = "Updated $name in deduction template";
            $log->save();
            $this->edit = false;
            $this->create = false;
            $this->record = true;
        }
    }
    public function close()
    {
        $this->record = true;
        $this->create = false;
        $this->edit = false;
    }
    public function deleteId($id)
    {
        $this->ids = $id;
        $this->alert('warning', 'Do you want to delete this record?', [
            'showConfirmButton' => true,
            'onConfirmed' => 'confirmed',
            'showCancelButton' => true,
            'timer' => 90000,
            'position' => 'center'
        ]);
    }
    public function confirmed()
    {
        $allowance = SalaryDeductionTemplate::find($this->ids);
        $allowance->delete();
        $this->alert('success', 'Record has been deleted successfully');
    }
    public function render()
    {
        $deductions = SalaryDeductionTemplate::when($this->filter_allow, function ($query) {
            return $query->where('salary_structure_id', $this->filter_allow);
        })->paginate($this->perpage);
        return view('livewire.forms.deduction-template', compact('deductions'))->extends('components.layouts.app');
    }
}
