<table class="table table-stripped table-bordered">
    <thead>
    <tr>
        <th>S/N</th>
        <th>name</th>
        <th>no_of_grade</th>
        <th>status</th>
    </tr>
    </thead>
    <tbody>
    @forelse($exports as $export)

        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$export->name}}</td>
            <td>{{$export->no_of_grade}}</td>
            <td>{{$export->status}}</td>

        </tr>
    @empty
        no record
    @endforelse
    </tbody>

</table>
