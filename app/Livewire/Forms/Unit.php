<?php

namespace App\Livewire\Forms;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Unit extends Component
{
    public $search,$perpage=25,$status;
    public $name,$ids;
    use WithPagination,WithoutUrlPagination,LivewireAlert;
    public $edit=false,$create=false;
    protected function rules()
    {
        return [
            'name' => 'required|regex:/^[\pL\s]+$/u|unique:units,name,' . $this->ids,
        ];
    }

    public function updated($pro)
    {
        $this->validateOnly($pro);
    }
    public function store()
    {
        $this->validate();
        $postObj=new \App\Models\Unit();
        $postObj->name=$this->name;
        $postObj->status=1;

        $postObj->save();

        $this->name='';
        $this->alert('success','Unit have been added');
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $log->action="added $this->name unit";
        $log->save();
    }
    public function edit_record($id)
    {
        $this->create=false;
        $this->edit=true;
        $postObj=\App\Models\Unit::find($id);
        $this->ids=$id;
        $this->name=$postObj->name;
    }
    public function create_post()
    {
        $this->create=true;
        $this->edit=false;
    }
    public function status_change($id)
    {
        $post=\App\Models\Unit::find($id);
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
    }

    public function update($id)
    {
       $this->validate();
        $postObj=\App\Models\Unit::find($id);
        $postObj->name=$this->name;
        $postObj->save();
        $this->edit=false;
        $this->create=false;
        $this->alert('success','Unit have been updated');
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $log->action="Updated $this->name unit";
        $log->save();
        $this->ids='';
        $this->name='';
    }


    public function render()
    {
        $posts=\App\Models\Unit::where('name','like',"%$this->search%")->paginate($this->perpage);
        return view('livewire.forms.unit',compact('posts'))->extends('components.layouts.app');
    }
}
