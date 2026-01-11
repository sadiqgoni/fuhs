<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Deduction Schedule</title>
    <style>
        tr td,tr th{
            border: 1px solid black;
        }
        *{
            font-size: 12px;
            font-family: "Times New Roman";
        }
        body{
            padding: 0 50px ;
        }
        #footer { position: fixed; right: 0px; bottom: 10px; text-align: center;border-top: 1px solid black;}
        #footer .page:after { content: counter(page, decimal); }
        @page { margin: 20px 30px 40px 50px; }
    </style>
</head>
<body style="padding-top: -60px !important;">
<div id="footer">
    <p class="page">Page </p>
</div>
<img src="{{public_path(logo())}}" alt="" style="width: 50px;position: absolute;right: 50px">
<h3 style="padding: 0;margin: 0;text-align: center;text-transform: uppercase">{{config('app.name')}}</h3>
<h4 style="padding: 0;margin: 0;text-align: center">{{address()}}</h4>
@if($reports->count()>0)
    <h6 style="float: right;margin-right:45px;margin-top: -1px " >Month: {{$reports[1][0]['salary_month']}}, {{$reports[1][0]['salary_year']}}</h6>

@endif
<table border="0" style="border-collapse: collapse;width: 100%;margin-top: -50px">
    @forelse($reports as $key=>$report)

    <thead>
    <tr style="border: 0 !important;border-collapse: collapse">
        <td colspan="4" style="border: 0 !important;border-collapse: collapse">
            @php
                $deduct_name=\App\Models\Deduction::find($report[0]['deduction_id'])
            @endphp
            <p style="padding: 5px"></p>
            <p style="margin: 0;padding: 2px;text-transform: capitalize">{{$deduct_name->code}}:{{$deduct_name->deduction_name}}</p>
            <p style="margin: 0;padding: 2px;text-transform: capitalize">{{$deduct_name->description}}</p>
        </td>
    </tr>
    <tr>
        <th>S/N</th>
        <th>STAFF No</th>
        <th>STAFF NAME</th>
        <th>AMOUNT</th>
    </tr>
    </thead>
    @forelse($report as $index=>$item)
        <tbody>
        <tr>
            <th>{{$index+1}}</th>
            <td>{{$item->pf_number}}</td>
            <td>{{$item->full_name}}</td>
            <td>{{round($item->amount,2)}}</td>
        </tr>
        </tbody>
    @empty

    @endforelse

        <tfoot>
        <tr style=";border: 0;border-collapse: collapse">
            <td colspan="3" style="text-align: right;font-weight: 100;border: 0;border-collapse: collapse">Total</td>
            <td colspan="1" style="text-align: right;font-weight: 100;border: 0;border-collapse: collapse">{{$report->sum('amount')}}</td>
        </tr>
        </tfoot>
    @empty

    @endforelse
</table>
<div style="margin-top: 5px">
    <p>Approved by:</p>
    <p>Name:.........................................</p>
    <p>Sign & Date:..................................</p>
</div>
</body>
</html>
