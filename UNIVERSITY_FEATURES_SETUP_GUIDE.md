# University Features Setup Guide

This guide will help you set up and use the new University Gallery, Scholarships, and Admission Requirements features.

## Overview

Three new features have been added to the university management system:

1. **Gallery Management** - Showcase university campus, facilities, and events through images and videos
2. **Scholarships Management** - Manage and display available scholarships
3. **Admission Requirements** - Define admission requirements for universities and programs

---

## Quick Setup

### Step 1: Run Migrations

Run the database migrations to create the necessary tables:

```bash
php artisan migrate
```

This will create three new tables:
- `university_galleries`
- `scholarships`
- `admission_requirements`

### Step 2: Clear Cache (Optional)

Clear the application cache to ensure the new routes and services are registered:

```bash
php artisan config:clear
php artisan route:clear
php artisan cache:clear
```

### Step 3: Test the API

You can now test the API endpoints. See the [API Documentation](UNIVERSITY_FEATURES_API_DOCUMENTATION.md) for details.

---

## What Was Created

### 1. Database Migrations (3 files)

Located in `database/migrations/`:
- `2025_10_27_000001_create_university_galleries_table.php`
- `2025_10_27_000002_create_scholarships_table.php`
- `2025_10_27_000003_create_admission_requirements_table.php`

### 2. Models (3 files)

Located in `app/Models/`:
- `UniversityGallery.php` - Manages gallery items
- `Scholarship.php` - Manages scholarships with dates and slots
- `AdmissionRequirement.php` - Manages admission requirements

### 3. Repositories (6 files)

Located in `app/Repositories/`:
- `UniversityGallery/UniversityGalleryInterface.php`
- `UniversityGallery/UniversityGalleryRepository.php`
- `Scholarship/ScholarshipInterface.php`
- `Scholarship/ScholarshipRepository.php`
- `AdmissionRequirement/AdmissionRequirementInterface.php`
- `AdmissionRequirement/AdmissionRequirementRepository.php`

### 4. Controllers (3 files)

Located in `app/Http/Controllers/Api/`:
- `UniversityGalleryController.php` - CRUD operations for gallery
- `ScholarshipController.php` - CRUD operations for scholarships
- `AdmissionRequirementController.php` - CRUD operations for admission requirements

### 5. Updated Files

- `routes/api.php` - Added new API routes
- `app/Models/University.php` - Added relationships to new models
- `app/Providers/RepositoryServiceProvider.php` - Registered new repositories

---

## Features Overview

### Gallery Management

**Key Features:**
- Upload images and videos
- Categorize by type (campus, facilities, events, etc.)
- Feature important items
- Custom display order
- Soft delete support

**Public API:**
- `GET /api/universities/{id}/gallery` - View gallery

**Admin API:**
- Full CRUD operations
- Filter by category, type, featured status
- Bulk management capabilities

### Scholarships Management

**Key Features:**
- Multiple scholarship types (merit, need-based, sports, etc.)
- Three amount types: fixed amount, percentage, or full
- Application date ranges
- Slot management (total and available)
- Renewable scholarships
- Automatic status calculation

**Public API:**
- `GET /api/universities/{id}/scholarships` - View scholarships
- Filter by type and availability

**Admin API:**
- Full CRUD operations
- Track application slots
- Manage eligibility criteria

### Admission Requirements

**Key Features:**
- General and program-specific requirements
- Multiple program levels (undergraduate, postgraduate, etc.)
- Academic requirements (GPA, percentage)
- Entrance test requirements
- Language requirements
- Document requirements
- Application deadlines and fees

**Public API:**
- `GET /api/universities/{id}/admission-requirements` - View requirements
- `GET /api/universities/{id}/programs/{programId}/admission-requirements` - Program-specific

**Admin API:**
- Full CRUD operations
- Filter by program level
- Manage general and specific requirements

---

## API Routes Summary

### Public Routes (No Authentication)

```
GET /api/universities/{id}/gallery
GET /api/universities/{id}/scholarships
GET /api/universities/{id}/admission-requirements
GET /api/universities/{id}/programs/{programId}/admission-requirements
```

### Admin Routes (Authentication Required)

```
# Gallery
GET    /api/admin/university-galleries
POST   /api/admin/university-galleries
GET    /api/admin/university-galleries/{id}
PUT    /api/admin/university-galleries/{id}
DELETE /api/admin/university-galleries/{id}

# Scholarships
GET    /api/admin/scholarships
POST   /api/admin/scholarships
GET    /api/admin/scholarships/{id}
PUT    /api/admin/scholarships/{id}
DELETE /api/admin/scholarships/{id}

# Admission Requirements
GET    /api/admin/admission-requirements
POST   /api/admin/admission-requirements
GET    /api/admin/admission-requirements/{id}
PUT    /api/admin/admission-requirements/{id}
DELETE /api/admin/admission-requirements/{id}
```

---

## Usage Examples

### Adding a Gallery Item (Admin)

```bash
curl -X POST "https://your-domain.com/api/admin/university-galleries" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -F "university_id=1" \
  -F "title=Main Library" \
  -F "description=State-of-the-art library facility" \
  -F "image_path=@/path/to/image.jpg" \
  -F "category=facilities" \
  -F "is_featured=true"
```

### Creating a Scholarship (Admin)

```bash
curl -X POST "https://your-domain.com/api/admin/scholarships" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "university_id": 1,
    "name": "Merit Scholarship 2025",
    "scholarship_type": "merit",
    "description": "For outstanding students",
    "eligibility_criteria": "Minimum 3.5 GPA",
    "amount": 50000,
    "amount_type": "fixed",
    "total_slots": 10,
    "available_slots": 10,
    "application_start_date": "2025-01-01",
    "application_end_date": "2025-06-30",
    "renewable": true,
    "renewable_years": 4,
    "status": true
  }'
```

### Adding Admission Requirements (Admin)

```bash
curl -X POST "https://your-domain.com/api/admin/admission-requirements" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "university_id": 1,
    "program_level": "undergraduate",
    "general_requirements": "High school diploma",
    "minimum_percentage": 60,
    "minimum_gpa": 2.5,
    "entrance_tests": "SAT or ACT",
    "minimum_test_score": 1200,
    "required_documents": "Transcripts, ID, passport photos",
    "application_deadline": "2025-07-31",
    "application_fee": 5000,
    "status": true
  }'
```

### Viewing Gallery (Public)

```bash
curl -X GET "https://your-domain.com/api/universities/1/gallery?category=campus&featured=1"
```

### Viewing Scholarships (Public)

```bash
curl -X GET "https://your-domain.com/api/universities/1/scholarships?accepting_applications=1"
```

### Viewing Admission Requirements (Public)

```bash
curl -X GET "https://your-domain.com/api/universities/1/admission-requirements?program_level=undergraduate"
```

---

## Database Schema

### university_galleries Table
- id
- university_id (foreign key)
- title
- description
- image_path
- image_type (image, video, virtual_tour)
- category
- display_order
- is_featured
- status
- timestamps
- soft deletes

### scholarships Table
- id
- university_id (foreign key)
- name
- scholarship_type
- description
- eligibility_criteria
- amount
- percentage
- amount_type (fixed, percentage, full)
- total_slots
- available_slots
- application_start_date
- application_end_date
- required_documents
- application_process
- contact_email
- contact_phone
- renewable
- renewable_years
- status
- timestamps
- soft deletes

### admission_requirements Table
- id
- university_id (foreign key)
- interest_id (foreign key, nullable)
- program_level
- general_requirements
- academic_requirements
- minimum_percentage
- minimum_gpa
- entrance_tests
- minimum_test_score
- required_documents
- language_requirements
- language_test_score
- specific_requirements
- application_deadline
- application_fee
- admission_process
- estimated_processing_days
- interview_required
- portfolio_required
- status
- timestamps
- soft deletes

---

## Model Scopes

### UniversityGallery
- `active()` - Get only active galleries
- `featured()` - Get featured galleries
- `ordered()` - Order by display_order and created_at

### Scholarship
- `active()` - Get active scholarships
- `acceptingApplications()` - Get scholarships currently accepting applications
- `ofType($type)` - Filter by scholarship type

### AdmissionRequirement
- `active()` - Get active requirements
- `forLevel($level)` - Filter by program level
- `forProgram($interestId)` - Filter by program/interest
- `general()` - Get general requirements (not program-specific)

---

## Testing Checklist

- [ ] Run migrations successfully
- [ ] Test creating a gallery item with image upload
- [ ] Test creating a scholarship
- [ ] Test creating admission requirements
- [ ] Test public endpoints for viewing gallery
- [ ] Test public endpoints for viewing scholarships
- [ ] Test public endpoints for viewing admission requirements
- [ ] Test filtering options on all endpoints
- [ ] Test update operations
- [ ] Test delete operations (soft delete)
- [ ] Test authorization on admin endpoints

---

## Frontend Integration

### Displaying Gallery

```javascript
// Fetch and display gallery
async function loadGallery(universityId) {
  const response = await fetch(`/api/universities/${universityId}/gallery?featured=1`);
  const data = await response.json();
  
  if (!data.error) {
    const gallery = data.data.data;
    // Render gallery items
    gallery.forEach(item => {
      // Display item.image_url, item.title, item.description
    });
  }
}
```

### Displaying Scholarships

```javascript
// Fetch and display scholarships
async function loadScholarships(universityId) {
  const response = await fetch(`/api/universities/${universityId}/scholarships?accepting_applications=1`);
  const data = await response.json();
  
  if (!data.error) {
    const scholarships = data.data.data;
    scholarships.forEach(scholarship => {
      // Display scholarship details
      console.log(scholarship.name, scholarship.amount, scholarship.is_accepting_applications);
    });
  }
}
```

### Displaying Admission Requirements

```javascript
// Fetch and display admission requirements
async function loadAdmissionRequirements(universityId, programLevel) {
  const response = await fetch(`/api/universities/${universityId}/admission-requirements?program_level=${programLevel}`);
  const data = await response.json();
  
  if (!data.error) {
    const requirements = data.data.data;
    requirements.forEach(req => {
      // Display requirements
      console.log(req.minimum_gpa, req.entrance_tests);
    });
  }
}
```

---

## Troubleshooting

### Issue: Routes not found
**Solution:** Clear route cache:
```bash
php artisan route:clear
php artisan route:cache
```

### Issue: Repository not found
**Solution:** Make sure RepositoryServiceProvider is registered in `config/app.php` and run:
```bash
php artisan config:clear
```

### Issue: Image upload fails
**Solution:** 
1. Check storage permissions: `chmod -R 775 storage`
2. Create symbolic link: `php artisan storage:link`
3. Check max upload size in php.ini

### Issue: Validation errors
**Solution:** Check the API documentation for required fields and data types

---

## Next Steps

1. Run the migrations
2. Test the API endpoints using Postman or similar tool
3. Integrate the endpoints into your frontend application
4. Add any custom business logic as needed
5. Configure file upload settings if needed

---

## Support

For detailed API documentation, see [UNIVERSITY_FEATURES_API_DOCUMENTATION.md](UNIVERSITY_FEATURES_API_DOCUMENTATION.md)

For questions or issues, please contact the development team.

