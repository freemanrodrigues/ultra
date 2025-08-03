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
        	

        foreach ($csv->getRecords() as $record) {
            DB::table('company_masters')->insert([
                'company_name'  => $record['company_name'],
                'pancard'  => $record['pancard'],
                'status'     => 1,
            ]);
        }
        	

        $this->command->info('Compony data seeded successfully!');
    }
}
