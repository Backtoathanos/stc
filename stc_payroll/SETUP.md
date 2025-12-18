# STC Payroll Setup Guide

## Prerequisites
- PHP >= 7.2.5
- Composer
- MySQL/MariaDB
- XAMPP (or similar local server)

## Installation Steps

### 1. Database Setup
Create a new MySQL database named `stc_payroll`:

```sql
CREATE DATABASE stc_payroll CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 2. Environment Configuration
Copy `.env.example` to `.env` and update the database configuration:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=stc_payroll
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Install AdminLTE Assets
You need to copy the AdminLTE assets from `superadmin.stcassociate.com/public` to `stc_payroll/public`:

**Option 1: Copy folders (Recommended)**
- Copy `dist` folder from `E:\xampp\htdocs\stc\superadmin.stcassociate.com\public\dist` to `E:\xampp\htdocs\stc\stc_payroll\public\dist`
- Copy `plugins` folder from `E:\xampp\htdocs\stc\superadmin.stcassociate.com\public\plugins` to `E:\xampp\htdocs\stc\stc_payroll\public\plugins`

**Option 2: Create symbolic links (Requires Admin privileges)**
Run PowerShell as Administrator:
```powershell
cd E:\xampp\htdocs\stc\stc_payroll
New-Item -ItemType SymbolicLink -Path "public\dist" -Target "E:\xampp\htdocs\stc\superadmin.stcassociate.com\public\dist"
New-Item -ItemType SymbolicLink -Path "public\plugins" -Target "E:\xampp\htdocs\stc\superadmin.stcassociate.com\public\plugins"
```

### 4. Run Migrations
```bash
php artisan migrate
```

### 5. Import User Data
```bash
php artisan import:userdata
```

### 6. Start Development Server
```bash
php artisan serve
```

Access the application at: http://localhost:8000

## Features
- Dashboard with employee statistics
- Master data management (Sites, Departments, Designations, Gangs, Employees)
- Transaction management (Payroll)
- Reports (Employee Reports)
- Settings
- User profile management

## Navigation Structure
- **Home**: Dashboard
- **Master**: Sites, Departments, Designations, Gangs, Employees
- **Transaction**: Payroll
- **Reports**: Employee Reports
- **Settings**: Application settings

