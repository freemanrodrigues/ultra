<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserDiscountLimit;
use App\Models\User;

class UserDiscountLimitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        
        foreach ($users as $user) {
            // Set discount limits based on user role
            $maxDiscount = match($user->user_role) {
                1 => 50,  // SuperAdmin - 50%
                2 => 10,  // DataEntry - 10%
                3 => 15,  // DataEntry Lead - 15%
                4 => 20,  // Sales - 20%
                5 => 25,  // KAM - 25%
                6 => 5,   // Credit Controller - 5%
                7 => 30,  // Lab Account Head - 30%
                8 => 20,  // Credit Controller Head - 20%
                default => 5, // Others - 5%
            };
            
            UserDiscountLimit::create([
                'user_id' => $user->id,
                'max_discount_percent' => $maxDiscount,
                'max_discount_amount' => null,
                'is_active' => true,
            ]);
        }
    }
}

