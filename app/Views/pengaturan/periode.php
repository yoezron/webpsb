<?= $this->extend('layouts/pengaturan') ?>

<?= $this->section('content') ?>

<?php $statusModel = new \App\Models\PeriodeModel(); ?>

<!-- Periode aktif -->
<div class="card-setting mb-4">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span><i class="icofont-flag me-1"></i> Periode Aktif</span>
        <a href="<?= base_url('admin/pengaturan/periode/baru') ?>" class="btn btn-psb btn-sm">
            <i class="icofont-plus me-1"></i> Buka Periode Tahun Ajaran Baru
        </a>
    </div>
    <div class="card-body">
        <?php if ($aktif): ?>
            <?php
            $status   = $statusModel->statusPendaftaran();
            $contohT  = \App\Models\PeriodeModel::formatNomor($aktif, 'TSANAWIYYAH', (int) $aktif['nomor_mulai']);
            $contohM  = \App\Models\PeriodeModel::formatNomor($aktif, 'MUALLIMIN', (int) $aktif['nomor_mulai']);
            $jumlah   = 0;
            foreach ($periodes as $p) {
                if ((int) $p['id_periode'] === (int) $aktif['id_periode']) {
                    $jumlah = $p;
                }
            }
            ?>
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <h4 class="mb-1">Tahun Ajaran <?= esc($aktif['tahun_ajaran']) ?></h4>
                    <div class="text-muted mb-2"><?= esc($aktif['nama_periode']) ?></div>
                    <span class="badge <?= $status['dibuka'] ? 'bg-success' : 'bg-secondary' ?> fs-6">
                        <?= $status['dibuka'] ? 'Pendaftaran Dibuka' : 'Pendaftaran Ditutup' ?>
                    </span>
                    <?php if (!$status['dibuka'] && $status['alasan']): ?>
                        <div class="small text-muted mt-1"><?= esc($status['alasan']) ?></div>
                    <?php endif; ?>
                    <table class="table table-sm mt-3 mb-0">
                        <tr>
                            <td class="text-muted" style="width:45%">Jadwal</td>
                            <td>
                                <?= $aktif['tanggal_buka'] ? date('d/m/Y', strtotime($aktif['tanggal_buka'])) : 'Tanpa batas' ?>
                                &ndash;
                                <?= $aktif['tanggal_tutup'] ? date('d/m/Y', strtotime($aktif['tanggal_tutup'])) : 'Tanpa batas' ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Format nomor</td>
                            <td><code><?= esc($aktif['format_nomor']) ?></code></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Contoh nomor</td>
                            <td><code><?= esc($contohT) ?></code> &middot; <code><?= esc($contohM) ?></code></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Kuota</td>
                            <td>
                                Tsanawiyyah: <?= $aktif['kuota_tsanawiyyah'] ? (int) $aktif['kuota_tsanawiyyah'] : '&infin;' ?> &middot;
                                Mu'allimin: <?= $aktif['kuota_muallimin'] ? (int) $aktif['kuota_muallimin'] : '&infin;' ?>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <div class="row g-2 text-center">
                        <div class="col-4">
                            <div class="p-3 rounded bg-light">
                                <div class="fs-3 fw-bold"><?= (int) ($jumlah['total_pendaftar'] ?? 0) ?></div>
                                <div class="small text-muted">Total</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 rounded bg-light">
                                <div class="fs-3 fw-bold"><?= (int) ($jumlah['total_tsanawiyyah'] ?? 0) ?></div>
                                <div class="small text-muted">Tsanawiyyah</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 rounded bg-light">
                                <div class="fs-3 fw-bold"><?= (int) ($jumlah['total_muallimin'] ?? 0) ?></div>
                                <div class="small text-muted">Mu'allimin</div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap gap-2 mt-3">
                        <a href="<?= base_url('admin/pengaturan/periode/edit/' . $aktif['id_periode']) ?>" class="btn btn-outline-primary">
                            <i class="icofont-edit me-1"></i> Ubah Periode / Format Nomor
                        </a>
                        <form method="post" action="<?= base_url('admin/pengaturan/periode/toggle/' . $aktif['id_periode']) ?>" class="d-inline">
                            <?= csrf_field() ?>
                            <?php if (!empty($aktif['pendaftaran_dibuka'])): ?>
                                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Tutup pendaftaran sekarang? Calon santri tidak akan bisa mendaftar.')">
                                    <i class="icofont-lock me-1"></i> Tutup Pendaftaran
                                </button>
                            <?php else: ?>
                                <button type="submit" class="btn btn-outline-success">
                                    <i class="icofont-unlock me-1"></i> Buka Pendaftaran
                                </button>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-warning mb-0">
                Belum ada periode aktif. Pendaftaran tidak dapat dilakukan sampai periode dibuka.
                <a href="<?= base_url('admin/pengaturan/periode/baru') ?>" class="alert-link">Buka periode sekarang</a>.
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Daftar periode -->
<div class="card-setting">
    <div class="card-header"><i class="icofont-listine-dots me-1"></i> Riwayat Periode</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Tahun Ajaran</th>
                        <th>Status</th>
                        <th>Format Nomor</th>
                        <th class="text-end">Pendaftar</th>
                        <th>Diarsipkan</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($periodes as $p): ?>
                        <tr>
                            <td>
                                <strong><?= esc($p['tahun_ajaran']) ?></strong>
                                <div class="small text-muted"><?= esc($p['nama_periode']) ?></div>
                            </td>
                            <td>
                                <?php if ($p['status'] === 'aktif'): ?>
                                    <span class="badge badge-aktif">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Arsip</span>
                                <?php endif; ?>
                            </td>
                            <td><code><?= esc(\App\Models\PeriodeModel::formatNomor($p, 'TSANAWIYYAH', (int) $p['nomor_mulai'])) ?></code></td>
                            <td class="text-end"><?= (int) $p['total_pendaftar'] ?></td>
                            <td><?= $p['diarsipkan_pada'] ? date('d/m/Y H:i', strtotime($p['diarsipkan_pada'])) : '-' ?></td>
                            <td class="text-end text-nowrap">
                                <a href="<?= base_url('admin/pengaturan/periode/edit/' . $p['id_periode']) ?>" class="btn btn-sm btn-outline-secondary" title="Edit">
                                    <i class="icofont-edit"></i>
                                </a>
                                <a href="<?= base_url('dashboard?periode=' . $p['id_periode']) ?>" class="btn btn-sm btn-outline-primary" title="Lihat data">
                                    <i class="icofont-eye-alt"></i>
                                </a>
                                <?php if ($p['status'] !== 'aktif'): ?>
                                    <form method="post" action="<?= base_url('admin/pengaturan/periode/aktifkan/' . $p['id_periode']) ?>" class="d-inline">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-outline-warning" title="Aktifkan kembali"
                                            onclick="return confirm('Aktifkan kembali periode <?= esc($p['tahun_ajaran'], 'js') ?>? Periode yang aktif saat ini akan diarsipkan.')">
                                            <i class="icofont-undo"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
