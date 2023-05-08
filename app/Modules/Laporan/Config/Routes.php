<?php

if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group('laporan', ['filter' => 'auth', 'namespace' => 'App\Modules\Laporan\Controllers'], function($routes){
	$routes->get('/', 'Laporan::index');
	$routes->get('barang-pdf', 'Laporan::barangPdf');
	$routes->get('stokbarang-pdf', 'Laporan::stokbarangPdf');
	$routes->get('penjualan-pdf', 'Laporan::penjualanPdf');
	$routes->get('kategori-pdf', 'Laporan::kategoriPdf');
	$routes->get('labarugi-pdf', 'Laporan::labarugiPdf');
	$routes->get('stokopname-pdf', 'Laporan::stokopnamePdf');
});


$routes->group('api', ['filter' => 'jwtauth', 'namespace' => 'App\Modules\Laporan\Controllers\Api'], function($routes){
	$routes->get('laporanbarang', 'Laporan::barang');
	$routes->get('laporanstok', 'Laporan::stok');
	$routes->get('laporanpenjualan', 'Laporan::penjualan');
	$routes->get('laporankategori', 'Laporan::kategori');
	$routes->get('laporandetailkategori', 'Laporan::detailKategori');
	$routes->get('laporanlabarugi', 'Laporan::LabaRugi');
	$routes->get('laporanstokopname', 'Laporan::stokOpname');
	$routes->get('laporanlog', 'Laporan::Log');
});