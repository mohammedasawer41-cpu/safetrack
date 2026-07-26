# Bulk Inspection Schedule Import Guide

## Overview
This feature allows bulk import of inspection schedules via CSV file, designed for the June 2026 CCTV facility inspection program.

## Setup Steps

### 1. Load Reference Data

First, import the base data (inspectors, facility site, checklist templates):

```bash
# In phpMyAdmin:
1. Click "Import"
2. Select sql/seed_cctv_facility.sql
3. Click Go
```

Or run directly in MySQL:
```sql
SOURCE sql/seed_cctv_facility.sql;
```

### 2. Prepare CSV File

Expected CSV format (5 columns):
```csv
Date,Inspector,Checklist,Status,Anomalies
6/2/2026,Hatem Ben Brahim,Gerbeurs/Tracteurs,effectuée,
6/5/2026,Ahlem Lellahom,Zones de Stockage,effectuée,4 anomalies
```

**Columns:**
- **Date**: DD/M/YYYY format (6/2/2026)
- **Inspector**: Full name (must match user in database)
- **Checklist**: Template name (must match template in database)
- **Status**: One of:
  - `effectuée` → Completed
  - `en cours` → In Progress
  - `planifiée` or blank → Planned
- **Anomalies**: Optional notes/anomaly count

### 3. Use Import Tool

1. Login to SafeTrack as Admin
2. Navigate: **Inspection Planning** → (click **Import Schedules** button)
3. Alternative: Direct URL: `/modules/planning/import.php`
4. Select CSV file from `data/cctv_schedule_june_2026.csv` (included)
5. Click **Import**

### 4. Verify Import

Check **Monthly Inspection Planning** view to confirm:
- All 30 inspection dates appear
- Inspectors are correctly assigned
- Checklist templates are linked
- Status reflects import (Completed/In Progress/Planned)

## Included Files

| File | Purpose |
|------|----------|
| `modules/planning/import.php` | Web import interface |
| `sql/seed_cctv_facility.sql` | Reference data (users, site, templates) |
| `data/cctv_schedule_june_2026.csv` | June 2026 CCTV inspection schedule |
| `docs/BULK_IMPORT_GUIDE.md` | This guide |

## Error Handling

The import tool skips invalid rows:
- Missing or invalid dates
- Inspector names not found in system
- Checklist template not found
- Less than 4 columns in row

**Import Summary** shows:
- Inserted: Number of successful records
- Skipped: Number of rows not imported

## Troubleshooting

### "Inspector not found"
- Verify inspector name exactly matches database (case-insensitive partial match)
- Ensure user status is "Active" in Users module

### "Checklist template not found"
- Run `sql/seed_cctv_facility.sql` first to create templates
- Check template names match CSV exactly (or use partial match)

### "CCTV Facility site not found"
- Import assumes site named "CCTV Facility" exists
- Create manually if needed: **Sites module** → Add "CCTV Facility 2026"

## Next Steps

After import:
1. View schedules in **Monthly Planning** (filter by month: June)
2. For completed inspections, click **Start Inspection** to begin checklist
3. Log findings as anomalies if needed
4. Create CAPA actions for open anomalies

## API Integration

For programmatic import:
```php
// Direct CSV processing without web form
$csv = fopen('data/cctv_schedule_june_2026.csv', 'r');
fgetcsv($csv); // Skip header
while ($row = fgetcsv($csv)) {
    // Process each row
}
```
