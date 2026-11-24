<?php

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ParentApiController;
use App\Http\Controllers\Api\StaffApiController;
use App\Http\Controllers\Api\StudentApiController;
use App\Http\Controllers\Api\TeacherApiController;
use App\Http\Controllers\SubscriptionWebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('subscription/webhook/stripe', [SubscriptionWebhookController::class, 'stripe']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify-email', [AuthController::class, 'verifyEmail']);
Route::post('/resend-code', [AuthController::class, 'resendVerificationCode']);

Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/verify-reset-code', [AuthController::class, 'verifyResetCode']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);


Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], static function () {
    Route::post('logout', [ApiController::class, 'logout']);
    Route::delete('delete-account', [AuthController::class, 'deleteAccount']);
});

/**
 * STUDENT APIs
 **/
// Route::group(['prefix' => 'student'], static function () {

//     //Non Authenticated APIs
//     Route::post('login', [StudentApiController::class, 'login']);
//     Route::post('forgot-password', [StudentApiController::class, 'forgotPassword']);

//     //Authenticated APIs
//     Route::group(['middleware' => ['auth:sanctum', 'checkSchoolStatus']], static function () {
//         Route::get('class-subjects', [StudentApiController::class, 'classSubjects']);
//         Route::get('subjects', [StudentApiController::class, 'subjects']);
//         Route::post('select-subjects', [StudentApiController::class, 'selectSubjects']);
//         Route::get('guradian-details', [StudentApiController::class, 'getGuardianDetails']);
//         Route::get('timetable', [StudentApiController::class, 'getTimetable']);
//         Route::get('lessons', [StudentApiController::class, 'getLessons']);
//         Route::get('lesson-topics', [StudentApiController::class, 'getLessonTopics']);
//         Route::get('assignments', [StudentApiController::class, 'getAssignments']);
//         Route::post('submit-assignment', [StudentApiController::class, 'submitAssignment']);
//         Route::post('delete-assignment-submission', [StudentApiController::class, 'deleteAssignmentSubmission']);
//         Route::get('attendance', [StudentApiController::class, 'getAttendance']);
//         Route::get('announcements', [StudentApiController::class, 'getAnnouncements']);
//         Route::get('get-exam-list', [StudentApiController::class, 'getExamList']); // Exam list Route
//         Route::get('get-exam-details', [StudentApiController::class, 'getExamDetails']); // Exam Details Route
//         Route::get('exam-marks', [StudentApiController::class, 'getExamMarks']); // Exam Details Route
//         Route::get('sliders', [StudentApiController::class, 'getSliders']); // Sliders

//         // online exam routes
//         Route::get('get-online-exam-list', [StudentApiController::class, 'getOnlineExamList']); // Get Online Exam List Route
//         Route::get('get-online-exam-questions', [StudentApiController::class, 'getOnlineExamQuestions']); // Get Online Exam Questions Route
//         Route::post('submit-online-exam-answers', [StudentApiController::class, 'submitOnlineExamAnswers']); // Submit Online Exam Answers Details Route
//         Route::get('get-online-exam-result-list', [StudentApiController::class, 'getOnlineExamResultList']); // Online exam result list Route
//         Route::get('get-online-exam-result', [StudentApiController::class, 'getOnlineExamResult']); // Online exam result  Route

//         //reports
//         Route::get('get-online-exam-report', [StudentApiController::class, 'getOnlineExamReport']); // Online Exam Report Route
//         Route::get('get-assignments-report', [StudentApiController::class, 'getAssignmentReport']); // Assignment Report Route

//         // profile data
//         Route::get('get-profile-data', [StudentApiController::class, 'getProfileDetails']); // Get Profile Data

//         // Session Year
//         Route::get('current-session-year', [StudentApiController::class, 'getSessionYear']);

//         Route::get('school-settings', [StudentApiController::class, 'getSchoolSettings']);


//     });
// });

// /**
//  * PARENT APIs
//  **/
// Route::group(['prefix' => 'parent'], static function () {
//     //Non Authenticated APIs
//     Route::post('login', [ParentApiController::class, 'login']);
//     //Authenticated APIs
//     Route::group(['middleware' => ['auth:sanctum',]], static function () {

//         Route::group(['middleware' => ['auth:sanctum', 'checkChild']], static function () {
//             Route::get('subjects', [ParentApiController::class, 'subjects']);
//             Route::get('class-subjects', [ParentApiController::class, 'classSubjects']);
//             Route::get('timetable', [ParentApiController::class, 'getTimetable']);
//             Route::get('lessons', [ParentApiController::class, 'getLessons']);
//             Route::get('lesson-topics', [ParentApiController::class, 'getLessonTopics']);
//             Route::get('assignments', [ParentApiController::class, 'getAssignments']);
//             Route::get('attendance', [ParentApiController::class, 'getAttendance']);
//             Route::get('teachers', [ParentApiController::class, 'getTeachers']);
//             Route::get('sliders', [ParentApiController::class, 'getSliders']); // Sliders

//             // Offline Exams
//             Route::get('get-exam-list', [ParentApiController::class, 'getExamList']); // Exam list Route
//             Route::get('get-exam-details', [ParentApiController::class, 'getExamDetails']); // Exam Details Route
//             Route::get('exam-marks', [ParentApiController::class, 'getExamMarks']); //Exam Marks

//             // Fees

//             Route::group(['prefix' => 'fees'], static function () {
//                 Route::get('/', [ParentApiController::class, 'getFees']);
//                 Route::post('/compulsory/pay', [ParentApiController::class, 'payCompulsoryFees']);
//                 Route::post('/optional/pay', [ParentApiController::class, 'payOptionalFees']);
//                 Route::get('/receipt', [ParentApiController::class, 'feesPaidReceiptPDF']); //Fees Receipt
//             });


//             // Online Exam
//             Route::get('get-online-exam-list', [ParentApiController::class, 'getOnlineExamList']); // Get Online Exam List Route
//             Route::get('get-online-exam-result-list', [ParentApiController::class, 'getOnlineExamResultList']); // Online exam result list Route
//             Route::get('get-online-exam-result', [ParentApiController::class, 'getOnlineExamResult']); // Online exam result  Route

//             // Reports
//             Route::get('get-online-exam-report', [ParentApiController::class, 'getOnlineExamReport']); // Online Exam Report Route
//             Route::get('get-assignments-report', [ParentApiController::class, 'getAssignmentReport']); // Assignment Report Route

//             // Session Year
//             Route::get('current-session-year', [ParentApiController::class, 'getSessionYear']);
//             Route::get('school-settings', [ParentApiController::class, 'getSchoolSettings']);

//             // profile data
//             Route::get('get-child-profile-data', [ParentApiController::class, 'getChildProfileDetails']); // Get Profile Data

//             // Announcements
//             Route::get('announcements', [ParentApiController::class, 'getAnnouncements']);
//         });
//     });
// });

// /**
//  * TEACHER APIs
//  **/
// Route::group(['prefix' => 'teacher'], static function () {
//     //Non Authenticated APIs
//     Route::post('login', [TeacherApiController::class, 'login']);
//     //Authenticated APIs
//     Route::group(['middleware' => ['auth:sanctum', 'checkSchoolStatus']], static function () {

//         Route::get('subjects', [TeacherApiController::class, 'subjects']);

//         //Assignment
//         Route::get('get-assignment', [TeacherApiController::class, 'getAssignment']);
//         Route::post('create-assignment', [TeacherApiController::class, 'createAssignment']);
//         Route::post('update-assignment', [TeacherApiController::class, 'updateAssignment']);
//         Route::post('delete-assignment', [TeacherApiController::class, 'deleteAssignment']);

//         //Assignment Submission
//         Route::get('get-assignment-submission', [TeacherApiController::class, 'getAssignmentSubmission']);
//         Route::post('update-assignment-submission', [TeacherApiController::class, 'updateAssignmentSubmission']);

//         //File
//         Route::post('delete-file', [TeacherApiController::class, 'deleteFile']);
//         Route::post('update-file', [TeacherApiController::class, 'updateFile']);

//         //Lesson
//         Route::get('get-lesson', [TeacherApiController::class, 'getLesson']);
//         Route::post('create-lesson', [TeacherApiController::class, 'createLesson']);
//         Route::post('update-lesson', [TeacherApiController::class, 'updateLesson']);
//         Route::post('delete-lesson', [TeacherApiController::class, 'deleteLesson']);

//         //Topic
//         Route::get('get-topic', [TeacherApiController::class, 'getTopic']);
//         Route::post('create-topic', [TeacherApiController::class, 'createTopic']);
//         Route::post('update-topic', [TeacherApiController::class, 'updateTopic']);
//         Route::post('delete-topic', [TeacherApiController::class, 'deleteTopic']);

//         //Announcement
//         Route::get('get-announcement', [TeacherApiController::class, 'getAnnouncement']);
//         Route::post('send-announcement', [TeacherApiController::class, 'sendAnnouncement']);
//         Route::post('update-announcement', [TeacherApiController::class, 'updateAnnouncement']);
//         Route::post('delete-announcement', [TeacherApiController::class, 'deleteAnnouncement']);

//         Route::get('get-attendance', [TeacherApiController::class, 'getAttendance']);
//         Route::post('submit-attendance', [TeacherApiController::class, 'submitAttendance']);


//         //Exam
//         Route::get('get-exam-list', [TeacherApiController::class, 'getExamList']); // Exam list Route
//         Route::get('get-exam-details', [TeacherApiController::class, 'getExamDetails']); // Exam Details Route
//         Route::post('submit-exam-marks/subject', [TeacherApiController::class, 'submitExamMarksBySubjects']); // Submit Exam Marks By Subjects Route
//         Route::post('submit-exam-marks/student', [TeacherApiController::class, 'submitExamMarksByStudent']); // Submit Exam Marks By Students Route

//         Route::group(['middleware' => ['auth:sanctum', 'checkStudent']], static function () {
//             Route::get('get-student-result', [TeacherApiController::class, 'GetStudentExamResult']); // Student Exam Result
//             Route::get('get-student-marks', [TeacherApiController::class, 'GetStudentExamMarks']); // Student Exam Marks
//         });

//         //Student List
//         Route::get('student-list', [TeacherApiController::class, 'getStudentList']);
//         Route::get('student-details', [TeacherApiController::class, 'getStudentDetails']);

//         //Schedule List
//         Route::get('teacher_timetable', [TeacherApiController::class, 'getTeacherTimetable']);

//         Route::post('class-detail', [TeacherApiController::class, 'getClassDetail']);
//     });
// });


// // Staff & Teacher APIs
// Route::group(['prefix' => 'staff'], static function () {
//     Route::post('login', [TeacherApiController::class, 'login']);

//     Route::group(['middleware' => ['auth:sanctum', 'checkSchoolStatus']], static function () {
//         // Payroll
//         Route::get('my-payroll', [StaffApiController::class, 'myPayroll']);
//         Route::get('payroll-slip', [StaffApiController::class, 'myPayrollSlip']);
//         Route::post('payroll-create', [StaffApiController::class, 'storePayroll']);
//         Route::get('payroll-staff-list', [StaffApiController::class, 'staffPayrollList']);

//         Route::get('payroll-year', [StaffApiController::class, 'payrollYear']);


//         Route::get('profile', [StaffApiController::class, 'profile']);
//         Route::get('counter', [StaffApiController::class, 'counter']);
//         Route::get('teachers', [StaffApiController::class, 'teacher']);
//         Route::get('teacher-timetable', [StaffApiController::class, 'teacherTimetable']);
//         Route::get('staffs', [StaffApiController::class, 'staff']);

//         Route::get('leave-request', [StaffApiController::class, 'leaveRequest']);
//         Route::post('leave-approve', [StaffApiController::class, 'leaveApprove']);
//         Route::post('leave-delete', [StaffApiController::class, 'leaveDelete']);

//         // Announcement
//         Route::get('get-announcement', [StaffApiController::class, 'getAnnouncement']);
//         Route::post('send-announcement', [StaffApiController::class, 'sendAnnouncement']);
//         Route::post('update-announcement', [StaffApiController::class, 'updateAnnouncement']);
//         Route::post('delete-announcement', [StaffApiController::class, 'deleteAnnouncement']);

//         Route::get('student/attendance', [StaffApiController::class, 'studentAttendance']);

//         Route::get('roles', [StaffApiController::class, 'getRoles']);
//         Route::get('users', [StaffApiController::class, 'getUsers']);
//         Route::post('notification', [StaffApiController::class, 'storeNotification']);
//         Route::get('notification', [StaffApiController::class, 'getNotification']);
//         Route::post('notification-delete', [StaffApiController::class, 'deleteNotification']);

//         Route::get('get-fees', [StaffApiController::class, 'getFees']);
//         Route::get('fees-paid-list', [StaffApiController::class, 'getFeesPaidList']);

//         Route::get('student-offline-exam-result', [StaffApiController::class, 'getOfflineExamResult']);
//         Route::get('features-permission', [StaffApiController::class, 'getFeaturesPermissions']);

//         Route::get('class-timetable', [StaffApiController::class, 'getClassTimetable']);

//         Route::get('student-fees-receipt', [StaffApiController::class, 'feesReceipt']);
//         Route::get('allowances-deductions', [StaffApiController::class, 'allowancesDeductions']);



//     });
// });

// /**
//  * GENERAL APIs
//  **/
// Route::get('settings', [ApiController::class, 'getSettings']);
// Route::post('forgot-password', [ApiController::class, 'forgotPassword']);

// Route::group(['middleware' => ['auth:sanctum',]], static function () {
//     Route::get('holidays', [ApiController::class, 'getHolidays']);
//     Route::post('change-password', [ApiController::class, 'changePassword']);
// //    Route::get('test', [ApiController::class, 'getPaymentMethod']);
//     Route::get('payment-confirmation', [ApiController::class, 'getPaymentConfirmation']);
//     Route::get('payment-transactions', [ApiController::class, 'getPaymentTransactions']);
//     Route::get('gallery', [ApiController::class, 'getGallery']);
//     Route::get('session-years', [ApiController::class, 'getSessionYear']);
// //    Route::get('features', [ApiController::class, 'getFeatures']);

//     // Leaves
//     Route::get('leaves', [ApiController::class, 'getLeaves']);
//     Route::post('leaves', [ApiController::class, 'applyLeaves']);
//     Route::get('my-leaves', [ApiController::class, 'getMyLeaves']);
//     Route::post('delete-my-leaves', [ApiController::class, 'deleteLeaves']);
//     Route::get('staff-leaves-details', [ApiController::class, 'getStaffLeaveDetail']);
//     Route::get('leave-settings', [ApiController::class, 'leaveSettings']);

//     Route::get('medium', [ApiController::class, 'getMedium']);
//     Route::get('classes', [ApiController::class, 'getClass']);

//     Route::post('update-profile', [ApiController::class, 'updateProfile']);
//     Route::get('student-exan-result-pdf', [ApiController::class, 'getExamResultPdf']);

// });

/**
 * UNIVERSITY ADMISSION APIs
 **/

// Public University APIs (No Authentication Required)
Route::group(['prefix' => 'universities'], static function () {
    Route::get('/recommended', [\App\Http\Controllers\Api\UniversityController::class, 'getRecommendedUniversities']);
    Route::get('/', [\App\Http\Controllers\Api\UniversityController::class, 'index']); // Get all universities with filters
    Route::get('/{id}', [\App\Http\Controllers\Api\UniversityController::class, 'show']); // Get single university details

    // University Gallery
    Route::get('/{universityId}/gallery', [\App\Http\Controllers\Api\UniversityGalleryController::class, 'getByUniversity']); // Get university gallery

    // University Scholarships
    Route::get('/{universityId}/scholarships', [\App\Http\Controllers\Api\ScholarshipController::class, 'getByUniversity']); // Get university scholarships

    // University Admission Requirements
    Route::get('/{universityId}/admission-requirements', [\App\Http\Controllers\Api\AdmissionRequirementController::class, 'getByUniversity']); // Get admission requirements
    Route::get('/{universityId}/programs/{interestId}/admission-requirements', [\App\Http\Controllers\Api\AdmissionRequirementController::class, 'getByProgram']); // Get program-specific requirements
});

// Get all available interests and cities (public)
Route::get('interests/all', [\App\Http\Controllers\Api\UserInterestController::class, 'getAllInterests']);
Route::get('cities/all', [\App\Http\Controllers\Api\UserCityController::class, 'getAllCities']);

// Authenticated User APIs
Route::group(['middleware' => ['auth:sanctum']], static function () {

    Route::get('/profile',[StudentApiController::class,'profile']);

    // Dashboard API
    Route::get('/dashboard', [\App\Http\Controllers\Api\DashboardController::class, 'getDashboard']); // Get application dashboard

    // User Education Management
    Route::group(['prefix' => 'education'], static function () {
        Route::get('/', [\App\Http\Controllers\Api\UserEducationController::class, 'index']); // Get all user education
        Route::post('/', [\App\Http\Controllers\Api\UserEducationController::class, 'store']); // Add education
        Route::put('/{id}', [\App\Http\Controllers\Api\UserEducationController::class, 'update']); // Update education
        Route::delete('/{id}', [\App\Http\Controllers\Api\UserEducationController::class, 'destroy']); // Delete education
    });

    // User Interests Management
    Route::group(['prefix' => 'interests'], static function () {
        Route::get('/', [\App\Http\Controllers\Api\UserInterestController::class, 'index']); // Get user interests
        Route::post('/', [\App\Http\Controllers\Api\UserInterestController::class, 'store']); // Add interests
        Route::post('/sync', [\App\Http\Controllers\Api\UserInterestController::class, 'sync']); // Replace all interests
        Route::delete('/{interestId}', [\App\Http\Controllers\Api\UserInterestController::class, 'destroy']); // Remove interest
    });

    // User Preferred Cities Management
    Route::group(['prefix' => 'preferred-cities'], static function () {
        Route::get('/', [\App\Http\Controllers\Api\UserCityController::class, 'index']); // Get user cities
        Route::post('/', [\App\Http\Controllers\Api\UserCityController::class, 'store']); // Add cities
        Route::post('/sync', [\App\Http\Controllers\Api\UserCityController::class, 'sync']); // Replace all cities
        Route::delete('/{cityId}', [\App\Http\Controllers\Api\UserCityController::class, 'destroy']); // Remove city
    });

    // Favorites Management
    Route::group(['prefix' => 'favorites'], static function () {
        Route::get('/', [\App\Http\Controllers\Api\FavoriteController::class, 'index']); // Get all favorites
        Route::post('/', [\App\Http\Controllers\Api\FavoriteController::class, 'store']); // Add to favorites
        Route::post('/toggle', [\App\Http\Controllers\Api\FavoriteController::class, 'toggle']); // Toggle favorite
        Route::delete('/{universityId}', [\App\Http\Controllers\Api\FavoriteController::class, 'destroy']); // Remove from favorites
    });

    // User Notifications Management
    Route::group(['prefix' => 'notifications'], static function () {
        Route::get('/', [\App\Http\Controllers\Api\UserNotificationController::class, 'index']); // Get all notifications
        Route::post('/{id}/read', [\App\Http\Controllers\Api\UserNotificationController::class, 'markAsRead']); // Mark notification as read
        Route::post('/mark-all-read', [\App\Http\Controllers\Api\UserNotificationController::class, 'markAllAsRead']); // Mark all notifications as read
    });

    // Account Management
    Route::group(['prefix' => 'account'], static function () {
        Route::get('/deletion-info', [\App\Http\Controllers\Api\AccountController::class, 'getDeletionInfo']); // Get account deletion information
        Route::delete('/', [\App\Http\Controllers\Api\AccountController::class, 'deleteAccount']); // Delete user account
    });
});

// Student Document Management APIs
Route::group(['prefix' => 'documents', 'middleware' => ['auth:sanctum']], static function () {
    Route::get('/', [\App\Http\Controllers\Api\DocumentController::class, 'index']); // Get all documents
    Route::get('/statistics', [\App\Http\Controllers\Api\DocumentController::class, 'statistics']); // Get document statistics
    Route::get('/{id}', [\App\Http\Controllers\Api\DocumentController::class, 'show']); // Get single document
    Route::post('/', [\App\Http\Controllers\Api\DocumentController::class, 'store']); // Upload new document
    Route::put('/{id}', [\App\Http\Controllers\Api\DocumentController::class, 'update']); // Update document details
    Route::post('/{id}/replace-file', [\App\Http\Controllers\Api\DocumentController::class, 'replaceFile']); // Replace document file
    Route::delete('/{id}', [\App\Http\Controllers\Api\DocumentController::class, 'destroy']); // Delete document
    Route::get('/{id}/download', [\App\Http\Controllers\Api\DocumentController::class, 'download']); // Download document
});

// Application Management APIs (Students)
Route::group(['prefix' => 'applications', 'middleware' => ['auth:sanctum']], static function () {
    Route::get('/universities/{universityId}/form', [\App\Http\Controllers\Api\ApplicationController::class, 'getApplicationForm']); // Get application form for university
    Route::get('/my-applications', [\App\Http\Controllers\Api\ApplicationController::class, 'myApplications']); // Get user's applications
    Route::get('/{id}', [\App\Http\Controllers\Api\ApplicationController::class, 'show']); // Get single application
    Route::post('/', [\App\Http\Controllers\Api\ApplicationController::class, 'store']); // Create/save draft application
    Route::put('/{id}', [\App\Http\Controllers\Api\ApplicationController::class, 'update']); // Update draft application
    Route::post('/{id}/submit', [\App\Http\Controllers\Api\ApplicationController::class, 'submit']); // Submit application
    Route::post('/{id}/withdraw', [\App\Http\Controllers\Api\ApplicationController::class, 'withdraw']); // Withdraw application
    Route::delete('/{id}', [\App\Http\Controllers\Api\ApplicationController::class, 'destroy']); // Delete draft application
});

// Application Package APIs
Route::group(['prefix' => 'application-packages'], static function () {
    Route::post('/purchase', [\App\Http\Controllers\Api\ApplicationPackageController::class, 'purchase']); // Purchase package
    Route::get('/status', [\App\Http\Controllers\Api\ApplicationPackageController::class, 'status']); // Check package status
});

// JazzCash Payment APIs
Route::group(['prefix' => 'jazzcash'], static function () {
    Route::post('/pay-with-wallet', [\App\Http\Controllers\JazzCashGatewayController::class, 'payWithWallet']); // Pay with JazzCash Wallet
    Route::post('/card-payment', [\App\Http\Controllers\JazzCashGatewayController::class, 'cardPayment']); // Pay with Card
    Route::post('/ipn', [\App\Http\Controllers\JazzCashGatewayController::class, 'ipn']); // IPN callback
});

// App Pages (Public - No Authentication Required)
Route::group(['prefix' => 'app'], static function () {
    Route::get('/privacy-policy', [\App\Http\Controllers\Api\AppPageController::class, 'privacyPolicy']); // Get Privacy Policy (JSON)
    Route::get('/terms-conditions', [\App\Http\Controllers\Api\AppPageController::class, 'termsConditions']); // Get Terms & Conditions (JSON)
});

// Multi-Step Application Form APIs (Mobile App Optimized)
Route::group(['prefix' => 'multi-step-application'], static function () {
    // Get HTML form view for WebView
    Route::get('/form-view', [\App\Http\Controllers\Api\MultiStepApplicationController::class, 'getFormView']); // Get HTML form for WebView

    // Get form configuration
    Route::get('/steps', [\App\Http\Controllers\Api\MultiStepApplicationController::class, 'getFormSteps']); // Get all form steps
    Route::get('/steps/{stepNumber}', [\App\Http\Controllers\Api\MultiStepApplicationController::class, 'getStep']); // Get specific step

    // Application management
    Route::post('/start', [\App\Http\Controllers\Api\MultiStepApplicationController::class, 'startApplication']); // Start new application
    Route::post('/{applicationId}/step/{stepNumber}', [\App\Http\Controllers\Api\MultiStepApplicationController::class, 'saveStep']); // Save step data
    Route::get('/{applicationId}/progress', [\App\Http\Controllers\Api\MultiStepApplicationController::class, 'getProgress']); // Get progress
    Route::get('/{applicationId}/summary', [\App\Http\Controllers\Api\MultiStepApplicationController::class, 'getApplicationSummary']); // Get summary for review
    Route::post('/{applicationId}/submit', [\App\Http\Controllers\Api\MultiStepApplicationController::class, 'submitApplication']); // Submit application
    Route::delete('/{applicationId}/draft', [\App\Http\Controllers\Api\MultiStepApplicationController::class, 'deleteDraft']); // Delete draft
});

// Public FAQ API (No Authentication Required)
Route::get('faqs', [\App\Http\Controllers\Api\AppFaqController::class, 'index']); // Get all active FAQs
Route::get('faqs/{id}', [\App\Http\Controllers\Api\AppFaqController::class, 'show']); // Get single FAQ

// Admin APIs for University Management
Route::group(['prefix' => 'admin', 'middleware' => ['auth:sanctum']], static function () {

    // FAQ Management (Admin)
    Route::group(['prefix' => 'faqs'], static function () {
        Route::get('/', [\App\Http\Controllers\Api\Admin\AppFaqAdminController::class, 'index']); // Get all FAQs
        Route::post('/', [\App\Http\Controllers\Api\Admin\AppFaqAdminController::class, 'store']); // Create FAQ
        Route::get('/statistics', [\App\Http\Controllers\Api\Admin\AppFaqAdminController::class, 'statistics']); // Get statistics
        Route::get('/{id}', [\App\Http\Controllers\Api\Admin\AppFaqAdminController::class, 'show']); // Get single FAQ
        Route::put('/{id}', [\App\Http\Controllers\Api\Admin\AppFaqAdminController::class, 'update']); // Update FAQ
        Route::delete('/{id}', [\App\Http\Controllers\Api\Admin\AppFaqAdminController::class, 'destroy']); // Delete FAQ
        Route::post('/{id}/toggle-status', [\App\Http\Controllers\Api\Admin\AppFaqAdminController::class, 'toggleStatus']); // Toggle status
        Route::post('/reorder', [\App\Http\Controllers\Api\Admin\AppFaqAdminController::class, 'reorder']); // Reorder FAQs
    });

    // Application Management (Admin)
    Route::group(['prefix' => 'applications'], static function () {
        Route::get('/', [\App\Http\Controllers\Api\Admin\ApplicationAdminController::class, 'index']); // Get all applications
        Route::get('/statistics', [\App\Http\Controllers\Api\Admin\ApplicationAdminController::class, 'statistics']); // Get statistics
        Route::get('/export', [\App\Http\Controllers\Api\Admin\ApplicationAdminController::class, 'export']); // Export applications
        Route::get('/{id}', [\App\Http\Controllers\Api\Admin\ApplicationAdminController::class, 'show']); // Get single application
        Route::post('/{id}/status', [\App\Http\Controllers\Api\Admin\ApplicationAdminController::class, 'updateStatus']); // Update application status
        Route::post('/{id}/notes', [\App\Http\Controllers\Api\Admin\ApplicationAdminController::class, 'addNotes']); // Add admin notes
        Route::post('/bulk-update-status', [\App\Http\Controllers\Api\Admin\ApplicationAdminController::class, 'bulkUpdateStatus']); // Bulk update status
    });

    // University Custom Application Fields Management
    Route::group(['prefix' => 'universities/{universityId}/fields'], static function () {
        Route::get('/', [\App\Http\Controllers\Api\Admin\UniversityFieldAdminController::class, 'index']); // Get all custom fields
        Route::post('/', [\App\Http\Controllers\Api\Admin\UniversityFieldAdminController::class, 'store']); // Create custom field
        Route::get('/{fieldId}', [\App\Http\Controllers\Api\Admin\UniversityFieldAdminController::class, 'show']); // Get single field
        Route::put('/{fieldId}', [\App\Http\Controllers\Api\Admin\UniversityFieldAdminController::class, 'update']); // Update field
        Route::delete('/{fieldId}', [\App\Http\Controllers\Api\Admin\UniversityFieldAdminController::class, 'destroy']); // Delete field
        Route::post('/reorder', [\App\Http\Controllers\Api\Admin\UniversityFieldAdminController::class, 'reorder']); // Reorder fields
        Route::post('/{fieldId}/toggle-status', [\App\Http\Controllers\Api\Admin\UniversityFieldAdminController::class, 'toggleStatus']); // Toggle field status
    });

    // University Management
    Route::group(['prefix' => 'universities'], static function () {
        Route::get('/', [\App\Http\Controllers\Api\Admin\UniversityAdminController::class, 'index']); // Get all universities
        Route::post('/', [\App\Http\Controllers\Api\Admin\UniversityAdminController::class, 'store']); // Create university
        Route::get('/{id}', [\App\Http\Controllers\Api\Admin\UniversityAdminController::class, 'show']); // Get university
        Route::put('/{id}', [\App\Http\Controllers\Api\Admin\UniversityAdminController::class, 'update']); // Update university
        Route::delete('/{id}', [\App\Http\Controllers\Api\Admin\UniversityAdminController::class, 'destroy']); // Delete university
        Route::post('/{id}/restore', [\App\Http\Controllers\Api\Admin\UniversityAdminController::class, 'restore']); // Restore university
        Route::post('/{id}/interests', [\App\Http\Controllers\Api\Admin\UniversityAdminController::class, 'updateInterests']); // Manage programs
    });

    // Interest Management
    Route::group(['prefix' => 'interests'], static function () {
        Route::get('/', [\App\Http\Controllers\Api\Admin\InterestAdminController::class, 'index']); // Get all interests
        Route::post('/', [\App\Http\Controllers\Api\Admin\InterestAdminController::class, 'store']); // Create interest
        Route::put('/{id}', [\App\Http\Controllers\Api\Admin\InterestAdminController::class, 'update']); // Update interest
        Route::delete('/{id}', [\App\Http\Controllers\Api\Admin\InterestAdminController::class, 'destroy']); // Delete interest
    });

    // City Management
    Route::group(['prefix' => 'cities'], static function () {
        Route::get('/', [\App\Http\Controllers\Api\Admin\CityAdminController::class, 'index']); // Get all cities
        Route::post('/', [\App\Http\Controllers\Api\Admin\CityAdminController::class, 'store']); // Create city
        Route::put('/{id}', [\App\Http\Controllers\Api\Admin\CityAdminController::class, 'update']); // Update city
        Route::delete('/{id}', [\App\Http\Controllers\Api\Admin\CityAdminController::class, 'destroy']); // Delete city
    });

    // University Gallery Management
    Route::group(['prefix' => 'university-galleries'], static function () {
        Route::get('/', [\App\Http\Controllers\Api\UniversityGalleryController::class, 'index']); // Get all galleries
        Route::post('/', [\App\Http\Controllers\Api\UniversityGalleryController::class, 'store']); // Create gallery item
        Route::get('/{id}', [\App\Http\Controllers\Api\UniversityGalleryController::class, 'show']); // Get gallery item
        Route::put('/{id}', [\App\Http\Controllers\Api\UniversityGalleryController::class, 'update']); // Update gallery item
        Route::delete('/{id}', [\App\Http\Controllers\Api\UniversityGalleryController::class, 'destroy']); // Delete gallery item
    });

    // Scholarship Management
    Route::group(['prefix' => 'scholarships'], static function () {
        Route::get('/', [\App\Http\Controllers\Api\ScholarshipController::class, 'index']); // Get all scholarships
        Route::post('/', [\App\Http\Controllers\Api\ScholarshipController::class, 'store']); // Create scholarship
        Route::get('/{id}', [\App\Http\Controllers\Api\ScholarshipController::class, 'show']); // Get scholarship
        Route::put('/{id}', [\App\Http\Controllers\Api\ScholarshipController::class, 'update']); // Update scholarship
        Route::delete('/{id}', [\App\Http\Controllers\Api\ScholarshipController::class, 'destroy']); // Delete scholarship
    });

    // Admission Requirements Management
    Route::group(['prefix' => 'admission-requirements'], static function () {
        Route::get('/', [\App\Http\Controllers\Api\AdmissionRequirementController::class, 'index']); // Get all requirements
        Route::post('/', [\App\Http\Controllers\Api\AdmissionRequirementController::class, 'store']); // Create requirement
        Route::get('/{id}', [\App\Http\Controllers\Api\AdmissionRequirementController::class, 'show']); // Get requirement
        Route::put('/{id}', [\App\Http\Controllers\Api\AdmissionRequirementController::class, 'update']); // Update requirement
        Route::delete('/{id}', [\App\Http\Controllers\Api\AdmissionRequirementController::class, 'destroy']); // Delete requirement
    });
});

