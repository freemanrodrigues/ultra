<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use League\Csv\Reader;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/country.csv');

        if (!file_exists($path)) {
            $this->command->error("CSV file not found at: $path");
            return;
        }

        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0); // First row is header

        foreach ($csv->getRecords() as $record) {
            DB::table('countries')->insert([
                'countryname'  => $record['countryname'],
                'shortname'  => $record['shortname'],
                'isocode' => $record['isocode'],
                'isocode3'  => $record['isocode3'],
                'tel_countrycode'  => $record['tel_countrycode'],
                'currencycode'  => $record['currencycode'],
                'currencyrate' => $record['currencyrate'],
                'currencysign' => $record['currencysign'],
                'status'     => 1,
            ]);
        }

        $this->command->info('Country data seeded successfully!');
    }
}
