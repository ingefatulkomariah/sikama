<?php

namespace App\Modules\Piutang\Controllers;
/*
PT. GLOBAL ITSHOP PURWOKERTO
Toko Online: ITShop Purwokerto (Tokopedia, Shopee, Bukalapak, Blibli)
Dibuat oleh: Hari Wicaksono, S.Kom
12-2022
*/

use App\Controllers\BaseController;
use App\Modules\Piutang\Models\PiutangModel;
use CodeIgniter\I18n\Time;

class Piutang extends BaseController
{
    protected $piutang;

    public function __construct()
    {
        //memanggil function di model
        $this->piutang = new PiutangModel();
    }

    public function index()
    {
        $cari = $this->request->getVar('faktur');
        return view('App\Modules\Piutang\Views/piutang', [
            'title' => lang('App.receivables'),
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
