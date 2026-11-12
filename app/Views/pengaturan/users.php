<?= $this->extend('layouts/pengaturan') ?>

<?= $this->section('content') ?>

<div class="card-setting">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span><i class="icofont-users-alt-3 me-1"></i> Daftar User Panitia</span>
        <a href="<?= base_url('admin/users/add') ?>" class="btn btn-psb btn-sm"><i class="icofont-plus me-1"></i> Tambah User</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Login Terakhir</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                        <?php $isSelf = (int) $u['id_admin'] === (int) session('id_admin'); ?>
                        <tr>
                            <td><strong><?= esc($u['username']) ?></strong> <?= $isSelf ? '<span class="badge bg-info text-dark">Anda</span>' : '' ?></td>
                            <td><?= esc($u['nama_lengkap'] ?? '-') ?></td>
                            <td><?= esc($u['email'] ?? '-') ?></td>
                            <td>
                                <?php $rc = ['superadmin' => 'bg-dark', 'tsanawiyyah' => 'bg-success', 'muallimin' => 'bg-warning text-dark'][$u['role_panitia']] ?? 'bg-secondary'; ?>
                                <span class="badge <?= $rc ?>"><?= esc($roles[$u['role_panitia']] ?? $u['role_panitia']) ?></span>
                            </td>
                            <td>
                                <?= $u['is_active'] ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Nonaktif</span>' ?>
                            </td>
                            <td class="small"><?= $u['last_login'] ? date('d/m/Y H:i', strtotime($u['last_login'])) : '-' ?></td>
                            <td class="text-end text-nowrap">
                                <a href="<?= base_url('admin/users/edit/' . $u['id_admin']) ?>" class="btn btn-sm btn-outline-secondary" title="Edit"><i class="icofont-edit"></i></a>
                                <?php if (!$isSelf): ?>
                                    <form method="post" action="<?= base_url('admin/users/toggle/' . $u['id_admin']) ?>" class="d-inline">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm <?= $u['is_active'] ? 'btn-outline-warning' : 'btn-outline-success' ?>" title="<?= $u['is_active'] ? 'Nonaktifkan' : 'Aktifkan' ?>">
                                            <i class="<?= $u['is_active'] ? 'icofont-lock' : 'icofont-unlock' ?>"></i>
                                        </button>
                                    </form>
                                    <form method="post" action="<?= base_url('admin/users/delete/' . $u['id_admin']) ?>" class="d-inline">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus"
                                            onclick="return confirm('Hapus user <?= esc($u['username'], 'js') ?>? Tindakan ini tidak dapat dibatalkan.')">
                                            <i class="icofont-trash"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="small text-muted mt-3">
            <strong>Panitia Tsanawiyyah</strong> dan <strong>Panitia Mu'allimin</strong> hanya dapat melihat data pendaftar jalurnya masing-masing pada periode aktif.
            <strong>Superadmin</strong> memiliki akses penuh termasuk menu Pengaturan.
        </div>
    </div>
</div>

<?= $this->endSection() ?>
