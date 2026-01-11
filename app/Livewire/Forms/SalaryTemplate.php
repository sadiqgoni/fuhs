<?php

namespace App\Livewire\Forms;

use App\Jobs\DeductionJob;
use App\Jobs\SalaryJob;
use App\Models\ActivityLog;
use App\Models\SalaryStructureTemplate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class SalaryTemplate extends Component
{
    public $salary_structure, $grade_level, $no_of_steps, $filter=1,$filter_grade;

    public $step1, $step2, $step3, $step4, $step5, $step6, $step7, $step8, $step9, $step10, $step11, $step12, $step13, $step14, $step15,$step16,
        $step17, $step18, $step19, $step20;
    public $record=true, $edit, $create,$ids,$grade_step,$amount;
    use WithPagination,WithoutUrlPagination,LivewireAlert,WithFileUploads;
    public $importFile;
    public $batchId;
    public $importing=false;
    public $importFilePath;
    public $importFinished=false;
    public $iteration;
    public $failures;
    public $salary_structure_name;

    public function import()
    {
        $this->validate([
            'importFile'=>'required|mimes:xlsx',
            'salary_structure_name'=>'required'
        ]);
        $this->importing=true;
        $this->importFilePath=$this->importFile->store('imports');
        $data=[
            'salary'=>$this->salary_structure_name,
            'import'=>$this->importFilePath
        ];
        $batch=Bus::batch([
            new SalaryJob($data),
        ])->dispatch();
        $this->batchId=$batch->id;
        $this->alert('success','successful',[
            'timer'=>9000,
            'timerProgressBar'=>true
        ]);
        $this->importFile=null;
        $this->iteration++;
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $log->action="Imported salary template ";
        $log->save();
    }
    public function getImportBatchProperty()
    {

        $this->importFile='';
        $this->importFile=[];
        if(!$this->batchId)
        {
            return null;
        }
        return  Bus::findBatch($this->batchId);
    }
    public function updateImportProgress()
    {

        $this->importFinished=$this->importBatch->finished();
        if ($this->importFinished){
            Storage::delete($this->importFilePath);
            $this->importing=false;

        }
    }
    public function edit_salary_structure($id){
        $this->ids=$id;
        $this->record=false;
        $this->edit=true;
        $this->create=false;
        $edit_salary_structureObj=SalaryStructureTemplate::find($id);
        $structure_name=\App\Models\SalaryStructure::find($edit_salary_structureObj->salary_structure_id);
//        dd($structure_name->name);
        $this->salary_structure=$structure_name->name;
        $this->grade_level=$edit_salary_structureObj->grade_level;
        $this->no_of_steps=$edit_salary_structureObj->no_of_grade_steps;
        $this->step1=$edit_salary_structureObj->Step1;
        $this->step2=$edit_salary_structureObj->Step2;
        $this->step3=$edit_salary_structureObj->Step3;
        $this->step4=$edit_salary_structureObj->Step4;
        $this->step5=$edit_salary_structureObj->Step5;
        $this->step6=$edit_salary_structureObj->Step6;
        $this->step7=$edit_salary_structureObj->Step7;
        $this->step8=$edit_salary_structureObj->Step8;
        $this->step9=$edit_salary_structureObj->Step9;
        $this->step10=$edit_salary_structureObj->Step10;
        $this->step11=$edit_salary_structureObj->Step11;
        $this->step12=$edit_salary_structureObj->Step12;
        $this->step13=$edit_salary_structureObj->Step13;
        $this->step14=$edit_salary_structureObj->Step14;
        $this->step15=$edit_salary_structureObj->Step15;
        $this->step16=$edit_salary_structureObj->Step16;
        $this->step17=$edit_salary_structureObj->Step17;
        $this->step18=$edit_salary_structureObj->Step18;
        $this->step19=$edit_salary_structureObj->Step19;
        $this->step20=$edit_salary_structureObj->Step20;
    }
    public function update($id)
    {
        $salaryObj=SalaryStructureTemplate::find($id);
        $salaryObj->Step1=$this->step1;
        $salaryObj->Step2=$this->step2;
        $salaryObj->Step3=$this->step3;
        $salaryObj->Step4=$this->step4;
        $salaryObj->Step5=$this->step5;
        $salaryObj->Step6=$this->step6;
        $salaryObj->Step7=$this->step7;
        $salaryObj->Step8=$this->step8;
        $salaryObj->Step9=$this->step9;
        $salaryObj->Step10=$this->step10;
        $salaryObj->Step11=$this->step11;
        $salaryObj->Step12=$this->step12;
        $salaryObj->Step13=$this->step13;
        $salaryObj->Step14=$this->step14;
        $salaryObj->Step15=$this->step15;
        $salaryObj->Step16=$this->step16;
        $salaryObj->Step17=$this->step17;
        $salaryObj->Step18=$this->step18;
        $salaryObj->Step19=$this->step19;
        $salaryObj->Step20=$this->step20;
        $salaryObj->save();
        $this->alert('success','Salary template updated successfully');
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $log->action="Updated salary template ";
        $log->save();
    }
    public function close()
    {
        $this->record=true;
        $this->edit=false;
        $this->create=false;
    }
    public function create_ss()
    {
        $this->create=true;
        $this->edit=false;
        $this->record=false;
    }
    protected $rules=[
        'salary_structure'=>'required',
        'grade_level'=>'required',
        'grade_step'=>'required',
        'amount'=>'required'
    ];
    public function store()
    {
        $this->validate();
        $salaryObj=new SalaryStructureTemplate();
        $salaryObj->salary_structure_id=$this->salary_structure;
        $salaryObj->grade_level=$this->grade_level;
        $salaryObj->no_of_grade_steps=$this->grade_step;
        $salaryObj->Step=$this->amount;
        $salaryObj->save();
        $this->alert('success','New record have been added');
    }
    public function updated($pro)
    {
        $this->validateOnly($pro);
    }

    public function deleteId($id){
        $this->ids=$id;
        $this->alert('warning','Do you want to delete this record?',[
            'showConfirmButton'=>true,
            'onConfirmed'=>'confirmed',
            'showCancelButton'=>true,
            'timer'=>90000,
            'position'=>'center'
        ]);
    }
    public function confirmed()
    {
        $allowance=SalaryStructureTemplate::find($this->ids);
        $allowance->delete();
        $this->alert('success','Record has been deleted successfully');
    }
    public function render()
    {

//        dd($this->filter);
        $salaries=SalaryStructureTemplate::when($this->filter,function ($query){
            return $query->where('salary_structure_id',$this->filter);
        })->paginate(10);
        return view('livewire.forms.salary-template',compact('salaries'))
            ->extends('components.layouts.app');
    }
}
