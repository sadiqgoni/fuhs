<div class="container">
    <style>
        sup{
            color:orangered;
        }
    </style>
    <div class="row">
        <p></p>
        <div class="col-12 mt-5">
            <div class="panel panel-default panel-table">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col col-xs-6">
                            <h3 class="panel-title">Pay Slip</h3>
                        </div>

                    </div>
                </div>
                @if($paySlips !=[])
                    <button class="btn export float-right" wire:click.prevent="sendMail()">Send Mail <i class="fa fa-envelope"></i></button>
                @endif
                <div class="panel-body table-responsive">
                    <?php
                    $allowance=App\Models\Allowance::all() ;

                    ?>
{{--                    @forelse($paySlips as $pay)--}}

                        @forelse($paySlips as $paySlip)
                            <?php
                            //        $allowance=\App\Models\Allowance::find($paySlip);
                            //        dd($allowance)
                            if ($loop->index%2==0){
                                $name=1;
                            }else{
                                $name=2;
                            }
                            $a=explode(" ",$paySlip->deduction_countdown);
                            $sorts=collect($a)->sort();
                            //        $str = preg_replace('/[^0-9.]+/', '', $str);
                            $loan=array();
                            $search="(";
                            foreach ($sorts as $sort){
                                $loan[\Illuminate\Support\Str::before($sort,$search)]="(".\Illuminate\Support\Str::after($sort,'(');
                            }
                            ?>

                            @if($name==1)
                                <div style="margin-top: 30px"></div>
                            @endif
{{--                            <img src="{{public_path('assets/img/KGP_LOGO.png')}}" alt="" style="width: 50px;float: right">--}}

                            <h5 style="padding: 0;margin: 0; text-align: center">STAFF PAY SLIP For the Month of {{$paySlip->salary_month}}, {{$paySlip->salary_year}}</h5>
                                <table style="margin: auto;width: 95%">

                                    <tbody>
                                    <tr>
                                        <td style="padding: 0 0 7px 0 !important;"><b>Name: </b>{{$paySlip->full_name}}</td>
                                        <td style="text-align: center"><b>Staff Id: </b>{{$paySlip->pf_number}}</td>
                                        <td><b>Payroll Id: </b>{{$paySlip->ip_number}}</td>
                                    </tr>
                                    <tr >
                                        <td>PFA: {{$paySlip->pfa_name}} <br>Department: {{$paySlip->department}}</td>
                                        <td style="padding-left: 50px">Pension Pin: {{$paySlip->pfa_name}} <br>Bank Name: {{$paySlip->bank_name}}</td>
                                        <td>{{$paySlip->salary_structure}}  {{$paySlip->grade_level}}{{"/"}}{{$paySlip->step}}</span> <br>Acc No.: {{$paySlip->account_number}}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding:10px 0 15px 0 !important;">Tax ID: </td>
                                        <td style="padding-left: 50px">Union: </td>
                                    </tr>
                                    </tbody>
                                </table>


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


                            @if($name==2)
                                <div class="page_break"></div>
                            @endif

{{--                        @empty--}}

{{--                        @endforelse--}}

                    @empty
                        <tr style="color:red;"></tr>
                    @endforelse

                </div>

            </div>

        </div>
    </div>
</div>
