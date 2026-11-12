<?= $this->extend('layouts/pengaturan') ?>

<?= $this->section('content') ?>

<?php
$v = fn (string $k) => old($k, $pengaturan[$k] ?? '');
$checked = fn (string $k) => ((string) old($k, $pengaturan[$k] ?? '0')) === '1' ? 'checked' : '';
$items = $keunggulan ?: [['ikon' => 'icofont-star', 'judul' => '', 'teks' => '']];
?>

<form method="post" action="<?= base_url('admin/pengaturan/landing') ?>">
    <?= csrf_field() ?>

    <div class="d-flex justify-content-end mb-3 gap-2">
        <a href="<?= base_url('/') ?>" target="_blank" class="btn btn-outline-secondary"><i class="icofont-external-link me-1"></i> Lihat Halaman</a>
        <button type="submit" class="btn btn-psb px-4"><i class="icofont-save me-1"></i> Simpan</button>
    </div>

    <div class="row g-4">
        <!-- Identitas -->
        <div class="col-xl-6">
            <div class="card-setting h-100">
                <div class="card-header"><i class="icofont-building-alt me-1"></i> Identitas Lembaga</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lembaga <span class="text-danger">*</span></label>
                        <input type="text" name="nama_lembaga" class="form-control" required value="<?= esc($v('nama_lembaga')) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Singkat</label>
                        <input type="text" name="nama_singkat" class="form-control" value="<?= esc($v('nama_singkat')) ?>">
                        <div class="form-text">Ditampilkan di navbar dashboard.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi (SEO)</label>
                        <textarea name="meta_deskripsi" class="form-control" rows="2"><?= esc($v('meta_deskripsi')) ?></textarea>
                    </div>
                    <div>
                        <label class="form-label fw-semibold">Kata Kunci (SEO)</label>
                        <input type="text" name="meta_keywords" class="form-control" value="<?= esc($v('meta_keywords')) ?>">
                    </div>
                </div>
            </div>
        </div>

        <!-- Hero -->
        <div class="col-xl-6">
            <div class="card-setting h-100">
                <div class="card-header"><i class="icofont-image me-1"></i> Bagian Utama (Hero)</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Judul</label>
                            <input type="text" name="hero_judul" class="form-control" value="<?= esc($v('hero_judul')) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Subjudul</label>
                            <input type="text" name="hero_subjudul" class="form-control" value="<?= esc($v('hero_subjudul')) ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Teks Sambutan</label>
                            <textarea name="hero_teks" class="form-control" rows="3"><?= esc($v('hero_teks')) ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Teks Badge (dibuka)</label>
                            <input type="text" name="badge_dibuka" class="form-control" value="<?= esc($v('badge_dibuka')) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Teks Badge (ditutup)</label>
                            <input type="text" name="badge_ditutup" class="form-control" value="<?= esc($v('badge_ditutup')) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Label Tombol Tsanawiyyah</label>
                            <input type="text" name="label_tombol_tsn" class="form-control" value="<?= esc($v('label_tombol_tsn')) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Label Tombol Mu'allimin</label>
                            <input type="text" name="label_tombol_mua" class="form-control" value="<?= esc($v('label_tombol_mua')) ?>">
                        </div>
                        <div class="col-12">
                            <div class="form-text mb-1">Tahun ajaran pada badge diambil otomatis dari periode aktif (TA <?= esc(tahun_ajaran_aktif()) ?>).</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bagian yang ditampilkan -->
        <div class="col-12">
            <div class="card-setting">
                <div class="card-header"><i class="icofont-eye-alt me-1"></i> Bagian yang Ditampilkan</div>
                <div class="card-body d-flex flex-wrap gap-4">
                    <?php foreach ([
                        'tampil_tombol_update'    => 'Tombol "Perbarui Data"',
                        'tampil_tombol_statistik' => 'Tombol "Lihat Statistik"',
                        'tampil_pengumuman'       => 'Pengumuman Terbaru',
                        'tampil_keunggulan'       => 'Keunggulan',
                        'tampil_kontak'           => 'Kontak & Peta',
                    ] as $k => $label): ?>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="<?= $k ?>" name="<?= $k ?>" value="1" <?= $checked($k) ?>>
                            <label class="form-check-label" for="<?= $k ?>"><?= esc($label) ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Keunggulan -->
        <div class="col-12">
            <div class="card-setting">
                <div class="card-header"><i class="icofont-award me-1"></i> Keunggulan</div>
                <div class="card-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Judul Bagian</label>
                            <input type="text" name="keunggulan_judul" class="form-control" value="<?= esc($v('keunggulan_judul')) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Subjudul Bagian</label>
                            <input type="text" name="keunggulan_subjudul" class="form-control" value="<?= esc($v('keunggulan_subjudul')) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Judul Ajakan (CTA)</label>
                            <input type="text" name="cta_judul" class="form-control" value="<?= esc($v('cta_judul')) ?>">
                        </div>
                    </div>

                    <div id="daftarKeunggulan">
                        <?php foreach ($items as $it): ?>
                            <div class="row g-2 align-items-start mb-2 item-keunggulan">
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="<?= esc($it['ikon'] ?? 'icofont-star') ?> ikon-preview"></i></span>
                                        <input type="text" name="keunggulan_ikon[]" class="form-control ikon-input" placeholder="icofont-star" value="<?= esc($it['ikon'] ?? '') ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="keunggulan_judul_item[]" class="form-control" placeholder="Judul" value="<?= esc($it['judul'] ?? '') ?>">
                                </div>
                                <div class="col-md-5">
                                    <textarea name="keunggulan_teks[]" class="form-control" rows="1" placeholder="Deskripsi"><?= esc($it['teks'] ?? '') ?></textarea>
                                </div>
                                <div class="col-md-1 text-end">
                                    <button type="button" class="btn btn-outline-danger btn-hapus" title="Hapus"><i class="icofont-trash"></i></button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-success" id="tambahKeunggulan"><i class="icofont-plus me-1"></i> Tambah Keunggulan</button>
                    <div class="form-text">Nama ikon memakai <a href="https://icofont.com/icons" target="_blank" rel="noopener">IcoFont</a>, mis. <code>icofont-book-alt</code>, <code>icofont-mosque</code>, <code>icofont-teacher</code>.</div>
                </div>
            </div>
        </div>

        <!-- Kontak -->
        <div class="col-xl-6">
            <div class="card-setting h-100">
                <div class="card-header"><i class="icofont-phone me-1"></i> Kontak</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Judul Bagian</label>
                            <input type="text" name="kontak_judul" class="form-control" value="<?= esc($v('kontak_judul')) ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Alamat</label>
                            <textarea name="kontak_alamat" class="form-control" rows="2"><?= esc($v('kontak_alamat')) ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Telepon</label>
                            <input type="text" name="kontak_telepon" class="form-control" placeholder="(022) 1234567" value="<?= esc($v('kontak_telepon')) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">WhatsApp</label>
                            <input type="text" name="kontak_whatsapp" class="form-control" placeholder="0812xxxxxxx" value="<?= esc($v('kontak_whatsapp')) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="kontak_email" class="form-control" value="<?= esc($v('kontak_email')) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Website</label>
                            <input type="text" name="kontak_website" class="form-control" placeholder="https://..." value="<?= esc($v('kontak_website')) ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Jam Layanan</label>
                            <input type="text" name="kontak_jam" class="form-control" placeholder="Senin - Sabtu, 08.00 - 15.00 WIB" value="<?= esc($v('kontak_jam')) ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Peta Google Maps</label>
                            <textarea name="kontak_maps" class="form-control font-monospace small" rows="2" placeholder='Tempel kode <iframe src="https://www.google.com/maps/embed?..."> atau URL embed-nya'><?= esc($v('kontak_maps')) ?></textarea>
                            <div class="form-text">Google Maps &rarr; Bagikan &rarr; Sematkan peta &rarr; salin HTML.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sosmed & Footer -->
        <div class="col-xl-6">
            <div class="card-setting h-100">
                <div class="card-header"><i class="icofont-share me-1"></i> Media Sosial &amp; Footer</div>
                <div class="card-body">
                    <div class="row g-3">
                        <?php foreach ([
                            'sosmed_instagram' => ['Instagram', 'icofont-instagram', 'https://instagram.com/...'],
                            'sosmed_facebook'  => ['Facebook', 'icofont-facebook', 'https://facebook.com/...'],
                            'sosmed_youtube'   => ['YouTube', 'icofont-youtube-play', 'https://youtube.com/@...'],
                            'sosmed_tiktok'    => ['TikTok', 'icofont-music-note', 'https://tiktok.com/@...'],
                        ] as $k => [$label, $icon, $ph]): ?>
                            <div class="col-12">
                                <label class="form-label fw-semibold"><?= esc($label) ?></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="<?= $icon ?>"></i></span>
                                    <input type="text" name="<?= $k ?>" class="form-control" placeholder="<?= esc($ph) ?>" value="<?= esc($v($k)) ?>">
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Teks Footer</label>
                            <input type="text" name="footer_teks" class="form-control" value="<?= esc($v('footer_teks')) ?>">
                            <div class="form-text">Ditampilkan setelah "&copy; <?= date('Y') ?> <?= esc(nama_lembaga()) ?>."</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kartu & Dokumen PDF -->
    <div class="card-setting mt-4">
        <div class="card-header"><i class="icofont-file-pdf me-1"></i> Kartu Pendaftaran &amp; Dokumen PDF</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Nama Penandatangan</label>
                    <input type="text" name="ketua_panitia" class="form-control" placeholder="Nama lengkap & gelar" value="<?= esc($v('ketua_panitia')) ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Jabatan Penandatangan</label>
                    <input type="text" name="jabatan_ttd" class="form-control" placeholder="Ketua Panitia PSB" value="<?= esc($v('jabatan_ttd')) ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Kota (tempat tanda tangan)</label>
                    <input type="text" name="kota_ttd" class="form-control" placeholder="Banjaran" value="<?= esc($v('kota_ttd')) ?>">
                </div>
                <div class="col-12">
                    <div class="form-text">
                        Alamat, WhatsApp, telepon, email, dan website pada kop kartu &amp; formulir PDF diambil dari bagian <strong>Kontak</strong> di atas.
                        Logo diatur di menu <a href="<?= base_url('admin/pengaturan/tampilan') ?>">Logo &amp; Warna</a>.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-psb px-4"><i class="icofont-save me-1"></i> Simpan</button>
    </div>
</form>

<template id="tplKeunggulan">
    <div class="row g-2 align-items-start mb-2 item-keunggulan">
        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-text"><i class="icofont-star ikon-preview"></i></span>
                <input type="text" name="keunggulan_ikon[]" class="form-control ikon-input" placeholder="icofont-star">
            </div>
        </div>
        <div class="col-md-3"><input type="text" name="keunggulan_judul_item[]" class="form-control" placeholder="Judul"></div>
        <div class="col-md-5"><textarea name="keunggulan_teks[]" class="form-control" rows="1" placeholder="Deskripsi"></textarea></div>
        <div class="col-md-1 text-end"><button type="button" class="btn btn-outline-danger btn-hapus" title="Hapus"><i class="icofont-trash"></i></button></div>
    </div>
</template>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    (function() {
        const list = document.getElementById('daftarKeunggulan');
        document.getElementById('tambahKeunggulan').addEventListener('click', () => {
            list.appendChild(document.getElementById('tplKeunggulan').content.cloneNode(true));
        });
        list.addEventListener('click', (e) => {
            const btn = e.target.closest('.btn-hapus');
            if (btn) btn.closest('.item-keunggulan').remove();
        });
        list.addEventListener('input', (e) => {
            if (e.target.classList.contains('ikon-input')) {
                const icon = e.target.closest('.item-keunggulan').querySelector('.ikon-preview');
                icon.className = (e.target.value.trim() || 'icofont-star') + ' ikon-preview';
            }
        });
    })();
</script>
<?= $this->endSection() ?>
