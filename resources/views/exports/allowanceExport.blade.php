<table class="table table-stripped table-bordered">
    <thead>
    <tr>
        <th>S/N</th>
        <th>code</th>
        <th>allowance_name</th>
        <th>description</th>
        <th>taxable</th>
        <th>status</th>
    </tr>
    </thead>
    <tbody>
    @forelse($exports as $export)

        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$export->code}}</td>
            <td>{{$export->allowance_name}}</td>
            <td>{{$export->description}}</td>
            <td>{{$export->taxable}}</td>
            <td>{{$export->status}}</td>

        </tr>
    @empty
        no record
    @endforelse
    </tbody>

</table>
