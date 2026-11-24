<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Interest;
use Illuminate\Database\Seeder;

class InterestsAndCitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Interests (Academic Fields/Departments)
        $interests = [
            ['name' => 'Computer Science', 'description' => 'Study of computers and computational systems'],
            ['name' => 'Software Engineering', 'description' => 'Design, development and maintenance of software systems'],
            ['name' => 'Information Technology', 'description' => 'Management and use of information systems'],
            ['name' => 'Electrical Engineering', 'description' => 'Study of electrical systems and electronics'],
            ['name' => 'Mechanical Engineering', 'description' => 'Design and manufacturing of mechanical systems'],
            ['name' => 'Civil Engineering', 'description' => 'Design and construction of infrastructure'],
            ['name' => 'Business Administration', 'description' => 'Management and administration of business operations'],
            ['name' => 'Medical & Health Sciences', 'description' => 'Study of medicine and healthcare'],
            ['name' => 'Law', 'description' => 'Study of legal systems and jurisprudence'],
            ['name' => 'Architecture', 'description' => 'Design of buildings and structures'],
            ['name' => 'Economics', 'description' => 'Study of production, distribution and consumption'],
            ['name' => 'Biotechnology', 'description' => 'Application of biological systems and organisms'],
            ['name' => 'Data Science', 'description' => 'Analysis and interpretation of complex data'],
            ['name' => 'Artificial Intelligence', 'description' => 'Development of intelligent machines and systems'],
            ['name' => 'Pharmacy', 'description' => 'Study of drugs and medications'],
        ];

        foreach ($interests as $interest) {
            Interest::create($interest);
        }

        // Seed Cities (Pakistan)
        $cities = [
            ['name' => 'Karachi', 'state_province' => 'Sindh', 'country' => 'Pakistan'],
            ['name' => 'Lahore', 'state_province' => 'Punjab', 'country' => 'Pakistan'],
            ['name' => 'Islamabad', 'state_province' => 'Islamabad Capital Territory', 'country' => 'Pakistan'],
            ['name' => 'Rawalpindi', 'state_province' => 'Punjab', 'country' => 'Pakistan'],
            ['name' => 'Faisalabad', 'state_province' => 'Punjab', 'country' => 'Pakistan'],
            ['name' => 'Multan', 'state_province' => 'Punjab', 'country' => 'Pakistan'],
            ['name' => 'Peshawar', 'state_province' => 'Khyber Pakhtunkhwa', 'country' => 'Pakistan'],
            ['name' => 'Quetta', 'state_province' => 'Balochistan', 'country' => 'Pakistan'],
            ['name' => 'Sialkot', 'state_province' => 'Punjab', 'country' => 'Pakistan'],
            ['name' => 'Gujranwala', 'state_province' => 'Punjab', 'country' => 'Pakistan'],
            ['name' => 'Hyderabad', 'state_province' => 'Sindh', 'country' => 'Pakistan'],
            ['name' => 'Bahawalpur', 'state_province' => 'Punjab', 'country' => 'Pakistan'],
            ['name' => 'Sargodha', 'state_province' => 'Punjab', 'country' => 'Pakistan'],
            ['name' => 'Abbottabad', 'state_province' => 'Khyber Pakhtunkhwa', 'country' => 'Pakistan'],
            ['name' => 'Sukkur', 'state_province' => 'Sindh', 'country' => 'Pakistan'],
        ];

        foreach ($cities as $city) {
            City::create($city);
        }
    }
}

