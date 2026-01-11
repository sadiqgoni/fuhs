{{--<h6 class="text-center text-dark">KEEP RECORD OF ANNUAL INCREMENT HISTORY</h6>--}}
<style>
    sup{
        color:orangered;
    }
</style>

<?php
$allowance=App\Models\Allowance::all() ;

?>
@can('can_mail')
    <button class="btn export float-right" wire:click.prevent="sendMail()">Send Mail <i class="fa fa-envelope"></i></button>
@endcan

@forelse($payslips as $paySlip)
    <?php
    $a=explode(" ",$paySlip->deduction_countdown);
    $sorts=collect($a)->sort();
    $loan=array();
    $search="(";
    foreach ($sorts as $sort){
        $loan[\Illuminate\Support\Str::before($sort,$search)]="(".\Illuminate\Support\Str::after($sort,'(');
    }
    ?>
    <table style="font-weight:bolder;margin:30px 9px 9px 9px ;width: 100%">
        <thead>
        <tr>
            <td style="max-width: 60px !important;"><img src="{{public_path('storage/'.app_settings()->logo)}}" alt="" style="width: 70px;position: relative;"></td>
            <td style="text-align: left;padding: 0 !important;">

                <p style="padding: 0 !important;text-align: center;margin: 0">{{app_settings()->name}}</p>
                <p style="padding: 0 !important;text-align: center;margin: 0">{{address()}}</p>
                <p style="padding: 0 !important;text-align: center;">Employee Pay Slip for the Month of {{$paySlip->salary_month}}, {{$paySlip->salary_year}}</p>
            </td>
            <td style="width: 60px !important;">&nbsp;</td>
        </tr>
        {{--           <tr>--}}
        {{--               <td rowspan="3" style="max-width: 60px !important;"> <img src="{{public_path('storage/'.app_settings()->logo)}}" alt="" style="width: 70px;position: relative;"></td>--}}
        {{--               <td style="font-weight: bolder;padding: 0!important;"> <p>{{app_settings()->name}}</p></td>--}}
        {{--           </tr>--}}
        {{--           <tr>--}}
        {{--               <td style="padding-left: 5% !important;padding-top: 0 !important;padding-bottom: 0 !important;"> <h4 style="padding: 0;margin: 0;">{{address()}}</h4></td>--}}
        {{--           </tr>--}}
        {{--           <tr>--}}
        {{--               <td style="padding: 10px 0 0 5%" colspan="2"> <h5 style="padding: 0;margin: 0; ">Employee Pay Slip for the Month of {{$paySlip->salary_month}}, {{$paySlip->salary_year}}</h5></td>--}}
        {{--           </tr>--}}
        </thead>

    </table>
    <table style="margin: auto;width: 95%">



    <table   style="width: 75%;margin-left: 3%;font-size: 13px !important;" >

        @php
            $step=\App\Models\EmployeeProfile::where('staff_number',$paySlip->pf_number)->first()->step;
        @endphp

        <tbody>
        <tr>
            <td  style="margin-top: 40px;">Basic Sal: <br> Sal Arrears: </td>
            <td style="text-align: right"> {{round($paySlip->basic_salary,2)}} <br>{{number_format($paySlip->salary_areas,2)}}</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>

            <td colspan="2"><b>Deductions</b></td>
        </tr>
        <tr>
            <td colspan="2"><b>Allowances</b></td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>

            <td>Payee:</td>
            <td style="text-align: right">{{number_format($paySlip->D1,2)}} <sup>@if(array_key_exists('D1',$loan)){{$loan['D1']}}@endif</sup> </td>
        </tr>
        <tr>
            <td>Resp Allow: </td>
            <td style="text-align: right">{{number_format($paySlip->A1,2)}}</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>

            <td>Pension:  </td>
            <td style="text-align: right">{{number_format($paySlip->D2,2)}} <sup>@if(array_key_exists('D2',$loan)){{$loan['D2']}}@endif</sup> </td>
        </tr>
        <tr>
            <td>Haz Allow:</td>
            <td style="text-align: right">{{number_format($paySlip->A2,2)}}</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>

            <td>NHF: </td>
            <td style="text-align: right">{{number_format($paySlip->D3,2)}} <sup>@if(array_key_exists('D3',$loan)){{$loan['D3']}}@endif</sup> </td>
        </tr>
        <tr>
            <td>NM Haz Allow: </td>
            <td style="text-align: right">{{number_format($paySlip->A3,2)}}</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>

            <td>Union 1 Ded:</td>
            <td style="text-align: right">{{number_format($paySlip->D4,2)}} <sup>@if(array_key_exists('D4',$loan)){{$loan['D4']}}@endif</sup> </td>
        </tr>
        <tr>
            <td>C Duty Allow: </td>
            <td style="text-align: right">{{number_format($paySlip->A4,2)}}</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>

            <td>Sal Ded  </td>
            <td style="text-align: right"> {{number_format($paySlip->D5,2)}} <sup>@if(array_key_exists('D5',$loan)){{$loan['D5']}}@endif</sup> </td>
        </tr>
        <tr>
            <td>Spec Allow: </td>
            <td style="text-align: right">{{number_format($paySlip->A5,2)}}</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td>FUHSNICS: </td>
            <td style="text-align: right">{{number_format($paySlip->D6,2)}} <sup>@if(array_key_exists('D6',$loan)){{$loan['D6']}}@endif</sup> </td>
        </tr>
        <tr>
            <td>Teach Allow: </td>
            <td style="text-align: right">{{number_format($paySlip->A6,2)}}</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>

            <td>Anupa: </td>
            <td style="text-align: right">{{number_format($paySlip->D7,2)}} <sup>@if(array_key_exists('D7',$loan)){{$loan['D7']}}@endif</sup> </td>
        </tr>
        <tr>
            <td>Shift Allow:</td>
            <td style="text-align: right">{{number_format($paySlip->A7,2)}}</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>

            <td>Page Loans: </td>
            <td style="text-align: right">{{number_format($paySlip->D8,2)}} <sup>@if(array_key_exists('D8',$loan)){{$loan['D8']}}@endif</sup> </td>

        </tr>
        <tr>
            <td>Other Allow1: </td>
            <td style="text-align: right">{{number_format($paySlip->A8,2)}}</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>

            <td>Other Ded1: </td>
            <td style="text-align: right">{{number_format($paySlip->D9,2)}} <sup>@if(array_key_exists('D9',$loan)){{$loan['D9']}}@endif</sup> </td>

        </tr>
        <tr>
            <td>Other Allow2: </td>
            <td style="text-align: right">{{number_format($paySlip->A9,2)}}</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>

            <td>Other Ded2: </td>
            <td style="text-align: right">{{number_format($paySlip->D10,2)}} <sup>@if(array_key_exists('D10',$loan)){{$loan['D10']}}@endif</sup> </td>

        </tr>
        <tr>
            <td>Other Allow3: </td>
            <td style="text-align: right">{{number_format($paySlip->A10,2)}}</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>

            <td>Union 2 Ded: </td>
            <td style="text-align: right">{{number_format($paySlip->D11,2)}} <sup>@if(array_key_exists('D11',$loan)){{$loan['D11']}}@endif</sup> </td>

        </tr>

        <tr>
            <td style="font-weight: bolder" colspan="4"><b>Gross Pay:{{number_format($paySlip->gross_pay,2)}}</b></td>
        </tr>
        <tr>
            <td style="font-weight: bolder" colspan="4"><b>Total Ded:{{number_format($paySlip->total_deduction,2)}}</b></td>
        </tr>
        <tr>
            <td style="font-weight: bolder" colspan="4"><b>Net Pay: {{number_format($paySlip->net_pay,2)}}</b></td>

        </tr>
        </tbody>
    </table>
@empty
    No record found
@endforelse

