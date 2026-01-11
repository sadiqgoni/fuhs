<?php

namespace App\Livewire\Forms;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class StaffCategory extends Component
{
    public $search,$perpage=25,$status;
    public $name,$ids;
    use WithPagination,WithoutUrlPagination,LivewireAlert;
    public $edit=false,$create=false;
    protected function rules()
    {
        return [
            'name' => 'required|regex:/^[\pL\s]+$/u|unique:staff_categories,name,'.$this->ids,
        ];
    }


    public function updated($pro)
    {
        $this->validateOnly($pro);
    }
    public function store()
    {
        $this->validate();
        $postObj=new \App\Models\StaffCategory();
        $postObj->name=$this->name;
        $postObj->status=1;
        $postObj->save();
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $postObj->status=1;

        $log->action="Added $this->name Category";
        $log->save();
        $this->name='';
        $this->alert('success','Staff category have been added');
    }
    public function edit_record($id)
    {
        $this->create=false;
        $this->edit=true;
        $postObj=\App\Models\StaffCategory::find($id);
        $this->ids=$id;
        $this->name=$postObj->name;
    }
    public function update($id)
    {
        $this->validate();
        $postObj=\App\Models\StaffCategory::find($id);
        $postObj->name=$this->name;
        $postObj->save();
        $this->edit=false;
        $this->create=false;
        $this->alert('success','Staff category have been updated');
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $log->action="Updated $this->name category";
        $log->save();
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
        $post=\App\Models\StaffCategory::find($id);
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
        $posts=\App\Models\StaffCategory::where('name','like',"%$this->search%")->paginate($this->perpage);
        return view('livewire.forms.staff-category',compact('posts'))->extends('components.layouts.app');
    }
}
