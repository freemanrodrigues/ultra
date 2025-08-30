<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use League\Csv\Reader;

class CourierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/courier_masters.csv');

        if (!file_exists($path)) {
            $this->command->error("CSV file not found at: $path");
            return;
        }

        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0); // First row is header
        					

        foreach ($csv->getRecords() as $record) {
            DB::table('courier_masters')->insert([
                'courier_code'  => $record['courier_code'],
                'courier_name'  => $record['courier_name'],
                'status'     => 1,
            ]);
        }
        	

        $this->command->info('Courier data seeded successfully!');
    }
}
