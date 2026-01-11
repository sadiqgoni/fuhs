<div>
    {{-- A good traveler has no fixed plans and is not intent upon arriving. --}}

    <div class="row">
        <div class="col-lg-12 p-3">
            <form action="{{route('employee.report')}}" method="post" id="myForm" target="_blank">
                @csrf
                <div wire:loading wire:target="generate, export" style="position: absolute;z-index: 9999;text-align: center;width: 100%;height: 50vh;padding: 25vh">
                    <div style="background: rgba(14,13,13,0.13);margin: auto;max-width:100px;">
                        <i class="fa fa-spin fa-spinner" style="font-size:100px"></i>
                    </div>
                </div>
                <div wire:loading  style="position: absolute;z-index: 9999;text-align: center;width: 100%;height: 50vh;padding: 25vh">
                    <div style="background: rgba(14,13,13,0.13);margin: auto;max-width:100px;">
                        <i class="fa fa-spin fa-spinner" style="font-size:100px"></i>
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
                    <div class="row">
                        <div class="col-12 col-lg-4">
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">
                                    Order By
                            </span>
                                    <select name="order_by" wire:model.blur="order_by" id="" class="form-control">
                                        {{--                                   <option value="">Order By</option>--}}
                                        <option value="id" selected>Id</option>
                                        <option value="staff_number">Staff Number</option>
                                        <option value="full_name">Staff Name</option>
                                    </select>
                                </div>
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4">
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">
                                    Order
                            </span>
                                    <select name="orderAsc" wire:model.blur="orderAsc" id="" class="form-control">
                                        {{--                                   <option value="">Order By</option>--}}
                                        <option value="asc" selected>Asc</option>
                                        <option value="desc">Desc</option>
                                    </select>
                                </div>
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

                </fieldset>

                <fieldset class="mt-3">
                    <legend><h6>Report Layout</h6></legend>
                    <div class="row">
                        <div class="col-12 col-lg-8">
                            <h5>Choose Report Columns</h5>
                            <label for="">Staff No.</label><input type="checkbox" wire:model.live="report_column" value="1" name="report_column[]">
                            <label for="">IPP No.</label><input type="checkbox" wire:model.live="report_column" value="2" name="report_column[]">
                            <label for="">Fullname.</label><input type="checkbox" wire:model.live="report_column" value="3" name="report_column[]">
                            <label for="">Unit.</label><input type="checkbox" wire:model.live="report_column" value="4" name="report_column[]">
                            <label for="">Department.</label><input type="checkbox" wire:model.live="report_column" value="5" name="report_column[]">
                            <label for="">Phone number.</label><input type="checkbox" wire:model.live="report_column" value="6" name="report_column[]">
                            <label for="">WhatsApp No.</label><input type="checkbox" wire:model.live="report_column" value="7" name="report_column[]">
                            <label for="">Email.</label><input type="checkbox" wire:model.live="report_column" value="8" name="report_column[]">
                            <label for="">DOB.</label><input type="checkbox" wire:model.live="report_column" value="9" name="report_column[]">
                            <label for="">Salary Structure.</label><input type="checkbox" wire:model.live="report_column" value="10" name="report_column[]">
                            <label for="">Grade Level.</label><input type="checkbox" wire:model.live="report_column" value="11" name="report_column[]">
                            <label for="">Grade Step.</label><input type="checkbox" wire:model.live="report_column" value="12" name="report_column[]">
                            <label for="">DFA.</label><input type="checkbox" wire:model.live="report_column" value="13" name="report_column[]">
                            <label for="">DLA.</label><input type="checkbox" wire:model.live="report_column" value="14" name="report_column[]">
                            <label for="">Gender.</label><input type="checkbox" wire:model.live="report_column" value="15" name="report_column[]">
                            <label for="">Religion.</label><input type="checkbox" wire:model.live="report_column" value="16" name="report_column[]">
                            <label for="">Tribe.</label><input type="checkbox" wire:model.live="report_column" value="17" name="report_column[]">
                            <label for="">Marital Status.</label><input type="checkbox" wire:model.live="report_column" value="18" name="report_column[]">
                            <label for="">Nationality.</label><input type="checkbox" wire:model.live="report_column" value="19" name="report_column[]">
                            <label for="">State.</label><input type="checkbox" wire:model.live="report_column" value="20" name="report_column[]">
                            <label for="">LGA.</label><input type="checkbox" wire:model.live="report_column" value="21" name="report_column[]">
                            <label for="">Staff Cat.</label><input type="checkbox" wire:model.live="report_column" value="22" name="report_column[]">
                            <label for="">Bank.</label><input type="checkbox" wire:model.live="report_column" value="23" name="report_column[]">
                            <label for="">Account No.</label><input type="checkbox" wire:model.live="report_column" value="24" name="report_column[]">
                            <label for="">PF Name.</label><input type="checkbox" wire:model.live="report_column" value="25" name="report_column[]">
                            <label for="">Pension Pin.</label><input type="checkbox" wire:model.live="report_column" value="26" name="report_column[]">
                            <label for="">Date of Retirement.</label><input type="checkbox" wire:model.live="report_column" value="27" name="report_column[]">
                            <label for="">Contract Termination Date.</label><input type="checkbox" wire:model.live="report_column" value="28" name="report_column[]">
                            <label for="">Staff Union.</label><input type="checkbox" wire:model.live="report_column" value="29" name="report_column[]">
                            <label for="">BVN.</label><input type="checkbox" wire:model.live="report_column" value="30" name="report_column[]">
                            <label for="">Tax Id.</label><input type="checkbox" wire:model.live="report_column" value="31" name="report_column[]">
                        </div>
                        <div class="col-12 col-lg-4">
                            <div class="row">
                                <div class="col-12">
                                    <label for="">Report Title  @error('report_title') <smal class="text-danger">{{$message}}</smal>@enderror</label>
                                    <input name="report_title" type="text" class="form-control @error('report_title') is-invalid @enderror" wire:model.blur="report_title" placeholder="@error('report_title') {{$message}} @enderror">
                                </div>
                                <div class="col-12">
                                    <label for="">Subtitle</label>
                                    <input name="sub_title" type="text" class="form-control @error('sub_title') is-invalid @enderror" wire:model.blur="sub_title" placeholder="@error('sub_title') {{$message}} @enderror">
                                </div>
                            </div>
                        </div>
                    </div>

                </fieldset>

                <div class="row mt-3">
                    <div class="col text-center">

                        <button class="btn generate my-1 my-md-0" wire:click.prevent="generate()">Generate</button>
                        <button class="btn view my-1 my-md-0"  type="submit">View</button>
                        <button class="btn export my-1 my-md-0" wire:click.prevent="export()">Export</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @if($record=true && $report_col !=[])

        <p>Showing total number of <span class="text-primary border">{{$reports->count()}}</span> Staffs</p>
       <div class="table-responsive">
           <table class="table table-bordered table-sm" style="font-size: 12px">

               <thead>
               <tr>
                   <th>S/N</th>
                   @if(in_array(1,$report_col))
                       <th>Staff No</th>
                   @endif
                   @if(in_array(2,$report_col))
                       <th>IPP No</th>
                   @endif
                   @if(in_array(3,$report_col))
                       <th>Fullname</th>
                   @endif
                   @if(in_array(4,$report_col))
                       <th>Unit</th>
                   @endif
                   @if(in_array(5,$report_col))
                       <th>Department</th>
                   @endif
                   @if(in_array(6,$report_col))
                       <th>Phone number</th>
                   @endif
                   @if(in_array(7,$report_col))
                       <th>WhatsApp No</th>
                   @endif
                   @if(in_array(8,$report_col))
                       <th>Email</th>
                   @endif
                   @if(in_array(9,$report_col))
                       <th>DOB</th>
                   @endif
                   @if(in_array(10,$report_col))
                       <th>Salary Structure</th>
                   @endif
                   @if(in_array(11,$report_col))
                       <th>Grade Level</th>
                   @endif
                   @if(in_array(12,$report_col))
                       <th>Grade Step</th>
                   @endif
                   @if(in_array(13,$report_col))
                       <th>DFA</th>
                   @endif
                   @if(in_array(14,$report_col))
                       <th>DLA</th>
                   @endif
                   @if(in_array(15,$report_col))
                       <th>Gender</th>
                   @endif
                   @if(in_array(16,$report_col))
                       <th>Religion</th>
                   @endif
                   @if(in_array(17,$report_col))
                       <th>Tribe</th>
                   @endif
                   @if(in_array(18,$report_col))
                       <th>Marital Status</th>
                   @endif
                   @if(in_array(19,$report_col))
                       <th>Nationality</th>
                   @endif
                   @if(in_array(20,$report_col))
                       <th>State</th>
                   @endif
                   @if(in_array(21,$report_col))
                       <th>LGA</th>
                   @endif
                   @if(in_array(22,$report_col))
                       <th>Staff Cat</th>
                   @endif
                   @if(in_array(23,$report_col))
                       <th>Bank</th>
                   @endif
                   @if(in_array(24,$report_col))
                       <th>Account No</th>
                   @endif
                   @if(in_array(25,$report_col))
                       <th>PFA</th>
                   @endif
                   @if(in_array(26,$report_col))
                       <th>Pension Pin</th>
                   @endif
                   @if(in_array(27,$report_col))
                       <th>Date of Retirement</th>
                   @endif
                   @if(in_array(28,$report_col))
                       <th>Contract Termination Date</th>
                   @endif
                   @if(in_array(29,$report_col))
                           <th>Staff Union</th>
                   @endif
                   @if(in_array(30,$report_col))
                       <th>BVN</th>
                   @endif
                   @if(in_array(31,$report_col))
                       <th>Tax Id</th>
                   @endif

               </tr>
               </thead>
               <tbody>
               @foreach($reports as $index=>$report)
                   <tr>
                       <th>{{$index+1}}</th>
                       @if(in_array(1,$report_col))
                           <td>{{$report->staff_number}}</td>
                       @endif
                       @if(in_array(2,$report_col))
                           <td>{{$report->payroll_number}}</td>
                       @endif
                       @if(in_array(3,$report_col))
                           <td>{{$report->full_name}}</td>
                       @endif
                       @if(in_array(4,$report_col))
                           <td>{{unit($report->unit)}}</td>
                       @endif
                       @if(in_array(5,$report_col))
                           <td>{{dept($report->id)}}</td>
                       @endif
                       @if(in_array(6,$report_col))
                           <td>{{$report->phone_number}}</td>
                       @endif
                       @if(in_array(7,$report_col))
                           <td>{{$report->whatsapp_number}}</td>
                       @endif
                       @if(in_array(8,$report_col))
                           <td>{{$report->email}}</td>
                       @endif
                       @if(in_array(9,$report_col))
                           <td>{{$report->date_of_birth}}</td>
                       @endif
                       @if(in_array(10,$report_col))
                           <td>{{ss($report->salary_structure)}}</td>
                       @endif
                       @if(in_array(11,$report_col))
                           <td>{{$report->grade_level}}</td>
                       @endif
                       @if(in_array(12,$report_col))
                           <td>{{$report->step}}</td>
                       @endif
                       @if(in_array(13,$report_col))
                           <td>{{$report->date_of_first_appointment}}</td>
                       @endif
                       @if(in_array(14,$report_col))
                           <td>{{$report->date_of_last_appointment}}</td>
                       @endif
                       @if(in_array(15,$report_col))
                           <td>{{gender($report->gender)}}</td>
                       @endif
                       @if(in_array(16,$report_col))
                           <td>{{religion($report->religion)}}</td>
                       @endif
                       @if(in_array(17,$report_col))
                           <td>{{$report->tribe}}</td>
                       @endif
                       @if(in_array(18,$report_col))
                           <td>{{marital_status($report->marital_status)}}</td>
                       @endif
                       @if(in_array(19,$report_col))
                           <td>{{nationality($report->nationality)}}</td>
                       @endif
                       @if(in_array(20,$report_col))
                           <td>{{state($report->state_of_origin)}}</td>
                       @endif
                       @if(in_array(21,$report_col))
                           <td>{{lga($report->local_government)}}</td>
                       @endif
                       @if(in_array(22,$report_col))
                           <td>{{$report->staff_category}}</td>
                       @endif
                       @if(in_array(23,$report_col))
                           <td>{{$report->bank_name}}</td>
                       @endif
                       @if(in_array(24,$report_col))
                           <td>{{$report->account_number}}</td>
                       @endif
                       @if(in_array(25,$report_col))
                           <td>{{pfa_name($report->pfa_name)}}</td>
                       @endif
                       @if(in_array(26,$report_col))
                           <td>{{$report->pension_pin}}</td>
                       @endif
                       @if(in_array(27,$report_col))
                           <td>{{$report->date_of_retirement}}</td>
                       @endif
                       @if(in_array(28,$report_col))
                           <td>{{$report->contract_termination_date}}</td>
                       @endif
                       @if(in_array(29,$report_col))
                           <td>{{$report->staff_union}}</td>
                       @endif
                       @if(in_array(30,$report_col))
                           <td>{{$report->bvn}}</td>
                       @endif
                       @if(in_array(31,$report_col))
                           <td>{{$report->tax_id}}</td>
                       @endif
                   </tr>
               @endforeach
               </tbody>
           </table>
       </div>

    @endif


{{--@include('form_spin')--}}


    @section('title')
        Employee Report Center
    @endsection
    @section('page_title')
        Payroll Report / Nominal Roll
    @endsection
</div>
