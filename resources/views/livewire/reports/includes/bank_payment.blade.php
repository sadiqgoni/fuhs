<div class="container" style="overflow-x: hidden">
    <div class="row" style="overflow-x: hidden !important;">

        <p></p>


        <div class="col-12 mt-5">

            <div class="panel panel-default panel-table">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col col-xs-6">
                            <h3 class="panel-title">Bank Payment</h3>
                        </div>

                    </div>
                </div>
                @if(!empty($payment_reports))
                    {{--@if($reports->count()>0)--}}
                    <h6 style="text-align: right" >Month: {{\Illuminate\Support\Carbon::parse($date_from)->format('F Y')}}</h6>

                    {{--@endif--}}

{{--                    <h5 style="margin:1px 0 0 0">The Manager</h5>--}}
{{--                    <p style="margin:1px 0 0 0">Guaranty Trust Bank, Zaria Road, Kano </p>--}}

{{--                    <p style="margin: 0 0 20px 0">Please credit the account(s) of the underlisted beneficiaries and debit our account Number: 302/617041/111</p>--}}
                @can('can_export')

               <button class="btn export float-right" wire:click.prevent="export_bank_payment()">Export</button>
                    @endcan
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
                            <th>STAFF No</th>
                            <th>Payroll No</th>
                            <th>STAFF <br>NAME</th>
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
                            no record
                        @endforelse

                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="2"><h4 style="margin: 0;color: #1b1e21">Grand Total</h4></td>
                            <td colspan="7"><h4 style="margin: 0;color: #1b1e21">{{number_format($payment_reports->sum('amount'),2)}}</h4></td>
                            {{--        <td colspan="7"><h4 style="margin: 0">{{number_format($total_sal)}}</h4></td>--}}
                        </tr>
{{--                        <tr>--}}
{{--                            <td colspan="5">--}}
{{--                                <p>Authorised Signature: ............................................................................</p>--}}
{{--                                <p>Name: ................................................................................. Thumb Print</p>--}}
{{--                                <div style="border:1px solid black;padding: 23px;float: right;margin-top: -58px"></div>--}}
{{--                            </td>--}}
{{--                            <td></td>--}}
{{--                            <td colspan="3">--}}
{{--                                <p>Authorised Signature: .........................................................................</p>--}}
{{--                                <p>Name: ............................................................................. Thumb Print</p>--}}
{{--                                <div style="border:1px solid black;padding: 23px;float: right;margin-top: -58px"></div>--}}

{{--                            </td>--}}
{{--                        </tr>--}}
                        </tfoot>
                    </table>


               @endif

            </div>

        </div>
    </div>
</div>
