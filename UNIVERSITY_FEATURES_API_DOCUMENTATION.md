# University Features API Documentation

This document provides comprehensive API documentation for the University Gallery, Scholarships, and Admission Requirements management features.

## Table of Contents
1. [University Gallery Management](#university-gallery-management)
2. [Scholarships Management](#scholarships-management)
3. [Admission Requirements Management](#admission-requirements-management)

---

## University Gallery Management

### Overview
The University Gallery feature allows universities to showcase their campus, facilities, events, and more through images and videos.

### Public Endpoints (No Authentication Required)

#### Get University Gallery
```
GET /api/universities/{universityId}/gallery
```

**Query Parameters:**
- `category` (optional): Filter by category (e.g., campus, facilities, events)
- `featured` (optional): Boolean - Get only featured items
- `per_page` (optional): Items per page (default: 20)

**Example Request:**
```bash
curl -X GET "https://your-domain.com/api/universities/1/gallery?category=campus&featured=1&per_page=10"
```

**Example Response:**
```json
{
    "error": false,
    "message": "University gallery retrieved successfully",
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "university_id": 1,
                "title": "Main Campus Building",
                "description": "Our state-of-the-art main campus building",
                "image_url": "https://your-domain.com/storage/university_galleries/image.jpg",
                "image_type": "image",
                "category": "campus",
                "display_order": 1,
                "is_featured": true,
                "status": true,
                "created_at": "2025-10-27T10:00:00.000000Z",
                "updated_at": "2025-10-27T10:00:00.000000Z"
            }
        ],
        "per_page": 10,
        "total": 25
    }
}
```

### Admin Endpoints (Authentication Required)

#### List All Gallery Items
```
GET /api/admin/university-galleries
```

**Query Parameters:**
- `university_id` (optional): Filter by university
- `category` (optional): Filter by category
- `image_type` (optional): Filter by type (image, video, virtual_tour)
- `featured` (optional): Get only featured items
- `include_inactive` (optional): Include inactive items
- `per_page` (optional): Items per page (default: 15)

#### Create Gallery Item
```
POST /api/admin/university-galleries
```

**Headers:**
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Request Body:**
- `university_id` (required): University ID
- `title` (required): Title of the gallery item
- `description` (optional): Description
- `image_path` (required): Image/video file (max 10MB)
- `image_type` (optional): Type - image, video, or virtual_tour
- `category` (optional): Category name
- `display_order` (optional): Display order (integer)
- `is_featured` (optional): Boolean
- `status` (optional): Boolean

**Example Request:**
```bash
curl -X POST "https://your-domain.com/api/admin/university-galleries" \
  -H "Authorization: Bearer your-token" \
  -F "university_id=1" \
  -F "title=Library" \
  -F "description=Modern library facility" \
  -F "image_path=@/path/to/image.jpg" \
  -F "category=facilities" \
  -F "is_featured=true"
```

#### Update Gallery Item
```
PUT /api/admin/university-galleries/{id}
```

**Request Body:** Same as create, all fields optional

#### Delete Gallery Item
```
DELETE /api/admin/university-galleries/{id}
```

#### Get Single Gallery Item
```
GET /api/admin/university-galleries/{id}
```

---

## Scholarships Management

### Overview
Manage scholarships offered by universities including merit-based, need-based, and other types of financial aid.

### Public Endpoints

#### Get University Scholarships
```
GET /api/universities/{universityId}/scholarships
```

**Query Parameters:**
- `scholarship_type` (optional): Filter by type (merit, need-based, sports, etc.)
- `accepting_applications` (optional): Boolean - Only show scholarships accepting applications
- `include_inactive` (optional): Include inactive scholarships
- `per_page` (optional): Items per page (default: 20)

**Example Response:**
```json
{
    "error": false,
    "message": "University scholarships retrieved successfully",
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "university_id": 1,
                "name": "Merit Scholarship 2025",
                "scholarship_type": "merit",
                "description": "For students with excellent academic records",
                "eligibility_criteria": "Minimum 3.5 GPA required",
                "amount": 50000.00,
                "percentage": null,
                "amount_type": "fixed",
                "total_slots": 10,
                "available_slots": 7,
                "application_start_date": "2025-01-01",
                "application_end_date": "2025-06-30",
                "required_documents": "Transcripts, recommendation letters",
                "application_process": "Apply online through university portal",
                "contact_email": "scholarships@university.edu",
                "contact_phone": "+1234567890",
                "renewable": true,
                "renewable_years": 4,
                "status": true,
                "is_active": true,
                "is_accepting_applications": true,
                "created_at": "2025-10-27T10:00:00.000000Z",
                "updated_at": "2025-10-27T10:00:00.000000Z"
            }
        ],
        "per_page": 20,
        "total": 5
    }
}
```

### Admin Endpoints

#### List All Scholarships
```
GET /api/admin/scholarships
```

**Query Parameters:**
- `university_id` (optional): Filter by university
- `scholarship_type` (optional): Filter by type
- `amount_type` (optional): fixed, percentage, or full
- `renewable` (optional): Boolean
- `accepting_applications` (optional): Boolean
- `include_inactive` (optional): Boolean
- `per_page` (optional): Items per page (default: 15)

#### Create Scholarship
```
POST /api/admin/scholarships
```

**Request Body:**
```json
{
    "university_id": 1,
    "name": "Excellence Scholarship",
    "scholarship_type": "merit",
    "description": "For top-performing students",
    "eligibility_criteria": "Minimum 3.8 GPA, extracurricular activities",
    "amount": 75000.00,
    "percentage": null,
    "amount_type": "fixed",
    "total_slots": 5,
    "available_slots": 5,
    "application_start_date": "2025-01-01",
    "application_end_date": "2025-05-31",
    "required_documents": "Transcripts, essays, recommendations",
    "application_process": "Submit online application with supporting documents",
    "contact_email": "scholarships@university.edu",
    "contact_phone": "+1234567890",
    "renewable": true,
    "renewable_years": 4,
    "status": true
}
```

**Amount Types:**
- `fixed`: Fixed amount in currency
- `percentage`: Percentage discount (e.g., 50% tuition waiver)
- `full`: Full scholarship covering all costs

**Scholarship Types Examples:**
- merit
- need-based
- sports
- academic
- research
- minority
- international

#### Update Scholarship
```
PUT /api/admin/scholarships/{id}
```

#### Delete Scholarship
```
DELETE /api/admin/scholarships/{id}
```

#### Get Single Scholarship
```
GET /api/admin/scholarships/{id}
```

---

## Admission Requirements Management

### Overview
Define and manage admission requirements for universities and specific programs.

### Public Endpoints

#### Get University Admission Requirements
```
GET /api/universities/{universityId}/admission-requirements
```

**Query Parameters:**
- `program_level` (optional): Filter by level (undergraduate, postgraduate, diploma)
- `interest_id` (optional): Filter by specific program
- `general_only` (optional): Boolean - Get only general requirements (not program-specific)
- `per_page` (optional): Items per page (default: 20)

**Example Response:**
```json
{
    "error": false,
    "message": "University admission requirements retrieved successfully",
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "university_id": 1,
                "interest_id": null,
                "program_level": "undergraduate",
                "general_requirements": "High school diploma or equivalent",
                "academic_requirements": "Minimum 60% in high school",
                "minimum_percentage": 60.00,
                "minimum_gpa": 2.5,
                "entrance_tests": "SAT or ACT",
                "minimum_test_score": 1200,
                "required_documents": "Transcripts, ID, passport photos",
                "language_requirements": "IELTS 6.0 or TOEFL 80",
                "language_test_score": 6.0,
                "specific_requirements": null,
                "application_deadline": "2025-07-31",
                "application_fee": 5000.00,
                "admission_process": "Online application followed by document verification",
                "estimated_processing_days": 30,
                "interview_required": "May be required for some programs",
                "portfolio_required": "Required for arts and design programs",
                "status": true,
                "created_at": "2025-10-27T10:00:00.000000Z",
                "updated_at": "2025-10-27T10:00:00.000000Z",
                "interest": null
            }
        ],
        "per_page": 20,
        "total": 8
    }
}
```

#### Get Program-Specific Admission Requirements
```
GET /api/universities/{universityId}/programs/{interestId}/admission-requirements
```

Returns admission requirements specific to a program/interest.

### Admin Endpoints

#### List All Admission Requirements
```
GET /api/admin/admission-requirements
```

**Query Parameters:**
- `university_id` (optional): Filter by university
- `interest_id` (optional): Filter by program
- `program_level` (optional): Filter by level
- `general_only` (optional): Boolean
- `include_inactive` (optional): Boolean
- `per_page` (optional): Items per page (default: 15)

#### Create Admission Requirement
```
POST /api/admin/admission-requirements
```

**Request Body:**
```json
{
    "university_id": 1,
    "interest_id": 5,
    "program_level": "undergraduate",
    "general_requirements": "High school diploma",
    "academic_requirements": "Strong background in sciences",
    "minimum_percentage": 70.00,
    "minimum_gpa": 3.0,
    "entrance_tests": "SAT Subject Tests in Biology and Chemistry",
    "minimum_test_score": 650,
    "required_documents": "Transcripts, recommendations, personal statement",
    "language_requirements": "IELTS 6.5 or TOEFL 90",
    "language_test_score": 6.5,
    "specific_requirements": "Laboratory experience preferred",
    "application_deadline": "2025-06-30",
    "application_fee": 7500.00,
    "admission_process": "Online application, document submission, interview",
    "estimated_processing_days": 45,
    "interview_required": "Yes, for shortlisted candidates",
    "portfolio_required": "No",
    "status": true
}
```

**Program Levels:**
- undergraduate
- postgraduate
- diploma
- certificate
- doctoral
- associate

#### Update Admission Requirement
```
PUT /api/admin/admission-requirements/{id}
```

#### Delete Admission Requirement
```
DELETE /api/admin/admission-requirements/{id}
```

#### Get Single Admission Requirement
```
GET /api/admin/admission-requirements/{id}
```

---

## Error Responses

All endpoints may return the following error responses:

### 401 Unauthorized
```json
{
    "error": true,
    "message": "Unauthenticated"
}
```

### 404 Not Found
```json
{
    "error": true,
    "message": "Resource not found",
    "data": "Error details"
}
```

### 422 Validation Error
```json
{
    "error": true,
    "message": "Validation failed",
    "data": {
        "field_name": ["Error message"]
    }
}
```

### 500 Server Error
```json
{
    "error": true,
    "message": "Failed to perform operation",
    "data": "Error details"
}
```

---

## Database Migrations

To set up the database tables, run:

```bash
php artisan migrate
```

This will create the following tables:
- `university_galleries` - Stores gallery images and videos
- `scholarships` - Stores scholarship information
- `admission_requirements` - Stores admission requirements

---

## Models and Relationships

### University Model
The `University` model now includes the following relationships:

```php
// Get all galleries
$university->galleries;

// Get active galleries
$university->activeGalleries;

// Get all scholarships
$university->scholarships;

// Get active scholarships
$university->activeScholarships;

// Get admission requirements
$university->admissionRequirements;

// Get active admission requirements
$university->activeAdmissionRequirements;
```

---

## Usage Examples

### Example 1: Display University Gallery on Frontend

```javascript
// Fetch gallery for a university
fetch('/api/universities/1/gallery?featured=1')
  .then(response => response.json())
  .then(data => {
    if (!data.error) {
      const gallery = data.data.data;
      // Display gallery items
      gallery.forEach(item => {
        console.log(item.title, item.image_url);
      });
    }
  });
```

### Example 2: List Available Scholarships

```javascript
// Fetch scholarships accepting applications
fetch('/api/universities/1/scholarships?accepting_applications=1')
  .then(response => response.json())
  .then(data => {
    if (!data.error) {
      const scholarships = data.data.data;
      scholarships.forEach(scholarship => {
        console.log(scholarship.name, scholarship.amount);
      });
    }
  });
```

### Example 3: Check Admission Requirements

```javascript
// Fetch admission requirements for a specific program
fetch('/api/universities/1/programs/5/admission-requirements')
  .then(response => response.json())
  .then(data => {
    if (!data.error) {
      const requirements = data.data;
      requirements.forEach(req => {
        console.log(req.minimum_gpa, req.entrance_tests);
      });
    }
  });
```

---

## Notes

1. **Authentication**: Admin endpoints require authentication via Sanctum tokens
2. **File Uploads**: Gallery images support formats: jpeg, png, jpg, gif, mp4, webp (max 10MB)
3. **Soft Deletes**: All models support soft deletes for data recovery
4. **Timestamps**: All records include created_at and updated_at timestamps
5. **Pagination**: All list endpoints support pagination
6. **Filtering**: Extensive filtering options available for all list endpoints

---

## Support

For questions or issues, please contact the development team or refer to the main API documentation.

