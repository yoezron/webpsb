# 🗄️ Database Setup Guide - PSB Persis 31 Banjaran

## 📋 Prerequisites

Sudah dilakukan:
- ✅ File `.env` sudah dikonfigurasi
- ✅ Composer dependencies sudah terinstall
- ✅ Encryption key sudah di-generate

## 🚀 Langkah-Langkah Setup Database

### Opsi 1: Menggunakan phpMyAdmin (Paling Mudah)

1. **Buka phpMyAdmin**
   ```
   http://localhost/phpmyadmin
   ```

2. **Login dengan credentials Laragon default:**
   - Username: `root`
   - Password: (kosongkan)

3. **Buat Database Baru:**
   - Klik tab "Databases"
   - Nama database: `psb_persis31`
   - Collation: `utf8mb4_unicode_ci`
   - Klik "Create"

4. **Jalankan Migration:**
   ```bash
   php spark migrate
   ```

---

### Opsi 2: Menggunakan HeidiSQL (Alternatif)

1. **Buka HeidiSQL** (sudah include di Laragon)

2. **Connect ke MySQL:**
   - Hostname: `localhost`
   - User: `root`
   - Password: (kosong)
   - Port: `3306`

3. **Buat Database:**
   - Klik kanan pada koneksi → "Create new" → "Database"
   - Nama: `psb_persis31`
   - Collation: `utf8mb4_unicode_ci`
   - OK

4. **Jalankan Migration:**
   ```bash
   php spark migrate
   ```

---

### Opsi 3: Menggunakan Command Line MySQL

1. **Buka Laragon Terminal** atau **Command Prompt**

2. **Masuk ke MySQL:**
   ```bash
   cd C:\laragon\bin\mysql\mysql-8.0.x\bin
   mysql -u root
   ```

3. **Buat Database:**
   ```sql
   CREATE DATABASE IF NOT EXISTS psb_persis31
   CHARACTER SET utf8mb4
   COLLATE utf8mb4_unicode_ci;

   SHOW DATABASES LIKE 'psb_persis31';

   EXIT;
   ```

4. **Kembali ke Project Directory:**
   ```bash
   cd C:\laragon\www\webpsb
   ```

5. **Jalankan Migration:**
   ```bash
   php spark migrate
   ```

---

## ✅ Verifikasi Database

Setelah migration berhasil, Anda akan melihat output seperti ini:

```
Running all new migrations...
Running: 2026-01-01-000001_CreatePendaftarTable
Migrated: 2026-01-01-000001_CreatePendaftarTable
Running: 2026-01-01-000002_CreateAlamatPendaftarTable
Migrated: 2026-01-01-000002_CreateAlamatPendaftarTable
...
All migrations completed!
```

## 🧪 Test Database Connection

Jalankan command ini untuk test koneksi:

```bash
php spark db:table
```

Jika berhasil, akan muncul daftar tabel yang sudah dibuat.

---

## 🌱 Menjalankan Seeder (Opsional)

Untuk mengisi data dummy (admin & sample pendaftar):

```bash
php spark db:seed DatabaseSeeder
```

---

## 🔧 Troubleshooting

### Error: "Access denied for user"

**Solusi:**
1. Cek username dan password di file `.env`
2. Pastikan MySQL di Laragon sudah running (lampu hijau)
3. Default Laragon:
   - Username: `root`
   - Password: (kosong/empty)

### Error: "Unknown database 'psb_persis31'"

**Solusi:**
Database belum dibuat. Ikuti salah satu opsi di atas untuk membuat database.

### Error: "Connection refused"

**Solusi:**
1. Pastikan MySQL di Laragon sudah running
2. Klik "Start All" di Laragon
3. Tunggu sampai status MySQL menjadi hijau

### Error: "Table already exists"

**Solusi:**
Migration sudah pernah dijalankan. Untuk reset:
```bash
php spark migrate:rollback
php spark migrate
```

---

## 📊 Struktur Database

Setelah migration, database akan memiliki 8 tabel:

1. **migrations** - Tracking migration history
2. **pendaftar** - Data utama pendaftaran
3. **alamat_pendaftar** - Data alamat
4. **data_ayah** - Data ayah
5. **data_ibu** - Data ibu
6. **data_wali** - Data wali
7. **bansos_pendaftar** - Data bantuan sosial
8. **asal_sekolah** - Data sekolah asal
9. **admin_panitia** - Data admin/panitia

---

## 🎯 Next Steps

Setelah database setup selesai:

1. ✅ Jalankan seeder untuk data dummy
2. ✅ Test landing page: `php spark serve` → http://localhost:8080
3. ✅ Mulai Sprint 3: Form Pendaftaran

---

## 📞 Bantuan

Jika masih ada masalah, cek:
- File `.env` sudah benar?
- MySQL di Laragon sudah running?
- Port 3306 tidak bentrok dengan aplikasi lain?

**Happy Coding!** 💻🚀
