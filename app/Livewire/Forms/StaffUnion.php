<?php

namespace App\Livewire\Forms;

use App\Models\ActivityLog;
use App\Models\Union;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class StaffUnion extends Component
{
    public $search,$perpage=25,$status;
    public $name,$ids;
    use WithPagination,WithoutUrlPagination,LivewireAlert;
    public $edit=false,$create=false;
    protected function rules()
    {
        return [

            'name' => 'required|regex:/^[\pL\s]+$/u|unique:employment_types,name,'.$this->ids,
        ];
    }

    public function updated($pro)
    {
        $this->validateOnly($pro);
    }
    public function store()
    {
        $this->validate();
        $postObj=new Union();
        $postObj->name=$this->name;
        $postObj->status=1;
        $postObj->save();
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $log->action="Added $this->name Union  ";
        $log->save();
        $this->name='';
        $this->alert('success','Union  have been added');
    }
    public function edit_record($id)
    {
        $this->create=false;
        $this->edit=true;
        $postObj=Union::find($id);
        $this->ids=$id;
        $this->name=$postObj->name;
        $this->status=$postObj->status;
    }

    public function create_post()
    {
        $this->create=true;
        $this->edit=false;
    }
    public function status_change($id)
    {
        $post=Union::find($id);
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
        $postObj=Union::find($id);
        $postObj->name=$this->name;
        $postObj->save();
        $this->edit=false;
        $this->create=false;
        $this->alert('success','Union  have been updated');
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $log->action="updated $this->name Union";
        $log->save();
        $this->ids='';
        $this->name='';
    }

    public function render()
    {
        $posts=Union::where('name','like',"%$this->search%")->paginate($this->perpage);

        return view('livewire.forms.staff-union',compact('posts'))->extends('components.layouts.app');
    }
}
