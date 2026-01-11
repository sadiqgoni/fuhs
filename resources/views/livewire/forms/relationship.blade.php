<div>
    {{-- Nothing in the world is as soft and yielding as water. --}}
        {{-- The Master doesn't talk, he acts. --}}
        <style>
            svg{
                display: none;
            }
        </style>
    <div wire:loading  style="position: absolute;z-index: 9999;text-align: center;width: 100%;height: 50vh;padding: 25vh">
        <div style="background: rgba(14,13,13,0.13);margin: auto;max-width:100px;">
            <i class="fa fa-spin fa-spinner" style="font-size:100px"></i>
        </div>
    </div>
        <div class="row mt-3">
            <div class="col table-responsive">
                {{--            <h6 class="text-center text-dark">KEEP RECORD OF ANNUAL INCREMENT HISTORY</h6>--}}
                <div>
                    @if($create==true || $edit==true)
                        <div class="row">
                            <div class="col-12 col-md-6 col-lg4 offset-md-6 offset-lg-6">
                                <fieldset class="px-4">
                                    <legend>

                                        @if($edit==true)
                                            <h6> Update Relationship </h6>
                                        @endif
                                        @if($create==true)
                                            <h6> Add Relationship </h6>
                                        @endif
                                    </legend>
                                    <form class="">


                                        <input type="text" wire:model="name" class="form-control " placeholder="Relationship Name">
                                        @if($create==true)
                                            <button wire:click.prevent="store()" class="mt-2 btn save_btn">Save</button>  @error('name')
                                            <small class="text-danger">{{$message}}</small>
                                            @enderror
                                            <button class="btn close_btn float-right mt-2" wire:click.prevent="close">Cancel</button>

                                        @endif
                                        @if($edit==true)
                                            <button wire:click.prevent="update({{$ids}})" class="mt-2 btn save_btn">Update</button>  @error('name')
                                            <small class="text-danger">{{$message}}</small>
                                            @enderror
                                            <button class="btn close_btn float-right mt-2" wire:click.prevent="close">Cancel</button>

                                        @endif
                                    </form>
                                </fieldset>
                            </div>
                        </div>
                    @endif
                    @if($create==false && $edit==false)
                        <button class="btn create float-right" wire:click.prevent="create_post()">Add</button>
                    @endif
                    <form action="" class="">
                       <label for="">Search</label> <input type="text" class="form-control-sm" wire:model.live="search">
                        <label for="">Show</label>  <select name="" id="" class="form-control-sm" wire:model.live="perpage">
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </form>
                </div>
                <div class="table-responsive">
                <table class="table table-striped table-bordered table-sm">
                    <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Relationship Name</th>
                        <th>Status </th>
                        <th>Action </th>

                    </tr>
                    </thead>
                    <tbody>
                    @forelse($posts as $post)

                        <tr>
                            <th>{{($posts->currentPage() - 1) * $posts->perPage() + $loop->index+1}}</th>
                            <td>{{$post->name}}</td>
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
            Relationship
        @endsection
        @section('page_title')
        Employees / Relationship
        @endsection

</div>
