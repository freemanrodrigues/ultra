<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use League\Csv\Reader;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/unit.csv');

        if (!file_exists($path)) {
            $this->command->error("CSV file not found at: $path");
            return;
        }

        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0); // First row is header

        foreach ($csv->getRecords() as $record) {
            DB::table('unit_masters')->insert([
                'unit_code'  => $record['Code'],
                'unit_name'  => $record['Name'],
                'status'     => 1,
            ]);
        }
        	

        $this->command->info('Unit data seeded successfully!');
    }
}
