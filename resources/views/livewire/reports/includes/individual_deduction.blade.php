<h6 style="text-align: center !important;margin: 10px 0;text-transform: uppercase;">Individual Salary Deduction Report</h6>

@forelse($deductions as $key=>$report)
      @php
          $deduct=\App\Models\Deduction::find($report->deduction_id);
            $records=\App\Models\TemporaryDeduction::where('deduction_id',$report->deduction_id)->get();
      @endphp
      <p style="text-transform: uppercase">{{$deduct->code}}: {{$deduct->deduction_name}}</p>

       <div class="row">
           <div class="col-12 col-lg-8 offset-lg-2 table-responsive">
               <table class="table table-sm">
                   <thead>
                  <tr>
                      <th>SN</th>
                      <th>MONTH/YEAR</th>
                      <th>AMOUNT</th>
                  </tr>
                   </thead>
                   <tbody>
                   @forelse($records as $item)
                       <tr>
                           <th>{{$loop->iteration}}</th>
                           <td>{{\Illuminate\Support\Carbon::parse($item->date_month)->format('F,Y')}}</td>
                           <td>{{number_format($item->amount,2)}}</td>
                       </tr>
                   @empty
                       {{----}}
                   @endforelse
                   </tbody>

{{--                    <tr>--}}
{{--                        <th colspan="2" class="text-right">Subtotal:</th>--}}
{{--                        <th>{{number_format($records->sum('amount'),2)}}</th>--}}
{{--                    </tr>--}}
               </table>
           </div>
       </div>

@empty
    no record
@endforelse
<div class="text-right">
{{--    <p colspan="2" class="float-right" style="font-weight: bolder;text-align: right">Grand Total:{{number_format($report[0]->sum('amount'),2)}}</p>--}}
</div>
