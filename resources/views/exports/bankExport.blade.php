<table class="table table-stripped table-bordered">
    <thead>
    <tr>
        <th>S/N</th>
        <th>bank_code</th>
        <th>bank_name</th>
        <th>bank_branch</th>
        <th>status</th>
    </tr>
    </thead>
    <tbody>
    @forelse($exports as $export)

        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$export->bank_code}}</td>
            <td>{{$export->bank_name}}</td>
            <td>{{$export->bank_branch}}</td>
            <td>{{$export->status}}</td>
        </tr>
    @empty
        no record
    @endforelse
    </tbody>

</table>
