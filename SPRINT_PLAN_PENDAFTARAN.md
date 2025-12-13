# 🚀 SPRINT PLAN - ENHANCEMENT FORM PENDAFTARAN PSB
**Project:** WebPSB - Sistem Pendaftaran Santri Baru
**Version:** 2.0 Enhancement
**Created:** 2025-12-13
**Duration:** 5 Sprints (15-20 Hari Kerja)

---

## 📊 OVERVIEW

### Objective
Melengkapi form pendaftaran sesuai requirements lengkap dengan penambahan:
- ✅ Field-field yang belum ada
- ✅ Validasi yang lebih ketat (NISN 10 digit, NIK 16 digit)
- ✅ Dropdown options sesuai requirements
- ✅ **Upload KK (WAJIB)**
- ✅ **Upload Kartu Bansos (KIP/KKS/PKH) - Opsional**
- ✅ Input type yang sesuai (checkbox untuk imunisasi, radio untuk disabilitas)

### Current Coverage
- **Database:** 60-70% field sudah ada
- **Form Views:** 50-60% sesuai requirements
- **Validation:** 40% (perlu perbaikan)
- **File Upload:** 0% (belum ada)

### Target Coverage
- **Database:** 95% field lengkap
- **Form Views:** 100% sesuai requirements
- **Validation:** 95% (ketat & sesuai aturan)
- **File Upload:** 100% (KK wajib, Bansos opsional)

---

## 📅 SPRINT BREAKDOWN

---

## **SPRINT 1: FOUNDATION & QUICK WINS**
**Duration:** 3 Hari Kerja
**Goal:** Perbaikan validasi dan dropdown tanpa mengubah struktur database

### 🎯 Sprint Goals
- [x] Fix validasi NISN (10 digit exact)
- [x] Fix validasi NIK (16 digit exact) - Siswa, Ayah, Ibu, Wali
- [x] Fix format nomor pendaftaran (T-26270001 / M-26270001)
- [x] Update dropdown Cita-cita (9 options)
- [x] Update dropdown Hobi (6 options)
- [x] Update dropdown Pekerjaan Ayah/Ibu/Wali (16+ options)
- [x] Update dropdown Penghasilan (10 options sesuai requirements)
- [x] Update dropdown Pendidikan (9 options sesuai requirements)

### 📋 Tasks Detail

#### **Task 1.1: Fix Validasi NISN & NIK** (4 jam)
**File:** `app/Controllers/PendaftaranLengkap.php`

**Changes:**
```php
// Validasi NISN - harus exact 10 digit
'nisn' => [
    'rules' => 'required|numeric|exact_length[10]',
    'errors' => [
        'required' => 'NISN wajib diisi',
        'numeric' => 'NISN harus berupa angka',
        'exact_length' => 'NISN harus 10 digit'
    ]
]

// Validasi NIK Siswa - harus exact 16 digit
'nik' => [
    'rules' => 'required|numeric|exact_length[16]',
    'errors' => [
        'required' => 'NIK wajib diisi',
        'numeric' => 'NIK harus berupa angka',
        'exact_length' => 'NIK harus 16 digit'
    ]
]

// Validasi NIK Ayah/Ibu/Wali - sama
'nik_ayah' => 'required|numeric|exact_length[16]'
'nik_ibu' => 'required|numeric|exact_length[16]'
'nik_wali' => 'permit_empty|numeric|exact_length[16]'
```

**Testing:**
- Input NISN < 10 digit → harus reject
- Input NISN > 10 digit → harus reject
- Input NISN dengan huruf → harus reject
- Input NIK tidak 16 digit → harus reject

---

#### **Task 1.2: Fix Format Nomor Pendaftaran** (3 jam)
**File:** `app/Models/PendaftarModel.php`

**Requirements:**
- Format: `T-26270001` untuk Tsanawiyyah (MTs)
- Format: `M-26270001` untuk Muallimin (MA)
- Auto-increment 8 digit dengan leading zeros
- Prefix berdasarkan jalur pendaftaran

**Implementation:**
```php
protected function generateNomorPendaftaran($jalur)
{
    // Prefix based on jalur
    $prefix = ($jalur === 'TSANAWIYYAH') ? 'T' : 'M';

    // Get current year (2 digit)
    $year = date('y'); // 26 untuk 2026

    // Get academic year (27 untuk tahun ajaran 2026/2027)
    $academicYear = date('y') + 1;

    // Get last number for this jalur and year
    $lastRecord = $this->db->table('pendaftar')
        ->where('jalur_pendaftaran', $jalur)
        ->where('YEAR(tanggal_daftar)', date('Y'))
        ->orderBy('id_pendaftar', 'DESC')
        ->limit(1)
        ->get()
        ->getRow();

    if ($lastRecord && $lastRecord->nomor_pendaftaran) {
        // Extract number from last registration
        $lastNumber = (int) substr($lastRecord->nomor_pendaftaran, -4);
        $nextNumber = $lastNumber + 1;
    } else {
        $nextNumber = 1;
    }

    // Format: T-26270001 or M-26270001
    return sprintf('%s-%s%s%04d', $prefix, $year, $academicYear, $nextNumber);
}
```

**Testing:**
- Pendaftaran pertama Tsanawiyyah → T-26270001
- Pendaftaran kedua Tsanawiyyah → T-26270002
- Pendaftaran pertama Muallimin → M-26270001
- Test dengan 9999+ pendaftar → harus tetap increment

---

#### **Task 1.3: Update Dropdown Cita-cita** (1 jam)
**File:** `app/Views/pendaftaran/sections/section1_data_diri.php`

**Requirements:**
```html
<select class="form-select" id="cita_cita" name="cita_cita" required>
    <option value="">Pilih Cita-cita</option>
    <option value="PNS">PNS</option>
    <option value="TNI/Polri">TNI/Polri</option>
    <option value="Guru/Dosen">Guru/Dosen</option>
    <option value="Dokter">Dokter</option>
    <option value="Politikus">Politikus</option>
    <option value="Wiraswasta">Wiraswasta</option>
    <option value="Seniman/Artis">Seniman/Artis</option>
    <option value="Ilmuwan">Ilmuwan</option>
    <option value="Agamawan">Agamawan</option>
    <option value="Lainnya">Lainnya</option>
</select>
```

---

#### **Task 1.4: Update Dropdown Hobi** (1 jam)
**File:** `app/Views/pendaftaran/sections/section1_data_diri.php`

**Requirements:**
```html
<select class="form-select" id="hobi" name="hobi" required>
    <option value="">Pilih Hobi</option>
    <option value="Olah Raga">Olah Raga</option>
    <option value="Kesenian">Kesenian</option>
    <option value="Membaca">Membaca</option>
    <option value="Menulis">Menulis</option>
    <option value="Jalan-jalan">Jalan-jalan</option>
    <option value="Lainnya">Lainnya</option>
</select>
```

---

#### **Task 1.5: Update Dropdown Pekerjaan (Ayah/Ibu/Wali)** (2 jam)
**Files:**
- `app/Views/pendaftaran/sections/section3_ayah.php`
- `app/Views/pendaftaran/sections/section4_ibu.php`
- `app/Views/pendaftaran/sections/section5_wali.php`

**Requirements:**
```html
<select class="form-select" id="pekerjaan_ayah" name="pekerjaan_ayah" required>
    <option value="">Pilih Pekerjaan</option>
    <option value="Tidak Bekerja">Tidak Bekerja</option>
    <option value="Pensiun">Pensiun</option>
    <option value="PNS">PNS</option>
    <option value="TNI/Polri">TNI/Polri</option>
    <option value="Guru/Dosen">Guru/Dosen</option>
    <option value="Pegawai Swasta">Pegawai Swasta</option>
    <option value="Wiraswasta">Wiraswasta</option>
    <option value="Pengacara/Jaksa/Hakim/Notaris">Pengacara/Jaksa/Hakim/Notaris</option>
    <option value="Seniman/Pelukis/Artis/Sejenis">Seniman/Pelukis/Artis/Sejenis</option>
    <option value="Dokter/Bidan/Perawat">Dokter/Bidan/Perawat</option>
    <option value="Pilot/Pramugara">Pilot/Pramugara</option>
    <option value="Pedagang">Pedagang</option>
    <option value="Petani/Peternak">Petani/Peternak</option>
    <option value="Nelayan">Nelayan</option>
    <option value="Buruh (Tani/Pabrik/Bangunan)">Buruh (Tani/Pabrik/Bangunan)</option>
    <option value="Sopir/Masinis/Kondektur">Sopir/Masinis/Kondektur</option>
    <option value="Politikus">Politikus</option>
    <option value="Lainnya">Lainnya</option>
</select>
```

---

#### **Task 1.6: Update Dropdown Penghasilan** (1 jam)
**Files:**
- `app/Views/pendaftaran/sections/section3_ayah.php`
- `app/Views/pendaftaran/sections/section4_ibu.php`
- `app/Views/pendaftaran/sections/section5_wali.php`

**Requirements:**
```html
<select class="form-select" id="penghasilan_ayah" name="penghasilan_ayah" required>
    <option value="">Pilih Penghasilan</option>
    <option value="Dibawah 800.000">Dibawah Rp 800.000</option>
    <option value="800.001-1.200.000">Rp 800.001 - Rp 1.200.000</option>
    <option value="1.200.001-1.800.000">Rp 1.200.001 - Rp 1.800.000</option>
    <option value="1.800.001-2.500.000">Rp 1.800.001 - Rp 2.500.000</option>
    <option value="2.500.001-3.500.000">Rp 2.500.001 - Rp 3.500.000</option>
    <option value="3.500.001-4.800.000">Rp 3.500.001 - Rp 4.800.000</option>
    <option value="4.800.001-6.500.000">Rp 4.800.001 - Rp 6.500.000</option>
    <option value="6.500.001-10.000.000">Rp 6.500.001 - Rp 10.000.000</option>
    <option value="10.000.001-20.000.000">Rp 10.000.001 - Rp 20.000.000</option>
    <option value="Diatas 20.000.000">Diatas Rp 20.000.000</option>
</select>
```

---

#### **Task 1.7: Update Dropdown Pendidikan** (1 jam)
**Files:**
- `app/Views/pendaftaran/sections/section3_ayah.php`
- `app/Views/pendaftaran/sections/section4_ibu.php`
- `app/Views/pendaftaran/sections/section5_wali.php`

**Requirements:**
```html
<select class="form-select" id="pendidikan_ayah" name="pendidikan_ayah" required>
    <option value="">Pilih Pendidikan</option>
    <option value="Tidak Bersekolah">Tidak Bersekolah</option>
    <option value="SD/Sederajat">SD/Sederajat</option>
    <option value="SMP/Sederajat">SMP/Sederajat</option>
    <option value="SMA/Sederajat">SMA/Sederajat</option>
    <option value="D1/D2/D3">D1/D2/D3</option>
    <option value="S1/D4">S1/D4</option>
    <option value="S2">S2</option>
    <option value="S3">S3</option>
    <option value="Lainnya">Lainnya</option>
</select>
```

---

### ✅ Sprint 1 Deliverables
- ✓ Validasi NISN & NIK lebih ketat
- ✓ Nomor pendaftaran format baru (T-26270001 / M-26270001)
- ✓ 5 dropdown updated (Cita-cita, Hobi, Pekerjaan, Penghasilan, Pendidikan)
- ✓ Unit test untuk validasi
- ✓ Manual testing checklist

### 📝 Sprint 1 Testing Checklist
- [ ] NISN harus exact 10 digit (reject jika kurang/lebih)
- [ ] NIK harus exact 16 digit untuk semua (Siswa/Ayah/Ibu/Wali)
- [ ] Nomor pendaftaran auto-generate dengan format benar
- [ ] Dropdown Cita-cita menampilkan 10 options
- [ ] Dropdown Hobi menampilkan 6 options
- [ ] Dropdown Pekerjaan menampilkan 18 options
- [ ] Dropdown Penghasilan menampilkan 10 options sesuai requirements
- [ ] Dropdown Pendidikan menampilkan 9 options sesuai requirements

---

## **SPRINT 2: DATABASE ENHANCEMENT & INPUT TYPES**
**Duration:** 4 Hari Kerja
**Goal:** Tambah field baru ke database & ubah input types

### 🎯 Sprint Goals
- [x] Migration: Tambah 13 field baru ke 4 tabel existing
- [x] Update Model untuk handle field baru
- [x] Ubah Imunisasi menjadi checkbox (6 pilihan)
- [x] Ubah Kebutuhan Disabilitas menjadi checkbox (8 pilihan)
- [x] Tambah dropdown Kebutuhan Khusus (6 pilihan)
- [x] Tambah field yang belum ada di form

### 📋 Tasks Detail

#### **Task 2.1: Migration - Tambah Field Baru** (3 jam)
**File:** `app/Database/Migrations/2026-01-02-000001_AddMissingFieldsPendaftaran.php`

**Changes:**

```php
<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMissingFieldsPendaftaran extends Migration
{
    public function up()
    {
        // =====================================================
        // 1. Tabel pendaftar - Tambah 3 field
        // =====================================================
        $fields_pendaftar = [
            'yang_membiayai_sekolah' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'after'      => 'cita_cita'
            ],
            'minat_bakat' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'prestasi'
            ],
            'kebutuhan_khusus' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'after'      => 'kebutuhan_disabilitas'
            ],
        ];
        $this->forge->addColumn('pendaftar', $fields_pendaftar);

        // =====================================================
        // 2. Tabel alamat_pendaftar - Tambah 4 field
        // =====================================================
        $fields_alamat = [
            'rt_rw' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
                'after'      => 'alamat'
            ],
            'nama_kepala_keluarga' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => true,
                'after'      => 'nomor_kk'
            ],
            'tinggal_bersama' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'after'      => 'kode_pos'
            ],
        ];
        $this->forge->addColumn('alamat_pendaftar', $fields_alamat);

        // =====================================================
        // 3. Tabel asal_sekolah - Tambah 5 field
        // =====================================================
        $fields_sekolah = [
            'alamat_sekolah' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'lokasi_sekolah'
            ],
            'tahun_lulus' => [
                'type'       => 'YEAR',
                'constraint' => 4,
                'null'       => true,
                'after'      => 'npsn'
            ],
            'rata_rata_rapor' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'null'       => true,
                'after'      => 'tahun_lulus'
            ],
            'nilai_tka' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'null'       => true,
                'after'      => 'rata_rata_rapor'
            ],
            'sekolah_md' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
                'null'       => true,
                'after'      => 'nilai_tka'
            ],
        ];
        $this->forge->addColumn('asal_sekolah', $fields_sekolah);

        // =====================================================
        // 4. Tabel data_wali - Tambah 2 field
        // =====================================================
        $fields_wali = [
            'tempat_lahir_wali' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'after'      => 'nik_wali'
            ],
            'tanggal_lahir_wali' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'tempat_lahir_wali'
            ],
        ];
        $this->forge->addColumn('data_wali', $fields_wali);

        // Drop old column tahun_lahir_wali (will be replaced by tanggal_lahir_wali)
        $this->forge->dropColumn('data_wali', 'tahun_lahir_wali');
    }

    public function down()
    {
        // Rollback: Drop added columns
        $this->forge->dropColumn('pendaftar', ['yang_membiayai_sekolah', 'minat_bakat', 'kebutuhan_khusus']);
        $this->forge->dropColumn('alamat_pendaftar', ['rt_rw', 'nama_kepala_keluarga', 'tinggal_bersama']);
        $this->forge->dropColumn('asal_sekolah', ['alamat_sekolah', 'tahun_lulus', 'rata_rata_rapor', 'nilai_tka', 'sekolah_md']);
        $this->forge->dropColumn('data_wali', ['tempat_lahir_wali', 'tanggal_lahir_wali']);

        // Re-add tahun_lahir_wali
        $this->forge->addColumn('data_wali', [
            'tahun_lahir_wali' => [
                'type'       => 'YEAR',
                'constraint' => 4,
                'null'       => true,
            ]
        ]);
    }
}
```

**Testing:**
```bash
php spark migrate
php spark migrate:rollback  # Test rollback
php spark migrate  # Re-apply
```

---

#### **Task 2.2: Update Models untuk Field Baru** (2 jam)

**File: `app/Models/PendaftarModel.php`**
```php
protected $allowedFields = [
    'jalur_pendaftaran', 'nisn', 'nik', 'nama_lengkap', 'jenis_kelamin',
    'tempat_lahir', 'tanggal_lahir', 'status_keluarga', 'anak_ke',
    'jumlah_saudara', 'hobi', 'cita_cita', 'yang_membiayai_sekolah',  // NEW
    'pernah_paud', 'pernah_tk', 'kebutuhan_disabilitas', 'kebutuhan_khusus',  // NEW
    'imunisasi', 'no_hp', 'ukuran_baju', 'prestasi', 'minat_bakat'  // NEW
];
```

**File: `app/Models/AlamatModel.php`**
```php
protected $allowedFields = [
    'id_pendaftar', 'nomor_kk', 'nama_kepala_keluarga',  // NEW
    'jenis_tempat_tinggal', 'alamat', 'rt_rw',  // NEW
    'desa', 'kecamatan', 'kabupaten', 'provinsi', 'kode_pos',
    'jarak_ke_sekolah', 'waktu_tempuh', 'transportasi',
    'tinggal_bersama',  // NEW
    'email', 'media_sosial'
];
```

**File: `app/Models/SekolahModel.php`**
```php
protected $allowedFields = [
    'id_pendaftar', 'nama_asal_sekolah', 'jenjang_sekolah',
    'status_sekolah', 'npsn', 'lokasi_sekolah',
    'alamat_sekolah',  // NEW
    'tahun_lulus', 'rata_rata_rapor', 'nilai_tka', 'sekolah_md',  // NEW
    'asal_jenjang'
];
```

**File: `app/Models/WaliModel.php`**
```php
protected $allowedFields = [
    'id_pendaftar', 'nama_wali', 'nik_wali',
    'tempat_lahir_wali', 'tanggal_lahir_wali',  // NEW (replace tahun_lahir_wali)
    'pendidikan_wali', 'pekerjaan_wali', 'penghasilan_wali', 'hp_wali'
];
```

---

#### **Task 2.3: Update Form - Imunisasi (Checkbox)** (2 jam)
**File:** `app/Views/pendaftaran/sections/section1_data_diri.php`

**Change from:**
```html
<input type="text" class="form-control" id="imunisasi" name="imunisasi">
```

**To:**
```html
<div class="col-md-12 mb-3">
    <label class="form-label required">Imunisasi</label>
    <div class="form-text mb-2">Centang jenis imunisasi yang sudah diterima</div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="imunisasi_hepatitis"
                       name="imunisasi[]" value="Hepatitis B">
                <label class="form-check-label" for="imunisasi_hepatitis">
                    Hepatitis B
                </label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="imunisasi_bcg"
                       name="imunisasi[]" value="BCG">
                <label class="form-check-label" for="imunisasi_bcg">
                    BCG
                </label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="imunisasi_dpt"
                       name="imunisasi[]" value="DPT">
                <label class="form-check-label" for="imunisasi_dpt">
                    DPT
                </label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="imunisasi_polio"
                       name="imunisasi[]" value="Polio">
                <label class="form-check-label" for="imunisasi_polio">
                    Polio
                </label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="imunisasi_campak"
                       name="imunisasi[]" value="Campak">
                <label class="form-check-label" for="imunisasi_campak">
                    Campak
                </label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="imunisasi_covid"
                       name="imunisasi[]" value="Covid">
                <label class="form-check-label" for="imunisasi_covid">
                    Covid-19
                </label>
            </div>
        </div>
    </div>
</div>
```

**Controller handling:**
```php
// In controller
$imunisasi = $this->request->getPost('imunisasi');
$imunisasiString = $imunisasi ? implode(', ', $imunisasi) : null;
```

---

#### **Task 2.4: Update Form - Kebutuhan Disabilitas (Checkbox)** (2 jam)
**File:** `app/Views/pendaftaran/sections/section1_data_diri.php`

**Requirements:**
```html
<div class="col-md-12 mb-3">
    <label class="form-label required">Kebutuhan Disabilitas</label>
    <div class="form-text mb-2">Centang jika memiliki kebutuhan khusus disabilitas</div>

    <div class="row">
        <div class="col-md-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="disabilitas_tidak"
                       name="kebutuhan_disabilitas[]" value="Tidak Ada">
                <label class="form-check-label" for="disabilitas_tidak">
                    Tidak Ada
                </label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="disabilitas_netra"
                       name="kebutuhan_disabilitas[]" value="Tuna Netra">
                <label class="form-check-label" for="disabilitas_netra">
                    Tuna Netra
                </label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="disabilitas_rungu"
                       name="kebutuhan_disabilitas[]" value="Tuna Rungu">
                <label class="form-check-label" for="disabilitas_rungu">
                    Tuna Rungu
                </label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="disabilitas_daksa"
                       name="kebutuhan_disabilitas[]" value="Tuna Daksa">
                <label class="form-check-label" for="disabilitas_daksa">
                    Tuna Daksa
                </label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="disabilitas_grahita"
                       name="kebutuhan_disabilitas[]" value="Tuna Grahita">
                <label class="form-check-label" for="disabilitas_grahita">
                    Tuna Grahita
                </label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="disabilitas_laras"
                       name="kebutuhan_disabilitas[]" value="Tuna Laras">
                <label class="form-check-label" for="disabilitas_laras">
                    Tuna Laras
                </label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="disabilitas_wicara"
                       name="kebutuhan_disabilitas[]" value="Tuna Wicara">
                <label class="form-check-label" for="disabilitas_wicara">
                    Tuna Wicara
                </label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="disabilitas_lainnya"
                       name="kebutuhan_disabilitas[]" value="Lainnya">
                <label class="form-check-label" for="disabilitas_lainnya">
                    Lainnya
                </label>
            </div>
        </div>
    </div>
</div>
```

---

#### **Task 2.5: Tambah Dropdown Kebutuhan Khusus** (1 jam)
**File:** `app/Views/pendaftaran/sections/section1_data_diri.php`

**Add new field:**
```html
<div class="col-md-6 mb-3">
    <label for="kebutuhan_khusus" class="form-label required">Kebutuhan Khusus</label>
    <select class="form-select" id="kebutuhan_khusus" name="kebutuhan_khusus" required>
        <option value="">Pilih Kebutuhan Khusus</option>
        <option value="Tidak ada">Tidak ada</option>
        <option value="Lamban Belajar">Lamban Belajar</option>
        <option value="Kesulitan belajar Spesifik">Kesulitan belajar Spesifik</option>
        <option value="Gangguan Komunikasi">Gangguan Komunikasi</option>
        <option value="Berbakat/memiliki kemampuan dan kecerdasan luar biasa">Berbakat/memiliki kemampuan dan kecerdasan luar biasa</option>
        <option value="Lainnya">Lainnya</option>
    </select>
</div>
```

---

#### **Task 2.6: Tambah Field Baru ke Form** (4 jam)

**File: `app/Views/pendaftaran/sections/section1_data_diri.php`**

Add fields:
1. **Yang Membiayai Sekolah** (dropdown)
```html
<div class="col-md-6 mb-3">
    <label for="yang_membiayai_sekolah" class="form-label required">Yang Membiayai Sekolah</label>
    <select class="form-select" id="yang_membiayai_sekolah" name="yang_membiayai_sekolah" required>
        <option value="">Pilih</option>
        <option value="Orang Tua">Orang Tua</option>
        <option value="Wali/Orang Tua Asuh">Wali/Orang Tua Asuh</option>
        <option value="Tanggungan Sendiri">Tanggungan Sendiri</option>
        <option value="Lainnya">Lainnya</option>
    </select>
</div>
```

2. **Minat & Bakat** (textarea)
```html
<div class="col-md-12 mb-3">
    <label for="minat_bakat" class="form-label">Minat & Bakat</label>
    <textarea class="form-control" id="minat_bakat" name="minat_bakat" rows="3"
        placeholder="Tuliskan minat dan bakat yang dimiliki"><?= old('minat_bakat') ?></textarea>
</div>
```

**File: `app/Views/pendaftaran/sections/section2_alamat.php`**

Add fields:
1. **RT/RW**
```html
<div class="col-md-6 mb-3">
    <label for="rt_rw" class="form-label required">RT/RW</label>
    <input type="text" class="form-control" id="rt_rw" name="rt_rw"
        value="<?= old('rt_rw') ?>" placeholder="Contoh: 001/002" required>
</div>
```

2. **Nama Kepala Keluarga**
```html
<div class="col-md-6 mb-3">
    <label for="nama_kepala_keluarga" class="form-label required">Nama Kepala Keluarga</label>
    <input type="text" class="form-control" id="nama_kepala_keluarga" name="nama_kepala_keluarga"
        value="<?= old('nama_kepala_keluarga') ?>" placeholder="Sesuai Kartu Keluarga" required>
</div>
```

3. **Calon Siswa Tinggal Bersama**
```html
<div class="col-md-6 mb-3">
    <label for="tinggal_bersama" class="form-label required">Calon Siswa Tinggal Bersama</label>
    <select class="form-select" id="tinggal_bersama" name="tinggal_bersama" required>
        <option value="">Pilih</option>
        <option value="Tinggal dengan ayah kandung">Tinggal dengan ayah kandung</option>
        <option value="Tinggal dengan Ibu Kandung">Tinggal dengan Ibu Kandung</option>
        <option value="Tinggal dengan Wali">Tinggal dengan Wali</option>
        <option value="Ikut Saudara/Kerabat">Ikut Saudara/Kerabat</option>
        <option value="Kontrak/Kost">Kontrak/Kost</option>
        <option value="Panti Asuhan">Panti Asuhan</option>
        <option value="Rumah Singgah">Rumah Singgah</option>
        <option value="Lainnya">Lainnya</option>
    </select>
</div>
```

**File: `app/Views/pendaftaran/sections/section7_sekolah.php`**

Add fields:
1. **Alamat Sekolah Asal**
```html
<div class="col-md-12 mb-3">
    <label for="alamat_sekolah" class="form-label required">Alamat Sekolah Asal</label>
    <textarea class="form-control" id="alamat_sekolah" name="alamat_sekolah" rows="2"
        placeholder="Alamat lengkap sekolah asal" required><?= old('alamat_sekolah') ?></textarea>
</div>
```

2. **Tahun Lulus**
```html
<div class="col-md-6 mb-3">
    <label for="tahun_lulus" class="form-label">Tahun Lulus</label>
    <input type="number" class="form-control" id="tahun_lulus" name="tahun_lulus"
        value="<?= old('tahun_lulus') ?>" placeholder="Contoh: 2026" min="2000" max="2030">
</div>
```

3. **Rata-rata Nilai Raport**
```html
<div class="col-md-6 mb-3">
    <label for="rata_rata_rapor" class="form-label">Rata-rata Nilai Raport Tahun Berjalan</label>
    <input type="number" class="form-control" id="rata_rata_rapor" name="rata_rata_rapor"
        value="<?= old('rata_rata_rapor') ?>" placeholder="Contoh: 85.5" step="0.01" min="0" max="100">
</div>
```

4. **Nilai TKA**
```html
<div class="col-md-6 mb-3">
    <label for="nilai_tka" class="form-label">Nilai TKA (Kalau sudah Melaksanakan)</label>
    <input type="number" class="form-control" id="nilai_tka" name="nilai_tka"
        value="<?= old('nilai_tka') ?>" placeholder="Kosongkan jika belum TKA" step="0.01" min="0" max="100">
</div>
```

5. **Sekolah MD (Madrasah Diniyah)**
```html
<div class="col-md-6 mb-3">
    <label for="sekolah_md" class="form-label">Sekolah MD (Madrasah Diniyah sore)</label>
    <input type="text" class="form-control" id="sekolah_md" name="sekolah_md"
        value="<?= old('sekolah_md') ?>" placeholder="Nama Madrasah Diniyah">
</div>
```

**File: `app/Views/pendaftaran/sections/section5_wali.php`**

Update fields:
1. **Tempat Lahir Wali** (tambah field baru)
```html
<div class="col-md-6 mb-3">
    <label for="tempat_lahir_wali" class="form-label">Tempat Lahir Wali</label>
    <input type="text" class="form-control" id="tempat_lahir_wali" name="tempat_lahir_wali"
        value="<?= old('tempat_lahir_wali') ?>" placeholder="Masukkan tempat lahir wali">
</div>
```

2. **Tanggal Lahir Wali** (ganti dari tahun_lahir_wali)
```html
<div class="col-md-6 mb-3">
    <label for="tanggal_lahir_wali" class="form-label">Tanggal Lahir Wali</label>
    <input type="date" class="form-control" id="tanggal_lahir_wali" name="tanggal_lahir_wali"
        value="<?= old('tanggal_lahir_wali') ?>">
</div>
```

---

### ✅ Sprint 2 Deliverables
- ✓ Migration file yang menambah 13 field baru
- ✓ 4 Model updated (allowedFields)
- ✓ Imunisasi berubah menjadi checkbox (6 pilihan)
- ✓ Kebutuhan Disabilitas berubah menjadi checkbox (8 pilihan)
- ✓ Kebutuhan Khusus menjadi dropdown (6 pilihan)
- ✓ 11 field baru ditambahkan ke form views
- ✓ Migration rollback test berhasil

### 📝 Sprint 2 Testing Checklist
- [ ] Migration berhasil (php spark migrate)
- [ ] Rollback migration berhasil
- [ ] Semua model dapat save data dengan field baru
- [ ] Checkbox imunisasi dapat select multiple
- [ ] Checkbox disabilitas dapat select multiple
- [ ] Dropdown kebutuhan khusus tampil dengan benar
- [ ] Field baru di form dapat di-input
- [ ] Data tersimpan ke database dengan benar

---

## **SPRINT 3: FILE UPLOAD SYSTEM**
**Duration:** 5 Hari Kerja
**Goal:** Implementasi upload KK (wajib) & Kartu Bansos (opsional)

### 🎯 Sprint Goals
- [x] Create migration untuk tabel `dokumen_pendaftar`
- [x] Create Model `DokumenModel`
- [x] Implementasi file upload handler (validation, storage)
- [x] Update form dengan file upload UI
- [x] Upload KK (WAJIB - max 2MB, JPG/PNG/PDF)
- [x] Upload KIP (opsional - max 2MB, JPG/PNG/PDF)
- [x] Upload KKS/PKH (opsional - max 2MB, JPG/PNG/PDF)
- [x] File preview functionality
- [x] File deletion handling (soft delete)

### 📋 Tasks Detail

#### **Task 3.1: Migration - Tabel dokumen_pendaftar** (2 jam)
**File:** `app/Database/Migrations/2026-01-03-000001_CreateDokumenPendaftarTable.php`

```php
<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDokumenPendaftarTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_dokumen' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_pendaftar' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'jenis_dokumen' => [
                'type'       => 'ENUM',
                'constraint' => ['KK', 'KIP', 'KKS_PKH'],
                'null'       => false,
            ],
            'nama_file' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'nama_asli_file' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'path_file' => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
                'null'       => false,
            ],
            'ukuran_file' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
                'comment'    => 'Size in bytes',
            ],
            'mime_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'uploaded_at' => [
                'type'    => 'TIMESTAMP',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
            ],
            'deleted_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id_dokumen', true);
        $this->forge->addKey('id_pendaftar');
        $this->forge->addKey('jenis_dokumen');

        // Foreign Key
        $this->forge->addForeignKey('id_pendaftar', 'pendaftar', 'id_pendaftar', 'CASCADE', 'CASCADE');

        // Unique constraint: satu pendaftar hanya boleh upload 1 file per jenis
        $this->forge->addUniqueKey(['id_pendaftar', 'jenis_dokumen', 'deleted_at'], 'unique_dokumen_per_pendaftar');

        $this->forge->createTable('dokumen_pendaftar', true);
    }

    public function down()
    {
        $this->forge->dropTable('dokumen_pendaftar', true);
    }
}
```

---

#### **Task 3.2: Create DokumenModel** (2 jam)
**File:** `app/Models/DokumenModel.php`

```php
<?php

namespace App\Models;

use CodeIgniter\Model;

class DokumenModel extends Model
{
    protected $table            = 'dokumen_pendaftar';
    protected $primaryKey       = 'id_dokumen';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_pendaftar',
        'jenis_dokumen',
        'nama_file',
        'nama_asli_file',
        'path_file',
        'ukuran_file',
        'mime_type'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'uploaded_at';
    protected $updatedField  = '';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'id_pendaftar'  => 'required|integer',
        'jenis_dokumen' => 'required|in_list[KK,KIP,KKS_PKH]',
        'nama_file'     => 'required|max_length[255]',
        'path_file'     => 'required|max_length[500]',
        'ukuran_file'   => 'required|integer',
        'mime_type'     => 'required|max_length[100]',
    ];

    protected $validationMessages = [
        'jenis_dokumen' => [
            'in_list' => 'Jenis dokumen tidak valid. Harus KK, KIP, atau KKS_PKH'
        ]
    ];

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = ['deletePhysicalFile'];
    protected $afterDelete    = [];

    /**
     * Get dokumen by pendaftar ID and jenis
     */
    public function getDokumenByPendaftarAndJenis($idPendaftar, $jenisDokumen)
    {
        return $this->where('id_pendaftar', $idPendaftar)
                    ->where('jenis_dokumen', $jenisDokumen)
                    ->first();
    }

    /**
     * Get all dokumen by pendaftar ID
     */
    public function getAllDokumenByPendaftar($idPendaftar)
    {
        return $this->where('id_pendaftar', $idPendaftar)->findAll();
    }

    /**
     * Delete physical file before soft delete
     */
    protected function deletePhysicalFile(array $data)
    {
        if (isset($data['id']) && is_array($data['id'])) {
            foreach ($data['id'] as $id) {
                $dokumen = $this->find($id);
                if ($dokumen && file_exists($dokumen['path_file'])) {
                    unlink($dokumen['path_file']);
                }
            }
        }
        return $data;
    }

    /**
     * Replace existing dokumen (delete old, insert new)
     */
    public function replaceDokumen($idPendaftar, $jenisDokumen, $fileData)
    {
        // Check if dokumen already exists
        $existing = $this->getDokumenByPendaftarAndJenis($idPendaftar, $jenisDokumen);

        if ($existing) {
            // Delete old file
            $this->delete($existing['id_dokumen']);
        }

        // Insert new dokumen
        $data = [
            'id_pendaftar'   => $idPendaftar,
            'jenis_dokumen'  => $jenisDokumen,
            'nama_file'      => $fileData['nama_file'],
            'nama_asli_file' => $fileData['nama_asli_file'],
            'path_file'      => $fileData['path_file'],
            'ukuran_file'    => $fileData['ukuran_file'],
            'mime_type'      => $fileData['mime_type'],
        ];

        return $this->insert($data);
    }
}
```

---

#### **Task 3.3: Create File Upload Helper** (3 jam)
**File:** `app/Helpers/file_upload_helper.php`

```php
<?php

if (!function_exists('upload_dokumen_pendaftar')) {
    /**
     * Upload dokumen pendaftar dengan validasi
     *
     * @param object $file CodeIgniter File object
     * @param int $idPendaftar ID pendaftar
     * @param string $jenisDokumen Jenis dokumen (KK, KIP, KKS_PKH)
     * @return array ['success' => bool, 'data' => array|null, 'error' => string|null]
     */
    function upload_dokumen_pendaftar($file, $idPendaftar, $jenisDokumen)
    {
        // Validation
        if (!$file->isValid()) {
            return [
                'success' => false,
                'data'    => null,
                'error'   => 'File tidak valid atau rusak'
            ];
        }

        // Check file size (max 2MB)
        $maxSize = 2 * 1024 * 1024; // 2MB in bytes
        if ($file->getSize() > $maxSize) {
            return [
                'success' => false,
                'data'    => null,
                'error'   => 'Ukuran file maksimal 2MB'
            ];
        }

        // Check file type
        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
        $mimeType = $file->getMimeType();

        if (!in_array($mimeType, $allowedMimes)) {
            return [
                'success' => false,
                'data'    => null,
                'error'   => 'Format file harus JPG, PNG, atau PDF'
            ];
        }

        // Generate unique filename
        $originalName = $file->getClientName();
        $extension = $file->getClientExtension();
        $newName = $jenisDokumen . '_' . $idPendaftar . '_' . time() . '.' . $extension;

        // Upload path
        $uploadPath = WRITEPATH . 'uploads/dokumen_pendaftar/';

        // Create directory if not exists
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Move file
        try {
            if (!$file->move($uploadPath, $newName)) {
                return [
                    'success' => false,
                    'data'    => null,
                    'error'   => 'Gagal mengupload file'
                ];
            }

            // Return file data
            return [
                'success' => true,
                'data' => [
                    'nama_file'      => $newName,
                    'nama_asli_file' => $originalName,
                    'path_file'      => $uploadPath . $newName,
                    'ukuran_file'    => $file->getSize(),
                    'mime_type'      => $mimeType,
                ],
                'error' => null
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'data'    => null,
                'error'   => 'Error: ' . $e->getMessage()
            ];
        }
    }
}

if (!function_exists('get_dokumen_url')) {
    /**
     * Get public URL for dokumen
     *
     * @param string $namaFile Nama file
     * @return string URL
     */
    function get_dokumen_url($namaFile)
    {
        return base_url('dokumen/view/' . $namaFile);
    }
}

if (!function_exists('format_file_size')) {
    /**
     * Format file size to human readable
     *
     * @param int $bytes Size in bytes
     * @return string Formatted size
     */
    function format_file_size($bytes)
    {
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
}
```

**Register helper in `app/Config/Autoload.php`:**
```php
public $helpers = ['security', 'file_upload'];
```

---

#### **Task 3.4: Update Controller untuk Handle Upload** (4 jam)
**File:** `app/Controllers/PendaftaranLengkap.php`

**Add to constructor:**
```php
use App\Models\DokumenModel;

protected $dokumenModel;

public function __construct()
{
    // ... existing code ...
    $this->dokumenModel = new DokumenModel();
    helper('file_upload');
}
```

**Add validation rules for files:**
```php
private function getValidationRules()
{
    return [
        // ... existing rules ...

        // File uploads
        'file_kk' => [
            'rules' => 'uploaded[file_kk]|max_size[file_kk,2048]|ext_in[file_kk,jpg,jpeg,png,pdf]',
            'errors' => [
                'uploaded' => 'Upload Kartu Keluarga wajib diisi',
                'max_size' => 'Ukuran file KK maksimal 2MB',
                'ext_in' => 'Format file KK harus JPG, PNG, atau PDF'
            ]
        ],
        'file_kip' => [
            'rules' => 'permit_empty|max_size[file_kip,2048]|ext_in[file_kip,jpg,jpeg,png,pdf]',
            'errors' => [
                'max_size' => 'Ukuran file KIP maksimal 2MB',
                'ext_in' => 'Format file KIP harus JPG, PNG, atau PDF'
            ]
        ],
        'file_kks_pkh' => [
            'rules' => 'permit_empty|max_size[file_kks_pkh,2048]|ext_in[file_kks_pkh,jpg,jpeg,png,pdf]',
            'errors' => [
                'max_size' => 'Ukuran file KKS/PKH maksimal 2MB',
                'ext_in' => 'Format file KKS/PKH harus JPG, PNG, atau PDF'
            ]
        ],
    ];
}
```

**Add file upload processing in submitPendaftaran:**
```php
public function submitPendaftaran($jalur = null)
{
    // ... existing validation code ...

    // Start transaction
    $this->db->transStart();

    try {
        // ... existing pendaftar insert code ...

        $idPendaftar = $this->pendaftarModel->insert($pendaftarData);

        // ... existing alamat, ayah, ibu, wali, bansos, sekolah inserts ...

        // =====================================================
        // TABLE 8: dokumen_pendaftar (File Uploads)
        // =====================================================

        // Upload KK (WAJIB)
        $fileKK = $this->request->getFile('file_kk');
        if ($fileKK && $fileKK->isValid()) {
            $uploadResult = upload_dokumen_pendaftar($fileKK, $idPendaftar, 'KK');

            if (!$uploadResult['success']) {
                throw new \Exception('Gagal upload Kartu Keluarga: ' . $uploadResult['error']);
            }

            if (!$this->dokumenModel->insert([
                'id_pendaftar' => $idPendaftar,
                'jenis_dokumen' => 'KK',
                ...$uploadResult['data']
            ])) {
                throw new \Exception('Gagal menyimpan data Kartu Keluarga');
            }

            $this->logPendaftaran('info', 'File KK uploaded', [
                'id_pendaftar' => $idPendaftar,
                'filename' => $uploadResult['data']['nama_file']
            ]);
        }

        // Upload KIP (OPSIONAL)
        $fileKIP = $this->request->getFile('file_kip');
        if ($fileKIP && $fileKIP->isValid()) {
            $uploadResult = upload_dokumen_pendaftar($fileKIP, $idPendaftar, 'KIP');

            if ($uploadResult['success']) {
                $this->dokumenModel->insert([
                    'id_pendaftar' => $idPendaftar,
                    'jenis_dokumen' => 'KIP',
                    ...$uploadResult['data']
                ]);

                $this->logPendaftaran('info', 'File KIP uploaded', [
                    'id_pendaftar' => $idPendaftar,
                    'filename' => $uploadResult['data']['nama_file']
                ]);
            }
        }

        // Upload KKS/PKH (OPSIONAL)
        $fileKKS = $this->request->getFile('file_kks_pkh');
        if ($fileKKS && $fileKKS->isValid()) {
            $uploadResult = upload_dokumen_pendaftar($fileKKS, $idPendaftar, 'KKS_PKH');

            if ($uploadResult['success']) {
                $this->dokumenModel->insert([
                    'id_pendaftar' => $idPendaftar,
                    'jenis_dokumen' => 'KKS_PKH',
                    ...$uploadResult['data']
                ]);

                $this->logPendaftaran('info', 'File KKS/PKH uploaded', [
                    'id_pendaftar' => $idPendaftar,
                    'filename' => $uploadResult['data']['nama_file']
                ]);
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            throw new \Exception('Transaction failed');
        }

        // ... success redirect ...

    } catch (\Exception $e) {
        $db->transRollback();
        // ... error handling ...
    }
}
```

---

#### **Task 3.5: Create Dokumen Viewer Controller** (2 jam)
**File:** `app/Controllers/Dokumen.php`

```php
<?php

namespace App\Controllers;

use App\Models\DokumenModel;

class Dokumen extends BaseController
{
    protected $dokumenModel;

    public function __construct()
    {
        $this->dokumenModel = new DokumenModel();
    }

    /**
     * View dokumen file
     */
    public function view($namaFile)
    {
        $dokumen = $this->dokumenModel->where('nama_file', $namaFile)->first();

        if (!$dokumen) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Dokumen tidak ditemukan');
        }

        $filePath = $dokumen['path_file'];

        if (!file_exists($filePath)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('File tidak ditemukan di server');
        }

        // Set headers
        $this->response->setContentType($dokumen['mime_type']);
        $this->response->setHeader('Content-Disposition', 'inline; filename="' . $dokumen['nama_asli_file'] . '"');
        $this->response->setBody(file_get_contents($filePath));

        return $this->response;
    }

    /**
     * Download dokumen file
     */
    public function download($namaFile)
    {
        $dokumen = $this->dokumenModel->where('nama_file', $namaFile)->first();

        if (!$dokumen) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Dokumen tidak ditemukan');
        }

        return $this->response->download($dokumen['path_file'], null)->setFileName($dokumen['nama_asli_file']);
    }
}
```

**Add routes in `app/Config/Routes.php`:**
```php
$routes->get('dokumen/view/(:segment)', 'Dokumen::view/$1');
$routes->get('dokumen/download/(:segment)', 'Dokumen::download/$1');
```

---

#### **Task 3.6: Update Form dengan File Upload UI** (4 jam)

**File:** `app/Views/pendaftaran/sections/section2_alamat.php`

**Add after "Nomor KK" field:**
```html
<!-- Upload Kartu Keluarga -->
<div class="col-md-12 mb-3">
    <label for="file_kk" class="form-label required">Upload Kartu Keluarga (KK)</label>
    <input type="file" class="form-control" id="file_kk" name="file_kk"
           accept=".jpg,.jpeg,.png,.pdf" required>
    <div class="form-text">
        <i class="icofont-info-circle"></i>
        Format: JPG, PNG, atau PDF. Maksimal 2MB.
        <strong class="text-danger">WAJIB</strong>
    </div>

    <!-- Preview area -->
    <div id="preview_kk" class="mt-2" style="display: none;">
        <div class="alert alert-success d-flex align-items-center">
            <i class="icofont-check-circled me-2"></i>
            <div>
                <strong>File dipilih:</strong> <span id="filename_kk"></span>
                <br>
                <small>Ukuran: <span id="filesize_kk"></span></small>
            </div>
        </div>
    </div>
</div>
```

**File:** `app/Views/pendaftaran/sections/section6_bansos.php`

**Add file upload fields:**
```html
<!-- Upload KIP -->
<div class="col-md-12 mb-3">
    <label for="file_kip" class="form-label">Upload Kartu Indonesia Pintar (KIP)</label>
    <input type="file" class="form-control" id="file_kip" name="file_kip"
           accept=".jpg,.jpeg,.png,.pdf">
    <div class="form-text">
        <i class="icofont-info-circle"></i>
        Format: JPG, PNG, atau PDF. Maksimal 2MB.
        <span class="text-muted">(Opsional - hanya jika memiliki)</span>
    </div>

    <!-- Preview area -->
    <div id="preview_kip" class="mt-2" style="display: none;">
        <div class="alert alert-info d-flex align-items-center">
            <i class="icofont-check-circled me-2"></i>
            <div>
                <strong>File dipilih:</strong> <span id="filename_kip"></span>
                <br>
                <small>Ukuran: <span id="filesize_kip"></span></small>
            </div>
        </div>
    </div>
</div>

<!-- Upload KKS/PKH -->
<div class="col-md-12 mb-3">
    <label for="file_kks_pkh" class="form-label">Upload Kartu KKS/PKH</label>
    <input type="file" class="form-control" id="file_kks_pkh" name="file_kks_pkh"
           accept=".jpg,.jpeg,.png,.pdf">
    <div class="form-text">
        <i class="icofont-info-circle"></i>
        Format: JPG, PNG, atau PDF. Maksimal 2MB.
        <span class="text-muted">(Opsional - hanya jika memiliki)</span>
    </div>

    <!-- Preview area -->
    <div id="preview_kks_pkh" class="mt-2" style="display: none;">
        <div class="alert alert-info d-flex align-items-center">
            <i class="icofont-check-circled me-2"></i>
            <div>
                <strong>File dipilih:</strong> <span id="filename_kks_pkh"></span>
                <br>
                <small>Ukuran: <span id="filesize_kks_pkh"></span></small>
            </div>
        </div>
    </div>
</div>
```

---

#### **Task 3.7: Add JavaScript for File Preview** (2 jam)

**File:** `app/Views/pendaftaran/form_lengkap.php`

**Add at the bottom before `</body>`:**
```html
<script>
// File upload preview functionality
document.addEventListener('DOMContentLoaded', function() {

    // Helper function to format file size
    function formatFileSize(bytes) {
        if (bytes >= 1048576) {
            return (bytes / 1048576).toFixed(2) + ' MB';
        } else if (bytes >= 1024) {
            return (bytes / 1024).toFixed(2) + ' KB';
        } else {
            return bytes + ' bytes';
        }
    }

    // Helper function to validate file
    function validateFile(file, maxSize = 2097152) { // 2MB default
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];

        if (!allowedTypes.includes(file.type)) {
            return {
                valid: false,
                error: 'Format file harus JPG, PNG, atau PDF'
            };
        }

        if (file.size > maxSize) {
            return {
                valid: false,
                error: 'Ukuran file maksimal 2MB'
            };
        }

        return { valid: true };
    }

    // Setup file input preview
    function setupFilePreview(inputId, previewId, filenameId, filesizeId) {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);
        const filenameSpan = document.getElementById(filenameId);
        const filesizeSpan = document.getElementById(filesizeId);

        if (!input) return;

        input.addEventListener('change', function(e) {
            const file = e.target.files[0];

            if (!file) {
                preview.style.display = 'none';
                return;
            }

            // Validate file
            const validation = validateFile(file);

            if (!validation.valid) {
                alert(validation.error);
                input.value = '';
                preview.style.display = 'none';
                return;
            }

            // Show preview
            filenameSpan.textContent = file.name;
            filesizeSpan.textContent = formatFileSize(file.size);
            preview.style.display = 'block';
        });
    }

    // Setup previews for all file inputs
    setupFilePreview('file_kk', 'preview_kk', 'filename_kk', 'filesize_kk');
    setupFilePreview('file_kip', 'preview_kip', 'filename_kip', 'filesize_kip');
    setupFilePreview('file_kks_pkh', 'preview_kks_pkh', 'filename_kks_pkh', 'filesize_kks_pkh');
});
</script>
```

---

#### **Task 3.8: Update form tag to support multipart** (10 menit)

**File:** `app/Views/pendaftaran/form_lengkap.php`

**Update form tag:**
```html
<form id="pendaftaranForm" method="post"
      action="<?= base_url('pendaftaran-lengkap/submit/' . strtolower($jalur)) ?>"
      enctype="multipart/form-data">
    <?= csrf_field() ?>
    <!-- ... rest of form ... -->
</form>
```

---

### ✅ Sprint 3 Deliverables
- ✓ Migration untuk tabel `dokumen_pendaftar`
- ✓ Model `DokumenModel` dengan soft delete
- ✓ Helper `file_upload_helper` dengan validasi
- ✓ Controller `Dokumen` untuk view/download
- ✓ Upload handler di `PendaftaranLengkap` controller
- ✓ Form UI dengan file input & preview
- ✓ JavaScript untuk client-side validation & preview
- ✓ Routes untuk akses dokumen

### 📝 Sprint 3 Testing Checklist
- [ ] Migration dokumen_pendaftar berhasil
- [ ] Upload KK (wajib) berfungsi
- [ ] Reject file > 2MB
- [ ] Reject file selain JPG/PNG/PDF
- [ ] Upload KIP (opsional) berfungsi
- [ ] Upload KKS/PKH (opsional) berfungsi
- [ ] File preview tampil dengan benar
- [ ] File tersimpan di `writable/uploads/dokumen_pendaftar/`
- [ ] Data file tersimpan di database
- [ ] View dokumen via URL berfungsi
- [ ] Download dokumen berfungsi
- [ ] Soft delete file berfungsi
- [ ] Transaction rollback jika upload gagal

---

## **SPRINT 4: INTEGRATION & COMPREHENSIVE TESTING**
**Duration:** 3 Hari Kerja
**Goal:** Integration testing, bug fixes, dan optimasi

### 🎯 Sprint Goals
- [x] End-to-end testing semua form sections
- [x] Validasi integration (frontend + backend)
- [x] Bug fixing
- [x] Performance testing
- [x] Security audit
- [x] Cross-browser testing
- [x] Mobile responsive testing

### 📋 Tasks Detail

#### **Task 4.1: End-to-End Testing** (1 hari)

**Test Scenarios:**

1. **Happy Path - Tsanawiyyah**
   - [ ] Isi semua field required
   - [ ] Upload KK (wajib)
   - [ ] Upload KIP & KKS (opsional)
   - [ ] Submit form
   - [ ] Verify data tersimpan di 7 tabel
   - [ ] Verify file terupload
   - [ ] Verify nomor pendaftaran format T-26270001
   - [ ] Verify success page

2. **Happy Path - Muallimin**
   - [ ] Isi semua field required
   - [ ] Upload KK (wajib)
   - [ ] Submit tanpa file opsional
   - [ ] Verify nomor pendaftaran format M-26270001
   - [ ] Verify success page

3. **Validation Testing**
   - [ ] Submit tanpa NISN → must reject
   - [ ] Submit NISN < 10 digit → must reject
   - [ ] Submit NISN > 10 digit → must reject
   - [ ] Submit NIK != 16 digit → must reject
   - [ ] Submit tanpa file KK → must reject
   - [ ] Submit file KK > 2MB → must reject
   - [ ] Submit file KK format .doc → must reject
   - [ ] Submit dengan semua dropdown default → must reject

4. **Edge Cases**
   - [ ] Submit dengan imunisasi kosong (tidak centang)
   - [ ] Submit dengan disabilitas "Tidak Ada"
   - [ ] Submit dengan field opsional kosong
   - [ ] Submit form multiple times (test idempotency)
   - [ ] Upload file dengan nama special characters
   - [ ] Upload file dengan nama sangat panjang

5. **Data Integrity**
   - [ ] Verify foreign keys bekerja
   - [ ] Test cascade delete (jika pendaftar dihapus, relasi ikut terhapus)
   - [ ] Verify unique constraint nomor_pendaftaran
   - [ ] Verify auto-increment nomor pendaftaran

---

#### **Task 4.2: Bug Fixing** (1 hari)

**Known Issues to Check:**

1. **Form Issues**
   - [ ] Old() function untuk repopulate form after error
   - [ ] Error messages tampil dengan jelas
   - [ ] Required field markers (*) konsisten
   - [ ] Dropdown pre-select untuk edit mode

2. **File Upload Issues**
   - [ ] File permission di `writable/uploads/`
   - [ ] Directory creation jika belum ada
   - [ ] File name collision handling
   - [ ] Orphan files cleanup

3. **Database Issues**
   - [ ] Transaction rollback testing
   - [ ] NULL handling untuk field opsional
   - [ ] ENUM validation
   - [ ] Date format consistency

4. **UI/UX Issues**
   - [ ] Loading indicator saat submit
   - [ ] Disable submit button after click
   - [ ] Form section navigation
   - [ ] Mobile responsive layout
   - [ ] File size display formatting

---

#### **Task 4.3: Performance Optimization** (0.5 hari)

**Optimizations:**

1. **Database**
   - Add indexes where needed
   - Optimize queries (use joins efficiently)
   - Add database query caching

2. **File Upload**
   - Implement chunked upload untuk file besar
   - Add image compression untuk JPG/PNG
   - Lazy loading untuk file preview

3. **Frontend**
   - Minify CSS/JS
   - Optimize image assets
   - Add browser caching headers

---

#### **Task 4.4: Security Audit** (0.5 hari)

**Security Checks:**

1. **CSRF Protection**
   - [ ] Verify CSRF token di semua form
   - [ ] Test CSRF token expiration

2. **File Upload Security**
   - [ ] Verify file type validation (server-side)
   - [ ] Verify file size validation (server-side)
   - [ ] Check for malicious file upload (PHP shell, etc.)
   - [ ] Verify file storage outside web root
   - [ ] Check file name sanitization

3. **SQL Injection**
   - [ ] All queries use query builder or prepared statements
   - [ ] No raw SQL with user input

4. **XSS Protection**
   - [ ] All user input escaped in views
   - [ ] Verify esc() function usage

5. **Access Control**
   - [ ] Verify direct file access prevention
   - [ ] Check dokumen viewer authorization

---

### ✅ Sprint 4 Deliverables
- ✓ Comprehensive test report
- ✓ Bug fixes implemented
- ✓ Performance optimization applied
- ✓ Security audit passed
- ✓ Cross-browser compatibility verified
- ✓ Mobile responsive verified

---

## **SPRINT 5: DOCUMENTATION & DEPLOYMENT PREP**
**Duration:** 2 Hari Kerja
**Goal:** Documentation, user guide, dan deployment preparation

### 🎯 Sprint Goals
- [x] Technical documentation
- [x] User guide / manual
- [x] Admin guide
- [x] API documentation (jika ada)
- [x] Deployment checklist
- [x] Database migration guide
- [x] Backup & restore procedure

### 📋 Tasks Detail

#### **Task 5.1: Technical Documentation** (0.5 hari)

**Create:** `PENDAFTARAN_TECHNICAL_DOCS.md`

**Contents:**
1. Architecture overview
2. Database schema (ERD)
3. File structure
4. API endpoints
5. Model relationships
6. Validation rules
7. File upload workflow
8. Security measures

---

#### **Task 5.2: User Guide** (0.5 hari)

**Create:** `PANDUAN_PENDAFTARAN_USER.md`

**Contents:**
1. Cara mengakses form pendaftaran
2. Penjelasan field per field
3. Cara upload dokumen
4. Format dokumen yang diterima
5. Troubleshooting common issues
6. FAQ

---

#### **Task 5.3: Admin Guide** (0.5 hari)

**Create:** `PANDUAN_ADMIN.md`

**Contents:**
1. Cara melihat data pendaftar
2. Cara export data
3. Cara verifikasi dokumen
4. Cara manage pendaftar
5. Cara backup data
6. Cara restore data

---

#### **Task 5.4: Deployment Checklist** (0.5 hari)

**Create:** `DEPLOYMENT_CHECKLIST.md`

**Contents:**
```markdown
## Pre-Deployment Checklist

### Environment Setup
- [ ] PHP 8.1+ installed
- [ ] MySQL 8.0+ installed
- [ ] Composer installed
- [ ] mod_rewrite enabled

### Database
- [ ] Create database
- [ ] Run migrations
- [ ] Run seeders (if needed)
- [ ] Verify indexes
- [ ] Setup database backup cron

### File System
- [ ] Create `writable/uploads/dokumen_pendaftar/` directory
- [ ] Set permissions 755 for uploads directory
- [ ] Verify disk space for file uploads
- [ ] Setup file backup strategy

### Configuration
- [ ] Copy `.env.example` to `.env`
- [ ] Set database credentials
- [ ] Set base_url
- [ ] Set encryption key
- [ ] Configure email (if needed)
- [ ] Set max upload size in php.ini

### Security
- [ ] Disable debug mode in production
- [ ] Set secure session settings
- [ ] Configure CSRF protection
- [ ] Setup HTTPS
- [ ] Configure security headers

### Testing
- [ ] Run all tests
- [ ] Test file upload
- [ ] Test form submission
- [ ] Test email notifications
- [ ] Load testing

### Monitoring
- [ ] Setup error logging
- [ ] Setup access logging
- [ ] Configure alerts
- [ ] Setup performance monitoring
```

---

### ✅ Sprint 5 Deliverables
- ✓ Technical documentation complete
- ✓ User guide complete
- ✓ Admin guide complete
- ✓ Deployment checklist complete
- ✓ Database migration scripts tested
- ✓ Backup/restore procedure documented

---

## 📊 SPRINT SUMMARY

| Sprint | Duration | Focus | Complexity | Risk |
|--------|----------|-------|------------|------|
| Sprint 1 | 3 hari | Validation & Dropdowns | Low | Low |
| Sprint 2 | 4 hari | Database & Input Types | Medium | Low |
| Sprint 3 | 5 hari | File Upload System | High | Medium |
| Sprint 4 | 3 hari | Testing & Bug Fixes | Medium | Low |
| Sprint 5 | 2 hari | Documentation | Low | Low |
| **TOTAL** | **17 hari** | | | |

---

## 🎯 MILESTONE TRACKING

### Milestone 1: Foundation Complete (After Sprint 1)
- ✓ Validasi ketat NISN & NIK
- ✓ Format nomor pendaftaran sesuai
- ✓ Semua dropdown updated

### Milestone 2: Database Complete (After Sprint 2)
- ✓ Semua field requirements ada
- ✓ Input types sesuai (checkbox, radio, dropdown)
- ✓ Migration tested & working

### Milestone 3: File Upload Complete (After Sprint 3)
- ✓ Upload KK (wajib) working
- ✓ Upload Bansos (opsional) working
- ✓ File validation & storage working
- ✓ File preview working

### Milestone 4: Production Ready (After Sprint 4)
- ✓ All tests passing
- ✓ No critical bugs
- ✓ Performance acceptable
- ✓ Security audit passed

### Milestone 5: Deployment Ready (After Sprint 5)
- ✓ Documentation complete
- ✓ Deployment guide ready
- ✓ Backup strategy in place

---

## 🚀 DEPLOYMENT STRATEGY

### Phase 1: Staging Deployment (Week 4)
1. Deploy to staging server
2. Run full regression tests
3. UAT with stakeholders
4. Gather feedback
5. Fix critical issues

### Phase 2: Production Deployment (Week 5)
1. Schedule maintenance window
2. Backup production database
3. Run migrations
4. Deploy code
5. Verify functionality
6. Monitor for 24 hours

### Phase 3: Post-Deployment (Week 5-6)
1. Monitor error logs
2. Gather user feedback
3. Fix minor bugs
4. Optimize based on real usage
5. Create hotfix releases if needed

---

## ⚠️ RISKS & MITIGATION

| Risk | Impact | Probability | Mitigation |
|------|--------|-------------|------------|
| File upload storage full | High | Low | Monitor disk space, implement cleanup cron |
| Large file upload timeout | Medium | Medium | Increase timeout, add chunked upload |
| Database migration fails | High | Low | Test rollback, have backup ready |
| Form validation bypass | High | Low | Server-side validation, security audit |
| File type vulnerability | High | Medium | Strict MIME type check, virus scan |

---

## 📈 SUCCESS METRICS

1. **Functionality**
   - [ ] 100% requirements implemented
   - [ ] All validations working
   - [ ] File upload success rate > 98%

2. **Performance**
   - [ ] Page load time < 2 seconds
   - [ ] Form submission < 5 seconds
   - [ ] File upload < 10 seconds (2MB)

3. **Quality**
   - [ ] Test coverage > 80%
   - [ ] Zero critical bugs
   - [ ] Security audit score > 90%

4. **User Experience**
   - [ ] Form completion rate > 85%
   - [ ] User satisfaction > 4/5
   - [ ] Support tickets < 5% of submissions

---

## 📝 NOTES

- Sprint dapat disesuaikan berdasarkan velocity tim
- Priority bisa berubah berdasarkan feedback stakeholder
- Testing dilakukan paralel dengan development
- Code review mandatory sebelum merge
- Daily standup untuk track progress

---

**END OF SPRINT PLAN**
