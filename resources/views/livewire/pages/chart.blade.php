{{-- The whole world belongs to you. --}}
@push('scripts')
    <script>
        // document.addEventListener('contentChanged', function(e) {
        //     var myChart = document.getElementById('myChart');
        //     var basic = e.basic;
        //     var basic_salary = e.basic_salary;
        //     var gross_pay = e.gross_pay;
        //     var net_pay = e.net_pay;
        //     var total_allowance = e.total_allowance;
        //     var total_deduction = e.total_deduction;
        // });
        // const DATA_COUNT = 7;
        // const NUMBER_CFG = {count: DATA_COUNT, min: -100, max: 100};
        window.addEventListener('refresh-page', event => {
            window.location.reload(false);
        })
        const labels = @json($basic_salary['months']);
        console.log(labels);
        const data = {
            labels: labels,
            {{--                labels: @json($data->map(fn ($data) => $data->date)),--}}
                {{--datasets: [{--}}
                {{--    label: 'Registered users in the last 30 days',--}}
                {{--    backgroundColor: 'rgba(255, 99, 132, 0.3)',--}}
                {{--    borderColor: 'rgb(255, 99, 132)',--}}
                {{--    data: @json($data->map(fn ($data) => $data->aggregate)),--}}
                {{--}],--}}
            datasets: [
                {
                    label: 'Basic Salary',
                    backgroundColor: 'rgba(161,255,99,0.3)',
                    borderColor: 'rgb(115,255,99)',
                    data:  @json($basic->map(fn ($data) => $data->total)),
                    pointStyle: 'circle',
                    pointRadius: 7,
                    pointHoverRadius: 9
                },
                {
                    label: 'Gross pay',
                    backgroundColor: 'rgba(121,246,232,0.3)',
                    borderColor: 'rgb(35,112,130)',
                    data: @json($gross_pay->map(fn ($data) => $data->total)),
                    pointStyle: 'circle',
                    pointRadius: 7,
                    pointHoverRadius: 9,

                },
                {
                    label: 'Total Allowance',
                    backgroundColor: 'rgba(17,85,69,0.3)',
                    borderColor: 'rgb(49,3,64)',
                    data: @json($total_allowance->map(fn ($data) => $data->total)),
                    pointStyle: 'circle',
                    pointRadius: 7,
                    pointHoverRadius: 9,
                    fill: true,
                },
                {
                    label: 'Total Deduction',
                    backgroundColor: 'rgba(255, 99, 132, 0.3)',
                    borderColor: 'rgb(255, 99, 132)',
                    data: @json($total_deduction->map(fn ($data) => $data->total)),
                    pointStyle: 'circle',
                    pointRadius: 7,
                    pointHoverRadius: 9,


                },
                {
                    label: 'Net pay',
                    backgroundColor: 'rgba(99,195,255,0.3)',
                    borderColor: 'rgb(99,130,255)',
                    data: @json($net_pay->map(fn ($data) => $data->total)),
                    pointStyle: 'circle',
                    pointRadius: 7,
                    pointHoverRadius: 9,
                    borderDash: [5, 5],
                    // fill: false,

                }
            ]
        };
        // const config = {
        //     type: 'bar',
        //     data: data
        // };
        const config = {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                stacked: false,
                plugins: {
                    title: {
                        display: true,
                        // text: 'Chart.js Line Chart - Multi Axis',
                        text: (ctx) => 'Point Style: ' + ctx.chart.data.datasets[0].pointStyle,
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',

                        // grid line settings
                        grid: {
                            drawOnChartArea: false, // only want the grid lines for one axis to show up
                        },
                    },
                }
            },
        };
        const myChart = new Chart(
            document.getElementById('myChart'),
            config
        );

    </script>
@endpush
<div>
    <label for="">Report Type</label><br>
    <input type="radio" wire:model="report_type" value="1" class="form-control-sm"><label for="">Annual Salaries Trend</label><br>
    <input type="radio" wire:model="report_type" value="2" class="form-control-sm"><label for="">Annual Staff Trend</label>
    <div class="float-right">
        <label for="">Chose Report Month/year</label> <select type="month" wire:model.defer="year" wire:change="onYearChange" class="form-control-sm">
            @php
                $firstYear = \Illuminate\Support\Carbon::now()->subYears(10)->format('Y');
                $lastYear = \Illuminate\Support\Carbon::now()->format('Y');
            @endphp
            @for($i=$firstYear;$i<=$lastYear;$i++)
                <option value="{{$i}}" @if(date('Y') == $i) selected @endif>{{$i}}</option>
            @endfor
        </select>
    </div>

</div>
