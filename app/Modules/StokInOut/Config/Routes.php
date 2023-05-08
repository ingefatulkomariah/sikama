<?php

if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group('stok', ['filter' => 'auth', "filter" => "role:1,3,5", 'namespace' => 'App\Modules\StokInOut\Controllers'], function($routes){
	$routes->get('/', 'Stok::index');
});


$routes->group('api', ['filter' => 'jwtauth', 'namespace' => 'App\Modules\StokInOut\Controllers\Api'], function($routes){
    $routes->get('stok', 'Stok::index');
	$routes->get('stok/(:segment)', 'Stok::show/$1');
	$routes->post('stok/save', 'Stok::create');
	$routes->put('stok/update/(:segment)', 'Stok::update/$1');
	$routes->delete('stok/delete/(:segment)', 'Stok::delete/$1');
});