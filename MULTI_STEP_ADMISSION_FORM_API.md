# Multi-Step Admission Form API Documentation
## Mobile App Optimized

---

## 📱 Overview

This is a **7-step admission form system** designed specifically for mobile applications. The form is broken down into logical steps for better user experience and allows users to save progress at each step.

### ✨ Key Features

- ✅ **7 well-organized steps** (Personal Info → Contact → Academic → Program → Documents → Additional Info → Review)
- ✅ **Save progress at each step** - Users don't lose data
- ✅ **Resume from where they left off** - Continue anytime
- ✅ **Progress tracking** - Visual feedback on completion
- ✅ **Step-by-step validation** - Validate one step at a time
- ✅ **File upload support** - Photos, documents, transcripts
- ✅ **Draft management** - Save as draft before submitting
- ✅ **Mobile-friendly** - Optimized for mobile screens

---

## 🎯 Form Steps

| Step | Title | Description | Icon |
|------|-------|-------------|------|
| 1 | Personal Information | Basic details (name, DOB, gender, nationality) | user |
| 2 | Contact Details | Email, phone, addresses | phone |
| 3 | Academic Background | Previous school, degree, GPA | graduation-cap |
| 4 | Program Selection | Choose desired program | book |
| 5 | Document Upload | Upload photos and documents | file-upload |
| 6 | Additional Information | Personal statement, emergency contact | info-circle |
| 7 | Review & Submit | Review all information | check-circle |

---

## 🔌 API Endpoints

### Base URL
```
https://your-domain.com/api/multi-step-application
```

### Authentication
All endpoints require authentication via Sanctum Bearer token:
```
Authorization: Bearer {token}
```

---

## 📋 API Reference

### 1. Get All Form Steps
Get the complete form configuration with all steps.

```http
GET /multi-step-application/steps
```

**Response:**
```json
{
  "error": false,
  "message": "Form steps retrieved successfully",
  "data": {
    "steps": [
      {
        "step": 1,
        "title": "Personal Information",
        "description": "Tell us about yourself",
        "icon": "user",
        "fields": [
          {
            "name": "first_name",
            "type": "text",
            "label": "First Name",
            "placeholder": "Enter your first name",
            "required": true,
            "validation": "required|string|max:255",
            "icon": "user"
          }
          // ... more fields
        ]
      }
      // ... more steps
    ],
    "total_steps": 7
  }
}
```

---

### 2. Get Specific Step
Get configuration for a specific step.

```http
GET /multi-step-application/steps/{stepNumber}
```

**Parameters:**
- `stepNumber` (integer): Step number (1-7)

**Example:**
```http
GET /multi-step-application/steps/1
```

**Response:**
```json
{
  "error": false,
  "message": "Step retrieved successfully",
  "data": {
    "step": 1,
    "title": "Personal Information",
    "description": "Tell us about yourself",
    "icon": "user",
    "fields": [
      {
        "name": "first_name",
        "type": "text",
        "label": "First Name",
        "placeholder": "Enter your first name",
        "required": true,
        "validation": "required|string|max:255",
        "icon": "user"
      }
      // ... more fields
    ]
  }
}
```

---

### 3. Start New Application
Start a new admission application (creates a draft).

```http
POST /multi-step-application/start
```

**Request Body:**
```json
{
  "university_id": 1
}
```

**Response (New Application):**
```json
{
  "error": false,
  "message": "Application started successfully",
  "data": {
    "application": {
      "id": 123,
      "user_id": 456,
      "university_id": 1,
      "status": "draft",
      "application_number": "APP2025000123",
      "created_at": "2025-10-27T10:00:00.000000Z"
    },
    "next_step": 1
  }
}
```

**Response (Existing Draft Found):**
```json
{
  "error": false,
  "message": "Existing draft application found",
  "data": {
    "application": {
      "id": 123,
      // ... application data
    },
    "can_continue": true
  }
}
```

---

### 4. Save Step Data
Save data for a specific step.

```http
POST /multi-step-application/{applicationId}/step/{stepNumber}
```

**Parameters:**
- `applicationId` (integer): Application ID
- `stepNumber` (integer): Step number (1-7)

**Request Body (Step 1 Example):**
```json
{
  "first_name": "John",
  "last_name": "Doe",
  "date_of_birth": "2000-01-15",
  "gender": "male",
  "nationality": "Pakistani"
}
```

**Request Body (Step 5 - File Upload Example):**
```http
Content-Type: multipart/form-data

photo_file: [file]
id_document_file: [file]
transcript_file: [file]
recommendation_letter_file: [file] (optional)
```

**Response:**
```json
{
  "error": false,
  "message": "Step saved successfully",
  "data": {
    "application": {
      "id": 123,
      "first_name": "John",
      "last_name": "Doe",
      // ... all application data
    },
    "current_step": 1,
    "next_step": 2,
    "progress": 14.29,
    "completed_steps": [1],
    "can_proceed": true,
    "can_submit": false
  }
}
```

---

### 5. Get Application Progress
Check current progress and which step to show.

```http
GET /multi-step-application/{applicationId}/progress
```

**Response:**
```json
{
  "error": false,
  "message": "Progress retrieved successfully",
  "data": {
    "application": {
      // ... application data
    },
    "current_step": 3,
    "completed_steps": [1, 2],
    "total_steps": 7,
    "progress_percentage": 28.57,
    "can_submit": false
  }
}
```

---

### 6. Get Application Summary
Get complete summary for review (Step 7).

```http
GET /multi-step-application/{applicationId}/summary
```

**Response:**
```json
{
  "error": false,
  "message": "Application summary retrieved successfully",
  "data": {
    "application": {
      // ... complete application data
    },
    "summary": {
      "personal_info": {
        "First Name": "John",
        "Last Name": "Doe",
        "Date of Birth": "2000-01-15",
        "Gender": "Male",
        "Nationality": "Pakistani"
      },
      "contact_info": {
        "Email": "john@example.com",
        "Phone": "+92300123456",
        "Current Address": "123 Street, City",
        "Permanent Address": "Same as current"
      },
      "academic_info": {
        "Previous School": "ABC School",
        "Previous Degree": "Intermediate",
        "Graduation Year": 2023,
        "GPA/Percentage": 85.5
      },
      "program_info": {
        "University": "XYZ University",
        "Program": "Computer Science"
      },
      "documents": {
        "Photo": true,
        "ID Document": true,
        "Transcript": true,
        "Recommendation Letter": false
      }
    }
  }
}
```

---

### 7. Submit Application
Submit the completed application.

```http
POST /multi-step-application/{applicationId}/submit
```

**Response (Success):**
```json
{
  "error": false,
  "message": "Application submitted successfully",
  "data": {
    "application": {
      "id": 123,
      "status": "submitted",
      "submitted_at": "2025-10-27T12:00:00.000000Z"
    },
    "application_number": "APP2025000123"
  }
}
```

**Response (Incomplete Steps):**
```json
{
  "error": true,
  "message": "Please complete all required steps before submitting",
  "data": {
    "completed_steps": [1, 2, 3],
    "required_steps": 6
  }
}
```

---

### 8. Delete Draft Application
Delete a draft application.

```http
DELETE /multi-step-application/{applicationId}/draft
```

**Response:**
```json
{
  "error": false,
  "message": "Draft application deleted successfully"
}
```

---

## 📱 Mobile App Implementation Guide

### Flow Diagram

```
User Opens App
     ↓
Login/Register
     ↓
Select University
     ↓
Start Application (POST /start)
     ↓
Get Step 1 Config (GET /steps/1)
     ↓
User Fills Step 1
     ↓
Save Step 1 (POST /{id}/step/1)
     ↓
Get Progress (GET /{id}/progress)
     ↓
Get Step 2 Config (GET /steps/2)
     ↓
User Fills Step 2
     ↓
... (repeat for all steps)
     ↓
Get Summary (GET /{id}/summary)
     ↓
User Reviews
     ↓
Submit Application (POST /{id}/submit)
     ↓
Show Success Message
```

---

### Sample Code (Flutter/Dart)

```dart
// 1. Get all steps configuration
Future<void> getFormSteps() async {
  final response = await http.get(
    Uri.parse('$baseUrl/multi-step-application/steps'),
    headers: {'Authorization': 'Bearer $token'},
  );
  
  if (response.statusCode == 200) {
    final data = json.decode(response.body);
    setState(() {
      formSteps = data['data']['steps'];
    });
  }
}

// 2. Start new application
Future<int?> startApplication(int universityId) async {
  final response = await http.post(
    Uri.parse('$baseUrl/multi-step-application/start'),
    headers: {
      'Authorization': 'Bearer $token',
      'Content-Type': 'application/json',
    },
    body: json.encode({'university_id': universityId}),
  );
  
  if (response.statusCode == 200 || response.statusCode == 201) {
    final data = json.decode(response.body);
    return data['data']['application']['id'];
  }
  return null;
}

// 3. Save step data
Future<bool> saveStep(int applicationId, int stepNumber, Map<String, dynamic> data) async {
  final response = await http.post(
    Uri.parse('$baseUrl/multi-step-application/$applicationId/step/$stepNumber'),
    headers: {
      'Authorization': 'Bearer $token',
      'Content-Type': 'application/json',
    },
    body: json.encode(data),
  );
  
  return response.statusCode == 200;
}

// 4. Upload files (Step 5)
Future<bool> uploadDocuments(int applicationId, {
  required File photoFile,
  required File idDocument,
  required File transcript,
  File? recommendationLetter,
}) async {
  var request = http.MultipartRequest(
    'POST',
    Uri.parse('$baseUrl/multi-step-application/$applicationId/step/5'),
  );
  
  request.headers['Authorization'] = 'Bearer $token';
  
  request.files.add(await http.MultipartFile.fromPath('photo_file', photoFile.path));
  request.files.add(await http.MultipartFile.fromPath('id_document_file', idDocument.path));
  request.files.add(await http.MultipartFile.fromPath('transcript_file', transcript.path));
  
  if (recommendationLetter != null) {
    request.files.add(await http.MultipartFile.fromPath('recommendation_letter_file', recommendationLetter.path));
  }
  
  final response = await request.send();
  return response.statusCode == 200;
}

// 5. Get progress
Future<Map<String, dynamic>?> getProgress(int applicationId) async {
  final response = await http.get(
    Uri.parse('$baseUrl/multi-step-application/$applicationId/progress'),
    headers: {'Authorization': 'Bearer $token'},
  );
  
  if (response.statusCode == 200) {
    return json.decode(response.body)['data'];
  }
  return null;
}

// 6. Submit application
Future<bool> submitApplication(int applicationId) async {
  final response = await http.post(
    Uri.parse('$baseUrl/multi-step-application/$applicationId/submit'),
    headers: {'Authorization': 'Bearer $token'},
  );
  
  return response.statusCode == 200;
}
```

---

## 🎨 UI/UX Recommendations

### Step Indicator
```
○ ─── ○ ─── ○ ─── ○ ─── ○ ─── ○ ─── ○
1     2     3     4     5     6     7

◉ ─── ◉ ─── ○ ─── ○ ─── ○ ─── ○ ─── ○
```

### Progress Bar
```
████████░░░░░░░░░░░░░░░░░░░░ 28.57%
```

### Save & Continue Button
```
[ Save & Continue to Step 2 → ]
```

### Navigation
```
[ ← Back ]  [ Save Draft ]  [ Next → ]
```

---

## 📊 Field Types Reference

| Type | Description | Example |
|------|-------------|---------|
| `text` | Single line text input | First Name, Last Name |
| `email` | Email input with validation | Email Address |
| `tel` | Phone number input | Phone Number |
| `date` | Date picker | Date of Birth |
| `number` | Numeric input | GPA, Year |
| `select` | Dropdown/Picker | Gender, Degree |
| `textarea` | Multi-line text | Address, Statement |
| `file` | File upload | Photo, Documents |

---

## ⚠️ Error Handling

### Common Error Responses

**401 Unauthorized:**
```json
{
  "error": true,
  "message": "Unauthenticated"
}
```

**422 Validation Error:**
```json
{
  "error": true,
  "message": "Validation failed",
  "data": {
    "first_name": ["The first name field is required."],
    "email": ["The email must be a valid email address."]
  }
}
```

**404 Not Found:**
```json
{
  "error": true,
  "message": "Application not found"
}
```

**500 Server Error:**
```json
{
  "error": true,
  "message": "Failed to save step",
  "data": "Error details"
}
```

---

## ✅ Validation Rules

### Step 1 - Personal Information
- `first_name`: required, string, max 255
- `last_name`: required, string, max 255
- `date_of_birth`: required, date, before today
- `gender`: required, in:male,female,other
- `nationality`: required, string, max 100

### Step 2 - Contact Details
- `email`: required, email, max 255
- `phone`: required, string, max 20
- `current_address`: required, string, max 500
- `permanent_address`: nullable, string, max 500

### Step 3 - Academic Background
- `previous_school`: required, string, max 255
- `previous_degree`: required, string, max 100
- `graduation_year`: required, integer, min:1950, max:(current year + 5)
- `previous_gpa`: required, numeric, min:0, max:100

### Step 4 - Program Selection
- `interest_id`: required, exists in interests table

### Step 5 - Document Upload
- `photo_file`: required, image, max 2MB
- `id_document_file`: required, file (pdf/image), max 5MB
- `transcript_file`: required, pdf, max 10MB
- `recommendation_letter_file`: optional, pdf, max 5MB

---

## 💡 Best Practices

1. **Cache Form Steps** - Download once, cache locally
2. **Save on Each Step** - Don't lose user data
3. **Show Progress** - Visual feedback is important
4. **Validate Early** - Validate before moving to next step
5. **Handle Offline** - Allow offline form filling, sync later
6. **Image Compression** - Compress before upload
7. **Resume Capability** - Always fetch progress on app start
8. **Clear Instructions** - Use helper_text from field config
9. **Error Messages** - Show validation errors clearly
10. **Success Feedback** - Celebrate submission!

---

## 🔐 Security Notes

- All endpoints require authentication
- File uploads are validated for type and size
- User can only access their own applications
- Draft applications can only be modified, not submitted applications
- All data is validated server-side

---

## 🆘 Support

For questions or issues with the API:
- Check API response error messages
- Validate request data format
- Ensure proper authentication headers
- Check file upload sizes and formats

---

**API Version:** 1.0.0  
**Last Updated:** October 27, 2025  
**Status:** Production Ready ✅

