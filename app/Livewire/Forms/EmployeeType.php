<?php

namespace App\Livewire\Forms;

use App\Models\ActivityLog;
use App\Models\EmploymentType;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class EmployeeType extends Component
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
        $postObj=new EmploymentType();
        $postObj->name=$this->name;
        $postObj->status=1;
        $postObj->save();
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $log->action="Added $this->name employment type ";
        $log->save();
        $this->name='';
        $this->alert('success','Employment type have been added');
    }
    public function edit_record($id)
    {
        $this->create=false;
        $this->edit=true;
        $postObj=EmploymentType::find($id);
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
        $post=EmploymentType::find($id);
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
        $postObj=EmploymentType::find($id);
        $postObj->name=$this->name;
        $postObj->save();
        $this->edit=false;
        $this->create=false;
        $this->alert('success','Employment type have been updated');
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $log->action="updated $this->name employment type ";
        $log->save();
        $this->ids='';
        $this->name='';
    }

    public function render()
    {
        $posts=EmploymentType::where('name','like',"%$this->search%")->paginate($this->perpage);
        return view('livewire.forms.employee-type',compact('posts'))->extends('components.layouts.app');
    }
}
