# FAQ Management API Documentation

## Overview
This document describes the FAQ (Frequently Asked Questions) management system for the university admission application. The system provides:
- **Public API**: For app users to view active FAQs
- **Admin API**: For administrators to manage FAQs (create, update, delete, reorder)

---

## Table of Contents
1. [Public FAQ APIs](#public-faq-apis)
2. [Admin FAQ Management APIs](#admin-faq-management-apis)
3. [Data Models](#data-models)
4. [Examples](#examples)

---

## Public FAQ APIs

### 1. Get All Active FAQs
Retrieve all active FAQs for display in the mobile app.

**Endpoint:** `GET /api/faqs`

**Authentication:** Not required

**Response:**
```json
{
  "error": false,
  "message": "FAQs retrieved successfully",
  "data": [
    {
      "id": 1,
      "question": "How do I apply for university admission?",
      "answer": "To apply for university admission, first register an account...",
      "order": 1,
      "status": 1,
      "created_at": "2025-11-05T10:30:00.000000Z",
      "updated_at": "2025-11-05T10:30:00.000000Z"
    },
    {
      "id": 2,
      "question": "What documents do I need to upload?",
      "answer": "You will typically need to upload your academic transcripts...",
      "order": 2,
      "status": 1,
      "created_at": "2025-11-05T10:30:00.000000Z",
      "updated_at": "2025-11-05T10:30:00.000000Z"
    }
  ]
}
```

---

### 2. Get Single FAQ
Retrieve details of a specific FAQ.

**Endpoint:** `GET /api/faqs/{id}`

**Authentication:** Not required

**Parameters:**
- `id` (path parameter): FAQ ID

**Response:**
```json
{
  "error": false,
  "message": "FAQ retrieved successfully",
  "data": {
    "id": 1,
    "question": "How do I apply for university admission?",
    "answer": "To apply for university admission, first register an account...",
    "order": 1,
    "status": 1,
    "created_at": "2025-11-05T10:30:00.000000Z",
    "updated_at": "2025-11-05T10:30:00.000000Z"
  }
}
```

---

## Admin FAQ Management APIs

All admin APIs require authentication via Sanctum token.

### 1. Get All FAQs (Admin)
Retrieve all FAQs with pagination, search, and filtering.

**Endpoint:** `GET /api/admin/faqs`

**Authentication:** Required (Bearer Token)

**Query Parameters:**
- `per_page` (optional): Number of items per page (default: 15)
- `search` (optional): Search in question and answer
- `status` (optional): Filter by status (1 = Active, 0 = Inactive)

**Example Request:**
```
GET /api/admin/faqs?per_page=10&search=admission&status=1
Authorization: Bearer {token}
```

**Response:**
```json
{
  "error": false,
  "message": "FAQs retrieved successfully",
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "question": "How do I apply for university admission?",
        "answer": "To apply for university admission...",
        "order": 1,
        "status": 1,
        "created_at": "2025-11-05T10:30:00.000000Z",
        "updated_at": "2025-11-05T10:30:00.000000Z"
      }
    ],
    "first_page_url": "http://example.com/api/admin/faqs?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http://example.com/api/admin/faqs?page=1",
    "links": [...],
    "next_page_url": null,
    "path": "http://example.com/api/admin/faqs",
    "per_page": 10,
    "prev_page_url": null,
    "to": 10,
    "total": 10
  }
}
```

---

### 2. Create FAQ
Create a new FAQ entry.

**Endpoint:** `POST /api/admin/faqs`

**Authentication:** Required (Bearer Token)

**Request Body:**
```json
{
  "question": "What are the admission requirements?",
  "answer": "The admission requirements vary by program but generally include...",
  "order": 11,
  "status": 1
}
```

**Validation Rules:**
- `question`: required, string, max 255 characters
- `answer`: required, string
- `order`: optional, integer, min 0
- `status`: optional, boolean (default: 1)

**Response:**
```json
{
  "error": false,
  "message": "FAQ created successfully",
  "data": {
    "id": 11,
    "question": "What are the admission requirements?",
    "answer": "The admission requirements vary by program...",
    "order": 11,
    "status": 1,
    "created_at": "2025-11-05T11:00:00.000000Z",
    "updated_at": "2025-11-05T11:00:00.000000Z"
  }
}
```

---

### 3. Get Single FAQ (Admin)
Retrieve details of a specific FAQ.

**Endpoint:** `GET /api/admin/faqs/{id}`

**Authentication:** Required (Bearer Token)

**Parameters:**
- `id` (path parameter): FAQ ID

**Response:**
```json
{
  "error": false,
  "message": "FAQ retrieved successfully",
  "data": {
    "id": 1,
    "question": "How do I apply for university admission?",
    "answer": "To apply for university admission...",
    "order": 1,
    "status": 1,
    "created_at": "2025-11-05T10:30:00.000000Z",
    "updated_at": "2025-11-05T10:30:00.000000Z"
  }
}
```

---

### 4. Update FAQ
Update an existing FAQ.

**Endpoint:** `PUT /api/admin/faqs/{id}`

**Authentication:** Required (Bearer Token)

**Parameters:**
- `id` (path parameter): FAQ ID

**Request Body:**
```json
{
  "question": "How do I apply for university admission? (Updated)",
  "answer": "Updated answer with more detailed information...",
  "order": 1,
  "status": 1
}
```

**Validation Rules:**
- `question`: sometimes required, string, max 255 characters
- `answer`: sometimes required, string
- `order`: optional, integer, min 0
- `status`: optional, boolean

**Response:**
```json
{
  "error": false,
  "message": "FAQ updated successfully",
  "data": {
    "id": 1,
    "question": "How do I apply for university admission? (Updated)",
    "answer": "Updated answer with more detailed information...",
    "order": 1,
    "status": 1,
    "created_at": "2025-11-05T10:30:00.000000Z",
    "updated_at": "2025-11-05T11:15:00.000000Z"
  }
}
```

---

### 5. Delete FAQ
Delete an FAQ entry.

**Endpoint:** `DELETE /api/admin/faqs/{id}`

**Authentication:** Required (Bearer Token)

**Parameters:**
- `id` (path parameter): FAQ ID

**Response:**
```json
{
  "error": false,
  "message": "FAQ deleted successfully",
  "data": null
}
```

---

### 6. Toggle FAQ Status
Toggle the status of an FAQ (active/inactive).

**Endpoint:** `POST /api/admin/faqs/{id}/toggle-status`

**Authentication:** Required (Bearer Token)

**Parameters:**
- `id` (path parameter): FAQ ID

**Response:**
```json
{
  "error": false,
  "message": "FAQ status updated successfully",
  "data": {
    "id": 1,
    "question": "How do I apply for university admission?",
    "answer": "To apply for university admission...",
    "order": 1,
    "status": 0,
    "created_at": "2025-11-05T10:30:00.000000Z",
    "updated_at": "2025-11-05T11:20:00.000000Z"
  }
}
```

---

### 7. Reorder FAQs
Update the display order of multiple FAQs.

**Endpoint:** `POST /api/admin/faqs/reorder`

**Authentication:** Required (Bearer Token)

**Request Body:**
```json
{
  "faqs": [
    {
      "id": 1,
      "order": 5
    },
    {
      "id": 2,
      "order": 1
    },
    {
      "id": 3,
      "order": 2
    }
  ]
}
```

**Validation Rules:**
- `faqs`: required, array
- `faqs.*.id`: required, integer, must exist in app_faqs table
- `faqs.*.order`: required, integer, min 0

**Response:**
```json
{
  "error": false,
  "message": "FAQs reordered successfully",
  "data": null
}
```

---

### 8. Get FAQ Statistics
Get statistics about FAQs.

**Endpoint:** `GET /api/admin/faqs/statistics`

**Authentication:** Required (Bearer Token)

**Response:**
```json
{
  "error": false,
  "message": "Statistics retrieved successfully",
  "data": {
    "total_faqs": 10,
    "active_faqs": 9,
    "inactive_faqs": 1
  }
}
```

---

## Data Models

### AppFaq Model

**Table:** `app_faqs`

**Columns:**
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| question | string(255) | FAQ question |
| answer | text | FAQ answer |
| order | integer | Display order (default: 0) |
| status | boolean | Active status (1=Active, 0=Inactive) |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

**Scopes:**
- `active()`: Filter only active FAQs
- `ordered()`: Order by order field, then by id

---

## Examples

### Mobile App Integration

#### Display FAQs in App
```javascript
// Flutter/React Native Example
async function fetchFAQs() {
  try {
    const response = await fetch('https://your-domain.com/api/faqs');
    const data = await response.json();
    
    if (!data.error) {
      // Display FAQs in your app
      return data.data;
    }
  } catch (error) {
    console.error('Error fetching FAQs:', error);
  }
}
```

### Admin Panel Integration

#### Create a New FAQ
```javascript
async function createFAQ(faqData, token) {
  try {
    const response = await fetch('https://your-domain.com/api/admin/faqs', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
      },
      body: JSON.stringify(faqData)
    });
    
    const data = await response.json();
    return data;
  } catch (error) {
    console.error('Error creating FAQ:', error);
  }
}

// Usage
const newFAQ = {
  question: "How long is the application process?",
  answer: "The application process typically takes 2-4 weeks...",
  order: 12,
  status: 1
};

createFAQ(newFAQ, userToken);
```

#### Update FAQ Order
```javascript
async function reorderFAQs(faqOrder, token) {
  try {
    const response = await fetch('https://your-domain.com/api/admin/faqs/reorder', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
      },
      body: JSON.stringify({ faqs: faqOrder })
    });
    
    const data = await response.json();
    return data;
  } catch (error) {
    console.error('Error reordering FAQs:', error);
  }
}

// Usage - Drag and drop implementation
const newOrder = [
  { id: 2, order: 1 },
  { id: 1, order: 2 },
  { id: 3, order: 3 }
];

reorderFAQs(newOrder, userToken);
```

---

## Error Responses

### Validation Error (422)
```json
{
  "error": true,
  "message": "Validation failed",
  "data": {
    "question": ["The question field is required."],
    "answer": ["The answer field is required."]
  }
}
```

### Not Found (404)
```json
{
  "error": true,
  "message": "FAQ not found",
  "data": null
}
```

### Server Error (500)
```json
{
  "error": true,
  "message": "Failed to retrieve FAQs",
  "data": "Error message details..."
}
```

### Unauthorized (401)
```json
{
  "error": true,
  "message": "Unauthenticated"
}
```

---

## Testing with Postman

### Import Collection
You can test all endpoints using the provided Postman collection. Import the collection and set up the following variables:

**Environment Variables:**
- `base_url`: Your API base URL (e.g., `http://localhost:8000/api`)
- `admin_token`: Your admin authentication token

### Test Sequence
1. **Get All FAQs (Public)** - Test without authentication
2. **Login as Admin** - Get authentication token
3. **Create FAQ** - Add a new FAQ
4. **Get All FAQs (Admin)** - View all FAQs with filters
5. **Update FAQ** - Modify the FAQ
6. **Toggle Status** - Change active/inactive status
7. **Reorder FAQs** - Change display order
8. **Get Statistics** - View FAQ statistics
9. **Delete FAQ** - Remove the FAQ

---

## Database Seeding

To populate sample FAQ data:

```bash
php artisan db:seed --class=AppFaqSeeder
```

This will create 10 sample FAQs covering common questions about:
- University application process
- Required documents
- Multiple applications
- Application timeline
- Payment methods
- Application editing
- Status tracking
- Scholarships
- Password recovery
- Support contact

---

## Summary

This FAQ management system provides:

✅ **Public API** for mobile app users to view FAQs
✅ **Admin API** for complete CRUD operations
✅ **Search and Filter** capabilities
✅ **Custom Ordering** for display priority
✅ **Status Management** (active/inactive)
✅ **Statistics** for monitoring
✅ **Seeded Data** for testing

The system is ready to use and can be integrated into your mobile application and admin panel immediately.



