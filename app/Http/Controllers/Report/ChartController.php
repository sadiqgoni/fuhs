<?php

namespace App\Http\Controllers\Report;

use App\Charts\PayrollChart;
use App\Http\Controllers\Controller;
use App\Models\SalaryHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ChartController extends Controller
{
    public function showChart()
    {
        $data = SalaryHistory::select(
            DB::raw('month(date_month) as month'),
            DB::raw('sum(basic_salary) as total_basic_salary'),
            DB::raw('sum(gross_pay) as gross_pay_total'),
            DB::raw('sum(total_allowance) as total_allowance'),
            DB::raw('sum(total_deduction) as total_deduction'),
            DB::raw('sum(net_pay) as net_pay'),
        )
            ->orderBy('month')
            ->groupBy('month')
            ->get();
//            ->map(function ($item) {
//                dd($item);
//                $item->sum('gross_pay');
//                return count($item);
//            });
        dd($data);
        $start = Carbon::parse(User::min("created_at"));
        $end = Carbon::now();
        $period = CarbonPeriod::create($start, "1 month", $end);

        $usersPerMonth = collect($period)->map(function ($date) {
            $endDate = $date->copy()->endOfMonth();

            return [
                "count" => User::where("created_at", "<=", $endDate)->count(),
                "month" => $endDate->format("Y-m-d")
            ];
        });

        $data = $usersPerMonth->pluck("count")->toArray();
        $labels = $usersPerMonth->pluck("month")->toArray();

        $chart = Chartjs::build()
            ->name("UserRegistrationsChart")
            ->type("line")
            ->size(["width" => 400, "height" => 200])
            ->labels($labels)
            ->datasets([
                [
                    "label" => "User Registrations",
                    "backgroundColor" => "rgba(38, 185, 154, 0.31)",
                    "borderColor" => "rgba(38, 185, 154, 0.7)",
                    "data" => $data
                ]
            ])
            ->options([
                'scales' => [
                    'x' => [
                        'type' => 'time',
                        'time' => [
                            'unit' => 'month'
                        ],
                        'min' => $start->format("Y-m-d"),
                    ]
                ],
                'plugins' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Monthly User Registrations'
                    ]
                ]
            ]);

        return view("user.chart", compact("chart"));

    }

    public function get_month()
    {
        $monthArray=array();
        $postdates= SalaryHistory::orderBy('date_month','ASC')->select('date_month')->get();
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
        $month_total=SalaryHistory::where('date_month',$month)->get()->sum('basic_salary');
        return $month_total;
    }
    public function getMonthlyData()
    {
        $monthly_post_array=array();
        $monthArray=$this->get_month();
        $month_name_array=array();
        foreach ($monthArray as $monthNo=>$monthName)
        {
            $monthPostSum=$this->getMonthTotal($monthNo);
            array_push($monthly_post_array,$monthPostSum);
            array_push($month_name_array,$monthName);
        }
        $monthlyPostDataArray=array('months'=>$month_name_array,'sumData'=>$monthly_post_array);
        return $monthlyPostDataArray;
    }
    public function payroll_chart()
    {
      $chart=new PayrollChart();
      $history=$this->getMonthlyData();
      $chart->labels($history['months']);
      $chart->dataset('monthly report','line',$history['sumData'])->options([
          'color'=>'red',
          'background'=>'seagreen'
      ]);
        return view('reports.chart',compact('chart'));
    }
}
