<?php

namespace App\Livewire\Pages;

use App\Models\Faq;
use App\Models\Help;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class HelpView extends Component
{
    public $content; // Full content string
    public $pages = [];
    public $wordsPerPage = 80;
    public $currentPage = 0;
    public $selectedTopicId;
    public $title = '';
    public $details = '';
    public $email = '';
    public $similar = null;
    public $success = false;
    public $ids;
    use LivewireAlert,WithoutUrlPagination,WithPagination;

    protected $rules = [
        'title' => 'required|string|min:5|max:255',
//        'details' => 'required|string|min:10',
        'email' => 'nullable|email',
    ];

    public function updatedTitle()
    {
        $this->checkSimilarity();
    }

    public function checkSimilarity()
    {
        $input = strtolower(trim($this->title));
        $this->similar = null;

        foreach (Faq::all() as $question) {
            similar_text($input, strtolower($question->questions), $percent);
            if ($percent > 50) {
                $this->similar = $question;
                break;
            }
        }
        $input = strtolower(trim($this->title));
        $this->similar = null;

        foreach (Faq::whereNotNull('answers')->get() as $question) {
            similar_text($input, strtolower($question->title), $percent);
            if ($percent > 85) {
                $this->similar = $question;
                break;
            }
        }
    }
//    public function checkSimilarity()
//    {
//        $input = strtolower(trim($this->title));
//        $this->similar = null;
//
//        foreach (Faq::whereNotNull('answers')->get() as $question) {
//            similar_text($input, strtolower($question->title), $percent);
//            if ($percent > 50) {
//                $this->similar = $question;
//                break;
//            }
//        }
//    }
    public function submit()
    {
        $this->validate();

//        if ($this->similar) {
//            // Link to existing or update count, etc.
//            $question = Faq::create([
//                'questions' => $this->title,
////                'details' => $this->details,
//                'email' => $this->email,
//                'group_id' => $this->similar->id,
//            ]);
//        } else {
//            $question = Faq::create([
//                'questions' => $this->title,
////                'details' => $this->details,
//                'email' => $this->email,
//            ]);
//        }


        // Using exact match
        // $sentence = Sentence::createIfNotExists($request->content);

        // Using similarity check
        $sentence = Faq::createIfNotSimilar($this->title);

        if ($sentence->wasRecentlyCreated) {
            return response()->json([
                $this->alert('success','your question has been submitted'),

            'data' => $sentence
            ], 201);
        }

//        return response()->json([
//            'message' => 'Similar sentence already exists',
//            'data' => $sentence
//        ], 200);
        $this->alert('success','your question has been submitted');


        $this->reset(['title', 'details', 'email', 'similar']);
    }

    public function updatedSelectedTopicId($id)
    {
        $this->ids=$id;
        $pageContent = Help::where('topic_id', $id)->orderBy('id')->pluck('body')->implode(' ');
        $this->content = strip_tags($pageContent); // Strip HTML if needed
        $this->pages = $this->paginateWords($this->content, $this->wordsPerPage);
        $this->currentPage = 0;
    }

    public function paginateWords($text, $wordsPerPage)
    {
        $words = explode(' ', $text);
        return array_chunk($words, $wordsPerPage);
    }

    public function getCurrentPageTextProperty()
    {
        return implode(' ', $this->pages[$this->currentPage] ?? []);
    }

    public function nextPage()
    {
        if ($this->currentPage < count($this->pages) - 1) {
            $this->currentPage++;
        }
    }

    public function prevPage()
    {
        if ($this->currentPage > 0) {
            $this->currentPage--;
        }
    }
    public function mount()
    {
        $pageContent = Help::where('topic_id', 2)->orderBy('id')->pluck('body')->implode(' ');
        $this->content = strip_tags($pageContent); // Strip HTML if needed
        $this->pages = $this->paginateWords($this->content, $this->wordsPerPage);
        $this->currentPage = 0;
    }
    public function render()
    {
        $faqs=Faq::where('questions','like',"%$this->title%")->whereNotNull('answers')->whereNotNull('status')->paginate(3);
        if (Auth::check()){
            return view('livewire.pages.help-view',compact('faqs'));

        }else{
            return view('livewire.pages.help-view',compact('faqs'))->extends('components.layouts.app')->section('help');

        }
    }
}
