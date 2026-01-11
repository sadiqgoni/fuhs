<h6 class="text-center text-dark">Bank Payment</h6>
{{--<div>--}}

{{--    <button class="btn btn-sm btn-danger float-right ml-3 ">Print</button>--}}

{{--    <button class="btn btn-sm btn-dark float-right ">Export</button>--}}
{{--</div>--}}


<div class="table-responsive">

<table class="border-none" border="1" style="width: 100%; border-collapse: collapse;font-size: 12px">
    <thead>
    <tr>
        <th>S/N</th>
        <th>ACCT <br> NUMBER</th>
        <th>AMOUNT</th>
        <th>BANK</th>
        <th>BRANCH</th>
        <th>SORT <br> CODE</th>
        <th>REMARK</th>

    </tr>
    </thead>
    <tbody>
    @forelse($banks as $report)

        <tr>
            <td>{{$loop->iteration}}.</td>
            <td>{{$report->account_number}}</td>
            <td>{{number_format($report->net_pay,2)}}</td>
            <td>{{$report->bank_name}}</td>
            <td></td>
            <td></td>
{{--            <td>{{$report->branch}}</td>--}}
{{--            <td>{{$report->sort_code}}</td>--}}
            <td>{{$report->salary_remark}}</td>

        </tr>



    @empty

    @endforelse
    </tbody>
{{--    <tfoot>--}}
{{--    <tr>--}}
{{--        <td colspan="2"><h4 style="margin: 0">Grand Total</h4></td>--}}
{{--        <td colspan="7"><h4 style="margin: 0">{{number_format($banks->sum('net_pay'),2)}}</h4></td>--}}
{{--        --}}{{--        <td colspan="7"><h4 style="margin: 0">{{number_format($total_sal)}}</h4></td>--}}
{{--    </tr>--}}
{{--    <tr>--}}
{{--        <td colspan="5">--}}
{{--            <p>Authorised Signature: ............................................................................</p>--}}
{{--            <p>Name: ................................................................................. Thumb Print</p>--}}
{{--            <div style="border:1px solid black;padding: 23px;float: right;margin-top: -58px"></div>--}}
{{--        </td>--}}
{{--        <td></td>--}}
{{--        <td colspan="3">--}}
{{--            <p>Authorised Signature: .........................................................................</p>--}}
{{--            <p>Name: ............................................................................. Thumb Print</p>--}}
{{--            <div style="border:1px solid black;padding: 23px;float: right;margin-top: -58px"></div>--}}

{{--        </td>--}}
{{--    </tr>--}}
{{--    </tfoot>--}}
</table>

</div>
