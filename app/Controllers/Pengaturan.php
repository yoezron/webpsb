<?php

namespace App\Controllers;

use App\Models\PengaturanModel;
use App\Models\PeriodeModel;

/**
 * Menu Pengaturan Sistem (khusus Superadmin)
 *  - Periode / Tahun Ajaran PSB + format nomor peserta
 *  - Buka periode baru (periode lama otomatis diarsipkan)
 *  - Arsip data pendaftar per periode (bisa diunduh)
 *  - Warna tampilan
 *  - Konten landing page & kontak
 */
class Pengaturan extends BaseController
{
    protected PeriodeModel $periodeModel;
    protected PengaturanModel $pengaturanModel;

    private const WARNA_KEYS = ['warna_primer', 'warna_primer_gelap', 'warna_sekunder', 'warna_sekunder_terang'];

    private const LANDING_TEXT_KEYS = [
        'nama_lembaga', 'nama_singkat', 'meta_deskripsi', 'meta_keywords',
        'hero_judul', 'hero_subjudul', 'hero_teks', 'badge_dibuka', 'badge_ditutup',
        'label_tombol_tsn', 'label_tombol_mua',
        'keunggulan_judul', 'keunggulan_subjudul', 'cta_judul',
        'kontak_judul', 'kontak_alamat', 'kontak_telepon', 'kontak_whatsapp', 'kontak_email',
        'kontak_website', 'kontak_jam', 'kontak_maps',
        'sosmed_instagram', 'sosmed_facebook', 'sosmed_youtube', 'sosmed_tiktok',
        'footer_teks',
        'ketua_panitia', 'jabatan_ttd', 'kota_ttd',
    ];

    private const LANDING_TOGGLE_KEYS = [
        'tampil_tombol_update', 'tampil_tombol_statistik', 'tampil_pengumuman', 'tampil_keunggulan', 'tampil_kontak',
    ];

    public function __construct()
    {
        $this->periodeModel    = new PeriodeModel();
        $this->pengaturanModel = new PengaturanModel();
    }

    public function index()
    {
        return redirect()->to(base_url('admin/pengaturan/periode'));
    }

    // =====================================================================
    // PERIODE
    // =====================================================================

    public function periode()
    {
        return view('pengaturan/periode', $this->baseData('periode', 'Periode & Tahun Ajaran') + [
            'aktif'    => $this->periodeModel->getAktif(),
            'periodes' => $this->periodeModel->getAllWithCounts(),
        ]);
    }

    public function periodeBaru()
    {
        $aktif = $this->periodeModel->getAktif();

        // Usulan nilai default: tahun ajaran berikutnya, format sama dengan periode aktif
        $mulai = $aktif ? PeriodeModel::tahunAkhir($aktif['tahun_ajaran']) : (int) date('Y') + 1;
        $usulan = [
            'tahun_ajaran'       => $mulai . '/' . ($mulai + 1),
            'nama_periode'       => 'PSB Tahun Ajaran ' . $mulai . '/' . ($mulai + 1),
            'tanggal_buka'       => '',
            'tanggal_tutup'      => '',
            'format_nomor'       => $aktif['format_nomor'] ?? PeriodeModel::DEFAULT_FORMAT,
            'prefix_tsanawiyyah' => $aktif['prefix_tsanawiyyah'] ?? 'T',
            'prefix_muallimin'   => $aktif['prefix_muallimin'] ?? 'M',
            'digit_nomor'        => $aktif['digit_nomor'] ?? 4,
            'nomor_mulai'        => 1,
            'kuota_tsanawiyyah'  => $aktif['kuota_tsanawiyyah'] ?? '',
            'kuota_muallimin'    => $aktif['kuota_muallimin'] ?? '',
            'pendaftaran_dibuka' => 1,
            'catatan'            => '',
        ];

        return view('pengaturan/periode_form', $this->baseData('periode', 'Buka Periode Baru') + [
            'mode'    => 'baru',
            'periode' => $usulan,
            'aktif'   => $aktif,
            'jumlahAktif' => $aktif ? $this->hitungPendaftar((int) $aktif['id_periode']) : 0,
        ]);
    }

    public function periodeSimpan()
    {
        $data = $this->ambilInputPeriode();
        if ($data instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $data;
        }

        if ($this->request->getPost('konfirmasi') !== 'YA') {
            return redirect()->back()->withInput()
                ->with('error', 'Centang konfirmasi pengarsipan terlebih dahulu.');
        }

        $aktifLama = $this->periodeModel->getAktif();
        $id = $this->periodeModel->bukaPeriodeBaru($data);

        if (!$id) {
            return redirect()->back()->withInput()->with('error', 'Gagal membuka periode baru. Silakan coba lagi.');
        }

        log_message('info', 'Periode baru dibuka: ' . $data['tahun_ajaran'] . ' oleh ' . session('username')
            . ($aktifLama ? ' (periode ' . $aktifLama['tahun_ajaran'] . ' diarsipkan)' : ''));

        $pesan = 'Periode Tahun Ajaran ' . $data['tahun_ajaran'] . ' berhasil dibuka.';
        if ($aktifLama) {
            $pesan .= ' Data periode ' . $aktifLama['tahun_ajaran'] . ' telah diarsipkan dan tetap dapat diunduh di menu Arsip.';
        }

        return redirect()->to(base_url('admin/pengaturan/periode'))->with('success', $pesan);
    }

    public function periodeEdit($id)
    {
        $periode = $this->periodeModel->find((int) $id);
        if (!$periode) {
            return redirect()->to(base_url('admin/pengaturan/periode'))->with('error', 'Periode tidak ditemukan.');
        }

        return view('pengaturan/periode_form', $this->baseData('periode', 'Edit Periode ' . $periode['tahun_ajaran']) + [
            'mode'    => 'edit',
            'periode' => $periode,
            'aktif'   => $this->periodeModel->getAktif(),
            'jumlahAktif' => $this->hitungPendaftar((int) $periode['id_periode']),
        ]);
    }

    public function periodeUpdate($id)
    {
        $periode = $this->periodeModel->find((int) $id);
        if (!$periode) {
            return redirect()->to(base_url('admin/pengaturan/periode'))->with('error', 'Periode tidak ditemukan.');
        }

        $data = $this->ambilInputPeriode((int) $id);
        if ($data instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $data;
        }

        if (!$this->periodeModel->update((int) $id, $data)) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan perubahan periode.');
        }
        PeriodeModel::resetCache();

        return redirect()->to(base_url('admin/pengaturan/periode'))
            ->with('success', 'Periode Tahun Ajaran ' . $data['tahun_ajaran'] . ' berhasil diperbarui.');
    }

    public function periodeAktifkan($id)
    {
        $periode = $this->periodeModel->find((int) $id);
        if (!$periode) {
            return redirect()->to(base_url('admin/pengaturan/periode'))->with('error', 'Periode tidak ditemukan.');
        }

        if (!$this->periodeModel->aktifkan((int) $id)) {
            return redirect()->back()->with('error', 'Gagal mengaktifkan periode.');
        }

        return redirect()->to(base_url('admin/pengaturan/periode'))
            ->with('success', 'Periode ' . $periode['tahun_ajaran'] . ' kini menjadi periode aktif. Periode sebelumnya telah diarsipkan.');
    }

    /**
     * Buka / tutup pendaftaran pada periode aktif.
     */
    public function periodeToggle($id)
    {
        $periode = $this->periodeModel->find((int) $id);
        if (!$periode) {
            return redirect()->to(base_url('admin/pengaturan/periode'))->with('error', 'Periode tidak ditemukan.');
        }

        $baru = empty($periode['pendaftaran_dibuka']) ? 1 : 0;
        $this->periodeModel->update((int) $id, ['pendaftaran_dibuka' => $baru]);
        PeriodeModel::resetCache();

        return redirect()->to(base_url('admin/pengaturan/periode'))->with('success', 'Pendaftaran Tahun Ajaran ' . $periode['tahun_ajaran'] . ($baru ? ' dibuka.' : ' ditutup.'));
    }

    // =====================================================================
    // ARSIP
    // =====================================================================

    public function arsip()
    {
        return view('pengaturan/arsip', $this->baseData('arsip', 'Arsip Data Pendaftar') + [
            'periodes' => $this->periodeModel->getAllWithCounts(),
        ]);
    }

    // =====================================================================
    // TAMPILAN (WARNA)
    // =====================================================================

    public function tampilan()
    {
        return view('pengaturan/tampilan', $this->baseData('tampilan', 'Logo & Warna Tampilan') + [
            'pengaturan' => $this->pengaturanModel->semua(),
            'default'    => PengaturanModel::defaults(),
        ]);
    }

    public function tampilanSimpan()
    {
        $data = [];
        foreach (self::WARNA_KEYS as $key) {
            $nilai = trim((string) $this->request->getPost($key));
            if (!preg_match('/^#[0-9a-fA-F]{6}$/', $nilai)) {
                return redirect()->back()->withInput()->with('error', 'Format warna tidak valid (gunakan format #RRGGBB).');
            }
            $data[$key] = $nilai;
        }

        $this->pengaturanModel->simpanBanyak($data);

        return redirect()->to(base_url('admin/pengaturan/tampilan'))->with('success', 'Warna tampilan berhasil disimpan.');
    }

    public function tampilanReset()
    {
        $this->pengaturanModel->resetKunci(self::WARNA_KEYS);

        return redirect()->to(base_url('admin/pengaturan/tampilan'))->with('success', 'Warna tampilan dikembalikan ke bawaan.');
    }

    // =====================================================================
    // LOGO
    // =====================================================================

    public function logoSimpan()
    {
        $rules = [
            'logo' => 'uploaded[logo]|max_size[logo,2048]|is_image[logo]|ext_in[logo,png,jpg,jpeg]|mime_in[logo,image/png,image/jpeg,image/pjpeg]',
        ];
        $messages = [
            'logo' => [
                'uploaded' => 'Pilih file logo terlebih dahulu.',
                'max_size' => 'Ukuran logo maksimal 2 MB.',
                'is_image' => 'File logo harus berupa gambar.',
                'ext_in'   => 'Logo harus berformat PNG atau JPG.',
                'mime_in'  => 'Logo harus berformat PNG atau JPG.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->to(base_url('admin/pengaturan/tampilan'))->with('error', implode(' ', $this->validator->getErrors()));
        }

        $file   = $this->request->getFile('logo');
        $folder = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'logo' . DIRECTORY_SEPARATOR;
        if (!is_dir($folder)) {
            mkdir($folder, 0755, true);
        }

        $ext  = strtolower($file->guessExtension() ?: $file->getClientExtension()) === 'png' ? 'png' : 'jpg';
        $nama = 'logo_' . date('YmdHis') . '.' . $ext;
        $file->move($folder, $nama);

        $lama = (string) $this->pengaturanModel->ambil('logo_file', '');
        $this->pengaturanModel->simpanBanyak(['logo_file' => 'uploads/logo/' . $nama]);
        $this->hapusLogoLama($lama);

        return redirect()->to(base_url('admin/pengaturan/tampilan'))->with('success', 'Logo berhasil diperbarui. Logo baru tampil di halaman utama, formulir, dashboard, dan dokumen PDF.');
    }

    public function logoReset()
    {
        $lama = (string) $this->pengaturanModel->ambil('logo_file', '');
        $this->pengaturanModel->resetKunci(['logo_file']);
        $this->hapusLogoLama($lama);

        return redirect()->to(base_url('admin/pengaturan/tampilan'))->with('success', 'Logo dikembalikan ke logo bawaan.');
    }

    private function hapusLogoLama(string $relatif): void
    {
        if ($relatif !== '' && preg_match('#^uploads/logo/[A-Za-z0-9_\-]+\.(png|jpe?g)$#', $relatif) && is_file(FCPATH . $relatif)) {
            @unlink(FCPATH . $relatif);
        }
    }

    // =====================================================================
    // LANDING PAGE
    // =====================================================================

    public function landing()
    {
        $pengaturan = $this->pengaturanModel->semua();
        $items = json_decode($pengaturan['keunggulan_items'] ?? '[]', true) ?: [];

        return view('pengaturan/landing', $this->baseData('landing', 'Tampilan Halaman Utama') + [
            'pengaturan' => $pengaturan,
            'keunggulan' => $items,
        ]);
    }

    public function landingSimpan()
    {
        $data = [];

        foreach (self::LANDING_TEXT_KEYS as $key) {
            $data[$key] = trim((string) $this->request->getPost($key));
        }
        foreach (self::LANDING_TOGGLE_KEYS as $key) {
            $data[$key] = $this->request->getPost($key) ? '1' : '0';
        }

        if ($data['nama_lembaga'] === '') {
            return redirect()->back()->withInput()->with('error', 'Nama lembaga wajib diisi.');
        }
        if ($data['kontak_email'] !== '' && !filter_var($data['kontak_email'], FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->withInput()->with('error', 'Format email kontak tidak valid.');
        }

        // Peta: terima URL embed atau kode <iframe> utuh, simpan hanya URL src dari Google Maps
        $data['kontak_maps'] = $this->normalisasiMaps($data['kontak_maps']);

        // Keunggulan (daftar dinamis)
        $ikon  = (array) $this->request->getPost('keunggulan_ikon');
        $judul = (array) $this->request->getPost('keunggulan_judul_item');
        $teks  = (array) $this->request->getPost('keunggulan_teks');
        $items = [];
        foreach ($judul as $i => $j) {
            $j = trim((string) $j);
            if ($j === '') {
                continue;
            }
            $ic = preg_replace('/[^a-z0-9\- ]/i', '', (string) ($ikon[$i] ?? '')) ?: 'icofont-star';
            $items[] = ['ikon' => trim($ic), 'judul' => $j, 'teks' => trim((string) ($teks[$i] ?? ''))];
        }
        $data['keunggulan_items'] = json_encode($items, JSON_UNESCAPED_UNICODE);

        $this->pengaturanModel->simpanBanyak($data);

        return redirect()->to(base_url('admin/pengaturan/landing'))->with('success', 'Pengaturan halaman utama berhasil disimpan.');
    }

    // =====================================================================
    // Helpers
    // =====================================================================

    private function baseData(string $menu, string $title): array
    {
        return [
            'title' => $title . ' - Pengaturan',
            'judul' => $title,
            'menu'  => $menu,
            'user'  => [
                'nama_lengkap' => session('nama_lengkap'),
                'username'     => session('username'),
                'role_panitia' => session('role_panitia'),
            ],
        ];
    }

    private function hitungPendaftar(int $idPeriode): int
    {
        return \Config\Database::connect()->table('pendaftar')
            ->where('id_periode', $idPeriode)
            ->where('deleted_at', null)
            ->countAllResults();
    }

    /**
     * Validasi & ambil input form periode.
     *
     * @return array|\CodeIgniter\HTTP\RedirectResponse
     */
    private function ambilInputPeriode(?int $idKecuali = null)
    {
        $rules = [
            'tahun_ajaran'       => 'required|regex_match[/^\d{4}\/\d{4}$/]',
            'nama_periode'       => 'permit_empty|max_length[150]',
            'tanggal_buka'       => 'permit_empty|valid_date[Y-m-d]',
            'tanggal_tutup'      => 'permit_empty|valid_date[Y-m-d]',
            'format_nomor'       => 'required|max_length[100]',
            'prefix_tsanawiyyah' => 'permit_empty|max_length[10]|alpha_numeric_punct',
            'prefix_muallimin'   => 'permit_empty|max_length[10]|alpha_numeric_punct',
            'digit_nomor'        => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[8]',
            'nomor_mulai'        => 'required|integer|greater_than_equal_to[1]',
            'kuota_tsanawiyyah'  => 'permit_empty|integer|greater_than_equal_to[0]',
            'kuota_muallimin'    => 'permit_empty|integer|greater_than_equal_to[0]',
        ];
        $messages = [
            'tahun_ajaran' => [
                'required'    => 'Tahun ajaran wajib diisi.',
                'regex_match' => 'Format tahun ajaran harus seperti 2027/2028.',
            ],
            'format_nomor' => ['required' => 'Format nomor peserta wajib diisi.'],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $post = $this->request->getPost();

        $ta1 = PeriodeModel::tahunMulai($post['tahun_ajaran']);
        $ta2 = PeriodeModel::tahunAkhir($post['tahun_ajaran']);
        if ($ta2 !== $ta1 + 1) {
            return redirect()->back()->withInput()->with('error', 'Tahun ajaran harus berurutan, contoh 2027/2028.');
        }

        // Tahun ajaran unik
        $dup = $this->periodeModel->where('tahun_ajaran', $post['tahun_ajaran']);
        if ($idKecuali) {
            $dup->where('id_periode !=', $idKecuali);
        }
        if ($dup->countAllResults() > 0) {
            return redirect()->back()->withInput()->with('error', 'Periode Tahun Ajaran ' . $post['tahun_ajaran'] . ' sudah ada.');
        }

        if (!empty($post['tanggal_buka']) && !empty($post['tanggal_tutup']) && $post['tanggal_tutup'] < $post['tanggal_buka']) {
            return redirect()->back()->withInput()->with('error', 'Tanggal tutup tidak boleh sebelum tanggal buka.');
        }

        $format = trim($post['format_nomor']);
        if (stripos($format, '{NOMOR}') === false) {
            return redirect()->back()->withInput()->with('error', 'Format nomor peserta wajib memuat {NOMOR} agar setiap nomor unik.');
        }
        if (preg_match('/[^A-Za-z0-9{}\-\/._]/', $format)) {
            return redirect()->back()->withInput()->with('error', 'Format nomor hanya boleh berisi huruf, angka, placeholder {..}, dan tanda - / . _');
        }

        $prefixT = trim($post['prefix_tsanawiyyah'] ?? '') ?: 'T';
        $prefixM = trim($post['prefix_muallimin'] ?? '') ?: 'M';
        if (stripos($format, '{PREFIX}') !== false && stripos($format, '{JALUR}') === false && $prefixT === $prefixM) {
            return redirect()->back()->withInput()->with('error', 'Prefix Tsanawiyyah dan Mu\'allimin harus berbeda agar nomor tidak bentrok.');
        }

        return [
            'tahun_ajaran'       => $post['tahun_ajaran'],
            'nama_periode'       => trim($post['nama_periode'] ?? '') ?: 'PSB Tahun Ajaran ' . $post['tahun_ajaran'],
            'tanggal_buka'       => $post['tanggal_buka'] ?: null,
            'tanggal_tutup'      => $post['tanggal_tutup'] ?: null,
            'format_nomor'       => $format,
            'prefix_tsanawiyyah' => $prefixT,
            'prefix_muallimin'   => $prefixM,
            'digit_nomor'        => (int) $post['digit_nomor'],
            'nomor_mulai'        => (int) $post['nomor_mulai'],
            'kuota_tsanawiyyah'  => ($post['kuota_tsanawiyyah'] ?? '') !== '' ? (int) $post['kuota_tsanawiyyah'] : null,
            'kuota_muallimin'    => ($post['kuota_muallimin'] ?? '') !== '' ? (int) $post['kuota_muallimin'] : null,
            'pendaftaran_dibuka' => !empty($post['pendaftaran_dibuka']) ? 1 : 0,
            'catatan'            => trim($post['catatan'] ?? ''),
        ];
    }

    private function normalisasiMaps(string $input): string
    {
        if ($input === '') {
            return '';
        }
        if (preg_match('/src=["\']([^"\']+)["\']/i', $input, $m)) {
            $input = $m[1];
        }
        $input = html_entity_decode(trim($input));

        if (preg_match('#^https://(www\.)?google\.[a-z.]+/maps/embed\?#i', $input)
            || preg_match('#^https://maps\.google\.[a-z.]+/#i', $input)) {
            return $input;
        }

        return '';
    }
}
