<?php

namespace App\Livewire\Forms;

use App\Models\ActivityLog;
use App\Models\Allowance;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class AvailableAllowances extends Component
{
    public $search, $perpage=10;
    public $record=true,$create,$edit,$ids;
    use LivewireAlert,WithPagination;
    public $allowance_code,$allowance_name,$description,$status,$taxable;
    protected function rules()
    {

        return [
            'allowance_code' => 'required|numeric|min:1|unique:allowances,code,'.$this->ids,
            'allowance_name' => 'required|regex:/^[\pL\s]+$/u|unique:allowances,allowance_name,'. $this->ids,
            'description' => 'required|regex:/^[\pL\s]+$/u',
            'status' => 'required',
            'taxable'=>'required'
        ];
    }
    public function create_allowance(){
        $this->reset_fields();
        $this->record=false;
        $this->create=true;
        $this->edit=false;
    }

    public function close(){
        $this->record=true;
        $this->create=false;
        $this->edit=false;
    }
    public function edit_allowance($id){
        $this->record=false;
        $this->create=false;
        $this->edit=true;
        $this->ids=$id;
        $allowanceObj=Allowance::find($id);
        $this->allowance_code=$allowanceObj->code;
        $this->allowance_name=$allowanceObj->allowance_name;
        $this->description=$allowanceObj->description;
        $this->status=$allowanceObj->status;
        $this->taxable=$allowanceObj->taxable;

    }
    public function update($id)
    {
        $this->validate();
        $allowanceObj=Allowance::find($id);
        $allowanceObj->code=$this->allowance_code;
        $allowanceObj->allowance_name=$this->allowance_name;
        $allowanceObj->description=$this->description;
        $allowanceObj->status=$this->status;
        $allowanceObj->taxable=$this->taxable;
        $allowanceObj->save();
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $log->action="updated $this->allowance_name allowance";
        $log->save();
        $this->edit=false;
        $this->record=true;
        $this->create=false;
        $this->alert('success','Allowance updated!');
    }


    public function store(){

        $this->validate();
        $addAllowanceObj= new Allowance();
        $addAllowanceObj->code=$this->allowance_code;
        $addAllowanceObj->allowance_name=$this->allowance_name;
        $addAllowanceObj->description=$this->description;
        $addAllowanceObj->status=$this->status;
        $addAllowanceObj->taxable=$this->taxable;
        $addAllowanceObj->save();
        $this->alert('success','New Allowance Added!');
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $log->action="Added $this->allowance_name allowance";
        $log->save();
        $this->reset_fields();
    }

    public function reset_fields(){
        $this->allowance_code='';
        $this->allowance_name='';
        $this->description='';
        $this->status='';
        $this->taxable='';
    }
    public function updated($prop){
        $this->validateOnly($prop);
    }
    public function render()
    {

            $allowances=Allowance::where('code','like',"%$this->search%")
                ->orwhere('allowance_name','like',"%$this->search%")
                ->orwhere('description','like',"%$this->search")
//                ->orwhere('status','like',"%$this->search%")
                ->paginate($this->perpage);
        return view('livewire.forms.available-allowances',compact('allowances'))
            ->extends('components.layouts.app');
    }
}
