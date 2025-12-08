<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Landing Page
$routes->get('/', 'Landing::index');

// Authentication Routes
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attemptLogin');
$routes->get('logout', 'Auth::logout', ['filter' => 'auth']);
$routes->get('unauthorized', 'Auth::unauthorized');

// Dashboard Routes (Protected by Auth Filter)
$routes->group('dashboard', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Dashboard::index');
    $routes->get('tsanawiyyah', 'Dashboard::tsanawiyyah', ['filter' => 'role:superadmin,tsanawiyyah']);
    $routes->get('muallimin', 'Dashboard::muallimin', ['filter' => 'role:superadmin,muallimin']);
});

// Admin Management Routes (Superadmin Only)
$routes->group('admin', ['filter' => 'auth,role:superadmin'], function ($routes) {
    $routes->get('/', 'Admin::index');
    $routes->get('users', 'Admin::users');
    $routes->get('users/add', 'Admin::addUser');
    $routes->post('users/create', 'Admin::createUser');
    $routes->get('users/edit/(:num)', 'Admin::editUser/$1');
    $routes->post('users/update/(:num)', 'Admin::updateUser/$1');
    $routes->get('users/delete/(:num)', 'Admin::deleteUser/$1');

    // Pengumuman Management Routes
    $routes->get('pengumuman', 'Pengumuman::index');
    $routes->get('pengumuman/create', 'Pengumuman::create');
    $routes->post('pengumuman/store', 'Pengumuman::store');
    $routes->get('pengumuman/edit/(:num)', 'Pengumuman::edit/$1');
    $routes->post('pengumuman/update/(:num)', 'Pengumuman::update/$1');
    $routes->get('pengumuman/delete/(:num)', 'Pengumuman::delete/$1');
    $routes->get('pengumuman/view/(:num)', 'Pengumuman::view/$1');
    $routes->post('pengumuman/reply/(:num)', 'Pengumuman::adminReply/$1');
    $routes->get('pengumuman/delete-reply/(:num)', 'Pengumuman::deleteReply/$1');
    $routes->get('pengumuman/toggle-approval/(:num)', 'Pengumuman::toggleApproval/$1');
});

// Public Pengumuman Routes
$routes->get('pengumuman', 'PengumumanPublic::index');
$routes->get('pengumuman/(:num)', 'PengumumanPublic::show/$1');

// API Pengumuman Routes (Public)
$routes->group('api/pengumuman', function ($routes) {
    $routes->post('reply/(:num)', 'PengumumanPublic::submitReply/$1');
    $routes->post('like/(:num)', 'PengumumanPublic::toggleLikeAnnouncement/$1');
    $routes->post('like-reply/(:num)', 'PengumumanPublic::toggleLikeReply/$1');
    $routes->get('landing', 'PengumumanPublic::getForLanding');
});

// Pendaftaran Routes (Old - 2 Step Form)
$routes->match(['GET', 'POST'], 'pendaftaran/tsanawiyyah', 'Pendaftaran::tsanawiyyah');
$routes->match(['GET', 'POST'], 'pendaftaran/muallimin', 'Pendaftaran::muallimin');
$routes->get('pendaftaran/sukses/(:any)', 'Pendaftaran::sukses/$1');

// Pendaftaran Routes (New - Complete 8 Step Form)
$routes->get('daftar/tsanawiyyah', 'PendaftaranLengkap::tsanawiyyah');
$routes->get('daftar/muallimin', 'PendaftaranLengkap::muallimin');
$routes->post('pendaftaran/submit/(:any)', 'PendaftaranLengkap::submit/$1');
$routes->get('pendaftaran/download-pdf/(:any)', 'PendaftaranLengkap::downloadPdf/$1');
$routes->get('pendaftaran/download-kartu/(:any)', 'PendaftaranLengkap::downloadKartu/$1');
$routes->get('pendaftaran/success/(:any)', 'PendaftaranLengkap::sukses/$1');

// ============================================================
// Authentication Routes
// ============================================================
$routes->get('auth/login', 'Auth::login');
$routes->post('auth/login', 'Auth::attemptLogin');
$routes->get('auth/logout', 'Auth::logout');

// ============================================================
// Dashboard Routes (Protected)
// ============================================================
$routes->group('dashboard', ['filter' => 'auth'], function ($routes) {
    // Main dashboard - redirects based on role
    $routes->get('/', 'Dashboard::index');

    // Tsanawiyyah dashboard - accessible by tsanawiyyah role and superadmin
    $routes->get('tsanawiyyah', 'Dashboard::tsanawiyyah', ['filter' => 'role:tsanawiyyah,superadmin']);

    // Muallimin dashboard - accessible by muallimin role and superadmin
    $routes->get('muallimin', 'Dashboard::muallimin', ['filter' => 'role:muallimin,superadmin']);

    // Detail pendaftar
    $routes->get('detail/(:num)', 'Dashboard::detail/$1');

    // Export routes
    $routes->get('export-csv', 'Dashboard::exportCsv');
    $routes->get('export-excel', 'Dashboard::exportExcel');
    $routes->get('export/(:any)', 'Dashboard::export/$1');
});
