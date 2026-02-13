<div class="container">
    <div class="row">

        <p></p>


        <div class="col-12 mt-5">

            <div class="panel panel-default panel-table">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col col-xs-6">
                            <h3 class="panel-title">PFA Payment Schedule <button class="btn btn-dark float-right" wire:click.prevent="pfa_export()">Export</button></h3>
                        </div>

                    </div>
                </div>
                @if(!empty($pfa_payment_schedules))
                    <div class="panel-body table-responsive">
                        <h5 style="text-align: center;padding:15px 0;margin: 0">Pension Deduction Schedule for the month of {{\Illuminate\Support\Carbon::parse($date_from)->format('F Y')}} </h5>
                <table style="width: 100%;border-collapse: collapse;font-size: 12px;" border="1" class="table table-striped table-bordered table-list table-sm">
                    <thead>
                    <tr>
                        <th>SN</th>
                        <th>Payroll No. </th>
                        <th>Staff Name </th>
                        <th>Pension Pin</th>
                        <th>Amount</th>
                    </tr>
                    </thead>
                    @php
                        $counter=1;
                    $total=0
                    @endphp
                    <tbody>
                    @forelse($pfa_payment_schedules as $report)
                        @php
                            $deductions=\App\Models\Deduction::where('status','1')->get();
                        $total=0

                        @endphp
                        {{-- PFA Payment Schedule should only show employer pension contribution (D2) --}}
                        @if($report->D2 != 0 && $report->D2 != '00')
                        <tr>
                            <td>{{$counter}}</td>
                            <td>{{$report->ip_number}}</td>
                            <td>{{$report->full_name}}</td>
                            <td>{{$report->pension_pin}}</td>
                            <td>
                                {{-- PFA Payment Schedule should only show employer pension contribution (D2) --}}
                                {{number_format($report->D2, 2)}}
                            </td>
                        </tr>
                        @php $counter++ @endphp
                        @endif
                    @empty

                    @endforelse
                    </tbody>

                </table>
                    </div>
                    @endif
            </div>
        </div>
    </div>
</div>

