<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UniversityAdmissionSeeder extends Seeder
{
    /**
     * Run all university admission system seeders in the correct order.
     */
    public function run(): void
    {
        // $this->command->info('🌱 Starting University Admission System Seeder...');
        
        // // Step 1: Seed Interests and Cities
        // $this->command->info('📚 Seeding Interests and Cities...');
        // $this->call(InterestsAndCitiesSeeder::class);
        
        // // Step 2: Seed Universities
        // $this->command->info('🏛️  Seeding Universities with Programs...');
        // $this->call(UniversitySeeder::class);
        
        // Step 3: Seed Applications (optional, comment out if not needed)
        $this->command->info('🎓 Seeding Student Applications...');
        $this->call(ApplicationSeeder::class);
        
        $this->command->info('✅ University Admission System seeded successfully!');
        $this->command->line('');
        $this->command->info('Summary:');
        $this->command->line('  • 15 Interests (Academic Fields)');
        $this->command->line('  • 15 Cities (Pakistani Cities)');
        $this->command->line('  • 10 Universities with complete details');
        $this->command->line('  • 40+ Programs/Departments across universities');
        $this->command->line('  • 100 Student Applications (various statuses)');
        $this->command->line('');
        $this->command->info('You can now test the API endpoints! 🚀');
    }
}

