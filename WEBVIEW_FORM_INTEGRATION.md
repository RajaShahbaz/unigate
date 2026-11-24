# WebView Form Integration Guide

## Overview

The multi-step application form is now available as an HTML view that can be embedded in a Flutter WebView. This provides a complete, mobile-optimized form experience with:

✅ **Pre-filled user data**  
✅ **Multi-step navigation**  
✅ **Auto-save on each step**  
✅ **File upload support**  
✅ **Responsive mobile design**  
✅ **Real-time validation**  

---

## API Endpoint

### Get HTML Form View

**Endpoint:** `GET /api/multi-step-application/form-view?university_id={id}`

**Authentication:** Required (Bearer Token)

**Query Parameters:**
- `university_id` (required): The ID of the university

**Returns:** HTML page ready for WebView

---

## Flutter Integration

### 1. Add WebView Package

Add to your `pubspec.yaml`:

```yaml
dependencies:
  webview_flutter: ^4.4.2
```

### 2. Create WebView Screen

```dart
import 'package:flutter/material.dart';
import 'package:webview_flutter/webview_flutter.dart';

class ApplicationFormWebView extends StatefulWidget {
  final int universityId;
  final String authToken;

  const ApplicationFormWebView({
    Key? key,
    required this.universityId,
    required this.authToken,
  }) : super(key: key);

  @override
  State<ApplicationFormWebView> createState() => _ApplicationFormWebViewState();
}

class _ApplicationFormWebViewState extends State<ApplicationFormWebView> {
  late final WebViewController _controller;
  bool isLoading = true;

  @override
  void initState() {
    super.initState();
    _initializeWebView();
  }

  void _initializeWebView() {
    _controller = WebViewController()
      ..setJavaScriptMode(JavaScriptMode.unrestricted)
      ..setBackgroundColor(const Color(0x00000000))
      ..setNavigationDelegate(
        NavigationDelegate(
          onPageStarted: (String url) {
            setState(() {
              isLoading = true;
            });
          },
          onPageFinished: (String url) {
            setState(() {
              isLoading = false;
            });
            
            // Handle navigation
            if (url.contains('application-submitted')) {
              _handleApplicationSubmitted();
            }
          },
          onWebResourceError: (WebResourceError error) {
            print('WebView error: ${error.description}');
            _showError('Failed to load form. Please try again.');
          },
        ),
      )
      ..addJavaScriptChannel(
        'FlutterChannel',
        onMessageReceived: (JavaScriptMessage message) {
          if (message.message == 'application_submitted') {
            _handleApplicationSubmitted();
          }
        },
      )
      ..loadRequest(
        Uri.parse(
          'https://your-domain.com/api/multi-step-application/form-view?university_id=${widget.universityId}',
        ),
        headers: {
          'Authorization': 'Bearer ${widget.authToken}',
          'Accept': 'text/html',
        },
      );
  }

  void _handleApplicationSubmitted() {
    // Show success message
    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(
        content: Text('Application submitted successfully!'),
        backgroundColor: Colors.green,
      ),
    );

    // Navigate back or to applications list
    Navigator.of(context).pop(true); // Return true to indicate submission
  }

  void _showError(String message) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(message),
        backgroundColor: Colors.red,
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('University Application'),
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh),
            onPressed: () {
              _controller.reload();
            },
          ),
        ],
      ),
      body: Stack(
        children: [
          WebViewWidget(controller: _controller),
          if (isLoading)
            const Center(
              child: CircularProgressIndicator(),
            ),
        ],
      ),
    );
  }
}
```

### 3. Usage Example

```dart
// Navigate to the form
Navigator.push(
  context,
  MaterialPageRoute(
    builder: (context) => ApplicationFormWebView(
      universityId: 12,
      authToken: authToken, // Your authentication token
    ),
  ),
).then((submitted) {
  if (submitted == true) {
    // Handle successful submission
    // Refresh applications list, show confirmation, etc.
  }
});
```

---

## Features

### ✅ Auto-Save
The form automatically saves each step when the user clicks "Next". This ensures no data is lost if the app is closed.

### ✅ Pre-filled Data
User data from their profile is automatically pre-filled:
- First Name, Last Name
- Email, Phone
- Gender, Date of Birth
- Address information

If a draft application exists, all previously saved data is loaded.

### ✅ Multi-Step Navigation
Users can:
- Move forward with "Next" button
- Go back with "Previous" button
- See progress indicator at the top
- Submit on the final step

### ✅ Validation
- Required fields are validated before proceeding
- Visual feedback for missing fields
- Error messages shown for validation issues

### ✅ File Upload
Supports file uploads for:
- Photo/Profile Picture
- ID Documents
- Transcripts
- Recommendation Letters
- Any custom file fields

### ✅ Responsive Design
- Optimized for mobile screens
- Touch-friendly interface
- Smooth animations
- Native-like experience

---

## Advanced Integration

### 1. Handle Deep Links

```dart
@override
void initState() {
  super.initState();
  _controller = WebViewController()
    ..setNavigationDelegate(
      NavigationDelegate(
        onNavigationRequest: (NavigationRequest request) {
          // Handle app:// URLs for deep linking
          if (request.url.startsWith('app://')) {
            final action = request.url.replaceFirst('app://', '');
            _handleDeepLink(action);
            return NavigationDecision.prevent;
          }
          return NavigationDecision.navigate;
        },
      ),
    );
}

void _handleDeepLink(String action) {
  switch (action) {
    case 'application-submitted':
      _handleApplicationSubmitted();
      break;
    case 'go-back':
      Navigator.of(context).pop();
      break;
    // Add more actions as needed
  }
}
```

### 2. Inject JavaScript

```dart
// Inject custom JavaScript if needed
_controller.runJavaScript('''
  // Custom JS code
  console.log('Flutter app loaded');
''');
```

### 3. Handle Permissions (File Upload)

For Android (in `AndroidManifest.xml`):
```xml
<uses-permission android:name="android.permission.READ_EXTERNAL_STORAGE"/>
<uses-permission android:name="android.permission.CAMERA"/>
```

For iOS (in `Info.plist`):
```xml
<key>NSPhotoLibraryUsageDescription</key>
<string>We need access to your photos to upload documents</string>
<key>NSCameraUsageDescription</key>
<string>We need access to your camera to take photos</string>
```

### 4. Loading State

```dart
Widget build(BuildContext context) {
  return Scaffold(
    body: Stack(
      children: [
        WebViewWidget(controller: _controller),
        if (isLoading)
          Container(
            color: Colors.white,
            child: const Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  CircularProgressIndicator(),
                  SizedBox(height: 16),
                  Text('Loading application form...'),
                ],
              ),
            ),
          ),
      ],
    ),
  );
}
```

### 5. Error Handling

```dart
..setNavigationDelegate(
  NavigationDelegate(
    onWebResourceError: (WebResourceError error) {
      print('WebView error: ${error.description}');
      
      showDialog(
        context: context,
        builder: (context) => AlertDialog(
          title: const Text('Error'),
          content: Text(
            'Failed to load the application form.\n\n'
            'Error: ${error.description}',
          ),
          actions: [
            TextButton(
              onPressed: () {
                Navigator.pop(context);
                _controller.reload();
              },
              child: const Text('Retry'),
            ),
            TextButton(
              onPressed: () {
                Navigator.pop(context);
                Navigator.pop(context);
              },
              child: const Text('Go Back'),
            ),
          ],
        ),
      );
    },
  ),
)
```

---

## Communication Between WebView and Flutter

### From WebView to Flutter

**Method 1: JavaScript Channel (Recommended)**
```dart
// In Flutter
_controller.addJavaScriptChannel(
  'FlutterChannel',
  onMessageReceived: (JavaScriptMessage message) {
    print('Message from WebView: ${message.message}');
    // Handle the message
  },
);

// In WebView (JavaScript)
window.FlutterChannel.postMessage('your_message');
```

**Method 2: URL Navigation**
```dart
// In Flutter
if (url.contains('app://action')) {
  // Handle action
}

// In WebView (JavaScript)
window.location.href = 'app://action';
```

### From Flutter to WebView

```dart
// Execute JavaScript in WebView
_controller.runJavaScript('''
  // Your JavaScript code
  alert('Message from Flutter!');
''');
```

---

## Offline Support

### Cache Pages
```dart
_controller.setWebViewCookie(
  const WebViewCookie(
    name: 'session',
    value: 'your-session-id',
    domain: 'your-domain.com',
  ),
);
```

### Handle No Internet
```dart
import 'package:connectivity_plus/connectivity_plus.dart';

// Check connectivity
final connectivityResult = await Connectivity().checkConnectivity();
if (connectivityResult == ConnectivityResult.none) {
  _showError('No internet connection. Please check your connection and try again.');
  return;
}
```

---

## Testing

### Test in Debug Mode

```dart
// Enable debugging in WebView
_controller.setJavaScriptMode(JavaScriptMode.unrestricted);
_controller.enableZoom(true); // For debugging only

// Monitor console logs
_controller.runJavaScript('''
  console.log = function(message) {
    window.FlutterChannel.postMessage('LOG: ' + message);
  };
''');
```

### Test URL

```dart
// Use test URL in development
final baseUrl = kDebugMode 
  ? 'http://10.0.2.2:8000/api' // Android emulator
  : 'https://your-domain.com/api';

_controller.loadRequest(
  Uri.parse('$baseUrl/multi-step-application/form-view?university_id=$universityId'),
);
```

---

## Performance Optimization

### 1. Enable Caching
```dart
_controller.setBackgroundColor(Colors.white);
```

### 2. Preload Form
```dart
// Preload the form before showing it
await _controller.loadRequest(url);
await Future.delayed(const Duration(milliseconds: 500));
// Then show the WebView
```

### 3. Memory Management
```dart
@override
void dispose() {
  // Clean up when done
  super.dispose();
}
```

---

## Security Considerations

### 1. HTTPS Only
Always use HTTPS in production:
```dart
final url = 'https://your-domain.com/api/...';
```

### 2. Token Security
Store auth tokens securely using `flutter_secure_storage`:
```dart
import 'package:flutter_secure_storage/flutter_secure_storage.dart';

final storage = FlutterSecureStorage();
final token = await storage.read(key: 'auth_token');
```

### 3. Validate URLs
```dart
if (!request.url.startsWith('https://your-domain.com')) {
  return NavigationDecision.prevent;
}
```

---

## Troubleshooting

### Issue: WebView not loading
**Solution:** Check internet connection and authentication token

### Issue: Files not uploading
**Solution:** Ensure permissions are granted in manifest files

### Issue: JavaScript not working
**Solution:** Set `JavaScriptMode.unrestricted`

### Issue: Can't go back
**Solution:** Implement back button handling:
```dart
Future<bool> _onWillPop() async {
  if (await _controller.canGoBack()) {
    _controller.goBack();
    return false;
  }
  return true;
}

// In Scaffold
WillPopScope(
  onWillPop: _onWillPop,
  child: Scaffold(...),
)
```

---

## Complete Example with All Features

```dart
import 'package:flutter/material.dart';
import 'package:webview_flutter/webview_flutter.dart';
import 'package:connectivity_plus/connectivity_plus.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';

class AdvancedApplicationFormWebView extends StatefulWidget {
  final int universityId;
  final String universityName;

  const AdvancedApplicationFormWebView({
    Key? key,
    required this.universityId,
    required this.universityName,
  }) : super(key: key);

  @override
  State<AdvancedApplicationFormWebView> createState() => _AdvancedApplicationFormWebViewState();
}

class _AdvancedApplicationFormWebViewState extends State<AdvancedApplicationFormWebView> {
  late final WebViewController _controller;
  bool isLoading = true;
  final storage = const FlutterSecureStorage();
  String? authToken;

  @override
  void initState() {
    super.initState();
    _loadTokenAndInitialize();
  }

  Future<void> _loadTokenAndInitialize() async {
    // Check connectivity
    final connectivityResult = await Connectivity().checkConnectivity();
    if (connectivityResult == ConnectivityResult.none) {
      _showError('No internet connection');
      return;
    }

    // Load auth token
    authToken = await storage.read(key: 'auth_token');
    if (authToken == null) {
      _showError('Authentication required');
      Navigator.of(context).pop();
      return;
    }

    _initializeWebView();
  }

  void _initializeWebView() {
    _controller = WebViewController()
      ..setJavaScriptMode(JavaScriptMode.unrestricted)
      ..setBackgroundColor(Colors.white)
      ..setNavigationDelegate(
        NavigationDelegate(
          onPageStarted: (String url) {
            setState(() => isLoading = true);
          },
          onPageFinished: (String url) {
            setState(() => isLoading = false);
            if (url.contains('application-submitted')) {
              _handleApplicationSubmitted();
            }
          },
          onWebResourceError: (WebResourceError error) {
            _handleError(error);
          },
          onNavigationRequest: (NavigationRequest request) {
            if (request.url.startsWith('app://')) {
              _handleDeepLink(request.url.replaceFirst('app://', ''));
              return NavigationDecision.prevent;
            }
            return NavigationDecision.navigate;
          },
        ),
      )
      ..addJavaScriptChannel(
        'FlutterChannel',
        onMessageReceived: (JavaScriptMessage message) {
          _handleMessage(message.message);
        },
      )
      ..loadRequest(
        Uri.parse(
          'https://your-domain.com/api/multi-step-application/form-view?university_id=${widget.universityId}',
        ),
        headers: {
          'Authorization': 'Bearer $authToken',
          'Accept': 'text/html',
        },
      );
  }

  void _handleMessage(String message) {
    if (message == 'application_submitted') {
      _handleApplicationSubmitted();
    } else if (message.startsWith('LOG:')) {
      debugPrint(message);
    }
  }

  void _handleDeepLink(String action) {
    switch (action) {
      case 'application-submitted':
        _handleApplicationSubmitted();
        break;
      case 'go-back':
        Navigator.of(context).pop();
        break;
    }
  }

  void _handleApplicationSubmitted() {
    showDialog(
      context: context,
      barrierDismissible: false,
      builder: (context) => AlertDialog(
        icon: const Icon(Icons.check_circle, color: Colors.green, size: 64),
        title: const Text('Success!'),
        content: const Text(
          'Your application has been submitted successfully. '
          'You will receive updates on your application status via email.',
        ),
        actions: [
          TextButton(
            onPressed: () {
              Navigator.of(context).pop(); // Close dialog
              Navigator.of(context).pop(true); // Return to previous screen
            },
            child: const Text('OK'),
          ),
        ],
      ),
    );
  }

  void _handleError(WebResourceError error) {
    debugPrint('WebView error: ${error.description}');
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Error'),
        content: Text('Failed to load form.\n\n${error.description}'),
        actions: [
          TextButton(
            onPressed: () {
              Navigator.pop(context);
              _controller.reload();
            },
            child: const Text('Retry'),
          ),
          TextButton(
            onPressed: () {
              Navigator.pop(context);
              Navigator.pop(context);
            },
            child: const Text('Cancel'),
          ),
        ],
      ),
    );
  }

  void _showError(String message) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(message),
        backgroundColor: Colors.red,
        duration: const Duration(seconds: 4),
      ),
    );
  }

  Future<bool> _onWillPop() async {
    if (await _controller.canGoBack()) {
      _controller.goBack();
      return false;
    }
    
    final shouldPop = await showDialog<bool>(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Leave Application?'),
        content: const Text(
          'Your progress will be saved. Do you want to leave this form?',
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context, false),
            child: const Text('Cancel'),
          ),
          TextButton(
            onPressed: () => Navigator.pop(context, true),
            style: TextButton.styleFrom(foregroundColor: Colors.red),
            child: const Text('Leave'),
          ),
        ],
      ),
    );
    
    return shouldPop ?? false;
  }

  @override
  Widget build(BuildContext context) {
    return WillPopScope(
      onWillPop: _onWillPop,
      child: Scaffold(
        appBar: AppBar(
          title: Text(widget.universityName),
          elevation: 0,
          actions: [
            IconButton(
              icon: const Icon(Icons.refresh),
              onPressed: () => _controller.reload(),
              tooltip: 'Refresh',
            ),
          ],
        ),
        body: Stack(
          children: [
            WebViewWidget(controller: _controller),
            if (isLoading)
              Container(
                color: Colors.white,
                child: const Center(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      CircularProgressIndicator(),
                      SizedBox(height: 24),
                      Text(
                        'Loading application form...',
                        style: TextStyle(
                          fontSize: 16,
                          color: Colors.grey,
                        ),
                      ),
                    ],
                  ),
                ),
              ),
          ],
        ),
      ),
    );
  }
}
```

---

## Summary

The WebView form provides a **complete, production-ready solution** for university applications with:

✅ Seamless integration with Flutter  
✅ Pre-filled user data  
✅ Auto-save functionality  
✅ Multi-step navigation  
✅ File upload support  
✅ Mobile-optimized UI  
✅ Secure authentication  
✅ Error handling  
✅ Offline detection  

Simply use the provided endpoint and integrate the WebView in your Flutter app for a smooth application experience!



