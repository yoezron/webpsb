# 🔧 MIGRATION INSTRUCTIONS - PENTING!

## ⚠️ MASALAH YANG TERJADI

Update data **berhasil dilakukan** tapi data **tidak masuk ke database** karena:

1. ✅ **SUDAH DIPERBAIKI**: Field `hubungan_wali` tidak ada di `WaliModel::$allowedFields`
   - Commit: `83f9187`
   - File: `app/Models/WaliModel.php`

2. ❌ **HARUS DIJALANKAN**: Kolom `hubungan_wali` belum ditambahkan ke database
   - Migration SQL sudah tersedia: `migration_add_hubungan_wali.sql`
   - **DATABASE HARUS DIUPDATE SEKARANG!**

---

## 📋 LANGKAH-LANGKAH PERBAIKAN

### Option 1: Via phpMyAdmin (Paling Mudah)

1. Buka **phpMyAdmin**
2. Pilih database PSB Anda
3. Klik tab **SQL**
4. Copy-paste query berikut:

```sql
ALTER TABLE `data_wali`
ADD COLUMN `hubungan_wali` VARCHAR(50) NULL AFTER `nama_wali`;
```

5. Klik **Go** / **Jalankan**

---

### Option 2: Via Command Line

```bash
# Login ke MySQL
mysql -u username -p database_name

# Jalankan query
ALTER TABLE `data_wali`
ADD COLUMN `hubungan_wali` VARCHAR(50) NULL AFTER `nama_wali`;

# Keluar
EXIT;
```

---

### Option 3: Import SQL File

```bash
# Via command line
mysql -u username -p database_name < migration_add_hubungan_wali.sql

# Atau via phpMyAdmin
# 1. Klik tab "Import"
# 2. Choose file: migration_add_hubungan_wali.sql
# 3. Klik "Go"
```

---

## ✅ VERIFIKASI

Setelah menjalankan migration, cek apakah kolom sudah ada:

```sql
DESCRIBE data_wali;
```

Harusnya ada kolom baru:
```
hubungan_wali | varchar(50) | YES | | NULL |
```

---

## 🔍 CEK APAKAH ADA FIELD SPRINT 2 LAINNYA YANG BELUM DI-MIGRATE

Jalankan query berikut untuk memastikan semua kolom Sprint 2 sudah ada:

```sql
-- Cek data_wali
SHOW COLUMNS FROM data_wali LIKE 'hubungan_wali';
SHOW COLUMNS FROM data_wali LIKE 'tempat_lahir_wali';
SHOW COLUMNS FROM data_wali LIKE 'tanggal_lahir_wali';

-- Cek alamat_pendaftar
SHOW COLUMNS FROM alamat_pendaftar LIKE 'nama_kepala_keluarga';
SHOW COLUMNS FROM alamat_pendaftar LIKE 'rt_rw';
SHOW COLUMNS FROM alamat_pendaftar LIKE 'tinggal_bersama';

-- Cek asal_sekolah
SHOW COLUMNS FROM asal_sekolah LIKE 'alamat_sekolah';
SHOW COLUMNS FROM asal_sekolah LIKE 'tahun_lulus';
SHOW COLUMNS FROM asal_sekolah LIKE 'rata_rata_rapor';
SHOW COLUMNS FROM asal_sekolah LIKE 'nilai_tka';
SHOW COLUMNS FROM asal_sekolah LIKE 'sekolah_md';
```

---

## 🚨 SETELAH MIGRATION

1. **Logout** dari aplikasi (jika sudah login)
2. **Clear browser cache** (Ctrl+Shift+Del)
3. **Coba update data lagi**
4. **Verifikasi di database** bahwa data benar-benar tersimpan

---

## 📞 TROUBLESHOOTING

### Jika masih gagal setelah migration:

1. Cek error log:
   ```bash
   tail -f writable/logs/log-*.php
   ```

2. Cek apakah ada error di browser console (F12)

3. Pastikan semua field di form sudah terisi dengan benar

4. Test dengan data minimal terlebih dahulu

---

## 📝 CATATAN

- Migration file: `migration_add_hubungan_wali.sql`
- Migration class: `app/Database/Migrations/2026-01-02-000001_AddHubunganWaliColumn.php`
- Model yang diperbaiki: `app/Models/WaliModel.php`
- Commit fix: `83f9187`

**PENTING**: Tanpa menjalankan migration SQL ini, update data akan **tetap gagal** meskipun kode sudah diperbaiki!
