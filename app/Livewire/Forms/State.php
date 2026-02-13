<?php

namespace App\Livewire\Forms;

use App\Models\ActivityLog;
use App\Models\State as StateModel;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class State extends Component
{
    public $search, $perpage = 25;
    public $name, $status, $ids;
    use WithPagination, WithoutUrlPagination, LivewireAlert;

    public $edit = false, $create = false, $record = true;

    protected function rules()
    {
        return [
            'name' => 'required|regex:/^[\pL\s]+$/u|unique:states,name,' . $this->ids,
        ];
    }

    public function create_state()
    {
        $this->create = true;
        $this->edit = false;
        $this->record = false;
    }

    public function close()
    {
        $this->record = true;
        $this->create = false;
        $this->edit = false;
        $this->reset(['name', 'status', 'ids']);
    }

    public function updated($pro)
    {
        $this->validateOnly($pro);
    }

    public function store()
    {
        $this->validate();
        $state = new StateModel();
        $state->name = $this->name;
        $state->country = 1; // Default Nigeria
        $state->status = 1; // Default Active
        $state->save();

        $this->alert('success', 'State have been added');
        $user = Auth::user();
        $log = new ActivityLog();
        $log->user_id = $user->id;
        $log->action = "Added ($this->name) state";
        $log->save();

        $this->close();
    }

    public function edit_record($id)
    {
        $this->create = false;
        $this->edit = true;
        $this->record = false;
        $state = StateModel::find($id);
        $this->ids = $id;
        $this->name = $state->name;
        $this->status = $state->status;
    }

    public function update($id)
    {
        $this->validate();
        $state = StateModel::find($id);
        $state->name = $this->name;
        $state->status = $this->status;
        $state->save();

        $this->alert('success', 'State have been updated');
        $user = Auth::user();
        $log = new ActivityLog();
        $log->user_id = $user->id;
        $log->action = "Updated ($this->name) state";
        $log->save();

        $this->close();
    }

    public function status_change($id)
    {
        $state = StateModel::find($id);
        if ($state->status == 1) {
            $state->status = 0;
            $message = 'State discontinued successfully';
        } else {
            $state->status = 1;
            $message = 'State activated successfully';
        }
        $state->save();
        $this->alert('success', $message);
    }

    public function render()
    {
        $states = StateModel::where('name', 'like', "%$this->search%")
            ->paginate($this->perpage);

        return view('livewire.forms.state', compact('states'))->extends('components.layouts.app');
    }
}
