<div>
    @if($create==true)
        <div class="mb-3">
            <form wire:submit.prevent="store()">
                <fieldset>
                    <legend><h6>Add Salary Structure</h6></legend>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="">Salary Structure Name <small class="text-danger">@error('name') {{$message}} @enderror</small></label>
                                <input type="text" class="form-control" wire:model.blur="name">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="">Number of Grades <small class="text-danger">@error('number_of_grade') {{$message}} @enderror</small></label>
                                <input type="number" class="form-control" wire:model.blur="number_of_grade">
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="">Status <small class="text-danger">@error('status') {{$message}} @enderror</small></label>
                            <select name="" id="" wire:model="status" class="form-control">
                                <option value="">Select Status</option>
                                <option value="1">Active</option>
                                <option value="0">Not Active</option>
                            </select>
                        </div>
                    </div>

                </fieldset>
                <div class="mt-3 text-right">
                    <button class="btn save_btn" type="submit">Submit</button>
                    <button class="btn ml-md-3 close_btn mt-2 mt-md-0" wire:click.prevent="close()">Close</button>

                </div>
            </form>
        </div>
    @endif
    @if($edit==true)
        <div class="mb-3">
            <form wire:submit.prevent="update({{$ids}})">
                <fieldset>
                    <legend><h6>Update Salary Structure</h6></legend>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="">Salary Structure Name <small class="text-danger">@error('name') {{$message}} @enderror</small></label>
                                <input type="text" class="form-control" wire:model.blur="name">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="">Number of Grades <small class="text-danger">@error('number_of_grade') {{$message}} @enderror</small></label>
                                <input type="number" class="form-control" wire:model.blur="number_of_grade">
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="">Status <small class="text-danger">@error('status') {{$message}} @enderror</small></label>
                            <select name="" id="" wire:model="status" class="form-control">
                                <option value="">Select Status</option>
                                <option value="1">Activate</option>
                                <option value="0">De Activate</option>
                            </select>
                        </div>
                    </div>

                </fieldset>
                <div class="mt-3 text-right">
                    <button class="btn save_btn" type="submit">Update</button>
                    <button class="btn ml-md-3 mt-2 mt-md-0 close_btn" wire:click.prevent="close()">Close</button>

                </div>
            </form>
        </div>
    @endif
        <div class="">
            <div class="text-right">
                @if($record==true)
                    <button class="btn create" wire:click.prevent="create_ss()">Add</button>

                @endif
            </div>
            <div class="table-responsive">
                <table class="mt-2 table table-stripped table-bordered">
                    <thead>
                    <tr>
                        <th>SN</th>
                        <th>NAME</th>
                        <th>NUMBER OF GRADE</th>
                        <th>STATUS</th>
                        <th>ACTION</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($salaries as $salary)
                        <tr>
                            <th>{{$loop->iteration}}</th>
                            <td>{{$salary->name}}</td>
                            <td>{{$salary->no_of_grade}}</td>
                            <td>{{status($salary->status)}}</td>
                            <td><button class="btn edit_btn" wire:click.prevent="edit_ss({{$salary->id}})">Edit</button></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">no record</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    @section('title')
        Salary Structure
    @endsection
    @section('page_title')
            Payroll Settings / Salary Structure
    @endsection
</div>
