# Quick Start Guide

## 1. Copy AdminLTE Assets (REQUIRED)

The CSS and JavaScript files won't load until you copy the assets. Run this in PowerShell:

```powershell
cd E:\xampp\htdocs\stc\stc_payroll
Copy-Item -Path "..\superadmin.stcassociate.com\public\dist" -Destination "public\dist" -Recurse -Force
Copy-Item -Path "..\superadmin.stcassociate.com\public\plugins" -Destination "public\plugins" -Recurse -Force
```

## 2. Configure Database

Update your `.env` file:
```
DB_DATABASE=stc_payroll
DB_USERNAME=root
DB_PASSWORD=
APP_URL=http://localhost/stc/stc_payroll/public
```

## 3. Run Migrations

```bash
php artisan migrate
```

## 4. Import Data

```bash
php artisan import:userdata
```

## 5. Access the Application

Open your browser and go to:
```
http://localhost/stc/stc_payroll/
```

The application should now work with all CSS and JavaScript loading correctly!

## Troubleshooting

### CSS/JS Not Loading?
- Make sure you copied the `dist` and `plugins` folders to `public/`
- Check browser console for 404 errors
- Verify the paths in the HTML source start with `/stc/stc_payroll/`

### 404 Errors?
- Make sure mod_rewrite is enabled in Apache
- Check that `.htaccess` files are present in both root and `public/` folders
- Verify Apache allows `.htaccess` overrides

