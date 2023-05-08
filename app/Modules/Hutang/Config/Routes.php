<?php

if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group('hutang', ['filter' => 'auth', "filter" => "role:1,3", 'namespace' => 'App\Modules\Hutang\Controllers'], function($routes){
	$routes->get('/', 'Hutang::index');
});

$routes->group('api', ['filter' => 'jwtauth', 'namespace' => 'App\Modules\Hutang\Controllers\Api'], function($routes){
    $routes->get('hutang', 'Hutang::index');
	//$routes->get('hutang/total', 'Hutang::total');
	$routes->get('hutang/(:segment)', 'Hutang::show/$1');
	$routes->post('hutang/save', 'Hutang::create');
	$routes->put('hutang/update/(:segment)', 'Hutang::update/$1');
	$routes->delete('hutang/delete/(:segment)', 'Hutang::delete/$1');
	$routes->delete('hutang/bayar/delete/(:segment)', 'Hutang::delete2/$1');
});