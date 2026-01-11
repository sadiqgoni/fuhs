
<div>
    <style>
        svg{
            display: none;
        }
    </style>
    {{-- Close your eyes. Count to one. That is how long forever feels. --}}
    <div wire:loading  style="position: absolute;z-index: 9999;text-align: center;width: 100%;height: 50vh;padding: 25vh">
        <div style="background: rgba(14,13,13,0.13);margin: auto;max-width:100px;">
            <i class="fa fa-spin fa-spinner" style="font-size:100px"></i>
        </div>
    </div>
    @if($record==true)
        <div>
            <label for="">Search</label> <input wire:model.live="search" type="text" class="form-control-sm">
            <label for="">Show</label>
            <select wire:model.lazy="perpage" class="form-control-sm">
                <option value=""></option>
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="30">30</option>
            </select>
            <button class="btn create float-right" wire:click="create_allowance()">Add</button>

        </div>
        <div class="table-responsive">
            <table class="table table-bordered mt-2">

                <thead>
                <tr style="text-transform: uppercase;">
                    <th>S/N</th>
                    <th>allowance code</th>
                    <th>allowance name</th>
                    <th>description</th>
                    <th>Taxable</th>
                    <th>status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($allowances as $allowance)
                    <tr>
                        <td>{{($allowances->currentPage() - 1) * $allowances->perpage() + $loop->index+1}}</td>
                        <td>{{$allowance->code}}</td>
                        <td>{{$allowance->allowance_name}}</td>
                        <td>{{$allowance->description}}</td>
                        <td>
                            @if($allowance->taxable==1)
                                <em class="badge badge-success">Yes</em>
                            @elseif($allowance->taxable==2)
                                <em class="badge badge-danger">No</em>
                            @else
                                <em class="badge badge-warning">Not Set</em>

                            @endif
                        </td>
                        <td>
                            {{allowance_status($allowance->status)}}
                        </td>
                        <td><button class="btn edit_btn float-right" wire:click="edit_allowance({{$allowance->id}})">Edit</button></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="color: red">Empty</td>
                    </tr>
                @endforelse
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="6">{{$allowances->links()}}</td>
                </tr>
                </tfoot>
            </table>
        </div>
        @endif
    @if($create==true)
        <div class="row">
            <div class="col-12 col-md-10 offset-md-1">
                <form wire:submit.prevent="store">
                    <fieldset>
                        <legend><h6 class="">Add Allowance</h6></legend>
                        @error('allowance_code')
                        <small class="text-danger">{{$message}}</small>
                        @enderror
                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text"><span class="d-none d-md-inline">Allowance</span> Code</span></div>
                            <input class="form-control @error('allowance_code') is-invalid @enderror" wire:model.lazy="allowance_code" type="text">
                            <div class="input-group-append"></div>
                        </div>
                        @error('allowance_name')
                        <small class="text-danger">{{$message}}</small>
                        @enderror
                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text"><span class="d-none d-md-inline">Allowance</span> Name</span></div>
                            <input class="form-control @error('allowance_name') is-invalid @enderror" wire:model.lazy="allowance_name" type="text">
                            <div class="input-group-append"></div>
                        </div>
                        @error('description')
                        <small class="text-danger">{{$message}}</small>
                        @enderror
                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text">Descr<span class="d-none d-md-inline">iption</span></span></div>
                            <input class="form-control @error('description') is-invalid @enderror" wire:model="description" type="text">
                            <dv class="input-group-append"></dv>
                        </div>

                        <div class="row">
                           <div class="col-12 col-md-6">
                               @error('status')
                               <small class="text-danger">{{$message}}</small>
                               @enderror
                               <div class="row">
                                   <div class="col">
                                       Status
                                   </div>
                                   <div class="col">
                                       <input class="@error('status') is-invalid @enderror" wire:model="status" value="1" type="radio"> Active
                                   </div>
                                   <div class="col">
                                       <input class="@error('status') is-invalid @enderror" wire:model="status" value="0" type="radio"> Discontinue
                                   </div>
                               </div>
                           </div>
                            <div class="col-12 col-md-6">
                                @error('taxable')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                                <div class="input-group-prepend"><span class="input-group-text">Tax<span class="d-none d-md-inline">able</span></span></div>
                                <select class="form-control @error('taxable') is-invalid @enderror" wire:model="taxable" >
                                    <option value="">Select if Taxable</option>
                                    <option value="1">Taxable</option>
                                    <option value="2">Not Taxable</option>
                                </select>
                                <dv class="input-group-append"></dv>
                            </div>
                        </div>
                    </fieldset>

                    <div class="row mt-3">
                        <div class="col-12 col-md-8"><button class="btn save_btn" type="submit">Save</button>
                        <button class="btn close_btn mt-2 mt-md-0 " wire:click.prevent="close">Close</button></div>
                    </div>
                </form>

            </div>
        </div>
        @endif
    @if($edit==true)
        <div class="row">
            <div class="col-12 col-md-10 offset-md-1">
                <form wire:submit.prevent="update({{$ids}})">
                    <fieldset>
                        <legend><h6 class="">Update Allowance</h6></legend>
                    @error('allowance_code')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                    <div class="input-group form-group">
                        <div class="input-group-prepend"><span class="input-group-text"><span class="d-none d-md-inline-block">Allowance </span> Code</span></div>
                        <input class="form-control @error('allowance_code') is-invalid @enderror" wire:model.lazy="allowance_code" type="text">
                        <div class="input-group-append"></div>
                    </div>
                    @error('allowance_name')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                    <div class="input-group form-group">
                        <div class="input-group-prepend"><span class="input-group-text"><span class="d-none d-md-inline-block">Allowance </span> Name</span></div>
                        <input class="form-control @error('allowance_name') is-invalid @enderror" wire:model.blur="allowance_name" type="text">
                        <div class="input-group-append"></div>
                    </div>
                    @error('description')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                    <div class="input-group form-group">
                        <div class="input-group-prepend"><span class="input-group-text">Desc<span class="d-none d-md-inline-block">ription</span></span></div>
                        <input class="form-control @error('description') is-invalid @enderror" wire:model="description" type="text">
                        <dv class="input-group-append"></dv>
                    </div>
                    @error('status')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                    <div class="row">
                        <div class="col-12 col-md-6">
                            @error('status')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                            <div class="row">
                                <div class="col">
                                    Status
                                </div>
                                <div class="col">
                                    <input class="@error('status') is-invalid @enderror" wire:model="status" value="1" type="radio"> Active
                                </div>
                                <div class="col">
                                    <input class="@error('status') is-invalid @enderror" wire:model="status" value="0" type="radio"> Discontinue
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            @error('taxable')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                            <div class="input-group-prepend"><span class="input-group-text">Tax<span class="d-none d-md-inline">able</span></span></div>
                            <select class="form-control @error('taxable') is-invalid @enderror" wire:model="taxable" >
                                <option value="">Select if Taxable</option>
                                <option value="1">Taxable</option>
                                <option value="2">Not Taxable</option>
                            </select>
                            <dv class="input-group-append"></dv>
                        </div>

                    </div>
                    </fieldset>

                    <div class="row mt-3">
                        <div class="col-12 col-md-8">
                            <button class="btn save_btn" wire:click="update({{$ids}})">Save</button>

                        <button class="btn close_btn mt-2 mt-md-0" wire:click.prevent="close()">Close</button></div>
                    </div>
                </form>

            </div>
        </div>
    @endif

    @section('title')
        Allowance
    @endsection
    @section('page_title')
        Payroll Settings / Allowance
    @endsection
</div>
