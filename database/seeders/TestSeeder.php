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
        					

        //		default_unit	tat_hours_default	status
 // Initialize an empty array to hold your records
$recordsToInsert = [];

// Loop through the CSV records and prepare the data for a batch insert
foreach ($csv->getRecords() as $record) {
    // This is much more efficient than inserting one at a time.
    $recordsToInsert[] = [
       'test_name'  => $record['test_name'],
       'sample_type_id'  => $record['sample_type_id'],
        'status'       => 1,
    ];
}

// Now, perform a single, optimized batch insert.
// Laravel's insertOrIgnore() will use the database's UNIQUE or PRIMARY key
// to automatically skip any records that would cause a duplicate key error.
if (!empty($recordsToInsert)) {
    DB::table('test_masters')->insertOrIgnore($recordsToInsert);
}


        $this->command->info('Test data seeded successfully!');
    }
}
