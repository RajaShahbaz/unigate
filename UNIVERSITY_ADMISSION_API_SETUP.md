# University Admission System - Setup & Quick Start Guide

## 📋 Overview

This system provides a complete API solution for managing student university admissions with the following features:

✅ **Student Features:**
- Add/manage education history (Matric, Intermediate, Graduation, etc.)
- Select multiple interests/departments (Software Engineering, Computer Science, etc.)
- Select multiple preferred cities for study
- Browse universities based on preferences
- Add universities to favorites

✅ **Admin Features:**
- Manage universities with detailed information
- Manage academic interests/departments
- Manage cities
- Assign programs to universities with fees and duration

---

## 🚀 Quick Setup

### Step 1: Run Migrations

```bash
php artisan migrate
```

This will create the following tables:
- `user_education` - Student education records
- `interests` - Academic fields/departments
- `user_interests` - Student selected interests
- `cities` - Available cities
- `user_cities` - Student preferred cities
- `universities` - University information
- `university_interests` - University programs with fees
- `user_favorites` - Student favorite universities

### Step 2: Seed Sample Data

```bash
# Seed interests and cities
php artisan db:seed --class=InterestsAndCitiesSeeder

# Seed universities
php artisan db:seed --class=UniversitySeeder
```

This will populate:
- 15 sample interests (Software Engineering, Computer Science, etc.)
- 15 Pakistani cities (Lahore, Karachi, Islamabad, etc.)
- 10 sample universities (FAST, NUST, UET, LUMS, etc.) with complete details and programs

---

## 📁 Files Created

### Database Migrations
1. `database/migrations/2025_10_09_000001_create_user_education_table.php`
2. `database/migrations/2025_10_09_000002_create_interests_table.php`
3. `database/migrations/2025_10_09_000003_create_user_interests_table.php`
4. `database/migrations/2025_10_09_000004_create_cities_table.php`
5. `database/migrations/2025_10_09_000005_create_user_cities_table.php`
6. `database/migrations/2025_10_09_000006_create_universities_table.php`
7. `database/migrations/2025_10_09_000007_create_university_interests_table.php`
8. `database/migrations/2025_10_09_000008_create_user_favorites_table.php`

### Models
1. `app/Models/UserEducation.php`
2. `app/Models/Interest.php`
3. `app/Models/City.php`
4. `app/Models/University.php`
5. `app/Models/UserFavorite.php`
6. Updated: `app/Models/User.php` (added relationships)

### API Controllers (User)
1. `app/Http/Controllers/Api/UserEducationController.php`
2. `app/Http/Controllers/Api/UserInterestController.php`
3. `app/Http/Controllers/Api/UserCityController.php`
4. `app/Http/Controllers/Api/UniversityController.php`
5. `app/Http/Controllers/Api/FavoriteController.php`

### API Controllers (Admin)
1. `app/Http/Controllers/Api/Admin/UniversityAdminController.php`
2. `app/Http/Controllers/Api/Admin/InterestAdminController.php`
3. `app/Http/Controllers/Api/Admin/CityAdminController.php`

### Seeders
1. `database/seeders/InterestsAndCitiesSeeder.php`
2. `database/seeders/UniversitySeeder.php`

### Documentation
1. `API_DOCUMENTATION.md` - Complete API documentation

---

## 🔌 API Endpoints Summary

### Public Endpoints (No Auth Required)
```
GET  /api/universities              # Browse all universities
GET  /api/universities/{id}         # Get university details
GET  /api/interests/all             # Get all interests
GET  /api/cities/all                # Get all cities
```

### Student Endpoints (Auth Required)
```
# Education Management
GET    /api/education                # Get user education
POST   /api/education                # Add education
PUT    /api/education/{id}           # Update education
DELETE /api/education/{id}           # Delete education

# Interests Management
GET    /api/interests                # Get user interests
POST   /api/interests                # Add interests
POST   /api/interests/sync           # Replace all interests
DELETE /api/interests/{id}           # Remove interest

# Cities Management
GET    /api/preferred-cities         # Get user cities
POST   /api/preferred-cities         # Add cities
POST   /api/preferred-cities/sync    # Replace all cities
DELETE /api/preferred-cities/{id}    # Remove city

# Universities
GET    /api/universities/recommended # Get recommended universities

# Favorites
GET    /api/favorites                # Get favorites
POST   /api/favorites                # Add to favorites
POST   /api/favorites/toggle         # Toggle favorite
DELETE /api/favorites/{id}           # Remove from favorites
```

### Admin Endpoints (Auth + Admin Role Required)
```
# Universities
GET    /api/admin/universities           # List all
POST   /api/admin/universities           # Create
GET    /api/admin/universities/{id}      # Get details
PUT    /api/admin/universities/{id}      # Update
DELETE /api/admin/universities/{id}      # Delete
POST   /api/admin/universities/{id}/restore         # Restore
POST   /api/admin/universities/{id}/interests       # Manage programs

# Interests
GET    /api/admin/interests              # List all
POST   /api/admin/interests              # Create
PUT    /api/admin/interests/{id}         # Update
DELETE /api/admin/interests/{id}         # Delete

# Cities
GET    /api/admin/cities                 # List all
POST   /api/admin/cities                 # Create
PUT    /api/admin/cities/{id}            # Update
DELETE /api/admin/cities/{id}            # Delete
```

---

## 💡 Usage Examples

### Example 1: Student Registration Flow

```bash
# 1. Student registers/logs in (existing auth)
POST /api/login
{
    "email": "student@example.com",
    "password": "password"
}

# 2. Add education history
POST /api/education
Authorization: Bearer {token}
{
    "education_level": "intermediate",
    "institution_name": "Government College",
    "board_university": "BISE Lahore",
    "field_of_study": "Pre-Engineering",
    "start_year": 2019,
    "end_year": 2021,
    "percentage_cgpa": 85.5,
    "grade_type": "percentage"
}

# 3. Select interests
POST /api/interests
Authorization: Bearer {token}
{
    "interest_ids": [1, 2]  # Software Engineering, Computer Science
}

# 4. Select preferred cities
POST /api/preferred-cities
Authorization: Bearer {token}
{
    "city_ids": [2, 3]  # Lahore, Islamabad
}

# 5. Get recommended universities
GET /api/universities/recommended
Authorization: Bearer {token}

# 6. Add to favorites
POST /api/favorites
Authorization: Bearer {token}
{
    "university_id": 1
}
```

### Example 2: Admin Creating University

```bash
# 1. Admin login
POST /api/login
{
    "email": "admin@example.com",
    "password": "password"
}

# 2. Create university
POST /api/admin/universities
Authorization: Bearer {admin_token}
Content-Type: multipart/form-data
{
    "name": "FAST NUCES",
    "description": "Leading technology university",
    "city_id": 2,
    "type": "private",
    "established_year": 2000,
    "ranking": 4.5,
    "tuition_fee_range_min": 200000,
    "tuition_fee_range_max": 500000,
    "hostel_available": true,
    "scholarship_available": true,
    "interest_ids": [1, 2, 3]
}

# 3. Add program details
POST /api/admin/universities/1/interests
Authorization: Bearer {admin_token}
{
    "interests": [
        {
            "interest_id": 1,
            "fee": 450000,
            "duration_years": 4,
            "program_details": "4-year BS Software Engineering"
        },
        {
            "interest_id": 2,
            "fee": 400000,
            "duration_years": 4,
            "program_details": "4-year BS Computer Science"
        }
    ]
}
```

---

## 🗂️ Database Schema

### user_education
- Student's education history (matric, intermediate, graduation, etc.)
- Supports document uploads
- Tracks current/ongoing education

### interests
- Master table for academic fields/departments
- Slug for URL-friendly names

### user_interests
- Pivot table linking users to their interests
- Supports multiple interests per user

### cities
- Master table for available cities
- Includes state/province and country

### user_cities
- Pivot table for user's preferred study locations
- Supports multiple cities per user

### universities
- Complete university information
- Includes fees, facilities, rankings, etc.
- Supports logo and cover images

### university_interests
- Links universities to programs they offer
- Includes program-specific fees and duration
- Program details field for additional info

### user_favorites
- Stores user's favorite universities
- Unique constraint prevents duplicates

---

## 🎯 Key Features

### Smart Recommendations
The system recommends universities based on:
- User's selected interests (matching programs)
- User's preferred cities
- Additional filters (type, hostel, scholarships)

### Flexible Education Levels
- Matric
- Intermediate
- Graduation
- Post Graduation
- Diploma
- Other

### University Types
- Public
- Private
- Semi-Government

### File Uploads
- Education certificates (PDF, JPG, PNG)
- University logos
- University cover images

---

## 🔐 Security Features

- All user-specific operations require authentication
- Users can only modify their own data
- Admin routes protected (add role middleware as needed)
- Soft deletes for universities
- File validation and size limits

---

## 📝 Response Format

All API responses follow this consistent format:

**Success Response:**
```json
{
    "error": false,
    "message": "Operation successful",
    "data": { ... }
}
```

**Error Response:**
```json
{
    "error": true,
    "message": "Error description",
    "data": { ... }
}
```

---

## 🔄 Next Steps

1. ✅ Run migrations: `php artisan migrate`
2. ✅ Seed interests and cities: `php artisan db:seed --class=InterestsAndCitiesSeeder`
3. ✅ Seed universities: `php artisan db:seed --class=UniversitySeeder`
4. 📝 Test endpoints using the API documentation
5. 🎨 Add role-based access control to admin routes if needed
6. 📱 Integrate with your frontend/mobile app

---

## 📚 Additional Documentation

See `API_DOCUMENTATION.md` for complete API reference with:
- Detailed endpoint descriptions
- Request/response examples
- Validation rules
- Complete workflow examples

---

## 🆘 Troubleshooting

### Common Issues

**Issue: Education certificate upload fails**
- Check file size (max 2MB)
- Ensure file type is PDF, JPG, or PNG
- Verify `storage/app/public` is linked: `php artisan storage:link`

**Issue: Recommended universities returns empty**
- Ensure user has selected interests and cities
- Verify universities exist with matching criteria
- Check university status is active (status = 1)

**Issue: Duplicate favorite error**
- University is already in favorites
- Use toggle endpoint instead

---

## 📞 Support

For issues or questions, refer to:
1. `API_DOCUMENTATION.md` - Complete API reference
2. Model relationships in `app/Models/`
3. Controller logic in `app/Http/Controllers/Api/`

---

**Built with Laravel & Sanctum Authentication** 🚀

