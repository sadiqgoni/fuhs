<?php

namespace App\Livewire\Forms;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Department extends Component
{
    public $search, $perpage = 8;
    public $name, $unit, $units, $depts, $ids;
    use WithPagination, WithoutUrlPagination, LivewireAlert;

    public $edit = false, $create = false;
    protected function  rules(){
        return[
        'name' => 'required|regex:/^[\pL\s]+$/u|unique:departments,name,'.$this->ids,
        'unit' => 'required'
    ];
}
    public function updatedUnit()
    {
        if ($this->unit !=null){
            $this->depts=\App\Models\Department::where('unit_id',$this->unit)->get();
        }else{
            $this->departments=[];
        }
    }
    public function mount()
    {
        $this->depts=[];
    }
    public function updated($pro)
    {
        $this->validateOnly($pro);
    }
    public function store()
    {
        $this->validate();
        $postObj=new \App\Models\Department();
        $postObj->name=$this->name;
        $postObj->unit_id=$this->unit;
        $postObj->status=1;

        $postObj->save();
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $log->action="Added $this->name department ";
        $log->save();
        $this->name='';
        $this->unit='';
        $this->alert('success','Department have been added');
    }
    public function edit_record($id)
    {
        $this->create=false;
        $this->edit=true;
        $postObj=\App\Models\Department::find($id);
        $this->ids=$id;
        $this->name=$postObj->name;
        $this->unit=$postObj->unit_id;

    }
    public function create_post()
    {
        $this->create=true;
        $this->edit=false;
    }
    public function status_change($id)
    {
        $post=\App\Models\Department::find($id);
        if ($post->status==1){
            $post->status=2;
        }else{
            $post->status=1;

        }
        $post->save();
    }
    public function close()
    {
        $this->create=false;
        $this->edit=false;
        $this->name='';
        $this->unit='';
    }

    public function update($id)
    {
       $this->validate();
        $postObj=\App\Models\Department::find($id);
        $postObj->name=$this->name;
        $postObj->unit_id=$this->unit;
        $postObj->save();
        $this->edit=false;
        $this->create=false;
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $log->action="Updated $this->name department ";
        $log->save();
        $this->alert('success','Department have been updated');
        $this->ids='';
        $this->name='';
    }

    public function render()
    {
        $posts=\App\Models\Department::where('name','like',"%$this->search%")->SimplePaginate($this->perpage);
        return view('livewire.forms.department',compact('posts'))->extends('components.layouts.app');
    }
}
