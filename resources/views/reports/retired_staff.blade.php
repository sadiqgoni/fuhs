<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        tr td,tr th{
            border: 1px solid black;
        }
        *{
            font-size: 12px;
        }
        #footer { position: fixed; right: 0px; bottom: 10px; text-align: center;border-top: 1px solid black;}
        #footer .page:after { content: counter(page, decimal); }
        @page { margin: 20px 30px 40px 50px; }
    </style>
</head>
<body>
<img src="{{public_path('storage/'.app_settings()->logo)}}" alt="" style="width: 35px;position: absolute;left: 70px">
<h3 style="padding: 0;margin: 0;text-align: center;text-transform: uppercase">{{app_settings()->name}}</h3>
<h4 style="padding: 0;margin: 0;text-align: center">{{address()}}</h4>
<p style="margin: 10px 1px 1px 1px;text-align: center;text-transform: capitalize"> Retirement List </p>
<table style="border-collapse: collapse;width: 100%" class="mt-3 table table-bordered">
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
    @php
        $counter=1;
    @endphp
    @foreach($employees as $employee)
        @php
            $emp= \Illuminate\Support\Carbon::parse($employee->date_of_retirement)->diffInYears(\Illuminate\Support\Carbon::now()->format('Y-m-d'),false);
             $emp=abs($emp);
        @endphp
        @if( $emp <= 3)
            <tr>
                <th>{{ $counter}}</th>
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
        @php
            $counter++
        @endphp
        @else
            @continue
        @endif
    @endforeach
    </tbody>
</table>

</body>
</html>
