<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>NHIF Report</title>
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
    </style>
</head>
<body style="padding: 0 75px">
<div id="footer">
    <p class="page">Page </p>
</div>
<img src="{{public_path('storage/'.app_settings()->logo)}}" alt="" style="width: 50px;position: absolute;left: 70px">
<h3 style="padding: 0;margin: 0;text-align: center;text-transform: uppercase">{{app_settings()->name}}</h3>
<h4 style="padding: 0;margin: 0;text-align: center">{{address()}}</h4>

<p style="padding: 10px;margin: 0;text-align: center">NHIS Report for the month of {{\Illuminate\Support\Carbon::parse($date)->format('F Y')}} </p>
<table style="width: 100%;border-collapse: collapse;font-size: 12px;margin-top: 25px" border="1">
    <thead>
    <tr>
        <th>SN</th>
        <th>Payroll No. </th>
        <th>Staff Name </th>
        <th>Amount</th>
    </tr>
    </thead>
    @php
        $counter=1;
    @endphp
    <tbody>
    @forelse($reports as $report)
        @php
            $deductions=\App\Models\Deduction::where('status','1')->get();
        $total=0

        @endphp
        <tr>
            <td>{{$counter}}</td>
            <td>{{$report->ip_number}}</td>
            <td>{{$report->full_name}}</td>
{{--            <td>{{$report->pension_pin}}</td>--}}
            <td>
                {{number_format($report->nhis,2)}}
            </td>
        </tr>
        @php $counter++ @endphp
    @empty

    @endforelse
    <tr>
        <td colspan="2"></td>
        <th style="text-align: right;padding:10px">Total</th>
        <th>{{number_format($reports->sum('nhis'),2)}}</th>
    </tr>

    </tbody>

</table>

</body>
</html>
