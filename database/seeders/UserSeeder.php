<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use League\Csv\Reader;
use Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/user.csv');

        if (!file_exists($path)) {
            $this->command->error("CSV file not found at: $path");
            return;
        }

        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0); // First row is header
        					
        $pwd = Hash::make('Asdf123$');
        foreach ($csv->getRecords() as $record) {
            DB::table('users')->insert([
                'email'  => $record['email'],
                'phone'  => $record['phone'],
                'firstname'  => $record['firstname'],
                'lastname'  => $record['lastname'],
                'company_id'  => $record['company_id'],
                'customer_id'  => $record['customer_id'],
                'user_type'  => $record['user_type'],
                'user_role'  => $record['user_role'],
                'password'  => $pwd,
                'status'     => 1,
            ]);
        }
        	

        $this->command->info('User data seeded successfully!');
    }
}
