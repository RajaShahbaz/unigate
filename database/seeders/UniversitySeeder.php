<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Interest;
use App\Models\University;
use Illuminate\Database\Seeder;

class UniversitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get cities and interests
        $lahore = City::where('name', 'Lahore')->first();
        $karachi = City::where('name', 'Karachi')->first();
        $islamabad = City::where('name', 'Islamabad')->first();
        $rawalpindi = City::where('name', 'Rawalpindi')->first();
        $faisalabad = City::where('name', 'Faisalabad')->first();
        $multan = City::where('name', 'Multan')->first();
        $peshawar = City::where('name', 'Peshawar')->first();

        $cs = Interest::where('name', 'Computer Science')->first();
        $se = Interest::where('name', 'Software Engineering')->first();
        $it = Interest::where('name', 'Information Technology')->first();
        $ee = Interest::where('name', 'Electrical Engineering')->first();
        $me = Interest::where('name', 'Mechanical Engineering')->first();
        $ce = Interest::where('name', 'Civil Engineering')->first();
        $bba = Interest::where('name', 'Business Administration')->first();
        $medical = Interest::where('name', 'Medical & Health Sciences')->first();
        $law = Interest::where('name', 'Law')->first();
        $architecture = Interest::where('name', 'Architecture')->first();
        $economics = Interest::where('name', 'Economics')->first();
        $biotech = Interest::where('name', 'Biotechnology')->first();
        $ds = Interest::where('name', 'Data Science')->first();
        $ai = Interest::where('name', 'Artificial Intelligence')->first();

        // Universities Data
        $universities = [
            [
                'name' => 'FAST National University of Computer and Emerging Sciences',
                'description' => 'FAST-NUCES is a leading technology university in Pakistan, known for excellence in computer science and engineering education.',
                'city_id' => $lahore->id ?? 2,
                'address' => 'Faisal Town, Lahore',
                'phone' => '+92-42-111-128-128',
                'email' => 'info@nu.edu.pk',
                'website' => 'https://www.nu.edu.pk',
                'type' => 'private',
                'established_year' => 2000,
                'facilities' => 'Library, Computer Labs, Hostel, Sports Complex, Cafeteria, Transportation',
                'ranking' => 4.5,
                'tuition_fee_range_min' => 200000,
                'tuition_fee_range_max' => 500000,
                'hostel_available' => true,
                'scholarship_available' => true,
                'admission_criteria' => 'Entry test (NAT/HAT) and interview required. Minimum 60% marks in FSc/A-Levels.',
                'accreditation' => 'HEC Recognized, PEC Accredited',
                'status' => 1,
                'programs' => [
                    ['interest' => $cs, 'fee' => 400000, 'duration' => 4, 'details' => '4-year BS Computer Science program'],
                    ['interest' => $se, 'fee' => 450000, 'duration' => 4, 'details' => '4-year BS Software Engineering program'],
                    ['interest' => $ai, 'fee' => 480000, 'duration' => 4, 'details' => '4-year BS Artificial Intelligence program'],
                    ['interest' => $ds, 'fee' => 470000, 'duration' => 4, 'details' => '4-year BS Data Science program'],
                ]
            ],
            [
                'name' => 'NUST (National University of Sciences and Technology)',
                'description' => 'NUST is one of Pakistan\'s top-ranked universities, offering world-class education in engineering, sciences, and management.',
                'city_id' => $islamabad->id ?? 3,
                'address' => 'H-12, Islamabad',
                'phone' => '+92-51-9085-5000',
                'email' => 'info@nust.edu.pk',
                'website' => 'https://www.nust.edu.pk',
                'type' => 'semi-government',
                'established_year' => 1991,
                'facilities' => 'State-of-the-art Labs, Library, Hostel, Sports Facilities, Medical Center, Innovation Center',
                'ranking' => 4.8,
                'tuition_fee_range_min' => 250000,
                'tuition_fee_range_max' => 600000,
                'hostel_available' => true,
                'scholarship_available' => true,
                'admission_criteria' => 'NET (NUST Entry Test) required. Minimum 60% in FSc for engineering programs.',
                'accreditation' => 'HEC Recognized, PEC Accredited, International Accreditations',
                'status' => 1,
                'programs' => [
                    ['interest' => $ee, 'fee' => 500000, 'duration' => 4, 'details' => '4-year BS Electrical Engineering'],
                    ['interest' => $me, 'fee' => 480000, 'duration' => 4, 'details' => '4-year BS Mechanical Engineering'],
                    ['interest' => $ce, 'fee' => 450000, 'duration' => 4, 'details' => '4-year BS Civil Engineering'],
                    ['interest' => $cs, 'fee' => 520000, 'duration' => 4, 'details' => '4-year BS Computer Science'],
                    ['interest' => $bba, 'fee' => 400000, 'duration' => 4, 'details' => '4-year BBA program'],
                ]
            ],
            [
                'name' => 'University of Engineering and Technology (UET) Lahore',
                'description' => 'UET Lahore is Pakistan\'s oldest and most prestigious engineering university with a rich history of excellence.',
                'city_id' => $lahore->id ?? 2,
                'address' => 'GT Road, Lahore',
                'phone' => '+92-42-99029202',
                'email' => 'info@uet.edu.pk',
                'website' => 'https://www.uet.edu.pk',
                'type' => 'public',
                'established_year' => 1921,
                'facilities' => 'Engineering Labs, Library, Hostel, Sports Complex, Health Center, Workshops',
                'ranking' => 4.6,
                'tuition_fee_range_min' => 50000,
                'tuition_fee_range_max' => 150000,
                'hostel_available' => true,
                'scholarship_available' => true,
                'admission_criteria' => 'ECAT (Engineering College Admission Test). Minimum 60% in FSc Pre-Engineering.',
                'accreditation' => 'HEC Recognized, PEC Accredited',
                'status' => 1,
                'programs' => [
                    ['interest' => $ee, 'fee' => 120000, 'duration' => 4, 'details' => '4-year BS Electrical Engineering'],
                    ['interest' => $me, 'fee' => 110000, 'duration' => 4, 'details' => '4-year BS Mechanical Engineering'],
                    ['interest' => $ce, 'fee' => 100000, 'duration' => 4, 'details' => '4-year BS Civil Engineering'],
                    ['interest' => $cs, 'fee' => 130000, 'duration' => 4, 'details' => '4-year BS Computer Science'],
                    ['interest' => $architecture, 'fee' => 140000, 'duration' => 5, 'details' => '5-year BS Architecture'],
                ]
            ],
            [
                'name' => 'LUMS (Lahore University of Management Sciences)',
                'description' => 'LUMS is a leading institution for business, economics, and social sciences in Pakistan.',
                'city_id' => $lahore->id ?? 2,
                'address' => 'DHA, Lahore Cantt',
                'phone' => '+92-42-111-11-5867',
                'email' => 'admissions@lums.edu.pk',
                'website' => 'https://www.lums.edu.pk',
                'type' => 'private',
                'established_year' => 1984,
                'facilities' => 'Modern Classrooms, Library, Hostel, Sports Facilities, Innovation Lab, Career Services',
                'ranking' => 4.7,
                'tuition_fee_range_min' => 800000,
                'tuition_fee_range_max' => 1200000,
                'hostel_available' => true,
                'scholarship_available' => true,
                'admission_criteria' => 'SAT/LUMS Admission Test. Merit-based selection with interviews.',
                'accreditation' => 'HEC Recognized, International Accreditations',
                'status' => 1,
                'programs' => [
                    ['interest' => $bba, 'fee' => 1000000, 'duration' => 4, 'details' => '4-year BBA program'],
                    ['interest' => $economics, 'fee' => 950000, 'duration' => 4, 'details' => '4-year BS Economics'],
                    ['interest' => $cs, 'fee' => 1100000, 'duration' => 4, 'details' => '4-year BS Computer Science'],
                    ['interest' => $law, 'fee' => 900000, 'duration' => 5, 'details' => '5-year LLB program'],
                ]
            ],
            [
                'name' => 'NED University of Engineering and Technology',
                'description' => 'NED University is one of the oldest engineering universities in Pakistan, located in Karachi.',
                'city_id' => $karachi->id ?? 1,
                'address' => 'University Road, Karachi',
                'phone' => '+92-21-99261261',
                'email' => 'info@neduet.edu.pk',
                'website' => 'https://www.neduet.edu.pk',
                'type' => 'public',
                'established_year' => 1922,
                'facilities' => 'Engineering Labs, Library, Computer Center, Sports Facilities, Cafeteria',
                'ranking' => 4.4,
                'tuition_fee_range_min' => 60000,
                'tuition_fee_range_max' => 180000,
                'hostel_available' => false,
                'scholarship_available' => true,
                'admission_criteria' => 'NED Entry Test required. Minimum 60% in FSc Pre-Engineering.',
                'accreditation' => 'HEC Recognized, PEC Accredited',
                'status' => 1,
                'programs' => [
                    ['interest' => $ee, 'fee' => 150000, 'duration' => 4, 'details' => '4-year BS Electrical Engineering'],
                    ['interest' => $me, 'fee' => 140000, 'duration' => 4, 'details' => '4-year BS Mechanical Engineering'],
                    ['interest' => $ce, 'fee' => 130000, 'duration' => 4, 'details' => '4-year BS Civil Engineering'],
                    ['interest' => $cs, 'fee' => 160000, 'duration' => 4, 'details' => '4-year BS Computer Science'],
                    ['interest' => $biotech, 'fee' => 155000, 'duration' => 4, 'details' => '4-year BS Biotechnology'],
                ]
            ],
            [
                'name' => 'COMSATS University Islamabad',
                'description' => 'COMSATS is a multi-campus university offering programs in sciences, engineering, and IT.',
                'city_id' => $islamabad->id ?? 3,
                'address' => 'Park Road, Islamabad',
                'phone' => '+92-51-9247000',
                'email' => 'info@comsats.edu.pk',
                'website' => 'https://www.comsats.edu.pk',
                'type' => 'public',
                'established_year' => 1998,
                'facilities' => 'Labs, Library, Hostel, Sports Complex, Medical Center, IT Center',
                'ranking' => 4.3,
                'tuition_fee_range_min' => 80000,
                'tuition_fee_range_max' => 200000,
                'hostel_available' => true,
                'scholarship_available' => true,
                'admission_criteria' => 'Entry test or SAT/NAT. Minimum 50% in FSc/A-Levels.',
                'accreditation' => 'HEC Recognized',
                'status' => 1,
                'programs' => [
                    ['interest' => $cs, 'fee' => 180000, 'duration' => 4, 'details' => '4-year BS Computer Science'],
                    ['interest' => $se, 'fee' => 190000, 'duration' => 4, 'details' => '4-year BS Software Engineering'],
                    ['interest' => $it, 'fee' => 170000, 'duration' => 4, 'details' => '4-year BS Information Technology'],
                    ['interest' => $ee, 'fee' => 185000, 'duration' => 4, 'details' => '4-year BS Electrical Engineering'],
                    ['interest' => $ds, 'fee' => 195000, 'duration' => 4, 'details' => '4-year BS Data Science'],
                ]
            ],
            [
                'name' => 'University of Central Punjab (UCP)',
                'description' => 'UCP is a private university in Lahore offering diverse programs in multiple disciplines.',
                'city_id' => $lahore->id ?? 2,
                'address' => 'Khayaban-e-Jinnah Road, Johar Town, Lahore',
                'phone' => '+92-42-111-000-827',
                'email' => 'info@ucp.edu.pk',
                'website' => 'https://www.ucp.edu.pk',
                'type' => 'private',
                'established_year' => 2002,
                'facilities' => 'Modern Campus, Library, Computer Labs, Sports Complex, Cafeteria, Transport',
                'ranking' => 4.0,
                'tuition_fee_range_min' => 180000,
                'tuition_fee_range_max' => 450000,
                'hostel_available' => false,
                'scholarship_available' => true,
                'admission_criteria' => 'University entry test or NAT. Minimum 50% in intermediate.',
                'accreditation' => 'HEC Recognized',
                'status' => 1,
                'programs' => [
                    ['interest' => $cs, 'fee' => 350000, 'duration' => 4, 'details' => '4-year BS Computer Science'],
                    ['interest' => $bba, 'fee' => 320000, 'duration' => 4, 'details' => '4-year BBA program'],
                    ['interest' => $law, 'fee' => 380000, 'duration' => 5, 'details' => '5-year LLB program'],
                    ['interest' => $architecture, 'fee' => 400000, 'duration' => 5, 'details' => '5-year BS Architecture'],
                ]
            ],
            [
                'name' => 'University of Faisalabad',
                'description' => 'A chartered university offering quality education in various disciplines.',
                'city_id' => $faisalabad->id ?? 5,
                'address' => 'Sargodha Road, Faisalabad',
                'phone' => '+92-41-8721501',
                'email' => 'info@tuf.edu.pk',
                'website' => 'https://www.tuf.edu.pk',
                'type' => 'private',
                'established_year' => 2002,
                'facilities' => 'Library, Labs, Sports Ground, Cafeteria, Medical Center',
                'ranking' => 3.8,
                'tuition_fee_range_min' => 120000,
                'tuition_fee_range_max' => 300000,
                'hostel_available' => true,
                'scholarship_available' => true,
                'admission_criteria' => 'Entry test and intermediate marks. Minimum 45% required.',
                'accreditation' => 'HEC Recognized',
                'status' => 1,
                'programs' => [
                    ['interest' => $cs, 'fee' => 250000, 'duration' => 4, 'details' => '4-year BS Computer Science'],
                    ['interest' => $bba, 'fee' => 220000, 'duration' => 4, 'details' => '4-year BBA program'],
                    ['interest' => $biotech, 'fee' => 240000, 'duration' => 4, 'details' => '4-year BS Biotechnology'],
                ]
            ],
            [
                'name' => 'Bahria University Islamabad',
                'description' => 'Bahria University is a multi-campus university with focus on engineering and computer sciences.',
                'city_id' => $islamabad->id ?? 3,
                'address' => 'Shangrila Road, Sector E-8, Islamabad',
                'phone' => '+92-51-9260002',
                'email' => 'info@bahria.edu.pk',
                'website' => 'https://www.bahria.edu.pk',
                'type' => 'private',
                'established_year' => 2000,
                'facilities' => 'Modern Labs, Library, Hostel, Sports Complex, Medical Center, IT Infrastructure',
                'ranking' => 4.2,
                'tuition_fee_range_min' => 220000,
                'tuition_fee_range_max' => 500000,
                'hostel_available' => true,
                'scholarship_available' => true,
                'admission_criteria' => 'Bahria University entry test. Minimum 50% in FSc/A-Levels.',
                'accreditation' => 'HEC Recognized, PEC Accredited',
                'status' => 1,
                'programs' => [
                    ['interest' => $cs, 'fee' => 380000, 'duration' => 4, 'details' => '4-year BS Computer Science'],
                    ['interest' => $se, 'fee' => 400000, 'duration' => 4, 'details' => '4-year BS Software Engineering'],
                    ['interest' => $ee, 'fee' => 390000, 'duration' => 4, 'details' => '4-year BS Electrical Engineering'],
                    ['interest' => $bba, 'fee' => 350000, 'duration' => 4, 'details' => '4-year BBA program'],
                    ['interest' => $ai, 'fee' => 420000, 'duration' => 4, 'details' => '4-year BS Artificial Intelligence'],
                ]
            ],
            [
                'name' => 'University of Peshawar',
                'description' => 'One of the oldest universities in Pakistan, offering diverse academic programs.',
                'city_id' => $peshawar->id ?? 7,
                'address' => 'University Campus, Peshawar',
                'phone' => '+92-91-9216701',
                'email' => 'info@uop.edu.pk',
                'website' => 'https://www.uop.edu.pk',
                'type' => 'public',
                'established_year' => 1950,
                'facilities' => 'Library, Labs, Hostel, Sports Facilities, Medical Center',
                'ranking' => 4.1,
                'tuition_fee_range_min' => 40000,
                'tuition_fee_range_max' => 120000,
                'hostel_available' => true,
                'scholarship_available' => true,
                'admission_criteria' => 'Merit-based admission. Minimum 50% in intermediate.',
                'accreditation' => 'HEC Recognized',
                'status' => 1,
                'programs' => [
                    ['interest' => $cs, 'fee' => 100000, 'duration' => 4, 'details' => '4-year BS Computer Science'],
                    ['interest' => $bba, 'fee' => 90000, 'duration' => 4, 'details' => '4-year BBA program'],
                    ['interest' => $economics, 'fee' => 80000, 'duration' => 4, 'details' => '4-year BS Economics'],
                ]
            ],
        ];

        // Create universities and attach programs
        foreach ($universities as $universityData) {
            $programs = $universityData['programs'] ?? [];
            unset($universityData['programs']);

            $university = University::create($universityData);

            // Attach programs/interests with pivot data
            if (!empty($programs)) {
                foreach ($programs as $program) {
                    if ($program['interest']) {
                        $university->interests()->attach($program['interest']->id, [
                            'fee' => $program['fee'],
                            'duration_years' => $program['duration'],
                            'program_details' => $program['details'],
                        ]);
                    }
                }
            }
        }

        $this->command->info('Universities seeded successfully!');
    }
}

