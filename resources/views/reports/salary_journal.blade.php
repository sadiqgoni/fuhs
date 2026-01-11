<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Salary Journal</title>
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
    </style>
</head>
<body style="padding: 0 75px">
<img src="{{public_path('storage/'.app_settings()->logo)}}" alt="" style="width: 50px;position: absolute;left: 70px">
<h3 style="padding: 0;margin: 0;text-align: center;text-transform: uppercase">{{app_settings()->name}}</h3>
<h4 style="padding: 0;margin: 0;text-align: center">{{address()}}</h4>
@if($reports->count()>0)
<h3 style="text-align: center;padding:15px 0;margin: 0">Salary Standard Journal for the Month of : {{$reports[0]['salary_month']}} {{$reports[0]['salary_year']}} </h3>
@endif
<table style="width: 100%;border-collapse: collapse;font-size: 12px;">
    <thead style="text-align: left !important;">
    <tr>
        <th style="text-align: left !important;">Code</th>
        <th style="text-align: left !important;">Description</th>
        <th style="text-align: left !important;">Debit Side</th>
        <th style="text-align: left !important;">Credit Side</th>
    </tr>
    </thead>
    <tbody>

    @php
        $allowances=\App\Models\Allowance::get();
        $deductions=\App\Models\Deduction::get();
        //where('code','>',0)->
        //get();
        $allow_total=0;
        $deduct_total=0;
    @endphp
<tr>
    <td>xxxx</td>
    <td>Basic Salary</td>
    <td>{{number_format($reports->sum('basic_salary'),2)}}</td>
    <td></td>
</tr>
    <tr>

        <td>xxxx</td>
        <td>Salary Areas</td>
        <td>{{number_format($reports->sum('salary_areas'),2)}}</td>
        <td></td>
    </tr>
    @forelse($allowances as $index=>$allowance)

        <tr>
            <td>{{$allowance->code}}</td>
            <td style="text-transform: capitalize !important;">{{$allowance->allowance_name}}</td>
            <td>{{number_format($reports->sum("A$allowance->id"),2)}}</td>
            <td></td>
        </tr>
        @php

            $a=$allow_total +=round($reports->sum("A$allowance->id"),2);
            $sal_ar=round($reports->sum('salary_areas'),2);
            $sal=round($reports->sum('basic_salary'),2);

        @endphp
    @empty

    @endforelse
    <tr>
        <td>&nbsp;</td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    @forelse($deductions as $index=>$deduction)
        <tr>
            <td>{{$deduction->code}}</td>
            <td style="text-transform: capitalize !important;">{{$deduction->deduction_name}}</td>
            <td></td>
            <td>{{number_format($reports->sum("D$deduction->id"),2)}}</td>
        </tr>
        @php
            $deduct_total +=round($reports->sum("D$deduction->id"),2)
        @endphp
    @empty

    @endforelse
    <tr>
        <td>xxxx</td>
        <td>Net Pay</td>
        <td></td>
        <td>{{number_format($reports->sum('net_pay'),2)}}</td>
    </tr>
    </tbody>
    <tfoot>
    <tr>
        <th style="text-align: right !important;"></th>
<th style="text-align: right !important;padding-right: 10px !important;">Total:</th>
        <th style="text-align: left !important;">{{number_format($a+$sal_ar+$sal,2)}}</th>
        <th style="text-align: left !important;">{{number_format($deduct_total+$reports->sum('net_pay'),2)}}</th>
    </tr>
    </tfoot>
</table>
<p style="text-align: center;margin-top: 50px">Approved By: ...............................................................................</p>
<p style="text-align: center;">Sign & Date: ................................................................................</p>
</body>
</html>
