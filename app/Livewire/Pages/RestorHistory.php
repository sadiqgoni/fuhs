<?php

namespace App\Livewire\Pages;

use App\Models\RestoreHistory;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class RestorHistory extends Component
{
    public $restore_type,$perpage=50;
    use WithPagination,WithoutUrlPagination;
    public $date_from,$date_to;
    public function render()
    {

        $restores=RestoreHistory::when($this->restore_type,function ($query){
            return $query->where('restore_type',$this->restore_type);
        })->when($this->date_from,function ($query){
            $date_from=Carbon::parse($this->date_from)->format('Y-m-d');
            $date_to=Carbon::parse($this->date_to)->format('Y-m-d');
            return $query ->whereBetween('created_at',[$date_from,$date_to]);
        })->paginate($this->perpage);
        return view('livewire.pages.restor-history',compact('restores'))->extends('components.layouts.app');
    }
}
