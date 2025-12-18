# STC Payroll System

A Laravel-based payroll management system for STC Associates.

## Features

- **Dashboard**: Overview of employees, sites, and departments
- **Master Data Management**:
  - Sites
  - Departments
  - Designations
  - Gangs
  - Employees
- **Transaction Management**: Payroll processing
- **Reports**: Employee reports
- **Settings**: Application configuration
- **User Profile**: Edit profile and logout functionality

## Layout

The application uses AdminLTE 3 template with:
- Dark sidebar navigation on the left
- Light main content area
- Header with user dropdown (Edit Profile, Logout)
- Responsive design

## Database Structure

The system uses the following tables:
- `sites` - Site locations
- `departments` - Department information
- `designations` - Job designations
- `gangs` - Gang/Group information
- `employees` - Employee master data
- `rates` - Employee salary/rate information

## Quick Start

1. Follow the setup instructions in `SETUP.md`
2. Configure your `.env` file with database credentials
3. Run migrations: `php artisan migrate`
4. Import data: `php artisan import:userdata`
5. Start server: `php artisan serve`

## Navigation

- **Home**: Dashboard with statistics
- **Master**: Manage sites, departments, designations, gangs, and employees
- **Transaction**: Process payroll
- **Reports**: View employee reports
- **Settings**: Application settings

## Development

Built with:
- Laravel 6.x
- AdminLTE 3
- MySQL/MariaDB
- Bootstrap 4
