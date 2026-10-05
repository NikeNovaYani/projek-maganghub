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
    });

    $routes->group('layanan', ['filter' => 'csrf'], function ($routes) {
        $routes->get('/', 'Admin\Layanan::index');

        // Kategori
        $routes->post('kategori/store', 'Admin\Layanan::storeKategori');
        $routes->post('kategori/update/(:num)', 'Admin\Layanan::updateKategori/$1');
        $routes->post('kategori/delete/(:num)', 'Admin\Layanan::deleteKategori/$1');
        $routes->post('kategori/move/(:num)/(:segment)', 'Admin\Layanan::moveKategori/$1/$2');

        // Layanan (item)
        $routes->post('item/store', 'Admin\Layanan::storeItem');
        $routes->post('item/update/(:num)', 'Admin\Layanan::updateItem/$1');
        $routes->post('item/delete/(:num)', 'Admin\Layanan::deleteItem/$1');
        $routes->post('item/move/(:num)/(:segment)', 'Admin\Layanan::moveItem/$1/$2');
    });

    $routes->group('kis', ['filter' => 'csrf'], function ($routes) {
        $routes->get('/', 'Admin\Kis::index');

        // Kategori
        $routes->post('kategori/store', 'Admin\Kis::storeKategori');
        $routes->post('kategori/update/(:num)', 'Admin\Kis::updateKategori/$1');
        $routes->post('kategori/delete/(:num)', 'Admin\Kis::deleteKategori/$1');
        $routes->post('kategori/move/(:num)/(:segment)', 'Admin\Kis::moveKategori/$1/$2');

        // Aplikasi
        $routes->post('app/store', 'Admin\Kis::storeApp');
        $routes->post('app/update/(:num)', 'Admin\Kis::updateApp/$1');
        $routes->post('app/delete/(:num)', 'Admin\Kis::deleteApp/$1');
        $routes->post('app/move/(:num)/(:segment)', 'Admin\Kis::moveApp/$1/$2');
    });

    $routes->get('berita', 'Admin\Dashboard::index');
    $routes->get('kegiatan', 'Admin\Dashboard::index');
    $routes->get('kategori', 'Admin\Dashboard::index');
    $routes->get('settings', 'Admin\Settings::index');
    $routes->post('settings/save', 'Admin\Settings::save');
});
