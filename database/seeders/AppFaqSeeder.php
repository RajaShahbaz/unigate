<?php

namespace Database\Seeders;

use App\Models\AppFaq;
use Illuminate\Database\Seeder;

class AppFaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'How do I apply for university admission?',
                'answer' => 'To apply for university admission, first register an account on our platform. Once registered, browse through the list of universities, select your preferred institution, and fill out the application form with all required information. You can save your progress and submit when ready.',
                'order' => 1,
                'status' => 1,
            ],
            [
                'question' => 'What documents do I need to upload?',
                'answer' => 'You will typically need to upload your academic transcripts, identification documents (passport or national ID), passport-size photograph, and any other documents specified by the university. You can manage all your documents in the Documents section of your profile.',
                'order' => 2,
                'status' => 1,
            ],
            [
                'question' => 'Can I apply to multiple universities?',
                'answer' => 'Yes, you can apply to as many universities as you wish through our platform. Each university application is tracked separately, and you can view all your applications in the "My Applications" section.',
                'order' => 3,
                'status' => 1,
            ],
            [
                'question' => 'How long does the application process take?',
                'answer' => 'The application review process varies by university. Typically, you can expect to hear back within 2-4 weeks after submission. You will receive notifications about any status updates on your application.',
                'order' => 4,
                'status' => 1,
            ],
            [
                'question' => 'What payment methods are accepted?',
                'answer' => 'We accept various payment methods including credit/debit cards, bank transfers, and mobile payment options. Specific payment options may vary by university and country.',
                'order' => 5,
                'status' => 1,
            ],
            [
                'question' => 'Can I edit my application after submission?',
                'answer' => 'Once an application is submitted, you cannot edit it directly. However, you can contact the university\'s admission office through our platform to request changes or provide additional information.',
                'order' => 6,
                'status' => 1,
            ],
            [
                'question' => 'How do I track my application status?',
                'answer' => 'You can track your application status by logging into your account and navigating to "My Applications". Each application will show its current status (Draft, Submitted, Under Review, Accepted, Rejected, or Withdrawn).',
                'order' => 7,
                'status' => 1,
            ],
            [
                'question' => 'Are scholarships available?',
                'answer' => 'Many universities offer scholarships and financial aid. You can view available scholarships on each university\'s profile page. Scholarship eligibility and application processes vary by institution.',
                'order' => 8,
                'status' => 1,
            ],
            [
                'question' => 'What if I forgot my password?',
                'answer' => 'If you forgot your password, click on the "Forgot Password" link on the login page. You will receive an email with instructions to reset your password.',
                'order' => 9,
                'status' => 1,
            ],
            [
                'question' => 'How can I contact support?',
                'answer' => 'You can contact our support team through the app\'s contact form or by emailing support@unigate.com. We typically respond within 24 hours during business days.',
                'order' => 10,
                'status' => 1,
            ],
        ];

        foreach ($faqs as $faq) {
            AppFaq::create($faq);
        }
    }
}



