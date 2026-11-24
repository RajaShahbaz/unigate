# University Admission System API Documentation

## Overview
This API system provides comprehensive endpoints for managing student university admissions, including education history, interests, preferred cities, university browsing, and favorites management.

---

## Table of Contents
1. [Authentication](#authentication)
2. [User Education Management](#user-education-management)
3. [User Interests Management](#user-interests-management)
4. [Preferred Cities Management](#preferred-cities-management)
5. [Universities](#universities)
6. [Favorites Management](#favorites-management)
7. [Admin APIs](#admin-apis)

---

## Authentication

All authenticated endpoints require a Bearer token in the Authorization header:
```
Authorization: Bearer {your_token}
```

---

## User Education Management

### Get All User Education
**GET** `/api/education`

**Authentication:** Required

**Response:**
```json
{
    "error": false,
    "message": "Education records retrieved successfully",
    "data": [
        {
            "id": 1,
            "user_id": 1,
            "education_level": "intermediate",
            "institution_name": "Government College",
            "board_university": "BISE Lahore",
            "field_of_study": "Pre-Engineering",
            "start_year": 2019,
            "end_year": 2021,
            "percentage_cgpa": 85.5,
            "grade_type": "percentage",
            "certificate_document": "path/to/document.pdf",
            "additional_info": "Position holder",
            "is_current": false,
            "created_at": "2025-10-09T10:00:00",
            "updated_at": "2025-10-09T10:00:00"
        }
    ]
}
```

### Add Education Record
**POST** `/api/education`

**Authentication:** Required

**Request Body:**
```json
{
    "education_level": "graduation",
    "institution_name": "FAST NUCES",
    "board_university": "FAST University",
    "field_of_study": "Computer Science",
    "start_year": 2021,
    "end_year": null,
    "percentage_cgpa": 3.8,
    "grade_type": "cgpa",
    "certificate_document": "(file upload)",
    "additional_info": "Currently enrolled",
    "is_current": true
}
```

**Validation Rules:**
- `education_level`: required, one of: matric, intermediate, graduation, post_graduation, diploma, other
- `institution_name`: required, string, max 255
- `board_university`: nullable, string, max 255
- `field_of_study`: nullable, string, max 255
- `start_year`: required, 4 digits, 1900 to current_year+1
- `end_year`: nullable, 4 digits, 1900 to current_year+10
- `percentage_cgpa`: nullable, numeric, 0-100
- `grade_type`: nullable, one of: percentage, cgpa
- `certificate_document`: nullable, file, pdf/jpg/jpeg/png, max 2MB
- `additional_info`: nullable, string
- `is_current`: nullable, boolean

### Update Education Record
**PUT** `/api/education/{id}`

**Authentication:** Required

**Request Body:** Same as Add Education (all fields optional except those you want to update)

### Delete Education Record
**DELETE** `/api/education/{id}`

**Authentication:** Required

---

## User Interests Management

### Get All Available Interests
**GET** `/api/interests/all`

**Authentication:** Not Required

**Response:**
```json
{
    "error": false,
    "message": "Interests retrieved successfully",
    "data": [
        {
            "id": 1,
            "name": "Software Engineering",
            "slug": "software-engineering",
            "description": "Design, development and maintenance of software systems",
            "status": 1
        }
    ]
}
```

### Get User's Selected Interests
**GET** `/api/interests`

**Authentication:** Required

### Add Interests to User Profile
**POST** `/api/interests`

**Authentication:** Required

**Request Body:**
```json
{
    "interest_ids": [1, 2, 5]
}
```

### Replace All User Interests
**POST** `/api/interests/sync`

**Authentication:** Required

**Request Body:**
```json
{
    "interest_ids": [3, 4, 6]
}
```

### Remove Interest
**DELETE** `/api/interests/{interestId}`

**Authentication:** Required

---

## Preferred Cities Management

### Get All Available Cities
**GET** `/api/cities/all`

**Authentication:** Not Required

**Response:**
```json
{
    "error": false,
    "message": "Cities retrieved successfully",
    "data": [
        {
            "id": 1,
            "name": "Lahore",
            "state_province": "Punjab",
            "country": "Pakistan",
            "status": 1
        }
    ]
}
```

### Get User's Preferred Cities
**GET** `/api/preferred-cities`

**Authentication:** Required

### Add Preferred Cities
**POST** `/api/preferred-cities`

**Authentication:** Required

**Request Body:**
```json
{
    "city_ids": [1, 2, 3]
}
```

### Replace All Preferred Cities
**POST** `/api/preferred-cities/sync`

**Authentication:** Required

### Remove City
**DELETE** `/api/preferred-cities/{cityId}`

**Authentication:** Required

---

## Universities

### Get All Universities (Public)
**GET** `/api/universities`

**Authentication:** Not Required

**Query Parameters:**
- `search`: Search by name
- `city_id`: Filter by city
- `interest_id`: Filter by program/interest
- `type`: Filter by type (public, private, semi-government)
- `per_page`: Items per page (default: 10)

**Response:**
```json
{
    "error": false,
    "message": "Universities retrieved successfully",
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "name": "FAST NUCES",
                "slug": "fast-nuces",
                "description": "Leading technology university",
                "city_id": 2,
                "address": "Main Campus, Lahore",
                "phone": "+92-42-111-128-128",
                "email": "info@nu.edu.pk",
                "website": "https://www.nu.edu.pk",
                "logo": "path/to/logo.png",
                "cover_image": "path/to/cover.jpg",
                "type": "private",
                "established_year": 2000,
                "facilities": "Library, Labs, Hostel, Sports",
                "ranking": 4.5,
                "tuition_fee_range_min": 200000,
                "tuition_fee_range_max": 500000,
                "hostel_available": true,
                "scholarship_available": true,
                "admission_criteria": "Entry test and interview required",
                "accreditation": "HEC Recognized",
                "status": 1,
                "is_favorite": false,
                "city": {
                    "id": 2,
                    "name": "Lahore",
                    "state_province": "Punjab"
                },
                "interests": [
                    {
                        "id": 1,
                        "name": "Software Engineering",
                        "pivot": {
                            "fee": 450000,
                            "duration_years": 4,
                            "program_details": "4-year BS program"
                        }
                    }
                ]
            }
        ],
        "per_page": 10,
        "total": 50
    }
}
```

### Get Single University
**GET** `/api/universities/{id}`

**Authentication:** Not Required

### Get Recommended Universities
**GET** `/api/universities/recommended`

**Authentication:** Required

**Description:** Returns universities based on user's interests and preferred cities

**Query Parameters:**
- `city_id`: Additional city filter
- `type`: Filter by type
- `hostel_available`: Filter by hostel
- `scholarship_available`: Filter by scholarship
- `per_page`: Items per page

---

## Favorites Management

### Get User's Favorite Universities
**GET** `/api/favorites`

**Authentication:** Required

**Response:**
```json
{
    "error": false,
    "message": "Favorite universities retrieved successfully",
    "data": [
        {
            "id": 1,
            "name": "FAST NUCES",
            "city": {...},
            "interests": [...]
        }
    ]
}
```

### Add University to Favorites
**POST** `/api/favorites`

**Authentication:** Required

**Request Body:**
```json
{
    "university_id": 1
}
```

### Toggle Favorite Status
**POST** `/api/favorites/toggle`

**Authentication:** Required

**Request Body:**
```json
{
    "university_id": 1
}
```

**Response:**
```json
{
    "error": false,
    "message": "University added to favorites",
    "data": {
        "is_favorite": true,
        "university_id": 1
    }
}
```

### Remove from Favorites
**DELETE** `/api/favorites/{universityId}`

**Authentication:** Required

---

## Admin APIs

### University Management

#### Get All Universities (Admin)
**GET** `/api/admin/universities`

**Authentication:** Required (Admin)

**Query Parameters:**
- `search`: Search by name
- `status`: Filter by status (0 or 1)
- `per_page`: Items per page (default: 15)

#### Create University
**POST** `/api/admin/universities`

**Authentication:** Required (Admin)

**Request Body:**
```json
{
    "name": "FAST NUCES",
    "description": "Leading technology university",
    "city_id": 2,
    "address": "Main Campus, Lahore",
    "phone": "+92-42-111-128-128",
    "email": "info@nu.edu.pk",
    "website": "https://www.nu.edu.pk",
    "logo": "(file upload)",
    "cover_image": "(file upload)",
    "type": "private",
    "established_year": 2000,
    "facilities": "Library, Labs, Hostel",
    "ranking": 4.5,
    "tuition_fee_range_min": 200000,
    "tuition_fee_range_max": 500000,
    "hostel_available": true,
    "scholarship_available": true,
    "admission_criteria": "Entry test required",
    "accreditation": "HEC Recognized",
    "status": 1,
    "interest_ids": [1, 2, 3]
}
```

#### Update University
**PUT** `/api/admin/universities/{id}`

**Authentication:** Required (Admin)

#### Delete University
**DELETE** `/api/admin/universities/{id}`

**Authentication:** Required (Admin)

#### Restore University
**POST** `/api/admin/universities/{id}/restore`

**Authentication:** Required (Admin)

#### Update University Programs/Interests
**POST** `/api/admin/universities/{id}/interests`

**Authentication:** Required (Admin)

**Request Body:**
```json
{
    "interests": [
        {
            "interest_id": 1,
            "fee": 450000,
            "duration_years": 4,
            "program_details": "4-year BS program in Software Engineering"
        },
        {
            "interest_id": 2,
            "fee": 400000,
            "duration_years": 4,
            "program_details": "4-year BS program in Computer Science"
        }
    ]
}
```

### Interest Management

#### Get All Interests (Admin)
**GET** `/api/admin/interests`

#### Create Interest
**POST** `/api/admin/interests`

**Request Body:**
```json
{
    "name": "Cyber Security",
    "description": "Study of computer security and information protection",
    "status": 1
}
```

#### Update Interest
**PUT** `/api/admin/interests/{id}`

#### Delete Interest
**DELETE** `/api/admin/interests/{id}`

### City Management

#### Get All Cities (Admin)
**GET** `/api/admin/cities`

#### Create City
**POST** `/api/admin/cities`

**Request Body:**
```json
{
    "name": "Karachi",
    "state_province": "Sindh",
    "country": "Pakistan",
    "status": 1
}
```

#### Update City
**PUT** `/api/admin/cities/{id}`

#### Delete City
**DELETE** `/api/admin/cities/{id}`

---

## Database Migrations

To set up the database, run:
```bash
php artisan migrate
```

To seed sample data:
```bash
php artisan db:seed --class=InterestsAndCitiesSeeder
```

---

## Error Responses

All endpoints return errors in the following format:

```json
{
    "error": true,
    "message": "Error description",
    "data": {} // Additional error details
}
```

**HTTP Status Codes:**
- 200: Success
- 201: Created
- 400: Bad Request
- 401: Unauthorized
- 422: Validation Error
- 500: Server Error

---

## Complete API Workflow

### Student Registration & Setup
1. Register/Login to get authentication token
2. Add education history: `POST /api/education`
3. Select interests: `POST /api/interests`
4. Select preferred cities: `POST /api/preferred-cities`

### Browse Universities
1. Get recommended universities: `GET /api/universities/recommended`
2. Or browse all: `GET /api/universities`
3. View details: `GET /api/universities/{id}`

### Manage Favorites
1. Add to favorites: `POST /api/favorites`
2. View favorites: `GET /api/favorites`
3. Remove: `DELETE /api/favorites/{id}`

### Admin Tasks
1. Create interests: `POST /api/admin/interests`
2. Create cities: `POST /api/admin/cities`
3. Create universities: `POST /api/admin/universities`
4. Manage programs: `POST /api/admin/universities/{id}/interests`

