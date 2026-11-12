<?= $this->extend('layouts/pengaturan') ?>

<?= $this->section('content') ?>

<?php
$fields = [
    'warna_primer'          => ['Warna Utama', 'Navbar, tombol utama, header tabel'],
    'warna_primer_gelap'    => ['Warna Utama (Gelap)', 'Gradasi & efek hover warna utama'],
    'warna_sekunder'        => ['Warna Aksen', 'Tombol kedua, badge, sorotan'],
    'warna_sekunder_terang' => ['Warna Aksen (Terang)', 'Gradasi warna aksen'],
];
$presets = [
    'Hijau Persis (bawaan)' => ['#1AB34A', '#158a3a', '#F3C623', '#f5d565'],
    'Biru Laut'             => ['#1E6FD9', '#1452A3', '#F5A623', '#F8C46B'],
    'Teal'                  => ['#0F9D8A', '#0B7366', '#F2C94C', '#F6DC86'],
    'Marun'                 => ['#9B1C31', '#701423', '#D4A017', '#E6C25A'],
    'Ungu'                  => ['#6B3FA0', '#4E2D77', '#F2B705', '#F6CF5A'],
    'Hijau Tua'             => ['#14532D', '#0D3B20', '#EAB308', '#FACC15'],
];
?>

<!-- Logo -->
<div class="card-setting mb-4">
    <div class="card-header"><i class="icofont-image me-1"></i> Logo Lembaga</div>
    <div class="card-body">
        <div class="row g-4 align-items-center">
            <div class="col-md-3 text-center">
                <div class="p-3 rounded bg-light d-inline-block">
                    <img src="<?= logo_url() ?>" id="logoPreview" alt="Logo saat ini" style="max-width: 160px; max-height: 160px;">
                </div>
                <div class="small text-muted mt-2"><?= logo_kustom() ? 'Logo unggahan' : 'Logo bawaan' ?></div>
            </div>
            <div class="col-md-9">
                <form method="post" action="<?= base_url('admin/pengaturan/logo') ?>" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <label for="logo" class="form-label fw-semibold">Unggah logo baru</label>
                    <input type="file" class="form-control" id="logo" name="logo" accept=".png,.jpg,.jpeg,image/png,image/jpeg" required>
                    <div class="form-text">
                        Format <strong>PNG</strong> (disarankan, latar transparan) atau JPG, maksimal 2 MB, bentuk persegi/hampir persegi.
                        Logo dipakai di halaman utama, formulir pendaftaran, halaman login, dashboard, favicon, dan dokumen PDF (kartu &amp; formulir lengkap).
                    </div>
                    <div class="d-flex flex-wrap gap-2 mt-3">
                        <button type="submit" class="btn btn-psb"><i class="icofont-upload-alt me-1"></i> Simpan Logo</button>
                        <?php if (logo_kustom()): ?>
                            <button type="submit" form="formResetLogo" class="btn btn-outline-secondary" onclick="return confirm('Kembalikan ke logo bawaan?')">
                                <i class="icofont-refresh me-1"></i> Gunakan Logo Bawaan
                            </button>
                        <?php endif; ?>
                    </div>
                </form>
                <form method="post" action="<?= base_url('admin/pengaturan/logo/reset') ?>" id="formResetLogo">
                    <?= csrf_field() ?>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-7">
        <form method="post" action="<?= base_url('admin/pengaturan/tampilan') ?>" class="card-setting" id="formWarna">
            <?= csrf_field() ?>
            <div class="card-header"><i class="icofont-paint me-1"></i> Warna Tema</div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="small text-muted mb-2">Pilihan cepat</div>
                    <div class="d-flex flex-wrap gap-2">
                        <?php foreach ($presets as $nama => $w): ?>
                            <button type="button" class="btn btn-sm btn-light border btn-preset" data-warna='<?= json_encode($w) ?>'>
                                <span class="d-inline-block rounded-circle align-middle me-1" style="width:14px;height:14px;background:<?= $w[0] ?>"></span>
                                <span class="d-inline-block rounded-circle align-middle me-1" style="width:14px;height:14px;background:<?= $w[2] ?>"></span>
                                <?= esc($nama) ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="row g-3">
                    <?php foreach ($fields as $key => [$label, $hint]): ?>
                        <?php $v = old($key, $pengaturan[$key] ?? $default[$key]); ?>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" for="<?= $key ?>"><?= esc($label) ?></label>
                            <div class="input-group">
                                <input type="color" class="form-control form-control-color picker" data-target="<?= $key ?>" value="<?= esc($v) ?>" title="Pilih warna">
                                <input type="text" class="form-control font-monospace hex" id="<?= $key ?>" name="<?= $key ?>" value="<?= esc($v) ?>"
                                    pattern="#[0-9a-fA-F]{6}" maxlength="7" required>
                            </div>
                            <div class="form-text"><?= esc($hint) ?> &middot; bawaan <code><?= esc($default[$key]) ?></code></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="card-footer bg-transparent d-flex gap-2 p-3">
                <button type="submit" class="btn btn-psb px-4"><i class="icofont-save me-1"></i> Simpan Warna</button>
                <button type="submit" form="formReset" class="btn btn-outline-secondary" onclick="return confirm('Kembalikan semua warna ke bawaan?')">
                    <i class="icofont-refresh me-1"></i> Kembalikan ke Bawaan
                </button>
            </div>
        </form>
        <form method="post" action="<?= base_url('admin/pengaturan/tampilan/reset') ?>" id="formReset">
            <?= csrf_field() ?>
        </form>
    </div>

    <div class="col-xl-5">
        <div class="card-setting">
            <div class="card-header"><i class="icofont-eye-alt me-1"></i> Pratinjau</div>
            <div class="card-body" id="preview">
                <div class="rounded-3 p-4 text-center text-white mb-3" id="pvHero">
                    <div class="d-inline-block rounded-3 px-3 py-2 mb-2" id="pvBadge" style="color:#333;font-weight:700">Tahun Ajaran <?= esc(tahun_ajaran_aktif()) ?></div>
                    <h4 class="fw-bold mb-1"><?= esc(pengaturan('hero_judul')) ?></h4>
                    <div class="small opacity-75 mb-3"><?= esc(pengaturan('hero_subjudul')) ?></div>
                    <span class="btn btn-sm rounded-pill px-3 fw-semibold text-white" id="pvBtn1" style="border:2px solid #fff">Daftar Tsanawiyyah</span>
                    <span class="btn btn-sm rounded-pill px-3 fw-semibold" id="pvBtn2" style="color:#333">Daftar Mu'allimin</span>
                </div>
                <table class="table table-sm mb-0">
                    <thead>
                        <tr id="pvThead">
                            <th style="color:#fff;background:transparent">Nama</th>
                            <th style="color:#fff;background:transparent">Jalur</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Ahmad</td>
                            <td><span class="badge" id="pvBadgeTsn">Tsanawiyyah</span></td>
                        </tr>
                        <tr>
                            <td>Fatimah</td>
                            <td><span class="badge" id="pvBadgeMua" style="color:#333">Mu'allimin</span></td>
                        </tr>
                    </tbody>
                </table>
                <p class="small text-muted mt-3 mb-0">Warna diterapkan ke halaman utama, formulir pendaftaran, statistik, pengumuman, dan dashboard panitia.</p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    (function() {
        const keys = ['warna_primer', 'warna_primer_gelap', 'warna_sekunder', 'warna_sekunder_terang'];
        const valid = (v) => /^#[0-9a-fA-F]{6}$/.test(v);
        const get = (k) => document.getElementById(k).value;

        function paint() {
            const [p, pg, s, st] = keys.map(get);
            if (![p, pg, s, st].every(valid)) return;
            document.getElementById('pvHero').style.background = `linear-gradient(135deg, ${p} 0%, ${pg} 100%)`;
            document.getElementById('pvBadge').style.background = `linear-gradient(135deg, ${s} 0%, ${st} 100%)`;
            document.getElementById('pvBtn1').style.background = `linear-gradient(135deg, ${p} 0%, ${pg} 100%)`;
            document.getElementById('pvBtn2').style.background = `linear-gradient(135deg, ${s} 0%, ${st} 100%)`;
            document.getElementById('pvThead').style.background = `linear-gradient(135deg, ${p}, ${pg})`;
            document.getElementById('pvBadgeTsn').style.background = p;
            document.getElementById('pvBadgeMua').style.background = s;
        }

        document.getElementById('logo')?.addEventListener('change', (e) => {
            const f = e.target.files && e.target.files[0];
            if (f) document.getElementById('logoPreview').src = URL.createObjectURL(f);
        });
        document.querySelectorAll('.picker').forEach((picker) => {
            picker.addEventListener('input', () => {
                document.getElementById(picker.dataset.target).value = picker.value.toUpperCase();
                paint();
            });
        });
        document.querySelectorAll('.hex').forEach((input) => {
            input.addEventListener('input', () => {
                if (valid(input.value)) {
                    document.querySelector(`.picker[data-target="${input.id}"]`).value = input.value;
                    paint();
                }
            });
        });
        document.querySelectorAll('.btn-preset').forEach((btn) => {
            btn.addEventListener('click', () => {
                JSON.parse(btn.dataset.warna).forEach((w, i) => {
                    document.getElementById(keys[i]).value = w;
                    document.querySelector(`.picker[data-target="${keys[i]}"]`).value = w;
                });
                paint();
            });
        });
        paint();
    })();
</script>
<?= $this->endSection() ?>
