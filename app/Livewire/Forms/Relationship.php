<?php

namespace App\Livewire\Forms;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Relationship extends Component
{
    public $search,$perpage=25;
    public $name,$ids;
    use WithPagination,WithoutUrlPagination,LivewireAlert;
    public $edit=false,$create=false;
    protected function rules()
    {
        return [
            'name' => 'required|regex:/^[\pL\s]+$/u|unique:relationships,name,'.$this->ids,

        ];
    }

    public function mount()
    {
    }
    public function updated($pro)
    {
        $this->validateOnly($pro);
    }
    public function store()
    {
        $this->validate();
        $postObj=new \App\Models\Relationship();
        $postObj->name=$this->name;
        $postObj->save();
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $log->action="Added $this->name relationship type ";
        $log->save();
        $this->name='';
        $this->alert('success','Relationship have been added');
    }
    public function edit_record($id)
    {
        $this->create=false;
        $this->edit=true;
        $postObj=\App\Models\Relationship::find($id);
        $this->ids=$id;
        $this->name=$postObj->name;
    }
    public function update($id)
    {
        $this->validate();
        $postObj=\App\Models\Relationship::find($id);
        $postObj->name=$this->name;
        $postObj->save();
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $log->action="updated $this->name relation type ";
        $log->save();
        $this->edit=false;
        $this->create=false;
        $this->alert('success','Relationship have been updated');
        $this->ids='';
        $this->name='';
    }
    public function create_post()
    {
        $this->create=true;
        $this->edit=false;
    }
    public function status_change($id)
    {
        $post=\App\Models\Relationship::find($id);
        if ($post->status==1){
            $post->status=2;
        }else{
            $post->status=1;

        }
        $post->save();
    }
    public function close()
    {
        $this->name='';
        $this->create=false;
        $this->edit=false;
    }
    public function render()
    {
        $posts=\App\Models\Relationship::where('name','like',"%$this->search%")->paginate($this->perpage);
        return view('livewire.forms.relationship',compact('posts'))->extends('components.layouts.app');
    }
}
