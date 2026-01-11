<?php

namespace App\Livewire\Pages;

use App\Models\ActivityLog;
use Illuminate\Support\Carbon;
use Livewire\Component;

class Log extends Component
{
    public $date_from,$date_to,$perpage=50;
    public function render()
    {
        $logs=ActivityLog::when($this->date_from,function ($query) {
            $date_from = Carbon::parse($this->date_from)->format('Y-m-d');
            $date_to = Carbon::parse($this->date_to)->format('Y-m-d');
            return $query->whereBetween('created_at', [$date_from, $date_to]);
        } )
            ->orderBy('id','desc')->paginate($this->perpage);
        return view('livewire.pages.log',compact('logs'))->extends('components.layouts.app');
    }
}
