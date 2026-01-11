<table>
    <tr></tr>
    <tr> <th colspan="20" style="padding: 0;margin: 0;text-align: center;text-transform: uppercase">{{app_settings()->name}}</th></tr>
    <tr><th colspan="20" style="padding: 0;margin: 0;text-align: center">{{address()}}</th></tr>




    @php
        $payroll_date=$payrolls->first();
    @endphp
    <tr> <th colspan="20">Payroll Sheet for All Staff – @if($payrolls->count() > 0)
                {{$payroll_date[0]['salary_month']}}, {{$payroll_date[0]['salary_year']}}@endif</th></tr>
</table>
<table class="n-bordered border-none" style="width:100% !important;font-size: 12px;border-collapse: collapse;margin-bottom: -20px !important;" border="1" >

   <thead>
   <tr >
       <th class="hidden-xs">Sn</th>
       <th>Staff Id </th>
       <th>Payroll Id</th>
       <th>Name</th>
       <th>Cons</th>
       <th>Basic Sal</th>
       <th>Salary Arr</th>
       <th>Resp Allow </th>
       <th>Haz Allow </th>
       <th>NM Haz Allow </th>
       <th>C duty Allow</th>
       <th>Spec Allow </th>
       <th> Teach Allow</th>
       <th>Shift Allow  </th>
       <th> Other Allow1</th>
       <th>Other Allow2</th>
       <th>Paye</th>
       <th> Pension</th>
       <th>NHF</th>
       <th> Union 1 Dd</th>
       <th>SAL DED</th>
       <th> FUHSNICS</th>
       <th>ANUPA</th>
       <th> Page Loans </th>
       <th>Other Ded1 </th>
       <th>Other Ded2</th>
       <th>Union 2 Dd </th>
       <th>Bank Name </th>
       <th> Acc No</th>
       <th>Gross </th>
       <th>Total Ded</th>
       <th>Netpay</th>

   </tr>

   </thead>
    @php
        $counter=1;
    @endphp

    @forelse($payrolls as $key=>$payroll)
        @php
            $reports=$payroll;
            $group=$payroll->first();

            $a=$group[$name_search];
        @endphp


{{--        <tr style="border:0 !important">--}}
{{--            <td colspan="21" style="padding: 0;border:0 !important;text-align: left;text-transform: capitalize" > <h3 style="font-size: 12px;margin:20px 0 0 0 !important;">--}}
{{--                    <span style="text-transform: capitalize">  {{$name}}:</span> {{$a}}</h3></td>--}}
{{--        </tr>--}}


        <tbody>
        @forelse($reports as $index=>$report)
            @php
                $emp=\App\Models\EmployeeProfile::where('staff_number',$report->pf_number)->first();
            @endphp

            <tr>
                <td>
                    {{$counter ++}}
                </td>
                <td style="text-align: left">{{$report->pf_number}} </td>
                <td>{{$report->ip_number}}</td>

                <td> {{$report->full_name}}</td>
                <td>grade{{$report->grade_level."/".$report->step}}</td>
                <td>{{number_format($report->basic_salary,2)}} </td>
                <td> {{number_format($report->salary_areas,2)}}</td>
                <td>{{number_format($report->A1,2)}} </td>
                <td> {{number_format($report->A2,2)}}</td>
                <td>{{number_format($report->A3,2)}} </td>
                <td> {{number_format($report->A4,2)}}</td>
                <td>{{number_format($report->A5,2)}} </td>
                <td> {{number_format($report->A6,2)}}</td>
                <td>{{number_format($report->A7,2)}} </td>
                <td> {{number_format($report->A8,2)}}</td>
                <td>{{number_format($report->A9,2)}}</td>
                <td>{{number_format($report->D1,2)}} </td>
                <td> {{number_format($report->D2,2)}}</td>
                <td>{{number_format($report->D3,2)}} </td>
                <td> {{number_format($report->D4,2)}}</td>
                <td>{{number_format($report->D5,2)}} </td>
                <td> {{number_format($report->D6,2)}}</td>
                <td>{{number_format($report->D7,2)}} </td>
                <td> {{number_format($report->D8,2)}}</td>
                <td>{{number_format($report->D9,2)}} </td>
                <td> {{number_format($report->D10,2)}}</td>
                <td>{{number_format($report->D11,2)}}</td>
                <td> {{$report->bank_name}} </td>
                <td>{{$report->account_number}}</td>
                <td>{{number_format($report->gross_pay,2)}}</td>
                <td>{{number_format($report->total_deduction,2)}}</td>
                <td>{{number_format($report->net_pay,2)}}</td>
            </tr>
        @empty

        @endforelse
{{--        <tr>--}}
{{--            <td colspan="15"></td>--}}
{{--            <th colspan="2" style="text-align: right">Subtotal: </th>--}}
{{--            <th colspan="2">{{number_format($reports->sum('net_pay'),2)}}</th>--}}
{{--        </tr>--}}
        </tbody>

    @empty

    @endforelse
</table>


{{--<table>--}}
{{--    <tr>--}}
{{--        <th colspan="10" style="padding: 0;margin: 0;text-align: center;text-transform: uppercase">{{app_settings()->name}}</th>  </tr>--}}
{{--     <tr>   <th colspan="10" style="padding: 0;margin: 0;text-align: center">{{address()}}</th></tr>--}}
{{--     <tr>   <th colspan="10">Payroll Sheet for Staff – @if(isset($payroll_date)){{$payroll_date[0]['salary_month']}}, {{$payroll_date[0]['salary_year']}}@endif</th></tr>--}}
{{--     <tr>   <th colspan="10">SUMMARY FOR PAYROLL ENTRIES</th></tr>--}}

{{--</table>--}}

{{--<table style="width: 25%">--}}
{{--    @php--}}
{{--        $allowances=\App\Models\Allowance::all();--}}
{{--$total=0;--}}

{{--    @endphp--}}
{{--    <tbody>--}}
{{--    <tr>--}}
{{--        <td>xxxx</td>--}}
{{--        <td>Basic Salary</td>--}}
{{--        <td>@if(isset($summaries)) {{number_format($summaries->sum('basic_salary'),2)}} @endif</td>--}}
{{--    </tr>--}}
{{--    <tr>--}}
{{--        <td>xxxx</td>--}}
{{--        <td>Salary Arears</td>--}}
{{--        <td>@if(isset($summaries)) {{number_format($summaries->sum('salary_areas'),2)}} @endif</td>--}}
{{--    </tr>--}}
{{--    @php--}}
{{--        $a=0;--}}
{{--$b=0;--}}
{{--$c=0;--}}
{{--$d=0;--}}
{{--    @endphp--}}
{{--    @forelse($allowances as $index=>$allowance)--}}
{{--        <tr>--}}
{{--            <td>{{$allowance->code}}</td>--}}
{{--            <td>{{$allowance->allowance_name}}</td>--}}
{{--            <td>@if(isset($summaries)){{number_format($summaries->sum('A'.$allowance->id),2)}}@endif</td>--}}
{{--        </tr>--}}
{{--        @php--}}
{{--            if($summaries){--}}
{{--           $a= $total+=round($summaries->sum('A'.$allowance->id),2);--}}
{{--           $b= round($summaries->sum('basic_salary'),2);--}}
{{--           $c= round($summaries->sum('salary_areas'),2);--}}
{{--           $d=round($a+$b+$c,2);--}}
{{--        }--}}
{{--        @endphp--}}
{{--    @empty--}}

{{--    @endforelse--}}
{{--    </tbody>--}}
{{--    --}}{{--    <tfoot>--}}
{{--    <tr>--}}
{{--        <td style="padding: 7px 0 10px 0"></td>--}}
{{--        <th style="padding: 7px 0 10px 0">Gross Pay</th>--}}
{{--        <th style="padding: 7px 0 10px 0">{{number_format($d,2)}}</th>--}}
{{--    </tr>--}}
{{--    --}}{{--    </tfoot>--}}
{{--    @php--}}
{{--        $deductions=\App\Models\Deduction::all();--}}
{{--        $total1=0;--}}
{{--    @endphp--}}
{{--    <tbody style="margin-top: 30px">--}}
{{--    @forelse($deductions as $index=>$deduction)--}}
{{--        <tr>--}}
{{--            <td>{{$deduction->code}}</td>--}}
{{--            <td>{{$deduction->deduction_name}}</td>--}}
{{--            <td>@if(isset($summaries)){{number_format($summaries->sum('D'.$deduction->id),2)}}@endif</td>--}}
{{--        </tr>--}}
{{--        @php--}}
{{--            if (isset($summaries)){$total1+=round($summaries->sum('D'.$deduction->id),2);}--}}
{{--        @endphp--}}
{{--    @empty--}}

{{--    @endforelse--}}
{{--    </tbody>--}}
{{--    <tfoot>--}}
{{--    <tr>--}}
{{--        <td></td>--}}
{{--        <th>Total Deductions</th>--}}
{{--        <th>{{number_format($total1,2)}}</th>--}}

{{--    </tr>--}}
{{--    <tr>--}}
{{--        <th colspan="3" style="text-align: center"><P style="font-size: 12px">NET SAL PAYABLE: {{number_format($d-$total1,2)}}</P></th>--}}

{{--    </tr>--}}
{{--    <tr>--}}
{{--        <th style="text-align: left">--}}
{{--            <p style="margin: 0;padding: 5px 0;font-weight: bold">Inputed By:.............</p>--}}
{{--            <p style="margin: 0;padding: 5px 0;font-weight: bold">Sign and Date:.............</p>--}}
{{--        </th>--}}
{{--        <th></th>--}}
{{--        <th style="text-align: left">--}}
{{--            <p style="margin: 0;padding: 5px 0;font-weight: bold">Checked By:.............</p>--}}
{{--            <p style="margin: 0;padding: 5px 0;font-weight: bold">Sign and Date:.............</p>--}}

{{--        </th>--}}
{{--    </tr>--}}
{{--    <tr>--}}
{{--        <th style="text-align: left">--}}
{{--            <p style="margin: 0;padding: 5px 0;font-weight: bold">Reviewed By:.............</p>--}}
{{--            <p style="margin: 0;padding: 5px 0;font-weight: bold">Sing and Date:.............</p>--}}
{{--        </th>--}}
{{--        <th></th>--}}
{{--        <th style="text-align: left">--}}
{{--            <p style="margin: 0;padding: 5px 0;font-weight: bold">Audited By:.............</p>--}}
{{--            <p style="margin: 0;padding: 5px 0;font-weight: bold">Sign and Date:.............</p>--}}

{{--        </th>--}}
{{--    </tr>--}}
{{--    </tfoot>--}}
{{--</table>--}}
