<div>
    {{-- Stop trying to control. --}}
    <style>
        svg{
            display: none;
        }
    </style>
    <div class="">
        <label for="">Show</label>
        <select name="" id="" wire:model.live="perpage" class="form-control-sm">
            <option value="50">50</option>
            <option value="100">100</option>
            <option value="250">250</option>
            <option value="500">500</option>
        </select>
        <label for="">Show</label>
        <select name="" id="" wire:model.live="restore_type" class="form-control-sm">
            <option value="">Select Restore Type</option>
            <option value="1">Payroll Data</option>
            <option value="2">Loan Deduction Data</option>
            <option value="3">Employee Data</option>
            <option value="4">Salary Update Data</option>
            <option value="5">Salary Template Data</option>
            <option value="6">Allowance Template Data</option>
            <option value="7">Deduction Template Data</option>
        </select>
        <label for="">Date from</label>
        <input type="date" class="form-control-sm" wire:model.live="date_from">
        <label for="">Date to</label>
        <input type="date" class="form-control-sm" wire:model.live="date_to">
        <div class="table-responsive">

            <table class="table table-striped table-bordered">
                <thead>
                <tr class="text-uppercase">
                    <th>SN</th>
                    <th>Restore Type</th>
                    <th>Restore By</th>
                    <th>Date/Time</th>
                </tr>
                </thead>
                <tbody>
                @forelse($restores as $restore)
                    @php
                        $user=\App\Models\User::find($restore->restore_by)
                    @endphp
                    <tr>
                        <th>{{$loop->iteration}}</th>
                        <td>{{$restore->restore_name}}</td>
                        <td class="text-capitalize">{{$user->name}}</td>
                        <td>{{\Illuminate\Support\Carbon::parse($restore->created_at)->toDayDateTimeString()}}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">no record</td>
                    </tr>
                @endforelse
                </tbody>
                <tfoot>
                <tr>
                    <td>{{$restores->links()}}</td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @section('title')
        Restore History
    @endsection
    @section('page_title')
     Other Reports / Restore History
    @endsection
</div>
