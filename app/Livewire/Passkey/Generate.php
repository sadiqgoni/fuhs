<?php

namespace App\Livewire\Passkey;

use App\Models\ActivityLog;
use App\Models\EmployeePasskey;
use App\Models\Passkey;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Generate extends Component
{
    public $passkeys;
    public $record=false;
    public function mount()
    {
        $this->passkeys=[];
    }
    public function generate()
    {
        $names=Passkey::inRandomOrder()->limit(20)->get();
        foreach ($names as $name)
        {
            $serial=rand(0000,1111);
            $employee=new EmployeePasskey();
            $employee->employee_id=Auth::id();
            $employee->rand_int=$serial;
            $employee->passkey=Hash::make($name->name);
            $employee->save();
        }

        $this->record=true;
    }
    public function render()
    {
        return view('livewire.passkey.generate');
    }
}
