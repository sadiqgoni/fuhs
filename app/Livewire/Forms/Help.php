<?php

namespace App\Livewire\Forms;

use App\Models\Faq;
use App\Models\HelpTopic;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Help extends Component
{
    public $record=true,$create,$edit,$ids;
    use WithFileUploads,WithPagination, WithoutUrlPagination,LivewireAlert;
    protected $listeners=['confirmed','dismissed'];
    public $body,$video_file,$topic,$faq,$status;
    public $help_rec;
    public $search,$perpage=50,$orderBy='id',$orderAsc='asc';
    public $questions;

    public function rules(){
        return [
            'topic'=>'required|string|regex:/[a-zA-Z]/',
            'video_file'=>'nullable||max:20480 |mimes:mp4,mov,avi,wmv,flv',
            'body'=>$this->video_file ? 'nullable|string' : 'required|string|regex:/[a-zA-Z]/',
        ];
    }
    public function messages()
    {
        return [
            'topic.regex' => 'The title must contain at least one letter and not be only numbers.',
            'body.regex' => 'The body must contain at least one letter and not be only numbers.',
            'video_file.max' => 'The video must not be greater than 10MB.',

        ];
    }

    public function create_record(){
        $this->record=false;
        $this->edit=false;
        $this->create=true;
        $this->faq=false;
        $this->reset_field();
    }
    public function create_faq(){
        $this->questions = Faq::all();

        // Pre-fill answers array with existing answers
        foreach ($this->questions as $question) {
            $this->answers[$question->id] = $question->answers;
        }
        $this->record=false;
        $this->edit=false;
        $this->create=false;
        $this->faq=true;
        $this->reset_field();
    }
    public function close(){
        $this->record=true;
        $this->edit=false;
        $this->create=false;
        $this->faq=false;
        $this->reset_field();
    }
    public function edit_record($id){
        $this->record=false;
        $this->edit=true;
        $this->create=false;
        $this->ids=$id;
        $helpTopic=\App\Models\HelpTopic::join('helps','helps.topic_id','help_topics.id')
            ->select('help_topics.*','helps.body','helps.video_file')
            ->where('help_topics.id',$id)->first();

        $this->topic=$helpTopic->topic;
        $this->body=$helpTopic->body;
        $this->topic=$helpTopic->topic;
        $this->help_rec=$helpTopic;
    }
    public function updated($prop){
        $this->validateOnly($prop);
    }
    public function store()
    {
        $this->validate();
        $helpTopic=HelpTopic::create([
            'topic'=>$this->topic
        ]);
        $helpObj=new \App\Models\Help();
        $helpObj->topic_id=$helpTopic->id;
        $helpObj->body=$this->body;
        $helpObj->video_file=$this->video_file?$this->video_file->store('help_videos','public'): '';
//        $helpObj->status=$this->status;

        $helpObj->save();
        $this->alert('success','New help topic has been added');
        $this->reset_field();
    }
    public function reset_field()
    {
        $this->topic='';
        $this->status='';
        $this->body='';
        $this->video_file='';
        $this->video_file=null;
    }
    public function update($id)
    {

        $this->validate();
        $helpObj=\App\Models\HelpTopic::find($id);
        $help=\App\Models\Help::where('topic_id',$id)->first();
        $helpObj->topic=$this->topic;
        $helpObj->save();
        $help->body=$this->body;
        $help->video_file=$this->video_file?$this->video_file->store('help_videos','public'): '';
//        $helpObj->status=$this->status;

        $help->save();
        $this->alert('success','Help topic has been updated');
        $this->close();
    }

    public function deleteId($id){
        $this->ids=$id;
        $this->alert('warning','Do you want to delete this topic?',[
            'showConfirmButton'=>true,
            'onConfirmed'=>'confirmed',
            'showCancelButton'=>true,
            'timer'=>90000,
            'position'=>'center'
        ]);
    }
    public function confirmed()
    {
        $helpObj=\App\Models\HelpTopic::find($this->ids);
        $help=\App\Models\Help::where('topic_id',$this->ids)->first();
        try {
            $help->delete();
            $helpObj->delete();
            $this->alert('success','Help topic has been deleted');

        }catch (\Exception $e){

        }
    }

    public $answers = [];
    public function reply($value, $key)
    {
        $question = Faq::find($key);
        if ($question) {
            $question->answers = $value;
            $question->save();
        }
        $this->alert('success','successful');
    }
    public function show($id)
    {
        $faqs=Faq::find($id);
        if ($faqs->status==1){
            $status=null;
        }else{
            $status=1;
        }
        $faqs->status=$status;
        $faqs->save();
        $this->alert('success','successful');
    }
    public function mount()
    {
        $this->questions = Faq::all();

        // Pre-fill answers array with existing answers
        foreach ($this->questions as $question) {
            $this->answers[$question->id] = $question->answers;
        }
    }
    public function updatedAnswers($value, $key)
    {
        // Save the answer in the database when changed
        $question = Faq::find($key);
        if ($question) {
            $question->answers = $value;
            $question->save();
            $this->alert('success','Replied Successfully');

        }
    }
    public function render()
    {

        $records=\App\Models\HelpTopic::join('helps','helps.topic_id','help_topics.id')
            ->select('help_topics.*','helps.body','helps.video_file')
            ->where('help_topics.topic','like',"%$this->search%")
            ->orderBy("help_topics.$this->orderBy",$this->orderAsc)
            ->paginate($this->perpage);
        $faqs=Faq::where('questions','like',"%$this->search%")
            ->orderBy("questions",$this->orderAsc)
            ->paginate($this->perpage);

        return view('livewire.forms.help',compact('records','faqs'))->extends('components.layouts.app');
    }
}
