<?php
/**
 * Section 1: Data Diri — dipakai oleh form pendaftaran baru & form update data.
 * Variabel opsional: $pendaftar (array data lama saat update).
 */
helper('psb_form');

use App\Libraries\FormPendaftaran as F;

$d = $pendaftar ?? [];

$disabilitasLama  = pf_nilai('kebutuhan_disabilitas', $d['kebutuhan_disabilitas'] ?? '');
$disabilitasArray = is_array($disabilitasLama) ? $disabilitasLama : ($disabilitasLama !== '' ? array_map('trim', explode(',', $disabilitasLama)) : []);

$imunisasiLama  = pf_nilai('imunisasi', $d['imunisasi'] ?? '');
$imunisasiArray = is_array($imunisasiLama) ? $imunisasiLama : ($imunisasiLama !== '' ? array_map('trim', explode(',', $imunisasiLama)) : []);
?>
<!-- Section 1: Data Diri -->
<div class="section-card active" id="section-1">
    <h2 class="section-title">
        <i class="icofont-ui-user"></i> Data Diri Calon Santri
    </h2>

    <div class="row">
        <?= pf_input('nisn', 'NISN', $d['nisn'] ?? '', ['wajib' => true, 'kelas' => 'numeric-only', 'maxlength' => 10, 'placeholder' => 'Masukkan NISN (10 digit)', 'hint' => 'Nomor Induk Siswa Nasional (10 digit).']) ?>
        <?= pf_input('nik', 'NIK', $d['nik'] ?? '', ['wajib' => true, 'kelas' => 'numeric-only', 'maxlength' => 16, 'placeholder' => 'Masukkan NIK (16 digit)', 'hint' => 'Nomor Induk Kependudukan sesuai Kartu Keluarga (16 digit).']) ?>

        <?= pf_input('nama_lengkap', 'Nama Lengkap', $d['nama_lengkap'] ?? '', ['wajib' => true, 'col' => 'col-md-12', 'kelas' => 'huruf-besar', 'placeholder' => 'Nama lengkap sesuai akta kelahiran', 'hint' => 'Otomatis ditulis dengan HURUF BESAR.']) ?>

        <?= pf_select('jenis_kelamin', 'Jenis Kelamin', ['L' => 'Laki-laki', 'P' => 'Perempuan'], $d['jenis_kelamin'] ?? '', ['wajib' => true, 'kosong' => 'Pilih Jenis Kelamin']) ?>
        <?= pf_select('agama', 'Agama', F::AGAMA, $d['agama'] ?? '', ['wajib' => true, 'kosong' => 'Pilih Agama']) ?>

        <?= pf_input('tempat_lahir', 'Tempat Lahir', $d['tempat_lahir'] ?? '', ['wajib' => true, 'placeholder' => 'Masukkan tempat lahir']) ?>
        <?= pf_input('tanggal_lahir', 'Tanggal Lahir', $d['tanggal_lahir'] ?? '', ['wajib' => true, 'type' => 'date', 'attrs' => 'max="' . date('Y-m-d') . '"']) ?>

        <?= pf_select('status_keluarga', 'Status dalam Keluarga', ['Anak Kandung', 'Anak Tiri', 'Anak Angkat'], $d['status_keluarga'] ?? '', ['kosong' => 'Pilih Status']) ?>
        <?= pf_input('anak_ke', 'Anak Ke-', $d['anak_ke'] ?? '', ['wajib' => true, 'type' => 'number', 'placeholder' => 'Masukkan urutan anak', 'attrs' => 'min="1" max="19"']) ?>
        <?= pf_input('jumlah_saudara', 'Jumlah Saudara Kandung', $d['jumlah_saudara'] ?? '', ['wajib' => true, 'type' => 'number', 'placeholder' => 'Isi 0 jika tidak punya saudara', 'attrs' => 'min="0" max="19"']) ?>

        <?= pf_select('hobi', 'Hobi', ['Olah Raga', 'Kesenian', 'Membaca', 'Menulis', 'Jalan-jalan', 'Lainnya'], $d['hobi'] ?? '', ['wajib' => true, 'kosong' => 'Pilih Hobi']) ?>
        <?= pf_select('cita_cita', 'Cita-cita', ['PNS', 'TNI/Polri', 'Guru/Dosen', 'Dokter', 'Politikus', 'Wiraswasta', 'Seniman/Artis', 'Ilmuwan', 'Agamawan', 'Lainnya'], $d['cita_cita'] ?? '', ['wajib' => true, 'kosong' => 'Pilih Cita-cita']) ?>

        <?= pf_input('no_hp', 'Nomor HP/WhatsApp Santri', $d['no_hp'] ?? '', ['wajib' => true, 'kelas' => 'numeric-only', 'maxlength' => 20, 'placeholder' => 'Contoh: 08123456789', 'hint' => 'Jika santri belum memiliki HP, isi dengan nomor orang tua/wali.']) ?>
        <?= pf_select('yang_membiayai_sekolah', 'Yang Membiayai Sekolah', F::YANG_MEMBIAYAI, $d['yang_membiayai_sekolah'] ?? '', ['wajib' => true]) ?>

        <!-- Pendidikan Sebelumnya -->
        <div class="col-md-12 mb-3">
            <label class="form-label">Pendidikan Sebelumnya</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="pernah_paud" name="pernah_paud" value="1"
                    <?= pf_nilai('pernah_paud', $d['pernah_paud'] ?? '') ? 'checked' : '' ?>>
                <label class="form-check-label" for="pernah_paud">Pernah PAUD</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="pernah_tk" name="pernah_tk" value="1"
                    <?= pf_nilai('pernah_tk', $d['pernah_tk'] ?? '') ? 'checked' : '' ?>>
                <label class="form-check-label" for="pernah_tk">Pernah TK</label>
            </div>
        </div>

        <!-- Kebutuhan Disabilitas (Checkbox, minimal 1) -->
        <div class="col-md-12 mb-3" data-field="kebutuhan_disabilitas" data-wajib-grup="kebutuhan_disabilitas[]">
            <label class="form-label required">Kebutuhan Disabilitas</label>
            <div class="form-text mb-2">Pilih "Tidak Ada" jika tidak memiliki kebutuhan disabilitas.</div>
            <div class="row">
                <?php foreach (['Tidak Ada' => 'tidak', 'Tuna Netra' => 'netra', 'Tuna Rungu' => 'rungu', 'Tuna Daksa' => 'daksa', 'Tuna Grahita' => 'grahita', 'Tuna Laras' => 'laras', 'Tuna Wicara' => 'wicara', 'Lainnya' => 'lainnya'] as $val => $id): ?>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input cek-disabilitas" type="checkbox" id="disabilitas_<?= $id ?>"
                                name="kebutuhan_disabilitas[]" value="<?= esc($val) ?>" <?= in_array($val, $disabilitasArray, true) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="disabilitas_<?= $id ?>"><?= esc($val) ?></label>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <?= pf_select('kebutuhan_khusus', 'Kebutuhan Khusus', ['Tidak ada', 'Lamban Belajar', 'Kesulitan belajar Spesifik', 'Gangguan Komunikasi', 'Berbakat/memiliki kemampuan dan kecerdasan luar biasa', 'Lainnya'], $d['kebutuhan_khusus'] ?? '', ['wajib' => true, 'kosong' => 'Pilih Kebutuhan Khusus']) ?>

        <!-- Imunisasi (Checkbox) -->
        <div class="col-md-12 mb-3" data-field="imunisasi">
            <label class="form-label required">Riwayat Imunisasi</label>
            <div class="form-text mb-2">Centang jenis imunisasi yang sudah diterima</div>
            <div class="row">
                <?php foreach (['Hepatitis B' => 'hepatitis', 'BCG' => 'bcg', 'DPT' => 'dpt', 'Polio' => 'polio', 'Campak' => 'campak', 'Covid-19' => 'covid'] as $val => $id): ?>
                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="imunisasi_<?= $id ?>"
                                name="imunisasi[]" value="<?= esc($val) ?>" <?= in_array($val, $imunisasiArray, true) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="imunisasi_<?= $id ?>"><?= esc($val) ?></label>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <?= pf_select('ukuran_baju', 'Ukuran Baju', ['S', 'M', 'L', 'XL', 'XXL'], $d['ukuran_baju'] ?? '', ['kosong' => 'Pilih Ukuran']) ?>

        <?= pf_textarea('prestasi', 'Prestasi yang Pernah Diraih', $d['prestasi'] ?? '', ['col' => 'col-md-12', 'rows' => 3, 'placeholder' => 'Tuliskan prestasi yang pernah diraih (jika ada)']) ?>
        <?= pf_textarea('minat_bakat', 'Minat & Bakat', $d['minat_bakat'] ?? '', ['col' => 'col-md-12', 'rows' => 3, 'placeholder' => 'Tuliskan minat dan bakat yang dimiliki']) ?>
    </div>
</div>
