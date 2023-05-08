<?php

if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group('toko', ['filter' => 'auth', 'namespace' => 'App\Modules\Toko\Controllers'], function($routes){
	$routes->get('/', 'Toko::index');
});


$routes->group('api', ['filter' => 'jwtauth', 'namespace' => 'App\Modules\Toko\Controllers\Api'], function($routes){
	$routes->get('toko', 'Toko::index');
	$routes->put('toko/update/(:segment)', 'Toko::update/$1');
	$routes->put('toko/setaktifprinterusb/(:segment)', 'Toko::setAktifPrinterUsb/$1');
	$routes->put('toko/setaktifprinterbt/(:segment)', 'Toko::setAktifPrinterBT/$1');
	$routes->put('toko/setaktifkodejualtahun/(:segment)', 'Toko::setAktifKodeJualTahun/$1');
	$routes->put('toko/setaktifscankeranjang/(:segment)', 'Toko::setAktifScanKeranjang/$1');
});