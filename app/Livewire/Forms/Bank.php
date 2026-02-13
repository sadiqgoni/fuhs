<?php

namespace App\Livewire\Forms;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Bank extends Component
{
    public $search,$perpage=25;
    public $bank_code,$bank_name,$bank_branch,$status,$ids;
    use WithPagination,WithoutUrlPagination,LivewireAlert;

    public $edit=false,$create=false,$record=true;
    protected function rules()
    {
        return [
            'bank_name' => 'required|regex:/^[\pL\s]+$/u|unique:banks,bank_name,'.$this->ids,
            'bank_code' => 'required|unique:banks,bank_code,'.$this->ids,
            'bank_branch' => 'nullable',
        ];
    }
    public function create_bank()
    {
        $this->create=true;
        $this->edit=false;
    }
    public function close()
    {
        $this->record=true;
        $this->create=false;
        $this->edit=false;
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
        $postObj=new \App\Models\Bank();
        $postObj->bank_name=$this->bank_name;
        $postObj->bank_code=$this->bank_code;
        $postObj->bank_branch=$this->bank_branch;
        $postObj->status=1;
        $postObj->save();
        $this->bank_name='';
        $this->bank_code='';
        $this->bank_branch='';
        $this->alert('success','Bank have been added');
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $log->action="Added ($this->bank_name) bank";
        $log->save();
    }
    public function edit_record($id)
    {
        $this->create=false;
        $this->edit=true;
        $postObj=\App\Models\Bank::find($id);
        $this->ids=$id;
        $this->bank_name=$postObj->bank_name;
        $this->bank_code=$postObj->bank_code;
        $this->bank_branch=$postObj->bank_branch;
        $this->status=$postObj->status;
    }
    public function update($id)
    {
       $this->validate();
        $postObj=\App\Models\Bank::find($id);
        $postObj->bank_name=$this->bank_name;
        $postObj->bank_code=$this->bank_code;
        $postObj->bank_branch=$this->bank_branch;
        $postObj->status=$this->status;
        $postObj->save();
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $log->action="Updated ($this->bank_name) bank";
        $log->save();
        $this->edit=false;
        $this->create=false;
        $this->alert('success','Bank have been updated');

    }
//    public function updatedStatus()
//    {
//        dd('hy');
//    }

    public function status_change($id)
    {
        $bank = \App\Models\Bank::find($id);
        if ($bank->status == 1) {
            $bank->status = 0;
            $message = 'Bank discontinued successfully';
        } else {
            $bank->status = 1;
            $message = 'Bank activated successfully';
        }
        $bank->save();
        $this->alert('success', $message);
    }

    public function render()
    {
        $posts=\App\Models\Bank::where('bank_name','like',"%$this->search%")
            ->orWhere('bank_code','like',"%$this->search%")
            ->paginate($this->perpage);
        return view('livewire.forms.bank',compact('posts'))->extends('components.layouts.app');
    }
}
