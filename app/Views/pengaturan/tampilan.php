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

<?php
$hv = fn(string $k) => old($k, $pengaturan[$k] ?? $default[$k]);
$subIkutTema = $hv('hero_warna_subjudul') === '';
$overlay     = (int) $hv('hero_overlay');
?>

<!-- Teks & Latar Halaman Utama -->
<div class="row g-4 mt-1">
    <div class="col-xl-7">
        <div class="card-setting mb-4">
            <div class="card-header"><i class="icofont-image me-1"></i> Gambar Latar Halaman Utama</div>
            <div class="card-body">
                <div class="row g-3 align-items-center">
                    <div class="col-md-4 text-center">
                        <img src="<?= latar_url() ?>" id="latarThumb" alt="Gambar latar saat ini" class="img-fluid rounded border" style="max-height: 120px; object-fit: cover;">
                        <div class="small text-muted mt-1"><?= latar_kustom() ? 'Gambar unggahan' : 'Gambar bawaan' ?></div>
                    </div>
                    <div class="col-md-8">
                        <form method="post" action="<?= base_url('admin/pengaturan/latar') ?>" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            <input type="file" class="form-control" id="latar" name="latar" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" required>
                            <div class="form-text">JPG, PNG, atau WEBP, maksimal 4 MB. Disarankan lanskap minimal 1600&times;900 px. Dipakai di bagian atas halaman utama dan bagian "Keunggulan".</div>
                            <div class="d-flex flex-wrap gap-2 mt-3">
                                <button type="submit" class="btn btn-psb"><i class="icofont-upload-alt me-1"></i> Simpan Gambar Latar</button>
                                <?php if (latar_kustom()): ?>
                                    <button type="submit" form="formResetLatar" class="btn btn-outline-secondary" onclick="return confirm('Kembalikan ke gambar latar bawaan?')">
                                        <i class="icofont-refresh me-1"></i> Gunakan Gambar Bawaan
                                    </button>
                                <?php endif; ?>
                            </div>
                        </form>
                        <form method="post" action="<?= base_url('admin/pengaturan/latar/reset') ?>" id="formResetLatar">
                            <?= csrf_field() ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <form method="post" action="<?= base_url('admin/pengaturan/teks') ?>" class="card-setting" id="formTeks">
            <?= csrf_field() ?>
            <div class="card-header"><i class="icofont-text-height me-1"></i> Warna Teks Halaman Utama</div>
            <div class="card-body">
                <div class="alert alert-light border small mb-3">
                    <i class="icofont-light-bulb me-1"></i>
                    Pilih warna teks yang kontras dengan latar. Tombol <strong>Sesuaikan Otomatis</strong> menghitung kecerahan gambar latar + lapisan warna, lalu memilih teks terang atau gelap.
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" for="hero_warna_judul">Warna Judul</label>
                        <div class="input-group">
                            <input type="color" class="form-control form-control-color picker" data-target="hero_warna_judul" value="<?= esc($hv('hero_warna_judul')) ?>">
                            <input type="text" class="form-control font-monospace hex" id="hero_warna_judul" name="hero_warna_judul" value="<?= esc($hv('hero_warna_judul')) ?>" pattern="#[0-9a-fA-F]{6}" maxlength="7" required>
                        </div>
                        <div class="form-text">Judul besar "<?= esc(pengaturan('hero_judul')) ?>"</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" for="hero_warna_teks">Warna Teks Isi</label>
                        <div class="input-group">
                            <input type="color" class="form-control form-control-color picker" data-target="hero_warna_teks" value="<?= esc($hv('hero_warna_teks')) ?>">
                            <input type="text" class="form-control font-monospace hex" id="hero_warna_teks" name="hero_warna_teks" value="<?= esc($hv('hero_warna_teks')) ?>" pattern="#[0-9a-fA-F]{6}" maxlength="7" required>
                        </div>
                        <div class="form-text">Paragraf sambutan, info pendaftaran ditutup, tombol garis, dan keterangan kecil</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" for="hero_warna_subjudul">Warna Subjudul</label>
                        <?php $subVal = $subIkutTema ? ($pengaturan['warna_sekunder_terang'] ?? $default['warna_sekunder_terang']) : $hv('hero_warna_subjudul'); ?>
                        <div class="input-group">
                            <input type="color" class="form-control form-control-color picker" data-target="hero_warna_subjudul" value="<?= esc($subVal) ?>" <?= $subIkutTema ? 'disabled' : '' ?>>
                            <input type="text" class="form-control font-monospace hex" id="hero_warna_subjudul" name="hero_warna_subjudul" value="<?= esc($subVal) ?>" pattern="#[0-9a-fA-F]{6}" maxlength="7" <?= $subIkutTema ? 'disabled' : '' ?>>
                        </div>
                        <div class="form-check mt-1">
                            <input class="form-check-input" type="checkbox" id="subjudul_ikut_tema" name="subjudul_ikut_tema" value="1" <?= $subIkutTema ? 'checked' : '' ?>>
                            <label class="form-check-label small" for="subjudul_ikut_tema">Ikuti Warna Aksen (Terang) tema</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" for="hero_overlay">Kepekatan Lapisan Warna: <span id="overlayVal"><?= $overlay ?></span>%</label>
                        <input type="range" class="form-range" id="hero_overlay" name="hero_overlay" min="0" max="100" step="5" value="<?= $overlay ?>">
                        <div class="form-text">0% = gambar latar terlihat penuh, 100% = tertutup warna tema</div>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" id="hero_bayangan_teks" name="hero_bayangan_teks" value="1" <?= $hv('hero_bayangan_teks') === '1' ? 'checked' : '' ?>>
                            <label class="form-check-label small" for="hero_bayangan_teks">Bayangan teks (membantu keterbacaan di atas gambar)</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent d-flex flex-wrap gap-2 p-3">
                <button type="submit" class="btn btn-psb px-4"><i class="icofont-save me-1"></i> Simpan Teks &amp; Latar</button>
                <button type="button" class="btn btn-outline-success" id="btnAuto"><i class="icofont-magic me-1"></i> Sesuaikan Otomatis</button>
                <button type="submit" form="formResetTeks" class="btn btn-outline-secondary" onclick="return confirm('Kembalikan warna teks halaman utama ke bawaan?')">
                    <i class="icofont-refresh me-1"></i> Kembalikan ke Bawaan
                </button>
            </div>
        </form>
        <form method="post" action="<?= base_url('admin/pengaturan/teks/reset') ?>" id="formResetTeks">
            <?= csrf_field() ?>
        </form>
    </div>

    <div class="col-xl-5">
        <div class="card-setting" style="position: sticky; top: 1rem;">
            <div class="card-header"><i class="icofont-eye-alt me-1"></i> Pratinjau Halaman Utama</div>
            <div class="card-body">
                <div id="pvLanding" class="rounded-3 p-4 text-center" style="background-size: cover; background-position: center;">
                    <h4 class="fw-bold mb-1" id="pvLJudul"><?= esc(pengaturan('hero_judul')) ?></h4>
                    <div class="mb-2" id="pvLSub"><?= esc(pengaturan('hero_subjudul')) ?></div>
                    <p class="small mb-3" id="pvLTeks" style="line-height:1.6"><?= esc(mb_strimwidth((string) pengaturan('hero_teks'), 0, 140, '…')) ?></p>
                    <div class="rounded-3 p-2 mb-3 small text-start mx-auto" id="pvLTutup" style="max-width: 320px; border: 2px solid; background: rgba(255,255,255,.15)">
                        <i class="icofont-lock"></i> <strong><?= esc(pengaturan('badge_ditutup')) ?></strong>
                    </div>
                    <span class="btn btn-sm rounded-pill px-3 fw-semibold" id="pvLBtn" style="border: 2px solid">Perbarui Data Pendaftaran</span>
                    <div class="mt-2" id="pvLKecil" style="font-size: .75rem; opacity: .85">Sudah mendaftar? Perbarui data Anda</div>
                </div>
                <p class="small text-muted mt-3 mb-0">Pratinjau memakai warna tema di atas. Gambar yang baru dipilih langsung tampil di sini, tetapi baru tersimpan setelah klik "Simpan Gambar Latar".</p>
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
            paintLanding();
        }

        // ---------- Pratinjau teks & latar halaman utama ----------
        let latarSrc = <?= json_encode(latar_url()) ?>;
        const subTema = document.getElementById('subjudul_ikut_tema');
        const overlay = document.getElementById('hero_overlay');
        const bayangan = document.getElementById('hero_bayangan_teks');
        const hexRgb = (h) => [1, 3, 5].map((i) => parseInt(h.substr(i, 2), 16));

        function paintLanding() {
            const p = get('warna_primer'), pg = get('warna_primer_gelap'), st = get('warna_sekunder_terang');
            const judul = get('hero_warna_judul'), teks = get('hero_warna_teks');
            const sub = subTema.checked ? st : get('hero_warna_subjudul');
            if (![p, pg, st, judul, teks, sub].every(valid)) return;
            const a = (overlay.value / 100).toFixed(2);
            document.getElementById('overlayVal').textContent = overlay.value;
            const pv = document.getElementById('pvLanding');
            pv.style.backgroundImage = `linear-gradient(135deg, rgba(${hexRgb(p)}, ${a}) 0%, rgba(${hexRgb(pg)}, ${a}) 100%), url("${latarSrc}")`;
            const shadow = bayangan.checked ? '1px 1px 3px rgba(0,0,0,.45)' : 'none';
            const set = (id, color) => {
                const el = document.getElementById(id);
                el.style.color = color;
                el.style.textShadow = shadow;
            };
            set('pvLJudul', judul);
            set('pvLSub', sub);
            set('pvLTeks', teks);
            set('pvLTutup', teks);
            set('pvLBtn', teks);
            set('pvLKecil', teks);
            document.getElementById('pvLTutup').style.borderColor = teks;
            document.getElementById('pvLBtn').style.borderColor = teks;
        }

        function setWarna(key, val) {
            document.getElementById(key).value = val;
            const pk = document.querySelector(`.picker[data-target="${key}"]`);
            if (pk) pk.value = val;
        }

        subTema.addEventListener('change', () => {
            const on = subTema.checked;
            document.getElementById('hero_warna_subjudul').disabled = on;
            document.querySelector('.picker[data-target="hero_warna_subjudul"]').disabled = on;
            if (on) setWarna('hero_warna_subjudul', get('warna_sekunder_terang'));
            paintLanding();
        });
        overlay.addEventListener('input', paintLanding);
        bayangan.addEventListener('change', paintLanding);

        document.getElementById('latar')?.addEventListener('change', (e) => {
            const f = e.target.files && e.target.files[0];
            if (!f) return;
            latarSrc = URL.createObjectURL(f);
            document.getElementById('latarThumb').src = latarSrc;
            paintLanding();
        });

        // Rata-rata warna gambar latar (via canvas), dicampur dengan lapisan warna tema
        function warnaRataLatar() {
            return new Promise((resolve) => {
                const img = new Image();
                img.onload = () => {
                    try {
                        const c = document.createElement('canvas');
                        c.width = 40; c.height = 40;
                        const ctx = c.getContext('2d');
                        ctx.drawImage(img, 0, 0, 40, 40);
                        const d = ctx.getImageData(0, 0, 40, 40).data;
                        let r = 0, g = 0, b = 0, n = 0;
                        for (let i = 0; i < d.length; i += 4) { r += d[i]; g += d[i + 1]; b += d[i + 2]; n++; }
                        resolve([r / n, g / n, b / n]);
                    } catch (err) { resolve(null); }
                };
                img.onerror = () => resolve(null);
                img.src = latarSrc;
            });
        }
        const luminance = ([r, g, b]) => {
            const f = (v) => { v /= 255; return v <= 0.03928 ? v / 12.92 : Math.pow((v + 0.055) / 1.055, 2.4); };
            return 0.2126 * f(r) + 0.7152 * f(g) + 0.0722 * f(b);
        };

        document.getElementById('btnAuto').addEventListener('click', async () => {
            const p = hexRgb(get('warna_primer')), pg = hexRgb(get('warna_primer_gelap'));
            const lapisan = p.map((v, i) => (v + pg[i]) / 2);
            const a = overlay.value / 100;
            const gambar = (await warnaRataLatar()) || lapisan;
            const campur = lapisan.map((v, i) => v * a + gambar[i] * (1 - a));
            const L = luminance(campur);
            // Pilih teks putih atau gelap, mana yang kontrasnya lebih tinggi
            const kontrasPutih = 1.05 / (L + 0.05);
            const kontrasGelap = (L + 0.05) / (luminance([31, 41, 55]) + 0.05);
            if (kontrasPutih >= kontrasGelap) {
                setWarna('hero_warna_judul', '#FFFFFF');
                setWarna('hero_warna_teks', '#FFFFFF');
                subTema.checked = true;
            } else {
                setWarna('hero_warna_judul', '#111827');
                setWarna('hero_warna_teks', '#1F2937');
                subTema.checked = false;
                setWarna('hero_warna_subjudul', get('warna_primer_gelap').toUpperCase());
            }
            subTema.dispatchEvent(new Event('change'));
            bayangan.checked = Math.max(kontrasPutih, kontrasGelap) < 7;
            paintLanding();
        });

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
