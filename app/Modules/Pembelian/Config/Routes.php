<?php

if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group('pembelian', ['filter' => 'auth', "filter" => "role:1,3", 'namespace' => 'App\Modules\Pembelian\Controllers'], function($routes){
	$routes->get('/', 'Pembelian::index');
	$routes->get('baru', 'Pembelian::add');
});

$routes->group('api', ['filter' => 'jwtauth', 'namespace' => 'App\Modules\Pembelian\Controllers\Api'], function($routes){
    $routes->get('pembelian', 'Pembelian::index');
	$routes->get('pembelian/(:segment)', 'Pembelian::show/$1');
	$routes->post('pembelian/save', 'Pembelian::create');
	$routes->put('pembelian/update/(:segment)', 'Pembelian::update/$1');
	$routes->delete('pembelian/delete/(:segment)', 'Pembelian::delete/$1');
	$routes->delete('pembelian/reset', 'Pembelian::truncate');
	$routes->get('pembelian/item/(:segment)', 'Pembelian::item/$1');
});