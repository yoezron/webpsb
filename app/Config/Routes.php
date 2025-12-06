<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Landing Page
$routes->get('/', 'Landing::index');

// Pendaftaran Routes (will be implemented in future sprints)
// $routes->get('/pendaftaran/tsanawiyyah', 'Pendaftaran::tsanawiyyah');
// $routes->get('/pendaftaran/muallimin', 'Pendaftaran::muallimin');
