<?php

if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group('bank', ['filter' => 'auth', "filter" => "role:1,3", 'namespace' => 'App\Modules\Bank\Controllers'], function($routes){
	$routes->get('/', 'Bank::index');
});


$routes->group('api', ['filter' => 'jwtauth', 'namespace' => 'App\Modules\Bank\Controllers\Api'], function($routes){
    $routes->get('bank', 'Bank::index');
	$routes->get('bank/saldo', 'Bank::saldo');
	$routes->get('bank/(:segment)', 'Bank::show/$1');
	$routes->post('bank/save', 'Bank::create');
	$routes->put('bank/update/(:segment)', 'Bank::update/$1');
	$routes->delete('bank/delete/(:segment)', 'Bank::delete/$1');

	$routes->get('bank/akun/all', 'BankAkun::index');
	$routes->get('bank/akun/(:segment)', 'BankAkun::show/$1');
	$routes->post('bank/akun/save', 'BankAkun::create');
	$routes->put('bank/akun/update/(:segment)', 'BankAkun::update/$1');
	$routes->delete('bank/akun/delete/(:segment)', 'BankAkun::delete/$1');
	$routes->put('bank/akun/setutama/(:segment)', 'BankAkun::setUtama/$1');
});