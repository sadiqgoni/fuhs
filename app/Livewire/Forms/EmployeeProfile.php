<?php

namespace App\Livewire\Forms;

use App\DeductionCalculation;
use App\Imports\EmployeesImport;
use App\Jobs\EmployeeJob;
use App\Models\ActivityLog;
use App\Models\Bank;
use App\Models\Deduction;
use App\Models\Department;
use App\Models\EmploymentType;
use App\Models\LocalGovt;
use App\Models\PFA;
use App\Models\Rank;
use App\Models\Relationship;
use App\Models\Religion;
use App\Models\SalaryAllowanceTemplate;
use App\Models\SalaryDeductionTemplate;
use App\Models\SalaryStructure;
use App\Models\SalaryStructureTemplate;
use App\Models\SalaryUpdate;
use App\Models\StaffCategory;
use App\Models\State;
use App\Models\UnionDeduction;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class EmployeeProfile extends Component
{
    use WithFileUploads;
    use WithPagination;
    use LivewireAlert;
    public $importFile;
    public $batchId;
    public $importing=false;
    public $importFilePath;
    public $importFinished=false;
    public $iteration;
    public $failures;
    public $full_name, $staff_number, $staff_category, $payroll_number, $employment_type, $status, $date_of_retirement,$date_of_first_appointment, $date_of_last_promotion,
        $post_held, $salary_structure, $grade_level, $step, $account_number, $bank_code, $bank_name, $gender, $tribe, $religion,
        $phone_number, $whatsapp_number, $email, $nationality, $state_of_origin, $local_government, $date_of_birth, $marital_status,
        $name_of_next_of_kin, $next_of_kin_phone_number, $relationship,$contract_termination_date, $address, $profile_picture,$pension_pin,$pfa_name,$department,$rank,$unit;
    public $staff_union,$bvn,$tax_id;
    public $create,$edit,$record=true,$disabled="disabled readonly",$view,$ids,$employeeInfo;
    public $lgas,$states,$departments,$employeeId,$depts,$search_employee;
    public $search,$filter_type,$filter_unit,$filter_dept,$orderBy="id",$orderAsc=true,$perpage=14;
    public  $tab1=true,$tab2,$tab3,$tab4,$show_contract=false;
    public $steps = 1;
    protected $listeners=['delete', 'cancelled'];

    public $form = [
        'full_name'=>'',
        'staff_number'=>'',
        'payroll_number'=>'',
        'status'=>'',
        'name_of_next_of_kin'=>'',
        'next_of_kin_phone_number'=>'',
        'relationship'=>'',
        'address'=>'',
        'profile_picture'=>'',
        'salary_structure'=>'',
        'grade_level'=>'',
        'step'=>'',
        'account_number'=>'',
        'bank_code'=>'',
        'bank_name'=>'',
        'pension_pin'=>'',
        'pfa_name'=>'',
        'bvn'=>'',
        'tax_id'=>'',
        'employment_type'=>'',
        'unit'=>'',
        'rank'=>'',
        'department'=>'',
        'date_of_retirement'=>'',
        'date_of_first_appointment'=>'',
        'date_of_last_promotion'=>'',
        'post_held'=>'',
        'staff_category'=>'',
        'staff_union'=>'',
        'contract_termination_date'=>'',
        'gender'=>'',
        'tribe'=>'',
        'religion'=>'',
        'phone_number'=>'',
        'whatsapp_number'=>'',
        'email'=>'',
        'nationality'=>'',
        'state_of_origin'=>'',
        'local_government'=>'',
        'date_of_birth'=>'',
        'marital_status'=>'',
    ];

    protected function rules()
    {

        return [
            'full_name'=>'required',
            'staff_number'=>'required|unique:employee_profiles,staff_number,'.$this->ids,
            'payroll_number'=>['required', Rule::unique('employee_profiles')->ignore($this->ids)],
            'status'=>'required',
            'name_of_next_of_kin'=>'nullable',
            'next_of_kin_phone_number'=>'nullable|digits:11',
            'relationship'=>'nullable',
            'address'=>'nullable',
            'profile_picture'=>'nullable|mimes:jpg,png,jpeg|max:1024',

            'salary_structure'=>'required',
            'grade_level'=>'required',
            'step'=>'required',
            'account_number'=>'required|digits:10|unique:employee_profiles,account_number,'.$this->ids,
            'bank_code'=>'required|numeric',
            'bank_name'=>'nullable',
            'pension_pin'=>'required|alpha_num',
            'pfa_name'=>'required',
            'bvn'=>'nullable|digits:10',
            'tax_id'=>'nullable',

            'employment_type'=>'required',
            'unit'=>'required',
            'rank'=>'nullable',
            'department'=>'required',
            'date_of_retirement'=>'nullable|date',
            'date_of_first_appointment'=>'nullable',
            'date_of_last_promotion'=>'nullable',
            'post_held'=>'nullable',
            'staff_category'=>'nullable',
            'staff_union'=>'required',
            'contract_termination_date'=>'nullable',


            'gender'=>'nullable',
            'tribe'=>'nullable',
            'religion'=>'nullable',
            'phone_number'=>'nullable|digits:11|unique:employee_profiles,phone_number,'.$this->ids,
            'whatsapp_number'=>'nullable|digits:11|',
            'email'=>['nullable', 'email', Rule::unique('employee_profiles')->ignore($this->ids)],
            'nationality'=>'nullable',
            'state_of_origin'=>'nullable',
            'local_government'=>'nullable',
            'date_of_birth'=>'required',
            'marital_status'=>'nullable',
        ];

    }
    protected $messages = [
        'payroll_number.unique' => 'the chosen payroll number exists.',
        'staff_number.unique' => 'the chosen staff number exists.',
        'email.unique' => 'the chosen email exists.',
        'phone_number.unique' => 'the chosen phone  number exists.',
        'account_number.unique' => 'the chosen Account  number exists.',
        'bvn.unique' => 'the chosen BVN  number exists.',

    ];


    public function updated($property)
    {
        $this->validateOnly($property);
    }

    public function nextStep()
    {
        $this->validateStep();

        $this->steps++;
    }

    public function prevStep()
    {
        $this->steps--;
    }


    public function validateStep()
    {

        $stepRules = match($this->steps) {
            1 => [
                'gender'=>'nullable',
                'tribe'=>'nullable',
                'religion'=>'nullable',
                'phone_number'=>'nullable|digits:11|unique:employee_profiles,phone_number,'.$this->ids,
                'whatsapp_number'=>'nullable|digits:11|',
                'email'=>['nullable', 'email', Rule::unique('employee_profiles')->ignore($this->ids)],
                'nationality'=>'nullable',
                'state_of_origin'=>'nullable',
                'local_government'=>'nullable',
                'date_of_birth'=>'required',
                'marital_status'=>'nullable',
            ],
            2 => [
                'employment_type'=>'required',
                'unit'=>'required',
                'rank'=>'nullable',
                'department'=>'required',
                'date_of_retirement'=>'nullable|date',
                'date_of_first_appointment'=>'nullable',
                'date_of_last_promotion'=>'nullable',
                'post_held'=>'nullable',
                'staff_category'=>'nullable',
                'staff_union'=>'required',
                'contract_termination_date'=>'nullable',
            ],
            3 => [
                'salary_structure'=>'required',
                'grade_level'=>'required',
                'step'=>'required',
                'account_number'=>'required|digits:10|unique:employee_profiles,account_number,'.$this->ids,
                'bank_code'=>'required',
                'bank_name'=>'nullable',
                'pension_pin'=>'required|alpha_num',
                'pfa_name'=>'required',
                'bvn'=>'nullable|digits:10',
                'tax_id'=>'nullable',
            ],
            4=>[
                'name_of_next_of_kin'=>'nullable',
                'next_of_kin_phone_number'=>'nullable|digits:11',
                'relationship'=>'nullable',
                'address'=>'nullable',
            ],
            default => [],
        };

        try {
            $this->validate($stepRules);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $firstErrorField = array_key_first($e->validator->errors()->getMessages());
            $this->step = $this->getStepForField($firstErrorField);
            throw $e;
        }
    }

    public function getStepForField($field)
    {
        return match($field) {
            'gender',
            'tribe',
            'religion',
            'phone_number',
            'whatsapp_number',
            'email',
            'nationality',
            'state_of_origin',
            'local_government',
            'date_of_birth',
            'marital_status' => 1,

            'salary_structure',
            'grade_level',
            'step',
            'account_number',
            'bank_code',
            'bank_name',
            'pension_pin',
            'pfa_name',
            'bvn',
            'tax_id' => 3,

            'employment_type',
            'unit',
            'rank',
            'department',
            'date_of_retirement',
            'date_of_first_appointment',
            'date_of_last_promotion',
            'post_held',
            'staff_category',
            'staff_union',
            'contract_termination_date' => 2,

            'name_of_next_of_kin',
            'next_of_kin_phone_number',
            'relationship',
            'address' => 4,
            default => 1,
        };
    }

    public function submit()
    {

        $this->validate();
//        $this->staff_number=str_replace('-','',$this->staff_number);
//        $this->payroll_number=str_replace('-','',$this->payroll_number);
        if ($this->employment_type==1 || $this->employment_type==3){
            $ct=$this->date_of_first_appointment? Carbon::parse($this->date_of_first_appointment)->addYear() : null;
        }else{
            $ct=null;
        }
        DB::beginTransaction();
        try {
        $employee =  \App\Models\EmployeeProfile::create([
            'full_name'=>$this->full_name,
        'rank'=>$this->rank,
        'unit'=>$this->unit,
        'staff_number'=>$this->staff_number,
        'payroll_number'=>$this->payroll_number,
        'employment_type'=>$this->employment_type,
        'staff_category'=>$this->staff_category,
        'status'=>$this->status,
        'date_of_first_appointment'=>$this->date_of_first_appointment??null,
        'date_of_last_appointment'=>$this->date_of_last_promotion??null,
        'staff_union'=>$this->staff_union,
            'contract_termination_date' => $ct,
        'date_of_retirement'=>Carbon::parse($this->date_of_birth)->addYears(65),
        'post_held'=>$this->post_held,
        'salary_structure'=>$this->salary_structure,
        'grade_level'=>$this->grade_level,
        'step'=>$this->step,
        'department'=>$this->department,
        'account_number'=>$this->account_number,
        'bank_code'=>$this->bank_code,
        'bvn'=>$this->bvn,
        'tax_id'=>$this->tax_id,


        'bank_name'=>$this->bank_name,
        'gender'=>$this->gender,
        'tribe'=>$this->tribe,
        'religion'=>$this->religion,
        'phone_number'=>$this->phone_number,
        'whatsapp_number'=>$this->whatsapp_number,
        'email'=>$this->email,
        'nationality'=>$this->nationality,
        'state_of_origin'=>$this->state_of_origin,
        'local_government'=>$this->local_government,
        'date_of_birth'=>$this->date_of_birth??null,
        'marital_status'=>$this->marital_status,
        'name_of_next_of_kin'=>$this->name_of_next_of_kin,
        'next_of_kin_phone_number'=>$this->next_of_kin_phone_number,
        'relationship'=>$this->relationship,
        'address'=>$this->address,

        'pension_pin'=>$this->pension_pin,
        'pfa_name'=>$this->pfa_name,
        'profile_picture'=>$this->profile_picture?$this->profile_picture->store('profilePictures','public'):'',
        ]);
            $this->ids=$employee->id;
            $this->salary_update();
            DB::commit();
            $this->reset_field();
            $this->alert('success','New record have been added',[
                'timer'=>9000
            ]);
            $user=Auth::user();
            $log=new ActivityLog();
            $log->user_id=$user->id;
            $log->action="Added an employee with payroll number ($this->payroll_number)";
            $log->save();
        }catch (\Exception $e)
        {
            DB::rollBack();
            return redirect()->back()->withErrors([
                'error' => 'Failed to save employee or salary data. ' . $e->getMessage(),
            ])->withInput();
        }
//        return redirect()->route('employee.profile');
        $this->reset_field();

    }
    public function reset_field()
    {
        $this->full_name='';
        $this->staff_number='';
        $this->payroll_number='';
        $this->employment_type='';
        $this->status='';
        $this->department='';
        $this->date_of_first_appointment='';
        $this->date_of_last_promotion='';
        $this->date_of_retirement='';
        $this->post_held='';
        $this->salary_structure='';
        $this->grade_level='';
        $this->step='';
        $this->account_number='';
        $this->bank_code='';

        $this->bank_name='';
        $this->gender='';
        $this->tribe='';
        $this->religion='';
        $this->phone_number='';
        $this->whatsapp_number='';
        $this->email='';
        $this->nationality='';
        $this->state_of_origin='';
        $this->local_government='';
        $this->date_of_birth='';
        $this->marital_status='';
        $this->name_of_next_of_kin='';
        $this->next_of_kin_phone_number='';
        $this->relationship='';
        $this->address='';
        $this->profile_picture=null;

        $this->pension_pin='' ;
        $this->pfa_name='' ;
        $this->contract_termination_date='';
        $this->staff_union='';
        $this->tax_id='';
    }
    public function updated_($prop)
    {
        $this->validateOnly($prop);
    }
    public function create_emp(){
        $this->create=true;
        $this->record=false;
        $this->edit=false;
        $this->view=false;
    }
    public function salary_update()
    {


        $salary=SalaryStructureTemplate::where('salary_structure_id',$this->salary_structure)
            ->where('grade_level',$this->grade_level)
            ->first();
        $a=SalaryAllowanceTemplate::where('salary_structure_id',$this->salary_structure)
            ->whereRaw('? between grade_level_from and grade_level_to', [$this->grade_level])
            ->get();
        $d=SalaryDeductionTemplate::where('salary_structure_id',$this->salary_structure)
            ->whereRaw('? between grade_level_from and grade_level_to', [$this->grade_level])
            ->get();
        if(!empty($salary)){
            $annual_salary=$salary["Step".$this->step];

            $basic_salary=round($annual_salary/12,2);

            if (SalaryUpdate::where('employee_id',$this->ids)->exists())
            {

                $salary_update=SalaryUpdate::where('employee_id',$this->ids)->first();

                //alowance
                $total_allow=0;
                foreach ($a as $key=>$allow){
                    if ($allow->allowance_type==1){
                        $amount=round($basic_salary/100 * $allow->value,2);
                    }else{
                        $amount=$allow->value;
                    }
                    $salary_update["A$allow->allowance_id"]=$amount;
                    $total_allow += round($amount,2);
                    $salary_update->save();
                }
                //deduction
                $total_deduct=0;
                foreach (Deduction::where('status',1)->get() as $deduction)
                {
                    if($deduction->id == 1){
                        $paye=app(DeductionCalculation::class);
                        $default_paye_calculation=app_settings()->paye_calculation;
                        $default_statutory_calculation=app_settings()->statutory_deduction;
                        if ($default_paye_calculation == 2){
                            $amount= $paye->paye_calculation1($basic_salary,$default_statutory_calculation);
                        }else{
                            $amount= $paye->paye_calculation2($basic_salary,$default_statutory_calculation);
                        }

                    }
                    else{

                        $dedTemp=SalaryDeductionTemplate::where('salary_structure_id',$this->salary_structure)
                            ->whereRaw('? between grade_level_from and grade_level_to', [$this->grade_level])
                            ->where('deduction_id',$deduction->id)->first();
                        //check if percentage of basic
                       if(!is_null($dedTemp)){
                           if ($dedTemp->deduction_type==1){
                               $amount=round($basic_salary/100 * $dedTemp->value,2);
                           }else{
                               $amount=$dedTemp->value;
                           }
                           //check if employee has pension
                           if ($dedTemp->deduction_id ==2 || $dedTemp->deduction_id==3){
                               if ($this->pfa_name == 10)
                               {
                                   $amount=0.00;
                               }
                           }
                           //check union
                           elseif(UnionDeduction::where('deduction_id',$dedTemp->deduction_id)->get()->count()>0){
                               if (UnionDeduction::where('deduction_id',$dedTemp->deduction_id)->where('union_id',$this->staff_union)->get()->count() > 0){
                                   $amount=  $amount;
                               }else{
                                   $amount=0.00;
                               }
                           }
                       }else{
                           $amount=$salary_update["D$deduction->id"];
                       }
                    }
                    $salary_update["D$deduction->id"]=$amount;
                    $total_deduct += round($amount,2);
                    $salary_update->save();
                }


                $salary_update->basic_salary=$basic_salary;
                $salary_update->total_allowance=$total_allow;
                $salary_update->total_deduction=$total_deduct;
                $total_earning=round($basic_salary +$total_allow,2);
                $gross_pay=$total_earning;
                $net_pay=round($gross_pay - $salary_update->total_deduction,2);
                $salary_update->gross_pay=$gross_pay;
                $salary_update->net_pay=$net_pay;
                $salary_update->save();
                $user=Auth::user();
                $log=new ActivityLog();
                $log->user_id=$user->id;
                $log->action="updated salary record for ($this->payroll_number)";
                $log->save();
            }
            else{
                $salary_update=new SalaryUpdate();
                $salary_update->employee_id=$this->ids;
                $salary_update->basic_salary=$basic_salary;

                //alowance
                $total_allow=0;
                foreach ($a as $key=>$allow){
                    if ($allow->allowance_type==1){
                        $amount=round($basic_salary/100 * $allow->value,2);
                    }else{
                        $amount=$allow->value;
                    }
                    $salary_update["A$allow->allowance_id"]=$amount;
                    $total_allow += round($amount,2);
                    $salary_update->save();
                }
                //deduction
                $total_deduct=0;
//
                foreach (Deduction::where('status',1)->get() as $deduction)
                {
                    $default_paye_calculation=app_settings()->paye_calculation;
                    $default_statutory_calculation=app_settings()->statutory_deduction;
                    if($deduction->id == 1){
                        $paye=app(DeductionCalculation::class);
                        if ($default_paye_calculation == 2){
                            $amount= $paye->paye_calculation1($basic_salary,$default_statutory_calculation);
                        }else{
                            $amount= $paye->paye_calculation2($basic_salary,$default_statutory_calculation);
                        }

                    }
                    else{
                        $amount=0.00;
                        $dedTemp=SalaryDeductionTemplate::where('salary_structure_id',$this->salary_structure)
                            ->whereRaw('? between grade_level_from and grade_level_to', [$this->grade_level])
                            ->where('deduction_id',$deduction->id)->first();
                        //check if percentage of basic
                        if(!is_null($dedTemp)){
                            if ($dedTemp->deduction_type==1){
                                $amount=round($basic_salary/100 * $dedTemp->value,2);
                            }else{
                                $amount=$dedTemp->value;
                            }
                            //check if employee has pension
                            if ($dedTemp->deduction_id ==2 || $dedTemp->deduction_id==3){
                                if ($this->pfa_name == 10)
                                {
                                    $amount=0.00;
                                }
                            }
                            //check union
                            elseif(UnionDeduction::where('deduction_id',$dedTemp->deduction_id)->get()->count()>0){
                                if (UnionDeduction::where('deduction_id',$dedTemp->deduction_id)->where('union_id',$this->staff_union)->get()->count() > 0){
                                    $amount=  $amount;
                                }else{
                                    $amount=0.00;
                                }
                            }
                        }
                    }
                    $salary_update["D$deduction->id"]=$amount;
                    $total_deduct += round($amount,2);
                    $salary_update->save();
                }


                $salary_update->basic_salary=$basic_salary;
                $salary_update->total_allowance=$total_allow;
                $salary_update->total_deduction=$total_deduct;
                $total_earning=round($basic_salary + $total_allow,2);
                $gross_pay=$total_earning;
                $net_pay=round($gross_pay - $total_deduct,2);
                $salary_update->gross_pay=$gross_pay;
                $salary_update->net_pay=$net_pay;
                $salary_update->save();
                $user=Auth::user();
                $log=new ActivityLog();
                $log->user_id=$user->id;
                $log->action="Added new salary record for ($this->payroll_number)";
                $log->save();
            }
        }


    }

    public function edit_emp($id){
        $employee=\App\Models\EmployeeProfile::find($id);
        if ($employee->nationality != null){
            $this->states=State::where('country',1)->get();
        }
        if ($employee->state_of_origin != null){
            $this->lgas=LocalGovt::where('state_id',$employee->state_of_origin)->get();
        }
        if ($employee->unit != null){

            $this->departments=Department::where('unit_id',$employee->unit)->where('status',1)->get();
        }
        $this->ids=$id;
        $this->record=false;
        $this->create=false;
        $this->edit=true;
        $this->view=false;
        $this->full_name=$employee->full_name;
        $this->rank=$employee->rank;
        $this->unit=$employee->unit;
        $this->staff_number=$employee->staff_number;
        $this->payroll_number=$employee->payroll_number;
        $this->employment_type=$employee->employment_type;
        $this->staff_category=$employee->staff_category;
        $this->status=$employee->status;
        $this->date_of_first_appointment=$employee->date_of_first_appointment;
        $this->date_of_last_promotion=$employee->date_of_last_appointment;
        $this->date_of_retirement=$employee->date_of_retirement;
        $this->post_held=$employee->post_held;
        $this->salary_structure=$employee->salary_structure;
        $this->grade_level=$employee->grade_level;
        $this->step=$employee->step;
        $this->department=$employee->department;
        $this->account_number=$employee->account_number;
        $this->bank_code=$employee->bank_code;
        $this->bank_name=$employee->bank_name;
        $this->bvn=$employee->bvn;
        $this->tax_id=$employee->tax_id;
        $this->staff_union=$employee->staff_union;
        $this->contract_termination_date=$employee->contract_termination_date;

        $this->gender=$employee->gender;
        $this->tribe=$employee->tribe;
        $this->religion=$employee->religion;
        $this->phone_number=$employee->phone_number;
        $this->whatsapp_number=$employee->whatsapp_number;
        $this->email=$employee->email;
        $this->nationality=$employee->nationality;
        $this->state_of_origin=$employee->state_of_origin;
        $this->local_government=$employee->local_government;
        $this->date_of_birth=$employee->date_of_birth;
        $this->marital_status=$employee->marital_status;
        $this->name_of_next_of_kin=$employee->name_of_next_of_kin;
        $this->next_of_kin_phone_number=$employee->next_of_kin_phone_number;
        $this->relationship=$employee->relationship;
        $this->address=$employee->address;

        $this->pension_pin=$employee->pension_pin;
        $this->pfa_name=$employee->pfa_name;

    }
    public function update($id)
    {
        $this->validate();
        $this->validateStep();
//        if ($this->employment_type==1 || $this->employment_type==3){
//            $ct=$this->date_of_first_appointment? Carbon::parse($this->date_of_first_appointment)->addYear() : null;
//        }else{
//            $ct=null;
//        }
        $profileObj=\App\Models\EmployeeProfile::find($id);
        $profileObj->full_name=$this->full_name;
        $profileObj->rank=$this->rank;
        $profileObj->unit=$this->unit;
        $profileObj->staff_number=$this->staff_number;
        $profileObj->payroll_number=$this->payroll_number;
        $profileObj->employment_type=$this->employment_type;
        $profileObj->staff_category=$this->staff_category;
        $profileObj->status=$this->status;
        $profileObj->date_of_first_appointment=$this->date_of_first_appointment;
        $profileObj->date_of_last_appointment=$this->date_of_last_promotion;
        $profileObj->date_of_retirement=Carbon::parse($this->date_of_birth)->addYears(65);
            $profileObj->contract_termination_date=$this->contract_termination_date? $this->contract_termination_date:null;

        $profileObj->post_held=$this->post_held;
        $profileObj->salary_structure=$this->salary_structure;
        $profileObj->grade_level=$this->grade_level;
        $profileObj->step=$this->step;
        $profileObj->department=$this->department;
        $profileObj->account_number=$this->account_number;
        $profileObj->bank_code=$this->bank_code;
        $profileObj->bvn=$this->bvn;
        $profileObj->tax_id=$this->tax_id;
        $profileObj->staff_union=$this->staff_union;

        $profileObj->bank_name=$this->bank_name;
        $profileObj->gender=$this->gender;
        $profileObj->tribe=$this->tribe;
        $profileObj->religion=$this->religion;
        $profileObj->phone_number=$this->phone_number;
        $profileObj->whatsapp_number=$this->whatsapp_number;
        $profileObj->email=$this->email;
        $profileObj->nationality=$this->nationality;
        $profileObj->state_of_origin=$this->state_of_origin;
        $profileObj->local_government=$this->local_government;
        $profileObj->date_of_birth=$this->date_of_birth;
        $profileObj->marital_status=$this->marital_status;
        $profileObj->name_of_next_of_kin=$this->name_of_next_of_kin;
        $profileObj->next_of_kin_phone_number=$this->next_of_kin_phone_number;
        $profileObj->relationship=$this->relationship;
        $profileObj->address=$this->address;

        $profileObj->pension_pin=$this->pension_pin;
        $profileObj->pfa_name=$this->pfa_name;

        if ($this->profile_picture ==''){
            $profileObj->profile_picture=$profileObj->profile_picture;
        }else{
            $profileObj->profile_picture=$this->profile_picture->store('profilePictures','public');

        }


        $this->employeeId=\App\Models\EmployeeProfile::where('payroll_number',$this->payroll_number)->first();
        if (!\App\Models\EmployeeProfile::where('id',$profileObj->id)->where('salary_structure',$this->salary_structure)
            ->where('grade_level',$this->grade_level)->where('step',$this->step)->exists()){

            $this->salary_update();
        }
        $profileObj->save();
        $this->alert('success','Employee record have been Updated',[
            'timer'=>9000
//            'progressBarTimer'=>5
        ]);
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $log->action="Updated employee with payroll number  ($this->payroll_number)";
        $log->save();
//        return redirect()->route('employee.profile');
    }
    public function close()
    {
        $this->create=false;
        $this->edit=false;
        $this->view=false;
        $this->record=true;
        $this->reset_field();
    }
    public function updatedNationality()
    {

        if ($this->nationality != null){
            $this->states=State::where('country',1)->get();
        }
    }
    public function UpdatedStateOfOrigin()
    {
        if ($this->state_of_origin != ''){
            $this->lgas =LocalGovt::where('state_id',$this->state_of_origin)->get();
        }
    }
    public function UpdatedUnit()
    {

        if ($this->unit != ''){

            $this->departments = Department::where('unit_id',$this->unit)->where('status',1)->get();
        }
    }
    public function updatedBankName()
    {
        if ($this->bank_name !=''){
            $this->bank_code=Bank::where('bank_name',$this->bank_name)->get();
            $this->bank_code=$this->bank_code['0']['id'];
        }
    }
    public function updatedBankCode()
    {
        if ($this->bank_code !=' '){
            $this->bank_name=Bank::where('id',$this->bank_code)->get();
            $this->bank_name=$this->bank_name['0']['bank_name'];
        }
    }
    public function mount()
    {
        $this->states=[];
        $this->lgas=[];
        $this->departments=[];
        $this->depts=[];
    }
    public function updatedSearch()
    {

        $this->render();
    }
    public function updatedFilterUnit()
    {

        if ($this->filter_unit != ''){
            $this->depts=Department::where('unit_id',$this->filter_unit)->where('status',1)->get();
        }
    }
    public function view_emp($id)
    {
        $this->view=true;
        $this->record=false;
        $this->create=false;
        $this->edit=false;
        $this->ids=$id;
        $this->employeeInfo=\App\Models\EmployeeProfile::find($id);
    }
    public function searchEmployee()
    {
        $emp=\App\Models\EmployeeProfile::where('payroll_number',$this->search_employee)->first();
        if ($emp){
            $this->view_emp($emp->id);
        }

    }
    public function updatedDateOfBirth()
    {
        $this->date_of_retirement=Carbon::parse($this->date_of_birth)->addYears(65)->format('Y-m-d');
    }
    public function updatedDateOfFirstAppointment()
    {
        if ($this->employment_type == 1 || $this->employment_type==3){
            $this->contract_termination_date=Carbon::parse($this->date_of_first_appointment)->addYear()->format('Y-m-d');

        }

    }
    public function deleteId($id){
        $this->ids=$id;
        $emp=\App\Models\EmployeeProfile::find($this->ids);
        $name=$emp->full_name." with payroll number ($emp->payroll_number)";
        $this->alert('warning','Deleting this record will permanently remove '.$name.' profile information, salary details and login credentials. are you sure you want to proceed?',[
                'showConfirmButton' => true,
                'confirmButtonText' => 'Yes',
                'onConfirmed' => 'delete',
                'showCancelButton' => true,
                'onDismissed' => 'cancelled',
                'position' => 'center',
                'timer'=>90000,
                'timerProgressBar'=>true,
                'toast' => true,
        ]);
    }
    public function delete()
    {
        $emp=\App\Models\EmployeeProfile::find($this->ids);
        $slry=SalaryUpdate::where('employee_id',$emp->id)->first();
        $user=User::where('email',$emp->email)->first();
        try {
            if (!is_null($slry)){
                $slry->delete();

            }
            if (!is_null($user)){
                $user->delete();
            }
            $emp->delete();
        }catch (\Exception $e){

        }
        $this->alert('success','Record has been deleted successfully');
    }
    public function render()
    {
        $salary_structures=SalaryStructure::where('status',1)->get();

        $ranks=Rank::where('status',1)->get();
        $categories=StaffCategory::where('status',1)->get();
        $employments=EmploymentType::where('status',1)->get();
        $units=Unit::where('status',1)->get();
        if ($this->search !=''){
            $employees=\App\Models\EmployeeProfile::where('full_name','like',"%$this->search%")
                ->orWhere('phone_number','like',"%$this->search%")
                ->orWhere('payroll_number','like',"%$this->search%")
                ->orWhere('pension_pin','like',"%$this->search%")
                ->orWhere('staff_number','like',"%$this->search%")
                ->paginate($this->perpage);
        }else {
            $employees = \App\Models\EmployeeProfile::when($this->filter_unit, function ($query) {
                return $query->where('unit', $this->filter_unit);
            })
                ->when($this->filter_dept, function ($query) {
                    return $query->where('department', $this->filter_dept);
                })
                ->when($this->filter_type, function ($query) {
                    return $query->where('employment_type', $this->filter_type);
                })
                ->paginate($this->perpage);
        }
        $pfas=PFA::where('status',1)->get();
        $banks=Bank::where('status',1)->get();
        $relationships=Relationship::all();
        $religions=Religion::all();
        $depts=Department::where('status',1)->get();
        return view('livewire.forms.employee-profile',compact(['religions','depts','relationships','salary_structures','banks','units','pfas','ranks','employments','categories','employees']))
            ->extends('components.layouts.app');
    }
}
