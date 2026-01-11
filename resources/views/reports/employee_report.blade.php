<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Employee Report</title>
    <style>
        *{
            font-size: 12px;
            font-family: "Times New Roman";
        }
        #footer { position: fixed;bottom: -30px;right: 0; text-align: center;border-top: 1px solid black;}
        #footer .page:after { content: counter(page, decimal); }
        @page { margin: 50px 30px 40px 50px; }
        tr td, tr th{
            border:1px solid black
        }
        table{
            border-collapse: collapse;
        }
    </style>
</head>
<body>
<div id="footer">
    <p class="page">Page </p>
</div>
<img src="{{public_path('storage/'.app_settings()->logo)}}" alt="" style="width: 35px;position: absolute;left: 70px">
<h3 style="padding: 0;margin: 0;text-align: center;text-transform: uppercase">{{app_settings()->name}}</h3>
<h4 style="padding: 0;margin: 0;text-align: center">{{address()}}</h4>
<p style="margin: 10px 1px 1px 1px;text-align: center;text-transform: capitalize">{{$columns->report_title}}</p>
<p style="margin: 1px;text-align: center;text-transform: capitalize">{{$columns->subtitle}}</p>
<p style="float: right;margin-top: -30px">Month: {{\Illuminate\Support\Carbon::now()->format('F, Y')}}</p>
@if(!empty($report_col))
    <table class="table table-bordered table-sm" style="font-size: 12px;width:100%">

        <thead>
        <tr>
            <th>S/N</th>
            @if(in_array(1,$report_col))
                <th>Staff No</th>
            @endif
            @if(in_array(2,$report_col))
                <th>IPP No</th>
            @endif
            @if(in_array(3,$report_col))
                <th>Fullname</th>
            @endif
            @if(in_array(4,$report_col))
                <th>Unit</th>
            @endif
            @if(in_array(5,$report_col))
                <th>Department</th>
            @endif
            @if(in_array(6,$report_col))
                <th>Phone number</th>
            @endif
            @if(in_array(7,$report_col))
                <th>WhatsApp No</th>
            @endif
            @if(in_array(8,$report_col))
                <th>Email</th>
            @endif
            @if(in_array(9,$report_col))
                <th>DOB</th>
            @endif
            @if(in_array(10,$report_col))
                <th>Salary Structure</th>
            @endif
            @if(in_array(11,$report_col))
                <th>Grade Level</th>
            @endif
            @if(in_array(12,$report_col))
                <th>Grade Step</th>
            @endif
            @if(in_array(13,$report_col))
                <th>DFA</th>
            @endif
            @if(in_array(14,$report_col))
                <th>DLP</th>
            @endif
            @if(in_array(15,$report_col))
                <th>Gender</th>
            @endif
            @if(in_array(16,$report_col))
                <th>Religion</th>
            @endif
            @if(in_array(17,$report_col))
                <th>Tribe</th>
            @endif
            @if(in_array(18,$report_col))
                <th>Marital Status</th>
            @endif
            @if(in_array(19,$report_col))
                <th>Nationality</th>
            @endif
            @if(in_array(20,$report_col))
                <th>State</th>
            @endif
            @if(in_array(21,$report_col))
                <th>LGA</th>
            @endif
            @if(in_array(22,$report_col))
                <th>Staff Cat</th>
            @endif
            @if(in_array(23,$report_col))
                <th>Bank</th>
            @endif
            @if(in_array(24,$report_col))
                <th>Account No</th>
            @endif
            @if(in_array(25,$report_col))
                <th>PF Name</th>
            @endif
            @if(in_array(26,$report_col))
                <th>Pension Pin</th>
            @endif
            @if(in_array(27,$report_col))
                <th>Date of Retirement</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @foreach($reports as $index=>$report)
            <tr style="text-transform: capitalize">
                <th>{{$index+1}}</th>
                @if(in_array(1,$report_col))
                    <td>{{$report->staff_number}}</td>
                @endif
                @if(in_array(2,$report_col))
                    <td>{{$report->payroll_number}}</td>
                @endif
                @if(in_array(3,$report_col))
                    <td>{{strtolower($report->full_name)}}</td>
                @endif
                @if(in_array(4,$report_col))
                    <td>{{unit($report->unit)}}</td>
                @endif
                @if(in_array(5,$report_col))
                    <td>{{dept($report->id)}}</td>
                @endif
                @if(in_array(6,$report_col))
                    <td>{{$report->phone_number}}</td>
                @endif
                @if(in_array(7,$report_col))
                    <td>{{$report->whatsapp_number}}</td>
                @endif
                @if(in_array(8,$report_col))
                    <td>{{$report->email}}</td>
                @endif
                @if(in_array(9,$report_col))
                    <td>{{$report->date_of_birth}}</td>
                @endif
                @if(in_array(10,$report_col))
                    <td>{{ss($report->salary_structure)}}</td>
                @endif
                @if(in_array(11,$report_col))
                    <td>{{$report->grade_level}}</td>
                @endif
                @if(in_array(12,$report_col))
                    <td>{{$report->step}}</td>
                @endif
                @if(in_array(13,$report_col))
                    <td>{{$report->date_of_first_appointment}}</td>
                @endif
                @if(in_array(14,$report_col))
                    <td>{{$report->date_of_last_appointment}}</td>
                @endif
                @if(in_array(15,$report_col))
                    <td>{{gender($report->gender)}}</td>
                @endif
                @if(in_array(16,$report_col))
                    <td>{{religion($report->religion)}}</td>
                @endif
                @if(in_array(17,$report_col))
                    <td>{{tribe($report->tribe)}}</td>
                @endif
                @if(in_array(18,$report_col))
                    <td>{{marital_status($report->marital_status)}}</td>
                @endif
                @if(in_array(19,$report_col))
                    <td>{{nationality($report->nationality)}}</td>
                @endif
                @if(in_array(20,$report_col))
                    <td>{{state($report->state_of_origin)}}</td>
                @endif
                @if(in_array(21,$report_col))
                    <td>{{lga($report->local_government)}}</td>
                @endif
                @if(in_array(22,$report_col))
                    <td>{{$report->staff_category}}</td>
                @endif
                @if(in_array(23,$report_col))
                    <td>{{$report->bank_name}}</td>
                @endif
                @if(in_array(24,$report_col))
                    <td>{{$report->account_number}}</td>
                @endif
                @if(in_array(25,$report_col))
                    <td>{{pfa_name($report->pfa_name)}}</td>
                @endif
                @if(in_array(26,$report_col))
                    <td>{{$report->pension_pin}}</td>
                @endif
                @if(in_array(27,$report_col))
                    <td>{{$report->date_of_retirement}}</td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
@endif

</body>
</html>
