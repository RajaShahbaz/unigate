# University Features - Gallery, Scholarships & Admission Requirements

## 🎯 Overview

This package adds three powerful features to your university management system:

1. **📸 Gallery Management** - Showcase university images and videos
2. **🎓 Scholarships Management** - Manage and display available scholarships
3. **📋 Admission Requirements** - Define admission criteria for programs

---

## ✨ Features

### Gallery Management
- ✅ Upload images and videos (max 10MB)
- ✅ Categorize content (campus, facilities, events, etc.)
- ✅ Feature important items
- ✅ Custom display ordering
- ✅ Soft delete support
- ✅ Public API for viewing
- ✅ Admin API for full CRUD operations

### Scholarships Management
- ✅ Multiple types (merit, need-based, sports, etc.)
- ✅ Three amount types (fixed, percentage, full)
- ✅ Application date management
- ✅ Slot tracking (total and available)
- ✅ Renewable scholarship support
- ✅ Automatic status calculation
- ✅ Contact information management

### Admission Requirements
- ✅ General and program-specific requirements
- ✅ Academic criteria (GPA, percentage)
- ✅ Entrance test requirements
- ✅ Language requirements (IELTS, TOEFL, etc.)
- ✅ Document requirements
- ✅ Application deadlines and fees
- ✅ Interview and portfolio requirements

---

## 📦 What's Included

### Files Created
- **3 Database Migrations** - Create tables for gallery, scholarships, and requirements
- **3 Models** - UniversityGallery, Scholarship, AdmissionRequirement
- **6 Repository Files** - Interface and implementation for each feature
- **3 Controllers** - Full CRUD operations for all features
- **API Routes** - Public and admin endpoints

### Files Modified
- `routes/api.php` - Added new API routes
- `app/Models/University.php` - Added relationships
- `app/Providers/RepositoryServiceProvider.php` - Registered repositories

---

## 🚀 Quick Start

### 1. Run Migrations
```bash
php artisan migrate
```

### 2. Test the API
Import the Postman collection: `University_Features_API.postman_collection.json`

Or use curl:
```bash
# View university gallery (Public)
curl -X GET "http://localhost:8000/api/universities/1/gallery"

# View scholarships (Public)
curl -X GET "http://localhost:8000/api/universities/1/scholarships"

# View admission requirements (Public)
curl -X GET "http://localhost:8000/api/universities/1/admission-requirements"
```

---

## 📚 Documentation

### Quick Reference
- **[Setup Guide](UNIVERSITY_FEATURES_SETUP_GUIDE.md)** - Complete setup instructions
- **[API Documentation](UNIVERSITY_FEATURES_API_DOCUMENTATION.md)** - Detailed API reference
- **[Postman Collection](University_Features_API.postman_collection.json)** - Import and test APIs

### API Endpoints Summary

#### Public Endpoints (No Auth)
```
GET /api/universities/{id}/gallery
GET /api/universities/{id}/scholarships
GET /api/universities/{id}/admission-requirements
GET /api/universities/{id}/programs/{programId}/admission-requirements
```

#### Admin Endpoints (Auth Required)
```
# Gallery
GET    /api/admin/university-galleries
POST   /api/admin/university-galleries
PUT    /api/admin/university-galleries/{id}
DELETE /api/admin/university-galleries/{id}

# Scholarships
GET    /api/admin/scholarships
POST   /api/admin/scholarships
PUT    /api/admin/scholarships/{id}
DELETE /api/admin/scholarships/{id}

# Admission Requirements
GET    /api/admin/admission-requirements
POST   /api/admin/admission-requirements
PUT    /api/admin/admission-requirements/{id}
DELETE /api/admin/admission-requirements/{id}
```

---

## 💡 Usage Examples

### Display University Gallery
```javascript
fetch('/api/universities/1/gallery?featured=1')
  .then(res => res.json())
  .then(data => {
    data.data.data.forEach(item => {
      console.log(item.title, item.image_url);
    });
  });
```

### Display Available Scholarships
```javascript
fetch('/api/universities/1/scholarships?accepting_applications=1')
  .then(res => res.json())
  .then(data => {
    data.data.data.forEach(scholarship => {
      console.log(scholarship.name, scholarship.amount);
    });
  });
```

### Check Admission Requirements
```javascript
fetch('/api/universities/1/admission-requirements?program_level=undergraduate')
  .then(res => res.json())
  .then(data => {
    data.data.data.forEach(req => {
      console.log(req.minimum_gpa, req.entrance_tests);
    });
  });
```

---

## 🔧 Configuration

### Storage Setup
For image uploads to work, ensure:

1. **Storage link exists:**
```bash
php artisan storage:link
```

2. **Permissions are set:**
```bash
chmod -R 775 storage
```

3. **Upload limits in php.ini:**
```ini
upload_max_filesize = 10M
post_max_size = 10M
```

### Clear Cache
After installation:
```bash
php artisan config:clear
php artisan route:clear
php artisan cache:clear
```

---

## 🗄️ Database Schema

### university_galleries
Stores gallery images and videos with categorization and ordering.

### scholarships
Manages scholarship information including eligibility, amounts, and application dates.

### admission_requirements
Stores admission criteria for universities and programs.

All tables include:
- Timestamps (created_at, updated_at)
- Soft deletes (deleted_at)
- Status flags

---

## 🔒 Security

- Admin endpoints require authentication via Sanctum
- File uploads validated for type and size
- Soft deletes for data recovery
- Input validation on all endpoints

---

## 🎨 Frontend Integration Tips

### Gallery Display
- Use `featured=1` to get hero images
- Filter by `category` for organized galleries
- Implement lightbox for image viewing
- Support video playback for video types

### Scholarship Display
- Show `is_accepting_applications` badge
- Display remaining slots with `available_slots`
- Highlight featured scholarships
- Show application deadlines

### Admission Requirements
- Group by `program_level`
- Show program-specific requirements
- Display required documents checklist
- Highlight application deadlines

---

## 📊 Data Examples

### Gallery Categories
- campus
- facilities
- events
- dormitories
- library
- laboratories
- sports
- student-life

### Scholarship Types
- merit
- need-based
- sports
- academic
- research
- minority
- international
- leadership

### Program Levels
- undergraduate
- postgraduate
- diploma
- certificate
- doctoral
- associate

---

## 🧪 Testing Checklist

- [ ] Migrations run successfully
- [ ] Gallery upload works (images/videos)
- [ ] Scholarships CRUD operations
- [ ] Admission requirements CRUD operations
- [ ] Public endpoints accessible without auth
- [ ] Admin endpoints require authentication
- [ ] Filtering works on all endpoints
- [ ] Pagination works correctly
- [ ] Soft delete works
- [ ] File uploads validated properly

---

## 🐛 Troubleshooting

### Routes not found
```bash
php artisan route:clear
php artisan route:cache
```

### Repository not found
```bash
php artisan config:clear
composer dump-autoload
```

### Image upload fails
1. Check storage permissions
2. Verify storage link exists
3. Check php.ini upload limits
4. Verify disk space available

---

## 📝 Notes

- All models support soft deletes
- Images stored in `storage/app/public/university_galleries`
- Scholarship slots automatically tracked
- Admission requirements can be general or program-specific
- All endpoints support pagination
- Extensive filtering options available

---

## 🤝 Support

- **Setup Issues:** See [UNIVERSITY_FEATURES_SETUP_GUIDE.md](UNIVERSITY_FEATURES_SETUP_GUIDE.md)
- **API Questions:** See [UNIVERSITY_FEATURES_API_DOCUMENTATION.md](UNIVERSITY_FEATURES_API_DOCUMENTATION.md)
- **Testing:** Import [University_Features_API.postman_collection.json](University_Features_API.postman_collection.json)

---

## 📄 License

This feature set is part of the SG Manager university management system.

---

## 🎉 Ready to Use!

Your university portal now has powerful tools to showcase campuses, manage scholarships, and define admission requirements. Students can easily explore galleries, discover scholarships, and understand admission criteria.

**Next Steps:**
1. Run `php artisan migrate`
2. Import Postman collection
3. Test the APIs
4. Integrate with your frontend
5. Add sample data

Happy coding! 🚀

