<?php

namespace App\Livewire\Auth;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ChangePassword extends Component
{
    public $old_password,$password,$confirm_password;
    use LivewireAlert;
    public function store()
    {
        $this->validate([
            'old_password' => [
                'required', function ($attribute, $value, $fail) {
                    if (!Hash::check($value, auth()->user()->password)) {
                        $fail('Old Password did not match');
                    }
                },
            ],
            'password' => ['required', Password::min(8)->mixedCase()->numbers()->symbols()->uncompromised()],
            'confirm_password'=>'required|same:password',
        ]);
        $user=Auth::user();
        $user->password=bcrypt($this->password);
        $user->save();
        $this->alert('success','Password have been updated');
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $log->action="Updated their password";
        $log->save();

    }
    public function render()
    {
        return view('livewire.auth.change-password');
    }
}
