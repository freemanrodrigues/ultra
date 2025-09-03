<?php

namespace Database\Seeders;

use App\Models\{CompanyMaster,User};
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
                    
        $this->call([
            CountrySeeder::class,
            StateSeeder::class,
            UserSeeder::class, 
            CompanySeeder::class,
            CustomerSeeder::class, 
            SiteMasterSeeder::class,
           CourierSeeder::class,
            SampleTypeSeeder::class,
            NatureOfSampleSeeder::class,
            BottleSeeder::class, 
               TestSeeder::class,
         /*
            
            UnitSeeder::class, 
            BrandSeeder::class,
            
            FerrographySeeder::class,
            MakeSeeder::class, */
        ]);
    }
}
