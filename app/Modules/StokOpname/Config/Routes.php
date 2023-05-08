<?php

if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group('stok_opname', ['filter' => 'auth', "filter" => "role:1,2,3", 'namespace' => 'App\Modules\StokOpname\Controllers'], function($routes){
	$routes->get('/', 'StokOpname::index');
});


$routes->group('api', ['filter' => 'jwtauth', 'namespace' => 'App\Modules\StokOpname\Controllers\Api'], function($routes){
    $routes->get('stok_opname', 'StokOpname::index');
	$routes->get('stok_opname/(:segment)', 'StokOpname::show/$1');
	$routes->post('stok_opname/save', 'StokOpname::create');
	$routes->put('stok_opname/update/(:segment)', 'StokOpname::update/$1');
	$routes->delete('stok_opname/delete/(:segment)', 'StokOpname::delete/$1');
});