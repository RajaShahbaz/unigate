# WebView Form Debugging Guide

## 🐛 The "Next" Button Issue - How to Debug

The form now has **comprehensive debugging built-in** to help identify why the Next button isn't working.

---

## 🔍 Quick Debug Steps

### Step 1: Test in Browser First

Before testing in Flutter WebView, test in a regular browser:

**URL:**
```
https://your-domain.com/api/multi-step-application/form-view?university_id=12
```

**With Auth Token:**
- Use Postman or browser extension to add Authorization header
- Or manually login first

### Step 2: Enable Debug Panel

Click the **🐛 Debug** button in the bottom-left corner of the form.

This will show a real-time debug log showing:
- When Next is clicked
- Current step number
- Validation results
- Fields being saved
- API responses
- Any errors

### Step 3: Open Browser Console

Press **F12** or **Right-click → Inspect → Console tab**

You'll see detailed logs like:
```
Form initialized
API Base URL: https://your-domain.com/api
Auth Token: Present (length: 180)
University ID: 12
Application ID: 45
====== NEXT BUTTON CLICKED ======
Current step: 1
Required inputs: 5
Validation passed ✓
Saving step 1...
Total fields to save: 5
Added field: first_name = John
Added field: last_name = Doe
...
Response status: 200
✓ Save SUCCESS
Moving to step: 2
```

---

## 🧪 Test Modes

### Test Mode 1: Skip Save (for UI testing)
Add `?skip_save` to the URL to skip API calls and just test UI navigation:

```
https://your-domain.com/api/multi-step-application/form-view?university_id=12&skip_save
```

This will:
- ✅ Skip all API save calls
- ✅ Allow navigation through all steps
- ✅ Show "Test Mode" indicator
- ✅ Useful for testing form layout and validation

### Test Mode 2: Debug Mode
Add `?debug` to enable extra debugging:

```
https://your-domain.com/api/multi-step-application/form-view?university_id=12&debug
```

---

## 🔎 Common Issues & What to Look For

### Issue 1: Auth Token Missing

**Symptoms in Console:**
```
Auth Token: MISSING
✗ ERROR: No auth token
```

**Solution:**
Check that the Bearer token is being passed in the Authorization header when loading the WebView.

**Flutter fix:**
```dart
controller.loadRequest(
  Uri.parse('...'),
  headers: {
    'Authorization': 'Bearer YOUR_TOKEN_HERE',  // ← Make sure this is set
  },
);
```

---

### Issue 2: Application ID Not Created

**Symptoms in Console:**
```
Application ID: Not set yet
No application ID. Trying to create application...
✗ Create FAILED: ...
```

**Possible Causes:**
- Auth token invalid or expired
- User doesn't have permission
- Network issue
- Server error

**Check:**
1. Verify auth token is valid
2. Check server logs for errors
3. Test `/api/multi-step-application/start` endpoint separately

---

### Issue 3: Validation Failing

**Symptoms in Console:**
```
Validation failed. Missing fields: [first_name, last_name]
```

**Symptoms in Debug Panel:**
```
Validation FAILED: first_name, last_name
```

**Solution:**
Fill in all required fields (marked with red asterisk *)

---

### Issue 4: Save API Failing

**Symptoms in Console:**
```
Response status: 422
✗ Save FAILED: Validation failed
✗ first_name: The first name field is required
```

**Possible Causes:**
- Server-side validation failing
- Missing required fields
- Invalid data format

**Check:**
Look at the validation errors in the debug panel or console

---

### Issue 5: Network Error

**Symptoms in Console:**
```
✗ Network ERROR: Failed to fetch
```

**Possible Causes:**
- No internet connection
- CORS issues
- Server is down
- Wrong API URL

**Check:**
1. Test API URL manually
2. Check internet connection
3. Verify CORS settings on server

---

## 📊 Debug Panel Symbols

The debug panel uses symbols to indicate status:

- ✓ = Success
- ✗ = Error
- ⚠️ = Warning
- --- = Section separator

**Example debug output:**
```
[10:30:45] Form initialized
[10:30:45] Total steps: 6
[10:30:45] Auth token: Present
[10:30:50] Next button clicked
[10:30:50] Current step: 1
[10:30:50] Required inputs: 4
[10:30:50] Validation PASSED ✓
[10:30:50] --- Saving Step 1 ---
[10:30:50] ✓ first_name: John
[10:30:50] ✓ last_name: Doe
[10:30:50] ✓ email: john@example.com
[10:30:50] ✓ phone: 1234567890
[10:30:50] Total fields: 4
[10:30:50] URL: /api/multi-step-application/45/step/1
[10:30:51] HTTP Status: 200
[10:30:51] ✓ Save SUCCESS
[10:30:51] Moving to step: 2
```

---

## 🔧 Quick Fixes

### Fix 1: Auth Token Issue

If auth token is missing, modify the controller to pass it explicitly:

```php
// In MultiStepApplicationController.php getFormView() method
$authToken = $request->bearerToken() ?? '';

return view('application.webview-form', compact(
    'university', 'steps', 'isCustom', 'userData', 'application', 'user', 'authToken'
));
```

Then in the view:
```javascript
const authToken = '{{ $authToken }}';
```

### Fix 2: Skip Validation for Testing

Add this URL parameter to skip all save operations:
```
?skip_save
```

This will let you navigate through steps without API calls (UI testing only).

### Fix 3: Check Application Model

The Application model must have these fields fillable to save properly.

Check `app/Models/Application.php`:
```php
protected $fillable = [
    'user_id',
    'university_id',
    'status',
    'first_name',
    'last_name',
    'email',
    'phone',
    // ... all other form fields
];
```

---

## 🧪 Test in Flutter WebView

### Enable Remote Debugging

**Android:**
```dart
// Enable debugging
if (kDebugMode) {
  WebView.debuggingEnabled = true;
}
```

Then connect to Chrome DevTools:
1. Open `chrome://inspect` in Chrome browser
2. Find your WebView
3. Click "Inspect"
4. See all console logs and errors

**iOS:**
1. Enable Web Inspector in Safari
2. Connect device
3. Safari → Develop → [Your Device] → [WebView]
4. See all console logs

---

## 📱 Flutter WebView Debugging

### Monitor JavaScript Messages

```dart
controller.addJavaScriptChannel(
  'FlutterChannel',
  onMessageReceived: (JavaScriptMessage message) {
    print('WebView Log: ${message.message}');
  },
);
```

Then in the HTML, you can send logs to Flutter:
```javascript
window.FlutterChannel.postMessage('Debug: ' + message);
```

### Catch Errors

```dart
controller.setNavigationDelegate(
  NavigationDelegate(
    onWebResourceError: (WebResourceError error) {
      print('WebView Error Code: ${error.errorCode}');
      print('WebView Error Description: ${error.description}');
      print('WebView Error Type: ${error.errorType}');
    },
  ),
);
```

---

## 🎯 Troubleshooting Checklist

When Next button doesn't work, check:

- [ ] **Debug Panel** - Click 🐛 Debug button and see what it says
- [ ] **Browser Console** - Open F12 and check for errors
- [ ] **Auth Token** - Verify token is present in logs
- [ ] **Application ID** - Check if application was created
- [ ] **Required Fields** - Ensure all fields with * are filled
- [ ] **Network Tab** - Check if API calls are being made
- [ ] **Response Status** - Check HTTP status codes (200, 401, 422, 500)
- [ ] **Server Logs** - Check Laravel logs for backend errors

---

## 🚀 Quick Test URLs

### Normal Mode
```
/api/multi-step-application/form-view?university_id=12
```

### With Debug Panel Auto-Open
```
/api/multi-step-application/form-view?university_id=12&debug
```

### Skip Save (UI Test Only)
```
/api/multi-step-application/form-view?university_id=12&skip_save
```

### Both Debug and Skip Save
```
/api/multi-step-application/form-view?university_id=12&debug&skip_save
```

---

## 📋 What Each Log Means

### On Form Load:
```
Form initialized           → Page loaded successfully
API Base URL: ...         → API endpoint configured
Auth Token: Present       → Authentication available
University ID: 12         → Target university set
Application ID: 45        → Draft application exists (or "Not set yet")
```

### On Next Click:
```
Next button clicked       → Button event fired
Current step: 1          → Which step you're on
Required inputs: 4       → How many required fields
Validation PASSED ✓      → All required fields filled
--- Saving Step 1 ---    → Starting save process
✓ first_name: John       → Field being saved
Total fields: 4          → Fields to save
URL: /api/.../step/1     → API endpoint
HTTP Status: 200         → Server response (200 = OK)
✓ Save SUCCESS           → Save completed
Moving to step: 2        → Proceeding to next step
```

### If Something Fails:
```
✗ ERROR: No auth token   → Authentication missing
✗ Create FAILED: ...     → Application creation failed
Validation FAILED: ...   → Required fields missing
✗ Save FAILED: ...       → Save API failed
✗ Network ERROR: ...     → Connection issue
```

---

## 💡 Pro Tips

### Tip 1: Use Skip Save Mode First
```
?skip_save
```
This will tell you if it's a UI/validation issue or an API issue.

- If it works with `?skip_save` → **API/backend issue**
- If it doesn't work with `?skip_save` → **UI/validation issue**

### Tip 2: Check Network Tab
Open F12 → Network tab → Try Next button

You should see:
1. POST request to `/api/multi-step-application/{id}/step/1`
2. Status code (200, 401, 422, etc.)
3. Response body

### Tip 3: Test API Separately
Use Postman to test the save endpoint independently:

```http
POST /api/multi-step-application/123/step/1
Authorization: Bearer {token}
Content-Type: multipart/form-data

first_name=John
last_name=Doe
...
```

---

## 🎬 Expected Behavior

When you click "Next":

1. ✓ Console log: "NEXT BUTTON CLICKED"
2. ✓ Validation runs
3. ✓ If validation passes, shows "Saving..."
4. ✓ API call is made
5. ✓ Success message appears
6. ✓ Form moves to next step

If any step fails, you'll see:
- ❌ Error message in red alert
- ❌ Console error with details
- ❌ Debug panel shows where it failed

---

## 📞 Getting Help

If the issue persists after checking all of the above:

1. **Share the console logs** - Copy everything from browser console
2. **Share debug panel output** - Click 🐛 Debug and copy the log
3. **Share network response** - Screenshot of Network tab showing the failed request
4. **Share server logs** - Check `storage/logs/laravel.log`

---

## ✅ Summary

**What was added:**
- ✓ Comprehensive console logging
- ✓ Visual debug panel (🐛 button)
- ✓ Test mode to skip saves (`?skip_save`)
- ✓ Better error messages
- ✓ Field-by-field validation logging
- ✓ API response logging

**How to debug:**
1. Open the form in browser
2. Press F12 for console
3. Click 🐛 Debug button
4. Click Next and watch the logs
5. Identify where it fails

**Common issues:**
- Missing auth token
- Validation failing
- API endpoint returning errors
- Network connectivity

The debug panel and console logs will tell you exactly what's happening! 🔍



