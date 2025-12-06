# Quick Database Reset Script

## ⚠️ IMPORTANT: Only run this if you need to reset the database

This script will drop all tables and reset the database for a fresh migration.

**Your MySQL Password:** `Behaviorisme90`

## 🚀 EASIEST WAY: Use the Automated Script

Simply run this command from your project directory:

```bash
reset_database.bat
```

This will automatically:
1. Drop the existing database
2. Create a new database
3. Run all migrations

---

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
# Login to MySQL with password
mysql -u root -p
# Enter password: Behaviorisme90

# Drop and recreate database
DROP DATABASE IF EXISTS psb_persis31;
CREATE DATABASE psb_persis31 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE psb_persis31;
SHOW TABLES;

EXIT;
```

**Or use one-liner with password:**

```bash
mysql -u root -pBehaviorisme90 -e "DROP DATABASE IF EXISTS psb_persis31; CREATE DATABASE psb_persis31 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

**Note:** No space between `-p` and password!

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
