<style>
    #sidebar {
        transition: 0.5s ease-in-out;
        animation: ease-in-out 0.5s;
    }

    .page-content {
        margin-top: 70px;
    }

    .dropdown-toggle {
        background: none;
    }

    @media (max-width:920px) {
        .page-content {
            margin-top: 20px;
        }
    }

    .active {
        background-color: #fff !important;
        color: blue !important;
    }

    .list-group {
        color: white;
        background: none;
    }

    .list-group a {
        color: white;
        background: none;
    }

    .dropdown-menu a {
        color: black;
        !important;
    }

    .staff_nav a {
        background: black;
        cursor: pointer;
    }
</style>

@php

    $employeeRoutes = [
        'employee.profile',
        'employee.type',
        'staff.category',
        'staff.union',
        'unit',
        'department',
        'bank',
        'pfa',
        'rank',
        'tribe',
        'relationship',
    ];
    $payrollSettingsRoute = [
        'available.allowance',
        'available.deduction',
        'salary.structure',
        'allowance.template',
        'deduction.template',
        'salary.template',
    ];
    $payrollUpdateRoutes = [
        'salary.update.center',
        'group-salary-update',
        'employee.promotion',
        'staff.annual.salary.increment',
        'deduction.countdown',
        'salary.ledger.posting',
    ];

    $payrollReportRoutes = [
        'payroll.report.center',
        'report.for.individual',
        'employee.report.center',
        'staff.annual.salary.increment.history',
        'loan.deduction.history',
        'retired.staff',
        'contract.termination',
    ];
    $otherReportRoutes = [
        'backup.history',
        'restore.history',
        'analytic',
        'report.repository',
    ];

    $securityRoutes = [
        'admin.user',
        'backup',
        'restore',
        'audit.log',
        'restore.point'
    ];
    $helpRoutes = [
        'help',
        'faq',
    ];
    $isEmployeeActive = in_array(\Illuminate\Support\Facades\Route::currentRouteName(), $employeeRoutes);
    $payrollSettingsActive = in_array(\Illuminate\Support\Facades\Route::currentRouteName(), $payrollSettingsRoute);
    $payrollUpdateActive = in_array(\Illuminate\Support\Facades\Route::currentRouteName(), $payrollUpdateRoutes);
    $payrollReportActive = in_array(\Illuminate\Support\Facades\Route::currentRouteName(), $payrollReportRoutes);
    $otherReportActive = in_array(\Illuminate\Support\Facades\Route::currentRouteName(), $otherReportRoutes);
    $securityActive = in_array(\Illuminate\Support\Facades\Route::currentRouteName(), $securityRoutes);
    $helpActive = in_array(\Illuminate\Support\Facades\Route::currentRouteName(), $helpRoutes);
@endphp

<div class="row" style="">
    @can('can_admin')
        <div class="col-12 d-block d-lg-none" style="background: darkblue; margin-top: 85px">

            <button onclick="myFunction()" class="text-white py-2"
                style="padding-left:20px;border:none;background: none;font-size: 16px !important;outline: none;border: 0"
                type="button" id="dropdownMenuButton">
                <i class="fa fa-align-justify"></i> Navigation Menu >>
            </button>
            <ul class="list-group list-group  bg-light" id="sidebar"
                style="box-shadow: #5a6268 0px 1px 4px 1px;display: none">
                <li style="list-style: none;background: darkblue"><a href="{{route('dashboard')}}" class="list-group-item "
                        style="background: darkblue;color:white !important;"> <i class="fa fa-dashboard"></i> Dashboard</a>
                </li>
                {{-- start--}}

                @can('employee_setting')
                    <li style="list-style: none">
                        <div class="dropdown" style="background: darkblue;width:100%;border:0">
                            <button class=" dropdown-toggle text-white py-2"
                                style="padding-left:20px;border:none;background: none;font-size: 16px !important" type="button"
                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-user-times"></i> EMPLOYEES
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="border: none">
                                <a class="dropdown-item" href="{{route('employee.profile')}}">Employee profile</a>
                                <a class="dropdown-item" href="{{route('employee.type')}}">Employment Type</a>
                                <a class="dropdown-item" href="{{route('staff.category')}}">Staff Category</a>
                                <a class="dropdown-item" href="{{route('staff.union')}}">Staff Union</a>
                                <a class="dropdown-item" href="{{route('unit')}}">Unit</a>
                                <a class="dropdown-item" href="{{route('department')}}">Department</a>
                                <a class="dropdown-item" href="{{route('bank')}}">Banks</a>
                                <a class="dropdown-item" href="{{route('pfa')}}">PFAs</a>
                                <a class="dropdown-item" href="{{route('rank')}}">Ranks</a>
                                <a class="dropdown-item" href="{{route('state')}}">States</a>
                                <a class="dropdown-item" href="{{route('local.govt')}}">Local Governments</a>
                                <a class="dropdown-item" href="{{route('tribe')}}">Tribe</a>
                                <a class="dropdown-item" href="{{route('relationship')}}">Relationship</a>
                            </div>
                        </div>
                    </li>
                @endcan

                <li style="list-style: none">
                    <div class="dropdown" style="background: darkblue; width:100%;border:0">
                        <button class=" dropdown-toggle text-white py-2"
                            style="padding-left:20px;border:none;background: none;font-size: 16px !important" type="button"
                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-cogs"></i> PAYROLL SETTINGS
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="border: none">
                            @can('allowance')
                                <a class="dropdown-item" href="{{route('available.allowance')}}">Allowances</a>
                            @endcan
                            @can('deduction')
                                <a class="dropdown-item" href="{{route('available.deduction')}}">Deduction</a>

                            @endcan
                            @can('salary_structure')
                                <a class="dropdown-item" href="{{route('salary.structure')}}">Salary structure</a>

                            @endcan
                            @can('allowance_template')
                                <a class="dropdown-item" href="{{route('allowance.template')}}">Allowance template</a>
                                <a class="dropdown-item" href="{{route('allowance.template.step')}}">Step allowance</a>
                            @endcan
                            @can('deduction_template')
                                <a class="dropdown-item" href="{{route('deduction.template')}}">Deduction template</a>

                            @endcan
                            @can('salary_template')
                                <a class="dropdown-item" href="{{route('salary.template')}}">Salary template</a>
                            @endcan

                        </div>
                    </div>
                </li>

                <li style="list-style: none">
                    <div class="dropdown" style="background: darkblue;width:100%;border:0">
                        <button class=" dropdown-toggle text-white py-2"
                            style="padding-left:20px;border:none;background: none;font-size: 16px !important" type="button"
                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-bank"></i> PAYROLL UPDATE
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="border: none">
                            @can('monthly_update')<a class="dropdown-item" href="{{route('salary.update.center')}}">Monthly
                            update</a>@endcan
                            @can('group_update')<a class="dropdown-item" href="{{route('group-salary-update')}}">Group
                            update</a>@endcan
                            @can('annual_increment')<a class="dropdown-item" href="{{route('employee.promotion')}}">Staff
                            Promotion</a>@endcan
                            @can('annual_increment')<a class="dropdown-item"
                            href="{{route('staff.annual.salary.increment')}}">Annual increment</a>@endcan
                            @can('loan_deduction')<a class="dropdown-item" href="{{route('deduction.countdown')}}">Loan
                            deduction</a>@endcan
                            @can('salary_posting')<a class="dropdown-item" href="{{route('salary.ledger.posting')}}">Salary
                            posting</a>@endcan
                        </div>
                    </div>
                </li>

                <li style="list-style: none">
                    <div class="dropdown" style="background: darkblue;width:100%;border:0">
                        <button class=" dropdown-toggle text-white py-2"
                            style="padding-left:20px;border:none;background: none;font-size: 16px !important" type="button"
                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-registered"></i> PAYROLL REPORT
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="border: none">
                            @can('group_report') <a class="dropdown-item" href="{{route('payroll.report.center')}}">For
                            groups</a>@endcan
                            @can('individual_report') <a class="dropdown-item" href="{{route('report.for.individual')}}">For
                            individual</a>@endcan
                            @can('nominal_roll') <a class="dropdown-item" href="{{route('employee.report.center')}}">
                            Nominal roll</a>@endcan
                            @can('annual_inc_history') <a class="dropdown-item"
                                href="{{route('staff.annual.salary.increment.history')}}">Annual increment
                            history</a>@endcan
                            @can('loan_dedc_history') <a class="dropdown-item"
                            href="{{route('loan.deduction.history')}}">Loan deduction history</a>@endcan
                            @can('retired_staff') <a class="dropdown-item" href="{{route('retired.staff')}}">Retirement
                            List</a>@endcan
                            @can('terminated_list') <a class="dropdown-item"
                            href="{{route('contract.termination')}}">Contract Termination List</a>@endcan

                        </div>
                    </div>
                </li>

                <li style="list-style: none">
                    <div class="dropdown" style="background: darkblue;width:100%;border:0">
                        <button class=" dropdown-toggle text-white py-2"
                            style="padding-left:20px;border:none;background: none;font-size: 16px !important" type="button"
                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-plus-square"></i> OTHER REPORTS
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="border: none">
                            @can('repository') <a class="dropdown-item" href="{{route('report.repository')}}">Report
                            Repository</a>@endcan
                            @can('backup_history') <a class="dropdown-item" href="{{route('backup.history')}}">Backup
                            history</a>@endcan
                            @can('restore_history') <a class="dropdown-item" href="{{route('restore.history')}}">Restore
                            history</a>@endcan
                            {{-- @can('audit_log') <a class="dropdown-item" href="#"> Audit logs</a>@endcan--}}
                            @can('analytic') <a class="dropdown-item" href="{{route('analytic')}}">Analytics</a>@endcan
                        </div>
                    </div>
                </li>

                <li style="list-style: none">
                    <div class="dropdown" style="background: darkblue;width:100%;border:0">
                        <button class=" dropdown-toggle text-white py-2"
                            style="padding-left:20px;border:none;background: none;font-size: 16px !important" type="button"
                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-lock"></i> SECURITY
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="border: none">
                            @can('admin_user') <a class="dropdown-item" href="{{route('admin.user')}}">User
                            account</a>@endcan
                            @can('backup') <a class="dropdown-item" href="{{route('backup')}}">Backup</a>@endcan
                            @can('restore') <a class="dropdown-item" href="{{route('restore')}}"> Restore</a>@endcan
                            @can('restore_point')
                                <a class="dropdown-item" href="{{route('restore.point')}}"> Auto Restore point</a>
                            @endcan

                            @can('audit_log') <a class="dropdown-item" href="{{route('audit.log')}}">Audit log</a>@endcan
                        </div>
                    </div>
                </li>





                <li style="list-style: none">
                    <div class="dropdown" style="background: darkblue;width:100%;border:0">
                        <button class=" dropdown-toggle {{ $helpActive ? 'active bg-primary' : '' }} text-white py-2"
                            style="padding-left:20px;border:none;font-size: 16px !important;width: 100%;text-align: left"
                            type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <i class="fa fa-lock"></i> Help
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="border: none">
                            <a class="dropdown-item" href="{{route('faq')}}">Faq</a>
                            <a class="dropdown-item" href="{{route('help')}}">Help</a>
                        </div>
                    </div>
                </li>




            </ul>

        </div>
        <div class="col-12 d-none d-lg-block col-md-2" style="background: darkblue;height: 100vh">
            <ul class="list-group list-group  bg-light" style="margin-top: 100px;box-shadow: #5a6268 0px 1px 4px 1px;">
                <li style="list-style: none;background: darkblue"><a href="{{route('dashboard')}}"
                        class="list-group-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                        style="background: darkblue; !important;"> <i class="fa fa-dashboard"></i> Dashboard</a></li>
                {{-- start--}}

                @can('employee_setting')
                    <li style="list-style: none">
                        <div class="dropdown" style="background: darkblue;width:100%;border:0">
                            <button class=" dropdown-toggle {{ $isEmployeeActive ? 'active bg-primary' : '' }} text-white py-2"
                                style="padding-left:20px;border:none;font-size: 16px !important;width: 100%;text-align: left"
                                type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <i class="fa fa-user-times"></i> EMPLOYEES
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="border: none">
                                <a class="dropdown-item" href="{{route('employee.profile')}}">Employee profile</a>
                                <a class="dropdown-item" href="{{route('employee.type')}}">Employment Type</a>
                                <a class="dropdown-item" href="{{route('staff.category')}}">Staff Category</a>
                                <a class="dropdown-item" href="{{route('staff.union')}}">Staff Union</a>
                                <a class="dropdown-item" href="{{route('unit')}}">Unit</a>
                                <a class="dropdown-item" href="{{route('department')}}">Department</a>
                                <a class="dropdown-item" href="{{route('bank')}}">Banks</a>
                                <a class="dropdown-item" href="{{route('pfa')}}">PFAs</a>
                                <a class="dropdown-item" href="{{route('rank')}}">Ranks</a>
                                <a class="dropdown-item" href="{{route('state')}}">States</a>
                                <a class="dropdown-item" href="{{route('local.govt')}}">Local Governments</a>
                                <a class="dropdown-item" href="{{route('tribe')}}">Tribe</a>
                                <a class="dropdown-item" href="{{route('relationship')}}">Relationship</a>
                            </div>
                        </div>
                    </li>
                @endcan

                <li style="list-style: none">
                    <div class="dropdown" style="background: darkblue; width:100%;border:0">
                        <button
                            class=" dropdown-toggle {{ $payrollSettingsActive ? 'active bg-primary' : '' }} text-white py-2"
                            style="padding-left:20px;border:none;font-size: 16px !important;width: 100%;text-align: left"
                            type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <i class="fa fa-cogs"></i> PAYROLL SETTINGS
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="border: none">
                            @can('allowance')
                                <a class="dropdown-item" href="{{route('available.allowance')}}">Allowances</a>
                            @endcan
                            @can('deduction')
                                <a class="dropdown-item" href="{{route('available.deduction')}}">Deduction</a>

                            @endcan
                            @can('salary_structure')
                                <a class="dropdown-item" href="{{route('salary.structure')}}">Salary structure</a>

                            @endcan
                            @can('allowance_template')
                                <a class="dropdown-item" href="{{route('allowance.template')}}">Allowance template</a>
                                <a class="dropdown-item" href="{{route('allowance.template.step')}}">Salary Allowance Table</a>
                            @endcan
                            @can('deduction_template')
                                <a class="dropdown-item" href="{{route('deduction.template')}}">Deduction template</a>

                            @endcan
                            @can('salary_template')
                                <a class="dropdown-item" href="{{route('salary.template')}}">Salary template</a>
                            @endcan
                            <a class="dropdown-item" href="{{route('tax-brackets.index')}}">
                                Paye Calculation Formula
                            </a>

                        </div>
                    </div>
                </li>

                <li style="list-style: none">
                    <div class="dropdown" style="background: darkblue;width:100%;border:0">
                        <button
                            class=" dropdown-toggle {{ $payrollUpdateActive ? 'active bg-primary' : '' }} text-white py-2"
                            style="padding-left:20px;border:none;font-size: 16px !important;width: 100%;text-align: left"
                            type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <i class="fa fa-bank"></i> PAYROLL UPDATE
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="border: none">
                            @can('monthly_update')<a class="dropdown-item" href="{{route('salary.update.center')}}">Monthly
                            update</a>@endcan
                            @can('group_update')<a class="dropdown-item" href="{{route('group-salary-update')}}">Group
                            update</a>@endcan
                            @can('can_promote')<a class="dropdown-item" href="{{route('employee.promotion')}}">Staff
                            Promotion</a>@endcan
                            @can('annual_increment')<a class="dropdown-item"
                            href="{{route('staff.annual.salary.increment')}}">Annual increment</a>@endcan
                            @can('loan_deduction')<a class="dropdown-item" href="{{route('deduction.countdown')}}">Loan
                            deduction</a>@endcan
                            @can('salary_posting')<a class="dropdown-item" href="{{route('salary.ledger.posting')}}">Salary
                            posting</a>@endcan
                        </div>
                    </div>
                </li>

                <li style="list-style: none">
                    <div class="dropdown" style="background: darkblue;width:100%;border:0">
                        <button
                            class=" dropdown-toggle {{ $payrollReportActive ? 'active bg-primary' : '' }} text-white py-2"
                            style="padding-left:20px;border:none;font-size: 16px !important;width: 100%;text-align: left"
                            type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <i class="fa fa-registered"></i> PAYROLL REPORT
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="border: none">
                            @can('group_report') <a class="dropdown-item" href="{{route('payroll.report.center')}}">For
                            groups</a>@endcan
                            @can('individual_report') <a class="dropdown-item" href="{{route('report.for.individual')}}">For
                            individual</a>@endcan
                            @can('nominal_roll') <a class="dropdown-item" href="{{route('employee.report.center')}}">
                            Nominal roll</a>@endcan
                            @can('annual_inc_history') <a class="dropdown-item"
                                href="{{route('staff.annual.salary.increment.history')}}">Annual increment
                            history</a>@endcan
                            @can('loan_dedc_history') <a class="dropdown-item"
                            href="{{route('loan.deduction.history')}}">Loan deduction history</a>@endcan
                            @can('retired_staff') <a class="dropdown-item" href="{{route('retired.staff')}}">Retirement
                            List</a>@endcan
                            @can('terminated_list') <a class="dropdown-item"
                            href="{{route('contract.termination')}}">Contract Termination List</a>@endcan

                        </div>
                    </div>
                </li>

                <li style="list-style: none">
                    <div class="dropdown" style="background: darkblue;width:100%;border:0">
                        <button class=" dropdown-toggle {{ $otherReportActive ? 'active bg-primary' : '' }} text-white py-2"
                            style="padding-left:20px;border:none;font-size: 16px !important;width: 100%;text-align: left"
                            type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <i class="fa fa-plus-square"></i> OTHER REPORTS
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="border: none">
                            @can('repository') <a class="dropdown-item" href="{{route('report.repository')}}">Report
                            Repository</a>@endcan
                            @can('backup_history') <a class="dropdown-item" href="{{route('backup.history')}}">Backup
                            history</a>@endcan
                            @can('restore_history') <a class="dropdown-item" href="{{route('restore.history')}}">Restore
                            history</a>@endcan
                            {{-- @can('audit_log') <a class="dropdown-item" href="#"> Audit logs</a>@endcan--}}
                            @can('analytic') <a class="dropdown-item" href="{{route('analytic')}}">Analytics</a>@endcan
                        </div>
                    </div>
                </li>

                <li style="list-style: none">
                    <div class="dropdown" style="background: darkblue;width:100%;border:0">
                        <button class=" dropdown-toggle {{ $securityActive ? 'active bg-primary' : '' }} text-white py-2"
                            style="padding-left:20px;border:none;font-size: 16px !important;width: 100%;text-align: left"
                            type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <i class="fa fa-lock"></i> SECURITY
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="border: none">
                            @can('admin_user') <a class="dropdown-item" href="{{route('admin.user')}}">User
                            account</a>@endcan
                            @can('backup') <a class="dropdown-item" href="{{route('backup')}}">Backup</a>@endcan
                            @can('restore') <a class="dropdown-item" href="{{route('restore')}}"> Restore</a>@endcan
                            @can('restore_point')
                                <a class="dropdown-item" href="{{route('restore.point')}}"> Auto Restore point</a>
                            @endcan

                            @can('audit_log') <a class="dropdown-item" href="{{route('audit.log')}}">Audit log</a>@endcan
                        </div>
                    </div>
                </li>




                <li style="list-style: none">
                    <div class="dropdown" style="background: darkblue;width:100%;border:0">
                        <button class=" dropdown-toggle {{ $helpActive ? 'active bg-primary' : '' }} text-white py-2"
                            style="padding-left:20px;border:none;font-size: 16px !important;width: 100%;text-align: left"
                            type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <i class="fa fa-lock"></i> Help
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="border: none">
                            <a class="dropdown-item" href="{{route('faq')}}">Faq</a>
                            <a class="dropdown-item" href="{{route('help')}}">Help</a>
                        </div>
                    </div>
                </li>




            </ul>
        </div>
        <div class="col-12 col-md-10" style="background: lightblue">
            <main class="page-content" style="">
                <div class="container-fluid">
                    <h2 class="text-uppercase " style="font-size: 20px;text-align: center;">@yield('page_title')</h2>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <div>
                                @if(isset($slot)) {{$slot}} @endif
                                @yield('content')
                            </div>
                        </div>

                    </div>
                </div>
            </main>
        </div>

    @endcan

    @cannot('can_admin')
    <div class="col-12 d-block d-lg-none" style="background: darkblue; margin-top: 85px">
        @auth
            @if(auth()->user()->password_changed != null)

                <ul class="d-flex" style="background: white !important;font-size: 12px">
                    <li style="list-style: none;margin: 5px 3px"><a href="{{route('staff.dashboard')}}"
                            class="staff_nav list-group-item {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}"
                            style="">Dashboard</a></li>

                    <li style="list-style: none;margin: 5px 3px"><a href="{{route('payroll.request')}}"
                            class="staff_nav list-group-item {{ request()->routeIs('staff.payroll.request') ? 'active' : '' }}"
                            style="">Report Request </a></li>
                    <li style="list-style: none;margin: 5px 3px"><a href="{{route('staff.profile')}}"
                            class="staff_nav list-group-item {{ request()->routeIs('stff.profile') ? 'active' : '' }}"
                            style="">Profile Update</a></li>

                </ul>
            @endif
        @endauth
    </div>
    <div class="col-12 d-none d-lg-block col-md-2" style="background: darkblue;height: 100vh;">
        @auth
            @if(auth()->user()->password_changed != null)
                <ul class="list-group list-group  bg-light" style="margin-top: 100px;box-shadow: #5a6268 0px 1px 4px 1px;">
                    <li style="list-style: none;background: darkblue"><a href="{{route('staff.dashboard')}}"
                            class="list-group-item {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}"
                            style="background: darkblue; !important;"> <i class="fa fa-dashboard"></i> Dashboard</a></li>
                    <li style="list-style: none;background: darkblue"><a href="{{route('payroll.request')}}"
                            class="list-group-item {{ request()->routeIs('payroll.request') ? 'active' : '' }}"
                            style="background: darkblue; !important;"> <i class="fa fa-dashboard"></i>Report Request </a></li>
                    <li style="list-style: none;background: darkblue"><a href="{{route('staff.profile')}}"
                            class="list-group-item {{ request()->routeIs('staff.profile') ? 'active' : '' }}"
                            style="background: darkblue; !important;"> <i class="fa fa-dashboard"></i> Profile Update</a></li>
                    {{-- <li style="list-style: none;background: darkblue"><a href="{{route('staff.logout')}}"
                            class="list-group-item {{ request()->routeIs('staff.logout') ? 'active' : '' }}"
                            style="background: darkblue; !important;"> <i class="fa fa-dashboard"></i> Logout</a></li>--}}

                </ul>
            @endif
        @endauth

    </div>

    <div class="col-12 col-md-10" style="background: lightblue">
        <main class="page-content" style="">
            <div class="container-fluid">
                <h2 class="text-uppercase " style="font-size: 20px;text-align: center;">@yield('page_title')</h2>
                <hr>
                <div class="row">
                    <div class="col-12">
                        <div>
                            @yield('body')
                        </div>
                    </div>

                </div>
            </div>
        </main>
    </div>

    @endcannot

</div>
<script>
    function myFunction() {
        var x = document.getElementById("sidebar");
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
            x.style.transition = "all 2s";
        }
    }
</script>