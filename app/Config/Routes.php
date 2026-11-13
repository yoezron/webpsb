<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ============================================================
// Halaman Publik
// ============================================================
$routes->get('/', 'Landing::index');

// Pendaftaran (formulir 8 langkah)
$routes->get('daftar/tsanawiyyah', 'PendaftaranLengkap::tsanawiyyah');
$routes->get('daftar/muallimin', 'PendaftaranLengkap::muallimin');
$routes->post('pendaftaran/submit/(:any)', 'PendaftaranLengkap::submit/$1');
$routes->get('pendaftaran/success/(:any)', 'PendaftaranLengkap::sukses/$1');
$routes->get('pendaftaran/download-pdf/(:any)', 'PendaftaranLengkap::downloadPdf/$1');
$routes->get('pendaftaran/download-kartu/(:any)', 'PendaftaranLengkap::downloadKartu/$1');

// URL formulir lama (2 langkah) dialihkan ke formulir baru agar tautan lama tetap berfungsi
$routes->addRedirect('pendaftaran/tsanawiyyah', 'daftar/tsanawiyyah');
$routes->addRedirect('pendaftaran/muallimin', 'daftar/muallimin');

// Perbarui data mandiri oleh pendaftar
$routes->get('update-data', 'UpdateDataPendaftar::index');
$routes->post('update-data/verifikasi', 'UpdateDataPendaftar::verifikasi');
$routes->get('update-data/edit', 'UpdateDataPendaftar::edit');
$routes->post('update-data/update', 'UpdateDataPendaftar::update');
$routes->get('update-data/sukses/(:any)', 'UpdateDataPendaftar::sukses/$1');
$routes->get('update-data/batal', 'UpdateDataPendaftar::batal');

// Pengumuman publik
$routes->get('pengumuman', 'PengumumanPublic::index');
$routes->get('pengumuman/(:num)', 'PengumumanPublic::show/$1');
$routes->group('api/pengumuman', static function ($routes) {
    $routes->post('reply/(:num)', 'PengumumanPublic::submitReply/$1');
    $routes->post('like/(:num)', 'PengumumanPublic::toggleLikeAnnouncement/$1');
    $routes->post('like-reply/(:num)', 'PengumumanPublic::toggleLikeReply/$1');
    $routes->get('landing', 'PengumumanPublic::getForLanding');
});

// Statistik publik
$routes->get('statistik', 'StatisticsPublic::index');
$routes->group('api/statistik', static function ($routes) {
    $routes->get('jalur', 'StatisticsPublic::getByJalur');
    $routes->get('tanggal', 'StatisticsPublic::getByTanggalDaftar');
    $routes->get('usia', 'StatisticsPublic::getByUsia');
    $routes->get('desa', 'StatisticsPublic::getByDesa');
    $routes->get('jarak', 'StatisticsPublic::getByJarak');
    $routes->get('waktu', 'StatisticsPublic::getByWaktuTempuh');
    $routes->get('sekolah', 'StatisticsPublic::getByStatusSekolah');
    $routes->get('penghasilan', 'StatisticsPublic::getByPenghasilan');
    $routes->get('table', 'StatisticsPublic::getTableData');
});

// ============================================================
// Autentikasi
// ============================================================
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attemptLogin');
$routes->get('logout', 'Auth::logout', ['filter' => 'auth']);
$routes->get('unauthorized', 'Auth::unauthorized');

// Alias lama
$routes->addRedirect('auth/login', 'login');
$routes->addRedirect('auth/logout', 'logout');

// ============================================================
// Dashboard Panitia (login wajib)
// Hak akses per jalur untuk detail/edit/hapus/ekspor diperiksa di controller.
// ============================================================
$routes->group('dashboard', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'Dashboard::index');
    $routes->get('tsanawiyyah', 'Dashboard::tsanawiyyah', ['filter' => 'role:tsanawiyyah,superadmin']);
    $routes->get('muallimin', 'Dashboard::muallimin', ['filter' => 'role:muallimin,superadmin']);

    // Data pendaftar
    $routes->get('detail/(:num)', 'Dashboard::detail/$1');
    $routes->get('edit/(:num)', 'Dashboard::edit/$1');
    $routes->post('update/(:num)', 'Dashboard::update/$1');
    $routes->post('delete/(:num)', 'Dashboard::delete/$1');

    // Ekspor
    $routes->get('export-csv', 'Dashboard::exportCsv');
    $routes->get('export-excel', 'Dashboard::exportExcel');

    // File Kartu Keluarga (lihat/unduh satu file & unduh ZIP sesuai filter)
    $routes->get('kk/(:num)', 'Dashboard::fileKk/$1');
    $routes->get('kk-zip', 'Dashboard::downloadKkZip');
});

// ============================================================
// Superadmin
// ============================================================
$routes->group('admin', ['filter' => ['auth', 'role:superadmin']], static function ($routes) {
    $routes->get('/', 'Admin::index');

    // User panitia
    $routes->get('users', 'Admin::users');
    $routes->get('users/add', 'Admin::addUser');
    $routes->post('users/create', 'Admin::createUser');
    $routes->get('users/edit/(:num)', 'Admin::editUser/$1');
    $routes->post('users/update/(:num)', 'Admin::updateUser/$1');
    $routes->post('users/delete/(:num)', 'Admin::deleteUser/$1');
    $routes->post('users/toggle/(:num)', 'Admin::toggleUser/$1');

    // Pengaturan sistem
    $routes->get('pengaturan', 'Pengaturan::index');
    $routes->get('pengaturan/periode', 'Pengaturan::periode');
    $routes->get('pengaturan/periode/baru', 'Pengaturan::periodeBaru');
    $routes->post('pengaturan/periode/simpan', 'Pengaturan::periodeSimpan');
    $routes->get('pengaturan/periode/edit/(:num)', 'Pengaturan::periodeEdit/$1');
    $routes->post('pengaturan/periode/update/(:num)', 'Pengaturan::periodeUpdate/$1');
    $routes->post('pengaturan/periode/aktifkan/(:num)', 'Pengaturan::periodeAktifkan/$1');
    $routes->post('pengaturan/periode/toggle/(:num)', 'Pengaturan::periodeToggle/$1');
    $routes->get('pengaturan/arsip', 'Pengaturan::arsip');
    $routes->get('pengaturan/tampilan', 'Pengaturan::tampilan');
    $routes->post('pengaturan/tampilan', 'Pengaturan::tampilanSimpan');
    $routes->post('pengaturan/tampilan/reset', 'Pengaturan::tampilanReset');
    $routes->post('pengaturan/logo', 'Pengaturan::logoSimpan');
    $routes->post('pengaturan/logo/reset', 'Pengaturan::logoReset');
    $routes->post('pengaturan/teks', 'Pengaturan::teksSimpan');
    $routes->post('pengaturan/teks/reset', 'Pengaturan::teksReset');
    $routes->post('pengaturan/latar', 'Pengaturan::latarSimpan');
    $routes->post('pengaturan/latar/reset', 'Pengaturan::latarReset');
    $routes->get('pengaturan/landing', 'Pengaturan::landing');
    $routes->post('pengaturan/landing', 'Pengaturan::landingSimpan');

    // Pengumuman
    $routes->get('pengumuman', 'Pengumuman::index');
    $routes->get('pengumuman/create', 'Pengumuman::create');
    $routes->post('pengumuman/store', 'Pengumuman::store');
    $routes->get('pengumuman/edit/(:num)', 'Pengumuman::edit/$1');
    $routes->post('pengumuman/update/(:num)', 'Pengumuman::update/$1');
    $routes->post('pengumuman/delete/(:num)', 'Pengumuman::delete/$1');
    $routes->get('pengumuman/view/(:num)', 'Pengumuman::view/$1');
    $routes->post('pengumuman/reply/(:num)', 'Pengumuman::adminReply/$1');
    $routes->get('pengumuman/delete-reply/(:num)', 'Pengumuman::deleteReply/$1');
    $routes->get('pengumuman/toggle-approval/(:num)', 'Pengumuman::toggleApproval/$1');
});
