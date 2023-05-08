<?php

namespace  App\Modules\Excel\Controllers;
/*
PT. GLOBAL ITSHOP PURWOKERTO
Toko Online: ITShop Purwokerto (Tokopedia, Shopee, Bukalapak, Blibli)
Dibuat oleh: Hari Wicaksono, S.Kom
06-2022
*/

use App\Controllers\BaseController;
use App\Libraries\Settings;
use App\Modules\Kategori\Models\KategoriModel;
use App\Modules\Barang\Models\BarangModel;
use App\Modules\Barang\Models\BarangItemModel;
use App\Modules\Satuan\Models\SatuanModel;
use App\Modules\Toko\Models\TokoModel;
use App\Modules\Log\Models\LogModel;
use Ramsey\Uuid\Uuid;
use ShortUUID\ShortUUID;

class Excel extends BaseController
{
    protected $setting;
    protected $barang;
    protected $kategori;
    protected $satuan;
    protected $log;
    protected $item;
    protected $toko;

    public function __construct()
    {
        //memeriksa session role selain Admin redirect ke /dashboard
        if ((session()->get('role') == '2' || session()->get('role') == '4')) {
            header('location:/dashboard');
            exit();
        }

        //memanggil Model
        $this->setting = new Settings();
        $this->barang = new BarangModel();
        $this->kategori = new KategoriModel();
        $this->satuan = new SatuanModel();
        $this->log = new LogModel();
        $this->item = new BarangItemModel();
        $this->toko = new TokoModel();
    }


    public function import()
    {
        return view('App\Modules\Excel\Views/import', [
            'title' => 'Import Data Barang Excel'
        ]);
    }

    public function saveExcel()
    {
        $rules = [
            'fileexcel' => [
                'rules'  => 'uploaded[fileexcel]|ext_in[fileexcel,xlsx,xls,csv]',
                'errors' => []
            ],
        ];

        $ignoreName = $this->request->getPost('ignorename');
        $file_excel = $this->request->getFile('fileexcel');

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput();
        } else {
            $ext = $file_excel->getClientExtension();
            if ($ext == 'xls') {
                $render = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            } else {
                $render = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }
            $spreadsheet = $render->load($file_excel);

            $data = $spreadsheet->getActiveSheet()->toArray();

            foreach ($data as $x => $row) {
                if ($x == 0) {
                    continue;
                }
                $barcode = $row[0];
                $namaBarang = $row[1];
                $merk = $row[2];
                $hargaBeli = $row[3];
                $hargaJual = $row[4];
                $satuan = $row[5];
                $deskripsi = $row[6];
                $stok = $row[7];
                $kategori = $row[8];

                $cekKategori = $this->kategori->where('nama_kategori', $kategori)->first();
                if ($cekKategori) {
                    $idKategori = $cekKategori['id_kategori'];
                } else {
                    $this->kategori->save(['nama_kategori' => $kategori]);
                    $idKategori = $this->kategori->getInsertID();
                }

                $cekSatuan = $this->satuan->where('nama_satuan', $satuan)->first();
                if ($cekSatuan) {
                    $namaSatuan = $cekSatuan['nama_satuan'];
                } else {
                    $this->satuan->save(['nama_satuan' => $satuan]);
                    $idSatuan = $this->satuan->getInsertID();
                    $qSatuan = $this->satuan->where('id_satuan', $idSatuan)->first();
                    $namaSatuan = $qSatuan['nama_satuan'];
                }

                $uuid = Uuid::uuid4();
                $suuid = new ShortUUID();

                $simpandata = [
                    'id_barang' => $suuid->encode($uuid),
                    'barcode' => $barcode,
                    'nama_barang' => $namaBarang,
                    'merk' => $merk,
                    'harga_beli' => $hargaBeli,
                    'harga_jual' => $hargaJual,
                    'satuan_barang' => $namaSatuan,
                    'deskripsi' => $deskripsi,
                    'stok' => $stok,
                    'active' => 1,
                    'id_kategori' => $idKategori,
                    'stok_min' => 0,
                    'id_kontak' => null,
                    'expired' => null
                ];

                // Fungsi cek barang untuk cek nama barang yang sama
                if ($ignoreName == true) {
                    $cekKode = array();
                } else {
                    $cekKode = $this->barang->getWhere(['nama_barang' => $namaBarang])->getResult();
                }

                if (count($cekKode) > 0) {
                    session()->setFlashdata('error', 'Import data gagal karena Nama Barang sudah ada');
                } else {
                    $this->barang->save($simpandata);
                    $lastID = $this->barang->getInsertID();
                    $barang = $this->barang->find($lastID);
                    $idBarang = $barang['id_barang'];

                    //Table Item Barang
                    $findItem = $this->item->findAll();
                    if (empty($findItem)) :
                        $this->item->truncate();
                    endif;
                    $query = $this->item->selectMax('id_barang_item', 'last');
                    $hasil = $query->get()->getRowArray();
                    $last = $hasil['last'] + 1;
                    $noKode = sprintf('%05s', $last);
                    //Ambil kode jual toko
                    $toko = $this->toko->first();
                    $kdBarang = $toko['kode_barang'];
                    $kodeBarang = $kdBarang . $noKode;
                    $dataKode = [
                        'id_barang' => $idBarang,
                        'kode_barang' => $kodeBarang,
                    ];
                    $this->item->save($dataKode);

                    //Save Log
                    $this->log->save(['keterangan' => session('nama') . '(' . session('email') . ') ' . strtolower(lang('App.do')) . ' Import Barang Excel']);

                    session()->setFlashdata('success', 'Proses Import data Excel Berhasil');
                }
            }

            return redirect()->to('/excel/import');
        }
    }
}
