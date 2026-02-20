@extends('components.layouts.app')
@section('content')
    <style>
        .grey-bg {
            /*background-color: #F5F7FA;*/
        }

        @media (max-width: 920px) {
            .media-body {
                text-align: center;
            }
        }

        @media (min-width: 920px) {
            .media-body {
                text-align: right;
            }
        }
    </style>
    <link rel="stylesheet" type="text/css"
        href="https://pixinvent.com/stack-responsive-bootstrap-4-admin-template/app-assets/fonts/simple-line-icons/style.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://pixinvent.com/stack-responsive-bootstrap-4-admin-template/app-assets/css/colors.min.css">

    <div class="grey-bg container-fluid" style="min-height: 100vh; padding-bottom: 50px;">
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
                    {{-- <h4 class="text-uppercase">Statistics With Subtitle</h4>--}}
                    <p>Active Payroll Total.</p>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-2 px-1 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media d-flex">
                                    {{-- <div class="align-self-center">--}}
                                        {{-- <i class="icon-pointer danger font-large-2 float-left"></i>--}}
                                        {{-- </div>--}}
                                    <div class="media-body">
                                        <h5> &#8358;{{number_format(\App\Models\SalaryUpdate::sum('basic_salary'), 2)}}</h5>
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
                                    {{-- <div class="align-self-center">--}}
                                        {{-- <i class="icon-pointer danger font-large-2 float-left"></i>--}}
                                        {{-- </div>--}}
                                    <div class="media-body">
                                        <h5> &#8358;{{number_format(\App\Models\SalaryUpdate::sum('salary_arears'), 2)}}
                                        </h5>
                                        <span style="background: silver" class="d-block p-2 text-center">Salary
                                            Arears</span>
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
                                    {{-- <div class="align-self-center">--}}
                                        {{-- <i class="icon-pointer danger font-large-2 float-left"></i>--}}
                                        {{-- </div>--}}
                                    <div class="media-body">
                                        <h5> &#8358;{{number_format(\App\Models\SalaryUpdate::sum('total_allowance'), 2)}}
                                        </h5>
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
                                    {{-- <div class="align-self-center">--}}
                                        {{-- <i class="icon-pointer danger font-large-2 float-left"></i>--}}
                                        {{-- </div>--}}
                                    <div class="media-body">
                                        <h5> &#8358;{{number_format(\App\Models\SalaryUpdate::sum('gross_pay'), 2)}}</h5>
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
                                    {{-- <div class="align-self-center">--}}
                                        {{-- <i class="icon-pointer danger font-large-2 float-left"></i>--}}
                                        {{-- </div>--}}
                                    <div class="media-body">
                                        <h5> &#8358;{{number_format(\App\Models\SalaryUpdate::sum('total_deduction'), 2)}}
                                        </h5>
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
                                    {{-- <div class="align-self-center">--}}
                                        {{-- <i class="icon-pointer danger font-large-2 float-left"></i>--}}
                                        {{-- </div>--}}
                                    <div class="media-body">
                                        <h5> &#8358;{{number_format(\App\Models\SalaryUpdate::sum('net_pay'), 2)}}</h5>
                                        <span style="background: silver" class="d-block p-2 text-center">Net Pay</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

        </section>

        {{-- VISUAL ANALYTICS SECTION --}}
        <section id="visual-analytics" class="mt-4">
            <div class="row">
                <div class="col-12 mt-1 mb-2">
                    <!-- <h4 class="text-uppercase" style="color: #333 !important;">Visual Analytics</h4> -->
                    <p>Overview of payroll trends and staff distribution.</p>
                </div>
            </div>

            <div class="row">
                <!-- CHART 1: Monthly Trend -->
                <div class="col-lg-8 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title text-dark">Monthly Net Payroll Trend (Last 12 Months)</h4>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body">
                                <canvas id="chart-trend" height="100"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CHART 2: Staff by Department -->
                <div class="col-lg-4 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title text-dark">Staff by Department</h4>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body">
                                <canvas id="chart-dept" height="230"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <!-- CHART 3: Staff by Grade Level -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title text-dark">Staff Distribution by Grade Level</h4>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body">
                                <canvas id="chart-grade" height="80"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Scripts for Charts --}}
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // DATA PREPARATION
                // 1. Trend Data
                const trendLabels = {!! json_encode($salaryTrend->pluck('month_year')) !!};
                const trendData = {!! json_encode($salaryTrend->pluck('total')) !!};

                // 2. Dept Data
                const deptLabels = {!! json_encode($staffByDept->pluck('department_name')) !!};
                const deptData = {!! json_encode($staffByDept->pluck('total')) !!};

                // 3. Grade Data
                const gradeLabels = {!! json_encode($staffByGrade->pluck('grade_level')) !!};
                const gradeData = {!! json_encode($staffByGrade->pluck('total')) !!};

                // --- CHART 1: TREND (Line) ---
                new Chart(document.getElementById('chart-trend'), {
                    type: 'line',
                    data: {
                        labels: trendLabels,
                        datasets: [{
                            label: 'Total Net Pay (₦)',
                            data: trendData,
                            borderColor: '#00B5B8',
                            backgroundColor: 'rgba(0, 181, 184, 0.2)',
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: { beginAtZero: true }
                        }
                    }
                });

                // --- CHART 2: DEPT (Doughnut) ---
                new Chart(document.getElementById('chart-dept'), {
                    type: 'doughnut',
                    data: {
                        labels: deptLabels,
                        datasets: [{
                            label: 'Staff Count',
                            data: deptData,
                            backgroundColor: [
                                '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40',
                                '#E7E9ED', '#71B37C', '#E6B0AA', '#D7BDE2'
                            ],
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { position: 'bottom' }
                        }
                    }
                });

                // --- CHART 3: GRADE (Bar) ---
                new Chart(document.getElementById('chart-grade'), {
                    type: 'bar',
                    data: {
                        labels: gradeLabels,
                        datasets: [{
                            label: 'Staff Count per Grade',
                            data: gradeData,
                            backgroundColor: '#FF6384',
                            // borderColor: '#FF6384',
                            // borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: { beginAtZero: true }
                        }
                    }
                });
            });
        </script>
    </div>
@endsection
@section('title')
    Dashboard
@endsection
@section('page_title')
    Payroll Management System Dashboard
@endsection