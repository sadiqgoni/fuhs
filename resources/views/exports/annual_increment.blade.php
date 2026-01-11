<table class="table">
    <thead>
    <tr>
        <th>S/N</th>
        <th>MONTH/YEAR</th>
        <th>STAFF NO </th>
        <th>PAYROLL NO </th>
        <th>NAME </th>
        <th>CURRENT
            SALARY </th>
        <th>INCREMENT
            SALARY </th>
        <th>STATUS</th>
    </tr>
    </thead>
    <tbody>
    @forelse($histories as $history)
        @php
            $employee=\App\Models\EmployeeProfile::find($history->employee_id);
            $salary=\App\Models\SalaryUpdate::where('employee_id',$history->employee_id)->first();
        @endphp
        <tr>
            <th>{{$loop->iteration}}</th>
            <td>{{$history->increment_month}} {{$history->increment_year}}</td>
            <td>{{$employee?$employee->staff_number:''}}</td>
            <td>{{$employee?$employee->payroll_number:''}}</td>
            <td>{{$employee?$employee->full_name:''}}</td>
            <td>{{number_format($history->current_salary,2)}}</td>
            <td>{{number_format($history->new_salary,2)}}</td>
            <td>{{success_status($history->status)}}</td>
        </tr>
    @empty
        no record
    @endforelse
    </tbody>

</table>
