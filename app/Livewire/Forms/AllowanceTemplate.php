<?php

namespace App\Livewire\Forms;

use App\Jobs\AllowanceJob;
use App\Models\ActivityLog;
use App\Models\SalaryAllowanceTemplate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class AllowanceTemplate extends Component
{
    public $salary_structure, $grade_level_from, $grade_level_to, $allowance, $allowance_type, $value, $taxable;
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
    public $salary_structure_name, $allowance_name, $amount;
    protected $listeners = ['confirmed'];

    public function store()
    {
        $this->validate([
            'salary_structure_name' => 'required',
            'allowance_name' => 'required',
            'grade_level_from' => 'required',
            'grade_level_to' => 'required',
            'allowance_type' => 'required',
            'amount' => 'required|min:1',
        ]);
        if ($this->allowance_type == 1) {
            $this->validate(
                [
                    'amount' => ['regex:/^([1-9][0-9]?|100)$/']
                ],
                [
                    'regex' => "The percentage of basic should only accept 1-100"
                ]
            );
        }

        $data = [
            'level_from' => (int) $this->grade_level_from,
            'level_to' => (int) $this->grade_level_to,
        ];
        $exists = DB::table('salary_allowance_templates')
            //            ->where('id', '!=', $id)
            ->where('salary_structure_id', $this->salary_structure_name)
            ->where('allowance_id', $this->allowance_name)
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
            $this->alert('warning', 'Allowance exists with same definition');

        } else {

            $allowance = new SalaryAllowanceTemplate();
            $allowance->salary_structure_id = $this->salary_structure_name;
            $allowance->allowance_id = $this->allowance_name;
            $allowance->grade_level_from = $this->grade_level_from;
            $allowance->grade_level_to = $this->grade_level_to;
            $allowance->value = $this->amount;
            $allowance->allowance_type = $this->allowance_type;
            $allowance->save();
            $this->alert('success', 'New record have been added');
            $user = Auth::user();
            $log = new ActivityLog();
            $log->user_id = $user->id;
            $name = allowance_name($this->allowance_name);
            $log->action = "Added new Allowance $name record";
            $log->save();
            $this->salary_structure_name = '';
            $this->allowance_name = '';
            $this->grade_level_from = '';
            $this->grade_level_to = '';
            $this->amount = '';
            $this->allowance_type = '';
            $this->record = true;
            $this->edit = false;
            $this->create = false;
        }
    }

    public function create_allowance()
    {
        $this->record = false;
        $this->edit = false;
        $this->create = true;
    }
    public function edit_allowance($id)
    {
        $this->record = false;
        $this->edit = true;
        $this->create = false;
        $this->ids = $id;
        $allowance = SalaryAllowanceTemplate::find($id);
        $this->salary_structure = $allowance->salary_structure_id;
        $this->grade_level_from = $allowance->grade_level_from;
        $this->grade_level_to = $allowance->grade_level_to;
        $this->allowance = $allowance->allowance_id;
        $this->allowance_type = $allowance->allowance_type;
        $this->value = $allowance->value;
    }
    public function update($id)
    {
        $this->validate([
            'salary_structure' => 'required',
            'grade_level_from' => 'required',
            'grade_level_to' => 'required',
            'allowance_type' => 'required',
            'value' => 'required|min:1|',
            'allowance' => 'required'
        ]);
        if ($this->allowance_type == 1) {
            $this->validate(
                [
                    'value' => ['regex:/^\d{1,2}(\.\d{1,2})?$|^100(\.00?)?$/']
                ],

                [
                    'regex' => "Value for percentage of basic field must be between 1-100"
                ]
            );
        }

        $allowance = SalaryAllowanceTemplate::find($id);
        $allowance->grade_level_from = $this->grade_level_from;
        $allowance->grade_level_to = $this->grade_level_to;
        $allowance->allowance_type = $this->allowance_type;
        $allowance->value = $this->value;
        $allowance->allowance_id = $this->allowance;
        $allowance->save();

        $this->alert('success', 'Allowance have been updated');
        $user = Auth::user();
        $log = new ActivityLog();
        $log->user_id = $user->id;
        $name = allowance_name($allowance->allowance_id);
        $log->action = "Updated new Allowance $name record";
        $log->save();
        $this->edit = false;
        $this->create = false;
        $this->record = true;
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
        $allowance = SalaryAllowanceTemplate::find($this->ids);
        $allowance->delete();
        $this->alert('success', 'Record has been deleted successfully');
    }
    public function render()
    {
        $allowances = SalaryAllowanceTemplate::when($this->filter_allow, function ($query) {
            return $query->where('salary_structure_id', $this->filter_allow);
        })->paginate($this->perpage);
        return view('livewire.forms.allowance-template', compact('allowances'))->extends('components.layouts.app');
    }
}
