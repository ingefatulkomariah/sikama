<?php

if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group('pajak', ['filter' => 'auth', "filter" => "role:1,2,3", 'namespace' => 'App\Modules\Pajak\Controllers'], function($routes){
	$routes->get('/', 'Pajak::index');
});


$routes->group('api', ['filter' => 'jwtauth', 'namespace' => 'App\Modules\Pajak\Controllers\Api'], function($routes){
    $routes->get('pajak', 'Pajak::index');
	$routes->get('pajak/saldo', 'Pajak::saldo');
	$routes->get('pajak/(:segment)', 'Pajak::show/$1');
	$routes->post('pajak/save', 'Pajak::create');
	$routes->put('pajak/update/(:segment)', 'Pajak::update/$1');
	$routes->delete('pajak/delete/(:segment)', 'Pajak::delete/$1');
});