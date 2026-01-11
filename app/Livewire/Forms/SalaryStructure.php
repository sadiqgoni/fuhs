<?php

namespace App\Livewire\Forms;

use App\Models\ActivityLog;
use App\Models\SalaryStructureTemplate;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class SalaryStructure extends Component
{
    public $name,$number_of_grade,$status;
    use LivewireAlert;
    public $record=true,$create,$edit,$ids;
    protected function rules()
    {
       return [
            'name' => 'required|regex:/^[\pL\s]+$/u|unique:salary_structures,name,' . $this->ids,
            'number_of_grade' => 'required|numeric|min:1',
            'status' => 'required'
        ];
    }
    public function updated($pro){$this->validateOnly($pro);}
    public function close(){$this->record=true;$this->edit=false;$this->create=false;}
    public function create_ss(){$this->name='';$this->status='';$this->number_of_grade='';$this->record=false;$this->create=true;$this->edit=false;}
    public function store(){
        $this->validate();
        $salary=new \App\Models\SalaryStructure();
        $salary->name=$this->name;
        $salary->no_of_grade=$this->number_of_grade;
        $salary->status=$this->status;
        $salary->save();
        $this->alert('success','Salary structure have been added');
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $log->action="Added $this->name Salary Structure";
        $log->save();
        $this->name='';
        $this->number_of_grade='';
        $this->status='';
        $this->record=true;
        $this->create=false;
        $this->edit=false;
    }
    public function edit_ss($id)
    {
        $this->record=false;
        $this->create=false;
        $this->edit=true;
        $this->ids=$id;
        $salary=\App\Models\SalaryStructure::find($id);
        $this->name=$salary->name;
        $this->number_of_grade=$salary->no_of_grade;
        $this->status=$salary->status;
    }
    public function update($id)
    {
        $this->validate();
        $salary=\App\Models\SalaryStructure::find($id);
        $salary->name=$this->name;
        $salary->no_of_grade=$this->number_of_grade;
        $salary->status=$this->status;
        $salary->save();
        $this->alert('success','Salary structure have been updated');
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $log->action="Updated $this->name salary structure ";
        $log->save();
        $this->record=true;
        $this->edit=false;
        $this->create=false;
    }

    public function render()
    {
        $salaries=\App\Models\SalaryStructure::all();
        return view('livewire.forms.salary-structure',compact('salaries'))
            ->extends('components.layouts.app');
    }
}

