<?php

namespace  App\Modules\Pembelian\Controllers;
/*
PT. GLOBAL ITSHOP PURWOKERTO
Toko Online: ITShop Purwokerto (Tokopedia, Shopee, Bukalapak, Blibli)
Dibuat oleh: Hari Wicaksono, S.Kom
06-2022
*/

use App\Controllers\BaseController;
use App\Libraries\Settings;
use App\Modules\Toko\Models\TokoModel;
use CodeIgniter\I18n\Time;

class Pembelian extends BaseController
{
	protected $setting;
	protected $toko;

	public function __construct()
	{
		//memeriksa session role selain Admin redirect ke /dashboard
        if (session()->get('logged_in') == true && session()->get('role') == 2) {
            header('location:/dashboard');
            exit();
        }
		
		//memanggil Model
		$this->setting = new Settings();
		$this->toko = new TokoModel();
	}


	public function index()
	{
		$cari = $this->request->getVar('faktur');
		$toko = $this->toko->first();
		return view('App\Modules\Pembelian\Views/pembelian', [
			'title' => lang('App.purchases'),
			'cetakUSB' => $toko['printer_usb'],
			'cetakBluetooth' => $toko['printer_bluetooth'],
			'scanKeranjang' => $toko['scan_keranjang'],
			'search' => $cari,
			'hariini' => date('Y-m-d', strtotime(Time::now())),
			'tujuhHari' => date('Y-m-d', strtotime('-1 week', strtotime(Time::now()))),
			'awalBulan' => date('Y-m-', strtotime(Time::now())) . '01',
            'akhirBulan' => date('Y-m-t', strtotime(Time::now())),
			'awalTahun' => date('Y-', strtotime(Time::now())) . '01-01',
            'akhirTahun' => date('Y-', strtotime(Time::now())) . '12-31',
		]);
	}

	public function add()
	{
		$toko = $this->toko->first();
		
		return view('App\Modules\Pembelian\Views/pembelian_baru', [
			'title' => lang('App.addPurchase'),
			'cetakUSB' => $toko['printer_usb'],
			'cetakBluetooth' => $toko['printer_bluetooth'],
			'scanKeranjang' => $toko['scan_keranjang'],
		]);
	}

}
