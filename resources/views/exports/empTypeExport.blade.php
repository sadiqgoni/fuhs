<table class="table table-stripped table-bordered">
    <thead>
    <tr>
        <th>S/N</th>
        <th>name</th>
        <th>status</th>
    </tr>
    </thead>
    <tbody>
    @forelse($exports as $export)

        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$export->name}}</td>
            <td>{{$export->status}}</td>

        </tr>
    @empty
        no record
    @endforelse
    </tbody>

</table>
