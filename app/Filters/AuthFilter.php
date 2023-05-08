<?php

namespace App\Filters;
/*
PT. GLOBAL ITSHOP PURWOKERTO
Toko Online: ITShop Purwokerto (Tokopedia, Shopee, Bukalapak, Blibli)
Dibuat oleh: Hari Wicaksono, S.Kom
02-2023
*/

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (session()->get('logged_in') == false) {
            return redirect()->to('/login');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
