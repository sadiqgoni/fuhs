<div class="container">
    <div class="row">

        <p></p>


        <div class="col-12 mt-5">
            @if($payrolls !=[])

            <div class="panel panel-default panel-table">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col col-xs-6">
                            <h3 class="panel-title">Payroll Report<button wire:click.prevent="export_payroll()" class="btn btn-dark float-right">Export</button></h3>
                        </div>

                    </div>
                </div>
                <div class="panel-body table-responsive">

                    <table class="table table-striped table-bordered table-list table-sm" style="font-size: 12px"  >
                        @php
                            $counter=1;
                        @endphp

{{--                        @forelse($payrolls as $key=>$payroll)--}}
{{--                            @php--}}

{{--                                $reports=$payroll;--}}
{{--                                $group=$payroll->first();--}}
{{--                            @endphp--}}


{{--                            <tr style="border:0 !important">--}}
{{--                                <td colspan="21" style="padding: 0;border:0 !important;text-align: left;text-transform: capitalize" > <h3 style="font-size: 12px;margin:20px 0 0 0 !important;">--}}
{{--                                        <span style="text-transform: capitalize">  {{$name}}:</span> {{$a}}</h3></td>--}}
{{--                            </tr>--}}
                            <tr >
                                <th class="hidden-xs">Sn</th>
                                <th>Staff Id <br>Name</th>
                                <th>Payroll Id<br/>Cons</th>
                                <th>Basic Sal<br/>Salary Arr</th>
                                <th>Resp Allow <br/>Haz Allow </th>
                                <th>NM Haz Allow <br/>C duty Allow</th>
                                <th>Spec Allow <br/> Teach Allow</th>
                                <th>Shift Allow  <br/> Other Allow1</th>
                                <th>Other Allow2</th>
                                <th>Paye<br/> Pension</th>
                                <th>NHF<br/> Union 1 Dd</th>
                                <th>SAL DED<br/> FUHSNICS</th>
                                <th>ANUPA<br/> Page Loans </th>
                                <th>Other Ded1 <br/>Other Ded2</th>
                                <th>Union 2 Dd </th>
                                <th>Bank Name <br/> Acc No</th>
                                <th>Gross <br/> Total Ded</th>
                                <th>Netpay</th>

                            </tr>


                            <tbody>
                            @forelse($payrolls as $index=>$report)
                                @php
                                    $emp=\App\Models\EmployeeProfile::where('staff_number',$report->pf_number)->first();
                                @endphp

                                <tr>
                                    <td>
                                        {{$counter ++}}
                                    </td>
                                    <td style="text-align: left">{{$report->pf_number}} <br/> {{$report->full_name}}</td>
                                    <td>{{$report->ip_number}}<br>grade{{$report->grade_level."/".$report->step}}</td>

                                    <td>{{number_format($report->basic_salary,2)}} <br> {{number_format($report->salary_areas,2)}}</td>
                                    <td>{{number_format($report->A1,2)}} <br> {{number_format($report->A2,2)}}</td>
                                    <td>{{number_format($report->A3,2)}} <br> {{number_format($report->A4,2)}}</td>
                                    <td>{{number_format($report->A5,2)}} <br> {{number_format($report->A6,2)}}</td>
                                    <td>{{number_format($report->A7,2)}} <br> {{number_format($report->A8,2)}}</td>
                                    <td>{{number_format($report->A9,2)}}</td>
                                    <td>{{number_format($report->D1,2)}} <br> {{number_format($report->D2,2)}}</td>
                                    <td>{{number_format($report->D3,2)}} <br> {{number_format($report->D4,2)}}</td>
                                    <td>{{number_format($report->D5,2)}} <br> {{number_format($report->D6,2)}}</td>
                                    <td>{{number_format($report->D7,2)}} <br> {{number_format($report->D8,2)}}</td>
                                    <td>{{number_format($report->D9,2)}} <br> {{number_format($report->D10,2)}}</td>
                                    <td>{{number_format($report->D11,2)}}</td>
                                    <td> {{$report->bank_name}} <br>{{$report->account_number}}</td>
                                    <td>{{number_format($report->gross_pay,2)}}<br/>{{number_format($report->total_deduction,2)}}</td>
                                    <td>{{number_format($report->net_pay,2)}}</td>
                                </tr>
                            @empty

                            @endforelse
{{--                            <tr>--}}
{{--                                <td colspan="15"></td>--}}
{{--                                <th colspan="2" style="text-align: right">Subtotal: </th>--}}
{{--                                <th colspan="2">{{number_format($reports->sum('net_pay'),2)}}</th>--}}
{{--                            </tr>--}}
                            </tbody>

{{--                        @empty--}}

{{--                        @endforelse--}}
                    </table>


                </div>
            </div>
            @endif

        </div>
    </div>
</div>
