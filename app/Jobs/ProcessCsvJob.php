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
                // here you can process each row of the CSV file
                // for example, you can create a new product
                Product::create([
                    'name' => $data[0],
                    'sku' => $data[1],
                    'description' => $data[2],
                    'brand' => $data[3],
                ]);
            }

            fclose($handle);
        }
    }
}