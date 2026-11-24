# 🎓 University Admission System API

A comprehensive Laravel API system for managing student university admissions with education tracking, interests, city preferences, university browsing, and favorites management.

---

## 📋 Table of Contents

- [Features](#features)
- [Quick Start](#quick-start)
- [What's Included](#whats-included)
- [API Endpoints](#api-endpoints)
- [Documentation](#documentation)
- [Database Schema](#database-schema)
- [Sample Data](#sample-data)

---

## ✨ Features

### Student Features
- ✅ **Education History** - Add education records (Matric, Intermediate, Graduation, etc.) with document uploads
- ✅ **Interests Selection** - Choose multiple academic interests/departments
- ✅ **City Preferences** - Select multiple preferred cities for study
- ✅ **Smart Recommendations** - Get university recommendations based on preferences
- ✅ **Favorites Management** - Save and manage favorite universities
- ✅ **University Browsing** - Search and filter universities

### Admin Features
- ✅ **University Management** - Complete CRUD with soft deletes
- ✅ **Program Management** - Assign programs to universities with fees
- ✅ **Interest Management** - Manage academic fields/departments
- ✅ **City Management** - Manage available cities
- ✅ **Media Upload** - University logos and cover images

---

## 🚀 Quick Start

### 3 Simple Steps:

```bash
# 1. Run migrations
php artisan migrate

# 2. Seed sample data (one command!)
php artisan db:seed --class=UniversityAdmissionSeeder

# 3. Link storage
php artisan storage:link
```

### You're Ready! 🎉

Your system now has:
- 15 Academic Interests
- 15 Pakistani Cities  
- 10 Complete Universities
- 40+ University Programs

---

## 📦 What's Included

### Database (8 Migrations)
1. `user_education` - Education history tracking
2. `interests` - Academic fields/departments
3. `user_interests` - Student interests (pivot)
4. `cities` - Available cities
5. `user_cities` - Preferred cities (pivot)
6. `universities` - University database
7. `university_interests` - University programs (pivot)
8. `user_favorites` - Favorite universities (pivot)

### Models (6 Files)
- `UserEducation.php`
- `Interest.php`
- `City.php`
- `University.php`
- `UserFavorite.php`
- `User.php` (updated with relationships)

### API Controllers (8 Files)

**Student Controllers:**
- `UserEducationController.php`
- `UserInterestController.php`
- `UserCityController.php`
- `UniversityController.php`
- `FavoriteController.php`

**Admin Controllers:**
- `UniversityAdminController.php`
- `InterestAdminController.php`
- `CityAdminController.php`

### Database Seeders (3 Files)
- `UniversityAdmissionSeeder.php` ⭐ (Master seeder - run this!)
- `InterestsAndCitiesSeeder.php`
- `UniversitySeeder.php`

### Documentation (6 Files)
1. **`QUICK_START.md`** - Start here! 3-step setup
2. **`API_DOCUMENTATION.md`** - Complete API reference (1500+ lines)
3. **`UNIVERSITY_ADMISSION_API_SETUP.md`** - Detailed setup guide (400+ lines)
4. **`SEEDING_GUIDE.md`** - Database seeding guide (350+ lines)
5. **`IMPLEMENTATION_SUMMARY.md`** - Full implementation details (500+ lines)
6. **`University_Admission_API.postman_collection.json`** - Ready to import

---

## 🔌 API Endpoints

### Public (No Authentication)
```
GET  /api/universities              # Browse universities
GET  /api/universities/{id}         # University details
GET  /api/interests/all             # All interests
GET  /api/cities/all                # All cities
```

### Student (Authentication Required)
```
# Education
GET    /api/education               # List education
POST   /api/education               # Add education
PUT    /api/education/{id}          # Update education
DELETE /api/education/{id}          # Delete education

# Interests
GET    /api/interests               # My interests
POST   /api/interests               # Add interests
POST   /api/interests/sync          # Replace all
DELETE /api/interests/{id}          # Remove interest

# Preferred Cities
GET    /api/preferred-cities        # My cities
POST   /api/preferred-cities        # Add cities
POST   /api/preferred-cities/sync   # Replace all
DELETE /api/preferred-cities/{id}   # Remove city

# Universities & Favorites
GET    /api/universities/recommended  # Smart recommendations
GET    /api/favorites                 # My favorites
POST   /api/favorites                 # Add to favorites
POST   /api/favorites/toggle          # Toggle favorite
DELETE /api/favorites/{id}            # Remove favorite
```

### Admin (Authentication Required)
```
# Universities
GET    /api/admin/universities           # List all
POST   /api/admin/universities           # Create
PUT    /api/admin/universities/{id}      # Update
DELETE /api/admin/universities/{id}      # Delete
POST   /api/admin/universities/{id}/restore        # Restore
POST   /api/admin/universities/{id}/interests      # Manage programs

# Interests & Cities
GET/POST/PUT/DELETE  /api/admin/interests/{id}
GET/POST/PUT/DELETE  /api/admin/cities/{id}
```

**Total: 40+ Endpoints**

---

## 📚 Documentation

| File | Purpose | When to Use |
|------|---------|-------------|
| **`QUICK_START.md`** | 3-step setup | Start here first! |
| **`API_DOCUMENTATION.md`** | Complete API reference | Building frontend/mobile |
| **`SEEDING_GUIDE.md`** | Database seeding | Setting up sample data |
| **`UNIVERSITY_ADMISSION_API_SETUP.md`** | Detailed setup | Full understanding |
| **`IMPLEMENTATION_SUMMARY.md`** | Technical details | Understanding architecture |

---

## 🗂️ Database Schema

### Core Relationships

```
User
├── education (hasMany UserEducation)
├── interests (belongsToMany Interest)
├── preferredCities (belongsToMany City)
└── favoriteUniversities (belongsToMany University)

University
├── city (belongsTo City)
├── interests (belongsToMany Interest) [with fees, duration]
└── favoritedBy (belongsToMany User)

Interest
├── users (belongsToMany User)
└── universities (belongsToMany University)

City
├── users (belongsToMany User)
└── universities (hasMany University)
```

---

## 📊 Sample Data (After Seeding)

### 15 Interests
Computer Science • Software Engineering • Information Technology • Electrical Engineering • Mechanical Engineering • Civil Engineering • Business Administration • Medical & Health Sciences • Law • Architecture • Economics • Biotechnology • Data Science • Artificial Intelligence • Pharmacy

### 15 Cities
Karachi • Lahore • Islamabad • Rawalpindi • Faisalabad • Multan • Peshawar • Quetta • Sialkot • Gujranwala • Hyderabad • Bahawalpur • Sargodha • Abbottabad • Sukkur

### 10 Universities

| University | City | Type | Fee Range | Programs |
|------------|------|------|-----------|----------|
| FAST NUCES | Lahore | Private | 200K-500K | CS, SE, AI, DS |
| NUST | Islamabad | Semi-Gov | 250K-600K | EE, ME, CE, CS, BBA |
| UET Lahore | Lahore | Public | 50K-150K | EE, ME, CE, CS, Arch |
| LUMS | Lahore | Private | 800K-1.2M | BBA, Econ, CS, Law |
| NED University | Karachi | Public | 60K-180K | EE, ME, CE, CS, Biotech |
| COMSATS | Islamabad | Public | 80K-200K | CS, SE, IT, EE, DS |
| UCP | Lahore | Private | 180K-450K | CS, BBA, Law, Arch |
| Univ of Faisalabad | Faisalabad | Private | 120K-300K | CS, BBA, Biotech |
| Bahria University | Islamabad | Private | 220K-500K | CS, SE, EE, BBA, AI |
| Univ of Peshawar | Peshawar | Public | 40K-120K | CS, BBA, Economics |

**Total: 40+ Programs across all universities**

---

## 🎯 Example Workflow

### Student Journey

```bash
# 1. Login
POST /api/login

# 2. Add Education
POST /api/education
{
  "education_level": "intermediate",
  "institution_name": "Government College",
  "percentage_cgpa": 85.5
}

# 3. Select Interests
POST /api/interests
{
  "interest_ids": [1, 2]  # CS, SE
}

# 4. Select Cities  
POST /api/preferred-cities
{
  "city_ids": [2, 3]  # Lahore, Islamabad
}

# 5. Get Recommendations
GET /api/universities/recommended
# Returns universities matching student's interests & cities

# 6. Add to Favorites
POST /api/favorites
{
  "university_id": 1
}
```

---

## 🧪 Testing

### Import Postman Collection
Import `University_Admission_API.postman_collection.json` into Postman for instant testing.

### Or Use cURL
```bash
# Get all universities
curl http://localhost:8000/api/universities

# Get all interests
curl http://localhost:8000/api/interests/all

# Get all cities
curl http://localhost:8000/api/cities/all
```

### Verify Seeded Data
```bash
php artisan tinker
>>> \App\Models\University::count()      # Should return 10
>>> \App\Models\Interest::count()        # Should return 15
>>> \App\Models\City::count()            # Should return 15
```

---

## 🔒 Security Features

- ✅ Sanctum bearer token authentication
- ✅ Per-user data isolation
- ✅ Input validation on all endpoints
- ✅ File upload validation (type, size)
- ✅ Soft deletes for universities
- ✅ Cascade deletes for user data

---

## 🛠️ Customization

### Add Custom Interest
Edit: `database/seeders/InterestsAndCitiesSeeder.php`

### Add Custom City
Edit: `database/seeders/InterestsAndCitiesSeeder.php`

### Add Custom University
Edit: `database/seeders/UniversitySeeder.php`

See `SEEDING_GUIDE.md` for detailed instructions.

---

## 🔄 Reset Everything

```bash
php artisan migrate:fresh
php artisan db:seed --class=UniversityAdmissionSeeder
php artisan storage:link
```

---

## 📈 Response Format

All endpoints return consistent JSON:

**Success:**
```json
{
  "error": false,
  "message": "Success message",
  "data": { ... }
}
```

**Error:**
```json
{
  "error": true,
  "message": "Error message",
  "data": { ... }
}
```

---

## 🆘 Troubleshooting

### Storage Link Missing
```bash
php artisan storage:link
```

### Seeders Not Found
```bash
composer dump-autoload
php artisan db:seed --class=UniversityAdmissionSeeder
```

### Reset Database
```bash
php artisan migrate:fresh --seed
```

See `UNIVERSITY_ADMISSION_API_SETUP.md` for more troubleshooting.

---

## 📞 Support & Resources

- **Quick Start:** `QUICK_START.md`
- **API Reference:** `API_DOCUMENTATION.md`
- **Seeding Guide:** `SEEDING_GUIDE.md`
- **Setup Guide:** `UNIVERSITY_ADMISSION_API_SETUP.md`
- **Full Details:** `IMPLEMENTATION_SUMMARY.md`

---

## 🎉 You're All Set!

Your University Admission System API is ready to use. Start with:

1. ✅ Read `QUICK_START.md`
2. ✅ Run the 3 setup commands
3. ✅ Import Postman collection
4. ✅ Start building your frontend!

**Built with Laravel 🚀 • Sanctum Auth 🔒 • RESTful APIs 📡**

---

*Happy Coding! 💻*

