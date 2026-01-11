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
        <div wire:loading  style="position: absolute;z-index: 9999;text-align: center;width: 100%;height: 50vh;padding: 25vh">
            <div style="background: rgba(14,13,13,0.13);margin: auto;max-width:100px;">
                <i class="fa fa-spin fa-spinner" style="font-size:100px"></i>
            </div>
        </div>
        <div class="table-responsive">

            <table class="table-sm  table-bordered table-striped table ">
                <thead>
                <tr class="text-uppercase">
                    <th>SN</th>
                    <th>Restore Id</th>
                    <th>Date/Time</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($restores->unique('backup_id') as $restore)
                    <tr>
                        <th>{{$loop->iteration}}</th>
                        <td>{{$restore->action}}</td>

                        <td>{{\Illuminate\Support\Carbon::parse($restore->created_at)->addHour()->toDayDateTimeString()}}</td>
                        <td><button wire:click.prevent="store('{{$restore->backup_id}}')" type="button" class="btn-link border-0">Restore</button></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">no record</td>
                    </tr>
                @endforelse
                </tbody>
                <tfoot>
                <tr>
{{--                    <td>{{$restores->links()}}</td>--}}
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @section('title')
        Restore Point
    @endsection
    @section('page_title')
        Security/ Auto Restore points
    @endsection
</div>
