# Quick Database Reset Script

## ⚠️ IMPORTANT: Only run this if you need to reset the database

This script will drop all tables and reset the database for a fresh migration.

## Option 1: Using phpMyAdmin (Recommended)

1. Open phpMyAdmin: http://localhost/phpmyadmin
2. Select database `psb_persis31` from left sidebar
3. Click on "Operations" tab
4. Scroll down to "Remove data or table"
5. Click "Drop the database (DROP)"
6. Confirm
7. Create the database again:
   - Click "New" or "Databases"
   - Name: `psb_persis31`
   - Collation: `utf8mb4_unicode_ci`
   - Click "Create"

## Option 2: Using MySQL Command Line

Open Laragon Terminal or Command Prompt:

```bash
# Navigate to MySQL bin directory
cd C:\laragon\bin\mysql\mysql-8.0.x\bin

# Login to MySQL
mysql -u root

# Drop and recreate database
DROP DATABASE IF EXISTS psb_persis31;
CREATE DATABASE psb_persis31 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE psb_persis31;
SHOW TABLES;

EXIT;
```

## Option 3: Drop Individual Table (If you only need to drop pendaftar table)

```bash
mysql -u root psb_persis31

DROP TABLE IF EXISTS pendaftar;

EXIT;
```

## After Dropping Database/Table

Return to your project directory and run migration:

```bash
cd C:\laragon\www\webpsb

php spark migrate
```

## ✅ Expected Output

```
Running all new migrations...
Running: 2026-01-01-000001_CreatePendaftarTable
Migrated: 2026-01-01-000001_CreatePendaftarTable
Running: 2026-01-01-000002_CreateAlamatPendaftarTable
Migrated: 2026-01-01-000002_CreateAlamatPendaftarTable
...
All migrations completed!
```
