<?= $this->extend('layouts/pengaturan') ?>

<?= $this->section('content') ?>

<?php
$isEdit = $mode === 'edit';
$isSelf = $isEdit && (int) $item['id_admin'] === (int) session('id_admin');
$v = fn (string $k) => old($k, $item[$k] ?? '');
$action = $isEdit ? base_url('admin/users/update/' . $item['id_admin']) : base_url('admin/users/create');
?>

<div class="card-setting" style="max-width: 720px;">
    <div class="card-header"><i class="icofont-user-alt-4 me-1"></i> <?= $isEdit ? 'Data User' : 'User Baru' ?></div>
    <div class="card-body">
        <form method="post" action="<?= $action ?>" autocomplete="off">
            <?= csrf_field() ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                    <input type="text" name="username" class="form-control" required minlength="3" maxlength="50" pattern="[A-Za-z0-9_\-]+" value="<?= esc($v('username')) ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama_lengkap" class="form-control" required maxlength="150" value="<?= esc($v('nama_lengkap')) ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control" maxlength="100" value="<?= esc($v('email')) ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
                    <select name="role_panitia" class="form-select" <?= $isSelf ? 'disabled' : '' ?>>
                        <?php foreach ($roles as $key => $label): ?>
                            <option value="<?= $key ?>" <?= $v('role_panitia') === $key ? 'selected' : '' ?>><?= esc($label) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if ($isSelf): ?>
                        <input type="hidden" name="role_panitia" value="<?= esc($item['role_panitia']) ?>">
                        <div class="form-text">Anda tidak dapat mengubah role akun sendiri.</div>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Password <?= $isEdit ? '' : '<span class="text-danger">*</span>' ?></label>
                    <input type="password" name="password" class="form-control" minlength="8" autocomplete="new-password" <?= $isEdit ? '' : 'required' ?>>
                    <div class="form-text"><?= $isEdit ? 'Kosongkan bila tidak ingin mengganti password.' : 'Minimal 8 karakter.' ?></div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Konfirmasi Password</label>
                    <input type="password" name="password_confirm" class="form-control" minlength="8" autocomplete="new-password" <?= $isEdit ? '' : 'required' ?>>
                </div>
                <?php if (!$isSelf): ?>
                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" <?= (string) $v('is_active') === '1' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="is_active">Akun aktif (dapat login)</label>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-psb px-4"><i class="icofont-save me-1"></i> Simpan</button>
                <a href="<?= base_url('admin/users') ?>" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
