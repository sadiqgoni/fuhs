<!doctype html>
<html lang="en">
<head>

    <title>Deduction Schedule</title>
    <style>
        *{
            font-size: 12px;
            font-family: "Times New Roman";
        }
        body{
            padding: 0 50px ;
        }
        tr td,tr th{
            border: 1px solid black;
        }
        #footer { position: fixed; right: 0px; bottom: 10px; text-align: center;border-top: 1px solid black;}
        #footer .page:after { content: counter(page, decimal); }
        @page { margin: 20px 30px 40px 50px; }
        @media print {
            #footer {page-break-after: always;}
            /*#header {*/
            /*    display: table-header-group;*/
            /*}*/
        }
        #header {
            position:fixed;
            top:0px;
            left:0px;
            width:100%;
            /*color:#CCC;*/
            /*background:#333;*/
            padding:20px;
            margin-bottom: 80px;
            height: 200px;
        }
        .header, .header-space,
        .footer, .footer-space {
            height: 50px;
        }
        .header {
            position: fixed;
            top: 0;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body onload="window.print()">

<div id="header" class="header">
    <img src="{{public_path('/storage/'.app_settings()->logo)}}" alt="" style="width: 50px;position: absolute;left: 70px">
    <h3 style="padding: 0;margin: 0;text-align: center;text-transform: uppercase">{{app_settings()->name}}</h3>
    <h4 style="padding: 0;margin: 0;text-align: center">{{address()}}</h4>
    <span style="position: absolute;right:100px;margin-top: 20px;margin-bottom: 0px " >Month: {{\Illuminate\Support\Carbon::parse($date_from)->format('F Y')}}</span>
    <h6 style="text-align: center !important;margin: 10px 0;text-transform: uppercase;">Salary Deduction Report</h6>

</div>


@forelse($reports as $report)

    @php
        $deduct_name=\App\Models\Deduction::find($report[0]['deduction_id']);
    @endphp
    @if(\App\Models\TemporaryDeduction::where('deduction_id',$report[1]['deduction_id'])->where('amount','>',1)->count())

        <table border="0" style="width:100%;border-collapse: collapse">
            <thead>
            <tr>
                <td colspan="5" style="padding-top: 60px !important;border: 0 !important;">
                    <div class="header-space">
                        <p style="padding: 2px;text-transform: uppercase;">{{$deduct_name->code}}:{{$deduct_name->deduction_name}}
                        <br>{{$deduct_name->description}}</p>
                    </div>
                </td>
            </tr>
            <tr>
                <th>S/N</th>
                <th>STAFF No</th>
                <th>Payroll No</th>
                <th>STAFF NAME</th>
                <th>AMOUNT</th>
            </tr>
            </thead>
            <tbody>
            @php
                $total=0;
$counter=1;
            @endphp
            @forelse($report as $index=>$item)
                @php
                    $emp=\App\Models\EmployeeProfile::where('staff_number',$item->staff_number)->first();
                @endphp
                @if($item->amount <= 0)
                    @continue
                @else

                    <tr>
                        <th>{{$counter}}</th>
                        <td>{{$item->staff_number}}</td>
                        <td>{{$emp->payroll_number??null}}</td>
                        <td>{{$item->staff_name}}</td>
                        <td>{{number_format($item->amount,2)}}</td>
                    </tr>
                @endif
                @php
                    $total +=round($report->sum('amount'));
$counter++
                @endphp
            @empty
            @endforelse
            </tbody>
            <div class="page-break"></div>
            <tr style="border-collapse: collapse;border: 0">
                <td colspan="4" style="text-align: right;font-weight: 100;border-collapse: collapse;border: 0;border-top:1px solid !important;padding-right: 10px ">Total: </td>
                <td colspan="1" style="font-weight: 100;border-collapse: collapse;border: 0;border-top:1px solid !important;">{{number_format($report->sum('amount'),2)}}</td>
            </tr>
            <tr style="border:0">
                <td colspan="4" style="border:0">
                    <div>
                        <p>Approved by:</p>
                        <p>Name:.........................................</p>
                        <p>Sign & Date:..................................</p>
                    </div>
                </td>
            </tr>

        </table>
        <p style="page-break-before: always"></p>


    @endif
@empty

@endforelse


</body>
</html>
