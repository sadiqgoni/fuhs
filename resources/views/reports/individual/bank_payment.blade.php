<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Individual Bank Payment</title>
    <style>
       tr td{
            border:1px solid black;
        }
       *{
           font-size: 12px;
           font-family: "Times New Roman";
       }
    </style>
</head>
<body>

<img src="{{public_path('storage/'.app_settings()->logo)}}" alt="" style="width: 35px;position: absolute;left: 70px;margin-top: -10px">
<h3 style="padding: 0;margin: 0;text-align: center;text-transform: uppercase">{{app_settings()->name}}</h3>
<h4 style="padding: 0;margin: 0;text-align: center">{{address()}}</h4>
<h4 style="padding: 10px;margin: 0;text-align: center">Employee Bank Payment Schedule</h4>

{{--@if($reports->count()>0)--}}
<h6 style="position: absolute;right:70px" >
    @if($date_from != $date_to)
        {{\Illuminate\Support\Carbon::parse($date_from)->format('F Y')}} - {{\Illuminate\Support\Carbon::parse($date_to)->format('F Y')}},
    @else
        {{\Illuminate\Support\Carbon::parse($date_from)->format('F Y')}}
    @endif
</h6>

{{--@endif--}}

<h5 style="margin:1px 0 0 0">{{$banks[0]['full_name']}}</h5>
<p style="margin:1px 0 0 0">{{$banks[0]['pf_number']}}</p>

<p style="margin: 0 0 20px 0">{{$banks[0]['ip_number']}}</p>



<table class="border-none"  style="width: 100%; border-collapse: collapse;font-size: 12px">
    <thead>
    <tr>
        <th style="border: 1px solid black">S/N</th>
        <th style="border: 1px solid black">ACCT <br> NUMBER</th>
        <th style="border: 1px solid black">AMOUNT</th>
        <th style="border: 1px solid black">BANK</th>
        <th style="border: 1px solid black">BRANCH</th>
        <th style="border: 1px solid black">SORT <br> CODE</th>
        <th style="border: 1px solid black">REMARK</th>

    </tr>
    </thead>
    <tbody>
    @forelse($banks as $report)

        <tr>
            <td>{{$loop->iteration}}.</td>
            <td>{{$report->account_number}}</td>
            <td>{{number_format($report->net_pay,2)}}</td>
            <td>{{$report->bank_name}}</td>
            <td></td>
            <td></td>
            {{--            <td>{{$report->branch}}</td>--}}
            {{--            <td>{{$report->sort_code}}</td>--}}
            <td>{{$report->salary_remark}}</td>

        </tr>



    @empty

    @endforelse
    </tbody>
        <tfoot>
        <tr>
            <td colspan="2" style="border:none;text-align: right;padding: unset 5px"><h4 style="margin: 0">Total</h4></td>
            <td colspan="5" style="border:none"><h4 style="margin: 0">{{number_format($banks->sum('net_pay'),2)}}</h4></td>
{{--                    <td colspan="7"><h4 style="margin: 0">{{number_format($total_sal),2}}</h4></td>--}}
        </tr>

        </tfoot>
</table>


</body>
</html>
