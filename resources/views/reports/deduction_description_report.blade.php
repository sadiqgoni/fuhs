<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Deduction Description</title>
</head>
<body>
<?php
$allowance=App\Models\SalaryHistory::all() ;

?>
@forelse($allowance as $paySlip)

    {{--    @forelse($pay as $paySlip)--}}
    <?php
    //        $allowance=\App\Models\Allowance::find($paySlip);
    //        dd($allowance)
    ?>
    <img src="{{asset('storage/'.app_settings()->logo)}}" alt="" style="width: 50px;position: absolute;right: 70px">
    <h3 style="padding: 0;margin: 0;text-align: center;text-transform: uppercase">{{app_settings()->name}}</h3>
    <h4 style="padding: 0;margin: 0;text-align: center">{{address()}}</h4>
    <br>
    <h5 style="padding: 0;margin: 0; text-align: center">Code: {{$paySlip->salary_month}}, {{$paySlip->salary_year}}</h5>
    <table   style="margin: auto; margin-top: 10px;">
        <tr>
            <td><span>Name: {{$paySlip->full_name}}</span></td>
            <td><span>PFN: {{$paySlip->pf_number}}</span></td>
            <td> <span>IPP No: {{$paySlip->ip_number}}</span></td>
        </tr>
        <tr>
            <td><span>PFA: {{$paySlip->pfa_name}}</span></td>
            <td><span>Pension PIN: {{$paySlip->pension_pin}}</span></td>
            <td><span>{{$paySlip->salary_structure}} {{$paySlip->grade_level}}/{{$paySlip->grade_step}}</span></td>
        </tr>
        <tr>
            <td><span>Department:{{$paySlip->department}}</span></td>
            <td><span>Bank Code: {{$paySlip->bank_code}}</span></td>
            <td><span>Acct No: {{$paySlip->account_number}}</span></td>
        </tr>
        <tr>
            <td  style="margin-top: 40px;">Basic Sal: {{$paySlip->basic_salary}}</td>
            <td  style="margin-top: 40px;">COEASULS: {{$paySlip->D7}}</td>
            <td  style="margin-top: 40px;">SSS: {{$paySlip->D23}}</td>
        </tr>
        <tr>
            <td>Sal Arrears: {{$paySlip->A2}}</td>
            <td>NASU UD: {{$paySlip->D8}}</td>
            <td>City Gate: {{$paySlip->D24}}</td>
        </tr>
        <tr>
            <td>Pecu Allow: {{$paySlip->A3}}</td>
            <td>NASU Ls: {{$paySlip->D9}}</td>
            <td>Cool Bucks: {{$paySlip->D25}}</td>
        </tr>
        <tr>
            <td>Res Allow: {{$paySlip->A4}}</td>
            <td>SSUCOE UD: {{$paySlip->D10}}</td>
            <td>Fast Cr: {{$paySlip->D26}}</td>
        </tr>
        <tr>
            <td>Rent Allow: {{$paySlip->A5}}</td>
            <td>SSUCOE Ls: {{$paySlip->D11}}</td>
            <td>Fast Cash: {{$paySlip->D27}}</td>
        </tr>
        <tr>
            <td>Shit Allow: {{$paySlip->A6}} </td>
            <td>H Loan : {{$paySlip->D12}}</td>
            <td>GWCU CND: {{$paySlip->D28}}</td>
        </tr>
        <tr>
            <td>Call Duty Allow: {{$paySlip->A7}} </td>
            <td>ASPTFund: {{$paySlip->D13}}</td>
            <td>GBPL Centre: {{$paySlip->D29}}</td>
        </tr>
        <tr>
            <td>Haz Allow: {{$paySlip->A8}} </td>
            <td>Rent GQ: {{$paySlip->D14}} </td>
            <td>Lapo MFP: {{$paySlip->D30}} </td>
        </tr>
        <tr>
            <td>New Haz Allow: {{$paySlip->A9}} </td>
            <td>Car Loan: {{$paySlip->D15}} </td>
            <td>Hasttelloy DV: {{$paySlip->D31}} </td>

        </tr>
        <tr>
            <td style="font-weight: bold">Deductions</td>
            <td>FMB HRLR: {{$paySlip->D16}}</td>
            <td>LsheGo MFB: {{$paySlip->D32}}</td>
        </tr>
        <tr>
            <td>Income Tax:</td>
            <td>NASU WFS: {{$paySlip->D17}}</td>
            <td>Page IF {{$paySlip->D33}}</td>
        </tr>
        <tr>
            <td>NHF:</td>
            <td>VehRefurb: {{$paySlip->D18}}</td>
            <td>Spec MFB: {{$paySlip->D34}}</td>
        </tr>
        <tr>
            <td>CPS:{{$paySlip->D3}}</td>
            <td>Staff Coop: {{$paySlip->D19}}</td>
            <td>UBA Consu: {{$paySlip->D35}}</td>
        </tr>
        <tr>
            <td>FCE Res: {{$paySlip->D15}}</td>
            <td>FCE WICE:{{$paySlip->D20}}</td>
            <td>UCEE MFB:{{$paySlip->D36}}</td>
        </tr>
        <tr>
            <td>Sal Ded:</td>
            <td>ECCE: {{$paySlip->D21}}</td>
            <td>OTHER DED: {{$paySlip->D37}}</td>
        </tr>
        <tr>
            <td>COEASUUD:</td>
            <td>DPS: {{$paySlip->D22}}</td>
            <td>OTHER DED2: {{$paySlip->D38}}</td>
        </tr>
        <tr>
            <td>Gross Pay:{{$paySlip->gross_pay}}</td>
            <td>Total Deduction:{{$paySlip->total_deduction}}</td>
            <td>Net Pay: {{$paySlip->net_pay}}</td>
        </tr>
    </table>
    <div class="page_break"></div>
    {{--    @empty--}}

    {{--    @endforelse--}}

@empty
    <tr style="color:red;">No Record</tr>
@endforelse


</body>
</html>
