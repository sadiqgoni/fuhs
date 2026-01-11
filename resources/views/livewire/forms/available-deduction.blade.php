<div>
<style>
    svg{display: none}
</style>
{{--    @include('spinner')--}}
    <div wire:loading  style="position: absolute;z-index: 9999;text-align: center;width: 100%;height: 50vh;padding: 25vh">
        <div style="background: rgba(14,13,13,0.13);margin: auto;max-width:100px;">
            <i class="fa fa-spin fa-spinner" style="font-size:100px"></i>
        </div>
    </div>
@if($record==true)
<div>
    {{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}

<div>

    <input class="form-control-sm" placeholder="Search" wire:model.live="search">
        <lable>Show</lable>
            <select wire:model.lazy="perpage">
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="40">40</option>
            </select>

            <button wire:click="create_deduction()" class="btn create load float-right my-2">Add</button>
{{--    <div wire:loading.class="create">--}}

{{--    </div>--}}


</div>
    <div class="table-responsive">
        <table class="table-bordered table-striped" style="width: 100%; border-collapse: collapse;">

            <thead>
            <tr style="text-transform: uppercase; font-weight: bolder">
                <th>S/N</th>
                <th> code</th>
                <th>Deduction name</th>
                <th>Description</th>
                <th>Account No</th>
                <th>Bank Name</th>
                <th>Account Name</th>
                <th>Tin Number</th>
                <th>Visibility</th>
                <th>Deduction Type</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            </thead>
            @forelse($deductions as $deduction)
                <?php
                $bank=\App\Models\Bank::where('bank_code',$deduction->bank_code)->first();
                ?>
                <tr>
                    {{--                <td></td>--}}
                    <td>{{($deductions->currentPage() - 1) * $deductions->perpage() + $loop->index+1}}</td>
                    <td>{{$deduction->Code}}</td>
                    <td>{{$deduction->deduction_name}}</td>
                    <td>{{$deduction->description}}</td>
                    <td>{{$deduction->account_no}}</td>
                    <td>{{$bank->bank_name??null}}</td>
                    <td>{{$deduction->account_name}}</td>
                    <td>{{$deduction->tin_number}}</td>
                    <td>{{visibility_status($deduction->visibility)}}</td>
                    <td>{{deduction_type($deduction->deduction_type)}}</td>
                    <td>{{g_s($deduction->status)}}</td>
                    <div wire:loading wire:target="edit_deduction({{$deduction->id}})" style="position: absolute;z-index: 9999;text-align: center;width: 100%;height: 50vh;padding: 25vh">
                        <div style="background: rgba(14,13,13,0.13);margin: auto;max-width:100px;">
                            <i class="fa fa-spin fa-spinner" style="font-size:100px"></i>
                        </div>
                    </div>
                    <td><button class="btn edit_btn load float-right" style="width:50px !important;padding: 5px !important;" wire:click="edit_deduction({{$deduction->id}})">Edit</button></td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="color: red">Empty</td>
                </tr>
            @endforelse
            <tfoot>
            <tr>
                <td colspan="9">{{$deductions->links()}}</td>
            </tr>
            </tfoot>
        </table>
    </div>

</div>
@endif
@if($create==true)
        <div class="row">
            <div class="col-12 col-md-10 offset-md-1">
                <form wire:submit.prevent="store">

                <fieldset>
                  <legend><h6>Add Deduction</h6></legend>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            @error('deduction_code')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text"><span class="d-none d-md-inline">Deduction</span> Code</span></div>
                                <input class="form-control @error('deduction_code') is-invalid @enderror" wire:model.lazy="deduction_code" type="text">
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            @error('deduction_name')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text"><span class="d-none d-md-inline">Deduction</span> Name</span></div>
                                <input class="form-control @error('deduction_name') is-invalid @enderror" wire:model.lazy="deduction_name" type="text">
                                <div class="input-group-append"></div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            @error('description')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Descr<span class="d-none d-md-inline">iption</span></span></div>
                                <input class="form-control" wire:model.lazy="description" type="text">
                                <dv class="input-group-append"></dv>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            @error('account_number')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text"><span class="d-none d-md-inline">Account Number</span><span class="d-inline d-md-none">Acc No</span></span></div>
                                <input class="form-control" wire:model.lazy="account_number" type="text">
                                <dv class="input-group-append"></dv>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            @error('account_name')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text"><span class="d-none d-md-inline">Account Name</span><span class="d-inline d-md-none">Acc Name</span></span></div>
                                <input class="form-control" wire:model.lazy="account_name" type="text">
                                <dv class="input-group-append"></dv>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            @error('bank_code')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                            <select class="form-control mb-2 @error('bank_code') is-invalid @enderror" wire:model.lazy="bank_code">
                                <?php
                                $banks= \App\Models\Bank::get();
                                ?>
                                <option value="">-- Select Bank --</option>
                                @foreach($banks as $bank)
                                    <option value="{{$bank->bank_code}}">{{$bank->bank_name}}</option>
                                @endforeach
                            </select>

                        </div>
                        <div class="col-12 col-md-6">
                            @error('tin_number')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text"><span class="d-none d-md-inline">TIN Number</span><span class="d-inline d-md-none">TIN</span></span></div>
                                <input class="form-control" wire:model.lazy="tin_number" type="text">
                                <dv class="input-group-append"></dv>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            @error('deduction_type')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text"><span class="d-none d-md-inline">Deduction Type</span><span class="d-inline d-md-none">Type</span></span></div>
                                <select class="form-control" wire:model.lazy="deduction_type">
                                    <option value="">Select Deduction Type</option>
                                    <option value="1">Define in template</option>
                                    <option value="2">Not Define</option>
                                </select>
                                <dv class="input-group-append"></dv>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            @error('status')
                            <small class="text-danger">{{$message}}</small>
                            @enderror


                            <label for="" style="display: block"> Status  </label>

                            <input wire:model.lazy="status" class="form-control-sm" value="1" type="radio"> Active

                            <input wire:model.lazy="status" class="form-control-sm" value="0" type="radio"> Discontinue
                        </div>
                        <div class="col-12 col-md-6">
                            @error('visibility')
                            <small class="text-danger">{{$message}}</small>
                            @enderror


                            <label for="" style="display: block"> Show in Bank payment report  </label>

                            <input wire:model.lazy="visibility" class="form-control-sm" value="1" type="radio"> Show

                            <input wire:model.lazy="visibility" class="form-control-sm" value="0" type="radio"> Hide
                        </div>

                    </div>


                    <div wire:loading wire:target="update({{$ids}})" style="position: absolute;z-index: 9999;text-align: center;width: 100%;height: 50vh;padding: 25vh">
                        <div style="background: rgba(14,13,13,0.13);margin: auto;max-width:100px;">
                            <i class="fa fa-spin fa-spinner" style="font-size:100px"></i>
                        </div>
                    </div>
              </fieldset>
                <div class="row">
                    <div class="col col-12 mt-3">
                        <button class="btn save_btn mr-3 load" type="submit">Save</button>

                        <button class="btn close_btn mt-2 mt-md-0 load" type="button" wire:click.prevent="close()">Close</button>
                    </div>
                </div>
                </form>

            </div>
        </div>

@endif
@if($edit==true)
        <div class="row">
            <div class="col-12 col-md-10 offset-md-1">
                <form wire:submit.prevent="update({{$ids}})">

                    <fieldset>
                        <legend><h6>Update Deduction</h6></legend>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                @error('deduction_code')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><span class="d-none d-md-inline">Deduction</span> Code</span></div>
                                    <input class="form-control @error('deduction_code') is-invalid @enderror" wire:model.lazy="deduction_code" type="text">
                                    <div class="input-group-append"></div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                @error('deduction_name')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><span class="d-none d-md-inline">Deduction</span> Name</span></div>
                                    <input class="form-control @error('deduction_name') is-invalid @enderror" wire:model.lazy="deduction_name" type="text">
                                    <div class="input-group-append"></div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                @error('description')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text">Descr<span class="d-none d-md-inline">iption</span></span></div>
                                    <input class="form-control" wire:model.lazy="description" type="text">
                                    <dv class="input-group-append"></dv>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                @error('account_number')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><span class="d-none d-md-inline">Account Number</span><span class="d-inline d-md-none">Acc No</span></span></div>
                                    <input class="form-control" wire:model.lazy="account_number" type="text">
                                    <dv class="input-group-append"></dv>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                @error('account_name')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><span class="d-none d-md-inline">Account Name</span><span class="d-inline d-md-none">Acc Name</span></span></div>
                                    <input class="form-control" wire:model.lazy="account_name" type="text">
                                    <dv class="input-group-append"></dv>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                @error('bank_code')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                                <select class="form-control mb-2 @error('bank_code') is-invalid @enderror" wire:model.lazy="bank_code">
                                    <?php
                                    $banks= \App\Models\Bank::get();
                                    ?>
                                    <option value="">-- Select Bank --</option>
                                    @foreach($banks as $bank)
                                        <option value="{{$bank->bank_code}}">{{$bank->bank_name}}</option>
                                    @endforeach
                                </select>

                            </div>
                            <div class="col-12 col-md-6">
                                @error('tin_number')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><span class="d-none d-md-inline">TIN Number</span><span class="d-inline d-md-none">TIN</span></span></div>
                                    <input class="form-control" wire:model.lazy="tin_number" type="text">
                                    <dv class="input-group-append"></dv>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                @error('deduction_type')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><span class="d-none d-md-inline">Deduction Type</span><span class="d-inline d-md-none">Type</span></span></div>
                                    <select class="form-control" wire:model.lazy="deduction_type">
                                        <option value="">Select Deduction Type</option>
                                        <option value="1">Define in template</option>
                                        <option value="2">Not Define</option>
                                    </select>
                                    <dv class="input-group-append"></dv>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                @error('status')
                                <small class="text-danger">{{$message}}</small>
                                @enderror


                                <label for="" style="display: block"> Status  </label>

                                <input wire:model.lazy="status" class="form-control-sm" value="1" type="radio"> Active

                                <input wire:model.lazy="status" class="form-control-sm" value="0" type="radio"> Discontinue
                            </div>
                            <div class="col-12 col-md-6">
                                @error('visibility')
                                <small class="text-danger">{{$message}}</small>
                                @enderror


                                <label for="" style="display: block"> Show in Bank payment report  </label>

                                <input wire:model.lazy="visibility" class="form-control-sm" value="1" type="radio"> Show

                                <input wire:model.lazy="visibility" class="form-control-sm" value="0" type="radio"> Hide
                            </div>

                        </div>
                    </fieldset>
                    <div class="row">
                        <div class="col col-12 mt-3">
                            <button class="btn save_btn mt-md-0 mt-2 mr-md-3" type="submit">Update</button>

                            <button class="btn close_btn mt-md-0 mt-2" type="button" wire:click.prevent="close()">Close</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
@endif

    @section('title')
            Available Deduction
    @endsection
            @section('page_title')
        Payroll Settings / Deduction
        @endsection
</div>
