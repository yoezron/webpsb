<?= $this->extend('layouts/pengaturan') ?>

<?= $this->section('content') ?>

<?php
$isBaru = $mode === 'baru';
$val = function (string $key) use ($periode) {
    return old($key, $periode[$key] ?? '');
};
$action = $isBaru
    ? base_url('admin/pengaturan/periode/simpan')
    : base_url('admin/pengaturan/periode/update/' . $periode['id_periode']);
?>

<form method="post" action="<?= $action ?>" id="formPeriode">
    <?= csrf_field() ?>

    <?php if ($isBaru && $aktif): ?>
        <div class="alert alert-info">
            <i class="icofont-info-circle me-1"></i>
            Periode aktif saat ini adalah <strong>TA <?= esc($aktif['tahun_ajaran']) ?></strong>
            dengan <strong><?= (int) $jumlahAktif ?></strong> pendaftar. Saat periode baru dibuka, periode tersebut
            akan <strong>diarsipkan</strong>: datanya tidak dihapus, tetap bisa dilihat dan diunduh dari menu
            <a href="<?= base_url('admin/pengaturan/arsip') ?>" class="alert-link">Arsip Data</a>.
            Dashboard dan statistik akan menampilkan data periode baru.
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-xl-6">
            <div class="card-setting h-100">
                <div class="card-header"><i class="icofont-calendar me-1"></i> Tahun Ajaran &amp; Jadwal</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label fw-semibold">Tahun Ajaran <span class="text-danger">*</span></label>
                            <input type="text" name="tahun_ajaran" id="tahun_ajaran" class="form-control" required
                                pattern="\d{4}/\d{4}" placeholder="2027/2028" value="<?= esc($val('tahun_ajaran')) ?>">
                        </div>
                        <div class="col-md-7">
                            <label class="form-label fw-semibold">Nama Periode</label>
                            <input type="text" name="nama_periode" class="form-control" maxlength="150"
                                placeholder="PSB Tahun Ajaran 2027/2028" value="<?= esc($val('nama_periode')) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal Buka</label>
                            <input type="date" name="tanggal_buka" class="form-control" value="<?= esc($val('tanggal_buka')) ?>">
                            <div class="form-text">Kosongkan bila langsung dibuka.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal Tutup</label>
                            <input type="date" name="tanggal_tutup" class="form-control" value="<?= esc($val('tanggal_tutup')) ?>">
                            <div class="form-text">Kosongkan bila tanpa batas.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kuota Tsanawiyyah</label>
                            <input type="number" min="0" name="kuota_tsanawiyyah" class="form-control" placeholder="Tanpa batas" value="<?= esc($val('kuota_tsanawiyyah')) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kuota Mu'allimin</label>
                            <input type="number" min="0" name="kuota_muallimin" class="form-control" placeholder="Tanpa batas" value="<?= esc($val('kuota_muallimin')) ?>">
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input type="hidden" name="pendaftaran_dibuka" value="0">
                                <input class="form-check-input" type="checkbox" role="switch" id="pendaftaran_dibuka" name="pendaftaran_dibuka" value="1"
                                    <?= (string) $val('pendaftaran_dibuka') === '1' ? 'checked' : '' ?>>
                                <label class="form-check-label fw-semibold" for="pendaftaran_dibuka">Pendaftaran dibuka</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Catatan Internal</label>
                            <textarea name="catatan" class="form-control" rows="2"><?= esc($val('catatan')) ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card-setting h-100">
                <div class="card-header"><i class="icofont-numbered me-1"></i> Format Nomor Peserta</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Format <span class="text-danger">*</span></label>
                            <input type="text" name="format_nomor" id="format_nomor" class="form-control font-monospace" required maxlength="100"
                                value="<?= esc($val('format_nomor')) ?>">
                            <div class="form-text">
                                Placeholder:
                                <code>{PREFIX}</code> prefix jalur,
                                <code>{JALUR}</code> TSN/MUA,
                                <code>{TA1}</code>/<code>{TA2}</code> tahun 4 digit,
                                <code>{YY1}</code>/<code>{YY2}</code> tahun 2 digit,
                                <code>{NOMOR}</code> nomor urut (wajib).
                            </div>
                            <div class="d-flex flex-wrap gap-1 mt-2">
                                <?php foreach (['{PREFIX}-{YY1}{YY2}{NOMOR}', '{PREFIX}{TA1}{NOMOR}', 'PSB-{TA1}-{JALUR}-{NOMOR}', '{PREFIX}/{YY1}{YY2}/{NOMOR}'] as $tpl): ?>
                                    <button type="button" class="btn btn-sm btn-outline-secondary font-monospace btn-template" data-format="<?= esc($tpl) ?>"><?= esc($tpl) ?></button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Prefix Tsanawiyyah</label>
                            <input type="text" name="prefix_tsanawiyyah" id="prefix_tsanawiyyah" class="form-control" maxlength="10" value="<?= esc($val('prefix_tsanawiyyah')) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Prefix Mu'allimin</label>
                            <input type="text" name="prefix_muallimin" id="prefix_muallimin" class="form-control" maxlength="10" value="<?= esc($val('prefix_muallimin')) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jumlah Digit Nomor Urut</label>
                            <input type="number" min="1" max="8" name="digit_nomor" id="digit_nomor" class="form-control" required value="<?= esc($val('digit_nomor')) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nomor Urut Mulai</label>
                            <input type="number" min="1" name="nomor_mulai" id="nomor_mulai" class="form-control" required value="<?= esc($val('nomor_mulai')) ?>">
                        </div>
                        <div class="col-12">
                            <div class="p-3 rounded bg-light">
                                <div class="small text-muted mb-1">Pratinjau nomor peserta pertama</div>
                                <div class="fs-5">
                                    Tsanawiyyah: <code id="previewT">-</code><br>
                                    Mu'allimin: <code id="previewM">-</code>
                                </div>
                            </div>
                            <?php if (!$isBaru && $jumlahAktif > 0): ?>
                                <div class="form-text text-warning">
                                    <i class="icofont-warning"></i> Periode ini sudah memiliki <?= (int) $jumlahAktif ?> pendaftar.
                                    Perubahan format hanya berlaku untuk pendaftar berikutnya; nomor yang sudah terbit tidak berubah.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if ($isBaru): ?>
        <div class="card-setting mt-4">
            <div class="card-body">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="konfirmasi" value="YA" id="konfirmasi" required>
                    <label class="form-check-label" for="konfirmasi">
                        <?php if ($aktif): ?>
                            Saya memahami bahwa periode <strong>TA <?= esc($aktif['tahun_ajaran']) ?></strong> akan diarsipkan
                            dan periode baru ini menjadi periode aktif.
                        <?php else: ?>
                            Saya ingin membuka periode ini sebagai periode aktif.
                        <?php endif; ?>
                    </label>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-psb px-4">
            <i class="icofont-save me-1"></i> <?= $isBaru ? 'Buka Periode Baru' : 'Simpan Perubahan' ?>
        </button>
        <a href="<?= base_url('admin/pengaturan/periode') ?>" class="btn btn-outline-secondary">Batal</a>
    </div>
</form>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    (function() {
        const $ = (id) => document.getElementById(id);

        function render(jalur) {
            const ta = ($('tahun_ajaran').value.match(/(\d{4})\D+(\d{4})/) || []);
            const ta1 = ta[1] || '';
            const ta2 = ta[2] || (ta1 ? String(+ta1 + 1) : '');
            const digit = Math.max(1, Math.min(8, parseInt($('digit_nomor').value || '4', 10)));
            const mulai = Math.max(1, parseInt($('nomor_mulai').value || '1', 10));
            let format = $('format_nomor').value.trim() || '{PREFIX}-{YY1}{YY2}{NOMOR}';
            if (format.toUpperCase().indexOf('{NOMOR}') === -1) format += '{NOMOR}';
            const prefix = jalur === 'T' ? ($('prefix_tsanawiyyah').value || 'T') : ($('prefix_muallimin').value || 'M');
            const map = {
                '{PREFIX}': prefix,
                '{JALUR}': jalur === 'T' ? 'TSN' : 'MUA',
                '{TA1}': ta1,
                '{TA2}': ta2,
                '{YY1}': ta1.slice(-2),
                '{YY2}': ta2.slice(-2),
                '{NOMOR}': String(mulai).padStart(digit, '0'),
            };
            return format.replace(/\{[A-Z0-9]+\}/g, (m) => (m in map ? map[m] : m));
        }

        function update() {
            $('previewT').textContent = render('T');
            $('previewM').textContent = render('M');
        }

        document.querySelectorAll('#formPeriode input').forEach((el) => el.addEventListener('input', update));
        document.querySelectorAll('.btn-template').forEach((btn) => btn.addEventListener('click', () => {
            $('format_nomor').value = btn.dataset.format;
            update();
        }));
        update();
    })();
</script>
<?= $this->endSection() ?>
