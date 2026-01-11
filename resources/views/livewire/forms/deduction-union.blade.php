<div>
    {{-- Nothing in the world is as soft and yielding as water. --}}
    <div>
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
            <div class="col">
                {{--            <h6 class="text-center text-dark">KEEP RECORD OF ANNUAL INCREMENT HISTORY</h6>--}}
                <div>
                    @if($create==true)
                        <div class="row">
                            <div class="col-12 col-md-6 col-lg4 offset-md-6 offset-lg-6">
                                <fieldset class="px-4">
                                    <legend>


{{--                                        @if($create==true)--}}
{{--                                            <h6> Add Rank </h6>--}}
{{--                                        @endif--}}
                                    </legend>
                                    <form class="">

                                        <label for="deduction">Select Deduction</label>
                                        <select type="text" wire:model="deduction" class="form-control ">
                                            <option value="">Select Deduction</option>
                                            @foreach(\App\Models\Deduction::where('status',1)->get() as $ded)
                                                <option value="{{$ded->id}}">{{$ded->deduction_name}}</option>
                                            @endforeach
                                        </select>
                                        <label for="deduction">Select Union</label>
                                        <select type="text" wire:model="union" class="form-control ">
                                            <option value="">Select Deduction</option>
                                            @foreach(\App\Models\Union::where('status',1)->get() as $union)
                                                <option value="{{$union->id}}">{{$union->name}}</option>
                                            @endforeach
                                        </select>
                                        @if($create==true)
                                            <button wire:click.prevent="store()" class="mt-2 btn save_btn">Save</button>
                                            <button class="btn close_btn float-right mt-2" wire:click.prevent="close">Cancel</button>
                                        @endif

                                    </form>
                                </fieldset>
                            </div>
                        </div>
                    @endif
                    @if($create==false)
                        <button class="btn create float-right" wire:click.prevent="create_post()">Add</button>
                    @endif
                    <form action="" class="">
{{--                        <label for="">Search</label> <input type="text" class="form-control-sm" wire:model.live="search">--}}
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
                            <th>Deduction Name</th>
                            <th>Deduction Name </th>
                            <th>Action </th>

                        </tr>
                        </thead>
                        <tbody>
                        @forelse($unions as $union)
                                @php
                                    $deduction=\App\Models\Deduction::find($union->deduction_id);
                                    $union_name=\App\Models\Union::find($union->union_id)
                                @endphp
                            <tr>
                                <th>{{$loop->iteration}}</th>
                                <td>{{$deduction->deduction_name}}</td>
                                <td>{{$union_name->name}}</td>

                                <td><button class="btn btn-sm btn-danger" wire:click.prevent="deleteId({{$union->id}})">Delete</button></td>
                            </tr>
                        @empty
                            no record
                        @endforelse
                        </tbody>
{{--                        <tr>--}}
{{--                            <td colspan="6">{{$posts->links()}}</td>--}}
{{--                        </tr>--}}
                    </table>
                </div>
            </div>
        </div>
        @section('title')
            Deduction Union
        @endsection
        @section('page_title')
            Deduction Union
        @endsection
    </div>

</div>
