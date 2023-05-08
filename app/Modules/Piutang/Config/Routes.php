<?php

if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group('piutang', ['filter' => 'auth', "filter" => "role:1,2,3", 'namespace' => 'App\Modules\Piutang\Controllers'], function($routes){
	$routes->get('/', 'Piutang::index');
});

$routes->group('api', ['filter' => 'jwtauth', 'namespace' => 'App\Modules\Piutang\Controllers\Api'], function($routes){
    $routes->get('piutang', 'Piutang::index');
	$routes->get('piutang/total', 'Piutang::total');
	$routes->get('piutang/(:segment)', 'Piutang::show/$1');
	$routes->post('piutang/save', 'Piutang::create');
	$routes->put('piutang/update/(:segment)', 'Piutang::update/$1');
	$routes->delete('piutang/delete/(:segment)', 'Piutang::delete/$1');
	$routes->delete('piutang/bayar/delete/(:segment)', 'Piutang::delete2/$1');
	$routes->get('find_piutang/(:segment)', 'Piutang::findPiutang/$1');
});