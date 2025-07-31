<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use League\Csv\Reader;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/brand_masters.csv');

        if (!file_exists($path)) {
            $this->command->error("CSV file not found at: $path");
            return;
        }

        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0); // First row is header
        					
       
        foreach ($csv->getRecords() as $record) {
            DB::table('brand_masters')->insert([
                'brand_code'  => $record['brand_code'],
                'brand_name'  => $record['brand_name'],
                'status'     => 1,
            ]);
        }
        	

        $this->command->info('Brand data seeded successfully!');
    }
}
