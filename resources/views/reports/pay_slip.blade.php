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
            padding: 1px 2px;
            /*border: 1px solid black;*/

        }

        .page_break {
            page-break-before: always !important;
        }

        #footer {
            position: fixed;
            right: 0;
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
{{--

<body>--}}

    <body style="padding: 0 5% !important;">
        <div id="footer">
            <p class="page">Page </p>
        </div>
        <?php
$allowance = App\Models\Allowance::all();

?>
        @forelse($paySlips as $pay)

            @forelse($pay as $paySlip)
                {{-- @dd($loop->iteration)--}}
                <?php
                //        $allowance=\App\Models\Allowance::find($paySlip);
                //        dd($allowance)

                $a = explode(" ", $paySlip->deduction_countdown);
                $sorts = collect($a)->sort();
                //        $str = preg_replace('/[^0-9.]+/', '', $str);
                $loan = array();
                $search = "(";
                foreach ($sorts as $sort) {
                    $loan[\Illuminate\Support\Str::before($sort, $search)] = "(" . \Illuminate\Support\Str::after($sort, '(');
                }
                ?>
                {{-- <!--@if($loop->iteration %2 ==0)-->--}}
                {{-- <div style="margin: 10px 0"></div>--}}
                {{-- <!--@endif-->--}}
                {{--<img src="{{public_path(logo())}}" alt="" style="width: 50px;position: relative;float:left;">--}}



                {{-- <br>--}}
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

                    </thead>

                </table>
                <table style="margin: auto;width: 95%">

                    <tbody>
                        <tr>
                            <td style="padding: 0 0 7px 0 !important;"><b>Name: </b>{{$paySlip->full_name}}</td>
                            <td style=""><b>Staff Id: </b>{{$paySlip->pf_number}}</td>
                            <td><b>Payroll Id: </b>{{$paySlip->ip_number}}</td>
                        </tr>
                        <tr>
                            <td>PFA: <span style="text-transform: capitalize">{{pfa($paySlip->pfa_name)}}</span>
                                <br>Department: {{$paySlip->department}}
                                <br><span style="padding:10px 0 15px 0 !important;">Tax ID: </span>
                            </td>
                            <td style="">Pension Pin: {{$paySlip->pfa_name}} <br>Bank Name: {{$paySlip->bank_name}}
                                <br> <span style="">Union: </span>

                            </td>
                            <td>{{$paySlip->salary_structure}} {{$paySlip->grade_level}}{{"/"}}{{$paySlip->step}} <br>Acc No.:
                                {{$paySlip->account_number}}</td>
                        </tr>
                        {{-- <tr>--}}
                            {{-- --}}
                            {{-- </tr>--}}
                    </tbody>
                </table>


                <table style="width: 75%;margin-left: 3%;font-size: 13px !important;">

                    @php
                        $step = \App\Models\EmployeeProfile::where('staff_number', $paySlip->pf_number)->first()->step;
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
                            <td style="font-weight: bolder;padding-top: 20px !important;" colspan="4"><b>Gross
                                    Pay:{{number_format($paySlip->gross_pay, 2)}}</b></td>
                        </tr>
                        <tr>
                            <td style="font-weight: bolder" colspan="4"><b>Total
                                    Ded:{{number_format($paySlip->total_deduction, 2)}}</b></td>
                        </tr>
                        <tr>
                            <td style="font-weight: bolder" colspan="4"><b>Net Pay: {{number_format($paySlip->net_pay, 2)}}</b>
                            </td>

                        </tr>
                    </tbody>
                </table>




            @empty

            @endforelse
            @if($loop->iteration % 2 == 1)
                {{-- <div class="page_break"></div>--}}
                {{-- <p style="page-break-before: always"></p>--}}

            @endif

        @empty
            <div style="color:red;">No Record</div>
        @endforelse

        {{--
        <script type="text/php">--}}
{{--    if ( isset($pdf) ) {--}}
{{--        $font = Font_Metrics::get_font("helvetica", "bold");--}}
{{--        $pdf->page_text(72, 18, "Header: {PAGE_NUM} of {PAGE_COUNT}",--}}
{{--                        $font, 6, array(0,0,0));--}}
{{--    }--}}
{{--</script>--}}
    </body>

</html>