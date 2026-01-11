<x-mail::message>
    @php
        $staff_no=encrypt($data['payroll_number']);
        $from=encrypt($data['month_from']);
        $to=encrypt($data['month_to']);
        $url="localhost:8000/individual/payslip/mail/$staff_no/$from/$to";
    @endphp
# Dear {{$employee->full_name}},

<h3>    Find attached your salary payslip for the month of @if($data['month_from'] = $data['month_to']){{\Illuminate\Support\Carbon::parse(decrypt($from))->format('F Y')}}.@else{{\Illuminate\Support\Carbon::parse(decrypt($from))->format('F Y')}} - {{\Illuminate\Support\Carbon::parse(decrypt($to))->format('F Y')}}.@endif
</h3>
    <br>
<a href="{{route('payslip.mail',[$staff_no,$from,$to])}}" class="button view">View File</a>
<br>
{{--<x-mail::button :url='$url' color="success">--}}
{{--View File--}}
{{--</x-mail::button>--}}

<br>
<p>Note that, this email is auto generated from our integrated payroll management
        software. Do not reply to this email. For any enquiry, please contact us at payroll
        section, bursary department
</p>
    Thank you and have a great day,<br>
    Best regard,<br>
{{--{{ config('app.name') }}--}}
    FUE Payroll Software
</x-mail::message>
