# WebView Form - Quick Reference

## 🚀 Quick Start

### API Endpoint
```
GET /api/multi-step-application/form-view?university_id={id}
Authorization: Bearer {token}
```

### Flutter Code (Minimal)
```dart
import 'package:webview_flutter/webview_flutter.dart';

class ApplicationFormScreen extends StatefulWidget {
  final int universityId;
  final String authToken;

  const ApplicationFormScreen({
    required this.universityId,
    required this.authToken,
  });

  @override
  State<ApplicationFormScreen> createState() => _ApplicationFormScreenState();
}

class _ApplicationFormScreenState extends State<ApplicationFormScreen> {
  late final WebViewController controller;

  @override
  void initState() {
    super.initState();
    controller = WebViewController()
      ..setJavaScriptMode(JavaScriptMode.unrestricted)
      ..loadRequest(
        Uri.parse(
          'https://your-domain.com/api/multi-step-application/form-view?university_id=${widget.universityId}',
        ),
        headers: {
          'Authorization': 'Bearer ${widget.authToken}',
        },
      );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Application Form')),
      body: WebViewWidget(controller: controller),
    );
  }
}
```

---

## 📱 Features

| Feature | Status | Description |
|---------|--------|-------------|
| Pre-filled Data | ✅ | User profile data auto-fills form fields |
| Multi-Step | ✅ | Progress through multiple form steps |
| Auto-Save | ✅ | Saves progress on each step |
| Validation | ✅ | Real-time field validation |
| File Upload | ✅ | Supports document uploads |
| Responsive | ✅ | Mobile-optimized design |
| Offline Draft | ✅ | Resumes from saved draft |
| Custom Forms | ✅ | University-specific forms supported |

---

## 🔧 Configuration

### Add Dependency
```yaml
# pubspec.yaml
dependencies:
  webview_flutter: ^4.4.2
```

### Android Setup
```xml
<!-- AndroidManifest.xml -->
<uses-permission android:name="android.permission.INTERNET"/>
<uses-permission android:name="android.permission.READ_EXTERNAL_STORAGE"/>
```

### iOS Setup
```xml
<!-- Info.plist -->
<key>NSPhotoLibraryUsageDescription</key>
<string>Upload documents for application</string>
```

---

## 💡 Common Use Cases

### 1. Basic Integration
```dart
Navigator.push(
  context,
  MaterialPageRoute(
    builder: (context) => ApplicationFormScreen(
      universityId: 12,
      authToken: userToken,
    ),
  ),
);
```

### 2. Handle Submission
```dart
controller.setNavigationDelegate(
  NavigationDelegate(
    onPageFinished: (url) {
      if (url.contains('application-submitted')) {
        Navigator.pop(context);
        // Show success message
      }
    },
  ),
);
```

### 3. Handle Back Button
```dart
Future<bool> onWillPop() async {
  if (await controller.canGoBack()) {
    controller.goBack();
    return false;
  }
  return true;
}
```

---

## 🎨 Form Structure

### Typical Steps:
1. **Personal Information** - Name, DOB, Gender
2. **Contact Details** - Email, Phone, Address
3. **Academic Background** - Previous education
4. **Program Selection** - Choose program/major
5. **Documents** - Upload required files
6. **Additional Info** - Essays, activities
7. **Review & Submit** - Final review

### Pre-filled Fields:
- First Name
- Last Name
- Email
- Phone
- Gender
- Date of Birth
- Current Address
- Permanent Address

---

## 🔒 Security

### Token Authentication
```dart
headers: {
  'Authorization': 'Bearer $authToken',
  'Accept': 'text/html',
}
```

### Secure Storage
```dart
import 'package:flutter_secure_storage/flutter_secure_storage.dart';

final storage = FlutterSecureStorage();
final token = await storage.read(key: 'auth_token');
```

---

## 🐛 Troubleshooting

| Problem | Solution |
|---------|----------|
| Form not loading | Check auth token and internet |
| Files not uploading | Add storage permissions |
| JavaScript errors | Enable `JavaScriptMode.unrestricted` |
| Can't go back | Implement `WillPopScope` |
| Slow loading | Show loading indicator |

---

## 📊 Flow Diagram

```
User Opens Form
    ↓
Load Form View (with auth token)
    ↓
Auto-fill user data
    ↓
Step 1: Fill & click Next
    ↓ (Auto-saved)
Step 2: Fill & click Next
    ↓ (Auto-saved)
Step 3: Fill & click Next
    ↓ (Auto-saved)
...
    ↓
Final Step: Review & Submit
    ↓
Submit Application
    ↓
Success → Close WebView
```

---

## 🎯 Best Practices

### 1. Loading State
```dart
bool isLoading = true;

Stack(
  children: [
    WebViewWidget(controller: controller),
    if (isLoading) Center(child: CircularProgressIndicator()),
  ],
)
```

### 2. Error Handling
```dart
onWebResourceError: (error) {
  showDialog(
    context: context,
    builder: (context) => AlertDialog(
      title: Text('Error'),
      content: Text(error.description),
      actions: [
        TextButton(
          onPressed: () => controller.reload(),
          child: Text('Retry'),
        ),
      ],
    ),
  );
}
```

### 3. Progress Tracking
```dart
addJavaScriptChannel(
  'FlutterChannel',
  onMessageReceived: (message) {
    print('Progress: ${message.message}');
  },
);
```

---

## 📝 API Details

### Request
```http
GET /api/multi-step-application/form-view?university_id=12 HTTP/1.1
Host: your-domain.com
Authorization: Bearer your-auth-token
Accept: text/html
```

### Response
```html
<!DOCTYPE html>
<html>
  <!-- Complete HTML form with JavaScript -->
</html>
```

---

## 🌟 Benefits Over Native Forms

| Aspect | Native Form | WebView Form |
|--------|-------------|--------------|
| Development Time | High | Low |
| Updates | App update required | Instant server-side |
| Consistency | Per-platform design | Uniform across platforms |
| Custom Forms | Complex to implement | Already supported |
| Maintenance | Multiple codebases | Single codebase |

---

## 📱 Testing

### Test URLs
```dart
// Development
'http://10.0.2.2:8000/api/...' // Android Emulator
'http://localhost:8000/api/...' // iOS Simulator

// Production
'https://your-domain.com/api/...'
```

### Debug Mode
```dart
if (kDebugMode) {
  controller.enableZoom(true);
  // Monitor console logs
}
```

---

## 🔗 Related Endpoints

| Endpoint | Purpose |
|----------|---------|
| `/api/multi-step-application/steps` | Get JSON form config |
| `/api/multi-step-application/form-view` | Get HTML form |
| `/api/multi-step-application/start` | Start new application |
| `/api/multi-step-application/{id}/step/{n}` | Save step |
| `/api/multi-step-application/{id}/submit` | Submit application |

---

## ✅ Checklist

Before going to production:

- [ ] Add webview_flutter dependency
- [ ] Configure Android permissions
- [ ] Configure iOS permissions
- [ ] Test on real devices
- [ ] Handle back button
- [ ] Show loading state
- [ ] Handle errors
- [ ] Test file uploads
- [ ] Test submission flow
- [ ] Secure token storage
- [ ] Test offline behavior
- [ ] Add analytics (optional)

---

## 📚 Additional Resources

- **Full Integration Guide**: `WEBVIEW_FORM_INTEGRATION.md`
- **API Documentation**: `MULTI_STEP_ADMISSION_FORM_API.md`
- **WebView Package**: https://pub.dev/packages/webview_flutter

---

## 💬 Support

For issues or questions:
1. Check `WEBVIEW_FORM_INTEGRATION.md` for detailed guide
2. Review error logs in WebView
3. Test authentication token
4. Verify internet connection

---

**Last Updated**: November 5, 2025  
**Version**: 1.0.0  
**Status**: ✅ Production Ready



