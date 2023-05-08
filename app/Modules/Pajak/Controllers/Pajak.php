<?php

namespace App\Modules\Pajak\Controllers;
/*
PT. GLOBAL ITSHOP PURWOKERTO
Toko Online: ITShop Purwokerto (Tokopedia, Shopee, Bukalapak, Blibli)
Dibuat oleh: Hari Wicaksono, S.Kom
06-2022
*/

use App\Controllers\BaseController;
use App\Modules\Pajak\Models\PajakModel;

class Pajak extends BaseController
{
    protected $pajak;

    public function __construct()
    {
        //memanggil function di model
        $this->pajak = new PajakModel();
    }

    public function index()
    {
        return view('App\Modules\Pajak\Views/pajak', [
            'title' => 'Pajak',
        ]);
    }

    
}
