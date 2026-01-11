<table>
    <thead>
    <tr>
        <td colspan="10">The Manager, {{$payment_reports[0]['remark']}}</td>
    </tr>
    <tr>
        <td colspan="10">Guaranty Trust Bank, Zaria Road, Kano </td>
    </tr>
    <tr>
        <td colspan="10">Please credit the account(s) of the underlisted beneficiaries and debit our account Number: 302/617041/111</td>
    </tr>
    <tr>
        <th>S/N</th>
        <th>ACCT NUMBER</th>
        <th>AMOUNT</th>
        <th>BANK</th>
        <th>BRANCH</th>
        <th>SORT CODE</th>
        <th>REMARK</th>
        <th>STAFF No</th>
        <th>IPP No</th>
        <th>STAFF NAME</th>
    </tr>
    </thead>
    <tbody>
    @forelse($payment_reports as $index=>$report)

        <tr>
            <td>{{$index+1}}.</td>
            <td>{{$report->account_number}}</td>
            <td>{{number_format($report->amount,2)}}</td>
            <td>{{$report->bank}}</td>

            <td>{{$report->branch}}</td>
            <td>{{$report->sort_code}}</td>
            <td>{{$report->remark}}</td>
            <td>{{$report->staff_number}}</td>
            <td>{{$report->ipp_no}}</td>
            <td>{{$report->staff_name}}</td>
        </tr>


    @empty

    @endforelse

    </tbody>
    <tfoot>
    <tr>
        <td colspan="2"><h4 style="margin: 0">Grand Total</h4></td>
        <td colspan="7"><h4 style="margin: 0">{{number_format($payment_reports->sum('amount'),2)}}</h4></td>
        {{--        <td colspan="7"><h4 style="margin: 0">{{number_format($total_sal)}}</h4></td>--}}
    </tr>
    <tr>
        <td colspan="5">
            <p>Authorised Signature: ............................................................................</p>
            <p>Name: ................................................................................. Thumb Print</p>
            <div style="border:1px solid black;padding: 23px;float: right;margin-top: -58px"></div>
        </td>
        <td></td>
        <td colspan="3">
            <p>Authorised Signature: .........................................................................</p>
            <p>Name: ............................................................................. Thumb Print</p>
            <div style="border:1px solid black;padding: 23px;float: right;margin-top: -58px"></div>

        </td>
    </tr>
    </tfoot>
</table>

