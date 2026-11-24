# Application Dashboard API Documentation

## Overview
This API provides a comprehensive dashboard for users to view their application statistics, profile completion status, and personalized tips to improve their profile and application success rate.

---

## Endpoint

### Get Application Dashboard
Retrieves dashboard data including application statistics, profile completion percentage, and improvement tips.

**URL:** `/api/dashboard`  
**Method:** `GET`  
**Authentication:** Required (Bearer Token)

---

## Request

### Headers
```http
Authorization: Bearer {access_token}
Accept: application/json
```

### Query Parameters
None required.

---

## Response

### Success Response (200 OK)

```json
{
    "error": false,
    "message": "Dashboard data retrieved successfully",
    "data": {
        "application_statistics": {
            "total_applications": 5,
            "pending": 2,
            "accepted": 1,
            "rejected": 0,
            "draft": 1,
            "shortlisted": 1,
            "withdrawn": 0
        },
        "profile_completion": {
            "percentage": 78.5,
            "completed_sections": 4,
            "total_sections": 6,
            "details": [
                {
                    "section": "Basic Info",
                    "percentage": 100,
                    "completed": 6,
                    "total": 6,
                    "weight": 20
                },
                {
                    "section": "Address Info",
                    "percentage": 100,
                    "completed": 2,
                    "total": 2,
                    "weight": 10
                },
                {
                    "section": "Profile Photo",
                    "percentage": 100,
                    "completed": 1,
                    "total": 1,
                    "weight": 10
                },
                {
                    "section": "Education",
                    "percentage": 100,
                    "completed": 1,
                    "total": 1,
                    "weight": 25
                },
                {
                    "section": "Interests",
                    "percentage": 100,
                    "completed": 1,
                    "total": 1,
                    "weight": 15
                },
                {
                    "section": "Documents",
                    "percentage": 66.67,
                    "completed": 2,
                    "total": 3,
                    "weight": 20
                }
            ]
        },
        "improvement_tips": [
            {
                "title": "Upload Required Documents",
                "description": "Upload important documents like transcripts, ID proof, and certificates. Having these ready speeds up your application process.",
                "priority": "high",
                "action": "upload_documents",
                "icon": "📄"
            },
            {
                "title": "Complete Draft Applications",
                "description": "You have 1 draft application(s). Complete and submit them to increase your admission chances.",
                "priority": "medium",
                "action": "complete_drafts",
                "icon": "✍️"
            },
            {
                "title": "Complete Your Profile",
                "description": "Your profile is 78.5% complete. A complete profile improves your application credibility.",
                "priority": "medium",
                "action": "complete_profile",
                "icon": "👤"
            }
        ],
        "recent_applications": [
            {
                "id": 15,
                "application_number": "APP2025000015",
                "university_name": "Harvard University",
                "program_name": "Computer Science",
                "status": "under_review",
                "submitted_at": "2025-10-15 14:30:00",
                "updated_at": "2025-11-01 09:20:00"
            },
            {
                "id": 12,
                "application_number": "APP2025000012",
                "university_name": "MIT",
                "program_name": "Artificial Intelligence",
                "status": "shortlisted",
                "submitted_at": "2025-10-10 11:45:00",
                "updated_at": "2025-10-28 16:15:00"
            }
        ]
    },
    "code": 200
}
```

---

## Response Fields Explanation

### Application Statistics
| Field | Type | Description |
|-------|------|-------------|
| `total_applications` | integer | Total number of applications submitted by the user |
| `pending` | integer | Applications with status 'submitted' or 'under_review' |
| `accepted` | integer | Applications with status 'accepted' |
| `rejected` | integer | Applications with status 'rejected' |
| `draft` | integer | Incomplete applications saved as drafts |
| `shortlisted` | integer | Applications that have been shortlisted |
| `withdrawn` | integer | Applications withdrawn by the user |

### Profile Completion
| Field | Type | Description |
|-------|------|-------------|
| `percentage` | float | Overall profile completion percentage (0-100) |
| `completed_sections` | integer | Number of fully completed profile sections |
| `total_sections` | integer | Total number of profile sections |
| `details` | array | Detailed breakdown of each section |

#### Profile Section Details
Each section in the `details` array contains:
- **section**: Name of the profile section
- **percentage**: Completion percentage for this section (0-100)
- **completed**: Number of completed fields in this section
- **total**: Total number of fields in this section
- **weight**: Weight of this section in overall profile completion (%)

#### Profile Sections and Their Weights
1. **Basic Info (20%)**: First name, last name, email, mobile, gender, date of birth
2. **Address Info (10%)**: Current address, permanent address
3. **Profile Photo (10%)**: Profile image
4. **Education (25%)**: At least one education record
5. **Interests (15%)**: At least one field of interest
6. **Documents (20%)**: At least 3 uploaded documents

### Improvement Tips
Each tip contains:
| Field | Type | Description |
|-------|------|-------------|
| `title` | string | Short title of the tip |
| `description` | string | Detailed description of what to do |
| `priority` | string | Priority level: 'high', 'medium', 'low', or 'info' |
| `action` | string | Action identifier for frontend navigation |
| `icon` | string | Emoji icon for visual representation |

#### Tip Action Types
- `upload_photo`: Navigate to profile photo upload
- `add_education`: Navigate to education form
- `add_interests`: Navigate to interests selection
- `update_address`: Navigate to address form
- `upload_documents`: Navigate to documents upload
- `complete_drafts`: Navigate to draft applications
- `browse_universities`: Navigate to university listing
- `complete_profile`: Navigate to profile settings

### Recent Applications
Shows the 5 most recently updated applications with:
| Field | Type | Description |
|-------|------|-------------|
| `id` | integer | Application ID |
| `application_number` | string | Unique application number |
| `university_name` | string | Name of the university |
| `program_name` | string | Name of the program/field of interest |
| `status` | string | Current application status |
| `submitted_at` | datetime | When the application was submitted (null for drafts) |
| `updated_at` | datetime | Last update timestamp |

---

## Application Status Values
- `draft`: Application is incomplete and not submitted
- `submitted`: Application has been submitted and awaiting review
- `under_review`: Application is being reviewed by the university
- `shortlisted`: Application has been shortlisted for next stage
- `accepted`: Application has been accepted
- `rejected`: Application has been rejected
- `withdrawn`: Application was withdrawn by the user

---

## Error Responses

### 401 Unauthorized
```json
{
    "error": true,
    "message": "Unauthenticated.",
    "code": 401
}
```

### 500 Internal Server Error
```json
{
    "error": true,
    "message": "Error message describing what went wrong",
    "code": 500
}
```

---

## Usage Examples

### cURL Example
```bash
curl -X GET \
  'https://your-domain.com/api/dashboard' \
  -H 'Authorization: Bearer your_access_token_here' \
  -H 'Accept: application/json'
```

### JavaScript (Axios) Example
```javascript
const axios = require('axios');

const getDashboard = async () => {
    try {
        const response = await axios.get('https://your-domain.com/api/dashboard', {
            headers: {
                'Authorization': 'Bearer your_access_token_here',
                'Accept': 'application/json'
            }
        });
        
        console.log('Dashboard Data:', response.data);
        
        // Access specific data
        const stats = response.data.data.application_statistics;
        console.log(`Total Applications: ${stats.total_applications}`);
        console.log(`Pending: ${stats.pending}`);
        console.log(`Accepted: ${stats.accepted}`);
        
        const profileCompletion = response.data.data.profile_completion;
        console.log(`Profile Completion: ${profileCompletion.percentage}%`);
        
        const tips = response.data.data.improvement_tips;
        console.log('Improvement Tips:');
        tips.forEach(tip => {
            console.log(`- [${tip.priority.toUpperCase()}] ${tip.title}: ${tip.description}`);
        });
        
    } catch (error) {
        console.error('Error fetching dashboard:', error.response?.data || error.message);
    }
};

getDashboard();
```

### PHP Example
```php
<?php

$token = 'your_access_token_here';
$url = 'https://your-domain.com/api/dashboard';

$headers = [
    'Authorization: Bearer ' . $token,
    'Accept: application/json'
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    $data = json_decode($response, true);
    
    echo "Total Applications: " . $data['data']['application_statistics']['total_applications'] . "\n";
    echo "Profile Completion: " . $data['data']['profile_completion']['percentage'] . "%\n";
    
    echo "\nImprovement Tips:\n";
    foreach ($data['data']['improvement_tips'] as $tip) {
        echo "- " . $tip['title'] . "\n";
    }
} else {
    echo "Error: HTTP " . $httpCode . "\n";
    echo $response . "\n";
}
```

### Flutter/Dart Example
```dart
import 'package:http/http.dart' as http;
import 'dart:convert';

Future<void> getDashboard(String accessToken) async {
  final url = Uri.parse('https://your-domain.com/api/dashboard');
  
  try {
    final response = await http.get(
      url,
      headers: {
        'Authorization': 'Bearer $accessToken',
        'Accept': 'application/json',
      },
    );
    
    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      
      // Access dashboard data
      final stats = data['data']['application_statistics'];
      print('Total Applications: ${stats['total_applications']}');
      print('Pending: ${stats['pending']}');
      print('Accepted: ${stats['accepted']}');
      
      final profileCompletion = data['data']['profile_completion'];
      print('Profile Completion: ${profileCompletion['percentage']}%');
      
      final tips = data['data']['improvement_tips'] as List;
      print('\nImprovement Tips:');
      for (var tip in tips) {
        print('${tip['icon']} ${tip['title']}: ${tip['description']}');
      }
      
      final recentApps = data['data']['recent_applications'] as List;
      print('\nRecent Applications:');
      for (var app in recentApps) {
        print('${app['application_number']} - ${app['university_name']} (${app['status']})');
      }
    } else {
      print('Error: ${response.statusCode}');
      print(response.body);
    }
  } catch (e) {
    print('Exception: $e');
  }
}
```

---

## Frontend Integration Tips

### Displaying Statistics
Use the statistics to create visual charts and cards:
- **Pie Chart**: Show distribution of application statuses
- **Progress Bar**: Display profile completion percentage
- **Count Cards**: Show total, pending, accepted counts
- **Timeline**: Display recent applications in chronological order

### Handling Improvement Tips
- Display tips in order of priority (high → medium → low → info)
- Use the `icon` field for visual appeal
- Make tips clickable using the `action` field to navigate to relevant screens
- Use color coding: red for high priority, yellow for medium, blue for info
- Show a badge or notification indicator for high-priority tips

### Profile Completion Widget
```javascript
// Example React component
const ProfileCompletionWidget = ({ profileCompletion }) => {
  const { percentage, details } = profileCompletion;
  
  const getColorClass = (percentage) => {
    if (percentage >= 80) return 'text-green-500';
    if (percentage >= 50) return 'text-yellow-500';
    return 'text-red-500';
  };
  
  return (
    <div className="profile-completion">
      <h3>Profile Completion</h3>
      <div className={`percentage ${getColorClass(percentage)}`}>
        {percentage}%
      </div>
      <div className="progress-bar">
        <div 
          className="progress-fill" 
          style={{ width: `${percentage}%` }}
        />
      </div>
      
      <div className="sections">
        {details.map(section => (
          <div key={section.section} className="section-item">
            <span>{section.section}</span>
            <span>{section.completed}/{section.total}</span>
            <div className="mini-progress">
              <div style={{ width: `${section.percentage}%` }} />
            </div>
          </div>
        ))}
      </div>
    </div>
  );
};
```

---

## Notes

1. **Caching**: Consider caching dashboard data on the client side for 5-10 minutes to reduce API calls
2. **Real-time Updates**: Refresh dashboard after user actions like submitting an application or updating profile
3. **Profile Completion Algorithm**: Uses weighted scoring where critical sections (Education, Documents) have higher weights
4. **Tip Priority**: Tips are automatically sorted by priority, with high-priority items appearing first
5. **Recent Applications Limit**: Only returns the 5 most recent applications; use the applications API for complete listing
6. **Performance**: Dashboard query is optimized but may be slower for users with many applications (100+)

---

## Related Endpoints

- `GET /api/profile` - Get complete user profile
- `GET /api/applications/my-applications` - Get all user applications
- `POST /api/education` - Add education record
- `POST /api/interests` - Add interests
- `POST /api/documents` - Upload documents
- `GET /api/universities` - Browse universities

---

## Version History

| Version | Date | Changes |
|---------|------|---------|
| 1.0.0 | 2025-11-03 | Initial release with application statistics, profile completion, and improvement tips |

