<?php

namespace App\Modules\StokOpname\Controllers;
/*
PT. GLOBAL ITSHOP PURWOKERTO
Toko Online: ITShop Purwokerto (Tokopedia, Shopee, Bukalapak, Blibli)
Dibuat oleh: Hari Wicaksono, S.Kom
02-2023
*/

use App\Controllers\BaseController;
use App\Modules\StokOpname\Models\StokOpnameModel;
use CodeIgniter\I18n\Time;

class StokOpname extends BaseController
{
    protected $stokOpname;

    public function __construct()
    {
        //memanggil function di model
        $this->stokOpname = new StokOpnameModel();
    }

    public function index()
    {
        $cari = $this->request->getVar('search');
        return view('App\Modules\StokOpname\Views/stok_opname', [
            'title' => 'Stok Opname',
            'search' => $cari,
            'hariini' => date('Y-m-d', strtotime(Time::now())),
			'tujuhHari' => date('Y-m-d', strtotime('-1 week', strtotime(Time::now()))),
			'awalBulan' => date('Y-m-', strtotime(Time::now())) . '01',
            'akhirBulan' => date('Y-m-t', strtotime(Time::now())),
			'awalTahun' => date('Y-', strtotime(Time::now())) . '01-01',
            'akhirTahun' => date('Y-', strtotime(Time::now())) . '12-31',
        ]);
    }

    
}
