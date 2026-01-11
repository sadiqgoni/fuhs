<div>
    {{-- Because she competes with no one, no one can compete with her. --}}
{{--    <div class="row">--}}
{{--        <div class="col-2">--}}

{{--        </div>--}}
{{--        <div class="col-10">--}}
            <form wire:submit.prevent="store()">
                <fieldset>
                    <legend> Personal Data {{$full_name}}</legend>


                    <div class="row">
                        <div class="col-12 col-lg-6">
                            @error('gender')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Gender</span></div>
                                <select class="form-control @error('gender') is-invalid @enderror" wire:model.blur="gender" >
                                    <option value="">Gender</option>
                                    @foreach(\App\Models\Gender::all() as $gender)
                                        <option value="{{$gender->id}}">{{$gender->name}}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            @error('tribe')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Tribe</span></div>
                                <select class="form-control @error('tribe') is-invalid @enderror" wire:model.blur="tribe" >
                                    <option value="">Select Tribe</option>
                                    @foreach(\App\Models\Tribe::all() as $tribee)
                                        <option value="{{$tribee->id}}">{{$tribee->name}}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            @error('religion')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Religion</span></div>
                                <select class="form-control @error('religion') is-invalid @enderror" wire:model.blur="religion">
                                    <option value="">Religion</option>
                                    @foreach(\App\Models\Religion::all() as $religion)
                                        <option value="{{$religion->id}}">{{$religion->name}}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            @error('marital_status')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Marital Status</span></div>
                                <select class="form-control @error('marital_status') is-invalid @enderror" wire:model.blur="marital_status">
                                    <option value="">Marital Status</option>
                                    @foreach(\App\Models\MaritalStatus::all() as $marital_status)
                                        <option value="{{$marital_status->id}}">{{$marital_status->name}}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            @error('nationality')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Nationality</span></div>
                                <select class="form-control @error('nationality') is-invalid @enderror" wire:model.blur="nationality">
                                    <option value="">Nationality</option>
                                    <option value="1" @if($nationality==1) selected @endif>Nigeria</option>
                                </select>

                                <div class="input-group-append"></div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-6">
                            @error('state_of_origin')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">State of Origin</span></div>
                                <select class="form-control @error('state_of_origin') is-invalid @enderror" wire:model.blur="state_of_origin">
                                    <option value="" class="">Select State</option>
                                    @forelse($states as $state)
                                        <option value="{{$state->id}}">{{$state->name}}</option>
                                    @empty

                                    @endforelse
                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            @error('local_government')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">LGA</span></div>
                                <select class="form-control @error('local_government') is-invalid @enderror" wire:model.blur="local_government" >
                                    <option value="">Select LGA</option>
                                    @forelse($lgas as $lga)
                                        <option value="{{$lga->id}}">{{$lga->name}}</option>
                                    @empty
                                    @endforelse
                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-6">
                            @error('whatsapp_number')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">WhatsApp Number</span></div>
                                <input class="form-control @error('whatsapp_number') is-invalid @enderror" wire:model.blur="whatsapp_number" type="text">
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                    </div>

                    <legend>Next of Kin</legend>

                    <div class="row">
                        <div class="col-12 col-lg-6">
                            @error('name_of_next_of_kin')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Name</span></div>
                                <input class="form-control @error('name_of_next_of_kin') is-invalid @enderror" wire:model.blur="name_of_next_of_kin" type="text">
                                <div class="input-group-append"></div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-6">
                            @error('next_of_kin_phone_number')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Phone Number</span></div>
                                <input class="form-control @error('next_of_kin_phone_number') is-invalid @enderror" wire:model.blur="next_of_kin_phone_number" type="text">
                                <div class="input-group-append"></div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-6">
                            @error('relationship')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Relationship</span></div>
                                <select class="form-control @error('relationship') is-invalid @enderror" wire:model.blur="relationship" >
                                    <option value="">Relationship</option>
                                    @foreach(\App\Models\Relationship::all() as $ralationship)
                                        <option value="{{$ralationship->id}}">{{$ralationship->name}}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-6">
                            @error('address')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Address</span></div>
                                <input class="form-control @error('address') is-invalid @enderror" wire:model.blur="address" type="text">
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <button class="btn save_btn float-right">Update</button>
            </form>

{{--        </div>--}}
{{--    </div>--}}
    @section('title')
        Employee Profile Update
    @endsection
    @section('page_title')
        Employee Profile Update
    @endsection
</div>
