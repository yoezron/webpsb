# **CLEAR PROMPT / PROJECT INSTRUCTION**

## **1. Tujuan Pengembangan**

Bangun sebuah **Sistem Informasi Pendaftaran Santri Baru** untuk Pesantren Persatuan Islam 31 Banjaran menggunakan **framework CodeIgniter 4** dengan basis arsitektur MVC.
Sistem harus menyediakan fitur **pendaftaran daring**, **penyimpanan data ke database**, **dashboard panitia**, dan **download kartu pendaftaran** dalam format PDF.
UI harus memanfaatkan **Hafsa Main HTML Template** dengan warna utama **hijau–kuning** menyesuaikan identitas lembaga.

---

## **2. Spesifikasi Teknis**

1. **Framework**: CodeIgniter 4 (versi terbaru LTS).
2. **Database**: MySQL / MariaDB.
3. **Frontend Template**: Hafsa Main HTML Template.
4. **Style Warna**: Hijau (#1AB34A atau sejenis) – Kuning (#F3C623 atau sejenis).
5. **PDF Generator**: Dompdf atau TCPDF.
6. **Autonumber**: Sistem harus menghasilkan **Nomor Pendaftaran** otomatis.

---

## **3. Struktur Halaman dan Flow Sistem**

---

### **A. Landing Page (Welcome Page)**

Tampilkan halaman utama dengan elemen berikut:

- Judul Besar:
  **Pendaftaran Santri Baru Pesantren Persatuan Islam 31 Banjaran**
  **Tahun Ajaran 2026/2027**
- Dua tombol pendaftaran:

  1. **Daftar Tingkat Tsanawiyyah**
  2. **Daftar Tingkat Mu’allimin**

- Gunakan hero banner template Hafsa.
- Warna utama hijau–kuning.
- CTA jelas dan responsif di mobile.

---

### **B. Halaman Formulir Pendaftaran**

Buat **dua jalur pendaftaran terpisah**:

1. **Form Tsanawiyyah**
2. **Form Mu’allimin**

**Setiap formulir memiliki field sebagai berikut:**

---

### **1. Data Diri**

- Nomor Pendaftaran (auto-generate)
  (contoh nomor pendaftaran: T2026-001 untuk pendaftar Tsanawiyyah, M2026-001 untuk pendaftar Mu'allimin)
- NISN
- NIK
- Nama Lengkap
- Jenis Kelamin
- Tempat, Tanggal Lahir
- Status dalam Keluarga
- Anak Ke-
- Jumlah Saudara
- Hobi
- Cita-cita
- Pernah PAUD (Ya/Tidak)
- Pernah TK (Ya/Tidak)
- Kebutuhan Disabilitas
- Imunisasi
- Nomor Handphone
- Ukuran Baju
- Prestasi

---

### **2. Data Tempat Tinggal**

- Nomor Kartu Keluarga
- Jenis Tempat Tinggal
- Alamat
- Desa
- Kecamatan
- Kabupaten
- Provinsi
- Kode Pos
- Jarak ke Sekolah
- Waktu Tempuh
- Transportasi yang Digunakan
- Email
- Media Sosial

---

### **3. Data Keluarga (Ayah, Ibu, Wali)**

#### **Data Ayah**

- Nama Ayah
- NIK Ayah
- Tempat, Tanggal Lahir Ayah
- Status Ayah
- Pendidikan
- Pekerjaan
- Penghasilan
- No. Handphone Ayah
- Alamat Ayah

#### **Data Ibu**

- Nama Ibu
- NIK Ibu
- Tempat, Tanggal Lahir Ibu
- Status Ibu
- Pendidikan
- Pekerjaan
- Penghasilan
- No. Handphone Ibu
- Alamat Ibu

#### **Data Wali**

- Nama Wali
- NIK Wali
- Tahun Lahir Wali
- Pendidikan
- Pekerjaan
- Penghasilan
- No. Handphone Wali

---

### **4. Data Bantuan Pemerintah**

- No. KKS
- No. PKH
- No. KIP

---

### **5. Data Asal Sekolah**

- Nama Asal Sekolah
- Jenjang Sekolah
- Status Sekolah
- NPSN
- Lokasi Sekolah
- Asal Ibtidaiyyah/Tsanawiyyah

---

### **6. Halaman Review Data**

- Semua data ditampilkan kembali dalam bentuk tabel ringkas.
- Tombol:

  - **Edit Data**
  - **Submit Pendaftaran**

---

### **7. Hasil Setelah Submit**

Setelah formulir dikirim, sistem harus menampilkan:

- Pesan:
  **Pendaftaran Berhasil**
- Tombol:
  **Download Kartu Bukti Pendaftaran (PDF)**
  PDF berisi:

  - Nomor Pendaftar
  - Nama Lengkap
  - Tempat Tanggal Lahir
  - NISN
  - Asal Sekolah
  - Tanggal Pendaftaran

- Data otomatis masuk database sesuai jalur (Tsanawiyyah/Mu’allimin).

---

## **C. Dashboard Panitia (Login Admin/Panitia)**

Buat dua level jalur:

### **1. Dashboard Tsanawiyyah**

- Daftar Pendaftar Tsanawiyyah

  - Tabel berisi data inti: Nomor Pendaftaran, Nama, TTL, Asal Sekolah, Tanggal Daftar
  - Tombol: **Lihat Detail**
  - Tombol: **Download Data (Excel/CSV)**

### **2. Dashboard Mu’allimin**

- Daftar Pendaftar Mu’allimin

  - Fitur sama seperti Tsanawiyyah

### **Detail Pendaftar**

Menampilkan seluruh data formulir secara lengkap.

- Tombol **Print** atau **Download PDF**.

---

## **4. Fitur Sistem**

1. Validasi Formulir (server-side + client-side).
2. Autogenerate Nomor Pendaftaran (format bebas, misal: PSB-2026-TSW-0001).
3. Export Data ke Excel/CSV.
4. Security:

   - CSRF Protection
   - Input sanitization
   - Login panitia dengan session

5. PDF Generator untuk bukti pendaftaran.
6. Mobile responsive layout.

---

## **5. Struktur Direktori (Disarankan)**

```
/app
  /Controllers
    Landing.php
    Pendaftaran.php
    Dashboard.php
  /Models
    PendaftarModel.php
  /Views
    landing/
    form/
    review/
    success/
    dashboard/
    template-hafsa/

public/
  /assets
  /uploads
```

---

## **6. Output Akhir yang Diharapkan**

1. Website siap pakai.
2. Database lengkap dengan semua field.
3. Fungsi pendaftaran berjalan tanpa error.
4. Kartu bukti pendaftaran PDF dapat diunduh.
5. Dashboard panitia berjalan baik untuk dua tingkat.
6. Template Hafsa sukses terintegrasi.

---

## **7. Deliverables**

- Source code CodeIgniter 4
- File SQL
- Dokumentasi instalasi
- Dokumentasi struktur data
- Tutorial penggunaan
- Template PDF kartu pendaftaran

---

1. Tabel Utama: pendaftar

Menyimpan seluruh data pendaftar, dari Tsanawiyyah maupun Mu’allimin.

pendaftar

id_pendaftar (PK)

nomor_pendaftaran (unique, autogenerated)

jalur_pendaftaran (enum: “TSANAWIYYAH”, “MUALLIMIN”)

nisn

nik

nama_lengkap

jenis_kelamin

tempat_lahir

tanggal_lahir

status_keluarga

anak_ke

jumlah_saudara

hobi

cita_cita

pernah_paud (boolean)

pernah_tk (boolean)

kebutuhan_disabilitas

imunisasi

no_hp

ukuran_baju

prestasi

tanggal_daftar (timestamp)

2. Tabel Alamat: alamat_pendaftar

alamat_pendaftar

id_alamat (PK)

id_pendaftar (FK → pendaftar.id_pendaftar)

jenis_tempat_tinggal

alamat

desa

kecamatan

kabupaten

provinsi

kode_pos

jarak_ke_sekolah

waktu_tempuh

transportasi

email

media_sosial

nomor_kk

3. Tabel Keluarga: Ayah, Ibu, Wali
   a. data_ayah

id_ayah (PK)

id_pendaftar (FK)

nama_ayah

nik_ayah

tempat_lahir_ayah

tanggal_lahir_ayah

status_ayah

pendidikan_ayah

pekerjaan_ayah

penghasilan_ayah

hp_ayah

alamat_ayah

b. data_ibu

id_ibu (PK)

id_pendaftar (FK)

nama_ibu

nik_ibu

tempat_lahir_ibu

tanggal_lahir_ibu

status_ibu

pendidikan_ibu

pekerjaan_ibu

penghasilan_ibu

hp_ibu

alamat_ibu

c. data_wali

id_wali (PK)

id_pendaftar (FK)

nama_wali

nik_wali

tahun_lahir_wali

pendidikan_wali

pekerjaan_wali

penghasilan_wali

hp_wali

4. Tabel Bantuan Sosial: bansos_pendaftar

bansos_pendaftar

id_bansos (PK)

id_pendaftar (FK)

no_kks

no_pkh

no_kip

5. Tabel Asal Sekolah: asal_sekolah

asal_sekolah

id_sekolah (PK)

id_pendaftar (FK)

nama_asal_sekolah

jenjang_sekolah

status_sekolah

npsn

lokasi_sekolah

asal_jenjang (Ibtidaiyyah/Tsanawiyyah)

6. Tabel Admin Panitia: admin_panitia

admin_panitia

id_admin (PK)

username

password_hash

role_panitia (tsanawiyyah / muallimin / superadmin)

created_at

updated_at

7. Relasi Utama (Simplified ERD Text Format)
   pendaftar 1---1 alamat_pendaftar
   pendaftar 1---1 data_ayah
   pendaftar 1---1 data_ibu
   pendaftar 1---1 data_wali
   pendaftar 1---1 bansos_pendaftar
   pendaftar 1---1 asal_sekolah

admin_panitia (standalone, no FK to pendaftar)

Semua hubungan 1:1 kecuali jika nanti diperlukan multientry.

8.  Versi Diagram Visual (ASCII ERD)
    +-----------------+ +--------------------+
    | pendaftar |1------1 | alamat_pendaftar |
    +-----------------+ +--------------------+
    | id_pendaftar PK | | id_alamat PK |
    | nomor_pendaftaran| | id_pendaftar FK |
    | jalur_pendaftaran| | ... alamat fields |
    | nisn | +--------------------+
    | ... |
    +------------------+

            |1
            |
            |1

    +------------------+
    | data_ayah |
    +------------------+
    | id_ayah PK |
    | id_pendaftar FK |
    | ... |
    +------------------+

            |1
            |
            |1

    +------------------+
    | data_ibu |
    +------------------+
    | id_ibu PK |
    | id_pendaftar FK |
    | ... |
    +------------------+

            |1
            |
            |1

    +------------------+
    | data_wali |
    +------------------+
    | id_wali PK |
    | id_pendaftar FK |
    | ... |
    +------------------+

            |1
            |
            |1

    +------------------+
    | bansos_pendaftar |
    +------------------+
    | id_bansos PK |
    | id_pendaftar FK |
    | ... |
    +------------------+

            |1
            |
            |1

    +------------------+
    | asal_sekolah |
    +------------------+
    | id_sekolah PK |
    | id_pendaftar FK |
    | ... |
    +------------------+

+------------------+
| admin_panitia |
+------------------+
| id_admin PK |
| username |
| password_hash |
| role_panitia |
+------------------+

# 🎓 Sistem Pendaftaran Santri Baru

## Pesantren Persatuan Islam 31 Banjaran

[![CodeIgniter](https://img.shields.io/badge/CodeIgniter-4.x-orange.svg)](https://codeigniter.com/)
[![PHP](https://img.shields.io/badge/PHP-8.0+-blue.svg)](https://php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-5.7+-green.svg)](https://mysql.com/)

---

## 📖 Deskripsi Project

Sistem Informasi Pendaftaran Santri Baru untuk Pesantren Persatuan Islam 31 Banjaran dengan fitur:

- ✅ Pendaftaran Online (Tsanawiyyah & Mu'allimin)
- ✅ Dashboard Panitia
- ✅ Cetak Kartu Pendaftaran (PDF)
- ✅ Export Data (Excel/CSV)
- ✅ Auto-generate Nomor Pendaftaran
- ✅ Responsive Design (Hafsa Template)

---

## 🛠️ Requirements

### Minimum Requirements:

- **PHP**: 8.0 atau lebih tinggi
- **MySQL/MariaDB**: 5.7+ / 10.3+
- **Composer**: 2.x
- **Web Server**: Apache 2.4+ / Nginx 1.18+
- **Extensions PHP**:
  - intl
  - mbstring
  - json
  - mysqlnd
  - xml
  - gd
  - zip

### Recommended:

- **PHP**: 8.2
- **MySQL**: 8.0+
- **RAM**: Minimum 512MB
- **Disk Space**: 500MB

---

## 📦 Instalasi

### 1️⃣ Clone Repository

```bash
git clone https://github.com/your-repo/psb-persis31-banjaran.git
cd psb-persis31-banjaran
```

### 2️⃣ Install CodeIgniter 4

```bash
composer create-project codeigniter4/appstarter .
```

**Atau** jika sudah ada composer.json:

```bash
composer install
```

### 3️⃣ Konfigurasi Environment

Copy file environment:

```bash
cp env .env
```

Edit file `.env`:

```env
#--------------------------------------------------------------------
# ENVIRONMENT
#--------------------------------------------------------------------
CI_ENVIRONMENT = development

#--------------------------------------------------------------------
# APP
#--------------------------------------------------------------------
app.baseURL = 'http://localhost:8080/'
app.indexPage = ''

#--------------------------------------------------------------------
# DATABASE
#--------------------------------------------------------------------
database.default.hostname = localhost
database.default.database = psb_persis31
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.DBPrefix =
database.default.port = 3306
```

### 4️⃣ Buat Database

```bash
mysql -u root -p
```

```sql
CREATE DATABASE psb_persis31 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

### 5️⃣ Install Dependencies

```bash
# Install Dompdf untuk PDF Generator
composer require dompdf/dompdf

# Install PhpSpreadsheet untuk Export Excel
composer require phpoffice/phpspreadsheet
```

### 6️⃣ Set Permissions (Linux/Mac)

```bash
chmod -R 755 writable/
chmod -R 755 public/assets/uploads/
```

### 7️⃣ Run Migration (akan dibuat di Sprint 1)

```bash
php spark migrate
php spark db:seed DatabaseSeeder
```

### 8️⃣ Jalankan Development Server

```bash
php spark serve
```

Buka browser: **http://localhost:8080**

---

## 📁 Struktur Folder

```
psb-persis31-banjaran/
├── app/
│   ├── Config/
│   │   ├── Routes.php
│   │   └── Database.php
│   ├── Controllers/
│   │   ├── Landing.php
│   │   ├── Pendaftaran.php
│   │   ├── Dashboard.php
│   │   └── Auth.php
│   ├── Models/
│   │   ├── PendaftarModel.php
│   │   ├── AlamatModel.php
│   │   ├── AyahModel.php
│   │   ├── IbuModel.php
│   │   ├── WaliModel.php
│   │   ├── BansosModel.php
│   │   ├── SekolahModel.php
│   │   └── AdminModel.php
│   ├── Views/
│   │   ├── templates/
│   │   │   ├── header.php
│   │   │   ├── footer.php
│   │   │   └── sidebar.php
│   │   ├── landing/
│   │   │   └── index.php
│   │   ├── form/
│   │   │   ├── tsanawiyyah.php
│   │   │   └── muallimin.php
│   │   ├── review/
│   │   │   └── index.php
│   │   ├── success/
│   │   │   └── index.php
│   │   ├── dashboard/
│   │   │   ├── tsanawiyyah.php
│   │   │   ├── muallimin.php
│   │   │   └── detail.php
│   │   └── auth/
│   │       └── login.php
│   └── Database/
│       ├── Migrations/
│       └── Seeds/
├── public/
│   ├── assets/
│   │   ├── hafsa/
│   │   │   ├── css/
│   │   │   ├── js/
│   │   │   ├── images/
│   │   │   └── fonts/
│   │   └── uploads/
│   │       └── pdf/
│   └── index.php
├── writable/
│   ├── logs/
│   ├── cache/
│   └── session/
├── .env
├── composer.json
└── README.md
```

---

## 🎨 Template Hafsa

Template Hafsa sudah terintegrasi dengan warna branding:

- **Primary**: #1AB34A (Hijau)
- **Secondary**: #F3C623 (Kuning)

File template terletak di: `public/assets/hafsa/`

---

## 🔐 Default Login Admin

Setelah seeding:

| Username    | Password   | Role        |
| ----------- | ---------- | ----------- |
| admin       | admin123   | superadmin  |
| panitia_tsn | panitia123 | tsanawiyyah |
| panitia_mua | panitia123 | muallimin   |

⚠️ **PENTING**: Ganti password setelah instalasi!

---

## 🧪 Testing

### Unit Testing

```bash
php spark test
```

### Browser Testing

- Chrome (Recommended)
- Firefox
- Safari
- Edge

---

## 📚 Dokumentasi Tambahan

- [User Manual - Pendaftar](docs/USER_MANUAL_PENDAFTAR.pdf)
- [User Manual - Panitia](docs/USER_MANUAL_PANITIA.pdf)
- [Database Schema](docs/DATABASE_SCHEMA.md)
- [API Documentation](docs/API_DOCS.md)

---

## 🐛 Troubleshooting

### Error: "Whoops! We seem to have hit a snag"

1. Pastikan `.env` sudah dikonfigurasi dengan benar
2. Cek permission folder `writable/`
3. Cek log di `writable/logs/`

### Database Connection Failed

1. Cek kredensial di `.env`
2. Pastikan MySQL service running:
   ```bash
   sudo service mysql status
   ```
3. Test koneksi database:
   ```bash
   php spark db:table users
   ```

### PDF Tidak Generate

1. Install dependencies:
   ```bash
   composer require dompdf/dompdf
   ```
2. Cek permission folder `writable/` dan `public/assets/uploads/pdf/`

---

## 📞 Support

Jika ada kendala, hubungi:

- **Email**: admin@persis31banjaran.com
- **WhatsApp**: +62-xxx-xxxx-xxxx
- **Website**: https://persis31banjaran.com

---

## 📝 License

Copyright © 2026 Pesantren Persatuan Islam 31 Banjaran

---

## 👥 Tim Pengembang

- **Project Manager**: [Nama]
- **Lead Developer**: [Nama]
- **Frontend Developer**: [Nama]
- **QA Tester**: [Nama]

---

## 🎯 Sprint Progress

- [x] Sprint 0: Setup & Persiapan
- [ ] Sprint 1: Database Design
- [ ] Sprint 2: Landing Page
- [ ] Sprint 3: Form Pendaftaran Part 1
- [ ] Sprint 4: Form Pendaftaran Part 2
- [ ] Sprint 5: Form Pendaftaran Part 3
- [ ] Sprint 6: Submit & Database
- [ ] Sprint 7: PDF Generator
- [ ] Sprint 8: Authentication
- [ ] Sprint 9-10: Dashboard
- [ ] Sprint 11: Detail & Export
- [ ] Sprint 12: UI/UX Polish
- [ ] Sprint 13: Testing
- [ ] Sprint 14: Documentation
- [ ] Sprint 15: Deployment

---

**Version**: 1.0.0  
**Last Updated**: Desember 2025
