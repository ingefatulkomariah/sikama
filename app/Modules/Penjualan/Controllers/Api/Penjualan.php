<?php

namespace App\Modules\Penjualan\Controllers\Api;

use Exception;
use App\Controllers\BaseControllerApi;
use App\Libraries\Settings;
use App\Modules\Bank\Models\BankModel;
use App\Modules\Penjualan\Models\PenjualanModel;
use App\Modules\Barang\Models\BarangModel;
use App\Modules\Penjualan\Models\PenjualanItemModel;
use App\Modules\Toko\Models\TokoModel;
use App\Modules\Cashflow\Models\CashflowModel;
use App\Modules\Pajak\Models\PajakModel;
use App\Modules\Piutang\Models\PiutangModel;
use App\Modules\Log\Models\LogModel;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\RawbtPrintConnector;
use Mike42\Escpos\CapabilityProfile;
use CodeIgniter\I18n\Time;

class Penjualan extends BaseControllerApi
{
    protected $format       = 'json';
    protected $modelName    = PenjualanModel::class;
    protected $setting;
    protected $barang;
    protected $itemJual;
    protected $toko;
    protected $cashflow;
    protected $pajak;
    protected $piutang;
    protected $bank;
    protected $log;

    public function __construct()
    {
        $this->setting = new Settings();
        $this->barang = new BarangModel();
        $this->itemJual = new PenjualanItemModel();
        $this->toko = new TokoModel();
        $this->cashflow = new CashflowModel();
        $this->pajak = new PajakModel();
        $this->piutang = new PiutangModel();
        $this->bank = new BankModel();
        $this->log = new LogModel();
        helper('tglindo');
    }

    public function index()
    {
        $input = $this->request->getVar();
        $start = $input['tgl_start'] ?? "";
        $end = $input['tgl_end'] ?? "";
        if ($start == "" && $end == "") {
            $data = $this->model->getPenjualan();
        } else {
            $data = $this->model->getPenjualan($start, $end);
        }
        if (!empty($data)) {
            $response = [
                "status" => true,
                "message" => lang('App.getSuccess'),
                "data" => $data
            ];
            return $this->respond($response, 200);
        } else {
            $response = [
                'status' => false,
                'message' => lang('App.noData'),
                'data' => []
            ];
            return $this->respond($response, 200);
        }
    }

    public function show($id = null)
    {
        return $this->respond(["status" => true, "message" => lang("App.getSuccess"), "data" => $this->model->getPenjualanById($id)], 200);
    }

    public function create()
    {
        $rules = [
            'bayar' => [
                'rules'  => 'required',
                'errors' => []
            ],
            'id_kontak' => [
                'rules'  => 'required',
                'errors' => []
            ],
        ];

        if ($this->request->getJSON()) {
            $json = $this->request->getJSON();
            $hpp = $json->hpp;
            $subtotal = $json->subtotal;
            $diskon = $json->diskon;
            $diskonpersen = $json->diskon_persen;
            $total = $json->total;
            $bayar = $json->bayar;
            $kembali = $json->kembali;
            $idkontak = $json->id_kontak;
            $ppn = $json->ppn;
            $pajak = $json->pajak;
            $totalLaba = $subtotal-$hpp;
        } else {
            $hpp = $this->request->getPost('hpp');
            $subtotal = $this->request->getPost('subtotal');
            $diskon = $this->request->getPost('diskon');
            $diskonpersen = $this->request->getPost('diskon_persen');
            $total = $this->request->getPost('total');
            $bayar = $this->request->getPost('bayar');
            $kembali = $this->request->getPost('kembali');
            $idkontak = $this->request->getPost('id_kontak');
            $ppn = $this->request->getPost('ppn');
            $pajak = $this->request->getPost('pajak');
            $totalLaba = $subtotal-$hpp;
        }

        if (!$this->validate($rules)) {
            $response = [
                'status' => false,
                'message' => lang('App.isRequired'),
                'data' => $this->validator->getErrors(),
            ];
            return $this->respond($response, 200);
        } else {
            if ($bayar != 0) {
                $tanggal = date('Y-m-d');
                $tanggaltempo = Date('Y-m-d', strtotime('+3 days'));
                //Hitung
                $hitung = $bayar - $total;
                $piutang = $total - $bayar;

                //Ambil request post data
                $input = $this->request->getVar('data');
                foreach ($input as $value) {
                    $id_barang[] = $value[0];
                    $harga_jual[] = $value[1];
                    $stok[] = $value[2];
                    $jumlah[] = $value[3];
                    $satuan[] = $value[4];
                    $harga_beli[] = $value[5];
                }
                $total_barang = count($id_barang);

                //Ambil Data Toko
                $toko = $this->toko->first();
                //Ambil kode jual toko
                $kdJual = $toko['kode_jual'];
                $kdJualTahun = $toko['kode_jual_tahun'];
                //Ambil timestamp
                $time = Time::now();
                if ($time->getHour() < 10) {
                    $getHour = '0' . $time->getHour();
                } else {
                    $getHour = $time->getHour();
                }
                if ($time->getMinute() < 10) {
                    $getMinute = '0' . $time->getMinute();
                } else {
                    $getMinute = $time->getMinute();
                }
                if ($time->getSecond() < 10) {
                    $getSecond = '0' . $time->getSecond();
                } else {
                    $getSecond = $time->getSecond();
                }
                $timestamp = $getHour . $getMinute . $getSecond;
                if ($kdJualTahun == '1') {
                    $kodeNota = $kdJual . date('dmy') . '-' . $timestamp;
                } else {
                    $kodeNota = $kdJual . $timestamp;
                }
                $dataNota = [
                    'faktur' => $kodeNota,
                    'id_kontak' => $idkontak,
                    'jumlah' => $total_barang,
                    'PPN' => $ppn,
                    'hpp' => $hpp,
                    'subtotal' => $subtotal,
                    'diskon' => $diskon,
                    'diskon_persen' => $diskonpersen,
                    'pajak' => $pajak,
                    'total' => $total,
                    'bayar' => $bayar,
                    'kembali' => $kembali,
                    'total_laba' => $totalLaba,
                    'periode' => date('m-Y'),
                    'id_login' => session()->get('id'),
                    'id_toko' => 1
                ];
                //Save Nota
                $this->model->save($dataNota);
                $idPenjualan = $this->model->getInsertID();
                //Save Log
                $this->log->save(['keterangan' => session('nama') . '(' . session('email') . ') ' . strtolower(lang('App.do')) . ' Save Penjualan - Cash: ' . $kodeNota]);

                $arrNota = array();
                foreach ($input as $key => $value) {
                    $id_barang = $value[0];
                    $harga_jual = $value[1];
                    $stok = $value[2];
                    $qty = $value[3];
                    $satuan = $value[4];
                    $harga_beli = $value[5];
                    $diskon = $value[6];
                    $diskon_persen = $value[7];
                    $item = array(
                        'id_barang' => $id_barang,
                        'id_penjualan' => $idPenjualan,
                        'harga_beli' => $harga_beli,
                        'harga_jual' => $harga_jual,
                        'diskon' => $diskon,
                        'diskon_persen' => $diskon_persen,
                        'qty' => $qty,
                        'satuan' => $satuan,
                        'jumlah' => ((int)$harga_jual - (int)$diskon) * $qty,
                    );
                    array_push($arrNota, $item);
                }
                $dataItem = $arrNota;
                $this->itemJual->insertBatch($dataItem);

                //Update stok barang dikurangi qty
                $arrStok = array();
                foreach ($input as $key => $value) {
                    $id_barang = $value[0];
                    $harga_jual = $value[1];
                    $stok = $value[2];
                    $qty = $value[3];
                    $satuan = $value[4];
                    $harga_beli = $value[5];
                    $diskon = $value[6];
                    $diskon_persen = $value[7];
                    $stock = array(
                        'id_barang' => $id_barang,
                        'stok' => $stok - $qty,
                    );
                    array_push($arrStok, $stock);
                }
                $dataStok = $arrStok;
                $this->barang->updateBatch($dataStok, 'id_barang');

                //Update status barang (active = 0) jika stok 0 / habis
                $arrStokHabis = array();
                foreach ($input as $key => $value) {
                    $id_barang = $value[0];
                    $harga_jual = $value[1];
                    $stok = $value[2];
                    $qty = $value[3];
                    $satuan = $value[4];
                    $harga_beli = $value[5];
                    $diskon = $value[6];
                    $diskon_persen = $value[7];
                    //Cari data barang/barangnya
                    $barang = $this->barang->where('id_barang', $id_barang)->first();
                    $stok = $barang['stok'];
                    if ($stok == 0) {
                        $stokHabis = array(
                            'id_barang' => $id_barang,
                            'active' => 0,
                        );
                    } else {
                        $stokHabis = array(
                            'id_barang' => $id_barang,
                            'active' => 1,
                        );
                    }
                    array_push($arrStokHabis, $stokHabis);
                }
                $dataStokHabis = $arrStokHabis;
                $this->barang->updateBatch($dataStokHabis, 'id_barang');

                //Data Pajak - Ambil besaran PPN Toko
                if ($toko['PPN'] > 0) :
                    //Ambil kode Pajak
                    $kdPajak = $toko['kode_pajak'];
                    if ($kdJualTahun == '1') {
                        $kodePajak = $kdPajak . date('dmy') . '-' . $timestamp;
                    } else {
                        $kodePajak = $kdPajak . $timestamp;
                    }
                    $dataPajak = [
                        'faktur' =>  $kodePajak,
                        'PPN' => $ppn,
                        'jenis' => 'Keluaran',
                        'nominal' => $pajak,
                        'keterangan' => 'Penjualan: ' . $kodeNota,
                        'id_toko' => 1,
                        'id_login' => session()->get('id'),
                        'id_penjualan' => $idPenjualan,
                    ];
                    //Save Pajak
                    $this->pajak->save($dataPajak);
                    //Save Log
                    $this->log->save(['keterangan' => session('nama') . '(' . session('email') . ') ' . strtolower(lang('App.do')) . ' Save Pajak: ' . $kodePajak . ' Keterangan: Keluaran Penjualan']);
                endif;

                //Jika Lunas
                if ($bayar >= $total) {
                    $nominalKas = ($total - $pajak);
                    $dataKas = [
                        'faktur' => $kodeNota,
                        'jenis' => 'Pemasukan',
                        'kategori' => 'Penjualan',
                        'tanggal' => date('Y-m-d', strtotime(Time::now())),
                        'waktu' => date('H:i:s', strtotime(Time::now())),
                        'pemasukan' => $nominalKas,
                        'pengeluaran' => 0,
                        'keterangan' => 'Penjualan: ' . $kodeNota,
                        'id_penjualan' => $idPenjualan,
                        'id_toko' => 1,
                        'id_login' => session()->get('id'),
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => null
                    ];
                    //Save Cashflow
                    $this->cashflow->save($dataKas);
                    //Save Log
                    $this->log->save(['keterangan' => session('nama') . '(' . session('email') . ') ' . strtolower(lang('App.do')) . ' Save Cashflow: ' . $kodeNota]);

                    $response = [
                        'status' => true,
                        'message' => lang('App.saveSuccess'),
                        'data' => ['id_penjualan' => $idPenjualan],
                    ];
                    return $this->respond($response, 200);
                } else {
                    //Bayar kurang Masukkan ke Piutang
                    $dataPiutang = [
                        'id_penjualan' =>  $idPenjualan,
                        'tanggal' => $tanggal,
                        'jatuh_tempo' => $tanggaltempo,
                        'jumlah_piutang' => $piutang,
                        'jumlah_bayar' => 0,
                        'sisa_piutang' => $piutang,
                        'status_piutang' => 0,
                        'keterangan' => '',
                        'id_login' => session()->get('id'),
                        'id_toko' => 1
                    ];
                    //Save Piutang
                    $this->piutang->save($dataPiutang);
                    $idPiutang =  $this->piutang->getInsertID();
                    //Save Log
                    $this->log->save(['keterangan' => session('nama') . '(' . session('email') . ') ' . strtolower(lang('App.do')) . ' Save Piutang: ' . $idPenjualan]);

                    $nominalKas = ($total - $pajak) - $piutang;
                    $dataKas = [
                        'faktur' => $kodeNota,
                        'jenis' => 'Pemasukan',
                        'kategori' => 'Penjualan',
                        'tanggal' => date('Y-m-d', strtotime(Time::now())),
                        'waktu' => date('H:i:s', strtotime(Time::now())),
                        'pemasukan' => $nominalKas,
                        'pengeluaran' => 0,
                        'keterangan' => 'Penjualan: ' . $kodeNota . '. Piutang ID: ' . $idPiutang,
                        'id_penjualan' => $idPenjualan,
                        'id_toko' => 1,
                        'id_login' => session()->get('id'),
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => null
                    ];
                    //Save Cashflow
                    $this->cashflow->save($dataKas);
                    //Save Log
                    $this->log->save(['keterangan' => session('nama') . '(' . session('email') . ') ' . strtolower(lang('App.do')) . ' Save Cashflow: ' . $kodeNota]);

                    $response = [
                        'status' => true,
                        'message' => lang('App.saveSuccess') . ". " . lang('App.underPayment') . ". Rp. " . $hitung,
                        'data' => ['id_penjualan' => $idPenjualan],
                    ];
                    return $this->respond($response, 200);
                }
            } else {
                $response = [
                    'status' => false,
                    'message' => lang('App.selectBayar'),
                    'data' => [],
                ];
                return $this->respond($response, 200);
            }
        }
    }

    public function create1()
    {
        $rules = [
            'bayar' => [
                'rules'  => 'required',
                'errors' => []
            ],
            'jatuh_tempo' => [
                'rules'  => 'required',
                'errors' => []
            ],
            'keterangan' => [
                'rules'  => 'required',
                'errors' => []
            ],
            'id_kontak' => [
                'rules'  => 'required',
                'errors' => []
            ],
        ];

        if ($this->request->getJSON()) {
            $json = $this->request->getJSON();
            $hpp = $json->hpp;
            $subtotal = $json->subtotal;
            $diskon = $json->diskon;
            $diskonpersen = $json->diskon_persen;
            $total = $json->total;
            $bayar = $json->bayar;
            $kembali = $json->kembali;
            $idkontak = $json->id_kontak;
            $ppn = $json->ppn;
            $pajak = $json->pajak;
            $totalLaba = $subtotal-$hpp;
            $jatuhtempo = $json->jatuh_tempo;
            $keterangan = $json->keterangan;
        } else {
            $hpp = $this->request->getPost('hpp');
            $subtotal = $this->request->getPost('subtotal');
            $diskon = $this->request->getPost('diskon');
            $diskonpersen = $this->request->getPost('diskon_persen');
            $total = $this->request->getPost('total');
            $bayar = $this->request->getPost('bayar');
            $kembali = $this->request->getPost('kembali');
            $idkontak = $this->request->getPost('id_kontak');
            $ppn = $this->request->getPost('ppn');
            $pajak = $this->request->getPost('pajak');
            $totalLaba = $subtotal-$hpp;
            $jatuhtempo = $this->request->getPost('jatuh_tempo');
            $keterangan = $this->request->getPost('keterangan');
        }

        if (!$this->validate($rules)) {
            $response = [
                'status' => false,
                'message' => lang('App.isRequired'),
                'data' => $this->validator->getErrors(),
            ];
            return $this->respond($response, 200);
        } else {
            if ($bayar < $total) {
                $tanggal = date('Y-m-d');
                $tanggaltempo = Date('Y-m-d', strtotime('+3 days'));
                //Hitung
                $hitung = $bayar - $total;
                $piutang = $total - $bayar;

                //Ambil request post data
                $input = $this->request->getVar('data');
                foreach ($input as $value) {
                    $id_barang[] = $value[0];
                    $harga_jual[] = $value[1];
                    $stok[] = $value[2];
                    $jumlah[] = $value[3];
                    $satuan[] = $value[4];
                    $harga_beli[] = $value[5];
                }
                $total_barang = count($id_barang);

                //Ambil Data Toko
                $toko = $this->toko->first();
                //Ambil kode jual toko
                $kdJual = $toko['kode_jual'];
                $kdJualTahun = $toko['kode_jual_tahun'];
                //Ambil timestamp
                $time = Time::now();
                if ($time->getHour() < 10) {
                    $getHour = '0' . $time->getHour();
                } else {
                    $getHour = $time->getHour();
                }
                if ($time->getMinute() < 10) {
                    $getMinute = '0' . $time->getMinute();
                } else {
                    $getMinute = $time->getMinute();
                }
                if ($time->getSecond() < 10) {
                    $getSecond = '0' . $time->getSecond();
                } else {
                    $getSecond = $time->getSecond();
                }
                $timestamp = $getHour . $getMinute . $getSecond;
                if ($kdJualTahun == '1') {
                    $kodeNota = $kdJual . date('dmy') . '-' . $timestamp;
                } else {
                    $kodeNota = $kdJual . $timestamp;
                }
                $dataNota = [
                    'faktur' => $kodeNota,
                    'id_kontak' => $idkontak,
                    'jumlah' => $total_barang,
                    'PPN' => $ppn,
                    'hpp' => $hpp,
                    'subtotal' => $subtotal,
                    'diskon' => $diskon,
                    'diskon_persen' => $diskonpersen,
                    'pajak' => $pajak,
                    'total' => $total,
                    'bayar' => $bayar,
                    'kembali' => $kembali,
                    'total_laba' => $totalLaba,
                    'periode' => date('m-Y'),
                    'id_login' => session()->get('id'),
                    'id_toko' => 1
                ];
                $this->model->save($dataNota);
                $idPenjualan = $this->model->getInsertID();
                //Save Log
                $this->log->save(['keterangan' => session('nama') . '(' . session('email') . ') ' . strtolower(lang('App.do')) . ' Save Penjualan - Credit: ' . $kodeNota]);

                $arrNota = array();
                foreach ($input as $key => $value) {
                    $id_barang = $value[0];
                    $harga_jual = $value[1];
                    $stok = $value[2];
                    $qty = $value[3];
                    $satuan = $value[4];
                    $harga_beli = $value[5];
                    $diskon = $value[6];
                    $diskon_persen = $value[7];
                    $item = array(
                        'id_barang' => $id_barang,
                        'id_penjualan' => $idPenjualan,
                        'harga_beli' => $harga_beli,
                        'harga_jual' => $harga_jual,
                        'diskon' => $diskon,
                        'diskon_persen' => $diskon_persen,
                        'qty' => $qty,
                        'satuan' => $satuan,
                        'jumlah' => ((int)$harga_jual - (int)$diskon) * $qty,
                    );
                    array_push($arrNota, $item);
                }
                $dataItem = $arrNota;
                $this->itemJual->insertBatch($dataItem);

                //Update stok barang dikurangi qty
                $arrStok = array();
                foreach ($input as $key => $value) {
                    $id_barang = $value[0];
                    $harga_jual = $value[1];
                    $stok = $value[2];
                    $qty = $value[3];
                    $satuan = $value[4];
                    $harga_beli = $value[5];
                    $diskon = $value[6];
                    $diskon_persen = $value[7];
                    $stock = array(
                        'id_barang' => $id_barang,
                        'stok' => $stok - $qty,
                    );
                    array_push($arrStok, $stock);
                }
                $dataStok = $arrStok;
                $this->barang->updateBatch($dataStok, 'id_barang');

                //Update status barang (active = 0) jika stok 0 / habis
                $arrStokHabis = array();
                foreach ($input as $key => $value) {
                    $id_barang = $value[0];
                    $harga_jual = $value[1];
                    $stok = $value[2];
                    $qty = $value[3];
                    $satuan = $value[4];
                    $harga_beli = $value[5];
                    $diskon = $value[6];
                    $diskon_persen = $value[7];
                    //Cari data barang/barangnya
                    $barang = $this->barang->where('id_barang', $id_barang)->first();
                    $stok = $barang['stok'];
                    if ($stok == 0) {
                        $stokHabis = array(
                            'id_barang' => $id_barang,
                            'active' => 0,
                        );
                    } else {
                        $stokHabis = array(
                            'id_barang' => $id_barang,
                            'active' => 1,
                        );
                    }
                    array_push($arrStokHabis, $stokHabis);
                }
                $dataStokHabis = $arrStokHabis;
                $this->barang->updateBatch($dataStokHabis, 'id_barang');

                //Data Pajak - Ambil besaran PPN Toko
                if ($toko['PPN'] > 0) :
                    //Ambil kode Pajak
                    $kdPajak = $toko['kode_pajak'];
                    if ($kdJualTahun == '1') {
                        $kodePajak = $kdPajak . date('dmy') . '-' . $timestamp;
                    } else {
                        $kodePajak = $kdPajak . $timestamp;
                    }
                    $dataPajak = [
                        'faktur' =>  $kodePajak,
                        'PPN' => $ppn,
                        'jenis' => 'Keluaran',
                        'nominal' => $pajak,
                        'keterangan' => 'Penjualan: ' . $kodeNota,
                        'id_toko' => 1,
                        'id_login' => session()->get('id'),
                        'id_penjualan' => $idPenjualan,
                    ];
                    //Save Pajak
                    $this->pajak->save($dataPajak);
                    //Save Log
                    $this->log->save(['keterangan' => session('nama') . '(' . session('email') . ') ' . strtolower(lang('App.do')) . ' Save Pajak: ' . $kodePajak]);
                endif;

                //Update penjualan kembali menghitung kurangnya berdasarkan $idPenjualan
                $penjualanKembali = [
                    'kembali' => $hitung
                ];
                $this->model->update($idPenjualan, $penjualanKembali);

                //Belum Bayar Masukkan ke Piutang
                $dataPiutang = [
                    'id_penjualan' =>  $idPenjualan,
                    'tanggal' => $tanggal,
                    'jatuh_tempo' => $jatuhtempo,
                    'jumlah_piutang' => $piutang,
                    'jumlah_bayar' => 0,
                    'sisa_piutang' => $piutang,
                    'status_piutang' => 0,
                    'keterangan' => $keterangan,
                    'id_login' => session()->get('id'),
                    'id_toko' => 1
                ];
                //Save Piutang
                $this->piutang->save($dataPiutang);
                $idPiutang =  $this->piutang->getInsertID();
                //Save Log
                $this->log->save(['keterangan' => session('nama') . '(' . session('email') . ') ' . strtolower(lang('App.do')) . ' Save Piutang, ID Faktur: ' . $idPenjualan]);

                //Jika mengisi nominal Bayar
                if ($bayar != 0) {
                    $nominalKas = ($total - $pajak) - $piutang;
                    $dataKas = [
                        'faktur' => $kodeNota,
                        'jenis' => 'Pemasukan',
                        'kategori' => 'Penjualan',
                        'tanggal' => date('Y-m-d', strtotime(Time::now())),
                        'waktu' => date('H:i:s', strtotime(Time::now())),
                        'pemasukan' => $nominalKas,
                        'pengeluaran' => 0,
                        'keterangan' => 'Penjualan: ' . $kodeNota . '. Piutang ID: ' . $idPiutang,
                        'id_penjualan' => $idPenjualan,
                        'id_toko' => 1,
                        'id_login' => session()->get('id'),
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => null
                    ];
                    //Save Kas
                    $this->cashflow->save($dataKas);
                    //Save Log
                    $this->log->save(['keterangan' => session('nama') . '(' . session('email') . ') ' . strtolower(lang('App.do')) . ' Save Cashflow: ' . $kodeNota]);
                }

                $response = [
                    'status' => true,
                    'message' => lang('App.saveSuccess') . ". " . "Piutang: " . $keterangan,
                    'data' => ['id_penjualan' => $idPenjualan],
                ];
                return $this->respond($response, 200);
            } else {
                $response = [
                    'status' => false,
                    'message' => lang('App.fullBayar'),
                    'data' => [],
                ];
                return $this->respond($response, 200);
            }
        }
    }

    public function create2()
    {
        $rules = [
            'bayar' => [
                'rules'  => 'required',
                'errors' => []
            ],
            'noref_nokartu' => [
                'rules'  => 'required',
                'errors' => []
            ],
            'id_kontak' => [
                'rules'  => 'required',
                'errors' => []
            ],
        ];

        if ($this->request->getJSON()) {
            $json = $this->request->getJSON();
            $hpp = $json->hpp;
            $subtotal = $json->subtotal;
            $diskon = $json->diskon;
            $diskonpersen = $json->diskon_persen;
            $total = $json->total;
            $bayar = $json->bayar;
            $kembali = $json->kembali;
            $idkontak = $json->id_kontak;
            $ppn = $json->ppn;
            $pajak = $json->pajak;
            $totalLaba = $subtotal-$hpp;
            $noRefnoKartu = $json->noref_nokartu;
        } else {
            $hpp = $this->request->getPost('hpp');
            $subtotal = $this->request->getPost('subtotal');
            $diskon = $this->request->getPost('diskon');
            $diskonpersen = $this->request->getPost('diskon_persen');
            $total = $this->request->getPost('total');
            $bayar = $this->request->getPost('bayar');
            $kembali = $this->request->getPost('kembali');
            $idkontak = $this->request->getPost('id_kontak');
            $ppn = $this->request->getPost('ppn');
            $pajak = $this->request->getPost('pajak');
            $totalLaba = $subtotal-$hpp;
            $noRefnoKartu = $this->request->getPost('noref_nokartu');
        }

        if (!$this->validate($rules)) {
            $response = [
                'status' => false,
                'message' => lang('App.isRequired'),
                'data' => $this->validator->getErrors(),
            ];
            return $this->respond($response, 200);
        } else {
            //Hitung
            $hitung = $bayar - $total;
            if ($bayar == $total) {
                //Ambil request post data
                $input = $this->request->getVar('data');
                foreach ($input as $value) {
                    $id_barang[] = $value[0];
                    $harga_jual[] = $value[1];
                    $stok[] = $value[2];
                    $jumlah[] = $value[3];
                    $satuan[] = $value[4];
                    $harga_beli[] = $value[5];
                }
                $total_barang = count($id_barang);

                //Ambil Data Toko
                $toko = $this->toko->first();
                //Ambil kode jual toko
                $kdJual = $toko['kode_jual'];
                $kdJualTahun = $toko['kode_jual_tahun'];
                //Ambil timestamp
                $time = Time::now();
                if ($time->getHour() < 10) {
                    $getHour = '0' . $time->getHour();
                } else {
                    $getHour = $time->getHour();
                }
                if ($time->getMinute() < 10) {
                    $getMinute = '0' . $time->getMinute();
                } else {
                    $getMinute = $time->getMinute();
                }
                if ($time->getSecond() < 10) {
                    $getSecond = '0' . $time->getSecond();
                } else {
                    $getSecond = $time->getSecond();
                }
                $timestamp = $getHour . $getMinute . $getSecond;
                if ($kdJualTahun == '1') {
                    $kodeNota = $kdJual . date('dmy') . '-' . $timestamp;
                } else {
                    $kodeNota = $kdJual . $timestamp;
                }
                $dataNota = [
                    'faktur' => $kodeNota,
                    'id_kontak' => $idkontak,
                    'jumlah' => $total_barang,
                    'PPN' => $ppn,
                    'hpp' => $hpp,
                    'subtotal' => $subtotal,
                    'diskon' => $diskon,
                    'diskon_persen' => $diskonpersen,
                    'pajak' => $pajak,
                    'total' => $total,
                    'bayar' => $bayar,
                    'kembali' => $kembali,
                    'total_laba' => $totalLaba,
                    'periode' => date('m-Y'),
                    'id_login' => session()->get('id'),
                    'id_toko' => 1
                ];
                //Save Nota
                $this->model->save($dataNota);
                $idPenjualan = $this->model->getInsertID();
                //Save Log
                $this->log->save(['keterangan' => session('nama') . '(' . session('email') . ') ' . strtolower(lang('App.do')) . ' Save Penjualan - Bank: ' . $kodeNota]);

                $arrNota = array();
                foreach ($input as $key => $value) {
                    $id_barang = $value[0];
                    $harga_jual = $value[1];
                    $stok = $value[2];
                    $qty = $value[3];
                    $satuan = $value[4];
                    $harga_beli = $value[5];
                    $diskon = $value[6];
                    $diskon_persen = $value[7];
                    $item = array(
                        'id_barang' => $id_barang,
                        'id_penjualan' => $idPenjualan,
                        'harga_beli' => $harga_beli,
                        'harga_jual' => $harga_jual,
                        'diskon' => $diskon,
                        'diskon_persen' => $diskon_persen,
                        'qty' => $qty,
                        'satuan' => $satuan,
                        'jumlah' => ((int)$harga_jual - (int)$diskon) * $qty,
                    );
                    array_push($arrNota, $item);
                }
                $dataItem = $arrNota;
                $this->itemJual->insertBatch($dataItem);

                //Update stok barang dikurangi qty
                $arrStok = array();
                foreach ($input as $key => $value) {
                    $id_barang = $value[0];
                    $harga_jual = $value[1];
                    $stok = $value[2];
                    $qty = $value[3];
                    $satuan = $value[4];
                    $harga_beli = $value[5];
                    $diskon = $value[6];
                    $diskon_persen = $value[7];
                    $stock = array(
                        'id_barang' => $id_barang,
                        'stok' => $stok - $qty,
                    );
                    array_push($arrStok, $stock);
                }
                $dataStok = $arrStok;
                $this->barang->updateBatch($dataStok, 'id_barang');

                //Update status barang (active = 0) jika stok 0 / habis
                $arrStokHabis = array();
                foreach ($input as $key => $value) {
                    $id_barang = $value[0];
                    $harga_jual = $value[1];
                    $stok = $value[2];
                    $qty = $value[3];
                    $satuan = $value[4];
                    $harga_beli = $value[5];
                    $diskon = $value[6];
                    $diskon_persen = $value[7];
                    //Cari data barang/barangnya
                    $barang = $this->barang->where('id_barang', $id_barang)->first();
                    $stok = $barang['stok'];
                    if ($stok == 0) {
                        $stokHabis = array(
                            'id_barang' => $id_barang,
                            'active' => 0,
                        );
                    } else {
                        $stokHabis = array(
                            'id_barang' => $id_barang,
                            'active' => 1,
                        );
                    }
                    array_push($arrStokHabis, $stokHabis);
                }
                $dataStokHabis = $arrStokHabis;
                $this->barang->updateBatch($dataStokHabis, 'id_barang');

                //Data Pajak - Ambil besaran PPN Toko
                if ($toko['PPN'] > 0) :
                    //Ambil kode Pajak
                    $kdPajak = $toko['kode_pajak'];
                    if ($kdJualTahun == '1') {
                        $kodePajak = $kdPajak . date('dmy') . '-' . $timestamp;
                    } else {
                        $kodePajak = $kdPajak . $timestamp;
                    }
                    $dataPajak = [
                        'faktur' =>  $kodePajak,
                        'PPN' => $ppn,
                        'jenis' => 'Keluaran',
                        'nominal' => $pajak,
                        'keterangan' => 'Penjualan: ' .$kodeNota,
                        'id_toko' => 1,
                        'id_login' => session()->get('id'),
                        'id_penjualan' => $idPenjualan,
                    ];
                    //Save Pajak
                    $this->pajak->save($dataPajak);
                    //Save Log
                    $this->log->save(['keterangan' => session('nama') . '(' . session('email') . ') ' . strtolower(lang('App.do')) . ' Save Pajak: ' . $kodePajak . ' Keterangan: Keluaran Penjualan']);
                endif;

                //Data Bank - Ambil kode Bank
                $kdBank = $toko['kode_bank'];
                $idBankAkun = $toko['id_bank_akun'];
        
                $nominalBank = ($total - $pajak);
                $dataBank = [
                    'faktur' => $kodeNota,
                    'jenis' => 'Pemasukan',
                    'tanggal' => date('Y-m-d', strtotime(Time::now())),
                    'waktu' => date('H:i:s', strtotime(Time::now())),
                    'pemasukan' => $nominalBank,
                    'pengeluaran' => 0,
                    'keterangan' => 'Penjualan: ' . $kodeNota,
                    'id_penjualan' => $idPenjualan,
                    'id_toko' => 1,
                    'id_login' => session()->get('id'),
                    'id_bank_akun' => $idBankAkun,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => null
                ];
                //Simpan Data Bank
                $this->bank->save($dataBank);
                //Save Log
                $this->log->save(['keterangan' => session('nama') . '(' . session('email') . ') ' . strtolower(lang('App.do')) . ' Save Bank: ' . $kodeNota . ' Keterangan: Pemasukan Penjualan']);

                $response = [
                    'status' => true,
                    'message' => lang('App.saveSuccess'),
                    'data' => ['id_penjualan' => $idPenjualan],
                ];
                return $this->respond($response, 200);
            } else {
                $response = [
                    'status' => false,
                    'message' => lang('App.notcorrectPayment') . ' +- ' . $hitung,
                    'data' => [],
                ];
                return $this->respond($response, 200);
            }
        }
    }

    public function update($id = NULL)
    {
        $rules = [
            'id_barang' => [
                'rules'  => 'required',
                'errors' => []
            ],
        ];

        if ($this->request->getJSON()) {
            $json = $this->request->getJSON();
            $data = [
                'id_barang' => $json->id_barang,
                'id_kontak' => $json->id_kontak,
                'jumlah' => $json->jumlah,
                'total' => $json->total,
                'periode' => $json->periode,
            ];
        } else {
            $data = $this->request->getRawInput();
        }

        if (!$this->validate($rules)) {
            $response = [
                'status' => false,
                'message' => lang('App.isRequired'),
                'data' => $this->validator->getErrors(),
            ];
            return $this->respond($response, 200);
        } else {
            $this->model->update($id, $data);
            $response = [
                'status' => true,
                'message' => lang('App.updSuccess'),
                'data' => [],
            ];
            return $this->respond($response, 200);
        }
    }

    public function delete($id = null)
    {
        $delete = $this->model->find($id);
        $faktur = $delete['faktur'];

        //Cek data Piutang dulu karena akan terkena Foreign key cek 'restrict'
        $cekPiutang = $this->piutang->where(['id_penjualan' => $id])->findAll();
        if ($cekPiutang) :
            $response = [
                'status' => true,
                'message' => lang('App.delFailedPiutang'),
                'data' => [],
            ];
            return $this->respond($response, 200);
        endif;

        if (!$cekPiutang) {
            //Cari data item penjualan
            $item = $this->itemJual->where('id_penjualan', $id)->findAll();
            foreach ($item as $row) {
                $idItemJual = $row['id_itempenjualan'];
                $idBarang = $row['id_barang'];
                $qty = $row['qty'];

                //Cari data barang/barangnya
                $barang = $this->barang->where('id_barang', $idBarang)->first();
                $stok = $barang['stok'];
                $dataStok = [
                    'stok' => $stok + $qty,
                ];
                //Update stok barang/barangnya
                $this->barang->update($idBarang, $dataStok);

                //Hapus item penjualan
                $this->itemJual->delete($idItemJual);
            }

            //Cari data Cashflow
            $cash = $this->cashflow->where('faktur', $faktur)->findAll();
            if ($cash) :
                foreach ($cash as $row) {
                    $idCashflow = $row['id_cashflow'];
                    //Hapus Cashflow
                    $this->cashflow->delete($idCashflow);
                    //Save Log
                    $this->log->save(['keterangan' => session('nama') . '(' . session('email') . ') ' . strtolower(lang('App.do')) . ' Delete Cashflow: ' . $idCashflow]);
                }
            endif;

            //Hapus penjualan
            $this->model->delete($id);
            //Save Log
            $this->log->save(['keterangan' => session('nama') . '(' . session('email') . ') ' . strtolower(lang('App.do')) . ' Delete Penjualan: ' . $faktur]);

            $response = [
                'status' => true,
                'message' => lang('App.delSuccess'),
                'data' => [],
            ];
            return $this->respond($response, 200);
        } else {
            $response = [
                'status' => false,
                'message' => lang('App.delFailed'),
                'data' => [],
            ];
            return $this->respond($response, 200);
        }
    }

    public function cetakNota($id = null)
    {
        return $this->respond(["status" => true, "message" => lang("App.getSuccess"), "data" => $this->itemJual->findNota($id)], 200);
    }

    //Function Ribuan
    function Ribuan($angka)
    {
        $hasil_rupiah = number_format($angka, 0, ',', '.');
        return $hasil_rupiah;
    }

    public function cetakUSB()
    {
        if ($this->request->getJSON()) {
            $json = $this->request->getJSON();
            $id = $json->id_penjualan;
        } else {
            $id = $this->request->getPost('id_penjualan');
        }

        $toko = $this->toko->first();
        $penjualan = $this->model->getPenjualanById($id);
        $item = $this->itemJual->findNotaCetak($id);

        $tanggal = dayshortdate_indo(date('Y-m-d', strtotime($penjualan['created_at']))) . ' ' . date('H:i', strtotime($penjualan['created_at']));
        $user = session()->get('nama');
        $appname = APP_NAME;
        $companyname = COMPANY_NAME;

        // Data Toko
        $namaToko = $toko['nama_toko'];
        $alamatToko = $toko['alamat_toko'];
        $telpToko = $toko['telp'];
        $nibToko = $toko['NIB'];
        $paperSize = $toko['paper_size'];
        $footerNota = $toko['footer_nota'];

        // Data Nota
        $faktur = $penjualan['faktur'];
        $subtotal = $this->Ribuan($penjualan['subtotal']);
        $ppn = $penjualan['PPN'];
        $pajak = $this->Ribuan($penjualan['pajak']);
        $diskon = $this->Ribuan($penjualan['diskon']);
        $diskonPersen = $penjualan['diskon_persen'];
        $total = $this->Ribuan($penjualan['total']);
        $bayar = $this->Ribuan($penjualan['bayar']);
        $kembali = $this->Ribuan($penjualan['kembali']);
        $items = $penjualan['jumlah'];
        $kontak = $penjualan['nama_kontak'];

        // Logo
        $logo = $this->setting->info['img_logo_resize'];
        $img = EscposImage::load("$logo", false, ['native']);

        try {
            /**
             * Install the printer dengan USB printing support (Generic / Text Only driver),
             * Buka Windows Control Panel > Devices and Printers > Printernya > Klik Kanan Printer properties 
             * Klik Tab Sharing > Share Name = Receipt Printer
             * Klik OK
             */
            // Share name dari USB printer
            $connector = new WindowsPrintConnector("Receipt Printer");

            // Mulai Printer
            $printer = new Printer($connector);
            $printer->feed();
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->bitImage($img);
            $printer->text("\n");
            $printer->setFont(Printer::FONT_A);
            $printer->text("$namaToko\n");
            $printer->setFont(Printer::FONT_B);
            $printer->text("NIB: $nibToko\n");
            $printer->text("$alamatToko\n");
            $printer->text("Telp/WA: $telpToko\n");
            $printer->textRaw(str_repeat(chr(196), 31) . PHP_EOL);
            $printer->setFont(false);
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("No: $faktur\n");
            $printer->text("Hr/Tgl: $tanggal\n");
            $printer->text("Kasir: $user\n");
            $printer->text("Customer: $kontak\n");
            $printer->textRaw(str_repeat(chr(196), 31) . PHP_EOL);
            foreach ($item as $item) {
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->text("$item->nama_barang\n");
                $printer->text(str_pad("$item->qty $item->satuan x {$this->Ribuan($item->harga_jual)}", 20));
                $printer->text(str_pad("{$this->Ribuan($item->jumlah)}", 10, ' ', STR_PAD_LEFT));
                $printer->text("\n");
            }
            $printer->textRaw(str_repeat(chr(196), 31) . PHP_EOL);
            $printer->setJustification(Printer::JUSTIFY_RIGHT);
            $printer->text("Subtotal ($items item): $subtotal\n");
            $printer->text("PPN $ppn%: $pajak\n");
            $printer->text("Diskon $diskonPersen%: $diskon\n");
            $printer->selectPrintMode(Printer::MODE_EMPHASIZED);
            $printer->text("Total: $total\n");
            $printer->selectPrintMode();
            $printer->text("Bayar: $bayar\n");
            if ($kembali >= 0) {
                $printer->text("Kembali: $kembali\n");
            } else {
                $printer->text("Kurang: $kembali\n");
            }
            $printer->text("\n");
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setFont(Printer::FONT_B);
            $printer->text("$footerNota. Dicetak menggunakan Aplikasi $appname by $companyname\n");
            $printer->feed(2);
            /* Cut printer */
            //$printer->cut();
            /* Tutup printer */
            $printer->close();

            $response = [
                'status' => true,
                'message' => 'Print Success',
                'data' => [],
            ];
            return $this->respond($response, 200);
        } catch (Exception $e) {
            //echo "Couldn't print to this printer: " . $e->getMessage() . "\n";
            $response = [
                'status' => false,
                'message' => "Couldn't print to this printer: " . $e->getMessage() . "\n",
                'data' => [],
            ];
            return $this->respond($response, 200);
        }
    }

    public function cetakBluetooth()
    {
        if ($this->request->getJSON()) {
            $json = $this->request->getJSON();
            $id = $json->id_penjualan;
        } else {
            $id = $this->request->getPost('id_penjualan');
        }

        $toko = $this->toko->first();
        $penjualan = $this->model->getPenjualanById($id);
        $item = $this->itemJual->findNotaCetak($id);

        $tanggal = dayshortdate_indo(date('Y-m-d', strtotime($penjualan['created_at']))) . ' ' . date('H:i', strtotime($penjualan['created_at']));
        $user = session()->get('nama');
        $appname = APP_NAME;
        $companyname = COMPANY_NAME;

        // Data Toko
        $namaToko = $toko['nama_toko'];
        $alamatToko = $toko['alamat_toko'];
        $telpToko = $toko['telp'];
        $nibToko = $toko['NIB'];
        $paperSize = $toko['paper_size'];
        $footerNota = $toko['footer_nota'];

        // Data Nota
        $faktur = $penjualan['faktur'];
        $subtotal = $this->Ribuan($penjualan['subtotal']);
        $ppn = $penjualan['PPN'];
        $pajak = $this->Ribuan($penjualan['pajak']);
        $diskon = $this->Ribuan($penjualan['diskon']);
        $diskonPersen = $penjualan['diskon_persen'];
        $total = $this->Ribuan($penjualan['total']);
        $bayar = $this->Ribuan($penjualan['bayar']);
        $kembali = $this->Ribuan($penjualan['kembali']);
        $items = $penjualan['jumlah'];
        $kontak = $penjualan['nama_kontak'];

        // Logo
        $logo = $this->setting->info['img_logo_resize'];
        $img = EscposImage::load("$logo", false, ['native']);

        try {
            $profile = CapabilityProfile::load("POS-5890");

            /* Fill in your own connector here */
            $connector = new RawbtPrintConnector();

            // Mulai Printer
            $printer = new Printer($connector, $profile);
            $printer->feed();
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->bitImage($img);
            $printer->text("\n");
            $printer->setFont(Printer::FONT_A);
            $printer->text("$namaToko\n");
            $printer->setFont(Printer::FONT_B);
            $printer->text("NIB: $nibToko\n");
            $printer->text("$alamatToko\n");
            $printer->text("Telp/WA: $telpToko\n");
            $printer->textRaw(str_repeat(chr(196), 31) . PHP_EOL);
            $printer->setFont(false);
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("No: $faktur\n");
            $printer->text("Hr/Tgl: $tanggal\n");
            $printer->text("Kasir: $user\n");
            $printer->text("Customer: $kontak\n");
            $printer->textRaw(str_repeat(chr(196), 31) . PHP_EOL);
            foreach ($item as $item) {
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->text("$item->nama_barang\n");
                $printer->text(str_pad("$item->qty $item->satuan x {$this->Ribuan($item->harga_jual)}", 20));
                $printer->text(str_pad("{$this->Ribuan($item->jumlah)}", 10, ' ', STR_PAD_LEFT));
                $printer->text("\n");
            }
            $printer->textRaw(str_repeat(chr(196), 31) . PHP_EOL);
            $printer->setJustification(Printer::JUSTIFY_RIGHT);
            $printer->text("Subtotal ($items item): $subtotal\n");
            $printer->text("PPN $ppn%: $pajak\n");
            $printer->text("Diskon $diskonPersen%: $diskon\n");
            $printer->selectPrintMode(Printer::MODE_EMPHASIZED);
            $printer->text("Total: $total\n");
            $printer->selectPrintMode();
            $printer->text("Bayar: $bayar\n");
            if ($kembali >= 0) {
                $printer->text("Kembali: $kembali\n");
            } else {
                $printer->text("Kurang: $kembali\n");
            }
            $printer->text("\n");
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setFont(Printer::FONT_B);
            $printer->text("$footerNota. Dicetak menggunakan Aplikasi $appname by $companyname\n");
            $printer->feed(2);
            /* Cut printer */
            //$printer->cut();
            /* Tutup printer */
            $printer->close();

            $response = [
                'status' => true,
                'message' => 'Print Success',
                'data' => [],
            ];
            return $this->respond($response, 200);
        } catch (Exception $e) {
            //echo "Couldn't print to this printer: " . $e->getMessage() . "\n";
            $response = [
                'status' => false,
                'message' => "Couldn't print to this printer: " . $e->getMessage() . "\n",
                'data' => [],
            ];
            return $this->respond($response, 200);
        }
    }
}
