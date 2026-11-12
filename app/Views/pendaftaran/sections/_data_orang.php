<?php
/**
 * Field data orang (Ayah / Ibu / Wali) sesuai EMIS.
 *
 * Variabel:
 *   $peran  'ayah' | 'ibu' | 'wali'
 *   $label  'Ayah' | 'Ibu' | 'Wali'
 *   $row    data lama (array) atau []
 *
 * Semua field ditandai data-wajib-dinamis="$peran": wajib hanya bila
 * status ayah/ibu "Masih Hidup" atau wali "Ada" (diatur JS & divalidasi ulang di server).
 */
helper('psb_form');

use App\Libraries\FormPendaftaran as F;

$p   = $peran;
$L   = $label;
$o   = ['dinamis' => $p];
$val = fn (string $k) => $row[$k . '_' . $p] ?? '';
?>
<?= pf_input('nik_' . $p, 'NIK ' . $L, $val('nik'), $o + ['kelas' => 'numeric-only', 'maxlength' => 16, 'placeholder' => 'NIK ' . $L . ' (16 digit)']) ?>
<?= pf_input('nama_' . $p, 'Nama Lengkap ' . $L, $val('nama'), $o + ['kelas' => 'huruf-besar', 'placeholder' => 'Nama lengkap ' . strtolower($L) . ' sesuai KTP/KK']) ?>
<?= pf_input('tempat_lahir_' . $p, 'Tempat Lahir ' . $L, $val('tempat_lahir'), $o + ['placeholder' => 'Tempat lahir ' . strtolower($L)]) ?>
<?= pf_input('tanggal_lahir_' . $p, 'Tanggal Lahir ' . $L, $val('tanggal_lahir'), $o + ['type' => 'date', 'attrs' => 'max="' . date('Y-m-d') . '"']) ?>
<?= pf_select('pendidikan_' . $p, 'Pendidikan Terakhir ' . $L, F::PENDIDIKAN, $val('pendidikan'), $o + ['kosong' => 'Pilih Pendidikan']) ?>
<?= pf_select('pekerjaan_' . $p, 'Pekerjaan Utama ' . $L, F::pekerjaanUntuk($p), $val('pekerjaan'), $o + ['kosong' => 'Pilih Pekerjaan']) ?>
<?= pf_select('domisili_' . $p, 'Domisili ' . $L, F::DOMISILI, $val('domisili'), $o + ['kosong' => 'Pilih Domisili']) ?>
<?= pf_input('hp_' . $p, 'Nomor HP/WhatsApp ' . $L, $val('hp'), $o + ['kelas' => 'numeric-only', 'maxlength' => 20, 'placeholder' => 'Contoh: 08123456789']) ?>
<?= pf_select('penghasilan_' . $p, 'Penghasilan Rata-rata Per Bulan', F::PENGHASILAN, $val('penghasilan'), $o + ['kosong' => 'Pilih Range Penghasilan']) ?>
<?= pf_select('status_tempat_tinggal_' . $p, 'Status Tempat Tinggal ' . $L, F::STATUS_TEMPAT_TINGGAL, $val('status_tempat_tinggal'), $o + ['kosong' => 'Pilih Status Tempat Tinggal']) ?>
<?= pf_textarea('alamat_' . $p, 'Alamat ' . $L, $val('alamat'), $o + [
    'col'         => 'col-md-12',
    'rows'        => 2,
    'placeholder' => 'Alamat lengkap ' . strtolower($L),
    'sebelum'     => '<div class="form-check mb-1"><input class="form-check-input salin-alamat" type="checkbox" id="salin_alamat_' . $p . '" data-target="alamat_' . $p . '">'
        . '<label class="form-check-label small" for="salin_alamat_' . $p . '">Sama dengan alamat santri</label></div>',
]) ?>
