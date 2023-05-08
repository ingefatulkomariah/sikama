<?php

namespace  App\Modules\Penjualan\Controllers;
/*
PT. GLOBAL ITSHOP PURWOKERTO
Toko Online: ITShop Purwokerto (Tokopedia, Shopee, Bukalapak, Blibli)
Dibuat oleh: Hari Wicaksono, S.Kom
03-2023
*/

use App\Controllers\BaseController;
use App\Libraries\Settings;
use App\Modules\Toko\Models\TokoModel;
use App\Modules\Bank\Models\BankAkunModel;

class Pointofsales extends BaseController
{
	protected $setting;
	protected $toko;
	protected $bankAkun;

	public function __construct()
	{
		//memanggil Model
		$this->setting = new Settings();
		$this->toko = new TokoModel();
		$this->bankAkun = new BankAkunModel();
	}

	public function index()
	{
		$toko = $this->toko->first();
        $bankUtama = $toko['id_bank_akun'];
        $bankAkun = $this->bankAkun->find($bankUtama);

		return view('App\Modules\Penjualan\Views/point_of_sales', [
			'title' => 'Point of Sales (POS)',
			'namaToko' => $toko['nama_toko'],
			'cetakUSB' => $toko['printer_usb'],
			'cetakBluetooth' => $toko['printer_bluetooth'],
			'scanKeranjang' => $toko['scan_keranjang'],
			'ppn' => $toko['PPN'],
			'logo' => $this->setting->info['img_logo'],
			'idBankUtama' => $bankUtama,
			'bankAkun' => $bankAkun,
			'cashierpayPos' => $this->setting->info['cashierpay_position'],
		]);
	}

}
