<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bank Summary</title>
    <style>
        *{
            font-size: 12px;
            font-family: "Times New Roman";
        }
        tr td, tr th{
            border: 1px solid black;
        }
        table{
            border-collapse: collapse;
        }
    </style>
</head>
<body style="margin: 0 60px">


<img src="{{public_path('storage/'.app_settings()->logo)}}" alt="" style="width: 50px;position: absolute;left: 70px">
<h3 style="padding: 0;margin: 0;text-align: center;text-transform: uppercase">{{app_settings()->name}}</h3>
<h4 style="padding: 0;margin: 0;text-align: center">{{address()}}</h4>
<h3  style="text-align: center;padding: 10px 0;margin: 0">Bank Summary Report For All Staff</h3>
<p  style="padding: 10px 0 20px 0;margin: 0">Month: {{\Illuminate\Support\Carbon::parse($date_from)->format('F Y')}}</p>

<table style="width: 100%">
    <thead>
    <tr>
        <th>S/N</th>
        <th>Bank Name</th>
        <th>Amount</th>
    </tr>
    </thead>
    <tbody>
    @php
        $total=0;
$counter=1;
    @endphp
    @forelse($reports->where('amount','>',0) as $index=>$report)
{{--        @if($report->amount > 0)--}}
            <tr>
                <th>{{$counter}}</th>
                <td>{{$report->bank_name}}</td>
                <td>{{number_format($report->amount,2)}}</td>
            </tr>
{{--        @endif--}}


@php
    $counter++
@endphp
    @empty

    @endforelse
    </tbody>
    <tfoot>
    <tr>
        <td></td>
        <th style="text-align: right">Total Amount</th>
        <th style="text-align: left">{{number_format($reports->sum('amount'),2)}}</th>
    </tr>
    </tfoot>
</table>
<div style="margin-top: 30px">
    <p style="text-align: center">Approved By:……………………………………………………….</p>
    <p style="text-align: center">Sing & Date: ………………………………………………………..</p>
</div>
</body>
</html>
