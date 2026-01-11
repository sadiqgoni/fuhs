<div class="container">
    <div class="row">

        <p></p>


        <div class="col-12 mt-5">

            <div class="panel panel-default panel-table">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col col-xs-6">
                            <h3 class="panel-title">Employer Pension </h3>
                        </div>

                    </div>
                </div>
                @if(!empty($employer_pension))
                    <div class="panel-body table-responsive">
                        <h5 style="text-align: center;padding:15px 0;margin: 0">Employer Pension  for the month of {{\Illuminate\Support\Carbon::parse($date_from)->format('F Y')}} </h5>
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
                            @forelse($employer_pension as $report)

                                <tr>
                                    <td>{{$counter}}</td>
                                    <td>{{$report->ip_number}}</td>
                                    <td>{{$report->full_name}}</td>
                                    <td>{{$report->pension_pin}}</td>
                                    <td>

                                        {{number_format($report->employer_pension,2)}}
                                    </td>
                                </tr>
                                @php $counter++ @endphp
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

