<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Interest;
use App\Models\University;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🎓 Starting Application Seeder...');

        // Clear existing applications
        $this->command->info('Clearing existing applications...');
        Application::query()->forceDelete();

        // Get necessary data
        $universities = University::all();
        $interests = Interest::all();
        $users = User::whereHas('roles', function($query) {
            $query->where('name', '!=', 'Super Admin')
                  ->where('name', '!=', 'School Admin');
        })->get();

        if ($universities->isEmpty()) {
            $this->command->error('❌ No universities found! Please run UniversitySeeder first.');
            return;
        }

        if ($interests->isEmpty()) {
            $this->command->error('❌ No interests found! Please run InterestsAndCitiesSeeder first.');
            return;
        }

        if ($users->isEmpty()) {
            $this->command->warn('⚠️  No users found. Creating sample students...');
            $users = $this->createSampleStudents();
        }

        // Sample data arrays
        $statuses = [
            'draft' => 15,        // 15% drafts
            'submitted' => 20,    // 20% submitted
            'under_review' => 25, // 25% under review
            'shortlisted' => 15,  // 15% shortlisted
            'accepted' => 15,     // 15% accepted
            'rejected' => 8,      // 8% rejected
            'withdrawn' => 2,     // 2% withdrawn
        ];

        $genders = ['male', 'female', 'other'];
        $nationalities = ['Pakistani', 'Indian', 'Bangladeshi', 'Afghan', 'Sri Lankan', 'Chinese', 'Turkish', 'Saudi Arabian'];
        
        $previousSchools = [
            'Karachi Grammar School',
            'Lahore College for Women',
            'Beaconhouse School System',
            'City School',
            'Army Public School',
            'Roots Millennium School',
            'Aitchison College',
            'LUMS',
            'NUST',
            'Government College University',
        ];

        $previousDegrees = [
            'High School Diploma',
            'A-Levels',
            'FSc Pre-Engineering',
            'FSc Pre-Medical',
            'ICS (Intermediate in Computer Science)',
            'Bachelor in Computer Science',
            'Bachelor in Engineering',
            'Bachelor in Business Administration',
        ];

        $rejectionReasons = [
            'Incomplete academic records',
            'GPA below minimum requirement',
            'Missing required documents',
            'Program capacity reached',
            'Did not meet entrance exam requirements',
            'Application submitted after deadline',
        ];

        $adminNotes = [
            'Excellent academic record, strong recommendation letters',
            'Good candidate with solid background',
            'Average performance, needs improvement in some areas',
            'Outstanding achievements in extracurricular activities',
            'Strong technical skills demonstrated',
            'Good communication skills, active in community service',
        ];

        $totalApplications = 100; // Create 100 sample applications
        $createdCount = 0;

        // Create applications with status distribution
        foreach ($statuses as $status => $percentage) {
            $count = (int) ceil(($percentage / 100) * $totalApplications);
            
            for ($i = 0; $i < $count; $i++) {
                try {
                    $user = $users->random();
                    $university = $universities->random();
                    $interest = $interests->random();
                    
                    // Generate realistic dates based on status
                    $createdAt = now()->subDays(rand(1, 90));
                    $submittedAt = null;
                    $reviewedAt = null;
                    $reviewedBy = null;
                    
                    if (!in_array($status, ['draft'])) {
                        $submittedAt = $createdAt->copy()->addDays(rand(0, 3));
                    }
                    
                    if (in_array($status, ['under_review', 'shortlisted', 'accepted', 'rejected'])) {
                        $reviewedAt = $submittedAt ? $submittedAt->copy()->addDays(rand(5, 20)) : null;
                        // Get a random admin/staff user as reviewer
                        $reviewer = User::whereHas('roles', function($query) {
                            $query->whereIn('name', ['Super Admin', 'School Admin']);
                        })->inRandomOrder()->first();
                        
                        $reviewedBy = $reviewer ? $reviewer->id : null;
                    }

                    $applicationData = [
                        'user_id' => $user->id,
                        'university_id' => $university->id,
                        'interest_id' => $interest->id,
                        'first_name' => $user->first_name ?? $this->generateFirstName(),
                        'last_name' => $user->last_name ?? $this->generateLastName(),
                        'email' => $user->email ?? $this->generateEmail(),
                        'phone' => $this->generatePhone(),
                        'date_of_birth' => now()->subYears(rand(18, 25))->format('Y-m-d'),
                        'gender' => $genders[array_rand($genders)],
                        'current_address' => $this->generateAddress(),
                        'permanent_address' => rand(0, 1) ? $this->generateAddress() : null,
                        'nationality' => $nationalities[array_rand($nationalities)],
                        'previous_school' => $previousSchools[array_rand($previousSchools)],
                        'previous_degree' => $previousDegrees[array_rand($previousDegrees)],
                        'previous_gpa' => rand(250, 400) / 100, // 2.50 to 4.00
                        'graduation_year' => rand(2018, 2024),
                        'status' => $status,
                        'application_message' => $this->generateApplicationMessage($interest->name ?? 'this program'),
                        'program_preference_reason' => $this->generatePreferenceReason($interest->name ?? 'this field'),
                        'emergency_contact_name' => $this->generateFirstName() . ' ' . $this->generateLastName(),
                        'emergency_contact_phone' => $this->generatePhone(),
                        'emergency_contact_relation' => ['Father', 'Mother', 'Guardian', 'Sibling'][array_rand(['Father', 'Mother', 'Guardian', 'Sibling'])],
                        'submitted_at' => $submittedAt,
                        'reviewed_at' => $reviewedAt,
                        'reviewed_by' => $reviewedBy,
                        'created_at' => $createdAt,
                        'updated_at' => $reviewedAt ?? $submittedAt ?? $createdAt,
                    ];

                    // Add status-specific fields
                    if ($status === 'rejected') {
                        $applicationData['rejection_reason'] = $rejectionReasons[array_rand($rejectionReasons)];
                        $applicationData['admin_notes'] = 'Application reviewed and rejected. ' . $applicationData['rejection_reason'];
                    } elseif (in_array($status, ['accepted', 'shortlisted'])) {
                        $applicationData['admin_notes'] = $adminNotes[array_rand($adminNotes)];
                    }

                    Application::create($applicationData);
                    $createdCount++;

                } catch (\Exception $e) {
                    $this->command->warn("Failed to create application: " . $e->getMessage());
                }
            }
            
            $this->command->info("  ✓ Created {$count} {$status} applications");
        }

        $this->command->info("✅ Successfully created {$createdCount} applications!");
        
        // Display summary
        $this->displaySummary();
    }

    /**
     * Create sample students if none exist
     */
    private function createSampleStudents()
    {
        $this->command->info('Creating 20 sample students...');
        
        $students = [];
        
        for ($i = 1; $i <= 20; $i++) {
            $firstName = $this->generateFirstName();
            $lastName = $this->generateLastName();
            
            $students[] = User::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => strtolower($firstName . '.' . $lastName . $i . '@student.edu'),
                'mobile' => $this->generatePhone(),
                'password' => bcrypt('password'),
                'gender' => ['male', 'female'][array_rand(['male', 'female'])],
                'dob' => now()->subYears(rand(18, 25))->format('Y-m-d'),
                'current_address' => $this->generateAddress(),
                'status' => 1,
            ]);
        }
        
        return collect($students);
    }

    /**
     * Generate random first name
     */
    private function generateFirstName()
    {
        $names = [
            'Ahmed', 'Ali', 'Hassan', 'Hussain', 'Muhammad', 'Omar', 'Usman', 'Bilal',
            'Fatima', 'Ayesha', 'Zainab', 'Mariam', 'Sara', 'Laiba', 'Hira', 'Amna',
            'Abdullah', 'Ibrahim', 'Yousuf', 'Hamza', 'Faisal', 'Arslan', 'Shahzaib',
            'Sana', 'Mahnoor', 'Iqra', 'Rabia', 'Nimra', 'Sidra', 'Aliza'
        ];
        return $names[array_rand($names)];
    }

    /**
     * Generate random last name
     */
    private function generateLastName()
    {
        $names = [
            'Khan', 'Ahmed', 'Ali', 'Hassan', 'Hussain', 'Sheikh', 'Malik',
            'Raza', 'Shah', 'Akhtar', 'Butt', 'Qureshi', 'Siddiqui', 'Mirza',
            'Hashmi', 'Javed', 'Rashid', 'Tariq', 'Nadeem', 'Aslam'
        ];
        return $names[array_rand($names)];
    }

    /**
     * Generate email
     */
    private function generateEmail()
    {
        return strtolower(Str::random(8) . '@student.edu');
    }

    /**
     * Generate phone number
     */
    private function generatePhone()
    {
        return '+92-3' . rand(0, 9) . rand(0, 9) . '-' . rand(1000000, 9999999);
    }

    /**
     * Generate address
     */
    private function generateAddress()
    {
        $areas = [
            'DHA Phase 5, Karachi',
            'Gulberg III, Lahore',
            'F-7 Markaz, Islamabad',
            'Saddar Town, Karachi',
            'Model Town, Lahore',
            'G-11, Islamabad',
            'Clifton Block 2, Karachi',
            'Johar Town, Lahore',
            'Bahria Town, Rawalpindi',
            'North Nazimabad, Karachi',
        ];
        
        $streetNumber = rand(1, 200);
        $area = $areas[array_rand($areas)];
        
        return "House No. {$streetNumber}, Street {rand(1, 50)}, {$area}, Pakistan";
    }

    /**
     * Generate application message
     */
    private function generateApplicationMessage($programName)
    {
        $messages = [
            "I am writing to express my strong interest in pursuing {$programName} at your esteemed institution. With a solid academic background and genuine passion for this field, I believe I would be an excellent fit for your program.",
            
            "I am excited to apply for the {$programName} program. My academic journey has prepared me well for this next step, and I am eager to contribute to your university's diverse academic community.",
            
            "Having achieved strong academic results and gained relevant experience, I am confident that the {$programName} program at your university is the perfect next step in my educational journey.",
            
            "It has been my long-standing goal to study {$programName} at a prestigious institution like yours. I am committed to academic excellence and look forward to the opportunity to learn from distinguished faculty.",
            
            "I am passionate about {$programName} and believe that your university's program offers the ideal environment for my academic and professional growth. I am ready to contribute positively to campus life.",
        ];
        
        return $messages[array_rand($messages)];
    }

    /**
     * Generate preference reason
     */
    private function generatePreferenceReason($fieldName)
    {
        $reasons = [
            "I have always been fascinated by {$fieldName} and have consistently excelled in related subjects throughout my academic career.",
            
            "My interest in {$fieldName} stems from both academic curiosity and a desire to make meaningful contributions to this field in the future.",
            
            "I chose {$fieldName} because it aligns perfectly with my career goals and I believe it offers excellent opportunities for professional growth.",
            
            "The field of {$fieldName} has always intrigued me, and I am excited about the prospect of deepening my knowledge and skills in this area.",
            
            "I am drawn to {$fieldName} due to its relevance in today's world and the potential it offers for innovative problem-solving and research.",
        ];
        
        return $reasons[array_rand($reasons)];
    }

    /**
     * Display summary of created applications
     */
    private function displaySummary()
    {
        $this->command->line('');
        $this->command->info('📊 Application Statistics:');
        $this->command->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        
        $statuses = [
            'draft' => '📝',
            'submitted' => '📤',
            'under_review' => '👁️',
            'shortlisted' => '⭐',
            'accepted' => '✅',
            'rejected' => '❌',
            'withdrawn' => '🔙',
        ];
        
        foreach ($statuses as $status => $icon) {
            $count = Application::where('status', $status)->count();
            $statusLabel = str_pad(ucfirst(str_replace('_', ' ', $status)), 15);
            $this->command->line("  {$icon} {$statusLabel}: {$count}");
        }
        
        $total = Application::count();
        $this->command->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info("  📊 Total Applications: {$total}");
        $this->command->line('');
        
        // Additional stats
        $uniqueStudents = Application::distinct('user_id')->count('user_id');
        $uniqueUniversities = Application::distinct('university_id')->count('university_id');
        
        $this->command->info('📈 Additional Statistics:');
        $this->command->line("  • Unique Students: {$uniqueStudents}");
        $this->command->line("  • Universities Applied To: {$uniqueUniversities}");
        $this->command->line('');
        
        $this->command->info('🎉 Application seeding completed successfully!');
        $this->command->line('');
        $this->command->info('💡 Test the APIs:');
        $this->command->line('  • GET /api/dashboard - View dashboard with stats');
        $this->command->line('  • GET /api/applications/my-applications - View grouped applications');
        $this->command->line('');
    }
}
















