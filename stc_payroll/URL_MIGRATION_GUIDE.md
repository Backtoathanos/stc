# URL Migration Guide - Dynamic URLs for Local and Server

This guide explains how the application now uses dynamic URLs that work in both local and server environments.

## What Was Changed

### 1. AppServiceProvider (`app/Providers/AppServiceProvider.php`)
- Automatically detects the base URL from `APP_URL` environment variable
- Falls back to auto-detection if `APP_URL` is not set
- Shares `$baseUrl` variable with all views
- Stores base path in config for helper functions

### 2. Helper Functions (`app/helpers.php`)
- `base_url($path)` - Generates URLs with subdirectory support
- `asset_url($path)` - Generates asset URLs with subdirectory support

### 3. Layout Files
- **`layouts/head.blade.php`**: All asset URLs now use `{{ asset() }}` helper
- **`layouts/foot.blade.php`**: All script URLs now use `{{ asset() }}` helper
- **`layouts/nav.blade.php`**: Navigation links use `{{ url() }}` helper
- **`layouts/header.blade.php`**: Breadcrumb links use `{{ url() }}` helper
- **`layouts/aside.blade.php`**: Sidebar links use dynamic `$baseUrl` variable

### 4. JavaScript Global Variables
Added to `layouts/head.blade.php`:
```javascript
window.appBaseUrl = '{{ url("/") }}';
window.appBasePath = '{{ config("app.base_path", "/") }}';
```

## How to Update Remaining JavaScript URLs

### Pattern to Replace:
**Old (Hardcoded):**
```javascript
url: '/stc/stc_payroll/some/route'
```

**New (Dynamic):**
```javascript
url: window.appBaseUrl + '/some/route'
```

### Examples:

1. **DataTables AJAX:**
```javascript
// OLD
ajax: {
    url: '/stc/stc_payroll/master/sites/list',
}

// NEW
ajax: {
    url: window.appBaseUrl + '/master/sites/list',
}
```

2. **jQuery AJAX:**
```javascript
// OLD
$.ajax({
    url: '/stc/stc_payroll/settings/rate',
})

// NEW
$.ajax({
    url: window.appBaseUrl + '/settings/rate',
})
```

3. **Window Location:**
```javascript
// OLD
window.location.href = '/stc/stc_payroll/master/employees';

// NEW
window.location.href = window.appBaseUrl + '/master/employees';
```

4. **Form Action:**
```javascript
// OLD
form.attr('action', '/stc/stc_payroll/logout');

// NEW
form.attr('action', window.appBaseUrl + '/logout');
```

## Files That Still Need Updates

The following files still contain hardcoded `/stc/stc_payroll` paths and should be updated:

1. `resources/views/pages/transaction/payroll.blade.php` - Multiple AJAX URLs
2. `resources/views/pages/transaction/attendance.blade.php` - Multiple AJAX URLs
3. `resources/views/pages/master/employees.blade.php` - Multiple AJAX URLs
4. `resources/views/pages/master/designations.blade.php` - Multiple AJAX URLs
5. `resources/views/pages/master/departments.blade.php` - Multiple AJAX URLs
6. `resources/views/pages/master/gangs.blade.php` - Multiple AJAX URLs
7. `resources/views/pages/admin/users.blade.php` - Multiple AJAX URLs
8. `resources/views/pages/settings/payroll-parameter.blade.php` - AJAX URLs
9. `resources/views/pages/calendar.blade.php` - Base URL variable
10. `resources/views/pages/settings.blade.php` - Base URL variable

## Quick Find & Replace

You can use your IDE's find and replace feature:

**Find:** `/stc/stc_payroll`
**Replace with:** `window.appBaseUrl + '`

**Note:** You'll need to manually adjust quotes and concatenation for each case.

## Environment Configuration

### Local Development (.env)
```env
APP_URL=http://localhost/stc/stc_payroll/public
```

### Production Server (.env)
```env
APP_URL=https://stcassociate.com/stc_payroll/public
```

## Testing

After updating URLs:

1. **Clear all caches:**
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

2. **Test in browser:**
   - Check that all links work
   - Check that AJAX requests work
   - Check that assets (CSS/JS) load correctly
   - Check browser console for 404 errors

## Benefits

✅ Works in both local and server environments  
✅ No need to change code when deploying  
✅ Just update `APP_URL` in `.env` file  
✅ All URLs are generated dynamically  
✅ Assets load correctly in subdirectories  

