<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Jobs\ProcessCsvJob;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all(); // Retrieve all products from the database
        return view('products.index', ['products' => $products]); // Pass the products to the view

    
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'sku' => 'required',
            'description' => 'required',
            'brand' => 'required',
        ]);  
    
        $product = Product::create($request->all());
    
        // Open the CSV file in append mode
        $file = fopen(storage_path('app/products.csv'), 'a');
    
        // Add the product data to the CSV file
        fputcsv($file, [$product->sku, $product->name, $product->description, $product->brand]);
    
        // Close the file
        fclose($file);
    
        return redirect()->route('products.index')
                        ->with('success','Product created successfully.');
    }

    public function storeCsv(Request $request)
    {   // validate the request
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);
        // store the file
        $file = $request->file('csv_file')->store('csv');

        ProcessCsvJob::dispatch(storage_path('app/' . $file));

        return redirect()->route('products.index')
                        ->with('success','CSV file is being processed.');
    }

    
    public function destroy($id)
    {
        $product = Product::find($id);
        if ($product) {
            $product->delete();
    
            // reading csv file
            $file = fopen(storage_path('app/products.csv'), 'r');
            $lines = [];
            while (($line = fgetcsv($file)) !== false) {
                // will check if product is already deleted
                if ($line[0] === $product->sku) {
                    continue;
                }
                $lines[] = $line;
            }
            fclose($file);
    
            // write the remaining products to the csv file
            $file = fopen(storage_path('app/products.csv'), 'w');
            foreach ($lines as $line) {
                fputcsv($file, $line);
            }
            fclose($file);
    
            return redirect()->route('products.index')->with('success', 'Product deleted successfully');
        } else {
            return redirect()->route('products.index')->with('error', 'Product not found');
        }
    }
}
