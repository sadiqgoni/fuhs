<div>
    {{-- The whole world belongs to you. --}}

    <div class="row">
        <div class="col-lg-12 p-3">
            <form action="{{route('individual.report')}}" method="post" id="myForm" target="_blank">
                <div wire:loading  style="position: absolute;z-index: 9999;text-align: center;width: 100%;height: 50vh;padding: 25vh">
                    <div style="background: rgba(14,13,13,0.13);margin: auto;max-width:100px;">
                        <i class="fa fa-spin fa-spinner" style="font-size:100px"></i>
                    </div>
                </div>
                @csrf
            <fieldset>

                <legend >
                    <h6>Employee Selection:</h6>
                </legend>
                <div class="row">
                    <div class="col">
                        @error('month_from')
                        <strong class="text-danger d-block form-text">{{$message}}</strong>
                        @enderror
                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text">Month From</span></div>
                            <input type="month" class="form-control  @error('month_from') is-invalid @enderror" wire:model.defer="month_from" name="month_from">

                            <div class="input-group-append"></div>
                        </div>
                    </div>
                    <div class="col">
                        @error('month_to')
                        <strong class="text-danger d-block form-text">{{$message}}</strong>
                        @enderror
                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text">Month To</span></div>
                            <input type="month" class="form-control  @error('month_to') is-invalid @enderror" wire:model.defer="month_to" name="month_to">

                            <div class="input-group-append"></div>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-12 @if($report_type==3) col-md-4 @else col-md-6 @endif">
                        @error('report_type')
                        <strong class="text-danger d-block form-text">{{$message}}</strong>
                        @enderror
                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text">Report Type</span></div>
                            <select class="form-control  @error('report_type') is-invalid @enderror" wire:model.live="report_type" name="report_type">
                                <option value="">Report Type</option>
                                <option value="1">Payslip</option>
                                <option value="2">Bank Payment</option>
                                <option value="3">Deduction Details</option>

                            </select>
                            <div class="input-group-append"></div>
                        </div>
                    </div>
                    @if($report_type==3)
                    <div class="col-12 @if($report_type==3) col-md-4 @else @endif">
                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text">Deduction Type</span></div>
                            <select class="form-control  @error('deduction') is-invalid @enderror" wire:model.live="deduction" name="deduction">
                                <option value="">Select Deduction</option>
                               @foreach(\App\Models\Deduction::all() as $deduction)
                                    <option value="{{$deduction->id}}">{{$deduction->deduction_name}}</option>
                               @endforeach

                            </select>
                            <div class="input-group-append"></div>
                        </div>
                    </div>
                    @endif
                    <div class="col-12 @if($report_type==3) col-md-4 @else col-md-6 @endif">
                        @error('payroll_number')
                        <strong class="text-danger d-block form-text">{{$message}}</strong>
                        @enderror
                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text">Payroll Number</span></div>
                            <input type="text" class="form-control @error('payroll_number') is-invalid @enderror" name="payroll_number" wire:model.defer="payroll_number" >

                            <div class="input-group-append"></div>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-12 col-md-3">
                        <label for="">Order By</label>
                        <select type="text" class="form-control-sm" name="order_by" wire:model.defer="order_by">
{{--                            <option value="id">Id</option>--}}
                            <option value="date_month">Month</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-3">
                        <label for="">Order</label>
                        <select type="text" class="form-control-sm" name="order" wire:model.defer="order">
                            <option value="Asc">Asc</option>
                            <option value="Desc">Desc</option>
                        </select>
                    </div>
                </div>

            </fieldset>
            <div class="row mt-3 ">
                <div class="col text-center">
                    @can('can_report')
                        <button wire:loading.attr="disabled" class="btn my-1 my-md-0  generate" type="button"  wire:click="generate()">Generate <span wire:loading><i class="fa fa-spin fa-spinner"></i></span></button>
                        <button class="btn view my-1 my-md-0" type="submit">View</button>
                    @endcan

                </div>
            </div>

            </form>

        </div>
    </div>




    <div class="row">
        <div class="col">
            @if($report_type==1)
                @if(!$payslips ==[])
                    @include('livewire.reports.includes.individual_payslip')
                @endif

            @endif
            @if($report_type==2)
                    @if(!$banks ==[])
                        @include('livewire.reports.includes.individual_bank_payment')
                    @endif

            @endif
            @if($report_type==3)
                @if(!$deductions==[])
                        @include('livewire.reports.includes.individual_deduction')

                    @endif
            @endif
        </div>
    </div>
{{--@include('form_spin')--}}
    @section('title')
       Report for Individual
    @endsection
    @section('page_title')
        Payroll Report /  for Individual
    @endsection
</div>
