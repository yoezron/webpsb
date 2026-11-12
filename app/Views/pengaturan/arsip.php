<?= $this->extend('layouts/pengaturan') ?>

<?= $this->section('content') ?>

<div class="alert alert-light border">
    <i class="icofont-info-circle me-1"></i>
    Setiap kali periode baru dibuka, data pendaftar periode sebelumnya <strong>diarsipkan</strong> &mdash; tidak dihapus.
    Data arsip tetap dapat dilihat di dashboard dan diunduh dalam format CSV maupun Excel (lengkap 7 tabel data).
</div>

<div class="row g-4">
    <?php foreach ($periodes as $p): ?>
        <?php $q = 'periode=' . (int) $p['id_periode']; ?>
        <div class="col-md-6 col-xxl-4">
            <div class="card-setting h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="icofont-archive me-1"></i> TA <?= esc($p['tahun_ajaran']) ?></span>
                    <?php if ($p['status'] === 'aktif'): ?>
                        <span class="badge badge-aktif">Aktif</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">Arsip</span>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <div class="text-muted small mb-2"><?= esc($p['nama_periode']) ?></div>
                    <div class="row text-center g-2 mb-3">
                        <div class="col-4">
                            <div class="p-2 rounded bg-light">
                                <div class="fs-4 fw-bold"><?= (int) $p['total_pendaftar'] ?></div>
                                <div class="small text-muted">Total</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 rounded bg-light">
                                <div class="fs-4 fw-bold"><?= (int) $p['total_tsanawiyyah'] ?></div>
                                <div class="small text-muted">Tsn</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 rounded bg-light">
                                <div class="fs-4 fw-bold"><?= (int) $p['total_muallimin'] ?></div>
                                <div class="small text-muted">Mua</div>
                            </div>
                        </div>
                    </div>
                    <?php if ($p['diarsipkan_pada']): ?>
                        <div class="small text-muted mb-2">Diarsipkan: <?= date('d/m/Y H:i', strtotime($p['diarsipkan_pada'])) ?></div>
                    <?php endif; ?>

                    <div class="d-flex flex-wrap gap-2">
                        <a href="<?= base_url('dashboard?' . $q) ?>" class="btn btn-sm btn-outline-primary">
                            <i class="icofont-eye-alt me-1"></i> Lihat Data
                        </a>
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-bs-toggle="dropdown" <?= $p['total_pendaftar'] ? '' : 'disabled' ?>>
                                <i class="icofont-download me-1"></i> Unduh
                            </button>
                            <ul class="dropdown-menu">
                                <li><h6 class="dropdown-header">Excel (.xlsx)</h6></li>
                                <li><a class="dropdown-item" href="<?= base_url('dashboard/export-excel?jalur=all&' . $q) ?>">Semua Jalur</a></li>
                                <li><a class="dropdown-item" href="<?= base_url('dashboard/export-excel?jalur=tsanawiyyah&' . $q) ?>">Tsanawiyyah</a></li>
                                <li><a class="dropdown-item" href="<?= base_url('dashboard/export-excel?jalur=muallimin&' . $q) ?>">Mu'allimin</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header">CSV</h6></li>
                                <li><a class="dropdown-item" href="<?= base_url('dashboard/export-csv?jalur=all&' . $q) ?>">Semua Jalur</a></li>
                                <li><a class="dropdown-item" href="<?= base_url('dashboard/export-csv?jalur=tsanawiyyah&' . $q) ?>">Tsanawiyyah</a></li>
                                <li><a class="dropdown-item" href="<?= base_url('dashboard/export-csv?jalur=muallimin&' . $q) ?>">Mu'allimin</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header">Kartu Keluarga (ZIP)</h6></li>
                                <li><a class="dropdown-item" href="<?= base_url('dashboard/kk-zip?jalur=all&' . $q) ?>">Semua Jalur</a></li>
                                <li><a class="dropdown-item" href="<?= base_url('dashboard/kk-zip?jalur=tsanawiyyah&' . $q) ?>">Tsanawiyyah</a></li>
                                <li><a class="dropdown-item" href="<?= base_url('dashboard/kk-zip?jalur=muallimin&' . $q) ?>">Mu'allimin</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <?php if (empty($periodes)): ?>
        <div class="col-12">
            <div class="alert alert-warning">Belum ada periode.</div>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
