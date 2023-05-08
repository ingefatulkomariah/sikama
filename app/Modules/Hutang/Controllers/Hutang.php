<?php

namespace App\Modules\Hutang\Controllers;
/*
PT. GLOBAL ITSHOP PURWOKERTO
Toko Online: ITShop Purwokerto (Tokopedia, Shopee, Bukalapak, Blibli)
Dibuat oleh: Hari Wicaksono, S.Kom
12-2022
*/

use App\Controllers\BaseController;
use App\Modules\Hutang\Models\HutangModel;
use CodeIgniter\I18n\Time;

class Hutang extends BaseController
{
    protected $hutang;

    public function __construct()
    {
        //memanggil function di model
        $this->hutang = new HutangModel();
    }

    public function index()
    {
        $cari = $this->request->getVar('faktur');
        return view('App\Modules\Hutang\Views/hutang', [
            'title' => lang('App.debts'),
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
