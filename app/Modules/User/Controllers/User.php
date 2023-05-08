<?php

namespace  App\Modules\User\Controllers;
/*
PT. GLOBAL ITSHOP PURWOKERTO
Toko Online: ITShop Purwokerto (Tokopedia, Shopee, Bukalapak, Blibli)
Dibuat oleh: Hari Wicaksono, S.Kom
06-2022
*/

use App\Controllers\BaseController;
use App\Libraries\Settings;

class User extends BaseController
{
	protected $setting;

	public function __construct()
	{
		//memeriksa session role selain Admin redirect ke /dashboard
        if ((session()->get('role') == '2' || session()->get('role') == '4' || session()->get('role') == '5')) {
            header('location:/dashboard');
            exit();
        }
		
		//memanggil Model
		$this->setting = new Settings();
	}

	public function index()
	{
		return view('App\Modules\User\Views/user', [
			'title' => lang('App.users')
		]);
	}

}
