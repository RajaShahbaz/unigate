# University Web Portal - Setup & Usage Guide

## Overview

This comprehensive university portal allows universities to:
- ✅ Login to a dedicated web portal
- ✅ Manage student applications
- ✅ View detailed student profiles and application data
- ✅ Manage university information and data
- ✅ Create custom application form fields for students
- ✅ Bulk update application statuses
- ✅ Export applications to CSV
- ✅ Track application statistics and analytics

---

## Installation & Setup

### 1. Run Database Migration

First, run the migration to create the university admins table:

```bash
php artisan migrate
```

This will create the `university_admins` table which stores university administrator accounts.

### 2. Create a University Admin Account

You need to manually create a university admin account in the database. Here's a sample seeder or you can use Tinker:

#### Using Tinker:

```bash
php artisan tinker
```

Then run:

```php
use App\Models\University;
use App\Models\UniversityAdmin;
use Illuminate\Support\Facades\Hash;

// First, make sure you have a university in the database
$university = University::first(); // or create one

// Create an admin for that university
UniversityAdmin::create([
    'university_id' => $university->id,
    'name' => 'John Doe',
    'email' => 'admin@university.edu',
    'password' => Hash::make('password123'),
    'phone' => '+1234567890',
    'position' => 'Admissions Director',
    'status' => true,
]);
```

#### Or create a Database Seeder:

Create `database/seeders/UniversityAdminSeeder.php`:

```php
<?php

namespace Database\Seeders;

use App\Models\University;
use App\Models\UniversityAdmin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UniversityAdminSeeder extends Seeder
{
    public function run()
    {
        $university = University::first();
        
        if ($university) {
            UniversityAdmin::create([
                'university_id' => $university->id,
                'name' => 'University Admin',
                'email' => 'admin@university.edu',
                'password' => Hash::make('password123'),
                'phone' => '+1234567890',
                'position' => 'Admissions Director',
                'status' => true,
            ]);
        }
    }
}
```

Then run:

```bash
php artisan db:seed --class=UniversityAdminSeeder
```

---

## Accessing the Portal

### Login URL

Visit: `http://your-domain.com/university/login`

**Default Credentials** (from the seeder above):
- Email: `admin@university.edu`
- Password: `password123`

---

## Portal Features

### 1. Dashboard

**URL:** `/university/dashboard`

Features:
- Overview statistics (total applications, pending, accepted, etc.)
- Recent applications list
- Applications by status chart (doughnut chart)
- Applications by program chart (bar chart)
- Quick access to view applications

### 2. Application Management

**URL:** `/university/applications`

Features:
- **View All Applications:** List all submitted applications
- **Filter Applications:** By status, program, date range, search by name/email/application number
- **Status Tabs:** Quick filter by application status
- **Bulk Actions:** Select multiple applications and update their status at once
- **Export to CSV:** Download filtered applications as CSV
- **View Details:** Click any application to see full details

#### Application Statuses:
- `Submitted` - Initial submission
- `Under Review` - Being reviewed by admin
- `Shortlisted` - Candidate shortlisted
- `Interview Scheduled` - Interview arranged
- `Accepted` - Application accepted
- `Rejected` - Application rejected

#### Application Details Page

**URL:** `/university/applications/{id}`

View complete application including:
- Student personal information
- Academic records (GPA, previous school, graduation year)
- Uploaded documents (transcripts, ID, photo, recommendation letters)
- Custom field responses
- Personal statement
- Update application status
- Add admin notes
- Add rejection reason

### 3. Programs & Courses Management

**URL:** `/university/programs`

Manage all programs/courses your university offers with detailed tuition fee information.

#### Features:
- **View All Programs:** See all programs your university offers
- **Add Programs:** Add individual or multiple programs at once
- **Set Tuition Fees:** Configure single fee or fee range (min-max)
- **Program Details:** Add duration, description, and requirements
- **Statistics:** View application counts per program
- **Smart Validation:** Prevents deletion if applications exist

#### Fee Configuration Options:

1. **Single Fee** - Fixed tuition for all students
   ```
   Example: $15,000 per year
   ```

2. **Fee Range** - Variable fees based on factors
   ```
   Example: $10,000 - $20,000 (based on residency, scholarships, etc.)
   ```

3. **Both** - Provide standard fee AND range
   ```
   Standard: $15,000
   Range: $12,000 - $18,000
   ```

4. **Duration** - Program length in years (1-10)

5. **Program Details** - Rich description (up to 5000 characters)

#### Program Information Includes:
- Program name (from interests/categories)
- Tuition fee (single or range)
- Duration in years
- Detailed program description
- Admission requirements
- Career prospects
- Special features

#### Bulk Operations:
- **Bulk Add:** Select multiple programs to add at once
- **Individual Edit:** Fine-tune each program's details
- **Safe Delete:** Prevents removal if applications exist

### 4. Custom Form Fields

**URL:** `/university/form-fields`

Create custom fields to collect additional information from applicants.

#### Available Field Types:
1. **Text** - Single line text input
2. **Textarea** - Multi-line text input
3. **Number** - Numeric input
4. **Date** - Date picker
5. **Email** - Email input with validation
6. **URL** - URL input with validation
7. **File** - File upload (with configurable file types and size limits)
8. **Select** - Dropdown menu (with custom options)
9. **Checkbox** - Multiple selection checkboxes
10. **Radio** - Single selection radio buttons

#### Features:
- **Create New Fields:** Add custom fields to your application form
- **Edit Fields:** Modify existing fields
- **Drag & Drop Reorder:** Change the order fields appear to students
- **Toggle Status:** Activate/deactivate fields without deleting them
- **Required/Optional:** Mark fields as required or optional
- **Delete Fields:** Remove fields (only if no responses exist)

#### Example Custom Fields:
```
Field Name: portfolio_url
Label: Portfolio URL
Type: URL
Required: Yes
Description: Please provide a link to your online portfolio

Field Name: motivation_essay
Label: Motivation Essay
Type: Textarea
Required: Yes
Description: Write a 500-word essay explaining your motivation (max 5000 characters)

Field Name: additional_certificate
Label: Additional Certificate
Type: File
File Types: pdf,jpg,png
Max Size: 5120 KB (5MB)
Required: No
```

### 5. University Profile Management

**URL:** `/university/university-profile`

Manage your university information:
- University name
- Description
- Type (Public, Private, Semi-Government)
- City/Location
- Contact information (phone, email, website)
- Established year
- Address
- Tuition fee range
- Facilities (hostel, scholarship availability)
- Logo and cover image upload

### 6. Personal Profile

**URL:** `/university/profile`

Manage your admin account:
- Full name
- Phone number
- Position/title
- Profile image upload
- View last login time

### 7. Change Password

**URL:** `/university/change-password`

Security features:
- Change your password
- Requires current password verification
- Minimum 8 characters for new password

---

## Workflow Examples

### Example 1: Setting Up Your University Programs

1. **Login to Portal**
   - Access `/university/login`
   - Use your university admin credentials

2. **Navigate to Programs**
   - Click "Programs/Courses" in sidebar
   - View currently offered programs

3. **Add Programs**
   - Option A: Click "Add Program" for individual addition
   - Option B: Click "Bulk Add Programs" to add multiple at once
   - Select program from available interests

4. **Configure Tuition Fees**
   - For each program, click "Edit"
   - Set single fee: e.g., $15,000
   - OR set fee range: Min $10,000, Max $20,000
   - Add program duration: e.g., 4 years
   - Add program details and requirements

5. **Students See Your Programs**
   - Students browse programs via mobile app
   - See tuition fees you configured
   - View program details you provided
   - Apply to specific programs

### Example 2: Typical Application Review Process:

1. **Student Submits Application** (via mobile app or API)
   - Student fills out general information
   - Completes custom fields you created
   - Uploads required documents
   - Submits application

2. **University Admin Reviews** (in portal)
   - Login to university portal
   - View new applications in "Submitted" tab
   - Click application to view full details
   - Review all information, documents, and custom field responses

3. **Update Status**
   - Change status to "Under Review"
   - Add admin notes
   - Move to "Shortlisted" if qualified

4. **Interview Process**
   - Update status to "Interview Scheduled"
   - Add notes about interview date/time

5. **Final Decision**
   - Accept or Reject application
   - Add rejection reason if rejected
   - Student is notified (implement email notifications separately)

6. **Bulk Processing**
   - Select multiple applications
   - Bulk update to same status
   - Add bulk admin notes

---

## API Integration

The portal works seamlessly with the existing API system:

### For Students (Mobile App):

Students continue to use the mobile API to:
- View universities
- Get application form (including custom fields)
- Submit applications
- Track application status

**API Endpoint:** `GET /api/applications/universities/{universityId}/form`

This endpoint returns:
- General application fields
- Custom fields created by the university (from the portal)

### For University Admins:

University admins can also use the API if needed:

**API Endpoints:** (Already implemented)
- `GET /api/admin/applications` - List applications
- `GET /api/admin/applications/{id}` - Get application details
- `POST /api/admin/applications/{id}/status` - Update status
- `POST /api/admin/applications/bulk-update-status` - Bulk update

---

## Security Features

1. **Separate Authentication Guard**
   - University admins use separate authentication from regular users
   - Guards prevent cross-authentication issues

2. **Authorization Checks**
   - Universities can only see their own applications
   - Cannot access other universities' data

3. **Status Validation**
   - Only active university admins can login
   - Only active universities can access the portal

4. **Password Security**
   - Passwords are hashed using bcrypt
   - Minimum 8 characters required
   - Current password verification for changes

---

## Customization

### Styling

The portal uses Bootstrap 5 with custom CSS. To customize:

1. Edit `resources/views/university/layouts/master.blade.php`
2. Modify the `<style>` section or create a separate CSS file
3. Change color variables in `:root`

### Adding More Features

To add new features to the portal:

1. **Create a Controller:** `app/Http/Controllers/University/YourController.php`
2. **Add Routes:** In `routes/web.php` under the university group
3. **Create Views:** In `resources/views/university/your-feature/`
4. **Add Menu Item:** In `resources/views/university/layouts/master.blade.php`

---

## Troubleshooting

### Cannot Login

1. **Check university admin exists:**
   ```bash
   php artisan tinker
   >>> App\Models\UniversityAdmin::count();
   ```

2. **Verify password:**
   ```bash
   >>> use Illuminate\Support\Facades\Hash;
   >>> Hash::check('password123', App\Models\UniversityAdmin::first()->password);
   ```

3. **Check university is active:**
   ```bash
   >>> App\Models\UniversityAdmin::first()->university->status;
   ```

### Applications Not Showing

1. **Check university_id:** Make sure applications have the correct `university_id`
2. **Check submitted_at:** Only submitted applications (not drafts) show by default
3. **Clear cache:** `php artisan cache:clear`

### Custom Fields Not Appearing

1. **Check field status:** Field must be active (`status = true`)
2. **Check university_id:** Field must belong to the logged-in university
3. **Clear view cache:** `php artisan view:clear`

---

## Database Schema

### Tables Created:

1. **`university_admins`**
   - `id` - Primary key
   - `university_id` - Foreign key to universities
   - `name` - Admin full name
   - `email` - Login email (unique)
   - `password` - Hashed password
   - `phone` - Contact number
   - `position` - Job title
   - `profile_image` - Profile picture path
   - `status` - Active/inactive
   - `last_login_at` - Last login timestamp
   - `remember_token` - For "remember me" functionality
   - `created_at`, `updated_at`, `deleted_at` - Timestamps

---

## Next Steps

### Recommended Enhancements:

1. **Email Notifications**
   - Send email to students when status changes
   - Send email to admin on new application

2. **Application PDF Export**
   - Generate PDF of application details
   - Download individual application as PDF

3. **Advanced Analytics**
   - More detailed charts and graphs
   - Application trends over time
   - Acceptance rate analytics

4. **Multi-Admin Support**
   - Multiple admins per university
   - Different permission levels
   - Audit log of who changed what

5. **Interview Scheduling**
   - Built-in calendar
   - Automated interview scheduling
   - Email reminders

6. **Document Viewer**
   - In-browser PDF viewer
   - Document annotation tools
   - Quick document comparison

---

## Support

For technical assistance:
- Check Laravel logs: `storage/logs/laravel.log`
- Run `php artisan route:list` to see all university routes
- Use `php artisan tinker` to debug database issues

---

## File Structure Summary

```
app/
├── Http/
│   ├── Controllers/
│   │   └── University/
│   │       ├── UniversityAuthController.php
│   │       ├── UniversityDashboardController.php
│   │       ├── UniversityApplicationController.php
│   │       ├── UniversityFormFieldController.php
│   │       └── UniversityProfileController.php
│   └── Middleware/
│       └── UniversityAuth.php
└── Models/
    └── UniversityAdmin.php

resources/views/university/
├── layouts/
│   └── master.blade.php
├── auth/
│   ├── login.blade.php
│   ├── change-password.blade.php
│   └── profile.blade.php
├── dashboard.blade.php
├── applications/
│   ├── index.blade.php
│   └── show.blade.php
├── form-fields/
│   ├── index.blade.php
│   ├── create.blade.php
│   └── edit.blade.php
└── profile/
    └── show.blade.php

routes/
└── web.php (University routes added)

config/
└── auth.php (University guard added)

database/migrations/
└── 2025_10_23_create_university_admins_table.php
```

---

**Happy Managing! 🎓**

