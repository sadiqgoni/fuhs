<div>
    {{-- Do your work, then step back. --}}
    <style>
        svg{
            display: none;
        }
    </style>
    <div class="row mt-3">
        <div class="col table-responsive">
            {{--            <h6 class="text-center text-dark">KEEP RECORD OF ANNUAL INCREMENT HISTORY</h6>--}}
            <div>

                    <label for="">Month-Year</label>
                    <input type="month" class="form-control-sm" name="date" wire:model.live="date">

                <label for="">Show</label>
                <select name="" wire:model.live="perpage" id="" class="form-control-sm">
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="250">250</option>
                    <option value="500">500</option>
                    <option value="1000">1000</option>
                </select>
                <select name="" id="" wire:model="orderAsc">
                    <option value="Asc">Asc</option>
                    <option value="Desc">Desc</option>
                </select>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-sm">
                    <div wire:loading  style="position: absolute;z-index: 9999;text-align: center;width: 100%;height: 50vh;padding: 25vh">
                        <div style="background: rgba(14,13,13,0.13);margin: auto;max-width:100px;">
                            <i class="fa fa-spin fa-spinner" style="font-size:100px"></i>
                        </div>
                    </div>
                    <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Date</th>
                        <th>Payroll</th>
                        <th>Payslip</th>
                        <th>Bank Payment </th>
                        <th>Bank Summary</th>
                        <th>Deduction Schedule</th>
                        <th>Deduction Summary</th>
                        <th>Salary Journal</th>
                        <th>PFAs</th>
                        <th>NHIS</th>
                        <th>Emp Pension</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($records as $record)
                        @php
                            $payroll=\App\Models\ReportRepository::where('report_type',1)->where('date',$record->date)->first();
                        $payslip=\App\Models\ReportRepository::where('report_type',2)->where('date',$record->date)->first();
                        $bank_payment=\App\Models\ReportRepository::where('report_type',3)->where('date',$record->date)->first();
                        $deduction=\App\Models\ReportRepository::where('report_type',4)->where('date',$record->date)->first();
                        $deductionSummary=\App\Models\ReportRepository::where('report_type',5)->where('date',$record->date)->first();
                        $bankSummary=\App\Models\ReportRepository::where('report_type',6)->where('date',$record->date)->first();
                        $journal=\App\Models\ReportRepository::where('report_type',7)->where('date',$record->date)->first();
                        $pfa=\App\Models\ReportRepository::where('report_type',8)->where('date',$record->date)->first();
                        $nhis=\App\Models\ReportRepository::where('report_type',9)->where('date',$record->date)->first();
                        $pension=\App\Models\ReportRepository::where('report_type',10)->where('date',$record->date)->first();
                        @endphp
                      <tr>
                          <td>1</td>
                          <td>{{\Illuminate\Support\Carbon::parse($record->date)->format('F Y')}}</td>
                          <td style="padding: 20px !important;">
{{--                              {{$payroll->report_name?? "N/A"}}--}}
                              @if($payroll)
                                  <a href="{{route('report.download',$payroll->id)}}" >
                                      <img src="{{url('assets/img/pdf.png')}}" alt="" style="width: 20%">  <i class="fa fa-download"></i></a>

                              @endif
                          </td>
                          <td>
{{--                              {{$payslip->report_name?? "N/A"}}--}}
                              @if($payslip)
                                  <a href="{{route('report.download',$payslip->id)}}" >
                                      <img src="{{url('assets/img/pdf.png')}}" alt="" style="width: 20%">  <i class="fa fa-download"></i></a>


                              @endif
                          </td>
                          <td>
{{--                              {{$bank_payment->report_name?? "N/A"}}--}}
                              @if($bank_payment)
                                  <a href="{{route('report.download',$bank_payment->id)}}" >
                                      <img src="{{url('assets/img/pdf.png')}}" alt="" style="width: 20%">  <i class="fa fa-download"></i></a>

                              @endif
                          </td>
                          <td>
{{--                              {{$bankSummary->report_name?? "N/A"}}--}}
                              @if($bankSummary)
                                  <a href="{{route('report.download',$bankSummary->id)}}" >
                                      <img src="{{url('assets/img/pdf.png')}}" alt="" style="width: 20%">  <i class="fa fa-download"></i></a>


                              @endif
                          </td>
                          <td>
{{--                              {{$deduction->report_name?? "N/A"}}--}}
                              @if($deduction)
                                  <a href="{{route('report.download',$deduction->id)}}" >
                                      <img src="{{url('assets/img/pdf.png')}}" alt="" style="width: 20%">  <i class="fa fa-download"></i></a>


                              @endif
                          </td>
                          <td>
{{--                              {{$deductionSummary->report_name?? "N/A"}}--}}
                              @if($deductionSummary)
                                  <a href="{{route('report.download',$deductionSummary->id)}}" >
                                      <img src="{{url('assets/img/pdf.png')}}" alt="" style="width: 20%">  <i class="fa fa-download"></i></a>


                              @endif
                          </td>
                          <td>
{{--                              {{$journal->report_name?? "N/A"}}--}}
                              @if($journal)
                                  <a href="{{route('report.download',$journal->id)}}" >
                                      <img src="{{url('assets/img/pdf.png')}}" alt="" style="width: 20%">  <i class="fa fa-download"></i></a>

                              @endif
                          </td>
                          <td>
{{--                              {{$pfa->report_name?? "N/A"}}--}}
                              @if($pfa)
                                  <a href="{{route('report.download',$pfa->id)}}" >
                                      <img src="{{url('assets/img/pdf.png')}}" alt="" style="width: 20%">  <i class="fa fa-download"></i></a>

                              @endif
                          </td>
                          <td>
                              {{--                              {{$nhis->report_name?? "N/A"}}--}}
                              @if($nhis)
                                  <a href="{{route('report.download',$nhis->id)}}" >
                                      <img src="{{url('assets/img/pdf.png')}}" alt="" style="width: 20%">  <i class="fa fa-download"></i></a>

                              @endif
                          </td>
                          <td>
                              {{--                              {{$pfa->report_name?? "N/A"}}--}}
                              @if($pension)
                                  <a href="{{route('report.download',$pension->id)}}" >
                                      <img src="{{url('assets/img/pdf.png')}}" alt="" style="width: 20%">  <i class="fa fa-download"></i></a>

                              @endif
                          </td>

                      </tr>
                    @empty
                        no record
                    @endforelse
                    </tbody>
                    <tr>
                        <td colspan="6">{{$records->links()}}</td>
                    </tr>
                </table>

            </div>
        </div>
    </div>
    @section('title')
        Report Repository
    @endsection
    @section('page_title')
        Report Repository
    @endsection
</div>
