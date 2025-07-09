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
        // User::factory(10)->create();
/*
        User::factory()->create([
          'email' => 'test@example.com', 'firstname' => 'Test', 'lastname' => 'Ltest', 'phone' => '9869635632'
        ]);
        User::factory()->create([
             'email' => 'freeman.rodrigues@gmail.com', 'firstname' => 'freeman', 'lastname' => 'rodrigues', 'phone' => '9869635631'
        ]);
    */   
                    
        $this->call([
            CountrySeeder::class,
            StateSeeder::class,
            UnitSeeder::class,
         
        ]);
    }
}
