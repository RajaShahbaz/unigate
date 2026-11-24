<?php

namespace Database\Seeders;

use App\Models\ApplicationPackage;
use Illuminate\Database\Seeder;

class ApplicationPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if package already exists
        $existingPackage = ApplicationPackage::where('name', 'Application Submission Package')->first();
        
        if (!$existingPackage) {
            ApplicationPackage::create([
                'name' => 'Application Submission Package',
                'description' => 'Required package to submit university applications',
                'price' => 10000.00,
                'currency' => 'PKR',
                'status' => true,
            ]);
            
            $this->command->info('Application package created successfully!');
        } else {
            $this->command->info('Application package already exists.');
        }
    }
}
