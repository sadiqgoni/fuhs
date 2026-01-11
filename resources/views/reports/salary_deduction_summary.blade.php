<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Salary Deduction Summary</title>
    <style>
        table{
            border: 1px solid black;
            margin: auto;
        }
        thead{
            background-color: black;
            color: white;
        }
        *{
            font-family: "Times New Roman";
            font-size: 12px;
        }
        #footer { position: fixed; right: 0px; bottom: 10px; text-align: center;border-top: 1px solid black;}
        #footer .page:after { content: counter(page, decimal); }
        @page { margin: 20px 30px 40px 50px; }
    </style>
</head>
<body style="margin: 0 75px">
<img src="{{public_path('storage/'.app_settings()->logo)}}" alt="" style="width: 50px;position: absolute;left: 70px">
<h3 style="padding: 0;margin: 0;text-align: center;text-transform: uppercase">{{app_settings()->name}}</h3>
<h4 style="padding: 0;margin: 0;text-align: center">{{address()}}</h4>
<p style="margin: 0;padding: 0;text-align: center">Salary Deduction Summary for all Staff</p>
<br>
{{--@if($reports != [])--}}

    <h5 style="padding: 0;margin: 0; text-align: left">Month: {{\Illuminate\Support\Carbon::parse($date_from)->format('F Y')}}</h5>
{{--@endif--}}
<?php
//$deductionObj=\App\Models\Deduction::all();
//$deductionAmounts=\App\Models\SalaryHistory::all();
//    dd($deductionAmount);


$total=0;
$counter=1;

?>
<table border="2" style="width:100%" cellspacing="0" cellpadding="5px">
    <thead class="thead-dark">
    <th>SN</th>
    <th>Title</th>
    <th>Description</th>
    <th>Amount</th>
    </thead>
    <tbody>


    @forelse($reports as $index=>$report)

        @foreach($report->where('amount','>',0)->unique('deduction_id') as $item)
            @php

                $deduction=\App\Models\Deduction::find($item->deduction_id)
            @endphp
{{--            @if($report->sum('amount') > 0)--}}
                <tr>
                    <td>{{$counter}}</td>
                    <td>{{$deduction->deduction_name}}</td>
                    <td>{{$deduction->description}}</td>
                    {{--        <td>{{$total}}</td>--}}
                    <td>
                        {{number_format($report->sum('amount'),2)}}
                    </td>
                </tr>
                <?php
                $counter+=1;
                $total += round($report->sum('amount'),2)
                ?>
{{--            @endif--}}


        @endforeach


    @empty
        No Record
    @endforelse
    </tbody>
    <tfoot>
    <tr>
        <td></td>
        <td></td>
        <th style="text-align: right">Total Amount</th>
        <th style="text-align: left">{{number_format($total,2)}}</th>
    </tr>
    </tfoot>
</table>

<div style="text-align: center; font-weight: bolder; margin-top: 20px">Approved By: ......................................</div>
<div style="text-align: center; font-weight: bolder; margin-top: 20px">Sign & Date: ......................................</div>

</body>
</html>
