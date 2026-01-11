<?php

namespace App\Livewire\Pages;

use App\Models\ReportRepository;
use Livewire\Component;

class ReportRepo extends Component
{
    public $date,$order_by='id',$orderAsc='asc',$perpage=10;
    public function render()
    {
        $records=ReportRepository::when($this->date,function ($query){
            return $query->where('date',$this->date);
        })
            ->select('date')
            ->groupBy('date')
            ->orderBy($this->order_by,$this->orderAsc)
            ->paginate($this->perpage);
        return view('livewire.pages.report-repo',compact('records'))->extends('components.layouts.app');
    }
}
