<table class="table table-stripped table-bordered">
    <thead>
    <tr>
        <th>S/N</th>
        <th>code</th>
        <th>deduction_name</th>
        <th>description</th>
        <th>account_no</th>
        <th>account_name</th>
        <th>bank_code</th>
        <th>tin_number</th>
        <th>visibility</th>
        <th>deduction_type</th>
        <th>status</th>
    </tr>
    </thead>
    <tbody>
    @forelse($exports as $export)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$export->code}}</td>
            <td>{{$export->deduction_name}}</td>
            <td>{{$export->description}}</td>
            <td>{{$export->account_no}}</td>
            <td>{{$export->account_name}}</td>
            <td>{{$export->bank_code}}</td>
            <td>{{$export->tin_number}}</td>
            <td>{{$export->visibility}}</td>
            <td>{{$export->deduction_type}}</td>
            <td>{{$export->status}}</td>
        </tr>
    @empty
        no record
    @endforelse
    </tbody>

</table>
