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

use App\Models\SalaryHistory;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        // 1. Monthly Payroll Trend (Last 12 Months)
        $salaryTrend = SalaryHistory::select(
            DB::raw("DATE_FORMAT(date_month, '%Y-%m') as month_year"),
            DB::raw("SUM(net_pay) as total")
        )
            ->groupBy('month_year')
            ->orderBy('month_year', 'DESC')
            ->limit(12)
            ->get()
            ->reverse();

        // 2. Staff by Department
        // Assuming 'department' column stores the ID or Name. 
        // We group by it. If it's an ID, we might need to map it to names in the view or here if Relation existed.
        // For now, raw grouping.
        $staffByDept = EmployeeProfile::select('department', DB::raw('count(*) as total'))
            ->groupBy('department')
            ->get();

        // If department is an ID, let's try to fetch department names to map them
        $departments = \App\Models\Department::pluck('name', 'id')->toArray();
        $staffByDept->transform(function ($item) use ($departments) {
            $item->department_name = $departments[$item->department] ?? $item->department;
            return $item;
        });

        // 3. Staff by Grade Level
        $staffByGrade = EmployeeProfile::select('grade_level', DB::raw('count(*) as total'))
            ->groupBy('grade_level')
            ->orderBy('grade_level')
            ->get();

        return view('Dashboard.dashboard', compact('salaryTrend', 'staffByDept', 'staffByGrade'));
    }
    public function logout()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $log = new ActivityLog();
            $log->user_id = $user->id;
            $log->action = "logged out";
            $log->save();
            $user->verify = null;
            $user->save();
        }
        auth()->logout();
        return redirect()->route('admin.login');
    }
    public function staff_logout()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $log = new ActivityLog();
            $log->user_id = $user->id;
            $log->action = "logged out";
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
        if (strcmp($request->get('old_password'), $request->get('password')) == 0) {
            //Current password and new password are same
            Alert::warning('New Password cannot be same as your current password. Please choose a different password');
            return back();
        }
        $this->validate($request, [
            'old_password' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, auth()->user()->password)) {
                        $fail('Old Password did not match');
                    }
                },
            ],
            //            'password' => ['required','different:old_password', Password::min(8)->mixedCase()->numbers()->symbols()->uncompromised()],
            'password' => ['required', 'different:old_password', Password::min(6)->mixedCase()],
            'confirm_password' => 'required|same:password',
        ]);

        $user = Auth::user();
        $user->password = bcrypt($request->password);
        $user->password_changed = 1;
        $user->save();
        Alert::success('Password have been updated');
        $user = Auth::user();
        $log = new ActivityLog();
        $log->user_id = $user->id;
        $log->action = "Updated their password";
        $log->save();
        return redirect()->route('staff.dashboard');
    }
    public function staff_dashboard()
    {
        $user = Auth::user();
        $user = EmployeeProfile::where('email', $user->email)->first();
        return view('dashboard', compact('user'));
    }
}
