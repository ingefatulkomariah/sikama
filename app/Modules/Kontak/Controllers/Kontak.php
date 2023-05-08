<?php

namespace App\Modules\Kontak\Controllers;
/*
PT. GLOBAL ITSHOP PURWOKERTO
Toko Online: ITShop Purwokerto (Tokopedia, Shopee, Bukalapak, Blibli)
Dibuat oleh: Hari Wicaksono, S.Kom
06-2022
*/

use App\Controllers\BaseController;

class Kontak extends BaseController
{
    public function __construct()
    {
        
    }

    public function index()
    {
        //memanggil function di model

        return view('App\Modules\Kontak\Views/kontak', [
            'title' => lang('App.contact'),
        ]);
    }

    
}
