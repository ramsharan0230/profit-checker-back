<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use League\Csv\Reader;

class BOMSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        info("hello there for test");
        $file = public_path('data/bom.csv');
        if(!file_exists($file) || !is_readable($file)){
            info("CSV file not found or unreadable at: $file");
            return;
        }

        $csv = Reader::createFromPath($file, 'r');
        $csv->setHeaderOffset(0);
        $records = $csv->getRecords();
        
        foreach ($records as $record) {
            dump("product: ", $record);

            try {
                Product::create([
                    'name'         => $record['Product'],
                    'quantity'     => (int) $record['Quantity'],
                    'trade_price'  => isset($record['Total Trade Price']) ? floatval(str_replace(['$', ','], '', $record['Total Trade Price'])) : 0.0,
                    'retail_price' => isset($record['Total Retail Price']) ? floatval(str_replace(['$', ','], '', $record['Total Retail Price'])) : 0.0,
                    'mpn'          => $record['MPN'] ?: null,
                    'sku'          => $record['SKU'] ?: null,
                    'status'       => true,
                ]);
            } catch (\Exception $e) {
                Log::error("Failed to insert product: " . json_encode($record) . " - Error: " . $e->getMessage());
            }
        }

        Log::info("BOMSeeder completed successfully.");
    }
}
