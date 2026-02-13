@if($edit == true)

    <div>

        {{-- <form wire:submit="store()">--}}
            <fieldset>

                <legend> <a href="{{route('employee.profile')}}" class="btn close_btn float-right" type="button"
                        wire:click="close()">Close</a>
                    <p class="text-center text-danger" style="font-size: 13px"><b>Note:</b>All fields with <span
                            class="text-white" style="font-size: 18px">(*)</span> are required</p>
                </legend>
                <div class="row">
                    <div class="col-12 col-lg-2">
                        @if ($profile_picture)
                            <img src="{{ $profile_picture->temporaryUrl() }}" class="d-block"
                                style="margin:auto !important; width: 78px;">
                        @endif
                        <label for="">upload staff photo</label>
                        <input type="file" class="form-control-sm @error('profile_picture') is-invalid @enderror"
                            wire:model.live="profile_picture" style="max-width: 150px">
                        @error('profile_picture') <strong class="text-danger">{{$message}}</strong> @enderror
                    </div>
                    <div class="col-12 col-lg-10">
                        <div class="row">
                            <div class="col-12 col-lg-6">
                                @error('staff_number')
                                    <strong class="text-danger d-block form-text">{{$message}}</strong>
                                @enderror
                                <div class="input-group form-group">

                                    <div class="input-group-prepend"><span class="input-group-text">Staff Number
                                            <sup>*</sup></span></div>
                                    <input class="form-control @error('staff_number') is-invalid @enderror"
                                        wire:model.blur="staff_number" type="text">
                                    <div class="input-group-append"></div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-6">
                                @error('payroll_number')
                                    <strong class="text-danger d-block form-text">{{$message}}</strong>
                                @enderror
                                <div class="input-group form-group">

                                    <div class="input-group-prepend"><span class="input-group-text">Payroll Number
                                            <sup>*</sup></span></div>
                                    <input class="form-control  @error('payroll_number') is-invalid @enderror"
                                        wire:model.blur="payroll_number" type="text">
                                    <div class="input-group-append"></div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-6">
                                @error('full_name')
                                    <strong class="text-danger d-block form-text">{{$message}}</strong>
                                @enderror
                                <div class="input-group form-group">

                                    <div class="input-group-prepend"><span class="input-group-text">Full Name
                                            <sup>*</sup></span></div>
                                    <input class="form-control @error('full_name') is-invalid @enderror"
                                        wire:model.blur="full_name" type="text">
                                    <div class="input-group-append"></div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-6">
                                @error('status')
                                    <strong class="text-danger d-block form-text">{{$message}}</strong>
                                @enderror
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">Status
                                            <sup>*</sup></span></div>
                                    <select class="form-control  @error('status') is-invalid @enderror"
                                        wire:model.blur="status">
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
                    </div>
                </div>
            </fieldset>
            {{-- Step Indicators --}}
            <div class="my-2 flex space-x-2">
                <button style="padding: 3px 5px" wire:click="$set('steps', 1)"
                    class="{{ $steps == 1 ? 'font-bold' : '' }} {{ $steps == 1 ? 'btn-primary text-white' : 'bg-gray-200 text-gray-800' }}">Personal
                    Data</button>
                <button style="padding: 3px 5px" wire:click="$set('steps', 2)"
                    class="{{ $steps == 2 ? 'font-bold' : '' }} {{ $steps == 2 ? 'btn-primary text-white' : 'bg-gray-200 text-gray-800' }}">Employment
                    Data</button>
                <button style="padding: 3px 5px" wire:click="$set('steps', 3)"
                    class="{{ $steps == 3 ? 'font-bold' : '' }} {{ $steps == 3 ? 'btn-primary text-white' : 'bg-gray-200 text-gray-800' }}">Salary
                    & Pension Data</button>
                <button style="padding: 3px 5px" wire:click="$set('steps', 4)"
                    class="{{ $steps == 4 ? 'font-bold' : '' }} {{ $steps == 4 ? 'btn-primary text-white' : 'bg-gray-200 text-gray-800' }}">Next
                    of Kin</button>
            </div>

            {{-- Step 1 --}}
            @if ($steps == 1)
                <div class="p-4 border rounded">
                    <h5>Personal Data</h5>

                    <div class="row">
                        <div class="col-12 col-lg-6">
                            @error('date_of_birth')
                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Date of Birth
                                        <sup>*</sup></span></div>
                                <input class="form-control @error('date_of_birth') is-invalid @enderror"
                                    wire:model.live="date_of_birth" type="date">
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            @error('gender')
                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Gender</span></div>
                                <select class="form-control @error('gender') is-invalid @enderror" wire:model.blur="gender">
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
                                <select class="form-control @error('tribe') is-invalid @enderror" wire:model.blur="tribe">
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
                            @error('nationality')
                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Nationality</span></div>
                                <select class="form-control @error('nationality') is-invalid @enderror"
                                    wire:model.blur="nationality">
                                    <option value="">Nationality</option>
                                    <option value="1">Nigeria</option>
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
                                <select class="form-control @error('marital_status') is-invalid @enderror"
                                    wire:model.blur="marital_status">
                                    <option value="">Marital Status</option>
                                    @foreach(\App\Models\MaritalStatus::all() as $marital_status)
                                        <option value="{{$marital_status->id}}">{{$marital_status->name}}</option>
                                    @endforeach
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
                                <select class="form-control @error('state_of_origin') is-invalid @enderror"
                                    wire:model.blur="state_of_origin">
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
                                <select class="form-control @error('local_government') is-invalid @enderror"
                                    wire:model.blur="local_government">
                                    <option value="">Select LGA</option>
                                    @forelse($lgas as $lga)
                                        <option value="{{$lga->id}}">{{$lga->name}}</option>
                                    @empty
                                    @endforelse
                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4">
                            @error('phone_number')
                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Phone Number</span></div>
                                <input class="form-control @error('phone_number') is-invalid @enderror"
                                    wire:model.blur="phone_number" type="text">
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4">
                            @error('email')
                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Email</span></div>
                                <input class="form-control @error('email') is-invalid @enderror" wire:model.blur="email"
                                    type="text">
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4">
                            @error('whatsapp_number')
                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">WhatsApp Number</span></div>
                                <input class="form-control @error('whatsapp_number') is-invalid @enderror"
                                    wire:model.blur="whatsapp_number" type="text">
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 flex justify-between">
                        <button wire:click="prevStep" class="bg-gray-500 text-white px-4 py-2">Back</button>
                        <button wire:click="nextStep" class="bg-blue-500 text-white px-4 py-2">Next</button>
                        <button wire:click="update({{$ids}})" class="btn-success text-white px-4 py-2 float-right">Save
                            Changes</button>

                    </div>
                </div>
            @endif

            {{-- Step 2 --}}
            @if ($steps == 2)
                <div class="p-4 border rounded">
                    <h5>Employment Data</h5>
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            @error('employment_type')
                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Employment Type
                                        <sup>*</sup></span></div>
                                <select class="form-control  @error('employment_type') is-invalid @enderror"
                                    wire:model.live="employment_type">
                                    <option value="">Employment Type</option>
                                    @foreach($employments as $emp)
                                        <option value="{{$emp->id}}">{{$emp->name}}</option>

                                    @endforeach
                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            @error('staff_category')
                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">Staff Category</span></div>
                                <select class="form-control  @error('staff_category') is-invalid @enderror"
                                    wire:model.blur="staff_category">
                                    <option value="">Staff Category</option>
                                    @foreach($categories as $cat)
                                        <option value="{{$cat->id}}">{{$cat->name}}</option>

                                    @endforeach
                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            @error('unit')
                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Unit <sup>*</sup></span></div>
                                <select class="form-control @error('unit') is-invalid @enderror" wire:model.live="unit">
                                    <option value="">Select Unit</option>
                                    @foreach($units as $unit)
                                        <option value="{{$unit->id}}">{{$unit->name}}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            @error('department')
                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">Department <sup>*</sup></span>
                                </div>
                                <select class="form-control @error('department') is-invalid @enderror"
                                    wire:model.blur="department">
                                    <option value="">Department</option>
                                    @foreach($departments as $dept)
                                        <option value="{{$dept->id}}">{{$dept->name}}</option>
                                    @endforeach

                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            @error('rank')
                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Rank</span></div>
                                <select class="form-control @error('rank') is-invalid @enderror" wire:model.blur="rank">
                                    <option value="">Select Rank</option>
                                    @foreach($ranks as $rank)
                                        <option value="{{$rank->id}}">{{$rank->name}}</option>
                                    @endforeach

                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            @error('post_held')
                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Post Held</span></div>
                                <input class="form-control  @error('post_held') is-invalid @enderror"
                                    wire:model.blur="post_held" type="text">
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            @error('date_of_first_appointment')
                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Date of FA</span></div>
                                <input class="form-control  @error('date_of_first_appointment') is-invalid @enderror"
                                    wire:model.live="date_of_first_appointment" type="date">
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            @error('date_of_last_promotion')
                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">Date of LP</span></div>
                                <input class="form-control  @error('date_of_last_promotion') is-invalid @enderror"
                                    wire:model.blur="date_of_last_promotion" type="date">
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="col-12 col-lg-auto">
                                    @error('date_of_retirement')
                                        <strong class="text-danger d-block form-text">{{$message}}</strong>
                                    @enderror
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text">Date of Retirement <sup
                                                    class="text-dark"></sup></span></div>
                                        <input class="form-control  @error('date_of_retirement') is-invalid @enderror"
                                            wire:model="date_of_retirement" type="date" disabled>
                                        <div class="input-group-append"></div>
                                    </div>
                                </div>
                                @if($employment_type == 1 || $employment_type == 3)
                                    <div class="col-12 col-lg-auto">
                                        @error('contract_termination_date')
                                            <strong class="text-danger d-block form-text">{{$message}}</strong>
                                        @enderror
                                        <div class="input-group form-group">
                                            <div class="input-group-prepend"><span class="input-group-text">Contract Termination
                                                    Date</span></div>
                                            <input class="form-control  @error('contract_termination_date') is-invalid @enderror"
                                                wire:model.live="contract_termination_date" type="date">
                                            <div class="input-group-append"></div>
                                        </div>
                                    </div>

                                @endif

                                <div class="col-12 col-lg-auto">
                                    @error('staff_union')
                                        <strong class="text-danger d-block form-text">{{$message}}</strong>
                                    @enderror
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text">Staff Union<sup
                                                    class="text-danger">*</sup></span></div>
                                        <select class="form-control  @error('staff_union') is-invalid @enderror"
                                            wire:model="staff_union">
                                            <option value="">Select Staff Union</option>
                                            @foreach(\App\Models\Union::where('status', 1)->get() as $union)
                                                <option value="{{$union->id}}">{{$union->name}}</option>
                                            @endforeach
                                        </select>
                                        {{-- <div class="input-group-append"></div>--}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 flex justify-between">
                        <button wire:click="prevStep" class="bg-gray-500 text-white px-4 py-2">Back</button>
                        <button wire:click="nextStep" class="bg-blue-500 text-white px-4 py-2">Next</button>
                        <button wire:click="update({{$ids}})" class="btn-success text-white px-4 py-2 float-right">Save
                            Changes</button>

                    </div>
                </div>
            @endif

            {{-- Step 3 --}}
            @if ($steps == 3)
                <div class="p-4 border rounded">
                    <h5>Salary & Pension Data</h5>
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            @error('pension_pin')
                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Pension Pin <sup>*</sup></span>
                                </div>
                                <input class="form-control  @error('pension_pin') is-invalid @enderror"
                                    wire:model.blur="pension_pin" type="text">
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            @error('pfa_name')
                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">PFA Name <sup>*</sup></span>
                                </div>
                                <select class="form-control  @error('pfa_name') is-invalid @enderror"
                                    wire:model.blur="pfa_name">
                                    <option value="">PF Name</option>
                                    @foreach($pfas as $pfa)
                                        <option value="{{$pfa->id}}">{{$pfa->name}}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            @error('salary_structure')
                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Salary Structure
                                        <sup>*</sup></span></div>
                                <select class="form-control @error('salary_structure') is-invalid @enderror"
                                    wire:model.live="salary_structure">
                                    <option value="">Salary Structure</option>
                                    @foreach($salary_structures as $salary)
                                        <option value="{{$salary->id}}">{{$salary->name}}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            @error('grade_level')
                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            @php
                                $salObj = \App\Models\SalaryStructure::find($this->salary_structure);
                                if (!is_null($salary_structure)) {
                                    $salObjs = \App\Models\SalaryStructureTemplate::where('salary_structure_id', $this->salary_structure)->select('grade_level')->get();

                                }
                            @endphp
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Grade Level <sup>*</sup></span>
                                </div>
                                <select class="form-control @error('grade_level') is-invalid @enderror"
                                    wire:model.live="grade_level" type="number">
                                    <option value="">Select Grade Level</option>
                                    @if($this->salary_structure != '')
                                        @foreach($salObjs as $obj)
                                            <option value="{{$obj->grade_level}}">Grade {{$obj->grade_level}}</option>

                                        @endforeach
                                    @endif
                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-6">
                            @error('step')
                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror

                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Step <sup>*</sup></span></div>
                                <select class="form-control @error('step') is-invalid @enderror" wire:model.blur="step">
                                    @if(!is_null($salObj))
                                        @php
                                            $step_no = \App\Models\SalaryStructureTemplate::where('salary_structure_id', $salObj->id)
                                                ->where('grade_level', $grade_level)
                                                ->first()
                                        @endphp
                                        <option value="">Select Step</option>
                                        @if(!is_null($step_no))
                                            @for($i = 1; $i <= $step_no->no_of_grade_steps; $i++)
                                                <option value="{{$i}}">Step {{$i}}</option>
                                            @endfor
                                        @endif
                                    @endif



                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            @error('account_number')
                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Account Number
                                        <sup>*</sup></span></div>
                                <input class="form-control @error('account_number') is-invalid @enderror"
                                    wire:model.blur="account_number" type="text">
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            @error('bank_code')
                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Bank Code <sup>*</sup></span>
                                </div>
                                <select class="form-control @error('bank_code') is-invalid @enderror"
                                    wire:model.live="bank_code">
                                    <option value="">Bank Code</option>
                                    @foreach($banks as $bank)
                                        <option value="{{$bank->id}}">{{$bank->bank_code}}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            @error('bank_name')
                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Bank Name <sup>*</sup></span>
                                </div>
                                <select class="form-control @error('bank_name') is-invalid @enderror"
                                    wire:model.live="bank_name">
                                    <option value="">Bank Name</option>
                                    @foreach($banks ? $banks : \App\Models\Bank::where('status', 1)->get() as $bank)
                                        <option value="{{$bank->bank_name}}">{{$bank->bank_name}}</option>
                                    @endforeach
                                </select>


                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            @error('bvn')
                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Bvn <sup></sup></span></div>
                                <input class="form-control @error('bvn') is-invalid @enderror" wire:model.blur="bvn"
                                    type="text">
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            @error('tax_id')
                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Tax Id <sup></sup></span></div>
                                <input class="form-control @error('tax_id') is-invalid @enderror" wire:model.blur="tax_id"
                                    type="text">
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            @error('arrears_months')
                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Arrears Months
                                        <sup></sup></span></div>
                                <input class="form-control @error('arrears_months') is-invalid @enderror"
                                    wire:model.blur="arrears_months" type="number" min="0">
                                <div class="input-group-append"></div>
                            </div>
                        </div>

                    </div>
                    <div class="mt-2 flex justify-between">
                        <button wire:click="prevStep" class="bg-gray-500 text-white px-4 py-2">Back</button>
                        <button wire:click="nextStep" class="bg-blue-500 text-white px-4 py-2">Next</button>
                        <button wire:click="update({{$ids}})" class="btn-success text-white px-4 py-2 float-right">Save
                            Changes</button>

                    </div>
                </div>
            @endif
            @if ($steps == 4)
                <div class="p-4 border rounded">
                    <h5>Next of Kin</h5>
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            @error('name_of_next_of_kin')
                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Name</span></div>
                                <input class="form-control @error('name_of_next_of_kin') is-invalid @enderror"
                                    wire:model.blur="name_of_next_of_kin" type="text">
                                <div class="input-group-append"></div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-6">
                            @error('next_of_kin_phone_number')
                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Phone Number</span></div>
                                <input class="form-control @error('next_of_kin_phone_number') is-invalid @enderror"
                                    wire:model.blur="next_of_kin_phone_number" type="text">
                                <div class="input-group-append"></div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-6">
                            @error('relationship')
                                <strong class="text-danger d-block form-text">{{$message}}</strong>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Relationship</span></div>
                                <select class="form-control @error('relationship') is-invalid @enderror"
                                    wire:model.blur="relationship">
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
                                <input class="form-control @error('address') is-invalid @enderror" wire:model.blur="address"
                                    type="text">
                                <div class="input-group-append"></div>
                            </div>
                        </div>

                    </div>
                    <div class="mt-2 flex justify-between">
                        <button wire:click="prevStep" class="bg-gray-500 text-white px-4 py-2">Back</button>
                        <button wire:click="update({{$ids}})" class="btn-success text-white px-4 py-2 float-right">Save
                            Changes</button>
                    </div>
                </div>
            @endif
            {{--
        </form>--}}
    </div>
@endif