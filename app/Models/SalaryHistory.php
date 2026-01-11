<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SalaryHistory extends Model
{
    use HasFactory;
    protected $fillable=[
        'id',
        'salary_month',
        'salary_year',
        'pf_number',
        'ip_number',
        'full_name',
        'unit',
        'department',
        'staff_category',
        'phone_number',
        'employment_type',
        'employment_status',
        'salary_structure',
        'grade_level',
        'step',
        'bank_code',
        'account_number',
        'bank_name',
        'pfa_name',
        'pension_pin',
        'basic_salary',
        'A1',
        'A2',
        'A3',
        'A4',
        'A5',
        'A6',
        'A7',
        'A8',
        'A9',
        'A10',
        'A11',
        'A12',
        'A13',
        'A14',
        'D1',
        'D2',
        'D3',
        'D4',
        'D5',
        'D6',
        'D7',
        'D8',
        'D9',
        'D10',
        'D11',
        'D12',
        'D13',
        'D14',
        'D15',
        'D16',
        'D17',
        'D18',
        'D19',
        'D20',
        'D21',
        'D22',
        'D23',
        'D24',
        'D25',
        'D26',
        'D27',
        'D28',
        'D29',
        'D30',
        'D31',
        'D32',
        'D33',
        'D34',
        'D35',
        'D36',
        'D37',
        'D38',
        'D39',
        'D40',
        'D41',
        'D42',
        'D43',
        'D44',
        'D45',
        'D46',
        'D47',
        'D48',
        'D49',
        'D50',
        'salary_areas',
        'total_allowance',
        'gross_pay',
        'total_deduction',
        'net_pay',
        'deduction_countdown',
        'salary_remark',
        'created_at',
        'updated_at',
        'date_month',
        'nhis',
        'employer_pension'
    ];

    public function get_month($data)
    {
        $monthArray=array();
        $postdates= SalaryHistory::whereYear('date_month', $data)->orderBy('date_month','ASC')->select('date_month')
//            ->where( DB::raw('YEAR(date_month)'), '=', $data )
//            ->whereYear('date_month', $data)
            ->get();

        $postdates=json_decode($postdates);
        if (!empty($postdates)){
            foreach ($postdates as $postdate)
            {
                $date=new \DateTime($postdate->date_month);
                $monthNo=$date->format('m');
                $monthName=$date->format('F');
                $monthArray[$monthNo]=$monthName;
            }
        }
        return $monthArray;
    }
    public function getMonthTotal($month)
    {

        $month_total=SalaryHistory::whereMonth('date_month',$month)->get()->sum('basic_salary');

        return $month_total;
    }
    public function getMonthlyData($data)
    {
        $monthly_post_array=array();
        $monthArray=$this->get_month($data);
        $month_name_array=array();
        foreach ($monthArray as $monthNo=>$monthName)
        {
            $monthPostSum=$this->getMonthTotal($monthNo);
            array_push($monthly_post_array,$monthPostSum);
            array_push($month_name_array,$monthName);
        }
        $monthlyPostDataArray=array('months'=>$month_name_array,'total'=>$monthly_post_array);
        return $monthlyPostDataArray;
    }


}
