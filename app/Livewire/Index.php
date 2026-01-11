<?php

namespace App\Livewire;

use App\Models\LoanDeductionCountdown;
use Illuminate\Support\Carbon;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        if (LoanDeductionCountdown::where('deduction_id',$this->deduction_name)
            ->whereDate('last_pay_month_year',Carbon::parse($this->salary_month)
                ->format('Y-m-d'))
            ->exists())
        {

            $deductions=LoanDeductionCountdown::where('deduction_id',$this->deduction_name)
                ->whereDate('last_pay_month_year', '!=',Carbon::parse($this->salary_month)->format('Y-m-d'))
                ->get();

            foreach ($deductions as $deduction)
            {
                if ($deduction->ded_countdown - 1 >= 0){
                    $deduction->ded_countdown=$deduction->ded_countdown - 1;
                    $deduction->last_pay_month_year=Carbon::parse($this->salary_month)->format('Y-m-d');
                    if ($deduction->ded_countdown - 1 == 0)
                    {
                        $deduction->status=1;
                    }
                    $deduction->save();
                }
            }

        }else{
            dd('na biyu');

            $deductions=LoanDeductionCountdown::where('deduction_id',$this->deduction_name)->get();

            foreach ($deductions as $deduction)
            {
                if (LoanDeductionCountdown::where('deduction_id',$deduction->deduction_id)->whereDate('last_pay_month_year','>=',Carbon::parse($this->salary_month)->format('Y-m-d'))->exists()){
                    continue;
                }else{
                    if ($deduction->ded_countdown - 1 >= 0){
                        $deduction->last_pay_month_year=Carbon::parse($this->salary_month)->format('Y-m-d');
                        $deduction->ded_countdown=$deduction->ded_countdown - 1;
                        if ($deduction->ded_countdown - 1 == 0)
                        {
                            $deduction->status=1;
                        }
                        $deduction->save();
                        $this->alert('success','Salary month have been updated');
                    }

                }
            }

        }
        return view('livewire.index');
    }
}
