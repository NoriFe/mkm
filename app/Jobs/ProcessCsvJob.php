<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Product;

class ProcessCsvJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $file;

    public function __construct($file)
    {
        $this->file = $file;
    }

    public function handle()
    {
        // opening the file
        if (($handle = fopen($this->file, 'r')) !== false) {            
            fgetcsv($handle);

            // processing each row
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                // update an existing product or create a new one
                Product::updateOrCreate(
                    ['sku' => $data[1]],
                    ['name' => $data[0], 'description' => $data[2], 'brand' => $data[3]]
            );
            }

            fclose($handle);
        }
    }
}