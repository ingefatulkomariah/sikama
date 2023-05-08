<?php

if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group('settings', ['filter' => 'auth', 'namespace' => 'App\Modules\Setting\Controllers'], function($routes){
	$routes->get('/', 'Setting::index');
});

$routes->group('api', ['filter' => 'jwtauth', 'namespace' => 'App\Modules\Setting\Controllers\Api'], function($routes){
    $routes->get('setting', 'Setting::index');
	$routes->put('setting/update/(:segment)', 'Setting::update/$1');
	$routes->post('setting/upload', 'Setting::upload');
});