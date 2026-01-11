<?php

namespace App\Jobs;

use App\Imports\EmployeesImport;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeJob implements ShouldQueue
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
        Excel::import(new EmployeesImport, $this->uploadFile);
    }
    public function fail(\Exception $exception)
    {
        dd($exception);
    }
}
