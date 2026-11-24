# FAQ System - Quick Start Guide

## 🚀 Overview

The FAQ management system is now fully implemented and ready to use! This guide will help you get started quickly.

## ✅ What's Been Implemented

1. **Database Table**: `app_faqs` table created with migration
2. **Model**: `AppFaq` model with scopes and relationships
3. **Repository Pattern**: Interface and implementation for data access
4. **Public API**: For app users to view FAQs
5. **Admin API**: Complete CRUD operations for FAQ management
6. **Sample Data**: 10 pre-seeded FAQs
7. **Documentation**: Complete API documentation
8. **Postman Collection**: Ready-to-use API testing collection

## 📋 Quick Setup

### Step 1: Database Migration ✓ (Already Done)
The migration has been run and the `app_faqs` table is created.

### Step 2: Seed Sample Data ✓ (Already Done)
10 sample FAQs have been seeded into the database.

### Step 3: Test the APIs

#### Public API (No Authentication)
```bash
# Get all active FAQs
curl http://your-domain.com/api/faqs

# Get single FAQ
curl http://your-domain.com/api/faqs/1
```

#### Admin API (Requires Authentication)
```bash
# Get all FAQs (with authentication)
curl -H "Authorization: Bearer YOUR_TOKEN" \
     http://your-domain.com/api/admin/faqs

# Create new FAQ
curl -X POST \
     -H "Authorization: Bearer YOUR_TOKEN" \
     -H "Content-Type: application/json" \
     -d '{
       "question": "New FAQ Question?",
       "answer": "Detailed answer here...",
       "order": 11,
       "status": 1
     }' \
     http://your-domain.com/api/admin/faqs
```

## 📱 Mobile App Integration

### Flutter Example
```dart
import 'dart:convert';
import 'package:http/http.dart' as http;

class FaqService {
  final String baseUrl = 'https://your-domain.com/api';
  
  Future<List<Faq>> getFaqs() async {
    final response = await http.get(
      Uri.parse('$baseUrl/faqs'),
      headers: {'Accept': 'application/json'},
    );
    
    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      if (!data['error']) {
        return (data['data'] as List)
            .map((faq) => Faq.fromJson(faq))
            .toList();
      }
    }
    throw Exception('Failed to load FAQs');
  }
}

class Faq {
  final int id;
  final String question;
  final String answer;
  final int order;
  final bool status;
  
  Faq({
    required this.id,
    required this.question,
    required this.answer,
    required this.order,
    required this.status,
  });
  
  factory Faq.fromJson(Map<String, dynamic> json) {
    return Faq(
      id: json['id'],
      question: json['question'],
      answer: json['answer'],
      order: json['order'],
      status: json['status'] == 1,
    );
  }
}
```

### React Native Example
```javascript
import axios from 'axios';

const API_BASE_URL = 'https://your-domain.com/api';

export const faqService = {
  // Get all FAQs
  async getAllFaqs() {
    try {
      const response = await axios.get(`${API_BASE_URL}/faqs`, {
        headers: {
          'Accept': 'application/json'
        }
      });
      return response.data;
    } catch (error) {
      console.error('Error fetching FAQs:', error);
      throw error;
    }
  },
  
  // Get single FAQ
  async getFaqById(id) {
    try {
      const response = await axios.get(`${API_BASE_URL}/faqs/${id}`, {
        headers: {
          'Accept': 'application/json'
        }
      });
      return response.data;
    } catch (error) {
      console.error('Error fetching FAQ:', error);
      throw error;
    }
  }
};

// Usage in component
import React, { useEffect, useState } from 'react';
import { View, Text, ScrollView, ActivityIndicator } from 'react-native';
import { faqService } from './services/faqService';

const FaqScreen = () => {
  const [faqs, setFaqs] = useState([]);
  const [loading, setLoading] = useState(true);
  
  useEffect(() => {
    loadFaqs();
  }, []);
  
  const loadFaqs = async () => {
    try {
      const response = await faqService.getAllFaqs();
      if (!response.error) {
        setFaqs(response.data);
      }
    } catch (error) {
      console.error('Error:', error);
    } finally {
      setLoading(false);
    }
  };
  
  if (loading) {
    return <ActivityIndicator size="large" />;
  }
  
  return (
    <ScrollView>
      {faqs.map(faq => (
        <View key={faq.id} style={styles.faqItem}>
          <Text style={styles.question}>{faq.question}</Text>
          <Text style={styles.answer}>{faq.answer}</Text>
        </View>
      ))}
    </ScrollView>
  );
};
```

## 🔧 Admin Panel Integration

### Create FAQ Form (React Example)
```jsx
import React, { useState } from 'react';
import axios from 'axios';

const CreateFaqForm = ({ token, onSuccess }) => {
  const [formData, setFormData] = useState({
    question: '',
    answer: '',
    order: 0,
    status: 1
  });
  const [loading, setLoading] = useState(false);
  
  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    
    try {
      const response = await axios.post(
        'https://your-domain.com/api/admin/faqs',
        formData,
        {
          headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          }
        }
      );
      
      if (!response.data.error) {
        alert('FAQ created successfully!');
        onSuccess();
        // Reset form
        setFormData({ question: '', answer: '', order: 0, status: 1 });
      }
    } catch (error) {
      console.error('Error creating FAQ:', error);
      alert('Error creating FAQ');
    } finally {
      setLoading(false);
    }
  };
  
  return (
    <form onSubmit={handleSubmit}>
      <div>
        <label>Question:</label>
        <input
          type="text"
          value={formData.question}
          onChange={(e) => setFormData({...formData, question: e.target.value})}
          required
        />
      </div>
      
      <div>
        <label>Answer:</label>
        <textarea
          value={formData.answer}
          onChange={(e) => setFormData({...formData, answer: e.target.value})}
          required
        />
      </div>
      
      <div>
        <label>Order:</label>
        <input
          type="number"
          value={formData.order}
          onChange={(e) => setFormData({...formData, order: parseInt(e.target.value)})}
        />
      </div>
      
      <div>
        <label>Status:</label>
        <select
          value={formData.status}
          onChange={(e) => setFormData({...formData, status: parseInt(e.target.value)})}
        >
          <option value={1}>Active</option>
          <option value={0}>Inactive</option>
        </select>
      </div>
      
      <button type="submit" disabled={loading}>
        {loading ? 'Creating...' : 'Create FAQ'}
      </button>
    </form>
  );
};
```

## 🧪 Testing with Postman

1. **Import Collection**: Import `FAQ_API.postman_collection.json` into Postman

2. **Set Environment Variables**:
   - `base_url`: http://your-domain.com/api
   - `admin_token`: Your admin authentication token

3. **Test Sequence**:
   - ✅ Get All Active FAQs (Public)
   - ✅ Get Single FAQ (Public)
   - ✅ Login as Admin
   - ✅ Get All FAQs (Admin)
   - ✅ Get Statistics
   - ✅ Create FAQ
   - ✅ Update FAQ
   - ✅ Toggle Status
   - ✅ Reorder FAQs
   - ✅ Delete FAQ

## 📊 Sample Data Included

The seeder has created 10 FAQs covering:

1. How to apply for university admission
2. Required documents
3. Multiple university applications
4. Application timeline
5. Payment methods
6. Editing applications
7. Tracking application status
8. Scholarship availability
9. Password recovery
10. Support contact information

## 🎯 API Endpoints Summary

### Public Endpoints (No Auth)
- `GET /api/faqs` - Get all active FAQs
- `GET /api/faqs/{id}` - Get single FAQ

### Admin Endpoints (Auth Required)
- `GET /api/admin/faqs` - List all FAQs (with filters)
- `POST /api/admin/faqs` - Create FAQ
- `GET /api/admin/faqs/statistics` - Get statistics
- `GET /api/admin/faqs/{id}` - Get single FAQ
- `PUT /api/admin/faqs/{id}` - Update FAQ
- `DELETE /api/admin/faqs/{id}` - Delete FAQ
- `POST /api/admin/faqs/{id}/toggle-status` - Toggle status
- `POST /api/admin/faqs/reorder` - Reorder FAQs

## 📚 Files Created

1. **Migration**: `database/migrations/2025_11_05_151357_create_app_faqs_table.php`
2. **Model**: `app/Models/AppFaq.php`
3. **Repository Interface**: `app/Repositories/AppFaq/AppFaqInterface.php`
4. **Repository**: `app/Repositories/AppFaq/AppFaqRepository.php`
5. **Public Controller**: `app/Http/Controllers/Api/AppFaqController.php`
6. **Admin Controller**: `app/Http/Controllers/Api/Admin/AppFaqAdminController.php`
7. **Seeder**: `database/seeders/AppFaqSeeder.php`
8. **Documentation**: `FAQ_API_DOCUMENTATION.md`
9. **Postman Collection**: `FAQ_API.postman_collection.json`
10. **Quick Start**: `FAQ_QUICK_START.md` (this file)

## 🔄 Routes Registered

Routes have been added to `routes/api.php`:
- Public routes at the top level
- Admin routes under `/api/admin/faqs`

## 🎨 Admin Panel UI Suggestions

### FAQ List Page
- Display FAQs in a table or card layout
- Search functionality
- Filter by status (Active/Inactive)
- Sortable by order
- Quick toggle status button
- Edit and delete actions
- Drag-and-drop to reorder

### FAQ Form
- Question input (text)
- Answer input (rich text editor recommended)
- Order input (number)
- Status toggle (Active/Inactive)
- Save and Cancel buttons

### Statistics Dashboard
- Total FAQs count
- Active FAQs count
- Inactive FAQs count
- Recently added FAQs

## 🚨 Important Notes

1. **Authentication**: Admin endpoints require Bearer token authentication
2. **Status Field**: 1 = Active, 0 = Inactive
3. **Order Field**: Lower numbers appear first
4. **Public API**: Only shows active FAQs (status = 1)
5. **Soft Delete**: Not implemented - deletes are permanent

## 🔐 Security

- Admin endpoints are protected with `auth:sanctum` middleware
- All inputs are validated
- SQL injection protection via Eloquent ORM
- XSS protection via proper encoding

## 📞 Support

For issues or questions:
- Check `FAQ_API_DOCUMENTATION.md` for detailed API specs
- Use the Postman collection for testing
- Review code comments in controller files

## ✨ Next Steps

1. **Mobile App**: Integrate FAQ screen in your app
2. **Admin Panel**: Add FAQ management page
3. **Customization**: Modify seeder data to match your needs
4. **Rich Text**: Consider adding rich text support for answers
5. **Categories**: Add FAQ categories if needed
6. **Analytics**: Track which FAQs are viewed most

---

**System Status**: ✅ Fully Operational

The FAQ management system is complete and ready for production use!



