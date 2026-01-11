<div class="container" style="overflow-x: hidden">
    <div class="row" style="overflow-x: hidden !important;">

        <p></p>


        <div class="col-12 mt-5">

            <div class="panel panel-default panel-table">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col col-xs-6">
                            <h3 class="panel-title">Bank Summary</h3>
                        </div>

                    </div>
                </div>
                @if(!empty($bank_sum_reports))
                    <div class="panel-body table-responsive">
                        <p  style="padding: 10px 0 20px 0;margin: 0">Month: {{\Illuminate\Support\Carbon::parse($date_from)->format('F Y')}}</p>

                        <table style="width: 100%">
                            <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Bank Name</th>
                                <th>Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $total=0;
                        $counter=1;
                            @endphp
                            @forelse($bank_sum_reports->where('amount','>',0) as $index=>$report)
{{--                                @if($report->amount > 0)--}}
                                    <tr>
                                        <th>{{$counter}}</th>
                                        <td>{{$report->bank_name}}</td>
                                        <td>{{number_format($report->amount,2)}}</td>
                                    </tr>
{{--                                @endif--}}
                                @php
                                    $counter++
                                @endphp
                            @empty

                            @endforelse
                            </tbody>
                            <tfoot>
                            <tr>
                                <td></td>
                                <th style="text-align: right">Total Amount</th>
                                <th style="text-align: left">@if(!empty($bank_sum_reports))
                                        {{number_format($bank_sum_reports->sum('amount'),2)}}
                                    @endif</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>

                @endif
            </div>
        </div>
    </div>
</div>
