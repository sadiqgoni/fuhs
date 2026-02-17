<div>
    <div class="row">
        <div class="col-lg-12 p-3">
            <form action="" wire:submit="confirm()">
                <fieldset>
                    <div wire:loading style="position: absolute;z-index: 9999;text-align: center;width: 100%;height: 50vh;padding: 25vh">
                        <div style="background: rgba(14,13,13,0.13);margin: auto;max-width:100px;">
                            <i class="fa fa-spin fa-spinner" style="font-size:100px"></i>
                        </div>
                    </div>
                    <legend class="mb-3">Employee Selection</legend>
                    <p class="text-muted small mb-3">Use the filters below to narrow the list, then check the staff you want to include in this group update.</p>

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

                    <div class="row mt-3">
                        <div class="col-12">
                            @error('specific_employee_ids')
                                <strong class="text-danger d-block form-text">{{ $message }}</strong>
                            @enderror
                            <div class="form-group">
                                <label class="font-weight-bold text-dark">Select employees (grouped by grade level)</label>
                                <div class="border rounded p-3" style="max-height: 500px; overflow-y: auto; background: #f8f9fa;">
                                    <div class="row">
                                        @forelse($specific_candidates as $groupName => $employees)
                                            <div class="col-md-4 mb-4">
                                                <div class="card shadow-sm h-100 border-primary">
                                                    <div class="card-header bg-white py-2 border-bottom">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <h6 class="mb-0 font-weight-bold text-dark" style="font-size: 0.9rem;">{{ $groupName }}</h6>
                                                            <span class="badge badge-primary">{{ count($employees) }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <div class="list-group list-group-flush" style="max-height: 250px; overflow-y: auto;">
                                                            @foreach ($employees as $emp)
                                                                <label class="list-group-item list-group-item-action d-flex align-items-center px-3 py-2 mb-0 cursor-pointer">
                                                                    <input type="checkbox" class="mr-3" value="{{ $emp->id }}" wire:model="specific_employee_ids">
                                                                    <div class="d-flex flex-column" style="line-height: 1.2;">
                                                                        <span class="font-weight-bold text-dark" style="font-size: 0.85rem;">{{ $emp->full_name }}</span>
                                                                        <small class="text-muted">{{ $emp->staff_number }}</small>
                                                                    </div>
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-12 text-center py-5 text-muted">
                                                <p class="mb-0">No employees match the current filters.</p>
                                                <small>Adjust Employment Type, Unit, Department, Salary Structure or Grade Level above.</small>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                                <small class="form-text text-muted mt-2">Check the boxes to select employees for this group allowance/deduction update.</small>
                            </div>
                        </div>
                    </div>

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
                                    {{-- For all allowances and non-PAYE deductions, allow percentage/fixed amount --}}
                                    <div class="col">
                                        <label for="">Percentage of Basic</label>
                                        <input type="text" class="form-control-sm @error('percentage_of_basic') is-invalid text-danger @enderror" wire:model.live="percentage_of_basic" placeholder="@error('percentage_of_basic') {{$message}} @enderror">
                                    </div>

                                    <div class="col">
                                        <label for="" class="d-block">Fixed Amount </label>
                                        <input type="text" class="form-control-sm @error('fixed_amount') is-invalid text-danger @enderror" wire:model.live="fixed_amount" placeholder="@error('fixed_amount') {{$message}} @enderror">NGN
                                    </div>
                                @endif

                                {{-- When updating PAYE as a deduction, always use Tax Bracket logic (no formula options) --}}
                                @if($selected_allow_deduct==1 && $update_allow_deduct==2 )
                                    <div class="col">
                                        <label class="d-block">PAYE Calculation</label>
                                        <small class="text-muted d-block">
                                            PAYE will be calculated automatically using the active Tax Bracket
                                        </small>
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
