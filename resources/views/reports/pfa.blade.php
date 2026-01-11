<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PFA Report</title>
    <style>
        *{
            font-size: 12px !important;
            font-family: "Times New Roman";
        }
        table td{
            /*border-top:0 !important;*/
            /*border-left:0 !important;*/
            /*border-right:0 !important;*/
            padding: 3px 2px !important;
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
        #footer { position: fixed; right: 0px; bottom: 10px; text-align: center;border-top: 1px solid black;}
        #footer .page:after { content: counter(page, decimal); }

        #header{
            position: fixed;
            top:0;
            left:0;
            width:100%;
            /*color:#CCC;*/
            /*background:#333;*/
            /*padding:20px;*/
            margin-bottom: 20px;
            height: 40px;
            border: 0 !important;
        }
        .header, .header-space,
        .footer, .footer-space {
            /*height: 80px;*/
            border: 0 !important;
        }
        th,td{
            border: 1px solid black;
        }
    </style>
</head>
<body style="padding: 0 75px">
<div id="footer">
    <p class="page">Page </p>
</div>
<div id="header">
    <img src="{{public_path('storage/'.app_settings()->logo)}}" alt="" style="width: 50px;position: absolute;left: 80px">
    <h3 style="padding: 0;margin: 0;text-align: center;text-transform: uppercase">{{app_settings()->name}}</h3>
    <h4 style="padding: 0;margin: 0;text-align: center">{{address()}}</h4>

    <p style="padding: 10px;margin: 0;text-align: center">Pension Deduction Schedule for the month of {{\Illuminate\Support\Carbon::parse($date)->format('F Y')}} </p>
{{--    <p style="margin-top: 10px;margin-bottom: 0;padding: 0;margin-left: 75px">The  Manager</p>--}}
</div>

@foreach($reports as $index=>$report)
    @php
       $pn=\App\Models\PFA::findOrFail($index);
    @endphp
    <table style="width: 100%;border-collapse: collapse;font-size: 12px;">
        <thead>
        <tr style="border: 0 !important;">
            <td colspan="5" style=" border: 0 !important;">
                <div class="header-space">
                    <p style="padding: 0;margin-top: 90px !important;">{{$pn->name}} </p>

                </div>
            </td>
        </tr>
        <tr>
            <th>SN</th>
            <th>Payroll No. </th>
            <th>Staff Name </th>
            <th>Pension Pin</th>
            <th>Amount</th>
        </tr>
        </thead>
        @php
            $counter=1;
$g_total=0;
        @endphp
        <tbody>

        @forelse($report as $report)
            @php
                $deductions=\App\Models\Deduction::where('status','1')->get();
            $total=0

            @endphp
            <tr>
                <td>{{$counter}}</td>
                <td>{{$report->ip_number}}</td>
                <td>{{$report->full_name}}</td>
                <td>{{$report->pension_pin}}</td>
                <td>
                    @foreach($deductions as $deduction)
                        {{--                {{dd($report["D$deduction->id"])}}--}}
                        @php
                            $total+=$report["D$deduction->id"]
                        @endphp
                    @endforeach
                    {{number_format($total,2)}}

                </td>
            </tr>
            @php $counter++;$g_total+=$total @endphp


        @empty

        @endforelse

<tr>
    <td></td>
    <td></td>
    <td></td>
    <th>Total</th>
    <th>{{number_format($g_total,2)}}</th>
</tr>
        </tbody>


    </table>
    @if (!$loop->last)
        <p style="page-break-before: always"></p>
    @endif


@endforeach


</body>
</html>
