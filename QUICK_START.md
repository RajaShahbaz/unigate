# 🚀 University Admission API - Quick Start

## ⚡ 3-Step Setup

### Step 1: Run Migrations
```bash
php artisan migrate
```

### Step 2: Seed Sample Data
```bash
php artisan db:seed --class=UniversityAdmissionSeeder
```

### Step 3: Link Storage
```bash
php artisan storage:link
```

## ✅ You're Ready!

Your database now has:
- ✅ 15 Interests (Software Engineering, CS, AI, etc.)
- ✅ 15 Cities (Lahore, Karachi, Islamabad, etc.)
- ✅ 10 Universities (FAST, NUST, UET, LUMS, etc.)
- ✅ 40+ Programs with fees

---

## 📱 Test the API

### Import Postman Collection
Import: `University_Admission_API.postman_collection.json`

### Or Use cURL

**Get Universities:**
```bash
curl http://localhost:8000/api/universities
```

**Get Interests:**
```bash
curl http://localhost:8000/api/interests/all
```

**Get Cities:**
```bash
curl http://localhost:8000/api/cities/all
```

---

## 🔑 Key Endpoints

### Public (No Auth)
- `GET /api/universities` - Browse universities
- `GET /api/interests/all` - Get interests
- `GET /api/cities/all` - Get cities

### Student (Auth Required)
- `POST /api/education` - Add education
- `POST /api/interests` - Select interests
- `POST /api/preferred-cities` - Select cities
- `GET /api/universities/recommended` - Get recommendations
- `POST /api/favorites` - Add to favorites

### Admin (Auth Required)
- `POST /api/admin/universities` - Create university
- `POST /api/admin/interests` - Create interest
- `POST /api/admin/cities` - Create city

---

## 📚 Documentation Files

| File | Purpose |
|------|---------|
| `API_DOCUMENTATION.md` | Complete API reference |
| `UNIVERSITY_ADMISSION_API_SETUP.md` | Detailed setup guide |
| `SEEDING_GUIDE.md` | Database seeding guide |
| `IMPLEMENTATION_SUMMARY.md` | Full implementation details |
| `University_Admission_API.postman_collection.json` | Postman collection |

---

## 🎯 Example Workflow

### 1. Student Registers
```bash
POST /api/register
```

### 2. Student Adds Education
```bash
POST /api/education
{
  "education_level": "intermediate",
  "institution_name": "Government College",
  "percentage_cgpa": 85.5
}
```

### 3. Student Selects Interests
```bash
POST /api/interests
{
  "interest_ids": [1, 2]  # CS, SE
}
```

### 4. Student Selects Cities
```bash
POST /api/preferred-cities
{
  "city_ids": [2, 3]  # Lahore, Islamabad
}
```

### 5. Get Recommendations
```bash
GET /api/universities/recommended
```

### 6. Add to Favorites
```bash
POST /api/favorites
{
  "university_id": 1
}
```

---

## 🔄 Reset Everything

```bash
php artisan migrate:fresh
php artisan db:seed --class=UniversityAdmissionSeeder
php artisan storage:link
```

---

## 📊 Seeded Universities

1. **FAST NUCES** - Lahore (Private)
2. **NUST** - Islamabad (Semi-Gov)
3. **UET** - Lahore (Public)
4. **LUMS** - Lahore (Private)
5. **NED** - Karachi (Public)
6. **COMSATS** - Islamabad (Public)
7. **UCP** - Lahore (Private)
8. **University of Faisalabad** (Private)
9. **Bahria University** - Islamabad (Private)
10. **University of Peshawar** (Public)

See `SEEDING_GUIDE.md` for complete details.

---

## ❓ Need Help?

- **Full API Docs:** `API_DOCUMENTATION.md`
- **Setup Guide:** `UNIVERSITY_ADMISSION_API_SETUP.md`
- **Seeding Guide:** `SEEDING_GUIDE.md`
- **Implementation:** `IMPLEMENTATION_SUMMARY.md`

---

**You're all set! Start building! 🎉**

