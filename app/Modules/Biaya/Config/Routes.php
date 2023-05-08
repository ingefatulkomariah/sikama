<?php

if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group('biaya', ['filter' => 'auth', "filter" => "role:1,2,3", 'namespace' => 'App\Modules\Biaya\Controllers'], function($routes){
	$routes->get('/', 'Biaya::index');
});


$routes->group('api', ['filter' => 'jwtauth', 'namespace' => 'App\Modules\Biaya\Controllers\Api'], function($routes){
    $routes->get('biaya', 'Biaya::index');
	$routes->get('biaya/(:segment)', 'Biaya::show/$1');
	$routes->post('biaya/save', 'Biaya::create');
	$routes->put('biaya/update/(:segment)', 'Biaya::update/$1');
	$routes->delete('biaya/delete/(:segment)', 'Biaya::delete/$1');
});