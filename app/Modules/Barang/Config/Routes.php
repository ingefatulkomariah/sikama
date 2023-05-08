<?php

if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group('barang', ['filter' => 'auth', 'namespace' => 'App\Modules\Barang\Controllers'], function($routes){
	$routes->get('/', 'Barang::index');
	$routes->get('baru', 'Barang::add');
	$routes->get('(:segment)/edit', 'Barang::edit/$1');
	$routes->get('barcode', 'Barang::barcode');
});

//Routes untuk Halaman Open Api Home
$routes->group('api', ['namespace' => 'App\Modules\Barang\Controllers\Api'], function ($routes) {
	$routes->get('cari_barang', 'Barang::cariBarang');
	$routes->get('barang/terbaru', 'Barang::getBarangTerbaru');
});

$routes->group('api', ['filter' => 'jwtauth', 'namespace' => 'App\Modules\Barang\Controllers\Api'], function($routes){
    $routes->get('barang', 'Barang::index');
	$routes->get('barang/kasir', 'Barang::getBarangKasir');
	$routes->get('barang/terbaru', 'Barang::getBarangTerbaru');
	$routes->get('barang/(:segment)', 'Barang::show/$1');
	$routes->post('barang/save', 'Barang::create');
	$routes->put('barang/update/(:segment)', 'Barang::update/$1');
	$routes->delete('barang/delete/(:segment)', 'Barang::delete/$1');
	$routes->put('barang/setaktif/(:segment)', 'Barang::setAktif/$1');
	$routes->put('barang/setstok/(:segment)', 'Barang::setStok/$1');
	$routes->put('barang/sethargabeli/(:segment)', 'Barang::setHargaBeli/$1');
	$routes->put('barang/sethargajual/(:segment)', 'Barang::setHargaJual/$1');
	$routes->get('cari_barang', 'Barang::cariBarang');
	$routes->get('scan_barang', 'Barang::scanBarang');
	$routes->post('barang/delete/multiple', 'Barang::deleteMultiple');
	$routes->get('barang/get/stokhabis', 'Barang::barangHabis');
	$routes->get('barang/get/nonaktif', 'Barang::barangNonaktif');
	$routes->get('barang/get/jmlsemuabarang', 'Barang::jmlSemuaBarang');
	$routes->get('barang/get/jmlstokhabis', 'Barang::jmlStokHabis');
	$routes->get('barang/get/jmlnonaktif', 'Barang::jmlNonaktif');
	$routes->get('barang/beli/(:segment)', 'Barang::getBarangBeliVendor/$1');
	$routes->get('find_barang', 'Barang::findBarang');
});