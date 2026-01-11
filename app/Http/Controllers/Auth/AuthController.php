<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\EmployeePasskey;
use App\Providers\RouteServiceProvider;
use App\Responses\ForceTwoFactorResponse;
use App\services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Features;
use RealRashid\SweetAlert\Facades\Alert;

class AuthController extends Controller
{
    public function login()
    {
        return view('index');
    }

    public function postLogin1(Request $request)
    {
        $this->validate($request,[
            'username'=>'required|present',
            'password'=>'required|present'
        ]);
        if (Auth::attempt([
            'username'=>$request->username,
            'password'=>$request->password,
        ])){
            $user=Auth::user();
            $log=new ActivityLog();
            $log->user_id=$user->id;
            $log->action="logged in";
            $log->save();

            return redirect()->route('dashboard');

        }else{
            Alert::warning('Invalid Login Credentials');
            return  back();
        }
    }
    public function postLogin2(Request $request)
    {
        $credentials= $request->validate([
            'username'=>'required|present',
            'password'=>'required|present'
        ]);
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if (Auth::attempt($credentials)) {
                $user = Auth::user();

                // Check if 2FA is enabled for the user
                session(['login.id'=>$user->id]);
                if (Features::enabled(Features::twoFactorAuthentication()) &&
                    session()->has('login.id')) {
                    // This will trigger the 2FA challenge
                    return app(ForceTwoFactorResponse::class);
                }

                // Regular login redirect
                return redirect()->intended('dashboard');
            }

            return back()->withErrors(['email' => 'Invalid credentials']);
        }
    }
    public function postLogin(Request $request){
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->is_2fa_enabled) {
                $otpService = app(services::class);
                $otpService->sendOTP($user);

                return redirect()->route('2fa.verify');
            }

            return redirect()->intended(RouteServiceProvider::HOME);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
    public function two_factor(Request $request){
//       dd($request->input('key'));
        $all=$request->all();
        $count_valid=0;
        foreach ($all as $key=>$item)
        {
            $emp_pass=EmployeePasskey::where('passkey',$key)->first();
            if (!is_null($emp_pass)){
                if ($emp_pass->rand_int==$item){
                    $count_valid +=1;
                }else{

                    break;
                }
            }

        }
        if ($count_valid ==3){
            auth()->user()->verify=1;
            auth()->user()->save();
            return redirect()->route('dashboard');
        }else{
            Alert::warning('Invalid secrete keys provide');
            return back();
        }
    }
    public function show_two_factor(){
        $emp_pass=EmployeePasskey::where('employee_id',auth()->id())->inRandomOrder()->limit(3)->get();
        return view('livewire.auth.two-factor',compact('emp_pass'));
    }

}
