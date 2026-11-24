# University Application System - Complete Guide

## Overview
The University Application System allows students to apply to universities with both general fields and university-specific custom fields. Universities can define their own custom application requirements, and the system tracks application status throughout the admission process.

## Key Features

### For Students
- ✅ Fill out standardized application forms with general fields
- ✅ Upload required documents (transcripts, ID, photo, recommendation letters)
- ✅ Complete university-specific custom fields
- ✅ Save applications as drafts
- ✅ Submit applications when ready
- ✅ Track application status
- ✅ Withdraw applications if needed
- ✅ View application history

### For University Admins
- ✅ Define custom application fields specific to their institution
- ✅ Manage field types (text, file upload, dropdown, etc.)
- ✅ Set required/optional fields
- ✅ Review all applications
- ✅ Update application status (submitted, under review, accepted, etc.)
- ✅ Add admin notes to applications
- ✅ Bulk update application statuses
- ✅ View application statistics
- ✅ Filter and search applications

## Database Structure

### Tables Created

1. **applications** - Main application data
2. **university_application_fields** - Custom fields defined by universities
3. **application_field_responses** - Student responses to custom fields

## Application Status Flow

```
draft → submitted → under_review → shortlisted → interview_scheduled → accepted/rejected
                                                                      ↓
                                                                  withdrawn
```

### Status Definitions

- **draft** - Application saved but not submitted
- **submitted** - Application submitted by student
- **under_review** - Being reviewed by university admin
- **shortlisted** - Student shortlisted for next round
- **interview_scheduled** - Interview scheduled
- **accepted** - Application accepted
- **rejected** - Application rejected
- **withdrawn** - Student withdrew application

## API Endpoints

### Student APIs (Authentication Required)

#### 1. Get Application Form for a University
```
GET /api/applications/universities/{universityId}/form
```

**Purpose:** Get the application form structure including general fields and university-specific custom fields.

**Response:**
```json
{
  "error": false,
  "message": "Application form retrieved successfully",
  "data": {
    "university": {
      "id": 1,
      "name": "Stanford University",
      "interests": [...]
    },
    "custom_fields": [
      {
        "id": 1,
        "field_name": "portfolio",
        "field_label": "Portfolio URL",
        "field_type": "url",
        "is_required": true,
        "field_description": "Please provide your portfolio URL"
      }
    ],
    "general_fields": {
      "first_name": {"type": "text", "required": true},
      "last_name": {"type": "text", "required": true},
      ...
    }
  }
}
```

#### 2. Get My Applications
```
GET /api/applications/my-applications?status=submitted&per_page=15
```

**Query Parameters:**
- `status` - Filter by status (draft, submitted, etc.)
- `per_page` - Results per page (default: 15)

**Response:**
```json
{
  "error": false,
  "message": "Applications retrieved successfully",
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "application_number": "APP2025000001",
        "university": {...},
        "interest": {...},
        "status": "submitted",
        "submitted_at": "2025-10-21T10:00:00.000000Z"
      }
    ]
  }
}
```

#### 3. Create/Save Draft Application
```
POST /api/applications
```

**Request Body:**
```json
{
  "university_id": 1,
  "interest_id": 3,
  "first_name": "John",
  "last_name": "Doe",
  "email": "john@example.com",
  "phone": "+1234567890",
  "date_of_birth": "2000-01-15",
  "gender": "male",
  "current_address": "123 Main St, City, State",
  "nationality": "American",
  "previous_school": "Example High School",
  "previous_gpa": 3.8,
  "graduation_year": 2018,
  "application_message": "I am passionate about...",
  "custom_fields": {
    "1": "https://myportfolio.com",
    "2": "I chose this program because..."
  }
}
```

**File Uploads (use multipart/form-data):**
- `transcript_file`
- `id_document_file`
- `photo_file`
- `recommendation_letter_file`
- `custom_fields.{field_id}` (for file type custom fields)

**Response:**
```json
{
  "error": false,
  "message": "Application draft saved successfully",
  "data": {
    "id": 1,
    "application_number": "APP2025000001",
    "status": "draft",
    ...
  }
}
```

#### 4. Update Draft Application
```
PUT /api/applications/{id}
```

**Request Body:** (Same as create, but all fields optional)

**Note:** Only draft applications can be updated.

#### 5. Submit Application
```
POST /api/applications/{id}/submit
```

**Purpose:** Submit a draft application for review.

**Response:**
```json
{
  "error": false,
  "message": "Application submitted successfully",
  "data": {
    "id": 1,
    "status": "submitted",
    "submitted_at": "2025-10-21T10:30:00.000000Z"
  }
}
```

#### 6. Withdraw Application
```
POST /api/applications/{id}/withdraw
```

**Purpose:** Withdraw a submitted application.

**Note:** Can only withdraw applications with status: submitted, under_review, or shortlisted.

#### 7. Get Single Application
```
GET /api/applications/{id}
```

#### 8. Delete Draft Application
```
DELETE /api/applications/{id}
```

**Note:** Only draft applications can be deleted.

---

### Admin APIs (Authentication Required)

#### 1. Get All Applications (Admin View)
```
GET /api/admin/applications
```

**Query Parameters:**
- `university_id` - Filter by university
- `status` - Filter by status
- `interest_id` - Filter by program/interest
- `search` - Search by application number, name, or email
- `date_from` - Filter by submission date (from)
- `date_to` - Filter by submission date (to)
- `include_drafts` - Include draft applications (default: false)
- `per_page` - Results per page (default: 15)

**Response:**
```json
{
  "error": false,
  "message": "Applications retrieved successfully",
  "data": {...},
  "statistics": {
    "total": 150,
    "submitted": 120,
    "under_review": 45,
    "shortlisted": 20,
    "accepted": 15,
    "rejected": 10
  }
}
```

#### 2. Get Application Statistics
```
GET /api/admin/applications/statistics?university_id=1
```

**Response:**
```json
{
  "error": false,
  "message": "Statistics retrieved successfully",
  "data": {
    "overview": {
      "total_applications": 150,
      "submitted": 120,
      "under_review": 45,
      "accepted": 15,
      "this_month": 25,
      "this_year": 150
    },
    "by_program": [
      {
        "interest": "Computer Science",
        "count": 50
      },
      {
        "interest": "Business Administration",
        "count": 30
      }
    ]
  }
}
```

#### 3. Update Application Status
```
POST /api/admin/applications/{id}/status
```

**Request Body:**
```json
{
  "status": "accepted",
  "admin_notes": "Strong candidate with excellent credentials",
  "rejection_reason": "Does not meet minimum requirements" // Required if status is 'rejected'
}
```

#### 4. Add Admin Notes
```
POST /api/admin/applications/{id}/notes
```

**Request Body:**
```json
{
  "admin_notes": "Follow up needed regarding transcript verification"
}
```

#### 5. Bulk Update Application Status
```
POST /api/admin/applications/bulk-update-status
```

**Request Body:**
```json
{
  "application_ids": [1, 2, 3, 4, 5],
  "status": "under_review",
  "admin_notes": "Batch processing for review"
}
```

---

### Custom Field Management APIs (Admin)

#### 1. Get All Custom Fields for a University
```
GET /api/admin/universities/{universityId}/fields
```

**Response:**
```json
{
  "error": false,
  "message": "Custom fields retrieved successfully",
  "data": [
    {
      "id": 1,
      "university_id": 1,
      "field_name": "portfolio",
      "field_label": "Portfolio URL",
      "field_type": "url",
      "is_required": true,
      "sort_order": 0,
      "status": true
    }
  ]
}
```

#### 2. Create Custom Field
```
POST /api/admin/universities/{universityId}/fields
```

**Request Body:**
```json
{
  "field_name": "essay",
  "field_label": "Personal Essay",
  "field_description": "Write a 500-word essay about your motivation",
  "field_type": "textarea",
  "is_required": true,
  "sort_order": 0,
  "status": true
}
```

**Field Types Available:**
- `text` - Single line text
- `textarea` - Multi-line text
- `number` - Numeric input
- `date` - Date picker
- `file` - File upload
- `select` - Dropdown menu
- `checkbox` - Multiple selection
- `radio` - Single selection
- `email` - Email input
- `url` - URL input

**For File Type Fields:**
```json
{
  "field_name": "medical_certificate",
  "field_label": "Medical Certificate",
  "field_type": "file",
  "file_types": "pdf,doc,docx",
  "max_file_size": 5120,
  "is_required": false
}
```

**For Select/Checkbox/Radio Fields:**
```json
{
  "field_name": "scholarship_interest",
  "field_label": "Scholarship Interest",
  "field_type": "select",
  "field_options": ["Merit-based", "Need-based", "Athletic", "Not interested"],
  "is_required": false
}
```

#### 3. Update Custom Field
```
PUT /api/admin/universities/{universityId}/fields/{fieldId}
```

#### 4. Delete Custom Field
```
DELETE /api/admin/universities/{universityId}/fields/{fieldId}
```

**Note:** Cannot delete fields that have existing responses. Deactivate them instead.

#### 5. Reorder Fields
```
POST /api/admin/universities/{universityId}/fields/reorder
```

**Request Body:**
```json
{
  "field_orders": [
    {"id": 1, "sort_order": 0},
    {"id": 2, "sort_order": 1},
    {"id": 3, "sort_order": 2}
  ]
}
```

#### 6. Toggle Field Status
```
POST /api/admin/universities/{universityId}/fields/{fieldId}/toggle-status
```

**Purpose:** Activate/deactivate a field without deleting it.

---

## General Application Fields

Every application includes these standardized fields:

### Required Fields
- First Name
- Last Name
- Email
- Phone
- Date of Birth
- Gender (male, female, other)
- Current Address
- Nationality

### Optional Fields
- Permanent Address
- Previous School/Institution
- Previous Degree
- Previous GPA (0-4 scale)
- Graduation Year

### Document Uploads (Optional)
- Academic Transcript (PDF, DOC, DOCX - max 5MB)
- ID Document (PDF, JPG, PNG - max 5MB)
- Passport Photo (JPG, PNG - max 2MB)
- Recommendation Letter (PDF, DOC, DOCX - max 5MB)

### Additional
- Personal Statement/Message (up to 5000 characters)

---

## Use Case Examples

### Example 1: Student Applying to University

**Step 1:** Get application form
```
GET /api/applications/universities/1/form
```

**Step 2:** Save draft with basic info
```
POST /api/applications
{
  "university_id": 1,
  "interest_id": 3,
  "first_name": "Jane",
  "last_name": "Smith",
  ...
}
```

**Step 3:** Upload documents (update draft)
```
PUT /api/applications/1
Content-Type: multipart/form-data

- transcript_file: [file]
- id_document_file: [file]
- photo_file: [file]
```

**Step 4:** Complete custom fields (update draft)
```
PUT /api/applications/1
{
  "custom_fields": {
    "1": "https://myportfolio.com",
    "2": "My essay about..."
  }
}
```

**Step 5:** Submit application
```
POST /api/applications/1/submit
```

### Example 2: University Admin Setting Up Custom Fields

**Step 1:** Create essay field
```
POST /api/admin/universities/1/fields
{
  "field_name": "motivation_essay",
  "field_label": "Motivation Essay",
  "field_description": "Explain why you want to join this program (500 words)",
  "field_type": "textarea",
  "is_required": true
}
```

**Step 2:** Create portfolio field
```
POST /api/admin/universities/1/fields
{
  "field_name": "portfolio",
  "field_label": "Portfolio",
  "field_type": "file",
  "file_types": "pdf",
  "max_file_size": 10240,
  "is_required": true
}
```

### Example 3: University Admin Reviewing Applications

**Step 1:** Get all applications
```
GET /api/admin/applications?university_id=1&status=submitted
```

**Step 2:** Review specific application
```
GET /api/admin/applications/15
```

**Step 3:** Update status to shortlisted
```
POST /api/admin/applications/15/status
{
  "status": "shortlisted",
  "admin_notes": "Strong candidate, schedule interview"
}
```

**Step 4:** Bulk update multiple applications
```
POST /api/admin/applications/bulk-update-status
{
  "application_ids": [15, 16, 17, 18],
  "status": "interview_scheduled"
}
```

---

## Validation Rules

### General Fields
- `first_name`, `last_name` - Required, max 255 characters
- `email` - Required, valid email format
- `phone` - Required, max 20 characters
- `date_of_birth` - Required, must be before today
- `gender` - Required, one of: male, female, other
- `current_address` - Required, text
- `nationality` - Required, max 100 characters
- `previous_gpa` - Optional, numeric, 0-4
- `graduation_year` - Optional, 4 digits, 1950-current year

### File Uploads
- `transcript_file` - PDF, DOC, DOCX, max 5MB
- `id_document_file` - PDF, JPG, PNG, max 5MB
- `photo_file` - JPG, PNG, max 2MB
- `recommendation_letter_file` - PDF, DOC, DOCX, max 5MB

### Custom Fields
Validation is dynamic based on field configuration:
- Required fields must be filled
- File uploads must match allowed types and size limits
- Text fields max 5000 characters
- URLs must be valid URL format
- Emails must be valid email format

---

## Application Number Format

Applications are automatically assigned a unique number:
```
APP{YEAR}{SEQUENTIAL_NUMBER}

Examples:
- APP2025000001
- APP2025000002
- APP2025000150
```

The counter resets each year.

---

## File Storage

All uploaded files are stored in:
- General documents: `storage/app/public/applications/documents/`
- Custom field files: `storage/app/public/applications/custom_fields/`

Files are automatically deleted when:
- Draft applications are deleted
- Applications are force-deleted
- Custom field responses are deleted

---

## Security Considerations

1. **Authentication:** All endpoints require authentication via Sanctum
2. **Authorization:** Students can only view/edit their own applications
3. **File Validation:** All file uploads are validated for type and size
4. **Data Privacy:** Student data is protected and only accessible to authorized users
5. **Soft Deletes:** Applications use soft deletes for data retention

---

## Status Tracking Features

### Student View
- See current status of each application
- View admin notes (if allowed)
- Track submission and review dates
- Know who reviewed the application

### Admin View
- Filter applications by status
- Bulk update statuses
- Add private admin notes
- Track reviewer and review date
- View complete application history

---

## Best Practices

### For Students
1. Save drafts frequently
2. Complete all required fields before submitting
3. Upload clear, legible documents
4. Review custom field requirements carefully
5. Submit applications before deadlines

### For University Admins
1. Define clear, specific custom field labels and descriptions
2. Only mark fields as required if truly necessary
3. Set appropriate file size limits
4. Review applications in batches for efficiency
5. Add detailed admin notes for record keeping
6. Use bulk operations for common actions

---

## Error Handling

All API responses follow this format:

**Success:**
```json
{
  "error": false,
  "message": "Success message",
  "data": {...}
}
```

**Error:**
```json
{
  "error": true,
  "message": "Error message",
  "data": "Detailed error information or validation errors"
}
```

**Common HTTP Status Codes:**
- `200` - Success
- `201` - Created
- `400` - Bad Request
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error

---

## Next Steps

1. Implement email notifications for status changes
2. Add PDF export for applications
3. Implement interview scheduling system
4. Add application analytics dashboard
5. Implement document verification system
6. Add multi-language support

---

## Support

For technical issues or questions, refer to:
- API Documentation: `API_DOCUMENTATION.md`
- Implementation Summary: `IMPLEMENTATION_SUMMARY.md`
- Quick Start Guide: `QUICK_START.md`

