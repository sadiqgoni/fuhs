<?php

namespace App\Imports;

use App\Models\SalaryStructureTemplate;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SalaryTemplate implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public $data;
    private $nextId;

    public function __construct($data)
    {
        $this->data=$data;
        $this->nextId = (SalaryStructureTemplate::max('id') ?? 0) + 1;
    }
    public function model(array $row)
    {
        $step = fn ($key) => $row[$key] ?? 0;

        return new SalaryStructureTemplate([
            'id'=>$this->nextId++,
            'salary_structure_id'=>$this->data,
            'grade_level'=>$row['grade_level_from'],
            'no_of_grade_steps'=>$row['no_of_grade_steps'],
            'Step1'=>$step('step1'),
            'Step2'=>$step('step2'),
            'Step3'=>$step('step3'),
            'Step4'=>$step('step4'),
            'Step5'=>$step('step5'),
            'Step6'=>$step('step6'),
            'Step7'=>$step('step7'),
            'Step8'=>$step('step8'),
            'Step9'=>$step('step9'),
            'Step10'=>$step('step10'),
            'Step11'=>$step('step11'),
            'Step12'=>$step('step12'),
            'Step13'=>$step('step13'),
            'Step14'=>$step('step14'),
            'Step15'=>$step('step15'),
            'Step16'=>$step('step16'),
            'Step17'=>$step('step17'),
            'Step18'=>$step('step18'),
            'Step19'=>$step('step19'),
            'Step20'=>$step('step20'),
        ]);
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
