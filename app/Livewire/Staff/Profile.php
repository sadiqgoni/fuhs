<?php

namespace App\Livewire\Staff;

use App\Models\EmployeeProfile;
use App\Models\LocalGovt;
use App\Models\Relationship;
use App\Models\Religion;
use App\Models\State;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Profile extends Component
{
    public $gender,$full_name,
$tribe,
$religion,
$whatsapp_number,
$nationality,
$state_of_origin,
$local_government,
$marital_status,
$name_of_next_of_kin,
$next_of_kin_phone_number,
$relationship,
$address,
$profile_picture;
    public $lgas,$states;
    use LivewireAlert;
    protected function rules()
    {

        return [


            'name_of_next_of_kin'=>'nullable',
            'next_of_kin_phone_number'=>'nullable|digits:11',
            'relationship'=>'nullable',
            'address'=>'nullable',

            'gender'=>'nullable',
            'tribe'=>'nullable',
            'religion'=>'nullable',
            'whatsapp_number'=>'nullable|digits:11|',
            'nationality'=>'nullable',
            'state_of_origin'=>'nullable',
            'local_government'=>'nullable',
            'marital_status'=>'nullable',
        ];

    }
    public function mount(){
        $this->states=[];
        $this->lgas=[];

        $employee=EmployeeProfile::where('email',Auth::user()->email)->first();
        if ($employee->nationality != null){
            $this->states=State::where('country',1)->get();
        }
        if ($employee->state_of_origin != null){
            $this->lgas=LocalGovt::where('state_id',$employee->state_of_origin)->get();
        }

        $this->full_name=$employee->full_name;
        $this->gender=$employee->gender;
        $this->tribe=$employee->tribe;
        $this->religion=$employee->religion;
        $this->whatsapp_number=$employee->whatsapp_number;
        $this->nationality=1;
        $this->state_of_origin=$employee->state;
        $this->local_government=$employee->local_government;
        $this->date_of_birth=$employee->date_of_birth;
        $this->marital_status=$employee->marital_status;
        $this->name_of_next_of_kin=$employee->name_of_next_of_kin;
        $this->next_of_kin_phone_number=$employee->next_of_kin_phone_number;
        $this->relationship=$employee->relationship;
        $this->address=$employee->address;
    }

    public function store()
    {

        $this->validate();
        $profileObj=EmployeeProfile::where('email',Auth::user()->email)->first();
        try {
            $profileObj->gender=$this->gender;
            $profileObj->tribe=$this->tribe;
            $profileObj->religion=$this->religion;
            $profileObj->whatsapp_number=$this->whatsapp_number;
            $profileObj->nationality=$this->nationality;
            $profileObj->state_of_origin=$this->state_of_origin;
            $profileObj->local_government=$this->local_government;
            $profileObj->marital_status=$this->marital_status;
            $profileObj->name_of_next_of_kin=$this->name_of_next_of_kin;
            $profileObj->next_of_kin_phone_number=$this->next_of_kin_phone_number;
            $profileObj->relationship=$this->relationship;
            $profileObj->address=$this->address;
            $profileObj->profile_picture=$this->profile_picture?$this->profile_picture->store('profilePictures','public'):'';
            $profileObj->save();
            $this->alert('success','Profile have been updated',[
                'timer'=>9000
//            'progressBarTimer'=>5
            ]);
        }catch (\Exception $e){
            $this->alert('error','Profile update failed');
        }
    }

    public function updatedNationality()
    {

        if ($this->nationality != null){
            $this->states=State::where('country',1)->get();
        }
    }
    public function UpdatedStateOfOrigin()
    {
        if ($this->state_of_origin != ''){
            $this->lgas =LocalGovt::where('state_id',$this->state_of_origin)->get();
        }
    }
    public function render()
    {
        $relationships=Relationship::all();
        $religions=Religion::all();
        return view('livewire.staff.profile',compact('relationships','religions'))
            ->extends('components.layouts.app')->section('body');
    }
}
