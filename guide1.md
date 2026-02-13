<div>
    {{-- The Master doesn't talk, he acts. --}}
    <div class="row">
        <div class="col-lg-12 p-3">
            <form>
                <fieldset>
                    <div wire:loading
                        style="position: absolute;z-index: 9999;text-align: center;width: 100%;height: 50vh;padding: 25vh">
                        <div style="background: rgba(14,13,13,0.13);margin: auto;max-width:100px;">
                            <i class="fa fa-spin fa-spinner" style="font-size:100px"></i>
                        </div>
                    </div>
                    <legend>
                        <h6>Employee Selection</h6>
                    </legend>
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="btn-group btn-group-toggle">
                                <label class="btn btn-secondary {{ $selection_mode == 'criteria' ? 'active' : '' }}"
                                    wire:click="$set('selection_mode', 'criteria')">
                                    <input type="radio" name="selection_mode" value="criteria" autocomplete="off" {{
                                        $selection_mode=='criteria' ? 'checked' : '' }}> Criteria Based
                                </label>
                                <label class="btn btn-secondary {{ $selection_mode == 'specific' ? 'active' : '' }}"
                                    wire:click="$set('selection_mode', 'specific')">
                                    <input type="radio" name="selection_mode" value="specific" autocomplete="off" {{
                                        $selection_mode=='specific' ? 'checked' : '' }}> Specific Employees
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-lg-4">
                            @error('increment_date')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Increment Date</span>
                                </div>
                                <input type="month" class="form-control  @error('increment_date') is-invalid @enderror"
                                    wire:model.blur="increment_date" name="increment_date">

                                <div class="input-group-append"></div>
                            </div>
                        </div>
                    </div>
                    @if($selection_mode == 'criteria')
                    <div class="row">
                        <div class="col-12 col-lg-4">
                            @error('employment_type')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Employment
                                        Type</span>
                                </div>
                                <select class="form-control  @error('employment_type') is-invalid @enderror"
                                    wire:model.blur="employee_type" name="employee_type">
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
                                <div class="input-group-prepend"><span class="input-group-text">Staff
                                        Category</span>
                                </div>
                                <select class="form-control  @error('staff_category') is-invalid @enderror"
                                    wire:model.blur="staff_category" name="staff_category">
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
                                <select class="form-control @error('unit') is-invalid @enderror" wire:model.blur="unit"
                                    name="unit">
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
                                <div class="input-group-prepend"><span class="input-group-text">Department</span>
                                </div>
                                <select class="form-control @error('department') is-invalid @enderror"
                                    wire:model.blur="department" name="department">
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
                                <select class="form-control  @error('status') is-invalid @enderror"
                                    wire:model.live="status">
                                    <option value="">Select Status</option>
                                    <option value="1">Active</option>
                                    <option value="2">Suspended</option>
                                    <option value="3">Dismissed</option>
                                    <option value="4">Transferred</option>
                                    <option value="5">Retired</option>
                                    <option value="6">Leave of Absence</option>
                                    <option value="7">Secondment</option>
                                    {{-- <option value="8">Visiting Lecturers</option>--}}
                                    {{-- <option value="9">Part-timers</option>--}}
                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                    </div>
                    @include('livewire.forms.ssd')
                    <div class="row mt-3">
                        <div class="col-12 col-lg-4">
                            @error('min_service_months')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Min. Service
                                        (Months)</span></div>
                                <input type="number"
                                    class="form-control @error('min_service_months') is-invalid @enderror"
                                    wire:model.blur="min_service_months" placeholder="e.g. 6">
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                    </div>

                    @if(!empty($preview_employees) && count($preview_employees) > 0)
                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <h6 class="mb-2"><i class="fa fa-info-circle"></i> Preview: Employees matching criteria
                                    & tenure ({{ count($preview_employees) }})</h6>
                                <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                                    <table class="table table-sm table-bordered bg-white">
                                        <thead class="thead-light sticky-top">
                                            <tr>
                                                <th>Name</th>
                                                <th>Date of Appointment</th>
                                                <th>Tenure (Months)</th>
                                                <th>Current Grade</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($preview_employees as $emp)
                                            <tr>
                                                <td>{{ $emp->full_name }} <small class="text-muted">({{
                                                        $emp->staff_number }})</small></td>
                                                <td>{{ \Carbon\Carbon::parse($emp->date_of_first_appointment)->format('d
                                                    M, Y') }}</td>
                                                <td>{{ $emp->service_months_diff ?? 'N/A' }} months</td>
                                                <td>Level {{ $emp->grade_level }} / Step {{ $emp->step }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <small class="text-muted">Showing first {{ count($preview_employees) }} matches. Adjust
                                    filters to refine.</small>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endif

                    @if($selection_mode == 'specific')
                    <div class="row">
                        <div class="col-12">
                            @error('specific_employee_ids')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="form-group">
                                <label>Select Employees</label>
                                <div class="border rounded p-3"
                                    style="max-height: 500px; overflow-y: auto; background: #f8f9fa;">
                                    <div class="row">
                                        @forelse($specific_candidates as $groupName => $employees)
                                        <div class="col-md-4 mb-4">
                                            <div class="card shadow-sm h-100">
                                                <div class="card-header bg-white py-2 border-bottom">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <h6 class="mb-0 font-weight-bold text-dark"
                                                            style="font-size: 0.9rem;">{{ $groupName }}</h6>
                                                        <span class="badge badge-light border text-muted">{{
                                                            count($employees) }}</span>
                                                    </div>
                                                </div>
                                                <div class="card-body p-0">
                                                    <div class="list-group list-group-flush"
                                                        style="max-height: 250px; overflow-y: auto;">
                                                        @foreach($employees as $emp)
                                                        <label
                                                            class="list-group-item list-group-item-action d-flex align-items-center px-3 py-2 mb-0 cursor-pointer">
                                                            <input type="checkbox" class="mr-3" value="{{ $emp->id }}"
                                                                wire:model="specific_employee_ids">
                                                            <div class="d-flex flex-column" style="line-height: 1.2;">
                                                                <span class="font-weight-bold text-dark"
                                                                    style="font-size: 0.85rem;">{{ $emp->full_name
                                                                    }}</span>
                                                                <small class="text-muted">{{ $emp->staff_number
                                                                    }}</small>
                                                            </div>
                                                        </label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @empty
                                        <div class="col-12 text-center py-5 text-muted">
                                            <p>No active employees found.</p>
                                        </div>
                                        @endforelse
                                    </div>
                                </div>
                                <small class="form-text text-muted mt-2">Check the boxes to select employees for
                                    increment.</small>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col">
                            <label for="">Please increment salaries of selected staff by</label>
                            @error('number_of_increment')
                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <input type="number"
                                class="form-control-sm @error('number_of_increment') is-invalid @enderror"
                                wire:model.blur="number_of_increment" name="increment">
                            <label for="">Step<sub>(s)</sub></label>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="overwrite"
                                    wire:model.blur="overwrite">
                                <label class="form-check-label text-danger font-weight-bold" for="overwrite">
                                    Force / Overwrite existing increments for this month?
                                </label>

                            </div>
                        </div>
                    </div>

                </fieldset>
                @can('can_save')
                <button class="btn sm btn-warning save_btn mt-3 float-right" wire:click.prevent="confirm()"
                    type="button">Apply</button>
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