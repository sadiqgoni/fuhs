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
                            $allowances = \App\Models\Allowance::where('status', 1)->get();
                            $deductions = \App\Models\Deduction::where('status', 1)->get();
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
                                
                                @foreach($allowances->chunk(2) as $chunk)
                                    <th>
                                        @foreach($chunk as $allowance)
                                            {{ $allowance->allowance_name }} @if(!$loop->last) <br/> @endif
                                        @endforeach
                                    </th>
                                @endforeach

                                @foreach($deductions->chunk(2) as $chunk)
                                    <th>
                                        @foreach($chunk as $deduction)
                                            {{ $deduction->deduction_name }} @if(!$loop->last) <br/> @endif
                                        @endforeach
                                    </th>
                                @endforeach

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
                                    
                                    @foreach($allowances->chunk(2) as $chunk)
                                        <td>
                                            @foreach($chunk as $allowance)
                                                {{ number_format($report->{'A'.$allowance->id}, 2) }} @if(!$loop->last) <br/> @endif
                                            @endforeach
                                        </td>
                                    @endforeach

                                    @foreach($deductions->chunk(2) as $chunk)
                                        <td>
                                            @foreach($chunk as $deduction)
                                                {{ number_format($report->{'D'.$deduction->id}, 2) }} @if(!$loop->last) <br/> @endif
                                            @endforeach
                                        </td>
                                    @endforeach

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
