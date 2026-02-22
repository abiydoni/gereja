<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('login', 'Auth::login');
$routes->post('auth/loginProcess', 'Auth::loginProcess');
$routes->get('logout', 'Auth::logout');

$routes->get('liturgi', 'Liturgi::index');
$routes->get('liturgi/(:num)', 'Liturgi::detail/$1');
$routes->get('liturgi/bi/(:num)', 'Liturgi::detail/$1');
$routes->get('kegiatan', 'Kegiatan::index');
$routes->get('warta', 'Warta::index');
// $routes->get('informasi', 'Informasi::index');
$routes->get('jadwal', 'Jadwal::index');
$routes->get('artikel', 'Artikel::index');
$routes->get('artikel/(:segment)', 'Artikel::detail/$1');

// Renungan Routes (Frontend)
$routes->get('renungan', 'Renungan::index');
$routes->get('renungan/arsip', 'Renungan::arsip');
$routes->get('renungan/(:num)', 'Renungan::detail/$1');

$routes->get('galeri', 'Galeri::index');
$routes->get('diskusi', 'Diskusi::index');
$routes->get('diskusi/(:num)', 'Diskusi::detail/$1');
$routes->post('diskusi/submit_topic', 'Diskusi::submit_topic');
$routes->post('diskusi/submit_reply/(:num)', 'Diskusi::submit_reply/$1');

// Kidung Jemaat Routes (Frontend)
$routes->get('kidung', 'Kidung::index');
$routes->get('kidung/(:num)', 'Kidung::detail/$1');

// Migration Tool (Temporary)
$routes->get('kidung_migrate', 'KidungMigrate::index');
$routes->post('kidung_migrate/process', 'KidungMigrate::process');


$routes->group('dashboard', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Dashboard::index');
    
    // Renungan Routes
    $routes->group('renungan', ['namespace' => 'App\Controllers\Dashboard'], function($routes){
        $routes->get('/', 'Renungan::index');
        $routes->get('create', 'Renungan::create');
        $routes->post('store', 'Renungan::store');
        $routes->get('edit/(:num)', 'Renungan::edit/$1');
        $routes->post('update/(:num)', 'Renungan::update/$1');
        $routes->get('delete/(:num)', 'Renungan::delete/$1');
    });

    // Jadwal Routes
    // Jadwal Service (Roster)
    $routes->get('jadwal_pelayanan', 'Dashboard\JadwalPelayanan::index');
    $routes->get('jadwal_pelayanan/create', 'Dashboard\JadwalPelayanan::create');
    $routes->post('jadwal_pelayanan/store', 'Dashboard\JadwalPelayanan::store');
    $routes->get('jadwal_pelayanan/edit/(:num)', 'Dashboard\JadwalPelayanan::edit/$1');
    $routes->post('jadwal_pelayanan/update/(:num)', 'Dashboard\JadwalPelayanan::update/$1');
    $routes->get('jadwal_pelayanan/delete/(:num)', 'Dashboard\JadwalPelayanan::delete/$1');
    
    // Redirect old route to new roster route (prevent 404)
    $routes->addRedirect('jadwal', 'dashboard/jadwal_pelayanan');
    $routes->addRedirect('jadwal/(:any)', 'dashboard/jadwal_pelayanan');

    // Jadwal Routine
    $routes->get('jadwal_rutin', 'Dashboard\JadwalRutin::index');
    $routes->get('jadwal_rutin/create', 'Dashboard\JadwalRutin::create');
    $routes->post('jadwal_rutin/store', 'Dashboard\JadwalRutin::store');
    $routes->get('jadwal_rutin/edit/(:num)', 'Dashboard\JadwalRutin::edit/$1');
    $routes->post('jadwal_rutin/update/(:num)', 'Dashboard\JadwalRutin::update/$1');
    $routes->get('jadwal_rutin/delete/(:num)', 'Dashboard\JadwalRutin::delete/$1');

    // Kegiatan Routes
    $routes->group('kegiatan', ['namespace' => 'App\Controllers\Dashboard'], function($routes){
        $routes->get('/', 'Kegiatan::index');
        $routes->get('create', 'Kegiatan::create');
        $routes->post('store', 'Kegiatan::store');
        $routes->get('edit/(:num)', 'Kegiatan::edit/$1');
        $routes->post('update/(:num)', 'Kegiatan::update/$1');
        $routes->get('delete/(:num)', 'Kegiatan::delete/$1');
    });

    // Majelis Routes
    $routes->group('majelis', ['namespace' => 'App\Controllers\Dashboard'], function($routes){
        $routes->get('/', 'Majelis::index');
        $routes->get('create', 'Majelis::create');
        $routes->post('store', 'Majelis::store');
        $routes->get('edit/(:num)', 'Majelis::edit/$1');
        $routes->post('update/(:num)', 'Majelis::update/$1');
        $routes->get('delete/(:num)', 'Majelis::delete/$1');
    });

    // Informasi Lain Routes
    $routes->group('informasi', ['namespace' => 'App\Controllers\Dashboard'], function($routes){
        $routes->get('/', 'Informasi::index');
        $routes->get('create', 'Informasi::create');
        $routes->post('store', 'Informasi::store');
        $routes->get('edit/(:num)', 'Informasi::edit/$1');
        $routes->post('update/(:num)', 'Informasi::update/$1');
        $routes->get('delete/(:num)', 'Informasi::delete/$1');
    });

    // Liturgi Routes
    $routes->group('liturgi', ['namespace' => 'App\Controllers\Dashboard'], function($routes){
        $routes->get('/', 'Liturgi::index');
        $routes->get('create', 'Liturgi::create');
        $routes->post('store', 'Liturgi::store');
        $routes->get('edit/(:num)', 'Liturgi::edit/$1');
        $routes->post('update/(:num)', 'Liturgi::update/$1');
        $routes->get('delete/(:num)', 'Liturgi::delete/$1');
    });

    // Persembahan Routes
    $routes->group('persembahan', ['namespace' => 'App\Controllers\Dashboard'], function($routes){
        $routes->get('/', 'Persembahan::index');
        $routes->get('create', 'Persembahan::create');
        $routes->post('store', 'Persembahan::store');
        $routes->get('edit/(:num)', 'Persembahan::edit/$1');
        $routes->post('update/(:num)', 'Persembahan::update/$1');
        $routes->get('delete/(:num)', 'Persembahan::delete/$1');
        $routes->get('post/(:num)', 'Persembahan::post/$1');
        $routes->get('check-kehadiran', 'Persembahan::checkKehadiran');
    });

    // Master Persembahan Routes
    $routes->group('master_persembahan', ['namespace' => 'App\Controllers\Dashboard'], function($routes){
        $routes->get('/', 'MasterPersembahan::index');
        $routes->post('store', 'MasterPersembahan::store');
        $routes->post('update/(:num)', 'MasterPersembahan::update/$1');
        $routes->get('delete/(:num)', 'MasterPersembahan::delete/$1');
    });

    // Keuangan Routes
    $routes->group('keuangan', ['namespace' => 'App\Controllers\Dashboard'], function($routes){
        $routes->get('/', 'Keuangan::index');
        // Laporan Keuangan
        $routes->get('create_laporan', 'Keuangan::create_laporan');
        $routes->post('store_laporan', 'Keuangan::store_laporan');
        $routes->get('edit_laporan/(:num)', 'Keuangan::edit_laporan/$1');
        $routes->post('update_laporan/(:num)', 'Keuangan::update_laporan/$1');
        $routes->get('delete_laporan/(:num)', 'Keuangan::delete_laporan/$1');
    });

    // Profil Gereja Routes
    $routes->group('gereja', ['namespace' => 'App\Controllers\Dashboard'], function($routes){
        $routes->get('/', 'Gereja::index');
        $routes->post('update', 'Gereja::update');
    });

    // Logs Routes
    $routes->get('logs', 'Dashboard\Logs::index');

    // Artikel Routes
    $routes->group('artikel', ['namespace' => 'App\Controllers\Dashboard'], function($routes){
        $routes->get('/', 'Artikel::index');
        $routes->get('create', 'Artikel::create');
        $routes->post('store', 'Artikel::store');
        $routes->get('edit/(:num)', 'Artikel::edit/$1');
        $routes->post('update/(:num)', 'Artikel::update/$1');
        $routes->get('delete/(:num)', 'Artikel::delete/$1');
    });

    // Galeri Routes
    $routes->group('galeri', ['namespace' => 'App\Controllers\Dashboard'], function($routes){
        $routes->get('/', 'Galeri::index');
        $routes->get('create', 'Galeri::create');
        $routes->post('store', 'Galeri::store');
        $routes->get('edit/(:num)', 'Galeri::edit/$1');
        $routes->post('update/(:num)', 'Galeri::update/$1');
        $routes->get('delete/(:num)', 'Galeri::delete/$1');
    });

    // Diskusi Routes
    $routes->group('diskusi', ['namespace' => 'App\Controllers\Dashboard'], function($routes){
        $routes->get('/', 'Diskusi::index');
        $routes->get('replies/(:num)', 'Diskusi::replies/$1');
        $routes->get('delete_topic/(:num)', 'Diskusi::delete_topic/$1');
        $routes->get('delete_reply/(:num)', 'Diskusi::delete_reply/$1');
        $routes->get('update_status/(:num)', 'Diskusi::update_status/$1');
    });

    // Users Routes
    $routes->group('users', ['namespace' => 'App\Controllers\Dashboard'], function($routes){
        $routes->get('/', 'Users::index');
        $routes->get('create', 'Users::create');
        $routes->post('store', 'Users::store');
        $routes->get('edit/(:num)', 'Users::edit/$1');
        $routes->post('update/(:num)', 'Users::update/$1');
        $routes->get('delete/(:num)', 'Users::delete/$1');
    });

    // Jemaat Routes
    $routes->group('jemaat', ['namespace' => 'App\Controllers\Dashboard'], function($routes){
        $routes->get('/', 'Jemaat::index');
        $routes->get('create', 'Jemaat::create');
        $routes->post('store', 'Jemaat::store');
        $routes->get('edit/(:num)', 'Jemaat::edit/$1');
        $routes->post('update/(:num)', 'Jemaat::update/$1');
        $routes->get('delete/(:num)', 'Jemaat::delete/$1');
    });

    // Konfigurasi Frontend Routes
    $routes->group('konfigurasi', ['namespace' => 'App\Controllers\Dashboard'], function($routes){
        $routes->get('/', 'Konfigurasi::index');
        $routes->get('create', 'Konfigurasi::create');
        $routes->post('store', 'Konfigurasi::store');
        $routes->get('edit/(:num)', 'Konfigurasi::edit/$1');
        $routes->post('update/(:num)', 'Konfigurasi::update/$1');
        $routes->delete('delete/(:num)', 'Konfigurasi::delete/$1'); // For _method=DELETE
        $routes->post('delete/(:num)', 'Konfigurasi::delete/$1'); // Fallback for direct POST
        $routes->post('toggle/(:num)', 'Konfigurasi::toggle/$1');
    });

    // System Utils
    $routes->post('system/toggleStatus/(:segment)/(:num)', 'Dashboard\System::toggleStatus/$1/$2');

    // Future routes
    // $routes->get('gereja', 'DashboardGereja::index');
});
