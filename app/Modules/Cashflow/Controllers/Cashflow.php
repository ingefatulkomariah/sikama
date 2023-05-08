<?php

namespace App\Modules\Cashflow\Controllers;
/*
PT. GLOBAL ITSHOP PURWOKERTO
Toko Online: ITShop Purwokerto (Tokopedia, Shopee, Bukalapak, Blibli)
Dibuat oleh: Hari Wicaksono, S.Kom
03-2023
*/

use App\Controllers\BaseController;
use App\Modules\Cashflow\Models\CashflowModel;
use CodeIgniter\I18n\Time;

class Cashflow extends BaseController
{
    protected $cashflow;

    public function __construct()
    {
        //memanggil function di model
        $this->cashflow = new CashflowModel();
    }

    public function index()
    {
        $cari = $this->request->getVar('search');
        return view('App\Modules\Cashflow\Views/cashflow', [
            'title' => lang('App.cashflow'),
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
