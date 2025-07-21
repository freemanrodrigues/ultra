<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use League\Csv\Reader;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/customer.csv');

        if (!file_exists($path)) {
            $this->command->error("CSV file not found at: $path");
            return;
        }

        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0); // First row is header

        foreach ($csv->getRecords() as $record) {
            DB::table('customer_masters')->insert([
                		

                'customer_name'  => $record['customer_name'],
                'company_id'  => $record['company_id'],
                'gst_no'  => $record['gst_no'],
                'status'     => 1, 
            ]);
        }
        	

        $this->command->info('Customer data seeded successfully!');
    }
}
