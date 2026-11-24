# My Applications API - Status-Wise Grouped (Updated)

## 📋 Overview

The `my-applications` API has been **updated** to return applications grouped by status instead of paginated data. This makes it easier to display applications in organized sections based on their current state.

---

## 🔄 What Changed

### ❌ Old Format (Paginated)
```json
{
    "data": {
        "current_page": 1,
        "data": [
            {/* application 1 */},
            {/* application 2 */}
        ],
        "per_page": 15,
        "total": 5
    }
}
```

### ✅ New Format (Status-Wise Grouped)
```json
{
    "data": {
        "summary": {
            "total": 5,
            "draft": 1,
            "submitted": 1,
            "under_review": 1,
            "shortlisted": 1,
            "accepted": 1,
            "rejected": 0,
            "withdrawn": 0
        },
        "applications": {
            "draft": [/* draft applications */],
            "submitted": [/* submitted applications */],
            "under_review": [/* under review applications */],
            "shortlisted": [/* shortlisted applications */],
            "accepted": [/* accepted applications */],
            "rejected": [/* rejected applications */],
            "withdrawn": [/* withdrawn applications */]
        }
    }
}
```

---

## 📡 API Endpoint

### Get My Applications (Status-Wise)

**URL:** `/api/applications/my-applications`  
**Method:** `GET`  
**Authentication:** Required (Bearer Token)

---

## 📥 Request

### Headers
```http
Authorization: Bearer {access_token}
Accept: application/json
```

### Query Parameters
**None** - All applications are returned grouped by status (no pagination)

---

## 📤 Response

### Success Response (200 OK)

```json
{
    "error": false,
    "message": "Applications retrieved successfully",
    "data": {
        "summary": {
            "total": 5,
            "draft": 1,
            "submitted": 1,
            "under_review": 1,
            "shortlisted": 1,
            "accepted": 1,
            "rejected": 0,
            "withdrawn": 0
        },
        "applications": {
            "draft": [
                {
                    "id": 10,
                    "application_number": "APP2025000010",
                    "university": {
                        "id": 5,
                        "name": "Stanford University",
                        "logo": "/storage/universities/stanford.png",
                        "city": "Stanford"
                    },
                    "interest": {
                        "id": 3,
                        "name": "Data Science"
                    },
                    "status": "draft",
                    "submitted_at": null,
                    "reviewed_at": null,
                    "created_at": "2025-11-01 10:30:00",
                    "updated_at": "2025-11-02 14:20:00"
                }
            ],
            "submitted": [
                {
                    "id": 12,
                    "application_number": "APP2025000012",
                    "university": {
                        "id": 2,
                        "name": "MIT",
                        "logo": "/storage/universities/mit.png",
                        "city": "Cambridge"
                    },
                    "interest": {
                        "id": 1,
                        "name": "Computer Science"
                    },
                    "status": "submitted",
                    "submitted_at": "2025-10-28 09:15:00",
                    "reviewed_at": null,
                    "created_at": "2025-10-27 16:45:00",
                    "updated_at": "2025-10-28 09:15:00"
                }
            ],
            "under_review": [
                {
                    "id": 15,
                    "application_number": "APP2025000015",
                    "university": {
                        "id": 1,
                        "name": "Harvard University",
                        "logo": "/storage/universities/harvard.png",
                        "city": "Cambridge"
                    },
                    "interest": {
                        "id": 1,
                        "name": "Computer Science"
                    },
                    "status": "under_review",
                    "submitted_at": "2025-10-15 14:30:00",
                    "reviewed_at": "2025-10-20 11:00:00",
                    "reviewer": {
                        "id": 45,
                        "name": "Dr. John Smith"
                    },
                    "created_at": "2025-10-14 08:20:00",
                    "updated_at": "2025-11-01 09:20:00"
                }
            ],
            "shortlisted": [
                {
                    "id": 13,
                    "application_number": "APP2025000013",
                    "university": {
                        "id": 3,
                        "name": "UC Berkeley",
                        "logo": "/storage/universities/berkeley.png",
                        "city": "Berkeley"
                    },
                    "interest": {
                        "id": 2,
                        "name": "Artificial Intelligence"
                    },
                    "status": "shortlisted",
                    "submitted_at": "2025-10-10 11:45:00",
                    "reviewed_at": "2025-10-25 15:30:00",
                    "reviewer": {
                        "id": 48,
                        "name": "Prof. Sarah Johnson"
                    },
                    "created_at": "2025-10-09 13:10:00",
                    "updated_at": "2025-10-28 16:15:00"
                }
            ],
            "accepted": [
                {
                    "id": 8,
                    "application_number": "APP2025000008",
                    "university": {
                        "id": 4,
                        "name": "Carnegie Mellon University",
                        "logo": "/storage/universities/cmu.png",
                        "city": "Pittsburgh"
                    },
                    "interest": {
                        "id": 1,
                        "name": "Computer Science"
                    },
                    "status": "accepted",
                    "submitted_at": "2025-09-15 10:00:00",
                    "reviewed_at": "2025-10-05 14:30:00",
                    "reviewer": {
                        "id": 50,
                        "name": "Dr. Emily Davis"
                    },
                    "created_at": "2025-09-14 11:30:00",
                    "updated_at": "2025-10-15 09:45:00"
                }
            ],
            "rejected": [],
            "withdrawn": []
        }
    },
    "code": 200
}
```

---

## 📊 Response Structure

### Summary Object
Contains count statistics for quick overview:

| Field | Type | Description |
|-------|------|-------------|
| `total` | integer | Total number of applications |
| `draft` | integer | Number of draft applications |
| `submitted` | integer | Number of submitted applications |
| `under_review` | integer | Number of applications under review |
| `shortlisted` | integer | Number of shortlisted applications |
| `accepted` | integer | Number of accepted applications |
| `rejected` | integer | Number of rejected applications |
| `withdrawn` | integer | Number of withdrawn applications |

### Applications Object
Contains 7 arrays, one for each status. Each array contains application objects with:

| Field | Type | Description |
|-------|------|-------------|
| `id` | integer | Application ID |
| `application_number` | string | Unique application number |
| `university` | object | University details (id, name, logo, city) |
| `interest` | object | Program/interest details (id, name) |
| `status` | string | Current application status |
| `submitted_at` | datetime/null | When application was submitted |
| `reviewed_at` | datetime/null | When application was reviewed |
| `reviewer` | object/null | Reviewer details (only if reviewed) |
| `rejection_reason` | string/null | Reason for rejection (only if rejected) |
| `created_at` | datetime | When application was created |
| `updated_at` | datetime | Last update timestamp |

---

## 🎨 Frontend Integration

### React Example

```jsx
import { useEffect, useState } from 'react';
import axios from 'axios';

function MyApplications() {
    const [data, setData] = useState(null);
    const [loading, setLoading] = useState(true);
    const [activeTab, setActiveTab] = useState('all');

    useEffect(() => {
        const fetchApplications = async () => {
            try {
                const response = await axios.get('/api/applications/my-applications', {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                });
                setData(response.data.data);
            } catch (error) {
                console.error('Error:', error);
            } finally {
                setLoading(false);
            }
        };

        fetchApplications();
    }, []);

    if (loading) return <div>Loading...</div>;
    if (!data) return <div>No data</div>;

    const { summary, applications } = data;

    return (
        <div className="applications-page">
            {/* Summary Stats */}
            <div className="stats-bar">
                <div className="stat">
                    <span className="label">Total</span>
                    <span className="value">{summary.total}</span>
                </div>
                <div className="stat pending">
                    <span className="label">Pending</span>
                    <span className="value">
                        {summary.submitted + summary.under_review}
                    </span>
                </div>
                <div className="stat success">
                    <span className="label">Accepted</span>
                    <span className="value">{summary.accepted}</span>
                </div>
                <div className="stat draft">
                    <span className="label">Drafts</span>
                    <span className="value">{summary.draft}</span>
                </div>
            </div>

            {/* Tabs */}
            <div className="tabs">
                <button 
                    className={activeTab === 'all' ? 'active' : ''}
                    onClick={() => setActiveTab('all')}
                >
                    All ({summary.total})
                </button>
                <button 
                    className={activeTab === 'draft' ? 'active' : ''}
                    onClick={() => setActiveTab('draft')}
                >
                    Drafts ({summary.draft})
                </button>
                <button 
                    className={activeTab === 'submitted' ? 'active' : ''}
                    onClick={() => setActiveTab('submitted')}
                >
                    Submitted ({summary.submitted})
                </button>
                <button 
                    className={activeTab === 'under_review' ? 'active' : ''}
                    onClick={() => setActiveTab('under_review')}
                >
                    Under Review ({summary.under_review})
                </button>
                <button 
                    className={activeTab === 'shortlisted' ? 'active' : ''}
                    onClick={() => setActiveTab('shortlisted')}
                >
                    Shortlisted ({summary.shortlisted})
                </button>
                <button 
                    className={activeTab === 'accepted' ? 'active' : ''}
                    onClick={() => setActiveTab('accepted')}
                >
                    Accepted ({summary.accepted})
                </button>
                {summary.rejected > 0 && (
                    <button 
                        className={activeTab === 'rejected' ? 'active' : ''}
                        onClick={() => setActiveTab('rejected')}
                    >
                        Rejected ({summary.rejected})
                    </button>
                )}
            </div>

            {/* Applications List */}
            <div className="applications-list">
                {activeTab === 'all' ? (
                    <>
                        {Object.entries(applications).map(([status, apps]) => (
                            apps.length > 0 && (
                                <ApplicationSection 
                                    key={status}
                                    status={status}
                                    applications={apps}
                                />
                            )
                        ))}
                    </>
                ) : (
                    <ApplicationSection 
                        status={activeTab}
                        applications={applications[activeTab]}
                    />
                )}
            </div>
        </div>
    );
}

function ApplicationSection({ status, applications }) {
    return (
        <div className="application-section">
            <h3 className={`status-header ${status}`}>
                {status.replace('_', ' ').toUpperCase()} ({applications.length})
            </h3>
            <div className="cards">
                {applications.map(app => (
                    <ApplicationCard key={app.id} application={app} />
                ))}
            </div>
        </div>
    );
}

function ApplicationCard({ application }) {
    return (
        <div className="application-card">
            <div className="card-header">
                <img src={application.university.logo} alt={application.university.name} />
                <div>
                    <h4>{application.university.name}</h4>
                    <p>{application.interest.name}</p>
                </div>
                <span className={`status-badge ${application.status}`}>
                    {application.status}
                </span>
            </div>
            <div className="card-body">
                <p className="app-number">{application.application_number}</p>
                <p className="city">📍 {application.university.city}</p>
                {application.submitted_at && (
                    <p className="date">
                        Submitted: {new Date(application.submitted_at).toLocaleDateString()}
                    </p>
                )}
            </div>
            <div className="card-footer">
                <button onClick={() => viewDetails(application.id)}>
                    View Details
                </button>
            </div>
        </div>
    );
}
```

### Flutter/Dart Example

```dart
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

class MyApplicationsScreen extends StatefulWidget {
  @override
  _MyApplicationsScreenState createState() => _MyApplicationsScreenState();
}

class _MyApplicationsScreenState extends State<MyApplicationsScreen> {
  Map<String, dynamic>? data;
  bool isLoading = true;
  String activeStatus = 'all';

  @override
  void initState() {
    super.initState();
    fetchApplications();
  }

  Future<void> fetchApplications() async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/api/applications/my-applications'),
        headers: {
          'Authorization': 'Bearer $token',
          'Accept': 'application/json',
        },
      );

      if (response.statusCode == 200) {
        final result = json.decode(response.body);
        setState(() {
          data = result['data'];
          isLoading = false;
        });
      }
    } catch (e) {
      print('Error: $e');
      setState(() {
        isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    if (isLoading) {
      return Center(child: CircularProgressIndicator());
    }

    if (data == null) {
      return Center(child: Text('No applications found'));
    }

    final summary = data!['summary'];
    final applications = data!['applications'];

    return Scaffold(
      appBar: AppBar(
        title: Text('My Applications'),
      ),
      body: Column(
        children: [
          // Summary Stats
          Container(
            padding: EdgeInsets.all(16),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceAround,
              children: [
                StatCard('Total', summary['total'], Colors.blue),
                StatCard('Pending', 
                    summary['submitted'] + summary['under_review'],
                    Colors.orange),
                StatCard('Accepted', summary['accepted'], Colors.green),
                StatCard('Drafts', summary['draft'], Colors.grey),
              ],
            ),
          ),
          
          // Status Tabs
          SingleChildScrollView(
            scrollDirection: Axis.horizontal,
            child: Row(
              children: [
                StatusTab('All', summary['total'], 'all'),
                StatusTab('Draft', summary['draft'], 'draft'),
                StatusTab('Submitted', summary['submitted'], 'submitted'),
                StatusTab('Under Review', summary['under_review'], 'under_review'),
                StatusTab('Shortlisted', summary['shortlisted'], 'shortlisted'),
                StatusTab('Accepted', summary['accepted'], 'accepted'),
                if (summary['rejected'] > 0)
                  StatusTab('Rejected', summary['rejected'], 'rejected'),
              ],
            ),
          ),
          
          // Applications List
          Expanded(
            child: activeStatus == 'all'
                ? AllApplicationsList(applications)
                : StatusApplicationsList(
                    applications[activeStatus] as List,
                    activeStatus,
                  ),
          ),
        ],
      ),
    );
  }

  Widget StatCard(String label, int value, Color color) {
    return Column(
      children: [
        Text(
          value.toString(),
          style: TextStyle(
            fontSize: 24,
            fontWeight: FontWeight.bold,
            color: color,
          ),
        ),
        Text(label, style: TextStyle(fontSize: 12)),
      ],
    );
  }

  Widget StatusTab(String label, int count, String status) {
    final isActive = activeStatus == status;
    return GestureDetector(
      onTap: () {
        setState(() {
          activeStatus = status;
        });
      },
      child: Container(
        padding: EdgeInsets.symmetric(horizontal: 16, vertical: 8),
        margin: EdgeInsets.symmetric(horizontal: 4),
        decoration: BoxDecoration(
          color: isActive ? Colors.blue : Colors.grey[200],
          borderRadius: BorderRadius.circular(20),
        ),
        child: Text(
          '$label ($count)',
          style: TextStyle(
            color: isActive ? Colors.white : Colors.black,
            fontWeight: isActive ? FontWeight.bold : FontWeight.normal,
          ),
        ),
      ),
    );
  }
}
```

---

## 💡 Usage Tips

### Display Applications by Status Sections

```javascript
// Show all applications grouped by status
const renderApplicationsByStatus = () => {
    return Object.entries(applications).map(([status, apps]) => {
        if (apps.length === 0) return null;
        
        return (
            <div key={status} className="status-section">
                <h2 className={`status-header ${status}`}>
                    {getStatusLabel(status)} ({apps.length})
                </h2>
                <div className="applications-grid">
                    {apps.map(app => (
                        <ApplicationCard key={app.id} data={app} />
                    ))}
                </div>
            </div>
        );
    });
};
```

### Filter by Status with Tabs

```javascript
// Create tabs for each status with counts
const renderStatusTabs = () => {
    const statuses = [
        { key: 'all', label: 'All', count: summary.total },
        { key: 'draft', label: 'Drafts', count: summary.draft },
        { key: 'submitted', label: 'Submitted', count: summary.submitted },
        { key: 'under_review', label: 'Under Review', count: summary.under_review },
        { key: 'shortlisted', label: 'Shortlisted', count: summary.shortlisted },
        { key: 'accepted', label: 'Accepted', count: summary.accepted },
        { key: 'rejected', label: 'Rejected', count: summary.rejected },
    ];

    return statuses.map(status => (
        <button
            key={status.key}
            className={activeTab === status.key ? 'active' : ''}
            onClick={() => setActiveTab(status.key)}
        >
            {status.label} {status.count > 0 && `(${status.count})`}
        </button>
    ));
};
```

### Show Status Badges with Colors

```javascript
const getStatusColor = (status) => {
    const colors = {
        draft: '#9E9E9E',
        submitted: '#FF9800',
        under_review: '#2196F3',
        shortlisted: '#9C27B0',
        accepted: '#4CAF50',
        rejected: '#F44336',
        withdrawn: '#757575',
    };
    return colors[status] || '#000';
};
```

---

## 🎨 CSS Styling Example

```css
.status-header {
    padding: 12px 16px;
    border-radius: 8px;
    font-weight: bold;
    margin: 16px 0 8px;
}

.status-header.draft {
    background: #F5F5F5;
    color: #616161;
}

.status-header.submitted {
    background: #FFF3E0;
    color: #E65100;
}

.status-header.under_review {
    background: #E3F2FD;
    color: #0D47A1;
}

.status-header.shortlisted {
    background: #F3E5F5;
    color: #4A148C;
}

.status-header.accepted {
    background: #E8F5E9;
    color: #1B5E20;
}

.status-header.rejected {
    background: #FFEBEE;
    color: #B71C1C;
}

.status-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.status-badge.draft {
    background: #E0E0E0;
    color: #424242;
}

.status-badge.submitted {
    background: #FFE0B2;
    color: #E65100;
}

.status-badge.under_review {
    background: #BBDEFB;
    color: #0D47A1;
}

.status-badge.shortlisted {
    background: #E1BEE7;
    color: #4A148C;
}

.status-badge.accepted {
    background: #C8E6C9;
    color: #1B5E20;
}

.status-badge.rejected {
    background: #FFCDD2;
    color: #B71C1C;
}
```

---

## 📱 Mobile UI Recommendations

### Swipeable Status Tabs
```javascript
import { Swiper, SwiperSlide } from 'swiper/react';

<Swiper spaceBetween={8} slidesPerView={2.5}>
    {statuses.map(status => (
        <SwiperSlide key={status.key}>
            <StatusTab {...status} />
        </SwiperSlide>
    ))}
</Swiper>
```

### Collapsible Status Sections
```javascript
const [expandedSections, setExpandedSections] = useState(['accepted', 'shortlisted']);

const toggleSection = (status) => {
    setExpandedSections(prev => 
        prev.includes(status)
            ? prev.filter(s => s !== status)
            : [...prev, status]
    );
};
```

---

## ⚡ Performance Benefits

### Old (Paginated)
- ❌ Multiple API calls for different pages
- ❌ Complex state management for pagination
- ❌ Difficult to show status-wise counts
- ❌ Poor UX for browsing different statuses

### New (Status-Wise Grouped)
- ✅ Single API call gets everything
- ✅ Easy to display by status sections
- ✅ Built-in summary counts
- ✅ Better UX with status-based navigation
- ✅ Faster status filtering (client-side)
- ✅ Perfect for mobile apps with tabs/segments

---

## 🔄 Migration Guide

### If you were using the old paginated version:

**Before:**
```javascript
const [currentPage, setCurrentPage] = useState(1);
const [applications, setApplications] = useState([]);

// Fetch with pagination
fetch(`/api/applications/my-applications?page=${currentPage}`)
```

**After:**
```javascript
const [applications, setApplications] = useState({});
const [summary, setSummary] = useState({});

// Fetch all grouped
fetch('/api/applications/my-applications')
    .then(res => res.json())
    .then(data => {
        setApplications(data.data.applications);
        setSummary(data.data.summary);
    });
```

---

## ✅ Summary of Changes

| Feature | Old | New |
|---------|-----|-----|
| **Data Format** | Paginated list | Status-wise grouped |
| **API Calls** | Multiple (for pages) | Single call |
| **Summary Stats** | Manual calculation | Built-in |
| **Status Filtering** | Server-side | Client-side |
| **Performance** | Multiple requests | One request |
| **Mobile Friendly** | Pagination UI | Tabs/Segments |
| **Use Case** | Large datasets | Perfect for dashboards |

---

## 📞 Need Help?

- **Full API Docs**: [APPLICATION_API_EXAMPLES.json](./APPLICATION_API_EXAMPLES.json)
- **Dashboard API**: [DASHBOARD_API_DOCUMENTATION.md](./DASHBOARD_API_DOCUMENTATION.md)

---

**Updated**: November 3, 2025  
**Version**: 2.0.0  
**Status**: ✅ **Ready to Use**
















