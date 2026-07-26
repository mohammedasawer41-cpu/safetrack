# SafeTrack HSE - Health, Safety & Environment Management System

A comprehensive PHP/MySQL-based inspection and anomaly management system for HSE compliance.

## Features

- **Dashboard**: Real-time KPI tracking (Total Inspections, Open Anomalies, Completed CAPA, Compliance %)
- **Inspection Planning**: Monthly scheduling with inspector assignment
- **Inspection Management**: Conduct inspections with checklist templates
- **Anomaly Tracking**: Log and track safety anomalies with severity levels
- **CAPA**: Corrective and Preventive Action tracking
- **Reports**: Analytics and compliance reporting
- **User Management**: Role-based access control

## Installation

### 1. Database Setup

1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Click **Import**
3. Select `safetrack_fixed.sql` file
4. Click **Go**

### 2. Configuration

Update database credentials in `config/database.php` if needed:

```php
$host = "localhost";
$dbname = "safetrack";
$user = "root";
$pass = "";
```

### 3. Access Application

- **URL**: `http://localhost/safetrack`
- **Username**: `admin`
- **Password**: `admin`

## Project Structure

```
safetrack/
├── config/
│   ├── database.php      # Database configuration
│   ├── auth.php          # Authentication check
│   └── db.php            # Legacy DB config
├── includes/
│   ├── header.php        # Page header & meta
│   ├── footer.php        # Page footer & scripts
│   ├── navbar.php        # Top navigation
│   ├── sidebar.php       # Main menu
│   ├── dashboard_data.php # Dashboard queries
│   └── chart_data.php    # Chart data queries
├── modules/
│   ├── planning/
│   │   ├── monthly.php   # Monthly planning
│   │   ├── create_schedule.php
│   │   ├── save_schedule.php
│   │   ├── view_schedule.php
│   │   ├── start_inspection.php
│   │   └── ...
│   ├── inspections/
│   ├── anomalies/
│   ├── actions/          # CAPA module
│   ├── reports/
│   └── users/
├── assets/
│   ├── adminlte/         # AdminLTE theme
│   ├── css/
│   └── js/
├── dashboard.php         # Main dashboard
├── login.php            # Login page
├── logout.php           # Logout handler
├── index.php            # Entry point
└── safetrack_fixed.sql  # Database schema
```

## Database Structure

### Main Tables

- **users** - System users with roles
- **roles** - Role definitions (Admin, Supervisor, Inspector, Viewer)
- **inspections** - Inspection records
- **inspection_schedule** - Planned inspections
- **inspection_answers** - Checklist question answers
- **anomalies** - Safety anomalies/findings
- **corrective_actions** - CAPA records
- **checklist_templates** - Reusable checklists
- **sites** - Facility locations
- **areas** - Site areas/departments

## Default Login

- **Username**: `admin`
- **Password**: `admin`

## Technologies

- **Backend**: PHP 7+
- **Database**: MySQL/MariaDB
- **Frontend**: Bootstrap 5, AdminLTE 3
- **Charts**: Chart.js
- **Icons**: FontAwesome

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or MariaDB 10.3+
- Apache with mod_rewrite
- Composer (optional)

## License

Closed Source

## Support

For issues or questions, contact the development team.
