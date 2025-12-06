<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Landing Page
$routes->get('/', 'Landing::index');

// Pendaftaran Routes
$routes->match(['get', 'post'], 'pendaftaran/tsanawiyyah', 'Pendaftaran::tsanawiyyah');
$routes->match(['get', 'post'], 'pendaftaran/muallimin', 'Pendaftaran::muallimin');
$routes->get('pendaftaran/sukses/(:any)', 'Pendaftaran::sukses/$1');
