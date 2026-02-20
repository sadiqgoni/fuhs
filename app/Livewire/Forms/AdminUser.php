<?php

namespace App\Livewire\Forms;

use App\Mail\UserAccountMail;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class AdminUser extends Component
{
    public $name, $email, $username, $permissions = [], $status, $role, $new_password;
    public $record = true, $create, $edit, $ids, $search, $perpage = 10, $userInfo, $reset_password_mode = false;
    use LivewireAlert, WithoutUrlPagination, WithPagination;
    protected $listeners = ['confirmed'];
    protected function rules()
    {
        return [
            'name' => 'required|',
            'email' => 'required|email|unique:users,email,' . $this->ids,
            'username' => 'required|unique:users,username,' . $this->ids,
        ];
    }
    public function updated($pro)
    {
        return $this->validateOnly($pro);
    }
    public function close()
    {
        $this->record = true;
        $this->create = false;
        $this->edit = false;
        $this->reset_password_mode = false;
        $this->name = '';
        $this->email = '';
        $this->username = '';
    }
    public function create_user()
    {
        $this->record = false;
        $this->create = true;
        $this->edit = false;
        $this->reset_password_mode = false;
        $this->name = '';
        $this->email = '';
        $this->username = '';
    }

    public function store()
    {
        $this->validate();
        $userObj = new User();
        $userObj->name = $this->name;
        $userObj->email = $this->email;
        $userObj->username = $this->username;
        //       $userObj->status=0;
        $userObj->password = bcrypt(123456);
        $userObj->permission = json_encode($this->permissions);
        $userObj->status = 1;
        $userObj->save();
        Mail::send(new UserAccountMail($this->email));
        $this->alert('success', 'Admin user has been added');
        $user = Auth::user();
        $log = new ActivityLog();
        $log->user_id = $user->id;
        $log->action = "Added new admin ($this->username) record";
        $log->save();
        $this->name = '';
        $this->username = '';
        $this->email = '';
        $this->permissions = [];
        $this->close();
    }
    public function edit_user($id)
    {
        $this->record = false;
        $this->edit = true;
        $create = false;
        $this->ids = $id;
        $userObj = User::find($id);
        $this->name = $userObj->name;
        $this->username = $userObj->username;
        $this->email = $userObj->email;
        $this->status = $userObj->status;
        $this->permissions = json_decode($userObj->permission);
        if ($userObj->role != 1) {
            if (!empty($this->permissions)) {
                foreach ($this->permissions as $key => $permission) {
                    $this->permissions[$key];
                }
            } else {
                $this->permissions = [];
            }

        }
        $this->userInfo = $userObj;

    }
    public function update($id)
    {
        $this->validate();
        $userObj = User::find($id);
        $userObj->name = $this->name;
        $userObj->email = $this->email;
        $userObj->status = 1;
        $userObj->username = $this->username;
        //       $userObj->password=bcrypt(123456);
        $userObj->permission = json_encode($this->permissions);
        $userObj->status = $this->status;
        $userObj->save();
        $this->alert('success', 'User have been updated');
        $user = Auth::user();
        $log = new ActivityLog();
        $log->user_id = $user->id;
        $log->action = "Updated admin ($this->username) record";
        $log->save();
        $this->close();
    }

    public function password()
    {
        $this->record = false;
        $this->create = false;
        $this->edit = false;
        $this->reset_password_mode = true;
        $this->new_password = '';
    }

    public function closePasswordReset()
    {
        $this->reset_password_mode = false;
        $this->record = true; // Go back to list
    }

    public function saveNewPassword()
    {
        $this->validate([
            'new_password' => 'required|min:6',
        ]);

        if (!$this->ids) {
            $this->alert('error', 'User ID not found.');
            return;
        }

        $user = User::find($this->ids);
        if ($user) {
            $user->password = bcrypt($this->new_password);
            $user->save();
            $this->alert('success', 'Password has been updated successfully.');

            $authUser = Auth::user();
            $log = new ActivityLog();
            $log->user_id = $authUser->id;
            $log->action = "Reset admin ($user->username) password manually";
            $log->save();

            $this->closePasswordReset();
        } else {
            $this->alert('error', 'User not found.');
        }
    }

    public function render()
    {
        $search = $this->search;
        $users = User::where(function ($query) use ($search) {
            $query->where('name', 'like', "%$search")
                ->orWhere('username', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%");
        })
            ->when($this->role, function ($q) {
                return $q->where('role', $this->role);
            })
            ->orderBy('role', 'asc')
            ->paginate($this->perpage);
        return view('livewire.forms.admin-user', compact('users'))->extends('components.layouts.app');
    }
}
