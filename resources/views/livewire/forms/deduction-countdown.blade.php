<div>
    {{-- In work, do what you enjoy. --}}
    <style>
        svg{
            display: none;
        }
        .inport_x label {
            border: 2px solid gray;
            color: gray;
            background-color: white;
            padding: 6px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
        }
        .upload-btn-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
        }

        .upload-btn-wrapper  .btn {
            border: 2px solid gray;
            color: gray;
            background-color: white;
            padding: 6px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
        }

        .upload-btn-wrapper input[type=file] {
            font-size: 100px;
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
        }
    </style>
@if($record==true)
        <div class="row">
            <div class="col">
                <form action="#" >
                    <fieldset>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <label for="">Deduction Name <strong class="text-danger">@error('deduction_name') {{$message}} @enderror</strong></label>
                                <div class="form-group">
                                    <select class="form-control" wire:model.live="deduction_name">
                                        <option value="" >Select Deduction</option>
                                        @foreach(\App\Models\Deduction::where('deduction_type',2)->get() as $item)
                                            <option value="{{$item->id}}">{{$item->deduction_name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-12 col-lg-6">
                                        <label for="">Total Deduction Amount <strong class="text-danger">@error('total_deduction_amount') {{$message}} @enderror</strong></label>
                                        <div class="form-group">
                                            <input type="text" class="form-control" value="@if($deduction_name !='') {{\App\Models\LoanDeductionCountdown::where('deduction_id',$deduction_name)->sum('installment_amount')}} @endif" disabled readonly placeholder="Total Deduction Amount">
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-6">
                                        <label for="">Total Staff Count <strong class="text-danger">@error('total_staff_count') {{$message}} @enderror</strong></label>
                                        <div class="form-group">
                                            <input type="text" class="form-control" value="@if($deduction_name !=''){{\App\Models\LoanDeductionCountdown::where('deduction_id',$deduction_name)->get()->count()}} @endif" readonly placeholder="Total Staff Count">
                                        </div>
                                    </div>
                                </div>


                            </div>
                            <div class="col-12 col-md-6">
                                <label for=""><a href="{{url('assets/excel_sample/loandeduction.xlsx')}}" style="color: #721c24">Download sample file</a></label>
                                <div  style="height: 50px;border: 1px solid black;border-radius: 7px;padding: 5px">
                                   @if($deduction_name !='') {{\App\Models\Deduction::find($deduction_name)->description}} @endif
                                </div>
                                <label for="">&nbsp;</label>

                                <div class="form-group">
                                    @if($deduction_name != '')
                                        @if($deduction_status->count() > 0)
                                            <button class="btn reset_btn my-1 my-md-0" wire:click.prevent="clear_all_staff()">Suspend All Staff</button>
                                        @else
                                            @if(\App\Models\LoanDeductionCountdown::where('deduction_id',$deduction_name)->exists())
                                                <button class="btn view my-1 my-md-0" wire:click.prevent="resume_all_staff()">Resume All Staff</button>

                                            @endif
                                        @endif
                                        @can('can_save')
                                                <button class="btn create" wire:click.prevent="create">Add Staff</button>
{{--                                               <form wire:submit.prevent="uploadile()" class="inport_x">--}}
                                                <input type="file" wire:model="importFile"  id="actual-btn" hidden/>
                                                <label for="actual-btn"  style="border: 2px solid red;padding: 5px 10px;font-size: 13px">@if($importFile) {{$importFile->getClientOriginalName()}} @else Click to Choose File @endif</label>
                                                   <button wire:click.prevent="uploadFile()" class="btn btn-sm btn-light" style=" border: 2px solid gray;color: gray;padding: 6px 20px;border-radius: 8px;font-size: 14px;font-weight: bold;margin-left:-5px; margin-top: -2px" type="submit">upload</button>

{{--                                               </form>--}}
                                            @endcan

                                    @endif

                                </div>

                            </div>
                        </div>
                    </fieldset>
                </form>
                @if(!empty($upload_errors))
{{--                    <div class="alert alert-warning alert-dismissible fade show" role="alert">--}}
{{--                    <strong>error!</strong> {{ session('message') }}--}}
{{--                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">X</button>--}}
                    <table style="max-width: 650px !important;" class="table-sm table table-bordered table-striped table-warning alert alert-warning alert-dismissible fade show">
                        <tr>
                            <td>Payroll Number</td>
                            <td>Error Occurred</td>
                        </tr>
                        @foreach($upload_errors as $err)
                            <tr>
                                <td>{{$err[0]}}</td>
                                <td>{{$err[1]}}</td>
                            </tr>
                        @endforeach
                    </table>
{{--                    </div>--}}
                @endif

            </div>
        </div>
        <div class="mt-3">
            <div>
                <label for="">Salary Month</label>
                <input type="month" wire:model="salary_month" class="form-control-sm">
                <small class="text-danger">@error('salary_month'){{$message}}@enderror</small>

            @if($deduction_status->count()>0)

                    <button class="btn my-1 my-md-0 save_btn float-right" wire:click.prevent="post_to_ledger">Post to Ledger</button>
                    @if($deduction_name)
                        <button class="btn my-1 my-md-0 save_btn float-right mr-md-3" wire:click.prevent="updatePayMonth">Update Pay Month</button>
                    @endif
                @endif

            </div>
            <div wire:loading  style="position: absolute;z-index: 9999;text-align: center;width: 100%;height: 50vh;padding: 25vh">
                <div style="background: rgba(14,13,13,0.13);margin: auto;max-width:100px;">
                    <i class="fa fa-spin fa-spinner" style="font-size:100px"></i>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-stripped table-bordered mt-2" style="font-size: 12px">
                    <thead>
                    <tr>
                        <th>SN</th>
                        <th>PF NO</th>
                        <th>IP NO</th>
                        <th>FULL NAME</th>
                        <th>INSTALLMENT <br> AMOUNT</th>
                        <th>START <br> MONTH/YEAR</th>
                        <th>LAST PAY <br> MONTH</th>
                        <th>DEDUCTION <br> COUNTDOWN</th>
                        <th>STATUS</th>
                        <th>ACTION</th>
                    </tr>
                    </thead>
                    <tbody style="font-size: 14px">
                    @forelse($deduction_records as $deduction)
                        <tr>
                            <th>{{$loop->iteration}}</th>
                            <td>{{$deduction->staff_number}}</td>
                            <td>{{$deduction->payroll_number}}</td>
                            <td>{{$deduction->full_name}}</td>
                            <td>{{number_format($deduction->installment_amount,2)}}</td>
                            <td>{{\Illuminate\Support\Carbon::parse($deduction->start_month)->format('F, Y')}}</td>
                            <td>{{\Illuminate\Support\Carbon::parse($deduction->last_pay_month_year)->format('F, Y')}}</td>
                            <td>{{$deduction->ded_countdown}}</td>
                            <td>{{loan_status($deduction->deduction_status)}}</td>
                            <td>
                                @can('can_edit')
                                    <button class="btn btn-sm btn-info" wire:click.prevent="edit_staff({{$deduction->id}})">&nbsp;&nbsp;Edit&nbsp;&nbsp;</button>

                                @endcan
                                @if($deduction->deduction_status==0)
                                    <button class="btn btn-sm btn-danger" wire:click.prevent="deleteId({{$deduction->id}})">Suspend</button>
                                @else
                                    <button class="btn btn-sm btn-primary" wire:click.prevent="resume_single_staff({{$deduction->id}})">Resume</button>

                                @endif
                            </td>
                        </tr>
                    @empty

                    @endforelse
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="9">
                            {{$deduction_records->links()}}
                        </td>
                    </tr>
                    </tfoot>
                </table>

            </div>
        </div>
@endif


   @if($crate==true)
        <form action="" wire:submit.prevent="store()" >
            <div class="row">
                <div class="col">
                    <div wire:loading  style="position: absolute;z-index: 9999;text-align: center;width: 100%;height: 50vh;padding: 25vh">
                        <div style="background: rgba(14,13,13,0.13);margin: auto;max-width:100px;">
                            <i class="fa fa-spin fa-spinner" style="font-size:100px"></i>
                        </div>
                    </div>
                    <fieldset>
                        <legend>
                            <h6>Add loan deduction countdown</h6>
                        </legend>
                        <div class="row">
                            <div class="col-12">
                                <label for="">Deduction Name</label>
                                <input type="text" name="deduction" disabled readonly value="{{\App\Models\Deduction::find($deduction_name)->deduction_name}}" class="form-control-sm">

                                <label for="">Payroll Number @error('payroll_number') <span class="text-danger">{{$message}}</span> @enderror</label>
                                <input type="text" name="payroll_number" class="form-control-sm  @error('payroll_number') is-invalid @enderror" wire:model.blur="payroll_number">
                            </div>

                            <div class="col-12 col-md-7 mb-2">
                                <fieldset style="height: 100px">
                                    <table>
                                        <tr>
                                            <th>Staff Number:</th>
                                            <td>{{$staff->staff_number?? null}}</td>
                                        </tr>
                                        <tr>
                                            <th>Staff Name:</th>
                                            <td>{{$staff->full_name?? null}}</td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-12 col-lg-4">
                                        <label for="">Installment Amount @error('installment_amount') <span class="text-danger">{{$message}}</span> @enderror</label>
                                        <input type="text" class="form-control-sm @error('installment_amount') is-invalid @enderror" wire:model="installment_amount">
                                    </div>
                                    <div class="col-12 col-lg-4">
                                        <label for="">No of Installment @error('number_of_installment') <span class="text-danger">{{$message}}</span>@enderror</label>
                                        <input type="number" class="form-control-sm @error('number_of_installment') is-invalid @enderror" wire:model="number_of_installment">
                                    </div>
                                    <div class="col-12 col-lg-4">
                                        <label for="">Start Month/Year @error('start_month') <small class="text-danger" style="position: absolute;top:-40px">{{$message}} @enderror</small></label>
                                        <input type="month" class="form-control-sm @error('start_month') is-invalid @enderror" wire:model="start_month">
                                    </div>

                                </div>
                            </div>


                        </div>
                    </fieldset>
                    <div class="text-center mt-2">
                        <button class="btn my-1 my-md-0  save_btn" type="submit">Save</button>
                        <button class="btn my-1 my-md-0  close_btn" wire:click.prevent="close()">Close</button>
                    </div>
                </div>
            </div>
        </form>
   @endif
    @if($edit==true)
        <form action="" wire:submit.prevent="update({{$ids}})" >
            <div class="row">
                <div class="col">
                    <div wire:loading  style="position: absolute;z-index: 9999;text-align: center;width: 100%;height: 50vh;padding: 25vh">
                        <div style="background: rgba(14,13,13,0.13);margin: auto;max-width:100px;">
                            <i class="fa fa-spin fa-spinner" style="font-size:100px"></i>
                        </div>
                    </div>
                    <fieldset>
                        <legend>
                            <h6>Update loan deduction countdown</h6>
                        </legend>
                        <div class="row">
                            <div class="col-12">
                                <label for="">Deduction Name</label>
                                <select name="" readonly disabled class="form-control-sm @error('deduction') is-invalid @enderror" wire:model="deduction">
                                    <option value="">Select Deduction</option>
                                    @foreach(\App\Models\Deduction::where('deduction_type',2)->get() as $deduction)
                                        <option value="{{$deduction->id}}">{{$deduction->deduction_name}}</option>
                                    @endforeach
                                </select>

                                <label for="">Payroll Number</label>
                                <input type="text" name="payroll_number" class="form-control-sm  @error('payroll_number') is-invalid @enderror" wire:model.blur="payroll_number">
                            </div>

                            <div class="col-12 col-md-7 mb-2">
                                <fieldset style="height: 100px">
                                    <table>
                                        @php
                                            $staffs=\App\Models\EmployeeProfile::where('payroll_number',$payroll_number)->first();

                                        @endphp
                                        <tr>
                                            <th>IPP Number:</th>
                                            <td>{{$staffs->payroll_number?? null}}</td>
                                        </tr>
                                        <tr>
                                            <th>Staff Name:</th>
                                            <td>{{$staffs->full_name?? null}}</td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </div>

                            <div class="col-12 col-lg-6">
                                <label for="">Installment Amount</label>
                                <input type="text" style="min-width:70% !important; " class="form-control-sm @error('installment_amount') is-invalid @enderror" wire:model="installment_amount">
                            </div>
                            <div class="col-12 col-lg-4">
                                <label for="">No of Installment</label>
                                <input type="text" class="form-control-sm @error('number_of_installment') is-invalid @enderror" wire:model="number_of_installment">
                            </div>
                            <div class="col-12 col-lg-4">
                                <label for="">Start Month</label>
                                <input type="month" class="form-control-sm @error('start_month') is-invalid @enderror" wire:model="start_month">
                            </div>


                        </div>
                    </fieldset>
                    <div class="text-center mt-2">
                        <button class="btn  save_btn" type="submit">Save</button>
                        <button class="btn  close_btn" wire:click.prevent="close()">Close</button>
                    </div>
                </div>
            </div>
        </form>
    @endif

    @section('title')
      Loan Deduction Countdown
    @endsection
    @section('page_title')
        Payroll Update / Loan Deduction
    @endsection
</div>
