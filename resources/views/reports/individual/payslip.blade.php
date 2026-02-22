<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        * {
            font-size: 12px;
            font-family: "Times New Roman";
        }

        th,
        td {
            margin-top: 0;
            padding: 2px;
            /*border: 1px solid black;*/

        }

        div.page_break {
            page-break-before: always;
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

        sup {
            color: orangered;
        }
    </style>
    <title>Pay Slip</title>

</head>

<body style="padding: 0 5% !important;">
    <div id="footer">
        <p class="page">Page </p>
    </div>
    <?php
    $allowances = \App\Models\Allowance::where('status', 1)
        ->whereNotIn('id', [9, 10])
        ->get();
    $deductions = \App\Models\Deduction::where('status', 1)->get();
    ?>

    @forelse($payslips as $paySlip)
        <?php
        $a = explode(" ", $paySlip->deduction_countdown);
        $sorts = collect($a)->sort();
        $loan = array();
        $search = "(";
        foreach ($sorts as $sort) {
            $loan[\Illuminate\Support\Str::before($sort, $search)] = "(" . \Illuminate\Support\Str::after($sort, '(');
        }
        ?>
        <table style="font-weight:bolder;margin:30px 9px 9px 9px ;width: 100%">
            <thead>
                <tr>
                    <td style="max-width: 60px !important;"><img src="{{public_path('storage/' . app_settings()->logo)}}"
                            alt="" style="width: 70px;position: relative;"></td>
                    <td style="text-align: left;padding: 0 !important;">

                        <p style="padding: 0 !important;text-align: center;margin: 0">{{app_settings()->name}}</p>
                        <p style="padding: 0 !important;text-align: center;margin: 0">{{address()}}</p>
                        <p style="padding: 0 !important;text-align: center;">Employee Pay Slip for the Month of
                            {{$paySlip->salary_month}}, {{$paySlip->salary_year}}</p>
                    </td>
                    <td style="width: 60px !important;">&nbsp;</td>
                </tr>
                {{-- <tr>--}}
                    {{-- <td rowspan="3" style="max-width: 60px !important;"> <img
                            src="{{public_path('storage/'.app_settings()->logo)}}" alt=""
                            style="width: 70px;position: relative;"></td>--}}
                    {{-- <td style="font-weight: bolder;padding: 0!important;">
                        <p>{{app_settings()->name}}</p>
                    </td>--}}
                    {{-- </tr>--}}
                {{-- <tr>--}}
                    {{-- <td style="padding-left: 5% !important;padding-top: 0 !important;padding-bottom: 0 !important;">
                        <h4 style="padding: 0;margin: 0;">{{address()}}</h4>
                    </td>--}}
                    {{-- </tr>--}}
                {{-- <tr>--}}
                    {{-- <td style="padding: 10px 0 0 5%" colspan="2">
                        <h5 style="padding: 0;margin: 0; ">Employee Pay Slip for the Month of {{$paySlip->salary_month}},
                            {{$paySlip->salary_year}}</h5>
                    </td>--}}
                    {{-- </tr>--}}
            </thead>

        </table>
        <table style="margin: auto;width: 95%">

            <tbody>
                <tr>
                    <td style="padding: 0 0 0px 0 !important;"><b>Name: </b>{{$paySlip->full_name}}</td>
                    <td style=""><b>Staff Id: </b>{{$paySlip->pf_number}}</td>
                    <td><b>Payroll Id: </b>{{$paySlip->ip_number}}</td>
                </tr>
                <tr>
                    <td>PFA: {{pfa($paySlip->pfa_name)}} <br>Department: {{$paySlip->department}}</td>
                    <td style="">Pension Pin: {{$paySlip->pfa_name}} <br>Bank Name: {{$paySlip->bank_name}}</td>
                    <td>{{$paySlip->salary_structure}} {{$paySlip->grade_level}}{{"/"}}{{$paySlip->step}}</span> <br>Acc
                        No.: {{$paySlip->account_number}}</td>
                </tr>
                <tr>
                    <td style="padding:2px 0 15px 0 !important;">Tax ID: </td>
                    <td style="padding: 2px !important;margin: 0 !important;">Union: </td>
                </tr>
            </tbody>
        </table>


        <table style="width: 75%;margin-left: 3%;font-size: 13px !important;">

            @php
                $empProfile = \App\Models\EmployeeProfile::where('staff_number', $paySlip->pf_number)->first();
                $step = $empProfile ? $empProfile->step : $paySlip->step;
            @endphp

            <tbody>
                <tr>
                    <td style="margin-top: 40px;">Basic Sal: <br> Sal Arrears: </td>
                    <td style="text-align: right"> {{round($paySlip->basic_salary, 2)}}
                        <br>{{number_format($paySlip->salary_areas, 2)}}</td>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>

                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td colspan="2"><b>Allowances</b></td>
                    <td>&nbsp;</td>
                    <td colspan="2"><b>Deductions</b></td>
                </tr>

                @php
                    $max = max($allowances->count(), $deductions->count());
                @endphp

                @for($i = 0; $i < $max; $i++)
                    <tr>
                        @if(isset($allowances[$i]))
                            <td>{{ $allowances[$i]->allowance_name }}:</td>
                            <td style="text-align: right">{{ number_format($paySlip->{'A' . $allowances[$i]->id}, 2) }}</td>
                        @else
                            <td></td>
                            <td></td>
                        @endif

                        <td>&nbsp;</td>

                        @if(isset($deductions[$i]))
                            <td>{{ $deductions[$i]->deduction_name }}:</td>
                            <td style="text-align: right">{{ number_format($paySlip->{'D' . $deductions[$i]->id}, 2) }}
                                <sup>@if(array_key_exists('D' . $deductions[$i]->id, $loan)){{$loan['D' . $deductions[$i]->id]}}@endif</sup>
                            </td>
                        @else
                            <td></td>
                            <td></td>
                        @endif
                    </tr>
                @endfor

                <tr>
                    <td style="font-weight: bolder" colspan="4"><b>Gross Pay:{{number_format($paySlip->gross_pay, 2)}}</b>
                    </td>
                </tr>
                <tr>
                    <td style="font-weight: bolder" colspan="4"><b>Total
                            Ded:{{number_format($paySlip->total_deduction, 2)}}</b></td>
                </tr>
                <tr>
                    <td style="font-weight: bolder" colspan="4"><b>Net Pay: {{number_format($paySlip->net_pay, 2)}}</b></td>

                </tr>
            </tbody>
        </table>
    @empty
        No record found
    @endforelse





</body>

</html>