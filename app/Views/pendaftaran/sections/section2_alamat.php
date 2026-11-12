<?php
/**
 * Section 2: Data Tempat Tinggal + Upload Kartu Keluarga.
 * Variabel opsional: $alamat, $pendaftar (data lama saat update).
 */
helper('psb_form');

use App\Libraries\FormPendaftaran as F;

$a         = $alamat ?? [];
$adaFileKk = !empty($pendaftar['file_kk']) && F::pathKk($pendaftar['file_kk']) !== null;
?>
<!-- Section 2: Data Alamat -->
<div class="section-card hidden" id="section-2">
    <h2 class="section-title">
        <i class="icofont-location-pin"></i> Data Tempat Tinggal
    </h2>

    <div class="row">
        <?= pf_input('nomor_kk', 'Nomor Kartu Keluarga (KK)', $a['nomor_kk'] ?? '', ['kelas' => 'numeric-only', 'maxlength' => 16, 'placeholder' => 'Masukkan nomor KK (16 digit)']) ?>
        <?= pf_input('nama_kepala_keluarga', 'Nama Kepala Keluarga', $a['nama_kepala_keluarga'] ?? '', ['wajib' => true, 'placeholder' => 'Sesuai Kartu Keluarga']) ?>

        <!-- Upload Kartu Keluarga -->
        <div class="col-md-12 mb-3" data-field="file_kk">
            <label for="file_kk" class="form-label<?= $adaFileKk ? '' : ' required' ?>">Upload Kartu Keluarga (KK)</label>
            <input type="file" class="form-control" id="file_kk" name="file_kk" accept=".jpg,.jpeg,image/jpeg" data-max-kb="<?= F::KK_MAX_KB ?>" <?= $adaFileKk ? '' : 'required' ?>>
            <div class="form-text">
                Foto/scan Kartu Keluarga yang jelas terbaca. Format <strong>JPG/JPEG</strong>, ukuran maksimal <strong>1 MB</strong>.
                <?php if ($adaFileKk): ?>
                    <br><span class="text-success"><i class="icofont-check-circled"></i> File Kartu Keluarga sudah tersimpan.</span> Unggah file baru hanya jika ingin menggantinya.
                <?php endif; ?>
            </div>
            <img id="preview_kk" alt="Pratinjau Kartu Keluarga" class="img-thumbnail mt-2 d-none" style="max-height: 220px;">
        </div>

        <?= pf_select('jenis_tempat_tinggal', 'Jenis Tempat Tinggal', ['Milik Sendiri', 'Rumah Orang Tua', 'Rumah Saudara', 'Rumah Dinas', 'Sewa/Kontrak', 'Lainnya'], $a['jenis_tempat_tinggal'] ?? '', ['kosong' => 'Pilih Jenis']) ?>

        <?= pf_textarea('alamat', 'Alamat Lengkap', $a['alamat'] ?? '', ['wajib' => true, 'col' => 'col-md-12', 'rows' => 3, 'placeholder' => 'Jalan/Kampung/Komplek, Blok, Nomor Rumah']) ?>

        <?= pf_input('rt_rw', 'RT/RW', $a['rt_rw'] ?? '', ['wajib' => true, 'placeholder' => 'Contoh: 004/016']) ?>
        <?= pf_input('desa', 'Desa/Kelurahan', $a['desa'] ?? '', ['wajib' => true, 'placeholder' => 'Masukkan desa/kelurahan']) ?>
        <?= pf_input('kecamatan', 'Kecamatan', $a['kecamatan'] ?? '', ['wajib' => true, 'placeholder' => 'Masukkan kecamatan']) ?>
        <?= pf_input('kabupaten', 'Kabupaten/Kota', $a['kabupaten'] ?? '', ['wajib' => true, 'placeholder' => 'Masukkan kabupaten/kota']) ?>
        <?= pf_input('provinsi', 'Provinsi', $a['provinsi'] ?? '', ['wajib' => true, 'placeholder' => 'Masukkan provinsi']) ?>
        <?= pf_input('kode_pos', 'Kode Pos', $a['kode_pos'] ?? '', ['wajib' => true, 'kelas' => 'numeric-only', 'maxlength' => 5, 'placeholder' => 'Masukkan kode pos (5 digit)']) ?>

        <?= pf_select('tinggal_bersama', 'Status Tempat Tinggal Santri (Tinggal Bersama)', [
            'Tinggal dengan kedua orang tua', 'Tinggal dengan ayah kandung', 'Tinggal dengan Ibu Kandung', 'Tinggal dengan Wali',
            'Ikut Saudara/Kerabat', 'Kontrak/Kost', 'Panti Asuhan', 'Rumah Singgah', 'Lainnya',
        ], $a['tinggal_bersama'] ?? '', ['wajib' => true]) ?>

        <?= pf_select('jarak_ke_sekolah', 'Jarak Tempat Tinggal ke Sekolah', F::JARAK, $a['jarak_ke_sekolah'] ?? '', ['wajib' => true, 'kosong' => 'Pilih Jarak']) ?>
        <?= pf_select('waktu_tempuh', 'Waktu Tempuh ke Sekolah', F::WAKTU_TEMPUH, $a['waktu_tempuh'] ?? '', ['wajib' => true, 'kosong' => 'Pilih Waktu Tempuh']) ?>
        <?= pf_select('transportasi', 'Transportasi ke Sekolah', F::TRANSPORTASI, $a['transportasi'] ?? '', ['wajib' => true, 'kosong' => 'Pilih Transportasi']) ?>

        <?= pf_input('email', 'Alamat Email Santri', $a['email'] ?? '', ['wajib' => true, 'type' => 'email', 'placeholder' => 'contoh@gmail.com', 'hint' => 'Jika santri belum memiliki email, isi dengan email orang tua/wali.']) ?>
        <?= pf_input('media_sosial', 'Media Sosial', $a['media_sosial'] ?? '', ['placeholder' => 'Contoh: @username_instagram']) ?>
    </div>
</div>
