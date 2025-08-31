<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use League\Csv\Reader;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/test_master.csv');

        if (!file_exists($path)) {
            $this->command->error("CSV file not found at: $path");
            return;
        }

        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0); // First row is header
        					

        foreach ($csv->getRecords() as $record) {
            DB::table('test_masters')->insert([
                'test_name'  => $record['bottle_code'],
                'bottle_name'  => $record['bottle_name'],
                'status'     => 1,
            ]);
        }
        		default_unit	tat_hours_default	status

        $this->command->info('Bottle data seeded successfully!');
    }
}
