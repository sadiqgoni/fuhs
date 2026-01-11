<div>
    {{-- Close your eyes. Count to one. That is how long forever feels. --}}
    <style>

    </style>
    <div class="row">
        <div class="col-lg-12 p-3">
            <form action="" wire:submit="confirm()">
                <fieldset>
                    <div wire:loading  style="position: absolute;z-index: 9999;text-align: center;width: 100%;height: 50vh;padding: 25vh">
                        <div style="background: rgba(14,13,13,0.13);margin: auto;max-width:100px;">
                            <i class="fa fa-spin fa-spinner" style="font-size:100px"></i>
                        </div>
                    </div>
                    <legend>Employee Selection:</legend>
                    <div class="row">
                        <div class="col-12 col-md-4">
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
                        <div class="col-12 col-md-4">
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
                        <div class="col-12 col-md-4">
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
                    <div class="row">
                        <div class="col-12 col-md-6">
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
                        <div class="col-12 col-md-6">
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

                    </div>
                    @include('livewire.forms.ssd')



                </fieldset>

                <div class="row">
                    <div class="col-12 col-md-6">
                        <fieldset class="mt-3">
                            <div class="row text-center">
                                <div class="col-6">
                                    <input type="radio" wire:model.live="update_allow_deduct" value="1"><label>Update Allowance</label>
                                </div>
                                <div class="col-6">
                                    <input type="radio" wire:model.live="update_allow_deduct" value="2"><label>Update Deduction</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="">Allowance/Deduction Selection @error('selected_allow_deduct') <small class="text-danger">{{$message}}</small> @enderror</label>
                                    <select name="" id="" class="form-control-sm @error('selected_allow_deduct') is-invalid @enderror" wire:model.live="selected_allow_deduct">

                                        @if($update_allow_deduct==1)
                                            <option value="">Select Allowance</option>
                                            @foreach(\App\Models\Allowance::where('status',1)->get() as $allowance)
                                                <option value="{{$allowance->id}}">{{$allowance->allowance_name}}</option>
                                            @endforeach
                                        @endif
                                        @if($update_allow_deduct==2)
                                            <option value="">Select Deduction</option>
                                            @foreach(\App\Models\Deduction::where('status',1)->get() as $deduction)
                                                <option value="{{$deduction->id}}">{{$deduction->deduction_name}}</option>
                                            @endforeach
                                        @endif

                                    </select>
                                </div>
                                <div>
                                    @php

                                        @endphp
                                    <label for=""></label>
                                </div>
                            </div>
                            <div class="row">
                                @if($selected_allow_deduct != 1 || $this->update_allow_deduct==1)
                                <div class="col">
                                    <label for="">Percentage of Basic</label>
                                    <input type="text" class="form-control-sm @error('percentage_of_basic') is-invalid text-danger @enderror" wire:model.live="percentage_of_basic" placeholder="@error('percentage_of_basic') {{$message}} @enderror">
                                </div>

                                <div class="col">
                                    <label for="" class="d-block">Fixed Amount </label>
                                    <input type="text" class="form-control-sm @error('fixed_amount') is-invalid text-danger @enderror" wire:model.live="fixed_amount" placeholder="@error('fixed_amount') {{$message}} @enderror">NGN
                                </div>
                                @endif
                                    @if($selected_allow_deduct==1 && $paye_calculation==0)
                                        <div class="col">
                                            <label for="">Percentage of Basic</label>
                                            <input type="text" class="form-control-sm @error('percentage_of_basic') is-invalid text-danger @enderror" wire:model.live="percentage_of_basic" placeholder="@error('percentage_of_basic') {{$message}} @enderror">
                                        </div>
                                    @endif
                                @if($selected_allow_deduct==1 && $update_allow_deduct==2 )
                                        <div class="col">
                                            <label for="">Paye Calculation Option @error('paye_calculation') <strong class="text-danger">{{$message}}</strong>@enderror</label>
                                            <select type="text" class="form-control @error('paye_calculation') is-invalid @enderror" wire:model.live="paye_calculation">
                                                {{--                                    <option value="">Select paye calculation</option>--}}
                                                <option value="1">Use Deduction Template</option>
                                                <option value="2">Formular 1</option>
                                                <option value="3">Formular 2</option>
                                                <option value="0">As Percentage of Basic</option>

                                            </select>
                                        </div>

                                @endif
                            </div>

                        </fieldset>
                            @error('percentage_of_basic')
                            <span class="text-danger">{{$message}}</span>
                            @enderror

                        <br>
                        @error('fixed_amount')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>


                </div>

                <div class="row mt-3">
                    <div class="col text-right">
                        @can('can_save')
                            <button class="btn  save_btn" type="submit" >Apply <span wire:loading><i class="fa-fa-spin fa-spinner"></i></span></button>

                        @endcan
                        {{--                        <button class="btn  btn-danger">Cancel</button>--}}
                    </div>
                </div>
            </form>
        </div>
    </div>
    @if(!empty($failed_records))
        <table class="table">
            <thead>
            <tr>
                <th>SN</th>
                <th>Name</th>
                <th>Staff Number</th>
                <th>Netpay</th>
                <th>Netpay after <br>applying deduction</th>
                <th>status</th>
            </tr>
            </thead>
            <tbody>

            @forelse($failed_records as $record)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$record['full_name']}}</td>
                    <td>{{$record['staff_number']}}</td>
                    <td>{{$record['net_pay']}}</td>
                    <td>{{$record['new_net_pay']}}</td>
                    <td>Failed</td>
                </tr>
            @empty

            @endforelse
            </tbody>
        </table>
    @endif

    @section('title')
        Group Salary Update Center
    @endsection
    @section('page_title')
        Payroll Update / Group Update
    @endsection
</div>
