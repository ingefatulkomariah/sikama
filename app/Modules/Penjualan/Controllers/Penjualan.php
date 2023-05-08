<?php

namespace  App\Modules\Penjualan\Controllers;
/*
PT. GLOBAL ITSHOP PURWOKERTO
Toko Online: ITShop Purwokerto (Tokopedia, Shopee, Bukalapak, Blibli)
Dibuat oleh: Hari Wicaksono, S.Kom
06-2022
*/

use App\Controllers\BaseController;
use App\Libraries\Settings;
use App\Modules\Dashboard\Models\DashboardModel;
use App\Modules\Toko\Models\TokoModel;
use App\Modules\Penjualan\Models\PenjualanModel;
use App\Modules\Barang\Models\BarangModel;
use App\Modules\Penjualan\Models\PenjualanItemModel;
use Spipu\Html2Pdf\Html2Pdf;
use CodeIgniter\I18n\Time;

class Penjualan extends BaseController
{
	protected $setting;
	protected $dashboard;
	protected $toko;
	protected $penjualan;
	protected $barang;
	protected $itemJual;

	public function __construct()
	{
		//memanggil Model
		$this->setting = new Settings();
		$this->dashboard = new DashboardModel();
		$this->toko = new TokoModel();
		$this->penjualan = new PenjualanModel();
		$this->barang = new BarangModel();
		$this->itemJual = new PenjualanItemModel();
		helper('tglindo');
	}

	public function index()
	{
		$cari = $this->request->getVar('faktur');
		$countTrxHariini = $this->dashboard->countTrxHariini();
		$countTrxHarikemarin = $this->dashboard->countTrxHarikemarin();
		$totalTrxHariini = $this->dashboard->totalTrxHariini();
		$totalTrxHarikemarin = $this->dashboard->totalTrxHarikemarin();
		$sisaPiutangHariini = $this->dashboard->sisaPiutangHariini();
		$sisaPiutangHarikemarin = $this->dashboard->sisaPiutangHarikemarin();
		$toko = $this->toko->first();

		return view('App\Modules\Penjualan\Views/penjualan', [
			'title' => lang('App.sales'),
			'countTrxHariini' => $countTrxHariini,
			'countTrxHarikemarin' => $countTrxHarikemarin,
			'totalTrxHariini' => $totalTrxHariini,
			'totalTrxHarikemarin' => $totalTrxHarikemarin,
			'sisaPiutangHariini' => $sisaPiutangHariini,
			'sisaPiutangHarikemarin' => $sisaPiutangHarikemarin,
			'cetakUSB' => $toko['printer_usb'],
			'cetakBluetooth' => $toko['printer_bluetooth'],
			'logo' => $this->setting->info['img_logo'],
			'search' => $cari,
			'hariini' => date('Y-m-d', strtotime(Time::now())),
			'tujuhHari' => date('Y-m-d', strtotime('-1 week', strtotime(Time::now()))),
			'awalBulan' => date('Y-m-', strtotime(Time::now())) . '01',
            'akhirBulan' => date('Y-m-t', strtotime(Time::now())),
			'awalTahun' => date('Y-', strtotime(Time::now())) . '01-01',
            'akhirTahun' => date('Y-', strtotime(Time::now())) . '12-31',
		]);
	}

	public function printNotaHtml()
	{
		$input = $this->request->getVar();
		$idPenjualan = $input['id_penjualan'];
		$penjualan = $this->penjualan->getPenjualanById($idPenjualan);
		$faktur = $penjualan['faktur'];
		$data = [
			'title' => '',
			'toko' => $this->toko->first(),
			'logo' => $this->setting->info['img_logo_resize'],
			'penjualan' => $penjualan,
			'item' => $this->itemJual->findNotaCetak($idPenjualan),
			'faktur' => $faktur,
			'user' => session()->get('nama'),
			'appname' => APP_NAME,
			'companyname' => COMPANY_NAME,
		];

		return view('App\Modules\Penjualan\Views/penjualan_html', $data);
	}

	
	/* public function printNotaPdf()
	{
		$input = $this->request->getVar();
		$idPenjualan = $input['id_penjualan'];
		$data = [
			'toko' => $this->toko->first(),
			'logo' => $this->setting->info['img_logo_resize'],
			'penjualan' => $this->penjualan->getPenjualanById($idPenjualan),
			'item' => $this->itemJual->findNotaCetak($idPenjualan),
			'user' => session()->get('nama'),
			'appname' => APP_NAME,
			'companyname' => COMPANY_NAME,
		];

		$html = view('App\Modules\Penjualan\Views/penjualan_pdf', $data);

		// create new PDF document
		$pdf = new Html2Pdf('P', 'A4');

		// Print text using writeHTMLCell()
		$pdf->writeHTML($html);
		$this->response->setContentType('application/pdf');
		// Close and output PDF document
		// This method has several options, check the source code documentation for more information.
		$file = FCPATH . 'files/cetakpenjualan.pdf';
		$pdf->Output($file, 'F');
		$attachment = base_url('files/cetakpenjualan.pdf');
		$pdf->Output('cetakpenjualan.pdf', 'I');  // display on the browser
	} */
}
