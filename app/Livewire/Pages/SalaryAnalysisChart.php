<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use App\Models\SalaryHistory;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;

class SalaryAnalysisChart extends Component
{
    public $selectedYear;
    public $availableYears;
    public $chartData = [];
    public $chartId;
    protected $listeners = ['refreshChart' => '$refresh'];

    public function mount()
    {
        $this->availableYears = SalaryHistory::query()
            ->selectRaw('YEAR(date_month) as year')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->pluck('year');

        $this->selectedYear = $this->availableYears->first() ?? now()->year;
        $this->updateChartData();
    }

    public function updatedSelectedYear()
    {
        $this->updateChartData();
        $this->dispatch('refreshChart');
//        $this->dispatchBrowserEvent('refreshChart');
    }

    protected function updateChartData()
    {
        $salaryData = $this->getSalaryData();

        $this->chartData = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'datasets' => [
                $this->createDataset('Basic Salary', $salaryData->pluck('basic_salary'), '#3490dc'),
                $this->createDataset('Allowance', $salaryData->pluck('allowance'), '#38c172'),
                $this->createDataset('Deduction', $salaryData->pluck('deduction'), '#e3342f'),
                $this->createDataset('Gross Pay', $salaryData->pluck('gross_pay'), '#f6993f'),
                $this->createDataset('Net Pay', $salaryData->pluck('net_pay'), '#6574cd'),
            ]
        ];
    }

    protected function createDataset($label, $data, $color)
    {
        return [
            'label' => $label,
            'data' => $data,
            'borderColor' => $color,
            'backgroundColor' => $color,
            'tension' => 0.3,
            'borderWidth' => 2,
            'pointBackgroundColor' => $color,
            'fill' => false,
        ];
    }

    protected function getSalaryData()
    {
        $rawData = SalaryHistory::query()
            ->selectRaw('
                MONTH(date_month) as month,
                SUM(basic_salary) as basic_salary,
                SUM(total_allowance) as allowance,
                SUM(total_deduction) as deduction,
                SUM(gross_pay) as gross_pay,
                SUM(net_pay) as net_pay
            ')
            ->whereYear('date_month', $this->selectedYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        $salaryData = collect();
        for ($i = 1; $i <= 12; $i++) {
            $salaryData->push([
                'month' => $i,
                'basic_salary' => 0,
                'allowance' => 0,
                'deduction' => 0,
                'gross_pay' => 0,
                'net_pay' => 0,
            ]);
        }

        foreach ($rawData as $data) {
            $salaryData[$data->month - 1] = [
                'month' => $data->month,
                'basic_salary' => $data->basic_salary,
                'allowance' => $data->allowance,
                'deduction' => $data->deduction,
                'gross_pay' => $data->gross_pay,
                'net_pay' => $data->net_pay,
            ];
        }

        return $salaryData;
    }

    public function render()
    {
//        logger('Selected Year: ' . $this->selectedYear);
//        logger('Chart Data: ', $this->chartData);
        return view('livewire.pages.salary-analysis-chart')->extends('components.layouts.app');
    }
}
