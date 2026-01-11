<div>
    {{-- The best athlete wants his opponent at his best. --}}

    <div class="row">
        <div class="col">
            @if(!is_null($setting))
                <form action="" wire:submit.prevent="update()">
                    <fieldset>
                        <legend>
                            <img src="{{asset('storage/'.$setting->logo)}}" alt="" style="width:50px;border-radius: 50%">
                        </legend>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="">Organisation Logo @error('logo') <strong class="text-danger">{{$message}}</strong> @enderror</label>
                                    <input type="file" class="form-control @error('logo') is-invalid @enderror" wire:model="logo">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="">Organisation Name @error('name') <strong class="text-danger">{{$message}}</strong> @enderror</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model.blur="name">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="">Organisation Address @error('address') <strong class="text-danger">{{$message}}</strong> @enderror</label>
                                    <input type="text" class="form-control @error('address') is-invalid @enderror" wire:model.blur="address">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="">Organisation Email @error('email') <strong class="text-danger">{{$message}}</strong> @enderror</label>
                                    <input type="text" class="form-control @error('email') is-invalid @enderror" wire:model.blur="email">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="">Set Default Paye Calculation Option @error('paye_calculation') <strong class="text-danger">{{$message}}</strong> @enderror</label>
                                    <select type="text" class="form-control @error('paye_calculation') is-invalid @enderror" wire:model.blur="paye_calculation">
                                        <option value="">Select a Default paye calculation</option>
                                        <option value="1">As define in Deduction Template</option>
                                        <option value="2">Formular 1</option>
                                        <option value="3">Formular 2</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="">Set Default Statutory Deduction Calculation option @error('statutory_deduction') <strong class="text-danger">{{$message}}</strong> @enderror</label>
                                    <select type="text" class="form-control @error('statutory_deduction') is-invalid @enderror" wire:model.blur="statutory_deduction">
                                        <option value="">Select Calculation</option>
                                        <option value="1">As Percentage of Basic</option>
                                        <option value="2">As Percentage of Gross</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="">2FA Type @error('two_factor_authentication_type') <strong class="text-danger">{{$message}}</strong> @enderror</label>
                                    <select type="text" class="form-control @error('two_factor_authentication_type') is-invalid @enderror" wire:model.blur="two_factor_authentication_type">
                                        <option value="">Select Authentication Method</option>
                                        <option value="1">Google 2FA</option>
                                        <option value="2">Random Words 2fa</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </fieldset>
                    <div class="mt-3">
                        <button class="save_btn btn float-right" type="submit">Update</button>
                    </div>
                </form>
            @else
                <form action="" wire:submit.prevent="store()">
                    <fieldset>
                        <legend><h6></h6></legend>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="">Application Logo @error('logo') <strong class="text-danger">{{$message}}</strong> @enderror</label>
                                    <input type="file" class="form-control @error('logo') is-invalid @enderror" wire:model="logo">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="">Application Name @error('name') <strong class="text-danger">{{$message}}</strong> @enderror</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model.blur="name">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="">Address @error('address') <strong class="text-danger">{{$message}}</strong> @enderror</label>
                                    <input type="text" class="form-control @error('address') is-invalid @enderror" wire:model.blur="address">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="">Email @error('email') <strong class="text-danger">{{$message}}</strong> @enderror</label>
                                    <input type="text" class="form-control @error('email') is-invalid @enderror" wire:model.blur="email">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="">Set Default Paye Calculation Option @error('paye_calculation') <strong class="text-danger">{{$message}}</strong> @enderror</label>
                                    <select type="text" class="form-control @error('paye_calculation') is-invalid @enderror" wire:model.blur="paye_calculation">
                                        <option value="">Select a Default paye calculation</option>
                                        <option value="1">Without Formula</option>
                                        <option value="2">Formular 1</option>
                                        <option value="3">Formular 2</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="">Set Default Statutory Deduction Cal @error('statutory_deduction') <strong class="text-danger">{{$message}}</strong> @enderror</label>
                                    <select type="text" class="form-control @error('statutory_deduction') is-invalid @enderror" wire:model.blur="statutory_deduction">
                                        <option value="">Select Calculation</option>
                                        <option value="1">As Percentage of Basic</option>
                                        <option value="2">As Percentage of Gross</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <div class="mt-3">
                        <button class="save_btn btn float-right" type="submit">Save</button>
                    </div>
                </form>
            @endif

        </div>
    </div>
    @section('title')
        Application Settings
    @endsection
    @section('page_title')
        Application Settings
    @endsection
</div>
