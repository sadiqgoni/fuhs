<?php

namespace App\Livewire\Pages;

use App\Models\RestoreHistory;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class BackupHistory extends Component
{
    public $backup_type,$perpage=50;
    public $date_from,$date_to;
    use WithPagination,WithoutUrlPagination;
    public function render()
    {
        $backups=\App\Models\BackupHistory::when($this->backup_type,function ($query){
            return $query->where('backup_type',$this->backup_type);
        })->when($this->date_from,function ($query){
            $date_from=Carbon::parse($this->date_from)->format('Y-m-d');
            $date_to=Carbon::parse($this->date_to)->format('Y-m-d');
            return $query ->whereBetween('created_at',[$date_from,$date_to]);
        })->paginate($this->perpage);
        return view('livewire.pages.backup-history',compact('backups'))->extends('components.layouts.app');
    }
}
