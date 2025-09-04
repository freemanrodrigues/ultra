<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{SampleType, TestMaster, SampleTypeRate};

class SampleTypeRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get sample types and tests
        $sampleTypes = SampleType::all();
        $tests = TestMaster::where('status', 1)->get();

        if ($sampleTypes->isEmpty() || $tests->isEmpty()) {
            $this->command->warn('No sample types or tests found. Please run sample type and test seeders first.');
            return;
        }

        // Create sample type rates for each combination
        foreach ($sampleTypes as $sampleType) {
            foreach ($tests as $test) {
                // Create a rate based on sample type and test
                $baseRate = $test->default_price ?? 100; // Use test default price or 100 as base
                
                // Adjust rate based on sample type (example logic)
                $rateMultiplier = 1.0;
                switch ($sampleType->sample_type_name) {
                    case 'Oil':
                        $rateMultiplier = 1.2; // 20% premium for oil samples
                        break;
                    case 'Water':
                        $rateMultiplier = 0.8; // 20% discount for water samples
                        break;
                    case 'Soil':
                        $rateMultiplier = 1.1; // 10% premium for soil samples
                        break;
                    case 'Air':
                        $rateMultiplier = 1.5; // 50% premium for air samples
                        break;
                    default:
                        $rateMultiplier = 1.0; // Standard rate
                        break;
                }

                $finalRate = $baseRate * $rateMultiplier;

                SampleTypeRate::create([
                    'sample_type_id' => $sampleType->id,
                    'test_id' => $test->id,
                    'rate' => round($finalRate, 2),
                    'is_active' => true,
                    'notes' => "Rate for {$sampleType->sample_type_name} - {$test->test_name}",
                ]);
            }
        }

        $this->command->info('Sample type rates created successfully!');
    }
}