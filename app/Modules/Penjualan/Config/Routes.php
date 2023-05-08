<?php

if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group('sales', ['filter' => 'auth', "filter" => "role:1,2,3", 'namespace' => 'App\Modules\Penjualan\Controllers'], function($routes){
	$routes->get('/', 'Pointofsales::index');
});

$routes->group('penjualan', ['filter' => 'auth', "filter" => "role:1,2,3", 'namespace' => 'App\Modules\Penjualan\Controllers'], function($routes){
	$routes->get('/', 'Penjualan::index');
	$routes->get('printnota-html', 'Penjualan::printNotaHtml');
	//$routes->get('printnota-pdf', 'Penjualan::printNotaPdf');
});

$routes->group('api', ['filter' => 'jwtauth', 'namespace' => 'App\Modules\Penjualan\Controllers\Api'], function($routes){
	$routes->get('penjualan', 'Penjualan::index');
	$routes->post('penjualan/save/cash', 'Penjualan::create');
	$routes->post('penjualan/save/credit', 'Penjualan::create1');
	$routes->post('penjualan/save/bank', 'Penjualan::create2');
	$routes->get('penjualan/(:segment)', 'Penjualan::show/$1');
	$routes->put('penjualan/update/(:segment)', 'Penjualan::update/$1');
	$routes->delete('penjualan/delete/(:segment)', 'Penjualan::delete/$1');
	$routes->get('cetaknota/(:segment)', 'Penjualan::cetakNota/$1');
	$routes->post('penjualan/cetakusb', 'Penjualan::cetakUSB');
	$routes->post('penjualan/cetakbluetooth', 'Penjualan::cetakBluetooth');
});