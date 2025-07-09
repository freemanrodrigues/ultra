<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use League\Csv\Reader;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/indian_states_gst_codes.csv');

        if (!file_exists($path)) {
            $this->command->error("CSV file not found at: $path");
            return;
        }

        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0); // First row is header

        foreach ($csv->getRecords() as $record) {
            DB::table('states')->insert([
              //  statename		country_id	statecode	zone	status

                'statename'  => $record['statename'],
                'shortname'  => Str::upper(Str::limit($record['shortname'], 3, '')),
                'country_id' => 11,
                'statecode'  => $record['statecode'],
                'zone'       => null,
                'status'     => 1,
            ]);
        }

        $this->command->info('State data seeded successfully!');
    }
}
