<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use League\Csv\Reader;

class FerrographySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/ferrography_masters.csv');

        if (!file_exists($path)) {
            $this->command->error("CSV file not found at: $path");
            return;
        }

        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0); // First row is header
        					

        foreach ($csv->getRecords() as $record) {
            DB::table('ferrography_masters')->insert([
                'ferrography_code'  => $record['ferrography_code'],
                'ferrography_name'  => $record['ferrography_name'],
                'status'     => 1,
            ]);
        }
        	

        $this->command->info('Ferrography data seeded successfully!');
    }
}
