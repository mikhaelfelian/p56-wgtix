<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', to: 'Frontend::index');

// Frontend routes
$routes->group('frontend', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('/', 'Frontend::index');
    $routes->post('register', 'Frontend::register');
    $routes->get('payment-success/(:num)', 'Frontend::paymentSuccess/$1');
    $routes->get('payment-failed/(:num)', 'Frontend::paymentFailed/$1');
});

// Auth routes
$routes->get('auth/login', 'Auth::login');
$routes->post('auth/cek_login', 'Auth::cek_login');
$routes->get('auth/logout', 'Auth::logout');
$routes->get('auth/forgot-password', 'Auth::forgot_password');
$routes->post('auth/forgot-password', 'Auth::forgot_password');

// Auth routes admin
$routes->group('admin', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('/', 'Auth::index');
    $routes->get('login', 'Auth::login');
    $routes->post('cek_login', 'Auth::cek_login');
    $routes->get('logout', 'Auth::logout');
    $routes->get('forgot-password', 'Auth::forgot_password');
    $routes->post('forgot-password', 'Auth::forgot_password');

    // Admin dashboard
    $routes->get('dashboard', 'Dashboard::index');

    // Admin Master Platform routes
    $routes->group('master', ['namespace' => 'App\Controllers\Master'], function ($routes) {
        $routes->group('platform', function ($routes) {
            $routes->get('/', 'Platform::index');
            $routes->get('create', 'Platform::create');
            $routes->post('store', 'Platform::store');
            $routes->get('edit/(:num)', 'Platform::edit/$1');
            $routes->post('update/(:num)', 'Platform::update/$1');
            $routes->get('show/(:num)', 'Platform::show/$1');
            $routes->get('delete/(:num)', 'Platform::delete/$1');
        });
    });

    // Admin Kategori routes
    $routes->group('kategori', ['namespace' => 'App\Controllers\Admin'], function ($routes) {
        $routes->get('/', 'Kategori::index');
        $routes->get('create', 'Kategori::create');
        $routes->post('store', 'Kategori::store');
        $routes->get('edit/(:num)', 'Kategori::edit/$1');
        $routes->post('update/(:num)', 'Kategori::update/$1');
        $routes->get('show/(:num)', 'Kategori::show/$1');
        $routes->get('delete/(:num)', 'Kategori::delete/$1');
    });

    // Admin Ukuran routes
    $routes->group('ukuran', ['namespace' => 'App\Controllers\Admin'], function ($routes) {
        $routes->get('/', 'Ukuran::index');
        $routes->get('create', 'Ukuran::create');
        $routes->post('store', 'Ukuran::store');
        $routes->get('edit/(:num)', 'Ukuran::edit/$1');
        $routes->post('update/(:num)', 'Ukuran::update/$1');
        $routes->get('show/(:num)', 'Ukuran::show/$1');
        $routes->get('delete/(:num)', 'Ukuran::delete/$1');
    });

    // Admin Jadwal routes
    $routes->group('jadwal', ['namespace' => 'App\Controllers\Admin'], function ($routes) {
        $routes->get('/', 'Jadwal::index');
        $routes->get('create', 'Jadwal::create');
        $routes->post('store', 'Jadwal::store');
        $routes->get('edit/(:num)', 'Jadwal::edit/$1');
        $routes->post('update/(:num)', 'Jadwal::update/$1');
        $routes->get('show/(:num)', 'Jadwal::show/$1');
        $routes->get('delete/(:num)', 'Jadwal::delete/$1');
    });

    // Admin Events routes
    $routes->group('events', ['namespace' => 'App\Controllers\Admin'], function ($routes) {
        $routes->get('/', 'Events::index');
        $routes->get('create', 'Events::create');
        $routes->post('store', 'Events::store');
        $routes->get('edit/(:num)', 'Events::edit/$1');
        $routes->get('show/(:num)', 'Events::show/$1');
        $routes->get('delete/(:num)', 'Events::delete/$1');
        $routes->get('toggle-status/(:num)', 'Events::toggleStatus/$1');
        $routes->get('search', 'Events::search');
        $routes->get('export/(:any)', 'Events::export/$1');
        $routes->get('get-events-ajax', 'Events::getEventsAjax');
        $routes->post('upload-gallery', 'Events::uploadGallery');
        
        // Pricing routes
        $routes->get('pricing/(:num)', 'Events::pricing/$1');
        $routes->post('store-price', 'Events::storePrice');
        $routes->post('update-price', 'Events::updatePrice');
        $routes->get('delete-price/(:num)', 'Events::deletePrice/$1');
        $routes->get('toggle-price-status/(:num)', 'Events::togglePriceStatus/$1');
    });

    // Admin Event Pricing routes
    $routes->group('event-pricing', ['namespace' => 'App\Controllers\Admin'], function ($routes) {
        $routes->get('/', 'EventPricing::index');
        $routes->get('create', 'EventPricing::create');
        $routes->post('store', 'EventPricing::store');
        $routes->get('edit/(:num)', 'EventPricing::edit/$1');
        $routes->post('update/(:num)', 'EventPricing::update/$1');
        $routes->get('delete/(:num)', 'EventPricing::delete/$1');
        $routes->get('by-event/(:num)', 'EventPricing::getByEvent/$1');
    });

    // Admin Event Gallery routes
    $routes->group('event-gallery', ['namespace' => 'App\Controllers\Admin'], function ($routes) {
        $routes->get('/', 'EventGallery::index');
        $routes->get('create', 'EventGallery::create');
        $routes->post('store', 'EventGallery::store');
        $routes->get('edit/(:num)', 'EventGallery::edit/$1');
        $routes->post('update/(:num)', 'EventGallery::update/$1');
        $routes->get('delete/(:num)', 'EventGallery::delete/$1');
        $routes->get('by-event/(:num)', 'EventGallery::getByEvent/$1');
        $routes->get('manage/(:num)', 'EventGallery::manage/$1');
        $routes->get('set-cover/(:num)', 'EventGallery::setCover/$1');
        $routes->post('upload', 'EventGallery::upload');
        $routes->get('test-upload', 'EventGallery::testUpload');
        $routes->post('test-simple-upload', 'EventGallery::testSimpleUpload');
        $routes->post('update-description', 'EventGallery::updateDescription');
        $routes->get('toggle-status/(:num)', 'EventGallery::toggleStatus/$1');
    });

    // Admin Peserta routes
    $routes->group('peserta', ['namespace' => 'App\Controllers\Admin'], function ($routes) {
        $routes->get('daftar', 'Peserta::daftar');
        $routes->get('kelompok', 'Peserta::kelompok');
        $routes->get('pendaftaran', 'Peserta::pendaftaran');
        $routes->get('rekap', 'Peserta::rekap');
        
        // CRUD routes for participants
        $routes->get('create', 'Peserta::create');
        $routes->post('store', 'Peserta::store');
        $routes->get('edit/(:num)', 'Peserta::edit/$1');
        $routes->post('update/(:num)', 'Peserta::update/$1');
        $routes->get('show/(:num)', 'Peserta::show/$1');
        $routes->get('delete/(:num)', 'Peserta::delete/$1');
        
        // CRUD routes for kelompok
        $routes->get('kelompok/create', 'Peserta::kelompokCreate');
        $routes->post('kelompok/store', 'Peserta::kelompokStore');
        $routes->get('kelompok/edit/(:num)', 'Peserta::kelompokEdit/$1');
        $routes->post('kelompok/update/(:num)', 'Peserta::kelompokUpdate/$1');
        $routes->get('kelompok/delete/(:num)', 'Peserta::kelompokDelete/$1');
        
        // Payment routes
        $routes->get('payment-success', 'Peserta::paymentSuccess');
    });

    // Admin Kategori Racepack routes
    $routes->group('kategori-racepack', ['namespace' => 'App\Controllers\Admin'], function ($routes) {
        $routes->get('/', 'KategoriRacepack::index');
        $routes->get('create', 'KategoriRacepack::create');
        $routes->post('store', 'KategoriRacepack::store');
        $routes->get('edit/(:num)', 'KategoriRacepack::edit/$1');
        $routes->post('update/(:num)', 'KategoriRacepack::update/$1');
        $routes->get('delete/(:num)', 'KategoriRacepack::delete/$1');
    });

    // Admin Racepack routes
    $routes->group('racepack', ['namespace' => 'App\Controllers\Admin'], function ($routes) {
        $routes->get('/', 'Racepack::index');
        $routes->get('create', 'Racepack::create');
        $routes->post('store', 'Racepack::store');
        $routes->get('edit/(:num)', 'Racepack::edit/$1');
        $routes->post('update/(:num)', 'Racepack::update/$1');
        $routes->get('show/(:num)', 'Racepack::show/$1');
        $routes->get('delete/(:num)', 'Racepack::delete/$1');
    });

    // Admin Stock Racepack routes
    $routes->group('stock-racepack', ['namespace' => 'App\Controllers\Admin'], function ($routes) {
        $routes->get('/', 'StockRacepack::index');
        $routes->get('create', 'StockRacepack::create');
        $routes->post('store', 'StockRacepack::store');
        $routes->get('edit/(:num)', 'StockRacepack::edit/$1');
        $routes->post('update/(:num)', 'StockRacepack::update/$1');
        $routes->get('delete/(:num)', 'StockRacepack::delete/$1');
        $routes->get('low-stock', 'StockRacepack::lowStock');
    });
});

// Pengaturan routes
$routes->group('pengaturan', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('app', 'Pengaturan::app');
    $routes->get('api-tokens', 'Pengaturan::apiTokens');
});

$routes->get('/dashboard', 'Dashboard::index', ['namespace' => 'App\Controllers', 'filter' => 'auth']);

// untuk test
$routes->get('home/test', 'Home::test');
$routes->get('home/test2', 'Home::test2');
$routes->get('test/check-table', 'TestController::checkTable');







