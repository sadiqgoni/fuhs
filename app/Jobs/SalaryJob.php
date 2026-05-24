<?php

namespace App\Jobs;

use App\Imports\SalaryTemplate;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class SalaryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels,Batchable;

    /**
     * Create a new job instance.
     */
    public $uploadFile;
    public function __construct($uploadFile)
    {
        $this->uploadFile=$uploadFile;
    }
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        DB::transaction(function () {
            $salaryStructureId = $this->uploadFile['salary'];

            \App\Models\SalaryStructureTemplate::where('salary_structure_id', $salaryStructureId)->delete();
            Excel::import(new SalaryTemplate($salaryStructureId), $this->uploadFile['import']);
        });
    }
}
