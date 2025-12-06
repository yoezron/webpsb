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

* Judul Besar:
  **Pendaftaran Santri Baru Pesantren Persatuan Islam 31 Banjaran**
  **Tahun Ajaran 2026/2027**
* Dua tombol pendaftaran:

  1. **Daftar Tingkat Tsanawiyyah**
  2. **Daftar Tingkat Mu’allimin**
* Gunakan hero banner template Hafsa.
* Warna utama hijau–kuning.
* CTA jelas dan responsif di mobile.

---

### **B. Halaman Formulir Pendaftaran**

Buat **dua jalur pendaftaran terpisah**:

1. **Form Tsanawiyyah**
2. **Form Mu’allimin**

**Setiap formulir memiliki field sebagai berikut:**

---

### **1. Data Diri**

* Nomor Pendaftaran (auto-generate)
(contoh nomor pendaftaran: T2026-001 untuk pendaftar Tsanawiyyah, M2026-001 untuk pendaftar Mu'allimin)
* NISN
* NIK
* Nama Lengkap
* Jenis Kelamin
* Tempat, Tanggal Lahir
* Status dalam Keluarga
* Anak Ke-
* Jumlah Saudara
* Hobi
* Cita-cita
* Pernah PAUD (Ya/Tidak)
* Pernah TK (Ya/Tidak)
* Kebutuhan Disabilitas
* Imunisasi
* Nomor Handphone
* Ukuran Baju
* Prestasi

---

### **2. Data Tempat Tinggal**

* Nomor Kartu Keluarga
* Jenis Tempat Tinggal
* Alamat
* Desa
* Kecamatan
* Kabupaten
* Provinsi
* Kode Pos
* Jarak ke Sekolah
* Waktu Tempuh
* Transportasi yang Digunakan
* Email
* Media Sosial

---

### **3. Data Keluarga (Ayah, Ibu, Wali)**

#### **Data Ayah**

* Nama Ayah
* NIK Ayah
* Tempat, Tanggal Lahir Ayah
* Status Ayah
* Pendidikan
* Pekerjaan
* Penghasilan
* No. Handphone Ayah
* Alamat Ayah

#### **Data Ibu**

* Nama Ibu
* NIK Ibu
* Tempat, Tanggal Lahir Ibu
* Status Ibu
* Pendidikan
* Pekerjaan
* Penghasilan
* No. Handphone Ibu
* Alamat Ibu

#### **Data Wali**

* Nama Wali
* NIK Wali
* Tahun Lahir Wali
* Pendidikan
* Pekerjaan
* Penghasilan
* No. Handphone Wali

---

### **4. Data Bantuan Pemerintah**

* No. KKS
* No. PKH
* No. KIP

---

### **5. Data Asal Sekolah**

* Nama Asal Sekolah
* Jenjang Sekolah
* Status Sekolah
* NPSN
* Lokasi Sekolah
* Asal Ibtidaiyyah/Tsanawiyyah

---

### **6. Halaman Review Data**

* Semua data ditampilkan kembali dalam bentuk tabel ringkas.
* Tombol:

  * **Edit Data**
  * **Submit Pendaftaran**

---

### **7. Hasil Setelah Submit**

Setelah formulir dikirim, sistem harus menampilkan:

* Pesan:
  **Pendaftaran Berhasil**
* Tombol:
  **Download Kartu Bukti Pendaftaran (PDF)**
  PDF berisi:

  * Nomor Pendaftar
  * Nama Lengkap
  * Tempat Tanggal Lahir
  * NISN
  * Asal Sekolah
  * Tanggal Pendaftaran
* Data otomatis masuk database sesuai jalur (Tsanawiyyah/Mu’allimin).

---

## **C. Dashboard Panitia (Login Admin/Panitia)**

Buat dua level jalur:

### **1. Dashboard Tsanawiyyah**

* Daftar Pendaftar Tsanawiyyah

  * Tabel berisi data inti: Nomor Pendaftaran, Nama, TTL, Asal Sekolah, Tanggal Daftar
  * Tombol: **Lihat Detail**
  * Tombol: **Download Data (Excel/CSV)**

### **2. Dashboard Mu’allimin**

* Daftar Pendaftar Mu’allimin

  * Fitur sama seperti Tsanawiyyah

### **Detail Pendaftar**

Menampilkan seluruh data formulir secara lengkap.

* Tombol **Print** atau **Download PDF**.

---

## **4. Fitur Sistem**

1. Validasi Formulir (server-side + client-side).
2. Autogenerate Nomor Pendaftaran (format bebas, misal: PSB-2026-TSW-0001).
3. Export Data ke Excel/CSV.
4. Security:

   * CSRF Protection
   * Input sanitization
   * Login panitia dengan session
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

* Source code CodeIgniter 4
* File SQL
* Dokumentasi instalasi
* Dokumentasi struktur data
* Tutorial penggunaan
* Template PDF kartu pendaftaran

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

8. Versi Diagram Visual (ASCII ERD)
+-----------------+         +--------------------+
|    pendaftar    |1------1 | alamat_pendaftar   |
+-----------------+         +--------------------+
| id_pendaftar PK |         | id_alamat PK       |
| nomor_pendaftaran|        | id_pendaftar FK     |
| jalur_pendaftaran|        | ... alamat fields   |
| nisn             |        +--------------------+
| ...              |
+------------------+

        |1
        |
        |1
+------------------+ 
|    data_ayah     |
+------------------+
| id_ayah PK       |
| id_pendaftar FK  |
| ...              |
+------------------+

        |1
        |
        |1
+------------------+
|    data_ibu      |
+------------------+
| id_ibu PK        |
| id_pendaftar FK  |
| ...              |
+------------------+

        |1
        |
        |1
+------------------+
|    data_wali     |
+------------------+
| id_wali PK       |
| id_pendaftar FK  |
| ...              |
+------------------+

        |1
        |
        |1
+------------------+
| bansos_pendaftar |
+------------------+
| id_bansos PK     |
| id_pendaftar FK  |
| ...              |
+------------------+

        |1
        |
        |1
+------------------+
|   asal_sekolah   |
+------------------+
| id_sekolah PK    |
| id_pendaftar FK  |
| ...              |
+------------------+

+------------------+
|  admin_panitia   |
+------------------+
| id_admin PK      |
| username         |
| password_hash    |
| role_panitia     |
+------------------+
