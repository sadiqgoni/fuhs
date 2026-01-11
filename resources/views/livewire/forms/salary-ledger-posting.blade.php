<div>
    {{-- Because she competes with no one, no one can compete with her. --}}
    <div class="row">
        <div class="col-lg-12 ">

            <form style="padding: 10px">
               <fieldset>
                   <legend>:</legend>
                   <div wire:loading style="position: absolute;z-index: 9999;text-align: center;width: 100%;padding: 25vh;top: -50px">
                       <div style="background: rgba(14,13,13,0.13);margin: auto;max-width:100px;">
                           <i class="fa fa-spin fa-spinner" style="font-size:100px"></i>
                       </div>
                   </div>
                   <div class="row">
                       <div class="col-12 col-md-6">
                           <div class="form-group">
                               @error('month')
                               <strong class="text-danger">{{$message}}</strong>
                               @enderror
                               <div class="input-group">
                                   <div class="input-group-prepend"><span class="input-group-text">Month</span></div>
                                   <select class="form-control @error('month') is-invalid @enderror" wire:model.defer="month" >
                                       <option value="">Select Month</option>
                                       <option value="January">January</option>
                                       <option value="February">February</option>
                                       <option value="March">March</option>
                                       <option value="April">April</option>
                                       <option value="May">May</option>
                                       <option value="June">June</option>
                                       <option value="July">July</option>
                                       <option value="August">August</option>
                                       <option value="September">September</option>
                                       <option value="October">October</option>
                                       <option value="November">November</option>
                                       <option value="December">December</option>
                                   </select>

                                   <div class="input-group-append"></div>
                               </div>
                           </div>
                       </div>
                       <div class="col-12 col-md-6">
                           <div class="form-group">
                               @error('year')
                               <strong class="text-danger">{{$message}}</strong>
                               @enderror
                               <div class="input-group">
                                   <div class="input-group-prepend"><span class="input-group-text">Year</span></div>
                                   <select class="form-control @error('year') is-invalid @enderror" wire:model.blur="year">
                                       @php
                                           $firstYear = \Illuminate\Support\Carbon::now()->subYears(20)->format('Y');
                                           $lastYear = \Illuminate\Support\Carbon::now()->addYears(20)->format('Y');
                                       @endphp
                                       @for($i=$firstYear;$i<=$lastYear;$i++)
                                           <option value="{{$i}}" @if(date('Y') == $i) selected @endif>{{$i}}</option>
                                       @endfor
                                   </select>
                                   <div class="input-group-append"></div>
                               </div>
                           </div>

                       </div>
                       <div class="col-12 col-md-12">
                           @error('description')
                           <strong class="text-danger">{{$message}}</strong>
                           @enderror
                           <div class="input-group">
                               <div class="input-group-prepend"><span class="input-group-text">Description</span></div>
                               <input class="form-control @error('description') is-invalid @enderror" wire:model.blur="description" type="text">
                               <div class="input-group-append"></div>
                           </div>
                       </div>

                   </div>
               </fieldset>


                <button  wire:click.prevent="store()" class="btn mt-3 save_btn" type="button">Post</button>
            </form>
        </div>
    </div>

    @section('title')
        Salary Ledger Posting
    @endsection
    @section('page_title')
        Payroll Update /  Salary  Posting
    @endsection
</div>
