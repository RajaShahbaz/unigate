<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\StudentDocument;
use App\Models\User;
use App\Models\UserEducation;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Get application dashboard with statistics, profile completion and tips
     */
    public function getDashboard(Request $request)
    {
        try {
            $user = Auth::user();

            // Get application statistics
            $applications = Application::where('user_id', $user->id)->get();

            $statistics = [
                'total_applications' => $applications->count(),
                'pending' => $applications->whereIn('status', ['submitted', 'documents_pending', 'under_review'])->count(),
                'accepted' => $applications->where('status', 'accepted')->count(),
                'rejected' => $applications->where('status', 'rejected')->count(),
                'draft' => $applications->where('status', 'draft')->count(),
                'shortlisted' => $applications->where('status', 'shortlisted')->count(),
                'withdrawn' => $applications->where('status', 'withdrawn')->count(),
            ];

            // Calculate profile completion percentage
            $profileCompletion = $this->calculateProfileCompletion($user);

            // Generate improvement tips
            $tips = $this->generateImprovementTips($user, $profileCompletion['missing_fields']);

            $dashboardData = [
                'application_statistics' => $statistics,
                'profile_completion' => [
                    'percentage' => $profileCompletion['percentage'],
                    'completed_sections' => $profileCompletion['completed_sections'],
                    'total_sections' => $profileCompletion['total_sections'],
                    'details' => $profileCompletion['details'],
                ],
                'improvement_tips' => $tips,
                'recent_applications' => $applications->sortByDesc('updated_at')->take(5)->map(function($app) {
                    return [
                        'id' => $app->id,
                        'application_number' => $app->application_number,
                        'university_name' => $app->university->name ?? 'N/A',
                        'program_name' => $app->interest->name ?? 'N/A',
                        'status' => $app->status,
                        'submitted_at' => $app->submitted_at ? $app->submitted_at->format('Y-m-d H:i:s') : null,
                        'updated_at' => $app->updated_at->format('Y-m-d H:i:s'),
                    ];
                })->values(),
            ];

            return ResponseService::successResponse('Dashboard data retrieved successfully', $dashboardData);
        } catch (\Exception $e) {
            return ResponseService::errorResponse($e->getMessage());
        }
    }

    /**
     * Calculate profile completion percentage
     */
    private function calculateProfileCompletion($user)
    {
        $sections = [
            'basic_info' => [
                'weight' => 20,
                'fields' => ['first_name', 'last_name', 'email', 'mobile', 'gender', 'dob'],
                'completed' => 0,
                'total' => 6,
            ],
            'address_info' => [
                'weight' => 10,
                'fields' => ['current_address', 'permanent_address'],
                'completed' => 0,
                'total' => 2,
            ],
            'profile_photo' => [
                'weight' => 10,
                'fields' => ['image'],
                'completed' => 0,
                'total' => 1,
            ],
            'education' => [
                'weight' => 25,
                'completed' => 0,
                'total' => 1, // At least one education record
            ],
            'interests' => [
                'weight' => 15,
                'completed' => 0,
                'total' => 1, // At least one interest
            ],
            'documents' => [
                'weight' => 20,
                'completed' => 0,
                'total' => 3, // At least 3 documents (transcript, ID, photo)
            ],
        ];

        $missing_fields = [];

        // Check basic info
        foreach ($sections['basic_info']['fields'] as $field) {
            if (!empty($user->$field)) {
                $sections['basic_info']['completed']++;
            } else {
                $missing_fields[] = ucwords(str_replace('_', ' ', $field));
            }
        }

        // Check address info
        foreach ($sections['address_info']['fields'] as $field) {
            if (!empty($user->$field)) {
                $sections['address_info']['completed']++;
            } else {
                $missing_fields[] = ucwords(str_replace('_', ' ', $field));
            }
        }

        // Check profile photo
        if (!empty($user->image)) {
            $sections['profile_photo']['completed'] = 1;
        } else {
            $missing_fields[] = 'Profile Photo';
        }

        // Check education records
        $educationCount = UserEducation::where('user_id', $user->id)->count();
        if ($educationCount > 0) {
            $sections['education']['completed'] = 1;
        } else {
            $missing_fields[] = 'Education History';
        }

        // Check interests (assuming there's a user_interests table or relation)
        $interestsCount = DB::table('user_interests')->where('user_id', $user->id)->count();
        if ($interestsCount > 0) {
            $sections['interests']['completed'] = 1;
        } else {
            $missing_fields[] = 'Field of Interest';
        }

        // Check documents
        $documentsCount = StudentDocument::where('user_id', $user->id)->count();
        $sections['documents']['completed'] = min($documentsCount, 3);
        if ($documentsCount < 3) {
            $missing_fields[] = 'Required Documents (' . $documentsCount . '/3)';
        }

        // Calculate weighted percentage
        $totalPercentage = 0;
        $completedSections = 0;
        $details = [];

        foreach ($sections as $sectionName => $section) {
            $sectionPercentage = ($section['completed'] / $section['total']) * 100;
            $weightedPercentage = ($sectionPercentage * $section['weight']) / 100;
            $totalPercentage += $weightedPercentage;

            if ($sectionPercentage == 100) {
                $completedSections++;
            }

            $details[] = [
                'section' => ucwords(str_replace('_', ' ', $sectionName)),
                'percentage' => round($sectionPercentage, 2),
                'completed' => $section['completed'],
                'total' => $section['total'],
                'weight' => $section['weight'],
            ];
        }

        return [
            'percentage' => round($totalPercentage, 2),
            'completed_sections' => $completedSections,
            'total_sections' => count($sections),
            'details' => $details,
            'missing_fields' => $missing_fields,
        ];
    }

    /**
     * Generate improvement tips based on profile completion
     */
    private function generateImprovementTips($user, $missingFields)
    {
        $tips = [];

        // Check for missing fields and generate specific tips
        if (in_array('Profile Photo', $missingFields)) {
            $tips[] = [
                'title' => 'Upload Profile Photo',
                'description' => 'Add a professional profile photo to make your application stand out. Universities appreciate seeing the face behind the application.',
                'priority' => 'high',
                'action' => 'upload_photo',
                'icon' => '📸',
            ];
        }

        if (in_array('Education History', $missingFields)) {
            $tips[] = [
                'title' => 'Complete Education History',
                'description' => 'Add your academic background including high school and any previous degrees. This is essential for university applications.',
                'priority' => 'high',
                'action' => 'add_education',
                'icon' => '🎓',
            ];
        }

        if (in_array('Field of Interest', $missingFields)) {
            $tips[] = [
                'title' => 'Select Your Interests',
                'description' => 'Choose your fields of interest to get personalized university recommendations and better match with suitable programs.',
                'priority' => 'high',
                'action' => 'add_interests',
                'icon' => '🎯',
            ];
        }

        if (in_array('Current Address', $missingFields) || in_array('Permanent Address', $missingFields)) {
            $tips[] = [
                'title' => 'Complete Address Information',
                'description' => 'Add your current and permanent address. This information is required by most universities for correspondence.',
                'priority' => 'medium',
                'action' => 'update_address',
                'icon' => '🏠',
            ];
        }

        // Check for incomplete documents
        $documentsCount = StudentDocument::where('user_id', $user->id)->count();
        if ($documentsCount < 3) {
            $tips[] = [
                'title' => 'Upload Required Documents',
                'description' => 'Upload important documents like transcripts, ID proof, and certificates. Having these ready speeds up your application process.',
                'priority' => 'high',
                'action' => 'upload_documents',
                'icon' => '📄',
            ];
        }

        // Check for draft applications
        $draftApplications = Application::where('user_id', $user->id)
            ->where('status', 'draft')
            ->count();

        if ($draftApplications > 0) {
            $tips[] = [
                'title' => 'Complete Draft Applications',
                'description' => "You have {$draftApplications} draft application(s). Complete and submit them to increase your admission chances.",
                'priority' => 'medium',
                'action' => 'complete_drafts',
                'icon' => '✍️',
            ];
        }

        // General tips based on application count
        $totalApplications = Application::where('user_id', $user->id)
            ->whereNotNull('submitted_at')
            ->count();

        if ($totalApplications == 0) {
            $tips[] = [
                'title' => 'Start Your First Application',
                'description' => 'Browse universities and submit your first application. The sooner you apply, the better your chances of securing admission.',
                'priority' => 'high',
                'action' => 'browse_universities',
                'icon' => '🚀',
            ];
        } elseif ($totalApplications < 3) {
            $tips[] = [
                'title' => 'Apply to More Universities',
                'description' => 'Consider applying to more universities to increase your chances of acceptance. Experts recommend applying to at least 3-5 institutions.',
                'priority' => 'medium',
                'action' => 'browse_universities',
                'icon' => '📚',
            ];
        }

        // Add profile completion tip if profile is not complete
        $profileCompletion = $this->calculateProfileCompletion($user);
        if ($profileCompletion['percentage'] < 100) {
            $tips[] = [
                'title' => 'Complete Your Profile',
                'description' => "Your profile is {$profileCompletion['percentage']}% complete. A complete profile improves your application credibility.",
                'priority' => $profileCompletion['percentage'] < 50 ? 'high' : 'medium',
                'action' => 'complete_profile',
                'icon' => '👤',
            ];
        }

        // If profile is complete, give positive reinforcement
        if (empty($tips)) {
            $tips[] = [
                'title' => 'Great Job!',
                'description' => 'Your profile is complete and looks great! Keep exploring universities and submit applications to your dream institutions.',
                'priority' => 'info',
                'action' => 'browse_universities',
                'icon' => '🌟',
            ];
        }

        // Sort tips by priority
        usort($tips, function($a, $b) {
            $priorityOrder = ['high' => 1, 'medium' => 2, 'low' => 3, 'info' => 4];
            return $priorityOrder[$a['priority']] - $priorityOrder[$b['priority']];
        });

        return $tips;
    }
}

