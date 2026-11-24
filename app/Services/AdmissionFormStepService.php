<?php

namespace App\Services;

class AdmissionFormStepService
{
    /**
     * Get all form steps configuration
     */
    public static function getFormSteps()
    {
        return [
            [
                'step' => 1,
                'title' => 'Personal Information',
                'description' => 'Tell us about yourself',
                'icon' => 'user',
                'fields' => self::getStep1Fields(),
            ],
            [
                'step' => 2,
                'title' => 'Contact Details',
                'description' => 'How can we reach you?',
                'icon' => 'phone',
                'fields' => self::getStep2Fields(),
            ],
            [
                'step' => 3,
                'title' => 'Academic Background',
                'description' => 'Your educational history',
                'icon' => 'graduation-cap',
                'fields' => self::getStep3Fields(),
            ],
            [
                'step' => 4,
                'title' => 'Program Selection',
                'description' => 'Choose your desired program',
                'icon' => 'book',
                'fields' => self::getStep4Fields(),
            ],
            [
                'step' => 5,
                'title' => 'Document Upload',
                'description' => 'Upload required documents',
                'icon' => 'file-upload',
                'fields' => self::getStep5Fields(),
            ],
            [
                'step' => 6,
                'title' => 'Additional Information',
                'description' => 'Tell us more about yourself',
                'icon' => 'info-circle',
                'fields' => self::getStep6Fields(),
            ],
            [
                'step' => 7,
                'title' => 'Review & Submit',
                'description' => 'Review your application',
                'icon' => 'check-circle',
                'fields' => [],
            ],
        ];
    }

    /**
     * Step 1: Personal Information
     */
    private static function getStep1Fields()
    {
        return [
            [
                'name' => 'first_name',
                'type' => 'text',
                'label' => 'First Name',
                'placeholder' => 'Enter your first name',
                'required' => true,
                'validation' => 'required|string|max:255',
                'icon' => 'user',
            ],
            [
                'name' => 'last_name',
                'type' => 'text',
                'label' => 'Last Name',
                'placeholder' => 'Enter your last name',
                'required' => true,
                'validation' => 'required|string|max:255',
                'icon' => 'user',
            ],
            [
                'name' => 'date_of_birth',
                'type' => 'date',
                'label' => 'Date of Birth',
                'placeholder' => 'Select your date of birth',
                'required' => true,
                'validation' => 'required|date|before:today',
                'icon' => 'calendar',
            ],
            [
                'name' => 'gender',
                'type' => 'select',
                'label' => 'Gender',
                'placeholder' => 'Select your gender',
                'required' => true,
                'validation' => 'required|in:male,female,other',
                'icon' => 'venus-mars',
                'options' => [
                    ['value' => 'male', 'label' => 'Male'],
                    ['value' => 'female', 'label' => 'Female'],
                    ['value' => 'other', 'label' => 'Other'],
                ],
            ],
            [
                'name' => 'nationality',
                'type' => 'text',
                'label' => 'Nationality',
                'placeholder' => 'Enter your nationality',
                'required' => true,
                'validation' => 'required|string|max:100',
                'icon' => 'flag',
            ],
        ];
    }

    /**
     * Step 2: Contact Details
     */
    private static function getStep2Fields()
    {
        return [
            [
                'name' => 'email',
                'type' => 'email',
                'label' => 'Email Address',
                'placeholder' => 'example@email.com',
                'required' => true,
                'validation' => 'required|email|max:255',
                'icon' => 'envelope',
            ],
            [
                'name' => 'phone',
                'type' => 'tel',
                'label' => 'Phone Number',
                'placeholder' => '+1234567890',
                'required' => true,
                'validation' => 'required|string|max:20',
                'icon' => 'phone',
            ],
            [
                'name' => 'current_address',
                'type' => 'textarea',
                'label' => 'Current Address',
                'placeholder' => 'Enter your current residential address',
                'required' => true,
                'validation' => 'required|string|max:500',
                'icon' => 'home',
                'rows' => 3,
            ],
            [
                'name' => 'permanent_address',
                'type' => 'textarea',
                'label' => 'Permanent Address',
                'placeholder' => 'Enter your permanent address (leave blank if same as current)',
                'required' => false,
                'validation' => 'nullable|string|max:500',
                'icon' => 'map-marker',
                'rows' => 3,
            ],
        ];
    }

    /**
     * Step 3: Academic Background
     */
    private static function getStep3Fields()
    {
        return [
            [
                'name' => 'previous_school',
                'type' => 'text',
                'label' => 'Previous School/Institution',
                'placeholder' => 'Name of your last school/college',
                'required' => true,
                'validation' => 'required|string|max:255',
                'icon' => 'school',
            ],
            [
                'name' => 'previous_degree',
                'type' => 'select',
                'label' => 'Last Completed Degree',
                'placeholder' => 'Select your degree',
                'required' => true,
                'validation' => 'required|string|max:100',
                'icon' => 'certificate',
                'options' => [
                    ['value' => 'high_school', 'label' => 'High School'],
                    ['value' => 'intermediate', 'label' => 'Intermediate/A-Levels'],
                    ['value' => 'bachelors', 'label' => 'Bachelor\'s Degree'],
                    ['value' => 'masters', 'label' => 'Master\'s Degree'],
                    ['value' => 'other', 'label' => 'Other'],
                ],
            ],
            [
                'name' => 'graduation_year',
                'type' => 'number',
                'label' => 'Graduation Year',
                'placeholder' => '2023',
                'required' => true,
                'validation' => 'required|integer|min:1950|max:' . (date('Y') + 5),
                'icon' => 'calendar',
            ],
            [
                'name' => 'previous_gpa',
                'type' => 'number',
                'label' => 'GPA/Percentage',
                'placeholder' => 'Enter your GPA (e.g., 3.5) or percentage',
                'required' => true,
                'validation' => 'required|numeric|min:0|max:100',
                'icon' => 'chart-line',
                'step' => '0.01',
            ],
        ];
    }

    /**
     * Step 4: Program Selection
     */
    private static function getStep4Fields()
    {
        return [
            [
                'name' => 'interest_id',
                'type' => 'select',
                'label' => 'Select Program/Course',
                'placeholder' => 'Choose your desired program',
                'required' => true,
                'validation' => 'required|exists:interests,id',
                'icon' => 'book-open',
                'dynamic_options' => true, // Options will be loaded from API
                'options_endpoint' => '/api/universities/{university_id}/interests',
            ],
            [
                'name' => 'program_preference_reason',
                'type' => 'textarea',
                'label' => 'Why do you want to join this program?',
                'placeholder' => 'Tell us why you chose this program',
                'required' => false,
                'validation' => 'nullable|string|max:1000',
                'icon' => 'comment',
                'rows' => 4,
            ],
        ];
    }

    /**
     * Step 5: Document Upload
     */
    private static function getStep5Fields()
    {
        return [
            [
                'name' => 'photo_file',
                'type' => 'file',
                'label' => 'Passport Photo',
                'placeholder' => 'Upload your recent passport-size photo',
                'required' => true,
                'validation' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'icon' => 'image',
                'accept' => 'image/*',
                'maxSize' => '2MB',
                'helper_text' => 'JPG, PNG format. Max 2MB',
            ],
            [
                'name' => 'id_document_file',
                'type' => 'file',
                'label' => 'ID Document (CNIC/Passport)',
                'placeholder' => 'Upload your ID document',
                'required' => true,
                'validation' => 'required|file|mimes:pdf,jpeg,png,jpg|max:5120',
                'icon' => 'id-card',
                'accept' => 'image/*,application/pdf',
                'maxSize' => '5MB',
                'helper_text' => 'PDF, JPG, PNG format. Max 5MB',
            ],
            [
                'name' => 'transcript_file',
                'type' => 'file',
                'label' => 'Academic Transcripts',
                'placeholder' => 'Upload your academic transcripts',
                'required' => true,
                'validation' => 'required|file|mimes:pdf|max:10240',
                'icon' => 'file-pdf',
                'accept' => 'application/pdf',
                'maxSize' => '10MB',
                'helper_text' => 'PDF format only. Max 10MB',
            ],
            [
                'name' => 'recommendation_letter_file',
                'type' => 'file',
                'label' => 'Recommendation Letter (Optional)',
                'placeholder' => 'Upload recommendation letter if available',
                'required' => false,
                'validation' => 'nullable|file|mimes:pdf|max:5120',
                'icon' => 'file-alt',
                'accept' => 'application/pdf',
                'maxSize' => '5MB',
                'helper_text' => 'PDF format only. Max 5MB',
            ],
        ];
    }

    /**
     * Step 6: Additional Information
     */
    private static function getStep6Fields()
    {
        return [
            [
                'name' => 'application_message',
                'type' => 'textarea',
                'label' => 'Personal Statement',
                'placeholder' => 'Tell us about yourself, your goals, and why you want to study at our university',
                'required' => false,
                'validation' => 'nullable|string|max:2000',
                'icon' => 'pen',
                'rows' => 6,
                'helper_text' => 'Maximum 2000 characters',
            ],
            [
                'name' => 'emergency_contact_name',
                'type' => 'text',
                'label' => 'Emergency Contact Name',
                'placeholder' => 'Name of emergency contact person',
                'required' => false,
                'validation' => 'nullable|string|max:255',
                'icon' => 'user-shield',
            ],
            [
                'name' => 'emergency_contact_phone',
                'type' => 'tel',
                'label' => 'Emergency Contact Phone',
                'placeholder' => 'Emergency contact phone number',
                'required' => false,
                'validation' => 'nullable|string|max:20',
                'icon' => 'phone',
            ],
            [
                'name' => 'emergency_contact_relation',
                'type' => 'select',
                'label' => 'Relationship with Emergency Contact',
                'placeholder' => 'Select relationship',
                'required' => false,
                'validation' => 'nullable|string|max:50',
                'icon' => 'users',
                'options' => [
                    ['value' => 'parent', 'label' => 'Parent'],
                    ['value' => 'guardian', 'label' => 'Guardian'],
                    ['value' => 'spouse', 'label' => 'Spouse'],
                    ['value' => 'sibling', 'label' => 'Sibling'],
                    ['value' => 'other', 'label' => 'Other'],
                ],
            ],
        ];
    }

    /**
     * Get fields for a specific step
     */
    public static function getStepFields($stepNumber)
    {
        $steps = self::getFormSteps();
        $step = collect($steps)->firstWhere('step', $stepNumber);
        return $step ? $step['fields'] : [];
    }

    /**
     * Get validation rules for a specific step
     */
    public static function getStepValidationRules($stepNumber)
    {
        $fields = self::getStepFields($stepNumber);
        $rules = [];
        
        foreach ($fields as $field) {
            if (isset($field['validation'])) {
                $rules[$field['name']] = $field['validation'];
            }
        }
        
        return $rules;
    }

    /**
     * Calculate form progress percentage
     */
    public static function calculateProgress($currentStep, $completedSteps = [])
    {
        $totalSteps = count(self::getFormSteps());
        $completed = count($completedSteps);
        return round(($completed / $totalSteps) * 100, 2);
    }

    /**
     * Check if user can proceed to next step
     */
    public static function canProceedToStep($stepNumber, $application)
    {
        if ($stepNumber <= 1) {
            return true;
        }

        // Check if previous step is completed
        $previousStep = $stepNumber - 1;
        $requiredFields = self::getStepFields($previousStep);
        
        foreach ($requiredFields as $field) {
            if ($field['required'] && empty($application->{$field['name']})) {
                return false;
            }
        }
        
        return true;
    }
}

