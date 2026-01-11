<?php

namespace App\Livewire\Forms;

use App\Models\ActivityLog;
use App\Models\Deduction;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class AvailableDeduction extends Component
{

    public $search, $perpage='10';
    public $record=true, $create, $edit, $ids,$tin_number;
    use WithPagination;
    use LivewireAlert;
    use WithoutUrlPagination;
    use LivewireAlert;
    public $deduction_code, $deduction_name,$deduction_type, $description,$visibility, $account_name, $account_number, $bank_code, $status;
    protected function rules()
    {

        return [
            'deduction_code' => 'required|numeric|unique:deductions,code,' . $this->ids,
            'deduction_name' => 'required|regex:/^[\pL\s]+$/u|unique:deductions,deduction_name,' . $this->ids,
            'description' => 'required|regex:/^[\pL\s]+$/u',
            'account_name' => 'required',
            'account_number' => 'required|digits:10',
            'bank_code' => 'required',
            'status' => 'required',
            'tin_number' => 'required|numeric|unique:deductions,tin_number,' . $this->ids,
            'deduction_type' => 'required',
        ];
    }

    public function store(){
        $this->validate();
        $addDeduction= new Deduction();
        $addDeduction->code=$this->deduction_code;
        $addDeduction->deduction_name=$this->deduction_name;
        $addDeduction->description=$this->description;
        $addDeduction->account_no=$this->account_number;
        $addDeduction->account_name=$this->account_name;
        $addDeduction->bank_code=$this->bank_code;
        $addDeduction->status=$this->status;
        $addDeduction->deduction_type=$this->deduction_type;
        $addDeduction->tin_number=$this->tin_number;


        $addDeduction->save();
        $this->alert('success','Deduction Added!');
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $log->action="Added $this->deduction_name deduction";
        $log->save();
        $this->reset_fields();
    }
    public function reset_fields(){
        $this->deduction_code='';
        $this->deduction_name='';
        $this->description='';
        $this->account_name='';
        $this->account_number='';
        $this->bank_code='';
        $this->tin_number='';
        $this->deduction_type='';
        $this->status='';

    }
    public function updated($prop){
        $this->validateOnly($prop);
    }
    public function create_deduction(){
        $this->reset_fields();
        $this->record=false;
        $this->create=true;
        $this->edit=false;
    }
    public function edit_deduction($id){
        $this->record=false;
        $this->create=false;
        $this->edit=true;
        $this->ids=$id;
        $deductionObj= Deduction::find($id);
        $this->deduction_code=$deductionObj->Code;
        $this->deduction_name=$deductionObj->deduction_name;
        $this->description=$deductionObj->description;
        $this->account_number=$deductionObj->account_no;
        $this->account_name=$deductionObj->account_name;
        $this->bank_code=$deductionObj->bank_code;
        $this->status=$deductionObj->status;
        $this->deduction_type=$deductionObj->deduction_type;
        $this->tin_number=$deductionObj->tin_number;
        $this->visibility=$deductionObj->visibility;

    }
    public function update($id){
        $this->validate();
        $updateObj= Deduction::find($id);
        $updateObj->Code=$this->deduction_code;
        $updateObj->deduction_name=$this->deduction_name;
        $updateObj->description=$this->description;
        $updateObj->account_no=$this->account_number;
        $updateObj->account_name=$this->account_name;
        $updateObj->bank_code=$this->bank_code;
        $updateObj->status=$this->status;
        $updateObj->tin_number=$this->tin_number;
        $updateObj->deduction_type=$this->deduction_type;
        $updateObj->visibility=$this->visibility;
        $updateObj->save();
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $log->action="updated $this->deduction_name deduction";
        $log->save();
        $this->alert('success','Deduction Updated!');
    }
    public function close(){
        $this->record=true;
        $this->create=false;
        $this->edit=false;
    }
    public function render()
    {
        $deductions= Deduction::where('code','like',"%$this->search%")
            ->orwhere('deduction_name','like',"%$this->search%")
            ->paginate($this->perpage);
        return view('livewire.forms.available-deduction',compact('deductions'))->extends('components.layouts.app');
    }

}
