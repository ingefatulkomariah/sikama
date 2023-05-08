<?php

if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group('excel', ['filter' => 'auth', 'namespace' => 'App\Modules\Excel\Controllers'], function($routes){
	$routes->get('import', 'Excel::import');
	$routes->post('saveExcel', 'Excel::saveExcel');
});

$routes->group('api', ['filter' => 'jwtauth', 'namespace' => 'App\Modules\Excel\Controllers\Api'], function($routes){
	$routes->post('excel/exporttoexcel', 'Excel::excelExport');
});