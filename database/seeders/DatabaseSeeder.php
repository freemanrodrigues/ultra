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
        //    CountrySeeder::class,
        //    StateSeeder::class,
            CompanySeeder::class,
            CustomerSeeder::class,
        /* UserSeeder::class, 
           SiteMasterSeeder::class,
            UnitSeeder::class, 
            CourierSeeder::class,
            BrandSeeder::class,
            BottleSeeder::class,
            FerrographySeeder::class,
            MakeSeeder::class, */
        ]);
    }
}
