<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use League\Csv\Reader;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/company.csv');

        if (!file_exists($path)) {
            $this->command->error("CSV file not found at: ".$path);
            return;
        }

        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0); // First row is header
        	

        // Initialize an empty array to hold your records
$recordsToInsert = [];

// Loop through the CSV records and prepare the data for a batch insert
foreach ($csv->getRecords() as $record) {
    // This is much more efficient than inserting one at a time.
    $recordsToInsert[] = [
        'company_name' => $record['company_name'],
        'pancard'      => $record['pancard'],
        'status'       => 1,
        'created_at'   => now(), // It's good practice to add these
        'updated_at'   => now(),
    ];
}

// Now, perform a single, optimized batch insert.
// Laravel's insertOrIgnore() will use the database's UNIQUE or PRIMARY key
// to automatically skip any records that would cause a duplicate key error.
if (!empty($recordsToInsert)) {
    DB::table('company_masters')->insertOrIgnore($recordsToInsert);
}

        $this->command->info('Compony data seeded successfully!');
    }
}
