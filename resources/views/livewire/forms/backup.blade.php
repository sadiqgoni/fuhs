<div>
    {{-- A good traveler has no fixed plans and is not intent upon arriving. --}}
    @push('styles')
        <style>
           input[type=checkbox]{
               height: 25px;
               width: 25px;
               border: solid white;
               border-width: 0 3px 3px 0;
           }
        </style>
    @endpush
    <div>
        <form action="" wire:submit.prevent="store()">
{{--            <legend>:</legend>--}}
           <fieldset>
               <div wire:loading  style="position: absolute;z-index: 9999;text-align: center;width: 100%;height: 50vh;padding: 25vh">
                   <div style="background: rgba(14,13,13,0.13);margin: auto;max-width:100px;">
                       <i class="fa fa-spin fa-spinner" style="font-size:100px"></i>
                   </div>
               </div>
               <div class="form-group">
                   <input type="radio" wire:model="backup_type" value="1" class="form-control-sm @error('backup_type') is-invalid @enderror"><label for="">Payroll History</label>
                  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                   <input type="radio" wire:model="backup_type" value="2" class="form-control-sm @error('backup_type') is-invalid @enderror"><label for="">Loan Deduction History</label>
               </div>

               <div style="padding-left: 5%">
{{--                   <div class="form-group">--}}
{{--                       <input type="checkbox" class="form-control-sm" wire:model="data_only" value="1"><label for="">Backup Payroll history data only</label>--}}
{{--                   </div>--}}
                   <div class="form-group">
                       <input type="checkbox" class="form-control-sm" wire:model="delete_record" value="2"><label for="">After backup delete record from my computer </label>
                   </div>
                   <div class="form-group">
                       <input type="checkbox" class="form-control-sm" wire:model="leave_data" value="3"><label for="">After backup leave the data on my computer </label>
                   </div>
                   <div class="form-group" style="padding-left: 3%">
                       <label for="" class="my-2 my-md-0">Data from month year</label><input type="month" class="form-control-sm @error('month_year_from') is-invalid @enderror" wire:model="month_year_from">
                       <label for="" class="my-2 my-md-0">To</label> <input wire:model="month_year_to" type="month" class="form-control-sm @error('month_year_to') is-invalid @enderror">
                   </div>
               </div>
               <input type="radio" wire:model="backup_type" value="3" class="form-control-sm @error('backup_type') is-invalid @enderror"><label for="">Other Backups</label>
                <div class="form-group">
{{--                    <label for="">Other Backups</label>--}}
                    <select name="" id="" wire:model="other_backup_type" class="form-control-sm">
                        <option value="">Select what to backup</option>
                        <option value="1">Employee Profile/Salary Data</option>
                        <option value="3">Salary Structure Template</option>
                        <option value="4">Allowance Template</option>
                        <option value="5">Deduction Template</option>
                        <option value="6">Banks Data</option>
                        <option value="7">PFA Data</option>
                        <option value="8">Unit Data</option>
                        <option value="9">Department Data</option>
                        <option value="10">Rank Data</option>
                        <option value="11">Employment Type Data</option>
                        <option value="12">Salary Structure Data</option>
                        <option value="13">Allowance Data</option>
                        <option value="14">Deduction Data</option>
                    </select>
                </div>

           </fieldset>
            <fieldset class="mt-3">
                <legend for="" class="mt-4"><h6>Chose how you want to do the backup</h6></legend>
                <input type="radio" wire:model="backup_location" value="1" class="form-control-sm @error('backup_location') is-invalid @enderror"><label for="">Online Backup</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" wire:model="backup_location" value="2" class="form-control-sm @error('backup_location') is-invalid @enderror"><label for="">On my Computer</label>
            </fieldset>
            <ul class="text-danger" style="list-style: none">

                <li>@error('backup_type') {{$message}} @enderror</li>
                <li>@error('backup_location') {{$message}} @enderror</li>


            </ul>

<div class="mt-3 text-center">
    <button class="btn export" type="submit">Proceed</button>
</div>
        </form>
    </div>

    @section('title')
        Backup Center
    @endsection
    @section('page_title')
        Security / Backup
    @endsection
</div>
