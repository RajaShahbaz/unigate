<?php

namespace Database\Seeders;

use App\Models\University;
use App\Models\UniversityAdmin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UniversityAdminSeeder extends Seeder
{
    /**
     * Run the database seeds to create university admin accounts.
     */
    public function run(): void
    {
        // Get all universities
        $universities = University::all();

        if ($universities->isEmpty()) {
            $this->command->warn('No universities found in the database. Please create universities first.');
            return;
        }

        foreach ($universities as $university) {
            // Check if admin already exists for this university
            if (UniversityAdmin::where('university_id', $university->id)->exists()) {
                $this->command->info("Admin already exists for {$university->name}");
                continue;
            }

            // Create admin for each university
            UniversityAdmin::create([
                'university_id' => $university->id,
                'name' => 'Admin - ' . $university->name,
                'email' => 'admin@' . str_replace(' ', '', strtolower($university->name)) . '.edu',
                'password' => Hash::make('password123'),
                'phone' => '+1234567890',
                'position' => 'Admissions Director',
                'status' => true,
            ]);

            $this->command->info("Created admin for {$university->name}");
        }

        $this->command->info('University admin seeding completed!');
        $this->command->line('');
        $this->command->line('==============================================');
        $this->command->line('Default Login Credentials:');
        $this->command->line('==============================================');
        
        $firstAdmin = UniversityAdmin::first();
        if ($firstAdmin) {
            $this->command->line("Email: {$firstAdmin->email}");
            $this->command->line('Password: password123');
            $this->command->line('Portal URL: ' . url('/university/login'));
        }
        $this->command->line('==============================================');
    }
}

