<?php

namespace App\Livewire\Pages;

use App\Exports\AnnualIncrement;
use App\Exports\LoadDeduction;
use App\Models\ActivityLog;
use App\Models\AnnualSalaryIncrement;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class AnnualIncrementHistory extends Component
{
    public $perpage=25,$month,$status;

    use WithPagination;
    public $date_from,$date_to;
    use WithoutUrlPagination,LivewireAlert;

    public function mount()
    {
        $this->date_from=Carbon::now()->format('m/d/Y');
        $this->date_to=Carbon::now()->format('m/d/Y');
    }
    public function export()
    {
        $histories=AnnualSalaryIncrement::when($this->status,function ($query){
            return $query->where('status',$this->status);
        })
            ->when($this->date_from,function ($query){
                $date_from = Carbon::parse($this->date_from)->format('Y-m-d');
                $date_to = Carbon::parse($this->date_to)->format('Y-m-d');
                return $query ->whereBetween('month_year',[$date_from,$date_to]);
            })->get();
        if ($histories->count()>0) {

            $this->alert('success', 'Exported successfully');
            $user = Auth::user();
            $log = new ActivityLog();
            $log->user_id = $user->id;
            $log->action = "Exported $this->month annual increment record";
            $log->save();
            return Excel::download(new AnnualIncrement($histories), 'annual_increments.xlsx');
        }
        else{
            $this->alert('warning',"No record found",['timer'=>9200]);
        }
    }
    public function render()
    {
        $histories=AnnualSalaryIncrement::when($this->status,function ($query){
            return $query->where('status',$this->status);
        })

            ->when($this->date_from,function ($query){
                $date_from = Carbon::parse($this->date_from)->format('Y-m-d');
                $date_to = Carbon::parse($this->date_to)->format('Y-m-d');
                return $query ->whereBetween('month_year',[$date_from,$date_to]);
            })

            ->paginate($this->perpage);
//        dd($histories);
        return view('livewire.pages.annual-increment-history',compact('histories'))->extends('components.layouts.app');
    }
}
