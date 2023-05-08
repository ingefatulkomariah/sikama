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

class RoleBased implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $cocok = 0;
        foreach ($arguments as $key => $value) {
            if (session()->get('role')==$value) {
                $cocok+=1;
            }
        }
        if ($cocok==0) {
            return redirect()->to(previous_url()); 
        }
    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
