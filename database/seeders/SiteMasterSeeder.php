<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class SiteMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/site-master.csv');

        if (!file_exists($path)) {
            $this->command->error("CSV file not found at: $path");
            return;
        }

        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0); // First row is header

        foreach ($csv->getRecords() as $record) {
            DB::table('site_masters')->insert([
                        'site_code'  => $record['site_code'],
                        'site_name'  => $record['site_name'],
                        'site_display_name'  => $record['site_display_name'],
                        'company_id'  => $record['company_id'],
                        'customer_id'  => $record['customer_id'],
                        'status'     => 1,
                            ]);
        }
        	

        $this->command->info('Site Master data seeded successfully!');
    }
}
