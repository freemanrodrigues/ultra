<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use League\Csv\Reader;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/customer.csv');

        if (!file_exists($path)) {
            $this->command->error("CSV file not found at: $path");
            return;
        }

        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0); // First row is header
$values = [7, 15, 30, 45, 60, 90, 180];
        foreach ($csv->getRecords() as $record) {
            $randomIndex = array_rand($values);
            DB::table('customer_masters')->insert([
                'customer_name'  => $record['customer_name'],
                'company_id'  => $record['company_id'],
                'gst_no'  => $record['gst_no'],
                'gst_state_code' => substr($record['gst_no'], 0, 2),
                'status'     => 1, 
                'address'     => 'Address', 
                'city'     => 'city', 
                'state'     => substr($record['gst_no'], 0, 2), 
                'country'     => 71, 
                'pincode'     => '400123', 
                'billing_cycle' => rand(1,3),
'group'=> rand(1,3),
'credit_cycle'=> $values[$randomIndex]         ]);
        }
    /*    	update `customer_masters` set gst_state_code = SUBSTRING(gst_no, 1, 2), state = SUBSTRING(gst_no, 1, 2) */
$sql = "update `states` , customer_masters set gst_state_code = shortname   where statecode = state";
 DB::statement($sql);
        $this->command->info('Customer data seeded successfully!');
    }
}
