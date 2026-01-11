<?php

namespace App\Livewire\Forms;

use App\Models\Faq;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Question extends Component
{

    public $record=true,$create,$edit,$ids;
    public $title,$body,$video_file,$faqInfo;
    use WithFileUploads,WithPagination, WithoutUrlPagination,LivewireAlert;
    protected $listeners=['confirmed','dismissed'];
    public $search,$perpage=50,$orderBy='id',$orderAsc='desc';

    public function rules(){
        return [
            'title'=>'required|string|regex:/[a-zA-Z]/',
            'video_file'=>'nullable||max:20480|mimes:mp4,mov,avi,wmv,flv',
            'body'=>$this->video_file ? 'nullable|string' : 'required|string|regex:/[a-zA-Z]/',
        ];
    }
    public function messages()
    {
        return [
            'title.regex' => 'The title must contain at least one letter and not be only numbers.',
            'body.regex' => 'The body must contain at least one letter and not be only numbers.',
            'video_file.max' => 'The video must not be greater than 10MB.',
        ];
    }
    public function create_faq(){
        $this->record=false;
        $this->edit=false;
        $this->create=true;
        $this->reset_field();
    }
    public function close(){
        $this->record=true;
        $this->edit=false;
        $this->create=false;
        $this->reset_field();
    }
    public function edit_record($id){
        $this->record=false;
        $this->edit=true;
        $this->create=false;
        $this->ids=$id;
        $faq=\App\Models\Faq::find($id);

        $this->title=$faq->questions;
        $this->body=$faq->answers;
        $this->faqInfo=$faq;
    }
    public function updated($prop){
        $this->validateOnly($prop);
    }
    public function store()
    {
        $this->validate();

        $helpObj=new Faq();

        $helpObj->questions=$this->title;
        $helpObj->answers=$this->body;
        $helpObj->status=1;
        $helpObj->video_file=$this->video_file?$this->video_file->store('help_videos','public'): '';

        $helpObj->save();
        $this->alert('success','New Faq  has been added');
        $this->reset_field();
    }
    public function reset_field()
    {
        $this->title='';
        $this->status='';
        $this->body='';
        $this->video_file='';
        $this->video_file=null;
    }
    public function update($id)
    {

        $this->validate();
        $helpObj=Faq::find($id);
        $helpObj->questions=$this->title;
        $helpObj->answers=$this->body;
        $helpObj->status=1;

        $helpObj->video_file=$this->video_file?$this->video_file->store('help_videos','public'): '';

        $helpObj->save();
        $this->alert('success','Faq  has has updated');
        $this->close();
    }

    public function deleteId($id){
        $this->ids=$id;
        $this->alert('warning','Do you want to delete this faq?',[
            'showConfirmButton'=>true,
            'onConfirmed'=>'confirmed',
            'showCancelButton'=>true,
            'timer'=>90000,
            'position'=>'center'
        ]);
    }
    public function confirmed()
    {
        $helpObj=Faq::find($this->ids);
        try {
            $helpObj->delete();
            $this->alert('success','Faq has been deleted successfully');

        }catch (\Exception $e){

        }
    }
    public function render()
    {
        $records=Faq::where('questions','like',"%$this->search%")
            ->orderBy($this->orderBy,$this->orderAsc)
            ->paginate($this->perpage);
        return view('livewire.forms.question',compact('records'))->extends('components.layouts.app');
    }
}
