<?php
/**
 * Section 4: Data Ibu Kandung. Variabel opsional: $ibu.
 */
helper('psb_form');

use App\Libraries\FormPendaftaran as F;

$rowIbu = $ibu ?? [];
?>
<!-- Section 4: Data Ibu -->
<div class="section-card hidden" id="section-4">
    <h2 class="section-title">
        <i class="icofont-user-female"></i> Data Ibu Kandung
    </h2>

    <div class="row">
        <?= pf_select('status_ibu', 'Status Ibu', F::STATUS_ORTU, $rowIbu['status_ibu'] ?? '', [
            'wajib'      => true,
            'kosong'     => 'Pilih Status',
            'attrs'      => 'data-pengendali-wajib="ibu"',
            'label_opsi' => ['Tidak Diketahui' => 'Tidak Diketahui (berpisah / tidak ada kabar)'],
        ]) ?>
        <div class="col-md-6 mb-3 d-flex align-items-end">
            <div class="alert alert-info py-2 px-3 mb-0 small w-100 catatan-opsional" data-untuk="ibu">
                <i class="icofont-info-circle"></i> Ibu sudah meninggal / tidak diketahui: isian di bawah <strong>boleh dikosongkan</strong>, isi yang diketahui saja.
            </div>
        </div>

        <?php $peran = 'ibu'; $label = 'Ibu'; $row = $rowIbu; include __DIR__ . '/_data_orang.php'; ?>
    </div>
</div>
