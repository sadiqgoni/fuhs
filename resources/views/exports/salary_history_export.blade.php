<table>
    <thead>
        <tr>
            <th>id</th>
            <th>salary_month</th>
            <th>salary_year</th>
            <th>pf_number</th>
            <th>ip_number</th>
            <th>full_name</th>
            <th>unit</th>
            <th>department</th>
            <th>staff_category</th>
            <th>phone_number</th>
            <th>employment_type</th>
            <th>employment_status</th>
            <th>salary_structure</th>
            <th>grade_level</th>
            <th>step</th>
            <th>bank_code</th>
            <th>account_number</th>
            <th>bank_name</th>
            <th>pfa_name</th>
            <th>pension_pin</th>
            <th>basic_salary</th>
            @php
                $allowances = \App\Models\Allowance::all();
                $deductions = \App\Models\Deduction::all();
               @endphp

            @foreach($allowances as $allowance)
                <th>{{$allowance->allowance_name}}</th>
            @endforeach

            @foreach($deductions as $deduction)
                <th>{{$deduction->deduction_name}}</th>
            @endforeach

            <th>salary_areas</th>
            <th>total_allowance</th>
            <th>gross_pay</th>
            <th>total_deduction</th>
            <th>net_pay</th>
            <th>nhis</th>
            <th>employer_pension</th>
            <th>deduction_countdown</th>
            <th>salary_remark</th>
            <th>created_at</th>
            <th>updated_at</th>
            <th>date_month</th>
        </tr>
    </thead>
    <tbody>
        @foreach($histories as $history)
            <tr>
                <td>{{$history->id}}</td>
                <td>{{$history->salary_month}}</td>
                <td>{{$history->salary_year}}</td>
                <td>{{$history->pf_number}}</td>
                <td>{{$history->ip_number}}</td>
                <td>{{$history->full_name}}</td>
                <td>{{$history->unit}}</td>
                <td>{{$history->department}}</td>
                <td>{{$history->staff_category}}</td>
                <td>{{$history->phone_number}}</td>
                <td>{{$history->employment_type}}</td>
                <td>{{$history->employment_status}}</td>
                <td>{{$history->salary_structure}}</td>
                <td>{{$history->grade_level}}</td>
                <td>{{$history->step}}</td>
                <td>{{$history->bank_code}}</td>
                <td>{{$history->account_number}}</td>
                <td>{{$history->bank_name}}</td>
                <td>{{$history->pfa_name}}</td>
                <td>{{$history->pension_pin}}</td>
                <td>{{$history->basic_salary}}</td>

                @foreach($allowances as $allowance)
                    <td>{{$history->{'A' . $allowance->id} }}</td>
                @endforeach

                @foreach($deductions as $deduction)
                    <td>{{$history->{'D' . $deduction->id} }}</td>
                @endforeach

                <td>{{$history->salary_areas}}</td>
                <td>{{$history->total_allowance}}</td>
                <td>{{$history->gross_pay}}</td>
                <td>{{$history->total_deduction}}</td>
                <td>{{$history->net_pay}}</td>
                <td>{{$history->nhis}}</td>
                <td>{{$history->employer_pension}}</td>
                <td>{{$history->deduction_countdown}}</td>
                <td>{{$history->salary_remark}}</td>
                <td>{{$history->created_at}}</td>
                <td>{{$history->updated_at}}</td>
                <td>{{$history->date_month}}</td>
            </tr>
        @endforeach
    </tbody>
</table>