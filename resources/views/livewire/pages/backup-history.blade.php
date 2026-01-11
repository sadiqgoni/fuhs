<div>
    {{-- The Master doesn't talk, he acts. --}}
    <div>
        {{-- Stop trying to control. --}}
        <style>
            svg{
                display: none;
            }
        </style>
        <div >
            <label for="">Show</label>
            <select name="" id="" wire:model.live="perpage" class="form-control-sm">
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="250">250</option>
                <option value="500">500</option>
            </select>
            <label for="">Show</label>
            <select name="" id="" wire:model.live="backup_type" class="form-control-sm">
                <option value="">Select backup Type</option>
                <option value="1">Payroll Data</option>
                <option value="2">Loan Deduction Data</option>
                <option value="3">Employee Profile Data</option>
                <option value="4">Salary Update Data</option>
                <option value="5">Salary Template Data</option>
                <option value="6">Allowance Template Data</option>
                <option value="7">Deduction Template Data</option>
            </select>
            <label for=""> Date from</label>
            <input type="date" class="form-control-sm" wire:model.live="date_from">
            <label for=""> Date to</label>
            <input type="date" class="form-control-sm" wire:model.live="date_to">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr class="text-uppercase">
                        <th>SN</th>
                        <th>Backup Type</th>
                        <th>Storage Location</th>
                        <th>Date Range</th>
                        <th>Backup By</th>
                        <th>Date/Time</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($backups as $backup)
                        @php
                            $user=\App\Models\User::find($backup->backup_by)
                        @endphp
                        <tr>
                            <th>{{$loop->iteration}}</th>
                            <td>{{backup_type($backup->backup_type)}}</td>
                            <td class="text-capitalize">
                                @if($backup->backup_loc==1)
                                    Online
                                @else
                                    Local
                                @endif
                            </td>
                            <td>
                                @if($backup->date_from==$backup->date_to)
                                    {{\Illuminate\Support\Carbon::parse($backup->date_from)->format('F,Y')}}
                                @else
                                    {{\Illuminate\Support\Carbon::parse($backup->date_from)->format('F,Y')}}
                                    to
                                    {{\Illuminate\Support\Carbon::parse($backup->date_to)->format('F,Y')}}
                                @endif
                            </td>
                            <td class="text-capitalize">{{$user->name}}</td>
                            <td>{{\Illuminate\Support\Carbon::parse($backup->created_at)->toDayDateTimeString()}}</td>
                            <td>
                                @if($backup->backup_loc==1)
                                    <a href="{{route('download',$backup->backup_name)}}">Download</a>
                                @else
                                    Local
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">no record</td>
                        </tr>
                    @endforelse
                    </tbody>
                    <tfoot>
                    <tr>
                        <td>{{$backups->links()}}</td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        @section('title')
            Backup History
        @endsection
        @section('page_title')
            Other Reports / Backup History
        @endsection
    </div>

</div>
