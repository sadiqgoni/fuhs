<?php

namespace App\Livewire\Forms;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class AppSetting extends Component
{
    public $name,$logo,$email,$address,$paye_calculation,$statutory_deduction,$two_factor_authentication_type;
    public $record=true,$create,$edit,$ids;
    use LivewireAlert,WithFileUploads;
    protected $rules=[
        'email'=>'email|required',
        'paye_calculation'=>'required',
        'logo'=>'required|mimes:jpg,jpeg,png',
        'address'=>'required',
        'name'=>'required',
        'statutory_deduction'=>'required',
        'two_factor_authentication_type'=>'required'
    ];
    public function mount()
    {
        if (\App\Models\AppSetting::get()->count() > 0){
            $appObj=\App\Models\AppSetting::first();
            $this->name=$appObj->name;
            $this->address=$appObj->address;
            $this->email=$appObj->email;
            $this->paye_calculation=$appObj->paye_calculation;
            $this->statutory_deduction=$appObj->statutory_deduction;
            $this->two_factor_authentication_type=$appObj->auth_type;

        }
    }
    public function store()
    {
        $this->validate();
        $appObj=new \App\Models\AppSetting();
        $appObj->name=$this->name;
        $appObj->email=$this->email;
        $appObj->logo=$this->logo->store('logo','public');
        $appObj->address=$this->address;
        $appObj->paye_calculation=$this->paye_calculation;
        $appObj->statutory_deduction=$this->statutory_deduction;
        $appObj->auth_type=$this->two_factor_authentication_type;

        $appObj->save();
        $this->alert('success','App variables have been set');
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $log->action="Set application variables";
        $log->save();
    }
    public function edit_app()
    {
        $this->record=false;
        $this->edit=true;
        $appObj=App\Models\AppSetting::first();
        $this->name=$appObj->name;
        $this->address=$appObj->address;
        $this->email=$appObj->email;
        $this->paye_calculation=$appObj->paye_calculation;
        $this->statutory_deduction=$appObj->statutory_deduction;
        $this->two_factor_authentication_type=$appObj->auth_type;

    }
    public function update()
    {
        $appObj=\App\Models\AppSetting::first();
        $appObj->name=$this->name? $this->name :$appObj->name;
        $appObj->email=$this->email? $this->email :$appObj->email;
        $appObj->address=$this->address? $this->address :$appObj->address;
        $appObj->statutory_deduction=$this->statutory_deduction? $this->statutory_deduction :$appObj->statutory_deduction;
        $appObj->paye_calculation=$this->paye_calculation? $this->paye_calculation :$appObj->paye_calculation;
        $appObj->auth_type=$this->two_factor_authentication_type? $this->two_factor_authentication_type :$appObj->auth_type;

        if ($this->logo !=''){
            $appObj->logo=$this->logo->store('logo','public');
        }
        $appObj->save();
        $this->alert('success','Application variables have been updated');
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $log->action="updated application variables";
        $log->save();
        $this->record=true;
        $this->edit=false;
    }
    public function render()
    {
        $setting=\App\Models\AppSetting::first();
        return view('livewire.forms.app-setting',compact('setting'))->extends('components.layouts.app');
    }
}
