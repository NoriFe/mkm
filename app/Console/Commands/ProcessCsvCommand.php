<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ProcessCsvJob;

class ProcessCsvCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csv:process {file}';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process a CSV file';
    
    /**
     * Execute the console command.
     */
   
    public function handle()
    {
        $file = $this->argument('file');
        ProcessCsvJob::dispatch($file);
    }
}
