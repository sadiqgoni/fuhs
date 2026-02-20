<style>
    #sidebar {
        transition: 0.5s ease-in-out;
        animation: ease-in-out 0.5s;
    }

    .page-content {
        margin-top: 70px;
    }

    @media (max-width:920px) {
        .page-content {
            margin-top: 20px;
        }
    }

    /* Dashboard active link: visible on dark blue background */
    .list-group-item.active {
        background-color: rgba(255,255,255,0.25) !important;
        color: #fff !important;
        border-color: transparent !important;
        font-weight: 700;
    }

    .list-group {
        color: white;
        background: none;
    }

    .list-group a {
        color: white;
        background: none;
    }

    .staff_nav a {
        background: black;
        cursor: pointer;
    }

    /* ========== ACCORDION SIDEBAR STYLES ========== */
    .sidebar-parent-btn {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        padding: 10px 16px;
        background: none;
        border: none;
        border-bottom: 1px solid rgba(255,255,255,0.1);
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        letter-spacing: 0.5px;
        cursor: pointer;
        text-align: left;
        transition: background 0.2s;
    }
    .sidebar-parent-btn:hover,
    .sidebar-parent-btn.sidebar-active {
        background: rgba(255,255,255,0.15);
    }
    .sidebar-arrow {
        font-size: 11px;
        transition: transform 0.3s ease;
        flex-shrink: 0;
    }
    .sidebar-arrow.rotated {
        transform: rotate(180deg);
    }
    .sidebar-sub-menu {
        background: rgba(0,0,80,0.4);
        padding: 4px 0;
    }
    .sidebar-sub-item {
        display: block;
        padding: 7px 16px 7px 32px;
        color: rgba(255,255,255,0.85) !important;
        font-size: 12.5px;
        text-decoration: none;
        border-bottom: 1px solid rgba(255,255,255,0.05);
        transition: background 0.2s, color 0.2s;
    }
    .sidebar-sub-item:hover {
        background: rgba(255,255,255,0.12);
        color: #fff !important;
        text-decoration: none;
    }
    .sidebar-sub-active {
        background: rgba(255,255,255,0.22) !important;
        color: #fff !important;
        font-weight: 700;
        border-left: 3px solid #7ecfff;
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
            <div class="col-12 d-none d-lg-block col-md-2" style="background: darkblue; min-height: 100vh; padding: 0;">
                <div style="position: sticky; top: 0; min-height: 100vh; background: darkblue;">
                    <ul class="list-group" style="margin-top: 100px; padding-bottom: 40px; padding-left: 8px; padding-right: 0;">

                        <li style="list-style: none; background: darkblue;">
                            <a href="{{route('dashboard')}}"
                                class="list-group-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                                style="background: darkblue; color: white !important; border: none; border-bottom: 1px solid rgba(255,255,255,0.1); padding-left: 16px;">
                                <i class="fa fa-dashboard"></i> Dashboard
                            </a>
                        </li>

                        @can('employee_setting')
                            <li style="list-style: none;">
                                <button class="sidebar-parent-btn {{ $isEmployeeActive ? 'sidebar-active' : '' }}"
                                    data-toggle="collapse" data-target="#collapseEmployees"
                                    aria-expanded="{{ $isEmployeeActive ? 'true' : 'false' }}">
                                    <span><i class="fa fa-user-times"></i> EMPLOYEES</span>
                                    <i class="fa fa-chevron-down sidebar-arrow {{ $isEmployeeActive ? 'rotated' : '' }}"></i>
                                </button>
                            <div class="collapse {{ $isEmployeeActive ? 'show' : '' }}" id="collapseEmployees">
                                    <div class="sidebar-sub-menu">
                                        <a class="sidebar-sub-item {{ request()->routeIs('employee.profile') ? 'sidebar-sub-active' : '' }}" href="{{route('employee.profile')}}">Employee Profile</a>
                                        <a class="sidebar-sub-item {{ request()->routeIs('employee.type') ? 'sidebar-sub-active' : '' }}" href="{{route('employee.type')}}">Employment Type</a>
                                        <a class="sidebar-sub-item {{ request()->routeIs('staff.category') ? 'sidebar-sub-active' : '' }}" href="{{route('staff.category')}}">Staff Category</a>
                                        <a class="sidebar-sub-item {{ request()->routeIs('staff.union') ? 'sidebar-sub-active' : '' }}" href="{{route('staff.union')}}">Staff Union</a>
                                        <a class="sidebar-sub-item {{ request()->routeIs('unit') ? 'sidebar-sub-active' : '' }}" href="{{route('unit')}}">Unit</a>
                                        <a class="sidebar-sub-item {{ request()->routeIs('department') ? 'sidebar-sub-active' : '' }}" href="{{route('department')}}">Department</a>
                                        <a class="sidebar-sub-item {{ request()->routeIs('bank') ? 'sidebar-sub-active' : '' }}" href="{{route('bank')}}">Banks</a>
                                        <a class="sidebar-sub-item {{ request()->routeIs('pfa') ? 'sidebar-sub-active' : '' }}" href="{{route('pfa')}}">PFAs</a>
                                        <a class="sidebar-sub-item {{ request()->routeIs('rank') ? 'sidebar-sub-active' : '' }}" href="{{route('rank')}}">Ranks</a>
                                        <a class="sidebar-sub-item {{ request()->routeIs('state') ? 'sidebar-sub-active' : '' }}" href="{{route('state')}}">States</a>
                                        <a class="sidebar-sub-item {{ request()->routeIs('local.govt') ? 'sidebar-sub-active' : '' }}" href="{{route('local.govt')}}">Local Governments</a>
                                        <a class="sidebar-sub-item {{ request()->routeIs('tribe') ? 'sidebar-sub-active' : '' }}" href="{{route('tribe')}}">Tribe</a>
                                        <a class="sidebar-sub-item {{ request()->routeIs('relationship') ? 'sidebar-sub-active' : '' }}" href="{{route('relationship')}}">Relationship</a>
                                    </div>
                                </div>
                            </li>
                        @endcan

                        <li style="list-style: none;">
                            <button class="sidebar-parent-btn {{ $payrollSettingsActive ? 'sidebar-active' : '' }}"
                                data-toggle="collapse" data-target="#collapsePayrollSettings"
                                aria-expanded="{{ $payrollSettingsActive ? 'true' : 'false' }}">
                                <span><i class="fa fa-cogs"></i> PAYROLL SETTINGS</span>
                                <i class="fa fa-chevron-down sidebar-arrow {{ $payrollSettingsActive ? 'rotated' : '' }}"></i>
                            </button>
                            <div class="collapse {{ $payrollSettingsActive ? 'show' : '' }}" id="collapsePayrollSettings">
                                <div class="sidebar-sub-menu">
                                    @can('allowance')<a class="sidebar-sub-item {{ request()->routeIs('available.allowance') ? 'sidebar-sub-active' : '' }}" href="{{route('available.allowance')}}">Allowances</a>@endcan
                                    @can('deduction')<a class="sidebar-sub-item {{ request()->routeIs('available.deduction') ? 'sidebar-sub-active' : '' }}" href="{{route('available.deduction')}}">Deduction</a>@endcan
                                    @can('salary_structure')<a class="sidebar-sub-item {{ request()->routeIs('salary.structure') ? 'sidebar-sub-active' : '' }}" href="{{route('salary.structure')}}">Salary Structure</a>@endcan
                                    @can('allowance_template')
                                        <a class="sidebar-sub-item {{ request()->routeIs('allowance.template') ? 'sidebar-sub-active' : '' }}" href="{{route('allowance.template')}}">Allowance Template</a>
                                        <a class="sidebar-sub-item {{ request()->routeIs('allowance.template.step') ? 'sidebar-sub-active' : '' }}" href="{{route('allowance.template.step')}}">Salary Allowance Table</a>
                                    @endcan
                                    @can('deduction_template')<a class="sidebar-sub-item {{ request()->routeIs('deduction.template') ? 'sidebar-sub-active' : '' }}" href="{{route('deduction.template')}}">Deduction Template</a>@endcan
                                    @can('salary_template')<a class="sidebar-sub-item {{ request()->routeIs('salary.template') ? 'sidebar-sub-active' : '' }}" href="{{route('salary.template')}}">Salary Template</a>@endcan
                                    <a class="sidebar-sub-item {{ request()->routeIs('tax-brackets.index') ? 'sidebar-sub-active' : '' }}" href="{{route('tax-brackets.index')}}">PAYE Calculation Formula</a>
                                </div>
                            </div>
                        </li>

                        <li style="list-style: none;">
                            <button class="sidebar-parent-btn {{ $payrollUpdateActive ? 'sidebar-active' : '' }}"
                                data-toggle="collapse" data-target="#collapsePayrollUpdate"
                                aria-expanded="{{ $payrollUpdateActive ? 'true' : 'false' }}">
                                <span><i class="fa fa-bank"></i> PAYROLL UPDATE</span>
                                <i class="fa fa-chevron-down sidebar-arrow {{ $payrollUpdateActive ? 'rotated' : '' }}"></i>
                            </button>
                            <div class="collapse {{ $payrollUpdateActive ? 'show' : '' }}" id="collapsePayrollUpdate">
                                <div class="sidebar-sub-menu">
                                    @can('monthly_update')<a class="sidebar-sub-item {{ request()->routeIs('salary.update.center') ? 'sidebar-sub-active' : '' }}" href="{{route('salary.update.center')}}">Monthly Update</a>@endcan
                                    @can('group_update')<a class="sidebar-sub-item {{ request()->routeIs('group-salary-update') ? 'sidebar-sub-active' : '' }}" href="{{route('group-salary-update')}}">Group Update</a>@endcan
                                    @can('can_promote')<a class="sidebar-sub-item {{ request()->routeIs('employee.promotion') ? 'sidebar-sub-active' : '' }}" href="{{route('employee.promotion')}}">Staff Promotion</a>@endcan
                                    @can('annual_increment')<a class="sidebar-sub-item {{ request()->routeIs('staff.annual.salary.increment') ? 'sidebar-sub-active' : '' }}" href="{{route('staff.annual.salary.increment')}}">Annual Increment</a>@endcan
                                    @can('loan_deduction')<a class="sidebar-sub-item {{ request()->routeIs('deduction.countdown') ? 'sidebar-sub-active' : '' }}" href="{{route('deduction.countdown')}}">Loan Deduction</a>@endcan
                                    @can('salary_posting')<a class="sidebar-sub-item {{ request()->routeIs('salary.ledger.posting') ? 'sidebar-sub-active' : '' }}" href="{{route('salary.ledger.posting')}}">Salary Posting</a>@endcan
                                </div>
                            </div>
                        </li>

                        <li style="list-style: none;">
                            <button class="sidebar-parent-btn {{ $payrollReportActive ? 'sidebar-active' : '' }}"
                                data-toggle="collapse" data-target="#collapsePayrollReport"
                                aria-expanded="{{ $payrollReportActive ? 'true' : 'false' }}">
                                <span><i class="fa fa-registered"></i> PAYROLL REPORT</span>
                                <i class="fa fa-chevron-down sidebar-arrow {{ $payrollReportActive ? 'rotated' : '' }}"></i>
                            </button>
                            <div class="collapse {{ $payrollReportActive ? 'show' : '' }}" id="collapsePayrollReport">
                                <div class="sidebar-sub-menu">
                                    @can('group_report')<a class="sidebar-sub-item {{ request()->routeIs('payroll.report.center') ? 'sidebar-sub-active' : '' }}" href="{{route('payroll.report.center')}}">For Groups</a>@endcan
                                    @can('individual_report')<a class="sidebar-sub-item {{ request()->routeIs('report.for.individual') ? 'sidebar-sub-active' : '' }}" href="{{route('report.for.individual')}}">For Individual</a>@endcan
                                    @can('nominal_roll')<a class="sidebar-sub-item {{ request()->routeIs('employee.report.center') ? 'sidebar-sub-active' : '' }}" href="{{route('employee.report.center')}}">Nominal Roll</a>@endcan
                                    @can('annual_inc_history')<a class="sidebar-sub-item {{ request()->routeIs('staff.annual.salary.increment.history') ? 'sidebar-sub-active' : '' }}" href="{{route('staff.annual.salary.increment.history')}}">Annual Increment History</a>@endcan
                                    @can('loan_dedc_history')<a class="sidebar-sub-item {{ request()->routeIs('loan.deduction.history') ? 'sidebar-sub-active' : '' }}" href="{{route('loan.deduction.history')}}">Loan Deduction History</a>@endcan
                                    @can('retired_staff')<a class="sidebar-sub-item {{ request()->routeIs('retired.staff') ? 'sidebar-sub-active' : '' }}" href="{{route('retired.staff')}}">Retirement List</a>@endcan
                                    @can('terminated_list')<a class="sidebar-sub-item {{ request()->routeIs('contract.termination') ? 'sidebar-sub-active' : '' }}" href="{{route('contract.termination')}}">Contract Termination List</a>@endcan
                                </div>
                            </div>
                        </li>

                        <li style="list-style: none;">
                            <button class="sidebar-parent-btn {{ $otherReportActive ? 'sidebar-active' : '' }}"
                                data-toggle="collapse" data-target="#collapseOtherReports"
                                aria-expanded="{{ $otherReportActive ? 'true' : 'false' }}">
                                <span><i class="fa fa-plus-square"></i> OTHER REPORTS</span>
                                <i class="fa fa-chevron-down sidebar-arrow {{ $otherReportActive ? 'rotated' : '' }}"></i>
                            </button>
                            <div class="collapse {{ $otherReportActive ? 'show' : '' }}" id="collapseOtherReports">
                                <div class="sidebar-sub-menu">
                                    @can('repository')<a class="sidebar-sub-item {{ request()->routeIs('report.repository') ? 'sidebar-sub-active' : '' }}" href="{{route('report.repository')}}">Report Repository</a>@endcan
                                    @can('backup_history')<a class="sidebar-sub-item {{ request()->routeIs('backup.history') ? 'sidebar-sub-active' : '' }}" href="{{route('backup.history')}}">Backup History</a>@endcan
                                    @can('restore_history')<a class="sidebar-sub-item {{ request()->routeIs('restore.history') ? 'sidebar-sub-active' : '' }}" href="{{route('restore.history')}}">Restore History</a>@endcan
                                    @can('analytic')<a class="sidebar-sub-item {{ request()->routeIs('analytic') ? 'sidebar-sub-active' : '' }}" href="{{route('analytic')}}">Analytics</a>@endcan
                                </div>
                            </div>
                        </li>

                        <li style="list-style: none;">
                            <button class="sidebar-parent-btn {{ $securityActive ? 'sidebar-active' : '' }}"
                                data-toggle="collapse" data-target="#collapseSecurity"
                                aria-expanded="{{ $securityActive ? 'true' : 'false' }}">
                                <span><i class="fa fa-lock"></i> SECURITY</span>
                                <i class="fa fa-chevron-down sidebar-arrow {{ $securityActive ? 'rotated' : '' }}"></i>
                            </button>
                            <div class="collapse {{ $securityActive ? 'show' : '' }}" id="collapseSecurity">
                                <div class="sidebar-sub-menu">
                                    @can('admin_user')<a class="sidebar-sub-item {{ request()->routeIs('admin.user') ? 'sidebar-sub-active' : '' }}" href="{{route('admin.user')}}">User Account</a>@endcan
                                    @can('backup')<a class="sidebar-sub-item {{ request()->routeIs('backup') ? 'sidebar-sub-active' : '' }}" href="{{route('backup')}}">Backup</a>@endcan
                                    @can('restore')<a class="sidebar-sub-item {{ request()->routeIs('restore') ? 'sidebar-sub-active' : '' }}" href="{{route('restore')}}">Restore</a>@endcan
                                    @can('restore_point')<a class="sidebar-sub-item {{ request()->routeIs('restore.point') ? 'sidebar-sub-active' : '' }}" href="{{route('restore.point')}}">Auto Restore Point</a>@endcan
                                    @can('audit_log')<a class="sidebar-sub-item {{ request()->routeIs('audit.log') ? 'sidebar-sub-active' : '' }}" href="{{route('audit.log')}}">Audit Log</a>@endcan
                                </div>
                            </div>
                        </li>

                        <li style="list-style: none;">
                            <button class="sidebar-parent-btn {{ $helpActive ? 'sidebar-active' : '' }}" data-toggle="collapse"
                                data-target="#collapseHelp" aria-expanded="{{ $helpActive ? 'true' : 'false' }}">
                                <span><i class="fa fa-question-circle"></i> HELP</span>
                                <i class="fa fa-chevron-down sidebar-arrow {{ $helpActive ? 'rotated' : '' }}"></i>
                            </button>
                            <div class="collapse {{ $helpActive ? 'show' : '' }}" id="collapseHelp">
                                <div class="sidebar-sub-menu">
                                    <a class="sidebar-sub-item" href="{{route('faq')}}">FAQ</a>
                                    <a class="sidebar-sub-item" href="{{route('help')}}">Help</a>
                                </div>
                            </div>
                        </li>

                    </ul>
                </div>
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

            <li style="list-style: none">
                <div class="dropdown" style="background: darkblue; width:100%;border:0">
                    <button class=" dropdown-toggle {{ $payrollSettingsActive ? 'active bg-primary' : '' }} text-white py-2"
                        style="padding-left:20px;border:none;font-size: 16px !important;width: 100%;text-align: left"
                        type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                    <button class=" dropdown-toggle {{ $payrollUpdateActive ? 'active bg-primary' : '' }} text-white py-2"
                        style="padding-left:20px;border:none;font-size: 16px !important;width: 100%;text-align: left"
                        type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                    <button class=" dropdown-toggle {{ $payrollReportActive ? 'active bg-primary' : '' }} text-white py-2"
                        style="padding-left:20px;border:none;font-size: 16px !important;width: 100%;text-align: left"
                        type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                        @can('loan_dedc_history') <a class="dropdown-item" href="{{route('loan.deduction.history')}}">Loan
                        deduction history</a>@endcan
                        @can('retired_staff') <a class="dropdown-item" href="{{route('retired.staff')}}">Retirement
                        List</a>@endcan
                        @can('terminated_list') <a class="dropdown-item" href="{{route('contract.termination')}}">Contract
                        Termination List</a>@endcan

                    </div>
                </div>
            </li>

            <li style="list-style: none">
                <div class="dropdown" style="background: darkblue;width:100%;border:0">
                    <button class=" dropdown-toggle {{ $otherReportActive ? 'active bg-primary' : '' }} text-white py-2"
                        style="padding-left:20px;border:none;font-size: 16px !important;width: 100%;text-align: left"
                        type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                        type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                        type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
<div class="col-12 d-none d-lg-block col-md-2" style="background: darkblue; min-height: 100vh;">
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
</script>

<script>
    // Mobile menu toggle
    function myFunction() {
        var x = document.getElementById("sidebar");
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
            x.style.transition = "all 2s";
        }
    }

    // Accordion arrow rotation on collapse show/hide
    document.addEventListener('DOMContentLoaded', function () {
        var collapses = document.querySelectorAll('.collapse');
        collapses.forEach(function (el) {
            el.addEventListener('show.bs.collapse', function () {
                var btn = document.querySelector('[data-target="#' + el.id + '"]');
                if (btn) btn.querySelector('.sidebar-arrow').classList.add('rotated');
            });
            el.addEventListener('hide.bs.collapse', function () {
                var btn = document.querySelector('[data-target="#' + el.id + '"]');
                if (btn) btn.querySelector('.sidebar-arrow').classList.remove('rotated');
            });
            // Also handle jQuery-based Bootstrap 4 events
            if (typeof $ !== 'undefined') {
                $(el).on('show.bs.collapse', function () {
                    var btn = $('[data-target="#' + el.id + '"]');
                    btn.find('.sidebar-arrow').addClass('rotated');
                }).on('hide.bs.collapse', function () {
                    var btn = $('[data-target="#' + el.id + '"]');
                    btn.find('.sidebar-arrow').removeClass('rotated');
                });
            }
        });
    });
</script>