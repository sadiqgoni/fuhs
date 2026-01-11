<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bank Summary Report</title>
    <style>
        {{--        @include('reports.dompdf')--}}
        tfoot td, tfoot tr{
            border-collapse: collapse;
            border:0;
        }
        tfoot tr:first-child td {
            border-top: none;
        }
        tfoot tr:last-child td {
            border-top: none;
        }
        tr td{
            padding: 4px 2px;
        }
        .border-none {
            border-collapse: collapse;
            border: none;
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
<body style="margin-left:80px;margin-right: 50px">
<img src="{{public_path('storage/'.app_settings()->logo)}}" alt="" style="width: 50px;position: absolute;left: 70px">
<h3 style="padding: 0;margin: 0;text-align: center;text-transform: uppercase">{{app_settings()->name}}</h3>
<h4 style="padding: 0;margin: 0;text-align: center">{{address()}}</h4>

{{--@if($reports->count()>0)--}}
<h6 style="text-align: center" >Bank Payment Report for the Month of {{\Illuminate\Support\Carbon::parse($date_from)->format('F Y')}}</h6>

{{--@endif--}}

{{--<h5 style="margin:30px 0 0 0">The Manager</h5>--}}
{{--<p style="margin:1px 0 0 0">Guaranty Trust Bank, Zaria Road, Kano </p>--}}

{{--<p style="margin: 0 0 20px 0">Please credit the account(s) of the underlisted beneficiaries and debit our account Number: 302/617041/111</p>--}}
<table class="border-none" border="1" style="width: 100%; border-collapse: collapse;font-size: 12px;margin-top: 50px">
    <thead>
    <tr>
        <th>S/N</th>
        <th>ACCT <br> NUMBER</th>
        <th>AMOUNT</th>
        <th>BANK</th>
        <th>BRANCH</th>
        <th>SORT <br> CODE</th>
        <th>REMARK</th>
        <th>STAFF No</th>
        <th>IPP No</th>
        <th>STAFF <br>NAME</th>
    </tr>
    </thead>
    <tbody>
    @forelse($reports as $index=>$report)

        @if($report->amount > 0)
            <tr>
                <td>{{$index+1}}.</td>
                <td>{{$report->account_number}}</td>
                <td>{{number_format($report->amount,2)}}</td>
                <td>{{$report->bank}}</td>

                <td>{{$report->branch}}</td>
                <td>{{$report->sort_code}}</td>
                <td>{{$report->remark}}</td>
                <td>{{$report->staff_number}}</td>
                <td>{{$report->ipp_no}}</td>
                <td>{{$report->staff_name}}</td>
            </tr>
        @endif



    @empty

    @endforelse

    </tbody>
    <tfoot>
    <tr>
        <td colspan="2"><h4 style="margin: 0">Grand Total</h4></td>
        <td colspan="7"><h4 style="margin: 0">{{number_format($reports->sum('amount'),2)}}</h4></td>
        {{--        <td colspan="7"><h4 style="margin: 0">{{number_format($total_sal)}}</h4></td>--}}
    </tr>
    <tr>
        <td colspan="5">
            <p>Authorised Signature: ............................................................................</p>
            <p>Name: ................................................................................. Thumb Print</p>
            <div style="border:1px solid black;padding: 23px;float: right;margin-top: -58px"></div>
        </td>
        <td></td>
        <td colspan="3">
            <p>Authorised Signature: .........................................................................</p>
            <p>Name: ............................................................................. Thumb Print</p>
            <div style="border:1px solid black;padding: 23px;float: right;margin-top: -58px"></div>

        </td>
    </tr>
    </tfoot>
</table>
<div id="footer">
    <p class="page">Page </p>
</div>
</body>
</html>
