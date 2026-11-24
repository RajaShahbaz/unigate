# University Admission System - Seeding Guide

## 🌱 Database Seeding Options

This guide explains how to populate your database with sample data for testing the University Admission System.

---

## Quick Start (Recommended)

### Option 1: Seed Everything at Once
Run this single command to seed all data in the correct order:

```bash
php artisan db:seed --class=UniversityAdmissionSeeder
```

This will create:
- ✅ 15 Academic Interests/Fields
- ✅ 15 Pakistani Cities
- ✅ 10 Universities with complete information
- ✅ 40+ Programs/Departments across universities

---

## Option 2: Seed Step by Step

If you prefer to seed data in stages:

### Step 1: Seed Interests and Cities
```bash
php artisan db:seed --class=InterestsAndCitiesSeeder
```

**Creates:**
- 15 Interests (Software Engineering, Computer Science, AI, etc.)
- 15 Cities (Lahore, Karachi, Islamabad, etc.)

### Step 2: Seed Universities
```bash
php artisan db:seed --class=UniversitySeeder
```

**Creates:**
- 10 Universities with complete details
- Programs for each university with fees and duration

---

## What Gets Seeded?

### 📚 Interests (15 Total)
1. Computer Science
2. Software Engineering
3. Information Technology
4. Electrical Engineering
5. Mechanical Engineering
6. Civil Engineering
7. Business Administration
8. Medical & Health Sciences
9. Law
10. Architecture
11. Economics
12. Biotechnology
13. Data Science
14. Artificial Intelligence
15. Pharmacy

### 🏙️ Cities (15 Total)
1. Karachi (Sindh)
2. Lahore (Punjab)
3. Islamabad (ICT)
4. Rawalpindi (Punjab)
5. Faisalabad (Punjab)
6. Multan (Punjab)
7. Peshawar (KPK)
8. Quetta (Balochistan)
9. Sialkot (Punjab)
10. Gujranwala (Punjab)
11. Hyderabad (Sindh)
12. Bahawalpur (Punjab)
13. Sargodha (Punjab)
14. Abbottabad (KPK)
15. Sukkur (Sindh)

### 🏛️ Universities (10 Total)

#### 1. FAST NUCES - Lahore
- **Type:** Private
- **Fee Range:** 200,000 - 500,000 PKR
- **Programs:** CS, SE, AI, Data Science
- **Hostel:** Yes | **Scholarships:** Yes

#### 2. NUST - Islamabad
- **Type:** Semi-Government
- **Fee Range:** 250,000 - 600,000 PKR
- **Programs:** EE, ME, CE, CS, BBA
- **Hostel:** Yes | **Scholarships:** Yes

#### 3. UET Lahore
- **Type:** Public
- **Fee Range:** 50,000 - 150,000 PKR
- **Programs:** EE, ME, CE, CS, Architecture
- **Hostel:** Yes | **Scholarships:** Yes

#### 4. LUMS - Lahore
- **Type:** Private
- **Fee Range:** 800,000 - 1,200,000 PKR
- **Programs:** BBA, Economics, CS, Law
- **Hostel:** Yes | **Scholarships:** Yes

#### 5. NED University - Karachi
- **Type:** Public
- **Fee Range:** 60,000 - 180,000 PKR
- **Programs:** EE, ME, CE, CS, Biotech
- **Hostel:** No | **Scholarships:** Yes

#### 6. COMSATS - Islamabad
- **Type:** Public
- **Fee Range:** 80,000 - 200,000 PKR
- **Programs:** CS, SE, IT, EE, Data Science
- **Hostel:** Yes | **Scholarships:** Yes

#### 7. UCP - Lahore
- **Type:** Private
- **Fee Range:** 180,000 - 450,000 PKR
- **Programs:** CS, BBA, Law, Architecture
- **Hostel:** No | **Scholarships:** Yes

#### 8. University of Faisalabad
- **Type:** Private
- **Fee Range:** 120,000 - 300,000 PKR
- **Programs:** CS, BBA, Biotechnology
- **Hostel:** Yes | **Scholarships:** Yes

#### 9. Bahria University - Islamabad
- **Type:** Private
- **Fee Range:** 220,000 - 500,000 PKR
- **Programs:** CS, SE, EE, BBA, AI
- **Hostel:** Yes | **Scholarships:** Yes

#### 10. University of Peshawar
- **Type:** Public
- **Fee Range:** 40,000 - 120,000 PKR
- **Programs:** CS, BBA, Economics
- **Hostel:** Yes | **Scholarships:** Yes

---

## Complete Setup Workflow

### 1. Fresh Installation
```bash
# Run migrations
php artisan migrate

# Seed all data
php artisan db:seed --class=UniversityAdmissionSeeder

# Link storage for file uploads
php artisan storage:link
```

### 2. Reset and Re-seed
```bash
# Fresh migration with seeding
php artisan migrate:fresh --seed

# Or specifically run university admission seeder
php artisan migrate:fresh
php artisan db:seed --class=UniversityAdmissionSeeder
```

### 3. Add More Data
If you want to add more universities manually:

```bash
# Use the admin API endpoints
POST /api/admin/universities
```

See `API_DOCUMENTATION.md` for complete API reference.

---

## Verifying Seeded Data

### Check Interests
```bash
php artisan tinker
>>> \App\Models\Interest::count()
=> 15
>>> \App\Models\Interest::pluck('name')
```

### Check Cities
```bash
php artisan tinker
>>> \App\Models\City::count()
=> 15
>>> \App\Models\City::pluck('name')
```

### Check Universities
```bash
php artisan tinker
>>> \App\Models\University::count()
=> 10
>>> \App\Models\University::with('interests')->get()
```

---

## Sample Data Statistics

After running the seeders, you'll have:

| Entity | Count | Details |
|--------|-------|---------|
| Interests | 15 | Academic fields/departments |
| Cities | 15 | Pakistani cities with provinces |
| Universities | 10 | Complete university profiles |
| Programs | 40+ | University-specific programs with fees |

---

## Customizing Seed Data

### Add Custom Interest
Edit: `database/seeders/InterestsAndCitiesSeeder.php`

```php
$interests = [
    // ... existing interests
    ['name' => 'Your Custom Interest', 'description' => 'Description here'],
];
```

### Add Custom City
Edit: `database/seeders/InterestsAndCitiesSeeder.php`

```php
$cities = [
    // ... existing cities
    ['name' => 'Your City', 'state_province' => 'Province', 'country' => 'Pakistan'],
];
```

### Add Custom University
Edit: `database/seeders/UniversitySeeder.php`

Add to the `$universities` array following the existing structure.

---

## Testing After Seeding

### 1. Test Public APIs (No Auth Required)
```bash
# Get all universities
curl http://localhost:8000/api/universities

# Get all interests
curl http://localhost:8000/api/interests/all

# Get all cities
curl http://localhost:8000/api/cities/all
```

### 2. Test with Postman
Import: `University_Admission_API.postman_collection.json`

### 3. Verify Relationships
```bash
php artisan tinker
>>> $uni = \App\Models\University::first()
>>> $uni->interests  // Check programs
>>> $uni->city       // Check city relationship
```

---

## Troubleshooting

### Issue: "Class not found"
**Solution:**
```bash
composer dump-autoload
php artisan db:seed --class=UniversityAdmissionSeeder
```

### Issue: Foreign key constraint fails
**Solution:** Seed in correct order (Interests & Cities first, then Universities)
```bash
php artisan db:seed --class=InterestsAndCitiesSeeder
php artisan db:seed --class=UniversitySeeder
```

### Issue: Duplicate entries
**Solution:** Reset database
```bash
php artisan migrate:fresh
php artisan db:seed --class=UniversityAdmissionSeeder
```

---

## Production Considerations

⚠️ **Warning:** These seeders are for **development/testing only**

For production:
1. Don't run seeders in production
2. Use proper data import tools
3. Validate all data before import
4. Backup database before any operations

---

## Next Steps

After seeding:
1. ✅ Test API endpoints with Postman
2. ✅ Create test user accounts
3. ✅ Add sample education records
4. ✅ Select interests and cities for users
5. ✅ Test university recommendations
6. ✅ Test favorites functionality

See `API_DOCUMENTATION.md` for complete API usage guide.

---

## Quick Commands Reference

```bash
# Seed everything
php artisan db:seed --class=UniversityAdmissionSeeder

# Reset and seed
php artisan migrate:fresh --seed

# Seed specific seeder
php artisan db:seed --class=InterestsAndCitiesSeeder
php artisan db:seed --class=UniversitySeeder

# Check seeded data
php artisan tinker
```

---

**Happy Testing! 🚀**

