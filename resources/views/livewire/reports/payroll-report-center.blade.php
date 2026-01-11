<div>
    {{-- A good traveler has no fixed plans and is not intent upon arriving. --}}
    <!-- Spinner (hidden by default) -->


        <!-- Spinner -->




        <!-- Spinner -->

        <div class="row">
        <div class="col-lg-12 p-3">
            <div wire:loading  style="position: absolute;z-index: 9999;text-align: center;width: 100%;height: 50vh;padding: 25vh">
                <div style="background: rgba(14,13,13,0.13);margin: auto;max-width:100px;">
                    <i class="fa fa-spin fa-spinner" style="font-size:100px"></i>
                </div>
            </div>
            <form action="{{route('payroll_report')}}" method="post" id="myForm" target="_blank">@csrf
                <div class="row">
                    <div class="col-12 col-md-6">
                        @error('date_from') <strong class="text-danger">{{$message}}</strong> @enderror
                        <div class="input-group  form-group">
                            <div class="input-group-prepend"><span class="input-group-text">Report Date From</span></div>
                            <input class="form-control" type="month" name="date_from" wire:model.live="date_from">
                            <div class="input-group-append"></div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        @error('date_to') <strong class="text-danger">{{$message}}</strong> @enderror
                        <div class="input-group form-group" >
                            <div class="input-group-prepend"><span class="input-group-text">Report Date To</span></div>
                            <input class="form-control" type="month" name="date_to" wire:model.live="date_to">

                            <div class="input-group-append"></div>
                        </div>
                    </div>
                </div>
                <fieldset>
                    <legend><h6>Employee Selection:</h6></legend>
                    <div class="row">
                        <div class="col-12 col-lg-6">
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
                        <div class="col-12 col-lg-6">
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
                        <div class="col-12 col-lg-6">
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
                        <div class="col-12 col-lg-6">
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
                <fieldset class="mt-3">
                    <legend><h6>Report Type:</h6></legend>
                    <div class="row">
                        <div class="col-12 col-lg-3">
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Report Type</span></div>
                                <select class="form-control"  wire:model.live="report_type" wire:change="change" name="report_type">
                                    <option value="1" selected>Payroll</option>
                                    <option value="2">Pay Slip</option>
                                    <option value="3">Bank Payment</option>
                                    <option value="4">Deduction Schedule</option>
                                    <option value="5">Salary Deduction Summary</option>
                                    <option value="6">Bank Summary</option>
                                    <option value="7">Salary Journal</option>
                                    <option value="8">PFA Payment Schedule</option>
                                    <option value="9">NHIS </option>
                                    <option value="10">Employer Pension</option>
                                </select>
                                <div class="input-group-append"></div>
                            </div>
                        </div>



                        @if($show_group_by == true)
                            <div class="col-12 col-lg-3">
                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text">Group By</span></div>
                                    <select class="form-control" wire:model.blur="group_by" type="text" name="group_by">
                                        {{--                            <option value="">Group By</option>--}}
                                        <option value="department">Department</option>
                                        <option value="unit">Unit</option>
                                        <option value="employment_type">Employment Type</option>
                                        <option value="staff_category">Staff Category</option>
                                        <option value="salary_structure">Salary Structure</option>
                                        {{--                                    <option value="grade_level">Grade Level</option>--}}
                                    </select>
                                    <div class="input-group-append"></div>
                                </div>
                            </div>
                        @endif

                        @if($show_group_by == false && $individ==true)

                            <div class="col-12 col-lg-3">
                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text">Select</span></div>
                                    <select class="form-control" wire:select.prevent="updatedGroupBy" wire:model.blur="group_by" type="text" name="group_by">
                                        {{--                            <option value="">Group By</option>--}}
                                        <option value="">All</option>
                                        @if($report_type==3 || $report_type==6)
                                            @foreach($banks as $bank)
                                                <option value="{{$bank->id}}">{{$bank->bank_name}}</option>
                                            @endforeach
                                        @endif
                                        @if($report_type==4)
                                            @foreach($deductions as $index=>$deduction)
                                                <option value="{{$deduction->id}}">{{$deduction->deduction_name}}</option>
                                            @endforeach
                                        @endif
                                        @if($report_type==8)
                                            @foreach(\App\Models\PFA::where('status',1)->get() as $pfa)
                                                <option value="{{$pfa->id}}">{{$pfa->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <div class="input-group-append"></div>
                                </div>
                            </div>


                        @endif

                        <div class="col-12 col-lg-2">
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">
                                            Order By
                                    </span>
                                    <select name="order_by" wire:model.blur="order_by" id="" class="form-control">
                                        {{--                                   <option value="">Order By</option>--}}
                                        <option value="id">Id</option>
                                        @if($this->report_type==1 || $this->report_type==2 ||$this->report_type==8)
                                            @if($this->report_type !=4)
                                            <option value="full_name" selected>Employee Name</option>
                                                <option value="pf_number">Staff Number</option>

                                            @endif
                                        @endif
                                        @if($this->report_type==3)
                                            <option value="bank" selected>Bank Name</option>
                                        @endif
                                        @if($this->report_type==4)
                                            <option value="staff_number" selected>Staff Number</option>
                                            <option value="staff_name" >Employee Name</option>
                                        @endif

                                    </select>
                                </div>
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-2">
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">
                                            Order
                                    </span>
                                    <select name="order" wire:model.live="orderAsc" id="" class="form-control">
                                        {{--                                   <option value=" ">Order</option>--}}
                                        <option value="asc">Asc</option>
                                        <option value="desc">Desc</option>
                                    </select>

                                </div>
                                <div class="input-group-append"></div>
                            </div>
                        </div>


                    </div>

                </fieldset>
                <div class="row table-bordered mt-3 ">
                    <div class="col text-center">
                        <button wire:loading.attr="disabled" class="btn generate" wire:click.prevent="generate({{$report_type}})" type="button">Generate</button>
                        <button class="btn view"  type="submit">View Pdf</button>
                    </div>
                </div>
            </form>
            <div class="row">
                <div class="col" wire:transition>
                    @if($this->report_type==1)
                        @include('livewire.reports.includes.payroll')
                    @endif
                    @if($this->report_type==2)
                        @include('livewire.reports.includes.pay_slip')
                    @endif
                    @if($this->report_type==3)
                        @include('livewire.reports.includes.bank_payment')
                    @endif
                    @if($this->report_type==4)
                        @include('livewire.reports.includes.deduction_schedule')
                    @endif
                    @if($this->report_type==5)
                        @include('livewire.reports.includes.deduction_summary')
                    @endif
                    @if($this->report_type==6)
                        @include('livewire.reports.includes.bank_summary')

                    @endif
                    @if($this->report_type==7)
                        @include('livewire.reports.includes.journal')
                    @endif
                    @if($this->report_type==8)
                        @include('livewire.reports.includes.pfa')
                    @endif
                        @if($this->report_type==9)
                            @include('livewire.reports.includes.nhis')
                        @endif
                        @if($this->report_type==10)
                            @include('livewire.reports.includes.employer_pension')
                        @endif
                </div>
            </div>

        </div>
    </div>
{{--        <script>--}}
{{--            document.getElementById('myForm').addEventListener('submit', function(event) {--}}
{{--                event.preventDefault(); // prevent actual submission--}}

{{--                // Do something with form data if needed--}}
{{--                const name = event.target.name.value;--}}

{{--                // Open next page in a new tab--}}
{{--                // window.open('https://example.com/next-page', '_blank');--}}
{{--            });--}}
{{--        </script>--}}
{{--       @include('form_spin')--}}
    @section('title')
        Report Center
    @endsection
    @section('page_title')
        Payroll Report / For Group
    @endsection
</div>
