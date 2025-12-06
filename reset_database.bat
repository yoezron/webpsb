@echo off
REM ================================================================
REM Database Reset Script for PSB Persis 31 Banjaran
REM ================================================================
REM This script will drop and recreate the database
REM Usage: Run this file from project root directory
REM ================================================================

echo.
echo ============================================================
echo  PSB PERSIS 31 - DATABASE RESET SCRIPT
echo ============================================================
echo.
echo This will DROP and RECREATE the database: psb_persis31
echo.
pause

echo.
echo [1/3] Dropping existing database...
mysql -u root -pBehaviorisme90 -e "DROP DATABASE IF EXISTS psb_persis31;"

if %errorlevel% neq 0 (
    echo ERROR: Failed to drop database!
    echo Please check your MySQL credentials.
    pause
    exit /b 1
)

echo [SUCCESS] Database dropped!
echo.

echo [2/3] Creating new database...
mysql -u root -pBehaviorisme90 -e "CREATE DATABASE psb_persis31 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

if %errorlevel% neq 0 (
    echo ERROR: Failed to create database!
    pause
    exit /b 1
)

echo [SUCCESS] Database created!
echo.

echo [3/3] Running migrations...
php spark migrate

if %errorlevel% neq 0 (
    echo ERROR: Migration failed!
    pause
    exit /b 1
)

echo.
echo ============================================================
echo  DATABASE RESET COMPLETED SUCCESSFULLY!
echo ============================================================
echo.
echo Next steps:
echo   1. Run seeder (optional): php spark db:seed DatabaseSeeder
echo   2. Start server: php spark serve
echo   3. Open browser: http://localhost:8080
echo.
pause
