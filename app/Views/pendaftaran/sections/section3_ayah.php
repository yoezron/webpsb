<?php
/**
 * Section 3: Data Ayah Kandung. Variabel opsional: $ayah.
 */
helper('psb_form');

use App\Libraries\FormPendaftaran as F;

$rowAyah = $ayah ?? [];
?>
<!-- Section 3: Data Ayah -->
<div class="section-card hidden" id="section-3">
    <h2 class="section-title">
        <i class="icofont-user-male"></i> Data Ayah Kandung
    </h2>

    <div class="row">
        <?= pf_select('status_ayah', 'Status Ayah', F::STATUS_ORTU, $rowAyah['status_ayah'] ?? '', [
            'wajib'      => true,
            'kosong'     => 'Pilih Status',
            'attrs'      => 'data-pengendali-wajib="ayah"',
            'label_opsi' => ['Tidak Diketahui' => 'Tidak Diketahui (berpisah / tidak ada kabar)'],
        ]) ?>
        <div class="col-md-6 mb-3 d-flex align-items-end">
            <div class="alert alert-info py-2 px-3 mb-0 small w-100 catatan-opsional" data-untuk="ayah">
                <i class="icofont-info-circle"></i> Ayah sudah meninggal / tidak diketahui: isian di bawah <strong>boleh dikosongkan</strong>, isi yang diketahui saja.
            </div>
        </div>

        <?php $peran = 'ayah'; $label = 'Ayah'; $row = $rowAyah; include __DIR__ . '/_data_orang.php'; ?>
    </div>
</div>
