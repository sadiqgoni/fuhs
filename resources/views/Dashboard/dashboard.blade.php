@extends('components.layouts.app')
@section('content')
    <style>
        .grey-bg {
            /*background-color: #F5F7FA;*/
        }
        @media (max-width: 920px) {
            .media-body{
                text-align: center;
            }
        }
        @media (min-width: 920px) {
            .media-body{
                text-align: right;
            }
        }
    </style>
    <link rel="stylesheet" type="text/css" href="https://pixinvent.com/stack-responsive-bootstrap-4-admin-template/app-assets/fonts/simple-line-icons/style.min.css">
    <link rel="stylesheet" type="text/css" href="https://pixinvent.com/stack-responsive-bootstrap-4-admin-template/app-assets/css/colors.min.css">

    <div class="grey-bg container-fluid" style="height: 80vh">
        <section id="minimal-statistics">
            <div class="row">
                <div class="col-12 mt-3 mb-1">
                    <p>Record Statistics</p>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-2 px-1 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media d-flex">
                                    <div class="align-self-center">
                                        <i class="icon-users primary font-large-2 float-left"></i>
                                    </div>
                                    <div class="media-body">
                                        <h3>{{\App\Models\EmployeeProfile::count()}}</h3>
                                        <span>Employees</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 px-1 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media d-flex">
                                    <div class="align-self-center">
                                        <i class="fa fa-align-center warning font-large-2 float-left"></i>
                                    </div>
                                    <div class="media-body">
                                        <h3>{{\App\Models\Unit::count()}}</h3>
                                        <span>Units</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 px-1 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media d-flex">
                                    <div class="align-self-center">
                                        <i class="fa fa-universal-access success font-large-2 float-left"></i>
                                    </div>
                                    <div class="media-body">
                                        <h3>{{\App\Models\Department::count()}}</h3>
                                        <span>Departments</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 px-1 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media d-flex">
                                    <div class="align-self-center">
                                        <i class="fa fa-bank danger font-large-2 float-left"></i>
                                    </div>
                                    <div class="media-body">
                                        <h3>{{\App\Models\Bank::count()}}</h3>
                                        <span>Banks</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 px-1 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media d-flex">
                                    <div class="align-self-center">
                                        <i class="fa fa-usb danger font-large-2 float-left"></i>
                                    </div>
                                    <div class="media-body">
                                        <h3>{{\App\Models\PFA::count()}}</h3>
                                        <span>PFAs</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 px-1 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media d-flex">
                                    <div class="align-self-center">
                                        <i class="icon-pointer danger font-large-2 float-left"></i>
                                    </div>
                                    <div class="media-body">
                                        <h3>{{\App\Models\SalaryStructure::count()}}</h3>
                                        <span>Salary Structure</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



        </section>

        <section id="stats-subtitle">
            <div class="row">
                <div class="col-12 mt-3 mb-1">
{{--                    <h4 class="text-uppercase">Statistics With Subtitle</h4>--}}
                    <p>Active Payroll Total.</p>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-2 px-1 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media d-flex">
                                    {{--                                    <div class="align-self-center">--}}
                                    {{--                                        <i class="icon-pointer danger font-large-2 float-left"></i>--}}
                                    {{--                                    </div>--}}
                                    <div class="media-body">
                                        <h5> &#8358;{{number_format(\App\Models\SalaryUpdate::sum('basic_salary'),2)}}</h5>
                                        <span style="background: silver" class="d-block p-2 text-center">Basic Salary</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 px-1 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media d-flex">
                                    {{--                                    <div class="align-self-center">--}}
                                    {{--                                        <i class="icon-pointer danger font-large-2 float-left"></i>--}}
                                    {{--                                    </div>--}}
                                    <div class="media-body">
                                        <h5> &#8358;{{number_format(\App\Models\SalaryUpdate::sum('salary_arears'),2)}}</h5>
                                        <span style="background: silver" class="d-block p-2 text-center">Salary Arears</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 px-1 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media d-flex">
                                    {{--                                    <div class="align-self-center">--}}
                                    {{--                                        <i class="icon-pointer danger font-large-2 float-left"></i>--}}
                                    {{--                                    </div>--}}
                                    <div class="media-body">
                                        <h5> &#8358;{{number_format(\App\Models\SalaryUpdate::sum('total_allowance'),2)}}</h5>
                                        <span style="background: silver" class="d-block text-center p-2">Allowances</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 px-1 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media d-flex">
                                    {{--                                    <div class="align-self-center">--}}
                                    {{--                                        <i class="icon-pointer danger font-large-2 float-left"></i>--}}
                                    {{--                                    </div>--}}
                                    <div class="media-body">
                                        <h5> &#8358;{{number_format(\App\Models\SalaryUpdate::sum('gross_pay'),2)}}</h5>
                                        <span style="background: silver" class="d-block text-center p-2">Gross Pay</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 px-1 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media d-flex">
                                    {{--                                    <div class="align-self-center">--}}
                                    {{--                                        <i class="icon-pointer danger font-large-2 float-left"></i>--}}
                                    {{--                                    </div>--}}
                                    <div class="media-body">
                                        <h5> &#8358;{{number_format(\App\Models\SalaryUpdate::sum('total_deduction'),2)}}</h5>
                                        <span style="background: silver" class="d-block text-center p-2">Deduction</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 px-1 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media d-flex">
                                    {{--                                    <div class="align-self-center">--}}
                                    {{--                                        <i class="icon-pointer danger font-large-2 float-left"></i>--}}
                                    {{--                                    </div>--}}
                                    <div class="media-body">
                                        <h5> &#8358;{{number_format(\App\Models\SalaryUpdate::sum('net_pay'),2)}}</h5>
                                        <span style="background: silver" class="d-block p-2 text-center">Net Pay</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

        </section>
    </div>
@endsection
@section('title')
    Dashboard
@endsection
@section('page_title')
    Payroll Management System Dashboard
@endsection
