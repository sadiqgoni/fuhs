<div>
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}

    <div>
        <form action="" wire:submit="import">
            <fieldset>
                <div wire:loading  style="position: absolute;z-index: 9999;text-align: center;width: 100%;height: 50vh;padding: 25vh">
                    <div style="background: rgba(14,13,13,0.13);margin: auto;max-width:100px;">
                        <i class="fa fa-spin fa-spinner" style="font-size:100px"></i>
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Restore @error('restore_type') <small class="text-danger">{{$message}}</small> @enderror</label>
                    <select name="" id="" wire:model="restore_type" class="form-control-sm">
                        <option value="">Select Backup File</option>
                        <option value="1">Payroll Data</option>
                        <option value="2">Loan Deduction History </option>
                        <option value="3">Employee Profile Data</option>
                        <option value="4">Salary Update Data</option>
                        <option value="5">Salary Template</option>
                        <option value="6">Salary Allowance Template</option>
                        <option value="7">Salary Deduction Template</option>
                        <option value="8">Banks Data</option>
                        <option value="9">PFA Data</option>
                        <option value="10">Unit Data</option>
                        <option value="11">Department Data</option>
                        <option value="12">Rank Data</option>
                        <option value="13">Employment Type Data</option>
                        <option value="14">Salary Structure Data</option>
                        <option value="15">Allowance Data</option>
                        <option value="16">Deduction Data</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Chose file to restore from</label>
                    <input type="file" id="upload{{ $iteration }}"  class="form-control" wire:model.live="importFile">
                    @error('importFile')
                        <div id="validationServer03Feedback" class="is-invalid text-danger">{{$message}}</div>
                    @enderror
                    @if($importing && !$importFinished)
                        <div wire:poll="updateImportProgress" class="text-danger">Importing...please wait.</div>
                     @endif
                     @if($importFinished)
                        <em class="text-success font-weight-bold">Finished Importing</em>
                     @endif
                </div>
            </fieldset>
            <div class="mt-3 text-right">
                <button class="btn save_btn" wire:loading.attr="disabled">Proceed</button>
            </div>
        </form>
    </div>
    <div class="text-danger">
        {{$error_messages}}
    </div>
    @section('title')
        Data Restore
    @endsection
    @section('page_title')
        Security / Restore
    @endsection
</div>
