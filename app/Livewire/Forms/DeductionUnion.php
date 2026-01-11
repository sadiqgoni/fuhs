<?php

namespace App\Livewire\Forms;

use App\Models\UnionDeduction;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class DeductionUnion extends Component
{
    public $create=false,$record=true;
    public $deduction,$union,$ids;
use LivewireAlert;
    protected $listeners=['confirmed'];

    public function create_post(){
        $this->record=false;
        $this->create=true;
    }
    public function store()
    {
        $this->validate([
            'deduction'=>'required',
            'union'=>'required',
        ]);
        $dedObj=new UnionDeduction();
        $dedObj->deduction_id=$this->deduction;
        $dedObj->union_id=$this->union;
        $dedObj->save();
        $this->alert('success','Deduction has added to union');
        $this->deduction='';
        $this->union='';
    }
    public function deleteId($id)
    {
        $this->ids=$id;
        $this->alert('warning','Do you want to delete this record?',[
            'showConfirmButton'=>true,
            'onConfirmed'=>'confirmed',
            'showCancelButton'=>true,
            'timer'=>90000,
            'position'=>'center'
        ]);
    }
    public function confirmed()
    {
        $deleteObj=UnionDeduction::find($this->ids);
        $deleteObj->delete();
        $this->alert('success','Record deleted successfully');
    }
    public function close()
    {
        $this->create=false;
        $this->edit=false;
    }
    public function render()
    {
        $unions=UnionDeduction::all();
        return view('livewire.forms.deduction-union',compact('unions'))->extends('components.layouts.app');
    }
}
