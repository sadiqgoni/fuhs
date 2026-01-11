<div class="container">
    <div class="row">

        <p></p>


        <div class="col-12 mt-5">

            <div class="panel panel-default panel-table">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col col-xs-6">
                            <h3 class="panel-title">Salary Journal Report</h3>
                        </div>

                    </div>
                </div>
                @if( !empty($journals))
                    <div class="panel-body table-responsive">
                       <h5 style="text-align: center;padding:15px 0;margin: 0">Salary Standard Journal For The Mouth Of: {{$journals[0]['salary_month']}} {{$journals[0]['salary_year']}} </h5>

                        {{--@endif--}}
                        <table style="width: 100%;border-collapse: collapse;font-size: 12px;" border="1" class="table table-striped table-bordered table-list table-sm">
                            <thead>
                            <tr>
                                <th>Code</th>
                                <th>Description</th>
                                <th>Debit Side</th>
                                <th>Credit Side</th>
                            </tr>
                            </thead>
                            <tbody>

                            @php
                                $allowances=\App\Models\Allowance::all();
                                $deductions=\App\Models\Deduction::
                                //where('code','>',0)->
                                get();
                                $allow_total=0;
                                $deduct_total=0;
                            @endphp
                            <tr>
                                <td>xxxx</td>
                                <td>Basic Salary</td>
                                <td>{{number_format($journals->sum('basic_salary'),2)}}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>xxxx</td>
                                <td>Salary Arears</td>
                                <td>{{number_format($journals->sum('salary_areas'))}}</td>
                                <td></td>
                            </tr>
                            @forelse($allowances as $index=>$allowance)
                                <tr>
                                    <td>{{$allowance->code}}</td>
                                    <td style="text-transform: capitalize !important;">{{$allowance->allowance_name}}</td>
                                    <td>{{number_format($journals->sum('A'.$index+1),2)}}</td>
                                    <td></td>
                                </tr>
                                @php

                                    $a=$allow_total +=$journals->sum('A'.$index+1);
                                    $sal_ar=$journals->sum('salary_areas');
                                    $sal=$journals->sum('basic_salary');

                                @endphp
                            @empty

                            @endforelse
                            <tr>
                                <td>&nbsp;</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            @forelse($deductions as $index=>$deduction)
                                <tr>
                                    <td>{{$deduction->code}}</td>
                                    <td style="text-transform: capitalize !important;">{{$deduction->deduction_name}}</td>
                                    <td></td>
                                    <td>{{number_format($journals->sum('D'.$index+1),2)}}</td>
                                </tr>
                                @php
                                    $deduct_total +=$journals->sum('D'.$index+1)
                                @endphp
                            @empty

                            @endforelse
                            <tr>
                                <td>xxxx</td>
                                <td>Net Pay</td>
                                <td></td>
                                <td>{{number_format($journals->sum('net_pay'),2)}}</td>
                            </tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Total</th>
                                <td></td>
                                <th>{{number_format($a+$sal_ar+$sal,2)}}</th>
                                <th>{{number_format($deduct_total+$journals->sum('net_pay'),2)}}</th>
                            </tr>
                            </tfoot>
                        </table>

                        <p style="text-align: center;margin-top: 50px">Approved By: ...............................................................................</p>
                        <p style="text-align: center;">Sign & Date: ................................................................................</p>

                    </div>

                @endif

            </div>

        </div>
    </div>
</div>
