<?php

namespace  App\Modules\Laporan\Controllers;
/*
PT. GLOBAL ITSHOP PURWOKERTO
Toko Online: ITShop Purwokerto (Tokopedia, Shopee, Bukalapak, Blibli)
Dibuat oleh: Hari Wicaksono, S.Kom
06-2022
*/

use App\Controllers\BaseController;
use App\Libraries\Settings;
use App\Modules\Laporan\Models\LaporanBarangModel;
use App\Modules\Laporan\Models\LaporanPenjualanModel;
use App\Modules\Laporan\Models\LaporanKategoriModel;
use App\Modules\Laporan\Models\LaporanCashflowModel;
use App\Modules\Laporan\Models\LaporanStokopnameModel;
use App\Modules\Toko\Models\TokoModel;
use TCPDF;
use Spipu\Html2Pdf\Html2Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use CodeIgniter\I18n\Time;

class Laporan extends BaseController
{
	protected $setting;
    protected $barang;
    protected $penjualan;
    protected $kategori;
    protected $cash;
    protected $stokopname;
    protected $toko;

	public function __construct()
	{
		//memanggil Model
		$this->setting = new Settings();
        $this->barang = new LaporanBarangModel();
        $this->penjualan = new LaporanPenjualanModel();
        $this->kategori = new LaporanKategoriModel();
        $this->cash = new LaporanCashflowModel();
        $this->stokopname = new LaporanStokopnameModel();
        $this->toko = new TokoModel();
        helper('tglindo');
	}


	public function index()
	{
		return view('App\Modules\Laporan\Views/laporan', [
			'title' => lang('App.report'),
            'startDate' => date('Y-m-', strtotime(Time::now())) . '01',
            'endDate' => date('Y-m-t', strtotime(Time::now())),
            'hariini' => date('Y-m-d', strtotime(Time::now())),
			'tujuhHari' => date('Y-m-d', strtotime('-1 week', strtotime(Time::now()))),
			'awalBulan' => date('Y-m-', strtotime(Time::now())) . '01',
            'akhirBulan' => date('Y-m-t', strtotime(Time::now())),
			'awalTahun' => date('Y-', strtotime(Time::now())) . '01-01',
            'akhirTahun' => date('Y-', strtotime(Time::now())) . '12-31',
		]);
	}

	public function barangPdf()
    {
        $input = $this->request->getVar();
        $start = $input['tgl_start'];
        $end = $input['tgl_end'];
        $data = [
            'toko' => $this->toko->first(),
            'logo' => $this->setting->info['img_logo_resize'],
            'tgl_start' => $start,
            'tgl_end' => $end,
            'data' => $this->barang->getLaporanByBarang($start, $end)
        ];

        $html = view('App\Modules\Laporan\Views/barang_pdf', $data);

        // create new PDF document
        $pdf = new Html2Pdf('P', 'A4');

        // Print text using writeHTMLCell()
        $pdf->writeHTML($html);
        $this->response->setContentType('application/pdf');
        // Close and output PDF document
        // This method has several options, check the source code documentation for more information.
        $file = FCPATH.'files/barang.pdf';
        $pdf->Output($file, 'F');
        $attachment = base_url('files/barang.pdf');
        $pdf->Output('barang.pdf','I');  // display on the browser
    }

    public function stokbarangPdf()
    {
        $input = $this->request->getVar();
        $start = $input['tgl_start'];
        $end = $input['tgl_end'];
        $data = [
            'toko' => $this->toko->first(),
            'logo' => $this->setting->info['img_logo_resize'],
            'tgl_start' => $start,
            'tgl_end' => $end,
            'data' => $this->barang->getLaporanByStok($start, $end)
        ];

        $html = view('App\Modules\Laporan\Views/stokbarang_pdf', $data);

        // create new PDF document
        $pdf = new Html2Pdf('P', 'A4');

        // Print text using writeHTMLCell()
        $pdf->writeHTML($html);
        $this->response->setContentType('application/pdf');
        // Close and output PDF document
        // This method has several options, check the source code documentation for more information.
        $file = FCPATH.'files/stokbarang.pdf';
        $pdf->Output($file, 'F');
        $attachment = base_url('files/stokbarang.pdf');
        $pdf->Output('stokbarang.pdf','I');  // display on the browser
    }

    public function penjualanPdf()
    {
        $input = $this->request->getVar();
        $start = $input['tgl_start'];
        $end = $input['tgl_end'];
        $data = [
            'toko' => $this->toko->first(),
            'logo' => $this->setting->info['img_logo_resize'],
            'tgl_start' => $start,
            'tgl_end' => $end,
            'data' => $this->penjualan->getLaporanByPenjualan($start, $end)
        ];

        $html = view('App\Modules\Laporan\Views/penjualan_pdf', $data);

        // create new PDF document
        $pdf = new Html2Pdf('P', 'A4');

        // Print text using writeHTMLCell()
        $pdf->writeHTML($html);
        $this->response->setContentType('application/pdf');
        // Close and output PDF document
        // This method has several options, check the source code documentation for more information.
        $file = FCPATH.'files/penjualan.pdf';
        $pdf->Output($file, 'F');
        $attachment = base_url('files/penjualan.pdf');
        $pdf->Output('penjualan.pdf','I');  // display on the browser
    }

    public function kategoriPdf()
    {
        $input = $this->request->getVar();
        $start = $input['tgl_start'];
        $end = $input['tgl_end'];
        $data = [
            'toko' => $this->toko->first(),
            'logo' => $this->setting->info['img_logo_resize'],
            'tgl_start' => $start,
            'tgl_end' => $end,
            'data' => $this->kategori->getLaporanByKategori($start, $end)
        ];

        $html = view('App\Modules\Laporan\Views/kategori_pdf', $data);

        // create new PDF document
        $pdf = new Html2Pdf('P', 'A4');

        // Print text using writeHTMLCell()
        $pdf->writeHTML($html);
        $this->response->setContentType('application/pdf');
        // Close and output PDF document
        // This method has several options, check the source code documentation for more information.
        $file = FCPATH.'files/kategori.pdf';
        $pdf->Output($file, 'F');
        $attachment = base_url('files/kategori.pdf');
        $pdf->Output('kategori.pdf','I');  // display on the browser
    }

    public function labarugiPdf()
    {
        $input = $this->request->getVar();
        $start = $input['tgl_start'];
        $end = $input['tgl_end'];

        $data['sumPenjualan'] = $this->cash->sumPenjualan($start, $end);
        $data['sumPemasukanLain'] = $this->cash->sumPemasukanLain($start, $end);
        $totalPendapatan = $data['sumPenjualan'] + $data['sumPemasukanLain'];
        $data['sumHPP'] = $this->cash->sumHPP($start, $end);
        $labaKotor = $totalPendapatan - $data['sumHPP'];
        $data['sumPengeluaran'] = $this->cash->sumPengeluaran($start, $end);
        $data['sumPengeluaranLain'] = $this->cash->sumMutasiBank($start, $end);
        $totalPengeluaran = $data['sumPengeluaran'] +  $data['sumPengeluaranLain'];
        $labaBersih = $labaKotor-$totalPengeluaran;
        foreach ($data as $key => $value) {
            $arrayData = [
                'pemasukan_penjualan' => $data['sumPenjualan'],   
                'pemasukan_lain' => $data['sumPemasukanLain'],
                'total_pendapatan' => $totalPendapatan,
                'beban_pokok_pendapatan' => $data['sumHPP'],
                'laba_kotor' => $labaKotor,
                'pengeluaran' => $data['sumPengeluaran'],
                'pengeluaran_lain' => $data['sumPengeluaranLain'],
                'total_pengeluaran' => $totalPengeluaran,
                'laba_bersih' => $labaBersih,
            ];
        }

        $data = [
            'toko' => $this->toko->first(),
            'logo' => $this->setting->info['img_logo_resize'],
            'tgl_start' => $start,
            'tgl_end' => $end,
            'data' => $arrayData
        ];

        $html = view('App\Modules\Laporan\Views/labarugi_pdf', $data);

        // create new PDF document
        $pdf = new Html2Pdf('P', 'A4');

        // Print text using writeHTMLCell()
        $pdf->writeHTML($html);
        $this->response->setContentType('application/pdf');
        // Close and output PDF document
        // This method has several options, check the source code documentation for more information.
        $file = FCPATH.'files/labarugi.pdf';
        $pdf->Output($file, 'F');
        $attachment = base_url('files/labarugi.pdf');
        $pdf->Output('labarugi.pdf','I');  // display on the browser
    }

    public function stokopnamePdf()
    {
        $input = $this->request->getVar();
        $start = $input['tgl_start'];
        $end = $input['tgl_end'];
        $data = [
            'toko' => $this->toko->first(),
            'logo' => $this->setting->info['img_logo_resize'],
            'tgl_start' => $start,
            'tgl_end' => $end,
            'data' => $this->stokopname->getStokOpname($start, $end)
        ];

        $html = view('App\Modules\Laporan\Views/stokopname_pdf', $data);

        // create new PDF document
        $pdf = new Html2Pdf('P', 'A4');

        // Print text using writeHTMLCell()
        $pdf->writeHTML($html);
        $this->response->setContentType('application/pdf');
        // Close and output PDF document
        // This method has several options, check the source code documentation for more information.
        $file = FCPATH.'files/stokopname.pdf';
        $pdf->Output($file, 'F');
        $attachment = base_url('files/stokopname.pdf');
        $pdf->Output('stokopname.pdf','I');  // display on the browser
    }


}
