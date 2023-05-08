<?php

if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group('cashflow', ['filter' => 'auth', "filter" => "role:1,2,3", 'namespace' => 'App\Modules\Cashflow\Controllers'], function($routes){
	$routes->get('/', 'Cashflow::index');
});


$routes->group('api', ['filter' => 'jwtauth', 'namespace' => 'App\Modules\Cashflow\Controllers\Api'], function($routes){
    $routes->get('cashflow', 'Cashflow::index');
	$routes->get('cashflow/saldo', 'Cashflow::saldo');
	$routes->get('cashflow/(:segment)', 'Cashflow::show/$1');
	$routes->post('cashflow/save', 'Cashflow::create');
	$routes->put('cashflow/update/(:segment)', 'Cashflow::update/$1');
	$routes->delete('cashflow/delete/(:segment)', 'Cashflow::delete/$1');
});