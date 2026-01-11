<div class="container" style="overflow-x: hidden">
    <div class="row" style="overflow-x: hidden !important;">

        <p></p>


        <div class="col-12 mt-5">

            <div class="panel panel-default panel-table">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col col-xs-6">
                            <h3 class="panel-title">Salary Deduction Schedule <button class="btn btn-dark float-right" wire:click.prevent="export_deduction()">Export</button></h3>
                        </div>

                    </div>
                </div>
                @forelse($reports as $report)
                    @php
                        //dd(\App\Models\TemporaryDeduction::where('deduction_id',1)->where('amount','>',1)->count());
                        $deduct_name=\App\Models\Deduction::find($report[0]['deduction_id'])
                    @endphp
                    @if(\App\Models\TemporaryDeduction::where('deduction_id',$report[0]['deduction_id'])->where('amount','>',1)->count())
                        <p style="margin: 0;padding: 2px;text-transform: capitalize">{{$deduct_name->code}}:{{$deduct_name->deduction_name}}</p>
                        <p style="margin: 0;padding: 2px;text-transform: capitalize">{{$deduct_name->description}}</p>
                        <table border="0" style="width:100%;border-collapse: collapse">

                            <thead>
                            <tr>
                                <th>S/N</th>
                                <th>STAFF No</th>
                                <th>Payroll No</th>
                                <th>STAFF NAME</th>
                                <th>AMOUNT</th>
                            </tr>
                            </thead>
                            @php
                                $total=0;
                            @endphp
                            @forelse($report as $index=>$item)
                                @php

                                    $emp=\App\Models\EmployeeProfile::where('staff_number',$item->staff_number)->first();
                                @endphp
                                @if($item->amount <= 0)
                                    @continue
                                @else
                                    <tbody>
                                    <tr>
                                        <th>{{$index+1}}</th>
                                        <td>{{$item->staff_number}}</td>
                                        <td>{{$emp->payroll_number??null}}</td>
                                        <td>{{$item->staff_name}}</td>
                                        <td>{{number_format($item->amount,2)}}</td>
                                    </tr>
                                    </tbody>
                                @endif
                                @php
                                    $total +=round($report->sum('amount'));
                                @endphp
                            @empty
                            @endforelse
                            <tfoot>
                            <tr style="border-collapse: collapse;border: 0">
                                <td colspan="3" style="text-align: right;font-weight: 100;border-collapse: collapse;border: 0">Total</td>
                                <td colspan="1" style="text-align: right;font-weight: 100;border-collapse: collapse;border: 0">{{number_format($report->sum('amount'),2)}}</td>
                            </tr>

                            </tfoot>
                        </table>
                        <div>
                            <p>Approved by:</p>
                            <p>Name:.........................................</p>
                            <p>Sign & Date:..................................</p>
                        </div>
                    @endif

                @empty

                @endforelse
            </div>

        </div>
    </div>
</div>
