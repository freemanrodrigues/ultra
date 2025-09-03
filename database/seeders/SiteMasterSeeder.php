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
                        'site_name'  => $record['site_name'],
                        'address'  => $record['address'],
                        'city'  => $record['city'],
                        'state'  => $record['state'],
                        'country'  => $record['country'],
                        'lat'  => $record['lat'],
                        'long'  => $record['long'],
                        'status'     => 1,
                            ]);
                           
        }
        	

        $this->command->info('Site Master data seeded successfully!');
    }
}
