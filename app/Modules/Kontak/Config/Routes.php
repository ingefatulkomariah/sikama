<?php

if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group('kontak', ['filter' => 'auth', 'namespace' => 'App\Modules\Kontak\Controllers'], function($routes){
	$routes->get('/', 'Kontak::index');
});

$routes->group('api', ['filter' => 'jwtauth', 'namespace' => 'App\Modules\Kontak\Controllers\Api'], function($routes){
    $routes->get('kontak', 'Kontak::index');
	$routes->get('kontak/pelanggan', 'Kontak::pelanggan');
	$routes->get('kontak/vendor', 'Kontak::vendor');
	$routes->get('kontak/(:segment)', 'Kontak::show/$1');
	$routes->post('kontak/save', 'Kontak::create');
	$routes->put('kontak/update/(:segment)', 'Kontak::update/$1');
	$routes->delete('kontak/delete/(:segment)', 'Kontak::delete/$1');
});