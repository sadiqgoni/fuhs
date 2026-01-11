<div>
    <style>
        svg{
            display: none;
        }
    </style>
    {{-- Nothing in the world is as soft and yielding as water. --}}
    @if($record==true)
        <div class="text-right">
            <label for="" class=" float-left">Filter By</label>
            <select wire:model.live="filter" class="form-control-sm float-left">
                <option value="">Salary Structure</option>
                @foreach(\App\Models\SalaryStructure::get() as $salary_structure)
                    <option value="{{$salary_structure->id}}">{{$salary_structure->name}}</option>
                @endforeach
            </select>

<button class="btn mt-2 create" wire:click.prevent="create_ss()">Add</button>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-sm  mt-2" style="font-size: 13px">
                <thead>
                <tr style="text-transform: uppercase">
                    <th>S/N</th>
                    <th>Salary Structure</th>
                    <th>Grade Level</th>
                    <th>No of Steps</th>
                    @php
                        if (!is_null($filter_grade)){
             try {
    $no_of_grade=\App\Models\SalaryStructureTemplate::where('salary_structure_id',$filter)
                               ->where('grade_level',$filter_grade)
                              ->first()->no_of_grade_steps;
     }catch (\Exception $e){
                 $no_of_grade=\App\Models\SalaryStructureTemplate::where('salary_structure_id',$filter)
                               ->max('no_of_grade_steps');
     }
        }else{
              $no_of_grade=\App\Models\SalaryStructureTemplate::where('salary_structure_id',$filter)
                               ->max('no_of_grade_steps');

        }

                    @endphp
                    @if($filter !='' && $no_of_grade !=null)
                        @for($i=1; $i<=$no_of_grade; $i++ )
                            <th>Step {{$i}}</th>
                        @endfor
                    @endif
                    <th>Action </th>
                </tr>
                </thead>
                <tbody>
                @forelse($salaries as $salary)
                    <tr>
                        <th></th>
                        <td>{{ss($salary->salary_structure_id)}}</td>
                        <td>Grade {{$salary->grade_level}}</td>
                        <td>Step {{$salary->no_of_grade_steps}}</td>
                        @php
                            if (!is_null($filter_grade)){
                 try {
        $no_of_grade=\App\Models\SalaryStructureTemplate::where('salary_structure_id',$filter)
                                   ->where('grade_level',$filter_grade)
                                  ->first()->no_of_grade_steps;
         }catch (\Exception $e){
                     $no_of_grade=\App\Models\SalaryStructureTemplate::where('salary_structure_id',$filter)
                                   ->max('no_of_grade_steps');
         }
            }else{
                  $no_of_grade=\App\Models\SalaryStructureTemplate::where('salary_structure_id',$filter)
                                   ->max('no_of_grade_steps');

            }

                        @endphp
                        @for($i=1; $i<=$no_of_grade; $i++)
                            <td>{{number_format($salary['Step'.$i],2)}}</td>
                        @endfor
                        <td>
                            <button class="btn btn-sm btn-info" wire:click.prevent="edit_salary_structure({{$salary->id}})">Edit</button>
{{--                            <button class="btn btn-sm btn-danger" wire:click.prevent="deleteId({{$salary->id}})" style="width:fit-content !important;padding: 5px !important;">Delete</button>--}}

                        </td>
                    </tr>
                @empty
                @endforelse
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5">{{$salaries->links()}}</td>
                </tr>
                </tfoot>
            </table>

        </div>
    @endif


    @if($edit==true)
        <div class="row">
            <div class="col ">
                <form action="">
                    <fieldset>
                        <legend><h6 class="">Update Salary Structure</h6></legend>

                        <div class="form-group input-group">
                            <div class="input-group-prepend"><span class="input-group-text">Salary Structure</span></div>

                            <input class="form-control" wire:model="salary_structure" readonly disabled type="text">

                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text">Grade Level</span></div>
                                    <input wire:model="grade_level"   readonly disabled class="form-control" type="text">
                                    <div class="input-group-append"></div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text">No of Steps</span></div>
                                    <input wire:model="no_of_steps"   readonly disabled class="form-control" type="text">
                                    <div class="input-group-append"></div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
                @php
                    $steps=\App\Models\SalaryStructureTemplate::find($ids);

                @endphp
                <form action="" wire:submit.prevent="update({{$ids}})">
                    <fieldset class="mt-4">
                        <legend><h6>Steps</h6></legend>
                        <div class="row">

                            @for($i=1; $i<=$steps->no_of_grade_steps; $i++)
                                <div class="col-12 col-md-4 col-lg-3">
                                    <div class="input-group form-group">
                                        <div class="input-group-prepend"><span class="input-group-text">Step {{$i}}</span></div>
                                        <input wire:model="step{{$i}}"   class="form-control" type="text">
                                        <div class="input-group-append"></div>
                                    </div>
                                </div>

                            @endfor
                        </div>
                    </fieldset>
                    <div class="mt-3">
                        <button class="btn save_btn" type="submit">Save</button>
                        <button class="close_btn mt-2 mt-md-0 btn" wire:click.prevent="close()">Close</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if($create==true)
       <div>
           <form wire:submit.prevent="import()">
               <div wire:loading style="position: absolute;z-index: 9999;text-align: center;width: 100%;height: 50vh;padding: 25vh">
                   <div style="background: rgba(14,13,13,0.13);margin: auto;max-width:100px;">
                       <i class="fa fa-spin fa-spinner" style="font-size:100px"></i>
                   </div>
               </div>
              <fieldset>
                  <div class="form-group">
                      <label for="">Salary Structure @error('salary_structure_name') <small class="text-danger">{{$message}}</small> @enderror</label>
                      <select name="" class="form-control-sm text-capitalize" wire:model.blur="salary_structure_name">
                          <option value="">Select Salary Structure</option>
                          @foreach(\App\Models\SalaryStructure::where('status',1)->get() as $ss)
                              <option value="{{$ss->id}}">{{$ss->name}}</option>
                          @endforeach
                      </select>
                  </div>
                  <legend><h6>Import Salary Structure Template</h6></legend>
                  <label for="">Chose template file</label>
                  <input type="file" id="upload{{ $iteration }}"  class="form-control-sm" wire:model.live="importFile">
                  @error('importFile')
                  <div id="validationServer03Feedback" class="is-invalid text-danger">{{$message}}</div>
                  @enderror
                  @if($importing && !$importFinished)
                      <div wire:poll="updateImportProgress" class="text-danger">Importing...please wait.</div>
                  @endif
                  @if($importFinished)
                      <em class="text-success font-weight-bold">Finished Importing</em>
                  @endif
              </fieldset>
              <div class="mt-3">
                  <button class="btn save_btn">Upload</button>
                  <button class="close_btn mt-2 mt-md-0 btn" wire:click.prevent="close()">Close</button>

              </div>
           </form>
       </div>

    @endif
    @section('title')
        Salary Structure Template
    @endsection
    @section('page_title')
        Payroll Settings / Salary Template
    @endsection
</div>
