<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\EmployeeProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use RealRashid\SweetAlert\Facades\Alert;

class DashboardController extends Controller
{
    public function dashboard()
    {
        return view('Dashboard.dashboard');
    }
    public function logout()
    {
        if (Auth::check()){
            $user=Auth::user();
            $log=new ActivityLog();
            $log->user_id=$user->id;
            $log->action="logged out";
            $log->save();
            $user->verify=null;
            $user->save();
        }
        auth()->logout();
        return redirect()->route('admin.login');
    }
    public function staff_logout()
    {
        if (Auth::check()){
            $user=Auth::user();
            $log=new ActivityLog();
            $log->user_id=$user->id;
            $log->action="logged out";
            $log->save();
//            $user->verify=null;
            $user->save();
        }
        auth()->logout();
        return redirect()->route('login');
    }
    public function show_pass()
    {
        return view('pass');
    }
    public function post_pass(Request $request)
    {
        if(strcmp($request->get('old_password'), $request->get('password')) == 0){
            //Current password and new password are same
            Alert::warning('New Password cannot be same as your current password. Please choose a different password');
            return back();
        }
        $this->validate($request,[
            'old_password' => [
                'required', function ($attribute, $value, $fail) {
                    if (!Hash::check($value, auth()->user()->password)) {
                        $fail('Old Password did not match');
                    }
                },
            ],
//            'password' => ['required','different:old_password', Password::min(8)->mixedCase()->numbers()->symbols()->uncompromised()],
            'password' => ['required','different:old_password', Password::min(6)->mixedCase()],
            'confirm_password'=>'required|same:password',
        ]);

        $user=Auth::user();
        $user->password=bcrypt($request->password);
        $user->password_changed=1;
        $user->save();
        Alert::success('Password have been updated');
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $log->action="Updated their password";
        $log->save();
        return redirect()->route('staff.dashboard');
    }
    public function staff_dashboard()
    {
        $user=Auth::user();
        $user=EmployeeProfile::where('email',$user->email)->first();
        return view('dashboard',compact('user'));
    }
}
