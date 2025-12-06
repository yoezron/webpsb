<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Landing Page
$routes->get('/', 'Landing::index');

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
