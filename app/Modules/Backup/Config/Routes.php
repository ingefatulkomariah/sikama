<?php

if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group('backup', ['filter' => 'auth', 'namespace' => 'App\Modules\Backup\Controllers'], function($routes){
	$routes->get('/', 'Backup::index');
});


$routes->group('api', ['filter' => 'jwtauth', 'namespace' => 'App\Modules\Backup\Controllers\Api'], function($routes){
    $routes->get('backup', 'Backup::index');
	$routes->post('backup/save', 'Backup::create');
	$routes->delete('backup/delete/(:segment)', 'Backup::delete/$1');
	$routes->post('backup/download', 'Backup::download');
});