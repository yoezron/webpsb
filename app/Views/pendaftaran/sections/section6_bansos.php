<?php
/**
 * Section 6: Data Bantuan Sosial (tidak wajib). Variabel opsional: $bansos.
 */
helper('psb_form');

$b = $bansos ?? [];
?>
<!-- Section 6: Data Bansos -->
<div class="section-card hidden" id="section-6">
    <h2 class="section-title">
        <i class="icofont-card"></i> Data Bantuan Sosial (Bansos)
    </h2>

    <div class="alert alert-info">
        <i class="icofont-info-circle"></i> Bagian ini <strong>tidak wajib</strong>. Kosongkan jika tidak memiliki/tidak menerima bantuan sosial.
    </div>

    <div class="row">
        <?= pf_input('no_kks', 'Nomor Kartu Keluarga Sejahtera (KKS)', $b['no_kks'] ?? '', ['col' => 'col-md-12', 'kelas' => 'numeric-only', 'maxlength' => 30, 'placeholder' => 'Masukkan nomor KKS', 'hint' => 'KKS adalah kartu yang diberikan kepada keluarga penerima PKH atau BPNT']) ?>
        <?= pf_input('no_pkh', 'Nomor Program Keluarga Harapan (PKH)', $b['no_pkh'] ?? '', ['kelas' => 'numeric-only', 'maxlength' => 30, 'placeholder' => 'Masukkan nomor PKH', 'hint' => 'PKH adalah program bantuan sosial bersyarat untuk keluarga miskin']) ?>
        <?= pf_input('no_kip', 'Nomor Kartu Indonesia Pintar (KIP)', $b['no_kip'] ?? '', ['kelas' => 'numeric-only', 'maxlength' => 30, 'placeholder' => 'Masukkan nomor KIP', 'hint' => 'KIP adalah kartu bantuan pendidikan untuk anak usia sekolah']) ?>
    </div>
</div>
