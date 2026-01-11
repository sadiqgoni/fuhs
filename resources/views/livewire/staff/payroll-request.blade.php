<div>
    {{-- In work, do what you enjoy. --}}
    <div class="row">
        <div class="col-lg-12 p-3">
            <form action="{{route('staff.payroll.request')}}" method="post" id="myForm" target="_blank">
                <div wire:loading  style="position: absolute;z-index: 9999;text-align: center;width: 100%;height: 50vh;padding: 25vh">
                    <div style="background: rgba(14,13,13,0.13);margin: auto;max-width:100px;">
                        <i class="fa fa-spin fa-spinner" style="font-size:100px"></i>
                    </div>
                </div>
                @csrf
                <fieldset>

                    <legend >
                        <h6>Request Report <i class="fa fa-bar-chart"></i></h6>
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
                        <div class="col-12 @if($report_type==3) col-md-6 @else col-md-12 @endif">
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
                            <div class="col-12 @if($report_type==3) col-md-6 @else @endif">
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

                    </div>
                    <div class="row">

                    </div>

                </fieldset>
                <div class="row mt-3 ">
                    <div class="col text-center">
                            <button class="btn view my-1 my-md-0" type="submit">View</button>

                    </div>
                </div>

            </form>

        </div>
    </div>
{{--    @include('form_spin')--}}
@section('title')
        Reports Request Page

@endsection
    @section('page_title')
        Reports Request Page
    @endsection
</div>
