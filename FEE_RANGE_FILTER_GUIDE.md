# University Interest Fee Range Feature Guide

## Overview
This feature allows you to manage tuition fee ranges for each university program/interest and filter universities based on fee ranges. Each program at a university can have its own specific fee range.

## Database Schema

The `university_interests` pivot table now includes:
- `fee` - General/average fee (existing)
- `fee_min` - Minimum fee for the program (new)
- `fee_max` - Maximum fee for the program (new)
- `duration_years` - Program duration
- `program_details` - Additional program information

## API Endpoints

### 1. Managing University Interests with Fee Ranges (Admin)

**Endpoint:** `PUT/PATCH /api/admin/universities/{id}/interests`

**Purpose:** Update university programs/interests with fee range information

**Request Body:**
```json
{
  "interests": [
    {
      "interest_id": 1,
      "fee": 50000,
      "fee_min": 45000,
      "fee_max": 55000,
      "duration_years": 4,
      "program_details": "Computer Science program with specialization options"
    },
    {
      "interest_id": 2,
      "fee": 40000,
      "fee_min": 38000,
      "fee_max": 42000,
      "duration_years": 4,
      "program_details": "Business Administration program"
    }
  ]
}
```

**Validation Rules:**
- `interests` - Required, must be an array
- `interests.*.interest_id` - Required, must exist in interests table
- `interests.*.fee` - Optional, numeric, minimum 0
- `interests.*.fee_min` - Optional, numeric, minimum 0
- `interests.*.fee_max` - Optional, numeric, minimum 0 (must be >= fee_min)
- `interests.*.duration_years` - Optional, integer, between 1-10
- `interests.*.program_details` - Optional, string

**Response:**
```json
{
  "error": false,
  "message": "University interests updated successfully",
  "data": {
    "id": 1,
    "name": "University of Example",
    "interests": [
      {
        "id": 1,
        "name": "Computer Science",
        "pivot": {
          "university_id": 1,
          "interest_id": 1,
          "fee": 50000,
          "fee_min": 45000,
          "fee_max": 55000,
          "duration_years": 4,
          "program_details": "Computer Science program with specialization options"
        }
      }
    ]
  }
}
```

### 2. Filtering Universities by Fee Range

**Endpoint:** `GET /api/universities`

**Purpose:** Get all universities with optional fee range filtering

**Query Parameters:**
- `search` - Search by university name
- `city_id` - Filter by city
- `interest_id` - Filter by interest/program
- `type` - Filter by university type (public, private, semi-government)
- `fee_min` - Minimum fee you're willing to pay
- `fee_max` - Maximum fee you're willing to pay
- `per_page` - Results per page (default: 10)

**Example Requests:**

1. **Get universities with programs between $30,000 - $50,000:**
```
GET /api/universities?fee_min=30000&fee_max=50000
```

2. **Get universities in specific city with fee range:**
```
GET /api/universities?city_id=5&fee_min=25000&fee_max=40000
```

3. **Get universities offering specific interest with fee range:**
```
GET /api/universities?interest_id=3&fee_min=20000&fee_max=35000
```

4. **Combine multiple filters:**
```
GET /api/universities?city_id=5&interest_id=3&type=public&fee_min=15000&fee_max=30000
```

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
        "name": "University of Example",
        "city": {
          "id": 5,
          "name": "New York"
        },
        "interests": [
          {
            "id": 3,
            "name": "Engineering",
            "pivot": {
              "fee": 35000,
              "fee_min": 32000,
              "fee_max": 38000,
              "duration_years": 4
            }
          }
        ],
        "is_favorite": false
      }
    ],
    "per_page": 10,
    "total": 1
  }
}
```

### 3. Get Recommended Universities (Authenticated Users)

**Endpoint:** `GET /api/universities/recommended`

**Purpose:** Get personalized university recommendations with fee range filtering

**Query Parameters:** (Same as above, plus user preferences are automatically considered)

**Example Request:**
```
GET /api/universities/recommended?fee_min=20000&fee_max=45000
```

This will return universities that:
1. Match user's preferred cities (if set)
2. Match user's interests (if set)
3. Have programs within the specified fee range

## How Fee Range Filtering Works

When you filter by fee range, the system uses intelligent matching:

1. **If you specify `fee_min`:** 
   - Returns universities where the program's maximum fee (`fee_max`) is greater than or equal to your minimum
   - This ensures you can afford at least some portion of the program

2. **If you specify `fee_max`:**
   - Returns universities where the program's minimum fee (`fee_min`) is less than or equal to your maximum
   - This ensures there are programs within your budget

3. **If you specify both:**
   - Returns universities with programs that have any overlap with your budget range
   - For example: If you search for $30k-$50k, it will show programs ranging from $25k-$55k, $35k-$45k, etc.

## Use Cases

### Example 1: Student with Limited Budget
A student with a budget of $15,000 - $25,000 wants to find universities:
```
GET /api/universities?fee_min=15000&fee_max=25000&interest_id=5
```

### Example 2: Finding Public Universities within Budget
```
GET /api/universities?type=public&fee_min=10000&fee_max=30000&city_id=3
```

### Example 3: Admin Adding Fee Range to Program
```json
PUT /api/admin/universities/5/interests
{
  "interests": [
    {
      "interest_id": 8,
      "fee": 28000,
      "fee_min": 25000,
      "fee_max": 31000,
      "duration_years": 3,
      "program_details": "MBA program with flexible payment options"
    }
  ]
}
```

## Benefits

1. **Better Search Experience:** Students can find universities within their budget
2. **Transparent Pricing:** Clear fee ranges for each program
3. **Flexible Filtering:** Combine fee range with other filters (city, type, interest)
4. **Program-Specific Fees:** Different programs at the same university can have different fee ranges
5. **Backward Compatible:** Existing `fee` field is still supported

## Notes

- Fee ranges are optional (nullable)
- Universities/programs without fee ranges will still appear in search results
- The `fee` field can be used as an average or base fee
- `fee_max` must be greater than or equal to `fee_min`
- All fee values are stored as decimal(10,2) for precision

