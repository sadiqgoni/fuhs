<?php

namespace App\Imports;

use App\Models\EmployeeProfile;
use App\Models\SalaryUpdate;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Collection;

class EmployeeProfileImport implements ToCollection, WithHeadingRow
//    , WithBatchInserts, WithUpserts, WithValidation
{
    use Importable;

    /**
     * Map old employee IDs (from backup file) to newly created EmployeeProfile IDs.
     *
     * @var array<int,int>
     */
    protected array $idMap = [];
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            if ($row['staff_union'] =="NA"){
                $union=null;
            }else{
                $union=$row['staff_union'];
            }

            // Create employee profile and remember mapping from old id -> new id
            $employee = EmployeeProfile::create([
                'employment_id'=>$row['employment_id'],
                'full_name'=>$row['full_name'],
                'department'=>$row['department'],
                'staff_category'=>$row['staff_category'],
                'employment_type'=>$row['employment_type'],
                'staff_number'=>$row['staff_number'],
                'payroll_number'=>$row['payroll_number'],
                'status'=>$row['status'],
                'salary_structure'=>$row['salary_structure'],
                'date_of_first_appointment'=>$row['date_of_first_appointment'],
                'date_of_last_appointment'=>$row['date_of_last_appointment'],
                'date_of_retirement'=>$row['date_of_retirement'],
                'contract_termination_date'=>$row['contract_termination_date'],
                'post_held'=>$row['post_held'],
                'grade_level'=>$row['grade_level'],
                'step'=>$row['step'],
                'rank'=>$row['rank'],
                'unit'=>$row['unit'],
                'phone_number'=>$row['phone_number'],
                'whatsapp_number'=>$row['whatsapp_number'],
                'email'=>$row['email'],
                'bank_name'=>$row['bank_name'],
                'account_number'=>$row['account_number'],
                'bank_code'=>$row['bank_code'],
                'pfa_name'=>$row['pfa_name'],
                'pension_pin'=>$row['pension_pin'],
                'date_of_birth'=>$row['date_of_birth'],
                'gender'=>$row['gender'],
                'religion'=>$row['religion'],
                'tribe'=>$row['tribe'],
                'marital_status'=>$row['marital_status'],
                'nationality'=>$row['nationality'],
                'state_of_origin'=>$row['state_of_origin'],
                'local_government'=>$row['local_government'],
                'profile_picture'=>$row['profile_picture'],
                'tax_id'=>$row['tax_id'],
                'bvn'=>$row['bvn'],
                'staff_union'=>$union,
                'name_of_next_of_kin'=>$row['name_of_next_of_kin'],
                'next_of_kin_phone_number'=>$row['next_of_kin_phone_number'],
                'relationship'=>$row['relationship'],
                'address'=>$row['address'],
            ]);

            if (isset($row['id']) && is_numeric($row['id'])) {
                $this->idMap[(int) $row['id']] = $employee->id;
            }
        }
        foreach ($rows as $row){
            // Determine the old employee id from the backup row
            $oldEmployeeId = null;
            if (isset($row['employee_id']) && is_numeric($row['employee_id'])) {
                $oldEmployeeId = (int) $row['employee_id'];
            } elseif (isset($row['id']) && is_numeric($row['id'])) {
                $oldEmployeeId = (int) $row['id'];
            }

            if (!$oldEmployeeId || !isset($this->idMap[$oldEmployeeId])) {
                // No matching employee in this restore run – skip to avoid misalignment
                continue;
            }

            $newEmployeeId = $this->idMap[$oldEmployeeId];

            // If there is no basic salary at all, treat as "no salary row" and skip
            if (!isset($row['basic_salary']) || $row['basic_salary'] === '' || $row['basic_salary'] === null) {
                continue;
            }

            SalaryUpdate::create([
                'employee_id'=>$newEmployeeId,
                'basic_salary'=>$row['basic_salary'],
                'A1'=>$row['a1'],
                'A2'=>$row['a2'],
                'A3'=>$row['a3'],
                'A4'=>$row['a4'],
                'A5'=>$row['a5'],
                'A6'=>$row['a6'],
                'A7'=>$row['a7'],
                'A8'=>$row['a8'],
                'A9'=>$row['a9'],
                'A10'=>$row['a10'],
                'A11'=>$row['a11'],
                'A12'=>$row['a12'],
                'A13'=>$row['a13'],
                'A14'=>$row['a14'],
                'D1'=>$row['d1'],
                'D2'=>$row['d2'],
                'D3'=>$row['d3'],
                'D4'=>$row['d4'],
                'D5'=>$row['d5'],
                'D6'=>$row['d6'],
                'D7'=>$row['d7'],
                'D8'=>$row['d8'],
                'D9'=>$row['d9'],
                'D10'=>$row['d10'],
                'D11'=>$row['d11'],
                'D12'=>$row['d12'],
                'D13'=>$row['d13'],
                'D14'=>$row['d14'],
                'D15'=>$row['d15'],
                'D16'=>$row['d16'],
                'D17'=>$row['d17'],
                'D18'=>$row['d18'],
                'D19'=>$row['d19'],
                'D20'=>$row['d20'],
                'D21'=>$row['d21'],
                'D22'=>$row['d22'],
                'D23'=>$row['d23'],
                'D24'=>$row['d24'],
                'D25'=>$row['d25'],
                'D26'=>$row['d26'],
                'D27'=>$row['d27'],
                'D28'=>$row['d28'],
                'D29'=>$row['d29'],
                'D30'=>$row['d30'],
                'D31'=>$row['d31'],
                'D32'=>$row['d32'],
                'D33'=>$row['d33'],
                'D34'=>$row['d34'],
                'D35'=>$row['d35'],
                'D36'=>$row['d36'],
                'D37'=>$row['d37'],
                'D38'=>$row['d38'],
                'D39'=>$row['d39'],
                'D40'=>$row['d40'],
                'D41'=>$row['d41'],
                'D42'=>$row['d42'],
                'D43'=>$row['d43'],
                'D44'=>$row['d44'],
                'D45'=>$row['d45'],
                'D46'=>$row['d46'],
                'D47'=>$row['d47'],
                'D48'=>$row['d48'],
                'D49'=>$row['d49'],
                'D50'=>$row['d50'],
                'salary_arears'=>$row['salary_arears'],
                'gross_pay'=>$row['gross_pay'],
                'total_allowance'=>$row['total_allowance'],
                'total_deduction'=>$row['total_deduction'],
                'net_pay'=>$row['net_pay'],
                'nhis'=>$row['nhis'],
                'employer_pension'=>$row['employer_pension'],
                'deduction_countdown'=>$row['deduction_countdown'],
            ]);
        }

    }


    public function batchSize(): int
    {
        return 100;
    }
    public function uniqueBy()
    {
//        return 'staff_number';
    }
    public function rules(): array
    {
        return [
            '1' => Rule::in(['patrick@maatwebsite.nl']),

            // Above is alias for as it always validates in batches
            '*.1' => Rule::in(['patrick@maatwebsite.nl']),

            // Can also use callback validation rules
            '0' => function($attribute, $value, $onFailure) {
                if ($value !== 'Patrick Brouwers') {
                    $onFailure('Name is not Patrick Brouwers');
                }
            }
        ];
    }
}
