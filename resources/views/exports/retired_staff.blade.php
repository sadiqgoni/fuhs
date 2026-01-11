<table style="width: 100%;text-align: center;border: 0" border="0">
    <tr>
{{--        <td>    <img src="{{public_path(logo())}}" alt="" style="width: 3px;height:4px;"></td>--}}
        <td></td>
        <td colspan="8" style="text-align: center">
            <h3 style="padding: 0;margin: 0;text-align: center;text-transform: uppercase">{{app_settings()->name}}</h3>
            <h4 style="padding: 0;margin: 0;text-align: center">{{address()}}</h4>
            <h5 style="padding: 0;margin: 10px auto; text-align: center;font-size: 12px"> Retirement List </h5>

        </td>
    </tr>
</table>

<table class="mt-3 table table-bordered">
    <thead>
    <tr>
        <th>S/N</th>
        <th>STAFF NO</th>
        <th>IP NO</th>
        <th>FULL NAME</th>
        <th>DEPARTMENT</th>
        <th>DOB</th>
        <th>DFA</th>
        <th>DOR</th>
        <th>STATUS</th>
    </tr>
    </thead>
    <tbody>
    @foreach($employees as $employee)
        @php
            $emp= \Illuminate\Support\Carbon::parse($employee->date_of_retirement)->diffInYears(\Illuminate\Support\Carbon::now()->format('Y-m-d'),false);
             $emp=abs($emp);
        @endphp
        @if( $emp <= 3)
            <tr>
                <th>{{ $loop->iteration}}</th>
                <td>{{$employee->staff_number}}</td>
                <td>{{$employee->payroll_number}}</td>
                <td>{{$employee->full_name}}</td>
                <td>{{dept($employee->department)}}</td>
                <td>{{$employee->date_of_birth}}</td>
                <td>{{$employee->date_of_first_appointment}}</td>
                <td>{{$employee->date_of_retirement}}</td>
                <td>
                    @if($emp <= 0)
                        Retired
                    @else
                        {{$emp}}years Remaining
                    @endif
                </td>
            </tr>

        @else
            @continue
        @endif
    @endforeach
    </tbody>
</table>
