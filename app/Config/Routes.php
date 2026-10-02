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
    
    $routes->group('organisasi', ['filter' => 'csrf'], function ($routes) {
        $routes->get('/', 'Admin\Organisasi::index');
        $routes->get('create', 'Admin\Organisasi::create');
        $routes->post('store', 'Admin\Organisasi::store');
        $routes->get('edit/(:num)', 'Admin\Organisasi::edit/$1');
        $routes->post('update/(:num)', 'Admin\Organisasi::update/$1');
        $routes->post('delete/(:num)', 'Admin\Organisasi::delete/$1');
        $routes->post('move/(:num)/(:segment)', 'Admin\Organisasi::move/$1/$2');
        $routes->get('anggota/create/(:num)', 'Admin\Organisasi::createMember/$1');
        $routes->post('anggota/store/(:num)', 'Admin\Organisasi::storeMember/$1');
        $routes->get('anggota/edit/(:num)/(:num)', 'Admin\Organisasi::editMember/$1/$2');
        $routes->post('anggota/update/(:num)/(:num)', 'Admin\Organisasi::updateMember/$1/$2');
        $routes->post('anggota/delete/(:num)/(:num)', 'Admin\Organisasi::deleteMember/$1/$2');
        $routes->get('layanan', 'Admin\Layanan::index');
    });
    $routes->get('layanan', 'Admin\Layanan::index');
    $routes->get('kis', 'Admin\Dashboard::index');
    $routes->get('berita', 'Admin\Dashboard::index');
    $routes->get('kegiatan', 'Admin\Dashboard::index');
    $routes->get('kategori', 'Admin\Dashboard::index');
    $routes->get('settings', 'Admin\Settings::index');
    $routes->post('settings/save', 'Admin\Settings::save');
});
