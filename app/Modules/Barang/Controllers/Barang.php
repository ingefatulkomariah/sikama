<?php

namespace App\Modules\Barang\Controllers;
/*
PT. GLOBAL ITSHOP PURWOKERTO
Toko Online: ITShop Purwokerto (Tokopedia, Shopee, Bukalapak, Blibli)
Dibuat oleh: Hari Wicaksono, S.Kom
06-2022
*/

use App\Controllers\BaseController;
use App\Modules\Barang\Models\BarangModel;
use App\Modules\Penjualan\Models\PenjualanModel;
use Ramsey\Uuid\Uuid;
use ShortUUID\ShortUUID;
use Picqer;

class Barang extends BaseController
{
    protected $barang;
    protected $penjualan;

    public function __construct()
    {
        //memeriksa session role selain Admin redirect ke /dashboard
        if ((session()->get('role') == '2' || session()->get('role') == '4')) {
            header('location:/dashboard');
            exit();
        }

        //memanggil Model
        $this->barang = new BarangModel();
        $this->penjualan = new PenjualanModel();
        //$this->userModel = new UserModel();
    }

    public function index()
    {
        $cari = $this->request->getVar('search');
        return view('App\Modules\Barang\Views/barang', [
            'title' => lang('App.items'),
            'search' => $cari
        ]);
    }

    //function _generateId()
    //{
        //helper('text');
        //$unique = random_string('nozero', 1).random_string('alpha', 1).random_string('numeric', 2).random_string('alpha', 3);
        //return $unique;
    //}

    public function add()
    {
        //$uniqueid = strtolower($this->_generateId());
        $uuid = Uuid::uuid4();
		$suuid = new ShortUUID();
        return view('App\Modules\Barang\Views/barang_baru', [
            'title' => lang('App.add'),
            'uuid' => $suuid->encode($uuid),
        ]);
    }

    public function edit($id = null)
    {  
        $data = $this->barang->find($id);
        
        return view('App\Modules\Barang\Views/barang_edit', [
            'title' => lang('App.edit'),
            'data' => $data,
        ]);
    }

    public function barcode()
    {
        $str = $this->request->getVar('text');
        $jml = $this->request->getVar('jumlah');
        $tipe= $this->request->getVar('tipe') ? $this->request->getVar('tipe') : "";
        $this->generateBarcode($str, $jml, $tipe);
    }

    private function generateBarcode( $string, $jumlah, $tipe ="HTML" )
    {
        switch($tipe)
        {
            case "HTML":
                $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
                break;
            case "JPG":
                header('Content-type: image/jpeg');
                $generator = new Picqer\Barcode\BarcodeGeneratorJPG();
                break;
            case "PNG":
                $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
                break;
            case "SVG":
                $generator = new Picqer\Barcode\BarcodeGeneratorSVG();
                break;
            default:
                $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
        }
        
        $barcode   = $generator->getBarcode($string, $generator::TYPE_CODE_128, 2, 60);
        echo view("App\Modules\Barang\Views/barcode", [
            "barcode" => $barcode, 
            "text" => $string, 
            "tipe" => $tipe,
            "jumlah" => $jumlah,
        ]);
    }


}
