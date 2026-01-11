<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\EmployeeProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class StaffAuth extends Controller
{
    public function login()
    {
        return view('staff_login');
    }

    public function postLogin(Request $request)
    {
        $this->validate($request,[
            'username'=>'required|present',
            'password'=>'required|present'
        ]);
        $user=EmployeeProfile::where('email',$request->username)->where('phone_number',$request->password)->first();
        if (Auth::attempt([
            'username'=>$request->username,
            'password'=>$request->password,
        ])){
            $user=Auth::user();
            $log=new ActivityLog();
            $log->user_id=$user->id;
            $log->action="logged in";
            $log->save();
            return redirect()->route('staff.dashboard');
        }else{
            if (empty($user)){
                Alert::warning('Invalid Login Credentials');
                return  back();
            }else{
                $this->validate($request,[
                    'username'=>'unique:users,username'
                ]);
                DB::beginTransaction();
                try {
                    $userObj=new User();
                    $userObj->name=$user->full_name;
                    $userObj->email=$user->email;
                    $userObj->username=$user->email;
                    $userObj->password=Hash::make($request->password);
                    $userObj->role=1;
                    $userObj->permission=190;
                    $userObj->save();
                    Auth::login($userObj);
                    return redirect()->route('staff.dashboard');
                }catch (\Exception $e){
                    DB::rollBack();
                    return "Email not found in your profile please contact the system admin";
                }
            }

        }
    }
}
