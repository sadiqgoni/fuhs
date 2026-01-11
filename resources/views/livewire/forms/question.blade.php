<div>
    {{-- Success is as dangerous as failure. --}}

    <style>
        svg{
            display: none;
        }
    </style>
    <div class="row mt-3">
        <div class="col ">
            <div wire:loading  style="position: absolute;z-index: 9999;text-align: center;width: 100%;height: 50vh;padding: 25vh">
                <div style="background: rgba(14,13,13,0.13);margin: auto;max-width:100px;">
                    <i class="fa fa-spin fa-spinner" style="font-size:100px"></i>
                </div>
            </div>
            @if($record==true)
                <div>
                    <label for="">Search</label>
                    <input type="text" class="form-control-sm" wire:model.live="search">

                    <label for="">Order By</label>
                    <select name="" id="" class="form-control-sm" wire:model.live="orderBy">
                        <option value=" "></option>
                        <option value="id">Id</option>
                        <option value="created_at">Date</option>
                    </select>
                    <label for="">Order</label>
                    <select name="" id="" class="form-control-sm" wire:model.live="orderAsc">
                        <option value=" "></option>
                        <option value="asc">Asc</option>
                        <option value="desc">Desc</option>
                    </select>

                    <label for="">Show</label>
                    <select name="" id="" class="form-control-sm" wire:model.live="perpage">
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>


                    <button class="btn  mb-2 float-right btn-info px-5 mx-3" wire:click.prevent="create_faq()">Add Faqs</button>

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
                            <th>Title</th>
                            <th>Body</th>
                            <th>Action </th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($records as $record)

                            <tr>
                                <th>{{($records->currentPage() - 1) * $records->perPage() + $loop->index+1}}</th>
                                <td>{{$record->questions}}</td>
                                <td>{{$record->answers}}</td>
                                <td>
                                    <button class="btn btn-sm btn-info" wire:click.prevent="edit_record({{$record->id}})">Edit</button>
                                    <button class="btn btn-sm btn-danger" wire:click.prevent="deleteId({{$record->id}})">Delete</button>
                                </td>
                            </tr>
                        @empty
                            no record
                        @endforelse
                        </tbody>
                        <tr>
                            <td colspan="6">{{$records->links()}}</td>
                        </tr>
                    </table>

                </div>
            @endif
            @if($create==true)
                <div class="row">
                    <div
                        x-data="{ isUploading: false, progress: 0 }"
                        x-on:livewire-upload-start="isUploading = true"
                        x-on:livewire-upload-finish="isUploading = false; progress = 0"
                        x-on:livewire-upload-error="isUploading = false"
                        x-on:livewire-upload-progress="progress = $event.detail.progress"
                    />
                        <form wire:submit.prevent="store">
                            <fieldset>
                                <legend><h6 class="">Add Faq</h6></legend>
                                @error('title')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><span class="d-none d-md-inline">Title</span> </span></div>
                                    <input class="form-control @error('title') is-invalid @enderror" wire:model.lazy="title" type="text">
                                    <div class="input-group-append"></div>
                                </div>
                                @error('video_file')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><span class="d-none d-md-inline">Video</span> File</span></div>
                                    <input class="form-control @error('video_file') is-invalid @enderror" wire:model.lazy="video_file" type="file">
                                    <div class="input-group-append" style="background: #0b2e13">
                                        <div x-show="isUploading" class="mt-2">
                                            <div class="w-full bg-gray-200 rounded">
                                                <div class="bg-blue-500 text-xs leading-none py-1 text-center text-white rounded"
                                                     :style="`width: ${progress}%`">
                                                    <span x-text="`${progress}%`"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @error('body')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                                <div class="input-group form-group" wire:ignore>
                                    {{--                                        <div class="input-group-prepend"><span class="input-group-text">Body<span class="d-none d-md-inline"></span></span></div>--}}
                                    <textarea  id="myeditor" class="form-control @error('body') is-invalid @enderror" wire:model="body" placeholder="body">Body
                                        </textarea>
                                    {{--                                        <dv class="input-group-append"></dv>--}}

                                </div>

                            </fieldset>

                            <div class="row mt-3">
                                <div class="col-12 col-md-8"><button class="btn save_btn" type="submit">Save</button>
                                    <button class="btn close_btn mt-2 mt-md-0 " wire:click.prevent="close">Close</button></div>
                            </div>
                        </form>

                    </div>


            @endif
            @if($edit==true)
                <div class="row">
                    <div class="col-12 col-md-10 offset-md-1">
                        @if($faqInfo->video_file !=null)

                            <video controls="controls">
                                <source src="{{asset('storage/'.$faqInfo->video_file)}}" type="video_type">
                            </video>
                        @endif

                        <form wire:submit.prevent="update({{$ids}})">
                            <div
                                x-data="{ isUploading: false, progress: 0 }"
                                x-on:livewire-upload-start="isUploading = true"
                                x-on:livewire-upload-finish="isUploading = false; progress = 0"
                                x-on:livewire-upload-error="isUploading = false"
                                x-on:livewire-upload-progress="progress = $event.detail.progress"
                            />

                            <fieldset>
                                <legend><h6 class="">Update Faq</h6></legend>
                                @error('title')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><span class="d-none d-md-inline">Title</span> </span></div>
                                    <input class="form-control @error('title') is-invalid @enderror" wire:model.lazy="title" type="text">
                                    <div class="input-group-append"></div>
                                </div>
                                @error('video_file')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><span class="d-none d-md-inline">Video</span> File</span></div>
                                    <input class="form-control @error('video_file') is-invalid @enderror" wire:model.lazy="video_file" type="file">
                                    <div class="input-group-append" style="background: darkseagreen">
                                        <div x-show="isUploading" class="mt-2">
                                            <div class="w-full bg-gray-200 rounded">
                                                <div class="bg-blue-500 text-xs leading-none py-1 text-center text-white rounded"
                                                     :style="`width: ${progress}%`">
                                                    <span x-text="`${progress}%`"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @error('body')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                                <div class="input-group form-group" wire:ignore>
                                    {{--                                        <div class="input-group-prepend"><span class="input-group-text">Body<span class="d-none d-md-inline"></span></span></div>--}}
                                    <textarea  id="myeditor" class="form-control @error('body') is-invalid @enderror" wire:model="body" placeholder="Body">Body
                                        </textarea>
                                    {{--                                        <dv class="input-group-append"></dv>--}}

                                </div>

                            </fieldset>

                            <div class="row mt-3">
                                <div class="col-12 col-md-8"><button class="btn save_btn" type="submit">Save Changes</button>
                                    <button class="btn close_btn mt-2 mt-md-0 " wire:click.prevent="close">Close</button></div>
                            </div>
                        </form>

                    </div>
                </div>


            @endif


    </div>
    </div>

@section('title')
  Help/ Faq
@endsection
@section('page_title')
  Help/ Faq
@endsection


</div>
