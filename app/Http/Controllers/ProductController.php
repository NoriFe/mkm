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

        // read file
        $file = fopen(storage_path('app/products.csv'), 'r');
        $lines = [];
        while (($line = fgetcsv($file)) !== false) {
            // skiiping the line if the sku matches
            if ($line[0] === $product->sku) {
                continue;
            }
            $lines[] = $line;
        }
        fclose($file);

        // write remaining lines to the file
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

    public function show($sku)
    {
        // finding product by sku
        $product = Product::where('sku', $sku)->first();

        if ($product) {
            return response()->json($product);
        } else {
            return response()->json(['error' => 'Product not found'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if ($product) {
            $product->update($request->all());

            // opening file
            $file = fopen(storage_path('app/products.csv'), 'r');
            $lines = [];
            while (($line = fgetcsv($file)) !== false) {
                // updating the line if the sku matches
                if ($line[0] === $product->sku) {
                    $line = [$product->sku, $product->name, $product->description, $product->brand];
                }
                $lines[] = $line;
            }
            fclose($file);

            // write back to the file
            $file = fopen(storage_path('app/products.csv'), 'w');
            foreach ($lines as $line) {
                fputcsv($file, $line);
            }
            fclose($file);

            return redirect()->route('products.index')->with('success', 'Product updated successfully');
        } else {
            return redirect()->route('products.index')->with('error', 'Product not found');
        }
    }
}
