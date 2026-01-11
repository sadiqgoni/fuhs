<table id="header">
{{--    <img src="{{url(logo())}}" alt="" style="width: 50px;position: absolute;left: 50px">--}}
    <tr style="padding: 0;margin: 0;text-align: center;text-transform: uppercase"><td colspan="4" style="text-align: center">{{app_settings()->name}}</td></tr>
    <tr style="padding: 0;margin: 0;text-align: center"><td colspan="4" style="text-align: center">{{address()}}</td></tr>
    <tr style="position: absolute;right:50px;margin-top: 20px;margin-bottom: 0px " ><td colspan="4" style="text-align: center">Month: {{\Illuminate\Support\Carbon::parse($date_from)->format('F Y')}}</td></tr>
    <tr style="text-align: center !important;margin: 10px 0;text-transform: uppercase;"><td colspan="4" style="text-align: center">Salary Deduction Report</td></tr>

</table>
@forelse($reports as $report)
    @php
        //dd(\App\Models\TemporaryDeduction::where('deduction_id',1)->where('amount','>',1)->count());
        $deduct_name=\App\Models\Deduction::find($report[0]['deduction_id'])
    @endphp
    @if(\App\Models\TemporaryDeduction::where('deduction_id',$report[0]['deduction_id'])->where('amount','>',1)->count())
        <table>
        <tr style="margin-top: 50px;padding: 2px;text-transform: capitalize;"><td colspan="4">{{$deduct_name->code}}:{{$deduct_name->deduction_name}}</td></tr>
        <tr style="margin: 0;padding: 2px;text-transform: capitalize"><td colspan="4">{{$deduct_name->description}}</td></tr>
        </table>
        <table border="0" style="width:100%;border-collapse: collapse">

            <thead>
            <tr>
{{--                <th>S/N</th>--}}
                <th>STAFF No</th>
                <th>Payroll No</th>
                <th>STAFF NAME</th>
                <th>AMOUNT</th>
            </tr>
            </thead>
            <tbody>
            @php
                $total=0;
$counter=1;
            @endphp
            @forelse($report as $index=>$item)
                @php
                    $emp=\App\Models\EmployeeProfile::where('staff_number',$item->staff_number)->first();
                @endphp
                @if($item->amount <= 0)
                    @continue
                @else

                    <tr style="text-align: center">
{{--                        <th>{{$counter}}</th>--}}
                        <td>{{$item->staff_number}}</td>
                        <td>{{$emp->payroll_number??null}}</td>
                        <td>{{$item->staff_name}}</td>
                        <td>{{number_format($item->amount,2)}}</td>
                    </tr>
                @endif
                @php
                    $total +=round($report->sum('amount'));
$counter++
                @endphp
            @empty
            @endforelse
            </tbody>

            <tr style="border-collapse: collapse;border: 0">
                <td colspan="3" style="text-align: right;font-weight: 100;border-collapse: collapse;border: 0">Total</td>
                <td colspan="1" style="text-align: right;font-weight: 100;border-collapse: collapse;border: 0">{{number_format($report->sum('amount'),2)}}</td>
            </tr>
{{--            <tr style="border:0">--}}
{{--                <td colspan="4" style="border:0">--}}
{{--                    <div>--}}
{{--                        <p>Approved by:</p>--}}
{{--                        <p>Name:.........................................</p>--}}
{{--                        <p>Sign & Date:..................................</p>--}}
{{--                    </div>--}}
{{--                </td>--}}
{{--            </tr>--}}
            <!--<tfoot>-->
            <!--</tfoot>-->
        </table>


    @endif
    <!--<p id="footer" style="break-after: page;">......................................................</p>-->
    <p style="page-break-before: always"></p>
@empty

@endforelse
