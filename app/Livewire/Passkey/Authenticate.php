<?php

namespace App\Livewire\Passkey;

use App\Models\EmployeePasskey;
use Livewire\Component;

class Authenticate extends Component
{
    public function check_passkey()
    {
       
    }
    public function render()
    {
        return view('livewire.passkey.authenticate');
    }
}
