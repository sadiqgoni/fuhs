<div>
    {{-- A good traveler has no fixed plans and is not intent upon arriving. --}}
    <style>
        svg{
            display: none;
        }
        p:first-letter{
            text-transform: uppercase;
        }
    </style>
    <div class="">
        <label for="">Show</label>
        <select name="" wire:model.live="perpage" id="" class="form-control-sm">
            <option value="50">50</option>
            <option value="100">100</option>
            <option value="250">250</option>
            <option value="500">500</option>
            <option value="1000">1000</option>
        </select>
        <label for=""> Date From</label>
        <input type="date" class="form-control-sm" wire:model.live="date_from">
        <label for=""> Date To</label>
        <input type="date" class="form-control-sm" wire:model.live="date_to">
        <div class="table-responsive">

            <table class="table table-stripped table-bordered">
                <thead>
                <tr class="text-uppercase">
                    <th>SN</th>
                    <th>Name</th>
                    <th>Action</th>
                    <th>Date/Time</th>
                </tr>
                </thead>
                <tbody>
                @forelse($logs as $log)
                    @php
                        $name=\App\Models\User::find($log->user_id);
                    @endphp
                    <tr>
                        <th>{{($logs->currentpage() -1 ) * $logs->perpage() + $loop->iteration}}</th>
                        <td><p class="text-capitalize">{{strtolower($name->name)}}</p></td>
                        <td><p>{{$log->action}}</p></td>
                        <td>{{\Illuminate\Support\Carbon::parse($log->created_at)->toDayDateTimeString()}}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">No record</td>
                    </tr>
                @endforelse
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="3">
                        {{$logs->links()}}
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @section('title')
        Audit Log
    @endsection
    @section('page_title')
       Security / Audit Log
    @endsection
</div>
