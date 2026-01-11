<?php

namespace App\Livewire\Forms;

use App\Exports\AllowanceExport;
use App\Exports\AllowExport;
use App\Exports\BankExport;
use App\Exports\DedExport;
use App\Exports\DeductionTemplateExport;
use App\Exports\DepartmentExport;
use App\Exports\EmployeeProfileExport;
use App\Exports\EmpTypeExport;
use App\Exports\PFAsExport;
use App\Exports\RankExport;
use App\Exports\SalaryHistoryExport;
use App\Exports\DeductionExport;
use App\Exports\SalaryStructureExport;
use App\Exports\SalaryTemplateExport;
use App\Exports\SalaryUpdateExport;
use App\Exports\UnitExport;
use App\Models\ActivityLog;
use App\Models\BackupHistory;
use App\Models\LoanDeductionCountdownHistory;
use App\Models\SalaryHistory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class Backup extends Component
{
    public $backup_type,
            $data_only,
            $delete_record,
            $leave_data,
            $month_year_from,
            $month_year_to,
            $other_backup_type,$backup_location;
    use LivewireAlert;
    protected $rules=[
        'month_year_from'=>'required',
        'month_year_to'=>'required',


    ];
    public function store()
    {
        $this->validate([
            'backup_type'=>'required',
            'backup_location'=>'required',
        ]);
        if ($this->backup_type==1 || $this->backup_type==2 )
        {
            $this->validate();
        }
        $from=Carbon::parse($this->month_year_from)->format('Y-m-d');
        $to=Carbon::parse($this->month_year_to)->format('Y-m-d');
        $backup=new BackupHistory();
        $backup->backup_by=Auth::id();
        $backup->date_from=$from;
        $backup->date_to=$to;
        $backup->backup_loc=$this->backup_location;

       if ($this->backup_type==1)
       {
           $histories=SalaryHistory::whereBetween('date_month',[$from,$to])->get();
           if ($histories->count()>0){
               $filename="payroll_backup_file".now()->format('ym').".xlsx";
               $backup->backup_type=$this->backup_type;
               $backup->backup_name=$filename;
               $backup->save();
               if ($this->backup_location==1){
                   return Excel::store( new SalaryHistoryExport($histories), "$filename", 'export' );
               }else{
                   return Excel::download(new SalaryHistoryExport($histories), "$filename");
               }
           }else{
               $this->alert('warning','You cannot backup an empty record');
           }


       }
       elseif ($this->backup_type==2)
        {
            $deductions=LoanDeductionCountdownHistory::whereBetween('start_month',[$from,$to])->get();
            if ($deductions->count()>0){
                $filename="loan_deduction_history_backup_file".now()->format('ym').".xlsx";
                $backup->backup_type=$this->backup_type;
                $backup->backup_name=$filename;
                $backup->save();
                if ($this->backup_location==1){
                    return Excel::store( new DeductionExport($deductions), "$filename", 'export' );
                }else{
                    return Excel::download(new DeductionExport($deductions), "$filename");
                }
            }else{
                $this->alert('warning','You cannot backup an empty record');

            }


        }
       else{
           if($this->other_backup_type==1){
               $employees=\App\Models\EmployeeProfile::get();
               if (isset($employees)){
                   $filename="employee_profile_backup_file".now()->format('ym').".xlsx";
                   $backup->backup_type=3;
                   $backup->backup_name=$filename;
                   $backup->save();
                   if ($this->backup_location==1){
                       return Excel::store( new EmployeeProfileExport($employees), "$filename", 'export' );

                   }else{
                       return Excel::download(new EmployeeProfileExport($employees),"$filename");
                   }
               }else{
                   $this->alert('warning','You cannot backup an empty record');
               }

           }
           elseif($this->other_backup_type==2){
               $salaries=\App\Models\SalaryUpdate::all();
               if (isset($salaries)){
                   $filename="salary_update_backup_file".now()->format('ym').".xlsx";
                   $backup->backup_type=4;
                   $backup->backup_name=$filename;
                   $backup->save();
                   if ($this->backup_location==1){
                       return Excel::store( new SalaryUpdateExport($salaries), "$filename", 'export' );

                   }else{
                       return Excel::download(new SalaryUpdateExport($salaries),"$filename");
                   }
               }else{
                   $this->alert('warning','You cannot backup an empty record');

               }

           }
           elseif($this->other_backup_type==3){
               $salaries=\App\Models\SalaryStructureTemplate::all();
               if (isset($salaries)){
                   $filename="salary_template_backup_file".now()->format('ym').".xlsx";
                   $backup->backup_type=5;
                   $backup->backup_name=$filename;
                   $backup->save();
                   if ($this->backup_location==1){
                       return Excel::store( new SalaryTemplateExport($salaries), "$filename", 'export' );

                   }else{
                       return Excel::download(new SalaryTemplateExport($salaries),"$filename");
                   }
               }else{
                   $this->alert('warning','You cannot backup an empty record');

               }

           }
           elseif($this->other_backup_type==4){
               $allowances=\App\Models\SalaryAllowanceTemplate::all();
               if (isset($allowances)){
                   $filename="allowance_template_backup_file".now()->format('ym').".xlsx";
                   $backup->backup_type=6;
                   $backup->backup_name=$filename;
                   $backup->save();
                   if ($this->backup_location==1){
                       return Excel::store( new AllowanceExport($allowances), "$filename", 'export' );

                   }else{
                       return Excel::download(new AllowanceExport($allowances),"$filename");
                   }
               }else{
                   $this->alert('warning','You cannot backup an empty record');

               }

           }
           elseif($this->other_backup_type==5){
               $deductions=\App\Models\SalaryDeductionTemplate::all();
               if (isset($deductions)){
                   $filename="deduction_template_backup_file".now()->format('ym').".xlsx";
                   $backup->backup_type=7;
                   $backup->backup_name=$filename;
                   $backup->save();
                   if ($this->backup_location==1){
                       return Excel::store( new DeductionTemplateExport($deductions), "$filename", 'export' );

                   }else{
                       return Excel::download(new DeductionTemplateExport($deductions),"$filename");
                   }
               }else{
                   $this->alert('warning','You cannot backup an empty record');
               }

           }
           elseif($this->other_backup_type==6){
               $banks=\App\Models\Bank::all();
               if (isset($banks)){
                   $filename="banks_backup_file".now()->format('ym').".xlsx";
                   $backup->backup_type=8;
                   $backup->backup_name=$filename;
                   $backup->save();
                   if ($this->backup_location==1){
                       return Excel::store( new BankExport($banks), "$filename", 'export' );

                   }else{
                       return Excel::download(new BankExport($banks),"$filename");
                   }
               }else{
                   $this->alert('warning','You cannot backup an empty record');
               }

           }
           elseif($this->other_backup_type==7){
               $pfa=\App\Models\PFA::all();
               if (isset($pfa)){
                   $filename="PFA_backup_file".now()->format('ym').".xlsx";
                   $backup->backup_type=9;
                   $backup->backup_name=$filename;
                   $backup->save();
                   if ($this->backup_location==1){
                       return Excel::store( new PFAsExport($pfa), "$filename", 'export' );

                   }else{
                       return Excel::download(new PFAsExport($pfa),"$filename");
                   }
               }else{
                   $this->alert('warning','You cannot backup an empty record');
               }

           }
           elseif($this->other_backup_type==8){
               $units=\App\Models\Unit::all();
               if (isset($units)){
                   $filename="unit_backup_file".now()->format('ym').".xlsx";
                   $backup->backup_type=10;
                   $backup->backup_name=$filename;
                   $backup->save();
                   if ($this->backup_location==1){
                       return Excel::store( new UnitExport($units), "$filename", 'export' );

                   }else{
                       return Excel::download(new UnitExport($units),"$filename");
                   }
               }else{
                   $this->alert('warning','You cannot backup an empty record');
               }

           }
           elseif($this->other_backup_type==9){
               $department=\App\Models\Department::all();
               if (isset($department)){
                   $filename="department_backup_file".now()->format('ym').".xlsx";
                   $backup->backup_type=11;
                   $backup->backup_name=$filename;
                   $backup->save();
                   if ($this->backup_location==1){
                       return Excel::store( new DepartmentExport($department), "$filename", 'export' );

                   }else{
                       return Excel::download(new DepartmentExport($department),"$filename");
                   }
               }else{
                   $this->alert('warning','You cannot backup an empty record');
               }

           }
           elseif($this->other_backup_type==10){
               $ranks=\App\Models\Rank::all();
               if (isset($ranks)){
                   $filename="ranks_backup_file".now()->format('ym').".xlsx";
                   $backup->backup_type=12;
                   $backup->backup_name=$filename;
                   $backup->save();
                   if ($this->backup_location==1){
                       return Excel::store( new RankExport($ranks), "$filename", 'export' );

                   }else{
                       return Excel::download(new RankExport($ranks),"$filename");
                   }
               }else{
                   $this->alert('warning','You cannot backup an empty record');
               }

           }
           elseif($this->other_backup_type==11){
               $emptype=\App\Models\EmploymentType::all();
               if (isset($emptype)){
                   $filename="employment_type_backup_file".now()->format('ym').".xlsx";
                   $backup->backup_type=13;
                   $backup->backup_name=$filename;
                   $backup->save();
                   if ($this->backup_location==1){
                       return Excel::store( new EmpTypeExport($emptype), "$filename", 'export' );

                   }else{
                       return Excel::download(new EmpTypeExport($emptype),"$filename");
                   }
               }else{
                   $this->alert('warning','You cannot backup an empty record');
               }

           }
           elseif($this->other_backup_type==12){
               $salary_structure=\App\Models\SalaryStructure::all();
               if (isset($salary_structure)){
                   $filename="salary_structure_backup_file".now()->format('ym').".xlsx";
                   $backup->backup_type=14;
                   $backup->backup_name=$filename;
                   $backup->save();
                   if ($this->backup_location==1){
                       return Excel::store( new SalaryStructureExport($salary_structure), "$filename", 'export' );

                   }else{
                       return Excel::download(new SalaryStructureExport($salary_structure),"$filename");
                   }
               }else{
                   $this->alert('warning','You cannot backup an empty record');
               }

           }
           elseif($this->other_backup_type==13){
               $allowance=\App\Models\Allowance::all();
               if (isset($allowance)){
                   $filename="allowance_backup_file".now()->format('ym').".xlsx";
                   $backup->backup_type=15;
                   $backup->backup_name=$filename;
                   $backup->save();
                   if ($this->backup_location==1){
                       return Excel::store( new AllowExport($allowance), "$filename", 'export' );

                   }else{
                       return Excel::download(new AllowExport($allowance),"$filename");
                   }
               }else{
                   $this->alert('warning','You cannot backup an empty record');
               }

           }
           elseif($this->other_backup_type==14){
               $deductions=\App\Models\Deduction::all();
               if (isset($deductions)){
                   $filename="deduction_backup_file".now()->format('ym').".xlsx";
                   $backup->backup_type=16;
                   $backup->backup_name=$filename;
                   $backup->save();
                   if ($this->backup_location==1){
                       return Excel::store( new DedExport($deductions), "$filename", 'export' );

                   }else{
                       return Excel::download(new DedExport($deductions),"$filename");
                   }
               }else{
                   $this->alert('warning','You cannot backup an empty record');
               }

           }

       }
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        if ($this->other_backup_type==1){
            $name="employee";
        }elseif ($this->other_backup_type==2){
            $name="salary update";

        }elseif ($this->other_backup_type==3){
            $name="salary template";

        }elseif ($this->other_backup_type==4){
            $name="allowance template";

        }elseif ($this->other_backup_type==5){
            $name="deduction template";

        }elseif($this->backup_type==1){
            $name="Payroll ";

        }
        elseif($this->backup_type==2){
            $name="Loan deduction countdown ";

        }
        elseif ($this->other_backup_type==6){
            $name="Banks Data";

        }
        elseif ($this->other_backup_type==7){
            $name="PFA data";
        }
        elseif ($this->other_backup_type==8){
            $name="Units Data";

        }
        elseif ($this->other_backup_type==9){
            $name="Department Data";

        }
        elseif ($this->other_backup_type==10){
            $name="Ranks Data";

        }
        elseif ($this->other_backup_type==11){
            $name="Employment Type Data";

        }
        elseif ($this->other_backup_type==12){
            $name="Salary Structure Data";

        }
        elseif ($this->other_backup_type==13){
            $name="Allowance Data";

        }
        elseif ($this->other_backup_type==14){
            $name="Deduction Data";

        }
        $log->action="Backup $name data";
        $log->save();
        $this->alert('success','Exported successfully');

    }

    public function render()
    {
        return view('livewire.forms.backup')->extends('components.layouts.app');
    }
}
