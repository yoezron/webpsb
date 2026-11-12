<?php
/**
 * Section 5: Data Wali. Variabel opsional: $wali (data lama saat update).
 */
helper('psb_form');

use App\Libraries\FormPendaftaran as F;

$rowWali = $wali ?? [];

// Saat update: default "Ya" bila sudah ada data wali; saat daftar baru: belum dipilih
$adaWaliDefault = isset($pendaftar['id_pendaftar']) ? (!empty($rowWali) ? 'Ya' : 'Tidak') : '';
$adaWali        = pf_nilai('ada_wali', $adaWaliDefault);
?>
<!-- Section 5: Data Wali -->
<div class="section-card hidden" id="section-5">
    <h2 class="section-title">
        <i class="icofont-users"></i> Data Wali
    </h2>

    <div class="row">
        <div class="col-md-12 mb-3" data-field="ada_wali" data-wajib-grup="ada_wali">
            <label class="form-label required">Apakah santri memiliki wali?</label>
            <div class="d-flex flex-wrap gap-4">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="ada_wali" id="ada_wali_ya" value="Ya" data-pengendali-wajib="wali" <?= $adaWali === 'Ya' ? 'checked' : '' ?>>
                    <label class="form-check-label" for="ada_wali_ya">Ya, ada wali</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="ada_wali" id="ada_wali_tidak" value="Tidak" data-pengendali-wajib="wali" <?= $adaWali === 'Tidak' ? 'checked' : '' ?>>
                    <label class="form-check-label" for="ada_wali_tidak">Tidak ada wali</label>
                </div>
            </div>
            <div class="form-text">Wali dapat berupa ayah/ibu kandung sendiri (pilih "Sama dengan Ayah/Ibu Kandung") atau orang lain yang mengasuh santri.</div>
        </div>
    </div>

    <div class="row" id="wali-fields">
        <?= pf_select('hubungan_wali', 'Status / Hubungan Wali', F::HUBUNGAN_WALI, $rowWali['hubungan_wali'] ?? '', [
            'dinamis' => 'wali',
            'kosong'  => 'Pilih Status Wali',
            'hint'    => 'Memilih "Sama dengan Ayah/Ibu Kandung" akan menyalin data ayah/ibu secara otomatis.',
        ]) ?>
        <div class="col-md-6 mb-3"></div>

        <?php $peran = 'wali'; $label = 'Wali'; $row = $rowWali; include __DIR__ . '/_data_orang.php'; ?>
    </div>
</div>
