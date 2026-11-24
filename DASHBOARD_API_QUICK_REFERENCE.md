# Dashboard API - Quick Reference Guide

## Quick Start

### Endpoint
```
GET /api/dashboard
```

### Authentication
```
Authorization: Bearer {access_token}
```

### Basic Usage
```bash
curl -H "Authorization: Bearer YOUR_TOKEN" \
     https://your-domain.com/api/dashboard
```

---

## Response Structure at a Glance

```json
{
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
            "details": [...]
        },
        "improvement_tips": [...],
        "recent_applications": [...]
    }
}
```

---

## Key Features

### 📊 Application Statistics
Track all application states:
- **Total Applications**: All applications created
- **Pending**: Submitted + Under Review
- **Accepted**: Successful applications
- **Rejected**: Unsuccessful applications
- **Draft**: Incomplete applications
- **Shortlisted**: Applications in shortlist
- **Withdrawn**: User withdrawn applications

### 📈 Profile Completion (Weighted)
Six sections with weighted importance:
| Section | Weight | Description |
|---------|--------|-------------|
| Basic Info | 20% | Name, email, mobile, gender, DOB |
| Address | 10% | Current & permanent addresses |
| Photo | 10% | Profile picture |
| Education | 25% | Academic history (most important) |
| Interests | 15% | Field preferences |
| Documents | 20% | ID, transcripts, certificates |

### 💡 Improvement Tips
Personalized, prioritized suggestions:
- **Priority Levels**: high, medium, low, info
- **Action Types**: upload_photo, add_education, add_interests, etc.
- **Icons**: Emoji icons for visual appeal
- Auto-sorted by priority

### 📝 Recent Applications
Last 5 updated applications with:
- Application number
- University & program names
- Current status
- Submission & update dates

---

## Profile Completion Calculation

```
Overall % = Σ (Section % × Section Weight)

Example:
- Basic Info: 100% × 20% = 20.0%
- Address: 100% × 10% = 10.0%
- Photo: 100% × 10% = 10.0%
- Education: 100% × 25% = 25.0%
- Interests: 100% × 15% = 15.0%
- Documents: 66.67% × 20% = 13.3%
────────────────────────────────────
Total Profile Completion = 93.3%
```

---

## Application Status Flow

```
draft
  ↓ (submit)
submitted
  ↓ (review)
under_review
  ↓ (shortlist)     ↓ (withdraw)
shortlisted       withdrawn
  ↓ (decision)
accepted / rejected
```

---

## Common Use Cases

### 1. Display Dashboard Cards
```javascript
// Total Applications Card
<Card>
  <h3>Total Applications</h3>
  <p className="count">{data.application_statistics.total_applications}</p>
</Card>

// Pending Applications Card (highlight if > 0)
<Card className={pending > 0 ? 'has-pending' : ''}>
  <h3>Pending Review</h3>
  <p className="count">{data.application_statistics.pending}</p>
</Card>

// Success Rate Card
<Card>
  <h3>Success Rate</h3>
  <p className="percentage">
    {(accepted / total_applications * 100).toFixed(1)}%
  </p>
</Card>
```

### 2. Profile Completion Widget
```javascript
const ProfileWidget = ({ profileCompletion }) => {
  const { percentage, details } = profileCompletion;
  
  // Color coding
  const getColor = (pct) => {
    if (pct >= 80) return '#4CAF50'; // Green
    if (pct >= 50) return '#FFC107'; // Amber
    return '#F44336'; // Red
  };
  
  return (
    <div>
      <CircularProgress 
        value={percentage} 
        color={getColor(percentage)} 
      />
      <h4>Profile {percentage}% Complete</h4>
      
      {/* Section breakdown */}
      {details.map(section => (
        <SectionBar 
          key={section.section}
          name={section.section}
          percentage={section.percentage}
          completed={section.completed}
          total={section.total}
        />
      ))}
    </div>
  );
};
```

### 3. Smart Tips Display
```javascript
const ImprovementTips = ({ tips }) => {
  const priorityColors = {
    high: 'bg-red-100 text-red-800',
    medium: 'bg-yellow-100 text-yellow-800',
    low: 'bg-blue-100 text-blue-800',
    info: 'bg-gray-100 text-gray-800'
  };
  
  return (
    <div className="tips-list">
      {tips.map((tip, index) => (
        <div 
          key={index}
          className={`tip-card ${priorityColors[tip.priority]}`}
          onClick={() => handleAction(tip.action)}
        >
          <span className="icon">{tip.icon}</span>
          <div>
            <h4>{tip.title}</h4>
            <p>{tip.description}</p>
          </div>
          <span className="badge">{tip.priority}</span>
        </div>
      ))}
    </div>
  );
};
```

### 4. Status Pie Chart
```javascript
import { Pie } from 'react-chartjs-2';

const ApplicationChart = ({ statistics }) => {
  const data = {
    labels: ['Accepted', 'Pending', 'Rejected', 'Draft', 'Shortlisted'],
    datasets: [{
      data: [
        statistics.accepted,
        statistics.pending,
        statistics.rejected,
        statistics.draft,
        statistics.shortlisted
      ],
      backgroundColor: [
        '#4CAF50', // Green - Accepted
        '#FFC107', // Amber - Pending
        '#F44336', // Red - Rejected
        '#9E9E9E', // Grey - Draft
        '#2196F3'  // Blue - Shortlisted
      ]
    }]
  };
  
  return <Pie data={data} />;
};
```

### 5. Recent Applications Timeline
```javascript
const RecentTimeline = ({ applications }) => {
  const getStatusBadge = (status) => {
    const badges = {
      'accepted': { color: 'green', icon: '✓' },
      'rejected': { color: 'red', icon: '✗' },
      'under_review': { color: 'blue', icon: '👁' },
      'shortlisted': { color: 'purple', icon: '⭐' },
      'submitted': { color: 'orange', icon: '📤' },
      'draft': { color: 'grey', icon: '✏️' }
    };
    return badges[status] || badges.draft;
  };
  
  return (
    <div className="timeline">
      {applications.map(app => (
        <div key={app.id} className="timeline-item">
          <div className="date">
            {new Date(app.updated_at).toLocaleDateString()}
          </div>
          <div className="content">
            <span className={`badge ${getStatusBadge(app.status).color}`}>
              {getStatusBadge(app.status).icon} {app.status}
            </span>
            <h4>{app.university_name}</h4>
            <p>{app.program_name}</p>
            <small>{app.application_number}</small>
          </div>
        </div>
      ))}
    </div>
  );
};
```

---

## Tip Action Handlers

```javascript
const handleTipAction = (action) => {
  const actionRoutes = {
    'upload_photo': '/profile/photo',
    'add_education': '/profile/education',
    'add_interests': '/profile/interests',
    'update_address': '/profile/address',
    'upload_documents': '/documents',
    'complete_drafts': '/applications?status=draft',
    'browse_universities': '/universities',
    'complete_profile': '/profile/edit'
  };
  
  if (actionRoutes[action]) {
    navigate(actionRoutes[action]);
  }
};
```

---

## Caching Strategy

### Recommended Cache Duration
```javascript
// Cache for 5 minutes
const CACHE_DURATION = 5 * 60 * 1000;

// Invalidate cache on these actions:
const invalidateDashboardCache = [
  'APPLICATION_SUBMITTED',
  'PROFILE_UPDATED',
  'EDUCATION_ADDED',
  'DOCUMENT_UPLOADED',
  'INTERESTS_UPDATED'
];

// React Query example
const useDashboard = () => {
  return useQuery('dashboard', fetchDashboard, {
    staleTime: CACHE_DURATION,
    cacheTime: CACHE_DURATION * 2,
    refetchOnWindowFocus: true
  });
};
```

---

## Performance Optimization

### Lazy Loading Sections
```javascript
// Load dashboard in stages
useEffect(() => {
  // 1. Load critical stats first
  fetchApplicationStats();
  
  // 2. Then profile completion
  setTimeout(() => fetchProfileCompletion(), 100);
  
  // 3. Finally tips and recent apps
  setTimeout(() => {
    fetchImprovementTips();
    fetchRecentApplications();
  }, 200);
}, []);
```

### Skeleton Loading
```javascript
const DashboardSkeleton = () => (
  <div className="dashboard-skeleton">
    <div className="skeleton-card" />
    <div className="skeleton-card" />
    <div className="skeleton-chart" />
    <div className="skeleton-list" />
  </div>
);

// Usage
{isLoading ? <DashboardSkeleton /> : <Dashboard data={data} />}
```

---

## Error Handling

```javascript
const fetchDashboard = async () => {
  try {
    const response = await api.get('/dashboard');
    return response.data.data;
  } catch (error) {
    if (error.response?.status === 401) {
      // Token expired - redirect to login
      redirectToLogin();
    } else if (error.response?.status === 500) {
      // Server error - show friendly message
      showError('Unable to load dashboard. Please try again.');
    } else {
      // Network error
      showError('Check your internet connection.');
    }
    throw error;
  }
};
```

---

## Mobile UI Recommendations

### Compact Cards Layout
```css
/* Mobile-first responsive grid */
.dashboard-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 1rem;
}

@media (min-width: 768px) {
  .dashboard-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (min-width: 1024px) {
  .dashboard-grid {
    grid-template-columns: repeat(4, 1fr);
  }
}
```

### Swipeable Tips
```javascript
// Use swiper for mobile tips carousel
<Swiper spaceBetween={16} slidesPerView={1.2}>
  {tips.map((tip, index) => (
    <SwiperSlide key={index}>
      <TipCard tip={tip} />
    </SwiperSlide>
  ))}
</Swiper>
```

---

## Testing Checklist

- [ ] Loads successfully with valid token
- [ ] Returns 401 for invalid/missing token
- [ ] Statistics counts are accurate
- [ ] Profile percentage is 0-100
- [ ] Tips are sorted by priority
- [ ] Recent applications limited to 5
- [ ] All required fields present
- [ ] Response time < 1 second
- [ ] Handles users with 0 applications
- [ ] Handles users with 100% profile

---

## Troubleshooting

### Issue: Profile completion always 0%
**Solution**: Check that user has filled basic fields and created education/interest records.

### Issue: No improvement tips showing
**Solution**: User profile is likely 100% complete. This is expected behavior.

### Issue: Slow response time
**Solution**: Add database indexes on `user_id` fields in applications, education, documents tables.

### Issue: Recent applications empty
**Solution**: Normal for new users. Show onboarding message to create first application.

---

## Related Documentation

- Full API Documentation: [DASHBOARD_API_DOCUMENTATION.md](./DASHBOARD_API_DOCUMENTATION.md)
- Postman Collection: [Dashboard_API.postman_collection.json](./Dashboard_API.postman_collection.json)
- Application API: [APPLICATION_API_EXAMPLES.json](./APPLICATION_API_EXAMPLES.json)

---

**Last Updated**: 2025-11-03  
**API Version**: 1.0.0

