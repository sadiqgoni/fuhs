
@if($record==true)
    <div class="row">
        <div class="col">
            <div>
                <label for="">Search</label>
                <input type="text" class="form-control-sm" wire:model="search">

                <button class="btn btn-sm float-right create px-4" wire:click="create()">Add</button>
            </div>
            <table class="table table-bordered" style="font-size: 14px">
                <thead class="text-center">
                <tr>
                    <th>S/N</th>
                    <th>EMPLOYEE</th>
                    <th>DEDUCTION NAME</th>
                    <th>TOTAL AMOUNT</th>
                    <th>INSTALL AMOUNT</th>
                    <th>NO OF INSTALL</th>
                    <th>START MONTH</th>
                    <th>START YEAR</th>
                    <th>REMAINING INSTALL</th>
                    <th>CURRENT SALARY MONTH</th>
                    <th> ACTIVE</th>
                </tr>
                </thead>
                <tbody>
                @forelse($deduction_records as $record)
                    <tr>
                        <th>{{($deduction_records->currentpage() -1) * $deduction_records->perpage() + $loop->index+1}}</th>
                        <td>{{$record->full_name}}</td>
                        <td>{{$record->deduction_name}}</td>
                        <td>{{$record->total_amount}}</td>
                        <td>{{$record->installment_amount}}</td>
                        <td>{{$record->no_of_installment}}</td>
                        <td>{{$record->start_month}}</td>
                        <td>{{$record->start_year}}</td>
                        <td>{{$record->current_salary_month}}</td>
                        <td>{{$record->Status}}</td>
                    </tr>
                @empty

                @endforelse
                </tbody>
                <tfoot>
                <tr>
                    <td>{{$deduction_records->links()}}</td>
                </tr>
                </tfoot>
            </table>

        </div>
    </div>
@endif
@if($record==false)
    <form action="" wire:submit="store()">
        <div class="row">
            <div class="col">
                <h6>Add loan deduction countdown</h6>
                <fieldset>
                    <div class="row">
                        <div class="col-12">
                            <label for="">Staff Number</label>
                            <input type="text" name="staff_number" class="form-control-sm  @error('staff_number') is-invalid @enderror" wire:model.blur="staff_number">
                        </div>
                        <div class="col-7 mb-2">
                            <fieldset style="height: 100px">
                                <table>
                                    <tr>
                                        <th>Staff Name:</th>
                                        <td>{{$staff->full_name?? null}}</td>
                                    </tr>
                                </table>
                            </fieldset>
                        </div>
                        <div class="col-6">
                            <label for="">Total Amount</label>
                            <input type="text" class="form-control-sm @error('total_amount') is-invalid @endif" wire:model="total_amount">
                        </div>
                        <div class="col-6">
                            <label for="">Installment Amount</label>
                            <input type="text" class="form-control-sm @error('installment_amount') is-invalid @endif" wire:model="installment_amount">
                        </div>
                        <div class="col-4">
                            <label for="">No of Installment</label>
                            <input type="text" class="form-control-sm @error('number_of_installment') is-invalid @endif" wire:model="number_of_installment">
                        </div>
                        <div class="col-4">
                            <label for="">Start Month</label>
                            <input type="month" class="form-control-sm @error('start_month') is-invalid @endif" wire:model="start_month">
                        </div>
                        <div class="col-4">
                            <label for="">End Month</label>
                            <input type="month" class="form-control-sm @error('end_year') is-invalid @endif" wire:model="end_year">
                        </div>

                    </div>
                </fieldset>
                <div class="text-center mt-2">
                    <button class="btn  save_btn" type="submit">Save</button>
                    <button class="btn  close_btn" wire:click="close()">Close</button>
                </div>
            </div>
        </div>
    </form>
@endif

<div class="col-12">
    <label for="">Deduction Name</label>
    <select name="" id="" class="form-control-sm @error('deduction') is-invalid @endif" wire:model="deduction">
        <option value="">Select Deduction</option>
        @foreach(\App\Models\Deduction::all() as $deduction)
            <option value="{{$deduction->id}}">{{$deduction->deduction_name}}</option>
        @endforeach
    </select>
</div>
