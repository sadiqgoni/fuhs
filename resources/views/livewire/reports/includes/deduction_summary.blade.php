<div class="container">
    <div class="row">

        <p></p>


        <div class="col-12 mt-5">

            <div class="panel panel-default panel-table">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col col-xs-6">
                            <h3 class="panel-title">Salary Deduction Summary</h3>
                        </div>

                    </div>
                </div>
                @if($reports != [])
                    <div class="panel-body table-responsive">
                        <p style="margin: 0;padding: 0;text-align: center">Salary Deduction Summary for all Staff</p>
                        <br>
                        @if(isset($reports ))

{{--                            <h5 style="padding: 0;margin: 0; text-align: left">Month: {{$date_fro}}</h5>--}}
                        @endif
                        <?php
                        //                    $deductionObj=\App\Models\Deduction::all();
                        //                    $deductionAmounts=\App\Models\SalaryHistory::all();
                        //    dd($deductionAmount);

                        $counter=1;
                        $total=0;

                        ?>

                        <table border="2" style="width:100%" cellspacing="0" cellpadding="5px">
                            <thead class="thead-dark">
                            <th>SN</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Amount</th>
                            </thead>
                            <tbody>


                            @forelse($reports as $index=>$report)
                                @foreach($report->where('amount','>',0)->unique('deduction_id') as $item)
                                    @php

                                        $deduction=\App\Models\Deduction::find($item->deduction_id)
                                    @endphp
                                    <tr>
                                        <td>{{$counter}}</td>
                                        <td>{{$deduction->deduction_name}}</td>
                                        <td>{{$deduction->description}}</td>
                                        {{--        <td>{{$total}}</td>--}}
                                        <td>
                                            {{number_format($report->sum('amount'),2)}}
                                        </td>
                                    </tr>
                                    <?php
                                    $counter+=1;
                                    $total += $report->sum('amount')
                                    ?>
                                @endforeach


                            @empty
                                No Record
                            @endforelse
                            </tbody>
                            <tfoot>
                            <tr>
                                <td></td>
                                <td></td>
                                <th style="text-align: right">Total Amount</th>
                                <th style="text-align: left">{{number_format($total,2)}}</th>
                            </tr>
                            </tfoot>
                        </table>
                        <div style="text-align: center; font-weight: bolder; margin-top: 20px">Approved By: ......................................</div>
                        <div style="text-align: center; font-weight: bolder; margin-top: 20px">Sign & Date: ......................................</div>


                    </div>

                @endif
            </div>

        </div>
    </div>
</div>
