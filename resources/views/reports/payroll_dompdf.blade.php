<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Payroll Report</title>
    <style>
        * {
            font-family: "Times New Roman";
            font-size: 12px;
        }

        table td {
            border-top: 0 !important;
            border-left: 0 !important;
            border-right: 0 !important;
            text-align: center;
        }

        .border-none {
            border-collapse: collapse;
            border: none;
        }

        .border-none td {
            border: 1px solid black;
        }

        .border-none tr:first-child td {
            /*border-top: none;*/
        }

        .border-none tr:last-child td {
            /*border-bottom: none;*/
        }

        .border-none tr td:first-child {
            /*border-left: none;*/
        }

        .border-none tr td:last-child {
            /*border-right: none;*/
        }

        #footer {
            position: fixed;
            right: 0px;
            bottom: 10px;
            text-align: center;
            border-top: 1px solid black;
        }

        #footer .page:after {
            content: counter(page, decimal);
        }

        @page {
            margin: 20px 30px 40px 50px;
        }

        div .page_break {
            page-break-before: always !important;
        }
    </style>
</head>
{{--onload="window.print()"--}}

<body>
    <div id="footer">
        <p class="page">Page </p>
    </div>
    {{--<img src="{{public_path(logo())}}" alt="" style="width: 50px;position: absolute;right: 70px">--}}
    <img src="{{public_path('storage/' . app_settings()->logo)}}" alt=""
        style="width: 50px;position: absolute;left: 500px">
    <h3 style="padding: 0;margin: 0;text-align: center;text-transform: uppercase">{{app_settings()->name}}</h3>
    <h4 style="padding: 0;margin: 0;text-align: center">{{address()}}</h4>
    <br>
    @php
        $payroll_date = $payrolls->first();
    @endphp
    <h3 style="text-align: center">Employee Payroll for the month of @if($payrolls->count() > 0)
    {{$payroll_date[0]['salary_month']}}, {{$payroll_date[0]['salary_year']}}@endif
    </h3>


    <table class="n-bordered border-none"
        style="width:100% !important;font-size: 12px;border-collapse: collapse;margin-bottom: -20px !important;"
        border="1">
        @php
            $counter = 1;
        @endphp

        @forelse($payrolls as $key => $payroll)
            @php

                $reports = $payroll;
                $group = $payroll->first();
                $a = $group[$name_search];
                $allowances = \App\Models\Allowance::where('status', 1)->get();
                $deductions = \App\Models\Deduction::where('status', 1)->get();
             @endphp


            <tr style="border:0 !important">
                <td colspan="21" style="padding: 0;border:0 !important;text-align: left;text-transform: capitalize">
                    <h3 style="font-size: 12px;margin:20px 0 0 0 !important;">
                        <span style="text-transform: capitalize"> {{$name}}:</span> {{$a}}
                    </h3>
                </td>
            </tr>
            <tr>
                <th class="hidden-xs">Sn</th>
                <th>Staff Id <br>Name</th>
                <th>Payroll Id<br />Cons</th>
                <th>Basic Sal<br />Salary Arr</th>

                @foreach($allowances->chunk(2) as $chunk)
                    <th>
                        @foreach($chunk as $allowance)
                            {{ $allowance->allowance_name }} @if(!$loop->last) <br /> @endif
                        @endforeach
                    </th>
                @endforeach

                @foreach($deductions->chunk(2) as $chunk)
                    <th>
                        @foreach($chunk as $deduction)
                            {{ $deduction->deduction_name }} @if(!$loop->last) <br /> @endif
                        @endforeach
                    </th>
                @endforeach

                <th>Bank Name <br /> Acc No</th>
                <th>Gross <br /> Total Ded</th>
                <th>Netpay</th>

            </tr>


            <tbody>
                @forelse($reports as $index => $report)
                    @php
                        $emp = \App\Models\EmployeeProfile::where('staff_number', $report->pf_number)->first();
                     @endphp

                    <tr>
                        <td>
                            {{$counter++}}
                        </td>
                        <td style="text-align: left">{{$report->pf_number}} <br /> {{$report->full_name}}</td>
                        <td>{{$report->ip_number}}<br>grade{{$report->grade_level . "/" . $report->step}}</td>

                        <td>{{number_format($report->basic_salary, 2)}} <br> {{number_format($report->salary_areas, 2)}}</td>

                        @foreach($allowances->chunk(2) as $chunk)
                            <td>
                                @foreach($chunk as $allowance)
                                    {{ number_format($report->{'A' . $allowance->id}, 2) }} @if(!$loop->last) <br /> @endif
                                @endforeach
                            </td>
                        @endforeach

                        @foreach($deductions->chunk(2) as $chunk)
                            <td>
                                @foreach($chunk as $deduction)
                                    {{ number_format($report->{'D' . $deduction->id}, 2) }} @if(!$loop->last) <br /> @endif
                                @endforeach
                            </td>
                        @endforeach

                        <td> {{$report->bank_name}} <br>{{$report->account_number}}</td>
                        <td>{{number_format($report->gross_pay, 2)}}<br />{{number_format($report->total_deduction, 2)}}</td>
                        <td>{{number_format($report->net_pay, 2)}}</td>
                    </tr>
                @empty

                @endforelse
                <tr>
                    <td colspan="15"></td>
                    <th colspan="2" style="text-align: right">Subtotal: </th>
                    <th colspan="2">{{number_format($reports->sum('net_pay'), 2)}}</th>
                </tr>
            </tbody>

        @empty

        @endforelse
    </table>
    <div class="page_break"></div>

    <div style="margin-top: 200px"></div>

    <h3 style="padding: 0;margin: 0;text-align: center;text-transform: uppercase">{{config('app.name')}}</h3>
    <h4 style="padding: 0;margin: 0;text-align: center">{{address()}}</h4>
    <h3>Payroll Sheet for Staff – @if(isset($payroll_date)){{$payroll_date[0]['salary_month']}},
    {{$payroll_date[0]['salary_year']}}@endif
    </h3>
    <h2>SUMMARY FOR PAYROLL ENTRIES</h2>
    <table style="width: 25%">
        @php
            $allowances = \App\Models\Allowance::all();
            $total = 0;

        @endphp
        <tbody>
            <tr>
                <td>xxxx</td>
                <td>Basic Salary</td>
                <td>@if(isset($summaries)) {{number_format($summaries->sum('basic_salary'), 2)}} @endif</td>
            </tr>
            <tr>
                <td>xxxx</td>
                <td>Salary Arears</td>
                <td>@if(isset($summaries)) {{number_format($summaries->sum('salary_areas'), 2)}} @endif</td>
            </tr>
            @php
                $a = 0;
                $b = 0;
                $c = 0;
                $d = 0;
            @endphp
            @forelse($allowances as $index => $allowance)
                <tr>
                    <td>{{$allowance->code}}</td>
                    <td>{{$allowance->allowance_name}}</td>
                    <td>@if(isset($summaries)){{number_format($summaries->sum('A' . $allowance->id), 2)}}@endif</td>
                </tr>
                @php
                    if ($summaries) {
                        $a = $total += round($summaries->sum('A' . $allowance->id), 2);
                        $b = round($summaries->sum('basic_salary'), 2);
                        $c = round($summaries->sum('salary_areas'), 2);
                        $d = round($a + $b + $c, 2);
                    }
                @endphp
            @empty

            @endforelse
        </tbody>
        {{-- <tfoot>--}}
            <tr>
                <td style="padding: 7px 0 10px 0"></td>
                <th style="padding: 7px 0 10px 0">Gross Pay</th>
                <th style="padding: 7px 0 10px 0">{{number_format($d, 2)}}</th>
            </tr>
            {{--
        </tfoot>--}}
        @php
            $deductions = \App\Models\Deduction::all();
            $total1 = 0;
        @endphp
        <tbody style="margin-top: 30px">
            @forelse($deductions as $index => $deduction)
                <tr>
                    <td>{{$deduction->code}}</td>
                    <td>{{$deduction->deduction_name}}</td>
                    <td>@if(isset($summaries)){{number_format($summaries->sum('D' . $deduction->id), 2)}}@endif</td>
                </tr>
                @php
                    if (isset($summaries)) {
                        $total1 += round($summaries->sum('D' . $deduction->id), 2);
                    }
                @endphp
            @empty

            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td></td>
                <th>Total Deductions</th>
                <th>{{number_format($total1, 2)}}</th>

            </tr>
            <tr>
                <th colspan="3" style="text-align: center">
                    <P style="font-size: 12px">NET SAL PAYABLE: {{number_format($d - $total1, 2)}}</P>
                </th>

            </tr>
            <tr>
                <th style="text-align: left">
                    <p style="margin: 0;padding: 5px 0;font-weight: bold">Inputed By:.............</p>
                    <p style="margin: 0;padding: 5px 0;font-weight: bold">Sign and Date:.............</p>
                </th>
                <th></th>
                <th style="text-align: left">
                    <p style="margin: 0;padding: 5px 0;font-weight: bold">Checked By:.............</p>
                    <p style="margin: 0;padding: 5px 0;font-weight: bold">Sign and Date:.............</p>

                </th>
            </tr>
            <tr>
                <th style="text-align: left">
                    <p style="margin: 0;padding: 5px 0;font-weight: bold">Reviewed By:.............</p>
                    <p style="margin: 0;padding: 5px 0;font-weight: bold">Sing and Date:.............</p>
                </th>
                <th></th>
                <th style="text-align: left">
                    <p style="margin: 0;padding: 5px 0;font-weight: bold">Audited By:.............</p>
                    <p style="margin: 0;padding: 5px 0;font-weight: bold">Sign and Date:.............</p>

                </th>
            </tr>
        </tfoot>
    </table>
    <table style="width: 30%">

    </table>


</body>

</html>