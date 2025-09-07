<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use League\Csv\Reader;

class NatureOfSampleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "N O S ";
       $path = database_path('seeders/data/NatureOfSample.csv');

        if (!file_exists($path)) {
            $this->command->error("CSV file not found at: $path");
            return;
        }

        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0); // First row is header
        					

        foreach ($csv->getRecords() as $record) {
            DB::table('sample_natures')->insert([
                'sample_nature_code'  => $record['sample_nature_code'],
                'sample_nature_name'  => $record['sample_nature_name'],
                'status'     => 1,
                		
            ]);
        }
        $this->command->info('Nature of Sample seeded successfully!');
    }
}
