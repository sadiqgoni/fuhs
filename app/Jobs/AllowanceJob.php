<?php

namespace App\Jobs;

use App\Imports\AllowanceImport;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class AllowanceJob implements ShouldQueue
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
        Excel::import(new AllowanceImport($this->uploadFile['allowance'],$this->uploadFile['salary']), $this->uploadFile['import']);
    }
}
