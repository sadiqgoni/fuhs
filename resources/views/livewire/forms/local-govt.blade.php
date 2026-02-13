<div>
    <style>
        svg{
            display: none;
        }
    </style>
    <div class="row mt-3">
        <div class="col ">
            <div>
                <label for="">Search</label>
                <input type="text" class="form-control-sm" wire:model.live="search">

                <label for="">Show</label>
                <select name="" id="" class="form-control-sm" wire:model.live="perpage">
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>

                <div class="p-4" style="position: absolute;right: 0">
                    @if($create==true)
                        <form action="" wire:submit.prevent="store()" style="background: white;min-width: 30%" class="p-4">
                            <fieldset>
                                <legend>Add Local Government</legend>
                                <div class="form-group">
                                    <label for="">LGA Name @error('name')<small class="text-danger">{{$message}}</small> @enderror</label>
                                    <input type="text" class="form-control" wire:model.lazy="name" placeholder="LGA Name">
                                </div>
                                <div class="form-group">
                                    <label for="">State @error('state_id')<small class="text-danger">{{$message}}</small> @enderror</label>
                                    <select class="form-control" wire:model.lazy="state_id">
                                        <option value="">Select State</option>
                                        @foreach($states as $state)
                                            <option value="{{$state->id}}">{{$state->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </fieldset>
                            <div class="form-group mt-2">
                                <button class="btn save_btn" type="submit">Save</button>
                                <button class="btn close_btn" type="submit" wire:click.prevent="close">Close</button>
                            </div>
                        </form>
                    @endif

                    @if($edit==true)
                        <form action="" wire:submit.prevent="update({{$ids}})" style="background: white;min-width: 30%" class="p-4">
                            <fieldset>
                                <legend>Edit Local Government</legend>
                                <div class="form-group">
                                    <label for="">LGA Name @error('name')<small class="text-danger">{{$message}}</small> @enderror</label>
                                    <input type="text" class="form-control" wire:model.lazy="name" placeholder="LGA Name">
                                </div>
                                <div class="form-group">
                                    <label for="">State @error('state_id')<small class="text-danger">{{$message}}</small> @enderror</label>
                                    <select class="form-control" wire:model.lazy="state_id">
                                        <option value="">Select State</option>
                                        @foreach($states as $state)
                                            <option value="{{$state->id}}">{{$state->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Status @error('status')<small class="text-danger">{{$message}}</small> @enderror</label>
                                    <select class="form-control" wire:model.lazy="status">
                                        <option value="1">Active</option>
                                        <option value="0">Discontinued</option>
                                    </select>
                                </div>
                            </fieldset>
                            <div class="form-group mt-2">
                                <button class="btn save_btn" type="submit">Update</button>
                                <button class="btn close_btn" type="submit" wire:click.prevent="close">Close</button>
                            </div>
                        </form>
                    @endif
                </div>
                @if($record==true)
                    <button class="btn create mb-2 float-right" wire:click.prevent="create_lga()">Add Local Government</button>
                @endif
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-sm">
                    <thead>
                    <div wire:loading  wire:target="store,close,create_lga"style="position: absolute;z-index: 9999;text-align: center;width: 100%;height: 50vh;padding: 25vh">
                        <div style="background: rgba(14,13,13,0.13);margin: auto;max-width:100px;">
                            <i class="fa fa-spin fa-spinner" style="font-size:100px"></i>
                        </div>
                    </div>
                    <tr>
                        <th>S/N</th>
                        <th>LGA Name</th>
                        <th>State</th>
                        <th>Status</th>
                        <th>Action </th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($lgas as $lga)
                        <tr>
                            <th>{{($lgas->currentPage() - 1) * $lgas->perPage() + $loop->index+1}}</th>
                            <td>{{$lga->name}}</td>
                            <td>{{$lga->state->name ?? 'N/A'}}</td>
                            <td>
                                @if($lga->status==1)
                                    <span class="badge badge-success">Active</span>
                                    <button class="float-right btn btn-sm btn-warning" wire:click.prevent="status_change({{$lga->id}})">Discontinue</button>
                                @else
                                    <span class="badge badge-danger">Discontinued</span>
                                    <button class="float-right btn btn-sm btn-success" wire:click.prevent="status_change({{$lga->id}})">Activate</button>
                                @endif
                            </td>
                            <td><button class="btn btn-sm btn-info" wire:click.prevent="edit_record({{$lga->id}})">Edit</button></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">No local governments found</td></tr>
                    @endforelse
                    </tbody>
                    <tr>
                        <td colspan="5">{{$lgas->links()}}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
