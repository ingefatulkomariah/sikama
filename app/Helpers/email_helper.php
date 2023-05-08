<?php
/*
PT. GLOBAL ITSHOP PURWOKERTO
Toko Online: ITShop Purwokerto (Tokopedia, Shopee, Bukalapak, Blibli)
Dibuat oleh: Hari Wicaksono, S.Kom
06-2022
*/

function sendEmail($subject, $to, $view)
{
    $email = \Config\Services::email();
    $email->setTo($to);
    $email->setSubject($subject);
    $email->setMessage($view);
    if ($email->send(false)) {
        return true;
    }
    return false;
}