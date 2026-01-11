<div>
    {{-- The Master doesn't talk, he acts. --}}
    <div class="row">
        <div class="col-lg-12 p-3">
            <form>
                <fieldset>
                    <div wire:loading  style="position: absolute;z-index: 9999;text-align: center;width: 100%;height: 50vh;padding: 25vh">
                        <div style="background: rgba(14,13,13,0.13);margin: auto;max-width:100px;">
                            <i class="fa fa-spin fa-spinner" style="font-size:100px"></i>
                        </div>
                    </div>
                    <legend >
                    <h6>Employee Selection</h6>
                    </legend>
                    <div class="row">
                        <div class="col-12 col-lg-4">
                            @error('increment_date')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Increment Date</span></div>
                                <input type="month" class="form-control  @error('increment_date') is-invalid @enderror" wire:model.blur="increment_date" name="increment_date">

                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4">
                            @error('employment_type')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Employment Type</span></div>
                                <select class="form-control  @error('employment_type') is-invalid @enderror" wire:model.blur="employee_type" name="employee_type">
                                    <option value="">Employment Type</option>
                                    @foreach($types as $emp)
                                        <option value="{{$emp->id}}">{{$emp->name}}</option>

                                    @endforeach
                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4">
                            @error('staff_category')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Staff Category</span></div>
                                <select class="form-control  @error('staff_category') is-invalid @enderror" wire:model.blur="staff_category" name="staff_category">
                                    <option value="">Staff Category</option>
                                    @foreach($categories as $cat)
                                        <option value="{{$cat->id}}">{{$cat->name}}</option>

                                    @endforeach
                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-lg-4">
                            @error('unit')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Unit</span></div>
                                <select class="form-control @error('unit') is-invalid @enderror" wire:model.blur="unit" name="unit">
                                    <option value="">Select Unit</option>
                                    @foreach($units as $unit)
                                        <option value="{{$unit->id}}">{{$unit->name}}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4">
                            @error('department')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Department</span></div>
                                <select class="form-control @error('department') is-invalid @enderror" wire:model.blur="department" name="department">
                                    <option value="">Department</option>
                                    @foreach($departments as $dept)
                                        <option value="{{$dept->id}}">{{$dept->name}}</option>
                                    @endforeach

                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4">
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Status</span></div>
                                <select class="form-control  @error('status') is-invalid @enderror" wire:model.live="status">
                                    <option value="">Select Status</option>
                                    <option value="1">Active</option>
                                    <option value="2">Suspended</option>
                                    <option value="3">Dismissed</option>
                                    <option value="4">Transferred</option>
                                    <option value="5">Retired</option>
                                    <option value="6">Leave of Absence</option>
                                    <option value="7">Secondment</option>
{{--                                    <option value="8">Visiting Lecturers</option>--}}
{{--                                    <option value="9">Part-timers</option>--}}
                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                    </div>
                    @include('livewire.forms.ssd')

                    <div class="row">
                        <div class="col">
                            <label for="">Please increment salaries of selected staff by</label>
                            @error('number_of_increment')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <input type="number" class="form-control-sm @error('number_of_increment') is-invalid @enderror" wire:model.blur="number_of_increment" name="increment">
                            <label for="">Step<sub>(s)</sub></label>
                        </div>
                    </div>

                </fieldset>
                @can('can_save')
                    <button class="btn sm btn-warning save_btn mt-3 float-right" wire:click.prevent="confirm()" type="button">Apply</button>
                @endcan

            </form>




        </div>
    </div>

    @section('title')
        Staff Annual Salary Increment
    @endsection
    @section('page_title')
        Payroll Update / Annual Increment
    @endsection
</div>
