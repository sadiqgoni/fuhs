<table>
    <thead>
    <tr>
        <th>id</th>
        <th>employment_id</th>
        <th>full_name</th>
        <th>department</th>
        <th>staff_category</th>
        <th>employment_type</th>
        <th>staff_number</th>
        <th>payroll_number</th>
        <th>status</th>
        <th>salary_structure</th>
        <th>date_of_first_appointment</th>
        <th>date_of_last_appointment</th>
        <th>date_of_retirement</th>
        <th>contract_termination_date</th>
        <th>post_held</th>
        <th>grade_level</th>
        <th>step</th>
        <th>rank</th>
        <th>unit</th>
        <th>phone_number</th>
        <th>whatsapp_number</th>
        <th>email</th>
        <th>bank_name</th>
        <th>account_number</th>
        <th>bank_code</th>
        <th>pfa_name</th>
        <th>pension_pin</th>
        <th>date_of_birth</th>
        <th>gender</th>
        <th>religion</th>
        <th>tribe</th>
        <th>marital_status</th>
        <th>nationality</th>
        <th>state_of_origin</th>
        <th>local_government</th>
        <th>profile_picture</th>
        <th>tax_id</th>
        <th>bvn</th>
        <th>staff_union</th>
        <th>name_of_next_of_kin</th>
        <th>next_of_kin_phone_number</th>
        <th>relationship</th>
        <th>address</th>

        <th>employee_id</th>
        <th>basic_salary</th>
        @for($i = 1; $i <= 14; $i++)
            <th>A{{ $i }}</th>
        @endfor
        @for($i = 1; $i <= 50; $i++)
            <th>D{{ $i }}</th>
        @endfor

        <th>salary_arears</th>
        <th>gross_pay</th>
        <th>total_allowance</th>
        <th>total_deduction</th>
        <th>net_pay</th>
        <th>deduction_countdown</th>
        <th>nhis</th>
        <th>employer_pension</th>
        <th>created_at</th>
        <th>updated_at</th>
    </tr>
    </thead>
    <tbody>
    @foreach($employees as $employee)
        @php
            $salary = \App\Models\SalaryUpdate::where('employee_id', $employee->id)->first();
        @endphp
        <tr>
            <td>{{ $employee->id ?? '' }}</td>
            <td>{{ $employee->employment_id ?? '' }}</td>
            <td>{{ $employee->full_name ?? '' }}</td>
            <td>{{ $employee->department ?? '' }}</td>
            <td>{{ $employee->staff_category ?? '' }}</td>
            <td>{{ $employee->employment_type ?? '' }}</td>
            <td>{{ $employee->staff_number ?? '' }}</td>
            <td>{{ $employee->payroll_number ?? '' }}</td>
            <td>{{ $employee->status ?? '' }}</td>
            <td>{{ $employee->salary_structure ?? '' }}</td>
            <td>{{ $employee->date_of_first_appointment ?? '' }}</td>
            <td>{{ $employee->date_of_last_appointment ?? '' }}</td>
            <td>{{ $employee->date_of_retirement ?? '' }}</td>
            <td>{{ $employee->contract_termination_date ?? '' }}</td>
            <td>{{ $employee->post_held ?? '' }}</td>
            <td>{{ $employee->grade_level ?? '' }}</td>
            <td>{{ $employee->step ?? '' }}</td>
            <td>{{ $employee->rank ?? '' }}</td>
            <td>{{ $employee->unit ?? '' }}</td>
            <td>{{ $employee->phone_number ?? '' }}</td>
            <td>{{ $employee->whatsapp_number ?? '' }}</td>
            <td>{{ $employee->email ?? '' }}</td>
            <td>{{ $employee->bank_name ?? '' }}</td>
            <td>{{ $employee->account_number ?? '' }}</td>
            <td>{{ $employee->bank_code ?? '' }}</td>
            <td>{{ $employee->pfa_name ?? '' }}</td>
            <td>{{ $employee->pension_pin ?? '' }}</td>
            <td>{{ $employee->date_of_birth ?? '' }}</td>
            <td>{{ $employee->gender ?? '' }}</td>
            <td>{{ $employee->religion ?? '' }}</td>
            <td>{{ $employee->tribe ?? '' }}</td>
            <td>{{ $employee->marital_status ?? '' }}</td>
            <td>{{ $employee->nationality ?? '' }}</td>
            <td>{{ $employee->state_of_origin ?? '' }}</td>
            <td>{{ $employee->local_government ?? '' }}</td>
            <td>{{ $employee->profile_picture ?? '' }}</td>
            <td>{{ $employee->tax_id ?? '' }}</td>
            <td>{{ $employee->bvn ?? '' }}</td>
            <td>{{ $employee->staff_union ?? '' }}</td>
            <td>{{ $employee->name_of_next_of_kin ?? '' }}</td>
            <td>{{ $employee->next_of_kin_phone_number ?? '' }}</td>
            <td>{{ $employee->relationship ?? '' }}</td>
            <td>{{ $employee->address ?? '' }}</td>

            <td>{{ optional($salary)->employee_id ?? '' }}</td>
            <td>{{ optional($salary)->basic_salary ?? '' }}</td>

            @for($i = 1; $i <= 14; $i++)
                <td>{{ optional($salary)->{"A$i"} ?? '' }}</td>
            @endfor

            @for($i = 1; $i <= 50; $i++)
                <td>{{ optional($salary)->{"D$i"} ?? '' }}</td>
            @endfor

            <td>{{ optional($salary)->salary_arears ?? '' }}</td>
            <td>{{ optional($salary)->gross_pay ?? '' }}</td>
            <td>{{ optional($salary)->total_allowance ?? '' }}</td>
            <td>{{ optional($salary)->total_deduction ?? '' }}</td>
            <td>{{ optional($salary)->net_pay ?? '' }}</td>
            <td>{{ optional($salary)->deduction_countdown ?? '' }}</td>
            <td>{{ optional($salary)->nhis ?? '' }}</td>
            <td>{{ optional($salary)->employer_pension ?? '' }}</td>
            <td>{{ optional($salary)->created_at ?? '' }}</td>
            <td>{{ optional($salary)->updated_at ?? '' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
