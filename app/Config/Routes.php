<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Public Routes
$routes->get('/', 'Home::index');
$routes->get('struktur/staf/(:num)', 'Struktur::get_staf/$1');

// Shield Auth Routes (bawaan Shield)
service('auth')->routes($routes);

// Admin Routes (Protected with Shield Session Filter)
$routes->group('admin', ['filter' => 'session'], function ($routes) {
    $routes->get('/', 'Admin\Dashboard::index');
    $routes->get('dashboard', 'Admin\Dashboard::index');
    
    // Placeholder groups yang akan dihubungkan di tahap selanjutnya
    $routes->get('organisasi', 'Admin\Dashboard::index');
    $routes->get('layanan', 'Admin\Dashboard::index');
    $routes->get('kis', 'Admin\Dashboard::index');
    $routes->get('berita', 'Admin\Dashboard::index');
    $routes->get('kegiatan', 'Admin\Dashboard::index');
    $routes->get('kategori', 'Admin\Dashboard::index');
    $routes->get('settings', 'Admin\Settings::index');
    $routes->post('settings/save', 'Admin\Settings::save');
});
