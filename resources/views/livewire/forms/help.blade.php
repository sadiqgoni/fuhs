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
                        <option value="topic">Help Topic</option>
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

                        <button class="btn create mb-2 float-right" wire:click.prevent="create_record()">Add Topic</button>

{{--                    <button class="btn  mb-2 float-right btn-info px-5 mx-3" wire:click.prevent="create_faq()">Faqs</button>--}}

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
                            <th>Topic</th>
                            {{--                            <th>Status</th>--}}
                            <th>Action </th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($records as $record)

                            <tr>
                                <th>{{($records->currentPage() - 1) * $records->perPage() + $loop->index+1}}</th>
                                <td>{{$record->topic}}</td>
                                {{--                                <td>{{status($record->status)}}--}}{{--</td>--}}
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
                    <div class="col-12 col-md-10 offset-md-1">
                        <form wire:submit.prevent="store">
                            <div
                                x-data="{ isUploading: false, progress: 0 }"
                                x-on:livewire-upload-start="isUploading = true"
                                x-on:livewire-upload-finish="isUploading = false; progress = 0"
                                x-on:livewire-upload-error="isUploading = false"
                                x-on:livewire-upload-progress="progress = $event.detail.progress"
                            />
                            <fieldset>
                                <legend><h6 class="">Add Help Topic</h6></legend>
                                @error('topic')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><span class="d-none d-md-inline">Help</span> Topic</span></div>
                                    <input class="form-control @error('topic') is-invalid @enderror" wire:model.lazy="topic" type="text">
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
                                    <textarea  id="myeditor" class="form-control @error('body') is-invalid @enderror" wire:model="body" placeholder="Body">
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
                </div>

            @endif
            @if($edit==true)
                <div class="row">
                    <div class="col-12 col-md-10 offset-md-1">
                        @if($help_rec->video_file !=null)
                            <video controls>
                                <source  src="{{asset('storage/'.$help_rec->video_file)}}">
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
                                <legend><h6 class="">Update Help Topic</h6></legend>
                                @error('topic')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><span class="d-none d-md-inline">Help</span> Topic</span></div>
                                    <input class="form-control @error('topic') is-invalid @enderror" wire:model.lazy="topic" type="text">
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
                                    <textarea  id="myeditor" class="form-control @error('body') is-invalid @enderror" wire:model="body" placeholder="Body">
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

            @if($faq==true)
                <div>
                    <label for="">Search</label>
                    <input type="text" class="form-control-sm" wire:model.live="search">

                    <label for="">Order By</label>
                    <select name="" id="" class="form-control-sm" wire:model.live="orderBy">
                        <option value=" "></option>
                        <option value="id">Id</option>
                        <option value="created_at">Date</option>
                        <option value="topic">Help Topic</option>
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
                    <button class="btn  mb-2 btn-danger   float-right " wire:click.prevent="close">Close</button></div>

                <button class="btn  mb-2 float-right btn-info px-5 mx-3" wire:click.prevent="create_faq()">Faqs</button>

                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-sm">
                        <thead>

                        <tr>
                            <th>S/N</th>
                            <th>Questions</th>
                            <th>Answer </th>
                            <th>Status </th>


                        </tr>
                        </thead>
                        <tbody>
                        @forelse($faqs as $record)

                            <tr>
                                <th>{{($records->currentPage() - 1) * $records->perPage() + $loop->index+1}}</th>
                                <td>{{$record->questions}}</td>
                                <td>
                                    <div class="input-group form-group">
                                        {{--                                        <div class="input-group-prepend"><span class="input-group-text">{{$record->questions}}</span></div>--}}
                                        <input class="form-control  @error('answer') is-invalid @enderror"  wire:model.debounce.500ms="answers.{{$record->id}}" placeholder="Type your answer here...">

                                        {{--                                        <div class="input-group-append"><Button type="button" wire:click.prevent="reply({{$record->id}})">Save</Button></div>--}}
                                    </div>

                                </td>
                                <td>
                                    @if($record->status==1)
                                        <em>Visible</em>
                                        <button class="btn btn-sm btn-outline-warning mx-3" wire:click.prevent="show({{$record->id}})">Hide</button>
                                    @else
                                        <em>Hidden</em>
                                        <button class="btn btn-sm btn-outline-success mx-3" wire:click.prevent="show({{$record->id}})">Show</button>

                                    @endif
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

        </div>
    </div>
    @section('title')
       Help/ Help
    @endsection
    @section('page_title')
       Help/ Help
    @endsection
    @push('scripts')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.js"></script>
    @endpush
</div>
