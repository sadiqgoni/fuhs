<?php

namespace App\Livewire\Forms;

use App\Imports\AllowanceImport;
use App\Imports\AllowImport;
use App\Imports\BankImport;
use App\Imports\DedImport;
use App\Imports\DeductionImport;
use App\Imports\DepartmentImport;
use App\Imports\EmployeeProfileImport;
use App\Imports\EmployeesImport;
use App\Imports\EmpTypeImport;
use App\Imports\PFAImport;
use App\Imports\RankImport;
use App\Imports\SalaryHistoryImport;
use App\Imports\SalaryStructureImport;
use App\Imports\SalaryTemplateImport;
use App\Imports\SalaryUpdateImport;
use App\Imports\UnitImport;
use App\Jobs\DeductionHistoryJob;
use App\Jobs\EmployeeJob;
use App\Jobs\SalaryHistoryJob;
use App\Models\ActivityLog;
use App\Models\Deduction;
use App\Models\RestoreHistory;
use App\Models\SalaryAllowanceTemplate;
use App\Models\SalaryDeductionTemplate;
use App\Models\SalaryHistory;
use App\Models\SalaryStructureTemplate;
use App\Models\SalaryUpdate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Restore extends Component
{
    use WithFileUploads;
    use WithPagination;
    use LivewireAlert;
    public $importFile;
    public $batchId;
    public $importing=false;
    public $importFilePath;
    public $importFinished=false;
    public $iteration;
    public $failures;
    public $restore_type;
    public $error_messages;
    public function import()
    {
        $this->validate([
            'importFile'=>'required|mimes:xlsx',
            'restore_type'=>'required'
        ]);

        $restoreBbj=new RestoreHistory();
        $restoreBbj->restore_by=Auth::id();
        if ($this->restore_type==1 || $this->restore_type==2) {
            $this->importing = true;
        }
        $this->importFilePath=$this->importFile->store('imports');
        if ($this->restore_type==1){
            try {
            $name="payroll";
            $batch=Bus::batch([
                new SalaryHistoryJob($this->importFilePath),
            ])->dispatch();
            $restoreBbj->restore_type=1;
            $restoreBbj->restore_name="Payroll Data";
            $restoreBbj->save();
            }catch (\Exception $e)
            {
                $this->error_messages=$e->getMessage();
                $this->alert('warning','Fail to restore data please check you are uploading the right restore file');
                return back();
            }
        }elseif($this->restore_type==2){
            try {
            $name="loan deduction history";
            $batch=Bus::batch([
                new DeductionHistoryJob($this->importFilePath),

            ])->dispatch();
            $restoreBbj->restore_type=2;
            $restoreBbj->restore_name="Loan Deduction Data";
            $restoreBbj->save();
            }catch (\Exception $e)
            {
                $this->error_messages=$e->getMessage();
                $this->alert('warning','Fail to restore data please check you are uploading the right restore file');
                return back();
            }
        }elseif ($this->restore_type==3)
        {
            try {
                \App\Models\EmployeeProfile::query()->truncate();
                \App\Models\SalaryUpdate::query()->truncate();
                $name="employee profile";
                $import = new EmployeeProfileImport();
                $import->import($this->importFilePath);
                $restoreBbj->restore_type=3;
                $restoreBbj->restore_name="Employee Profile Data";
                $restoreBbj->save();
            }catch (\Exception $e)
            {
                $this->error_messages=$e->getMessage();
                $this->alert('warning','Fail to restore data please check if you are uploading the right restore file');
                return back();
            }
        }elseif ($this->restore_type==4)
        {
            try {
                SalaryUpdate::query()->truncate();
            $name="salary update";
            $import = new SalaryUpdateImport();
            $import->import($this->importFilePath);
            $restoreBbj->restore_type=4;
            $restoreBbj->restore_name="Salary Update Data";
            $restoreBbj->save();
            }catch (\Exception $e)
            {
                $this->error_messages=$e->getMessage();
                $this->alert('warning','Fail to restore data please check  you are uploading the right restore file');
                return back();
            }
        }elseif ($this->restore_type==5)
        {
            try {
                SalaryStructureTemplate::query()->truncate();

                $name="salary template";
            $import = new SalaryTemplateImport();
            $import->import($this->importFilePath);
            $restoreBbj->restore_type=5;
            $restoreBbj->restore_name="Salary Template Data";
            $restoreBbj->save();
            }catch (\Exception $e)
            {
                $this->error_messages=$e->getMessage();
                $this->alert('warning','Fail to restore data please check you are uploading the right restore file');
                return back();
            }
        }elseif ($this->restore_type==6)
        {
            try {
                SalaryAllowanceTemplate::query()->truncate();

                $name="allowance template";
            $import = new AllowanceImport();
            $import->import($this->importFilePath);
            $restoreBbj->restore_type=6;
            $restoreBbj->restore_name="Allowance Template Data";
            $restoreBbj->save();
            }catch (\Exception $e)
            {
                $this->error_messages=$e->getMessage();
                $this->alert('warning','Fail to restore data please check you are uploading the right restore file');
                return back();
            }
        }elseif ($this->restore_type==7)
        {
            try {
                SalaryDeductionTemplate::query()->truncate();

                $name="deduction template";
            $import = new DeductionImport();
            $import->import($this->importFilePath);
            $restoreBbj->restore_type=7;
            $restoreBbj->restore_name="Deduction Template Data";
            $restoreBbj->save();
            }catch (\Exception $e)
            {
                $this->error_messages=$e->getMessage();
                $this->alert('warning','Fail to restore data please check you are uploading the right restore file');
                return back();
            }
        }
        elseif ($this->restore_type==8)
        {
            try {
                \App\Models\Bank::query()->truncate();

                $name="Banks Data";
                $import = new BankImport();
                $import->import($this->importFilePath);
                $restoreBbj->restore_type=8;
                $restoreBbj->restore_name=$name;
                $restoreBbj->save();
            }catch (\Exception $e)
            {
                $this->error_messages=$e->getMessage();
                $this->alert('warning','Fail to restore data please check you are uploading the right restore file');
                return back();
            }
        }
        elseif ($this->restore_type==9)
        {
            try {
                \App\Models\PFA::query()->truncate();

                $name="PFA Data";
                $import = new PFAImport();
                $import->import($this->importFilePath);
                $restoreBbj->restore_type=9;
                $restoreBbj->restore_name=$name;
                $restoreBbj->save();
            }catch (\Exception $e)
            {
                $this->error_messages=$e->getMessage();
                $this->alert('warning','Fail to restore data please check you are uploading the right restore file');
                return back();
            }
        }
        elseif ($this->restore_type==10)
        {
            try {
                \App\Models\Unit::query()->truncate();

                $name="Unit Data";
                $import = new UnitImport();
                $import->import($this->importFilePath);
                $restoreBbj->restore_type=10;
                $restoreBbj->restore_name=$name;
                $restoreBbj->save();
            }catch (\Exception $e)
            {
                $this->error_messages=$e->getMessage();
                $this->alert('warning','Fail to restore data please check you are uploading the right restore file');
                return back();
            }
        }
        elseif ($this->restore_type==11)
        {
            try {
                \App\Models\Department::query()->truncate();

                $name="Department Data";
                $import = new DepartmentImport();
                $import->import($this->importFilePath);
                $restoreBbj->restore_type=11;
                $restoreBbj->restore_name=$name;
                $restoreBbj->save();
            }catch (\Exception $e)
            {
                $this->error_messages=$e->getMessage();
                $this->alert('warning','Fail to restore data please check you are uploading the right restore file');
                return back();
            }
        }
        elseif ($this->restore_type==12)
        {
            try {
                \App\Models\Rank::query()->truncate();

                $name="Rank Data";
                $import = new RankImport();
                $import->import($this->importFilePath);
                $restoreBbj->restore_type=12;
                $restoreBbj->restore_name=$name;
                $restoreBbj->save();
            }catch (\Exception $e)
            {
                $this->error_messages=$e->getMessage();
                $this->alert('warning','Fail to restore data please check you are uploading the right restore file');
                return back();
            }
        }
        elseif ($this->restore_type==13)
        {
            try {
                \App\Models\EmploymentType::query()->truncate();

                $name="Employment Type Data";
                $import = new EmpTypeImport();
                $import->import($this->importFilePath);
                $restoreBbj->restore_type=13;
                $restoreBbj->restore_name=$name;
                $restoreBbj->save();
            }catch (\Exception $e)
            {
                $this->error_messages=$e->getMessage();
                $this->alert('warning','Fail to restore data please check you are uploading the right restore file');
                return back();
            }
        }
        elseif ($this->restore_type==14)
        {
            try {
                \App\Models\SalaryStructure::query()->truncate();

                $name="Salary Structure Data";
                $import = new SalaryStructureImport();
                $import->import($this->importFilePath);
                $restoreBbj->restore_type=14;
                $restoreBbj->restore_name=$name;
                $restoreBbj->save();
            }catch (\Exception $e)
            {
                $this->error_messages=$e->getMessage();
                $this->alert('warning','Fail to restore data please check you are uploading the right restore file');
                return back();
            }
        }
        elseif ($this->restore_type==15)
        {
            try {
                \App\Models\Allowance::query()->truncate();

                $name="Allowance Data";
                $import = new AllowImport();
                $import->import($this->importFilePath);
                $restoreBbj->restore_type=15;
                $restoreBbj->restore_name=$name;
                $restoreBbj->save();
            }catch (\Exception $e)
            {
                $this->error_messages=$e->getMessage();
                $this->alert('warning','Fail to restore data please check you are uploading the right restore file');
                return back();
            }
        }
        elseif ($this->restore_type==16)
        {
            try {
               Deduction::query()->truncate();
                $name="Deduction Data";
                $import = new DedImport();
                $import->import($this->importFilePath);
                $restoreBbj->restore_type=15;
                $restoreBbj->restore_name=$name;
                $restoreBbj->save();
            }catch (\Exception $e)
            {
                $this->error_messages=$e->getMessage();
                $this->alert('warning','Fail to restore data please check you are uploading the right restore file');
                return back();
            }
        }
        if ($this->restore_type==1 || $this->restore_type==2){
            $this->batchId=$batch->id;
            $this->importFile=null;
            $this->iteration++;
        }
        $this->reset('importFile');

        $this->alert('success','successful',[
            'timer'=>9000,
            'timerProgressBar'=>true
        ]);
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $log->action="Restored $name data ";
        $log->save();



    }
    public function getImportBatchProperty()
    {
        if ($this->restore_type==1 || $this->restore_type==2) {
            $this->importFile = '';
            $this->importFile = [];
            if (!$this->batchId) {
                return null;
            }
            return Bus::findBatch($this->batchId);
        }
    }
//
    public function updateImportProgress()
    {
        if ($this->restore_type==1 || $this->restore_type==2) {
            $this->importFinished = $this->importBatch->finished();
            if ($this->importFinished) {
                Storage::delete($this->importFilePath);
                $this->importing = false;
            }
        }
    }
    public function render()
    {
        return view('livewire.forms.restore')->extends('components.layouts.app');
    }
}
