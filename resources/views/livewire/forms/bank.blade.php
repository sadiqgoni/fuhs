<div>
    {{-- Nothing in the world is as soft and yielding as water. --}}
    <div>
        {{-- The Master doesn't talk, he acts. --}}
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

                                    <legend>
                                        Add Bank
                                    </legend>
                                    <div class="form-group">
                                        <label for="">Bank Code @error('bank_code')<small class="text-danger">{{$message}}</small> @enderror</label>
                                        <input type="text" class="form-control" wire:model.lazy="bank_code" placeholder="Bank Code">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Bank Name @error('bank_name')<small class="text-danger">{{$message}}</small> @enderror</label>
                                        <input type="text" class="form-control" wire:model.lazy="bank_name" placeholder="Bank Name">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Bank Branch @error('bank_branch')<small class="text-danger">{{$message}}</small> @enderror</label>
                                        <input type="text" class="form-control" wire:model.lazy="bank_branch" placeholder="Bank Branch">
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
                                        <legend>
                                            Update Bank
                                        </legend>
                                        <div class="form-group">
                                            <label for="">Bank Code @error('bank_code')<small class="text-danger">{{$message}}</small> @enderror</label>
                                            <input type="text" class="form-control" wire:model.lazy="bank_code" placeholder="Bank Code">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Bank Name @error('bank_name')<small class="text-danger">{{$message}}</small> @enderror</label>
                                            <input type="text" class="form-control" wire:model.lazy="bank_name" placeholder="Bank Name">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Bank Branch @error('bank_branch')<small class="text-danger">{{$message}}</small> @enderror</label>
                                            <input type="text" class="form-control" wire:model.lazy="bank_branch" placeholder="Bank Branch">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Status @error('status')<small class="text-danger">{{$message}}</small> @enderror</label>
                                            <select type="text" class="form-control" wire:model.lazy="status">
                                                <option value="">Select Status</option>
                                                <option value="1" @if($status==1) selected @endif>Active</option>
                                                <option value="0" @if($status==0) selected @endif>Not Active</option>
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
                        <button class="btn create mb-2 float-right" wire:click.prevent="create_bank()">Add Bank</button>
                    @endif
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-sm">
                        <thead>
                        <div wire:loading  wire:target="store,close,create_bank"style="position: absolute;z-index: 9999;text-align: center;width: 100%;height: 50vh;padding: 25vh">
                            <div style="background: rgba(14,13,13,0.13);margin: auto;max-width:100px;">
                                <i class="fa fa-spin fa-spinner" style="font-size:100px"></i>
                            </div>
                        </div>
                        <tr>
                            <th>S/N</th>
                            <th>Bank Code</th>
                            <th>Bank Name</th>
                            <th>Branch</th>
                            <th>Status</th>
                            <th>Action </th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($posts as $post)

                            <tr>
                                <th>{{($posts->currentPage() - 1) * $posts->perPage() + $loop->index+1}}</th>
                                <td>{{$post->bank_code}}</td>
                                <td>{{$post->bank_name}}</td>
                                <td>{{$post->bank_branch}}</td>
                                <td>
                                    @if($post->status==1)
                                        <span class="badge badge-success">Active</span>
                                        <button class="float-right btn btn-sm btn-warning" wire:click.prevent="status_change({{$post->id}})">Discontinue</button>
                                    @else
                                        <span class="badge badge-danger">Discontinued</span>
                                        <button class="float-right btn btn-sm btn-success" wire:click.prevent="status_change({{$post->id}})">Activate</button>
                                    @endif
                                </td>
                                <td><button class="btn btn-sm btn-info" wire:click.prevent="edit_record({{$post->id}})">Edit</button></td>
                            </tr>
                        @empty
                            no record
                        @endforelse
                        </tbody>
                        <tr>
                            <td colspan="6">{{$posts->links()}}</td>
                        </tr>
                    </table>

                </div>
            </div>
        </div>
        @section('title')
            Banks
        @endsection
        @section('page_title')
            Employees / Banks
        @endsection
    </div>

</div>
