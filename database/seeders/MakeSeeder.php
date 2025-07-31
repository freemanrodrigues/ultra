<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use League\Csv\Reader;

class MakeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/make_msts.csv');

        if (!file_exists($path)) {
            $this->command->error("CSV file not found at: $path");
            return;
        }

        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0); // First row is header
        					

        foreach ($csv->getRecords() as $record) {
            DB::table('make_msts')->insert([
                'make_code'  => $record['make_code'],
                'make_name'  => $record['make_name'],
                'brand_id'  => $record['brand_id'],
                'status'     => 1,
            ]);
        }
        	

        $this->command->info('Make data seeded successfully!');
    }
}
