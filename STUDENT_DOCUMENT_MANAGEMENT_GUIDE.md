# Student Document Management System - Complete Guide

## Overview
The Student Document Management System allows students to upload, organize, and manage their personal documents. Students can add custom names, notes, and easily access their files whenever needed.

## Key Features

✅ **Upload Documents** - Upload any type of file (PDFs, images, Word docs, etc.)  
✅ **Custom Document Names** - Give meaningful names to your documents  
✅ **Add Notes** - Add descriptions or notes for each document  
✅ **View All Documents** - See all your documents in one place  
✅ **Search Documents** - Search by name, notes, or filename  
✅ **Update Details** - Edit document name and notes  
✅ **Replace Files** - Replace document file while keeping details  
✅ **Delete Documents** - Remove documents you no longer need  
✅ **Download Documents** - Download your files anytime  
✅ **Statistics** - View document counts and storage usage  
✅ **Secure Storage** - All files stored securely in public disk  

## Database Structure

### Table: `student_documents`

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint | Primary key |
| `user_id` | bigint | Foreign key to users table |
| `document_name` | string | User-defined name for the document |
| `notes` | text | User notes/description |
| `file_path` | string | Path to uploaded file |
| `original_filename` | string | Original filename when uploaded |
| `file_type` | string | MIME type (e.g., application/pdf) |
| `file_size` | integer | File size in bytes |
| `created_at` | timestamp | Upload date |
| `updated_at` | timestamp | Last modified date |
| `deleted_at` | timestamp | Soft delete timestamp |

## API Endpoints

All endpoints require authentication via Sanctum token.

### 1. Get All Documents

```
GET /api/documents
```

**Query Parameters:**
- `search` - Search by document name, notes, or filename
- `sort_by` - Sort field (document_name, created_at, updated_at, file_size)
- `sort_order` - Sort order (asc, desc)
- `per_page` - Results per page (default: 15)

**Example Request:**
```
GET /api/documents?search=passport&sort_by=created_at&sort_order=desc&per_page=20
```

**Response:**
```json
{
  "error": false,
  "message": "Documents retrieved successfully",
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "user_id": 5,
        "document_name": "My Passport",
        "notes": "Valid until 2030",
        "file_path": "student_documents/abc123.pdf",
        "original_filename": "passport_scan.pdf",
        "file_type": "application/pdf",
        "file_size": 1048576,
        "file_url": "http://domain.com/storage/student_documents/abc123.pdf",
        "file_size_formatted": "1.00 MB",
        "created_at": "2025-10-21T10:00:00.000000Z",
        "updated_at": "2025-10-21T10:00:00.000000Z"
      }
    ],
    "per_page": 15,
    "total": 1
  }
}
```

---

### 2. Get Single Document

```
GET /api/documents/{id}
```

**Response:**
```json
{
  "error": false,
  "message": "Document retrieved successfully",
  "data": {
    "id": 1,
    "document_name": "My Passport",
    "notes": "Valid until 2030",
    "file_url": "http://domain.com/storage/student_documents/abc123.pdf",
    "file_size_formatted": "1.00 MB",
    ...
  }
}
```

---

### 3. Upload New Document

```
POST /api/documents
Content-Type: multipart/form-data
```

**Request Body (Form Data):**
- `document_name` (required) - Name for the document
- `notes` (optional) - Description or notes
- `file` (required) - File to upload (max 10MB)

**Example:**
```javascript
const formData = new FormData();
formData.append('document_name', 'Birth Certificate');
formData.append('notes', 'Original copy from hospital');
formData.append('file', fileObject);

fetch('/api/documents', {
  method: 'POST',
  headers: {
    'Authorization': 'Bearer YOUR_TOKEN'
  },
  body: formData
});
```

**Response:**
```json
{
  "error": false,
  "message": "Document uploaded successfully",
  "data": {
    "id": 2,
    "document_name": "Birth Certificate",
    "notes": "Original copy from hospital",
    "file_path": "student_documents/xyz789.pdf",
    "original_filename": "birth_cert.pdf",
    "file_type": "application/pdf",
    "file_size": 524288,
    "file_url": "http://domain.com/storage/student_documents/xyz789.pdf",
    "file_size_formatted": "512.00 KB",
    ...
  }
}
```

---

### 4. Update Document Details

```
PUT /api/documents/{id}
Content-Type: application/json
```

**Purpose:** Update document name and/or notes (does not replace the file)

**Request Body:**
```json
{
  "document_name": "Updated Document Name",
  "notes": "Updated notes here"
}
```

**Response:**
```json
{
  "error": false,
  "message": "Document updated successfully",
  "data": {
    "id": 1,
    "document_name": "Updated Document Name",
    "notes": "Updated notes here",
    ...
  }
}
```

---

### 5. Replace Document File

```
POST /api/documents/{id}/replace-file
Content-Type: multipart/form-data
```

**Purpose:** Replace the actual file while keeping document name and notes

**Request Body (Form Data):**
- `file` (required) - New file to upload (max 10MB)

**Example:**
```javascript
const formData = new FormData();
formData.append('file', newFileObject);

fetch('/api/documents/1/replace-file', {
  method: 'POST',
  headers: {
    'Authorization': 'Bearer YOUR_TOKEN'
  },
  body: formData
});
```

**Response:**
```json
{
  "error": false,
  "message": "Document file replaced successfully",
  "data": {
    "id": 1,
    "document_name": "My Passport",
    "file_path": "student_documents/new_file_456.pdf",
    "original_filename": "new_passport.pdf",
    "file_type": "application/pdf",
    "file_size": 2097152,
    "file_size_formatted": "2.00 MB",
    ...
  }
}
```

---

### 6. Delete Document

```
DELETE /api/documents/{id}
```

**Purpose:** Permanently delete document and file

**Response:**
```json
{
  "error": false,
  "message": "Document deleted successfully"
}
```

**Note:** This will delete both the database record and the physical file from storage.

---

### 7. Download Document

```
GET /api/documents/{id}/download
```

**Purpose:** Download the document file

**Response:** File download with original filename

**Example:**
```html
<a href="/api/documents/1/download" download>Download Document</a>
```

---

### 8. Get Document Statistics

```
GET /api/documents/statistics
```

**Purpose:** Get overview of all documents

**Response:**
```json
{
  "error": false,
  "message": "Statistics retrieved successfully",
  "data": {
    "total_documents": 15,
    "total_size": 52428800,
    "total_size_formatted": "50.00 MB",
    "recent_uploads": 5,
    "by_type": [
      {
        "type_group": "PDF Documents",
        "count": 8
      },
      {
        "type_group": "Images",
        "count": 5
      },
      {
        "type_group": "Office Documents",
        "count": 2
      }
    ]
  }
}
```

---

## File Storage

### Storage Location
All files are stored in: `storage/app/public/student_documents/`

### Public Access
Files are accessible via: `http://your-domain.com/storage/student_documents/{filename}`

### Storage Disk Configuration
The system uses Laravel's `public` disk which is configured in `config/filesystems.php`:

```php
'public' => [
    'driver' => 'local',
    'root' => storage_path('app/public'),
    'url' => env('APP_URL').'/storage',
    'visibility' => 'public',
],
```

### File Size Limit
- Maximum upload size: **10MB** per file
- This can be adjusted in the controller validation rules

### Automatic File Cleanup
Files are automatically deleted when:
- Document is deleted from database
- Document file is replaced with a new one

---

## Use Cases & Examples

### Example 1: Upload Important Documents

**Scenario:** Student wants to upload their passport, birth certificate, and transcript.

```javascript
// Upload Passport
const passportForm = new FormData();
passportForm.append('document_name', 'Passport');
passportForm.append('notes', 'Valid until 2030. Renewal needed in 5 years.');
passportForm.append('file', passportFile);

await fetch('/api/documents', {
  method: 'POST',
  headers: { 'Authorization': 'Bearer TOKEN' },
  body: passportForm
});

// Upload Birth Certificate
const birthCertForm = new FormData();
birthCertForm.append('document_name', 'Birth Certificate');
birthCertForm.append('notes', 'Original document from hospital');
birthCertForm.append('file', birthCertFile);

await fetch('/api/documents', {
  method: 'POST',
  headers: { 'Authorization': 'Bearer TOKEN' },
  body: birthCertForm
});
```

---

### Example 2: Organize and Search Documents

**Scenario:** Student has many documents and wants to find specific ones.

```javascript
// Search for passport-related documents
const response = await fetch('/api/documents?search=passport', {
  headers: { 'Authorization': 'Bearer TOKEN' }
});

// Sort documents by upload date (newest first)
const response = await fetch('/api/documents?sort_by=created_at&sort_order=desc', {
  headers: { 'Authorization': 'Bearer TOKEN' }
});

// Get all large files
const response = await fetch('/api/documents?sort_by=file_size&sort_order=desc', {
  headers: { 'Authorization': 'Bearer TOKEN' }
});
```

---

### Example 3: Update Document Information

**Scenario:** Student uploaded a document but wants to update its name and notes.

```javascript
const response = await fetch('/api/documents/1', {
  method: 'PUT',
  headers: {
    'Authorization': 'Bearer TOKEN',
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    document_name: 'Updated Passport - Renewed 2025',
    notes: 'New passport received. Valid until 2035.'
  })
});
```

---

### Example 4: Replace Outdated File

**Scenario:** Student got a new passport and wants to replace the old scan.

```javascript
const formData = new FormData();
formData.append('file', newPassportScan);

const response = await fetch('/api/documents/1/replace-file', {
  method: 'POST',
  headers: { 'Authorization': 'Bearer TOKEN' },
  body: formData
});
```

---

### Example 5: Delete Unnecessary Documents

**Scenario:** Student wants to remove documents they no longer need.

```javascript
const response = await fetch('/api/documents/5', {
  method: 'DELETE',
  headers: { 'Authorization': 'Bearer TOKEN' }
});
```

---

## Features Details

### 1. Automatic File Metadata
When you upload a file, the system automatically captures:
- Original filename
- File type (MIME type)
- File size in bytes
- Upload timestamp

### 2. Smart File Size Formatting
File sizes are automatically formatted for easy reading:
- 500 bytes → "500 bytes"
- 1024 bytes → "1.00 KB"
- 1048576 bytes → "1.00 MB"
- 1073741824 bytes → "1.00 GB"

### 3. File Type Detection
The system categorizes files into groups:
- **Images**: JPG, PNG, GIF, WebP
- **PDF Documents**: PDF files
- **Office Documents**: Word, Excel, PowerPoint
- **Other Files**: Everything else

### 4. Search Functionality
Search works across multiple fields:
- Document name
- Notes/descriptions
- Original filename

### 5. Sorting Options
Sort documents by:
- `document_name` - Alphabetically by name
- `created_at` - By upload date
- `updated_at` - By last modification date
- `file_size` - By file size

---

## Security Features

✅ **Authentication Required** - All endpoints require valid auth token  
✅ **User Isolation** - Students can only access their own documents  
✅ **File Validation** - File size and type validation  
✅ **Secure Storage** - Files stored in protected directory  
✅ **Soft Deletes** - Documents can be recovered if needed  
✅ **Auto Cleanup** - Old files automatically removed  

---

## Validation Rules

### Upload Document
- `document_name`: Required, string, max 255 characters
- `notes`: Optional, string, max 5000 characters
- `file`: Required, file, max 10MB

### Update Document
- `document_name`: Optional, string, max 255 characters
- `notes`: Optional, string, max 5000 characters

### Replace File
- `file`: Required, file, max 10MB

---

## Error Handling

### Common Errors

**404 - Document Not Found**
```json
{
  "error": true,
  "message": "Failed to retrieve document",
  "data": "Model not found"
}
```

**422 - Validation Error**
```json
{
  "error": true,
  "message": "Validation error",
  "data": {
    "document_name": ["The document name field is required."],
    "file": ["The file field is required."]
  }
}
```

**500 - Server Error**
```json
{
  "error": true,
  "message": "Failed to upload document",
  "data": "Error details here"
}
```

---

## Model Features

### StudentDocument Model

**Relationships:**
- `user()` - Belongs to User

**Accessors:**
- `file_url` - Returns public URL to file
- `file_size_formatted` - Returns human-readable file size

**Helper Methods:**
- `getFileExtension()` - Get file extension
- `isImage()` - Check if file is an image
- `isPdf()` - Check if file is a PDF
- `isDocument()` - Check if file is a document

**Scopes:**
- `byUser($userId)` - Filter by user
- `search($term)` - Search documents

---

## Frontend Integration Examples

### React/Next.js Example

```jsx
import { useState } from 'react';

function DocumentUpload() {
  const [document, setDocument] = useState({
    name: '',
    notes: '',
    file: null
  });

  const handleUpload = async (e) => {
    e.preventDefault();
    
    const formData = new FormData();
    formData.append('document_name', document.name);
    formData.append('notes', document.notes);
    formData.append('file', document.file);

    const response = await fetch('/api/documents', {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`
      },
      body: formData
    });

    const data = await response.json();
    if (!data.error) {
      alert('Document uploaded successfully!');
    }
  };

  return (
    <form onSubmit={handleUpload}>
      <input
        type="text"
        placeholder="Document Name"
        value={document.name}
        onChange={(e) => setDocument({...document, name: e.target.value})}
        required
      />
      
      <textarea
        placeholder="Notes (optional)"
        value={document.notes}
        onChange={(e) => setDocument({...document, notes: e.target.value})}
      />
      
      <input
        type="file"
        onChange={(e) => setDocument({...document, file: e.target.files[0]})}
        required
      />
      
      <button type="submit">Upload</button>
    </form>
  );
}
```

### Vue.js Example

```vue
<template>
  <div>
    <h2>My Documents</h2>
    <div v-for="doc in documents" :key="doc.id" class="document-card">
      <h3>{{ doc.document_name }}</h3>
      <p>{{ doc.notes }}</p>
      <p>Size: {{ doc.file_size_formatted }}</p>
      <p>Uploaded: {{ formatDate(doc.created_at) }}</p>
      <button @click="downloadDocument(doc.id)">Download</button>
      <button @click="deleteDocument(doc.id)">Delete</button>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      documents: []
    };
  },
  mounted() {
    this.fetchDocuments();
  },
  methods: {
    async fetchDocuments() {
      const response = await fetch('/api/documents', {
        headers: {
          'Authorization': `Bearer ${this.token}`
        }
      });
      const data = await response.json();
      this.documents = data.data.data;
    },
    async downloadDocument(id) {
      window.location.href = `/api/documents/${id}/download`;
    },
    async deleteDocument(id) {
      if (confirm('Are you sure?')) {
        await fetch(`/api/documents/${id}`, {
          method: 'DELETE',
          headers: {
            'Authorization': `Bearer ${this.token}`
          }
        });
        this.fetchDocuments();
      }
    }
  }
};
</script>
```

---

## Best Practices

### For Students

1. **Use Descriptive Names**: Give documents clear, meaningful names
2. **Add Helpful Notes**: Include expiry dates, renewal info, or other important details
3. **Organize Regularly**: Delete documents you no longer need
4. **Check Statistics**: Monitor your storage usage
5. **Keep Backups**: Download important documents for local backup

### For Developers

1. **Validate File Types**: Add specific file type restrictions if needed
2. **Monitor Storage**: Implement storage quotas per user
3. **Add File Previews**: Show thumbnails for images and PDFs
4. **Implement Categories**: Add document categories or tags
5. **Add Expiry Tracking**: Track document expiry dates

---

## Summary

The Student Document Management System provides a complete solution for students to:

✅ Upload and store documents securely  
✅ Organize files with custom names and notes  
✅ Search and filter documents easily  
✅ Update document details  
✅ Replace files when needed  
✅ Download documents anytime  
✅ Track storage usage  
✅ Delete unwanted documents  

All files are stored in the public disk (`storage/app/public/student_documents/`) and are accessible via secure authenticated endpoints.

**Ready to use!** 🚀

