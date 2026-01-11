<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Deduction Details</title>
    <style>
        #footer { position: fixed; right: 0px; bottom: 10px; text-align: center;border-top: 1px solid black;}
        #footer .page:after { content: counter(page, decimal); }
        @page { margin: 20px 30px 40px 50px; }
        tr td,tr th{
            border:1px solid black;
            padding: 2px 5px;
        }
        *{
            font-size: 12px;
            font-family: "Times New Roman";
        }
        table{
            border-collapse: collapse;
        }
    </style>
</head>
<body style="padding:0 10%">
<img src="{{public_path('storage/'.app_settings()->logo)}}" alt="" style="width: 35px;position: absolute;left: 70px;">
<h3 style="padding: 0;margin: 0;text-align: center;text-transform: uppercase">{{app_settings()->name}}</h3>
<h4 style="padding: 0;margin: 0;text-align: center">{{address()}}</h4>
<h6 style="text-align: center !important;margin: 10px 0;text-transform: capitalize !important;">
    @php
        $history=\App\Models\SalaryHistory::find($deductions[0]['history_id'])
    @endphp
   <span style="text-transform: capitalize">{{strtolower($history->full_name)}}</span> Salary Deduction Report
    @if($date_from == $date_to)
        for {{\Illuminate\Support\Carbon::parse($date_from)->format('F,Y')}}
    @else
      from  {{\Illuminate\Support\Carbon::parse($date_from)->format('F,Y')}} - {{\Illuminate\Support\Carbon::parse($date_to)->format('F,Y')}}
    @endif

</h6>
<div id="footer">
    <p class="page">Page </p>
</div>
@php
    $total=0;
@endphp
@forelse($deductions as $key=>$report)
    @php
        $deduct=\App\Models\Deduction::find($report->deduction_id);
          $records=\App\Models\TemporaryDeduction::where('deduction_id',$report->deduction_id)->get();
    @endphp

{{--@if($report->amount > 0)--}}
    <p style="margin: 0 !important;text-transform: uppercase">{{$deduct->code}}: {{strtolower($deduct->deduction_name)}}</p>
    <p style="margin: 0 !important;text-transform: capitalize">{{strtolower($deduct->description)}}</p>
    <table class="" style="width: 100%">
        <thead>
        <tr>
            <th>SN</th>
            <th>MONTH/YEAR</th>
            <th>AMOUNT</th>
        </tr>
        </thead>
        <tbody>

        @forelse($records as $item)
            <tr>
                <th>{{$loop->iteration}}</th>
                <td>{{\Illuminate\Support\Carbon::parse($item->date_month)->format('F,Y')}}</td>
                <td>{{number_format($item->amount,2)}}</td>
            </tr>
        @empty
            {{----}}
        @endforelse
        </tbody>
        <tr>
            <th style="border: none;text-align: right" colspan="2" class="text-right">Subtotal:</th>
            <th style="border: none;text-align: left">{{number_format($records->sum('amount'),2)}}</th>
        </tr>
        @php
            $total +=$records->sum('amount')
        @endphp
    </table>

{{--@endif--}}

@empty
    no record
@endforelse
{{--<div class="text-right">--}}
{{--        <p colspan="2" class="float-right" style="font-weight: bolder;text-align: right">Grand Total:{{number_format($total,2)}}</p>--}}
{{--</div>--}}

</body>
</html>
