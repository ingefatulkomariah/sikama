<?php

namespace App\Modules\Keranjang\Controllers\Api;
/*
PT. GLOBAL ITSHOP PURWOKERTO
Toko Online: ITShop Purwokerto (Tokopedia, Shopee, Bukalapak, Blibli)
Dibuat oleh: Hari Wicaksono, S.Kom
02-2023
*/

use App\Controllers\BaseControllerApi;
use App\Modules\Keranjang\Models\KeranjangModel;
use App\Modules\Keranjang\Models\OrderModel;
use App\Modules\Barang\Models\BarangModel;

class Keranjang extends BaseControllerApi
{
    protected $format       = 'json';
    protected $modelName    = KeranjangModel::class;
    protected $order;
    protected $barang;

    public function __construct()
    {
        $this->order = new OrderModel();
        $this->barang = new BarangModel();
    }

    // Keranjang Jual
    public function index()
    {
        return $this->respond(["status" => true, "message" => lang('App.getSuccess'), "data" => $this->model->getKeranjang()], 200);
    }

    // Keranjang Beli
    public function beli()
    {
        return $this->respond(["status" => true, "message" => lang('App.getSuccess'), "data" => $this->order->getKeranjang()], 200);
    }

    public function show($id = null)
    {
        return $this->respond(['status' => true, 'message' => lang('App.getSuccess'), 'data' => $this->model->find($id)], 200);
    }

    public function show2($id = null)
    {
        return $this->respond(['status' => true, 'message' => lang('App.getSuccess'), 'data' => $this->order->find($id)], 200);
    }

    public function create()
    {
        $rules = [
            'id_barang' => [
                'rules'  => 'required',
                'errors' => []
            ],
        ];

        if ($this->request->getJSON()) {
            $json = $this->request->getJSON();

            $id_barang = $json->id_barang;
            $qty = $json->qty;

            //cari data barang/barangnya sesuai id_barang
            $data = $this->barang->where(['id_barang' => $id_barang])->first();
            $beli = $data['harga_beli'];
            $jual = $data['harga_jual'];
            $satuan = $data['satuan_barang'];
            $diskon = $data['diskon'];
            $diskonPersen = $data['diskon_persen'];
            $hpp = $beli * $qty;
            $jumlah = ((int)$jual-(int)$diskon) * $qty;
            $data = [
                'id_barang' => $id_barang,
                'id_kontak' => $json->id_kontak,
                'harga_beli' => $beli,
                'harga_jual' => $json->harga_jual,
                'diskon' => $diskon,
                'diskon_persen' => $diskonPersen,
                'stok' => $json->stok,
                'qty' => $qty,
                'satuan' => $satuan,
                'hpp' => $hpp,
                'jumlah' => $jumlah,
                'total_laba' => $jumlah-$hpp
            ];
        } else {
            $id_barang = $this->request->getPost('id_barang');
            $qty = $this->request->getPost('qty');

            //cari data barang/barangnya sesuai id_barang
            $data = $this->barang->where(['id_barang' => $id_barang])->first();
            $beli = $data['harga_beli'];
            $jual = $data['harga_jual'];
            $satuan = $data['satuan_barang'];
            $diskon = $data['diskon'];
            $diskonPersen = $data['diskon_persen'];
            $hpp = $beli * $qty;
            $jumlah = ((int)$jual-(int)$diskon) * $qty;
            $data = [
                'id_barang' => $id_barang,
                'id_kontak' => $this->request->getPost('id_kontak'),
                'harga_beli' => $beli,
                'harga_jual' => $this->request->getPost('harga_jual'),
                'diskon' => $diskon,
                'diskon_persen' => $diskonPersen,
                'stok' => $this->request->getPost('stok'),
                'qty' => $qty,
                'satuan' => $satuan,
                'hpp' => $hpp,
                'jumlah' => $jumlah,
                'total_laba' => $jumlah-$hpp
            ];
        }

        if (!$this->validate($rules)) {
            $response = [
                'status' => false,
                'message' => lang('App.isRequired'),
                'data' => $this->validator->getErrors(),
            ];
            return $this->respond($response, 200);
        } else {
            //cari barang/barangnya apakah sudah ada di keranjang
            $cari_keranjang = $this->model->where(['id_barang' => $id_barang])->first();
            if ($cari_keranjang) {
                $id_keranjang = $cari_keranjang['id_keranjang'];
                $beli = $cari_keranjang['harga_beli'];
                $jual = $cari_keranjang['harga_jual'];
                $diskon = $cari_keranjang['diskon'];
                $diskonPersen = $cari_keranjang['diskon_persen'];
                $qty = $cari_keranjang['qty'] + 1;
                $hpp = $beli * $qty;
                $jumlah = ((int)$jual-(int)$diskon) * $qty;
                $update = [
                    'qty' => $qty,
                    'hpp' => $hpp,
                    'jumlah' => $jumlah,
                    'total_laba' => $jumlah-$hpp
                ];

                $id_barang = $cari_keranjang['id_barang'];
                $qty_barang = $cari_keranjang['qty'];
                $barang = $this->barang->where(['id_barang' => $id_barang])->first();
                $stok = $barang['stok'];

                if ($qty_barang >= $stok) {
                    $response = [
                        'status' => false,
                        'message' => lang('App.stockLess'),
                        'data' => [],
                    ];
                    return $this->respond($response, 200);
                } else {
                    //lalu update qty nya
                    $this->model->update($id_keranjang, $update);
                }
            } else {
                //simpan barang/barang yang belum ada di keranjang
                $this->model->save($data);
            }

            $response = [
                'status' => true,
                'message' => lang('App.itemSuccess'),
                'data' => [],
            ];
            return $this->respond($response, 200);
        }
    }

    public function create2()
    {
        $rules = [
            'id_barang' => [
                'rules'  => 'required',
                'errors' => []
            ],
        ];

        if ($this->request->getJSON()) {
            $json = $this->request->getJSON();

            $id_barang = $json->id_barang;
            $beli = $json->harga_beli;
            $qty = $json->qty;

            //cari data barang/barangnya sesuai id_barang
            $data = $this->barang->where(['id_barang' => $id_barang])->first();
            $jual = $data['harga_jual'];
            $satuan = $data['satuan_barang'];
            $jumlah = $beli * $qty;
            $data = [
                'id_barang' => $id_barang,
                'id_kontak' => $json->id_kontak,
                'harga_beli' => $beli,
                'harga_jual' => $jual,
                'stok' => $json->stok,
                'qty' => $qty,
                'satuan' => $satuan,
                'jumlah' => $jumlah,
            ];
        } else {
            $id_barang = $this->request->getPost('id_barang');
            $beli = $this->request->getPost('harga_beli');
            $qty = $this->request->getPost('qty');

            //cari data barang/barangnya sesuai id_barang
            $data = $this->barang->where(['id_barang' => $id_barang])->first();
            $jual = $data['harga_jual'];
            $satuan = $data['satuan_barang'];
            $jumlah = $beli * $qty;
            $data = [
                'id_barang' => $id_barang,
                'id_kontak' => $this->request->getPost('id_kontak'),
                'harga_beli' => $beli,
                'harga_jual' => $jual,
                'stok' => $this->request->getPost('stok'),
                'qty' => $qty,
                'satuan' => $satuan,
                'jumlah' => $jumlah,
            ];
        }

        if (!$this->validate($rules)) {
            $response = [
                'status' => false,
                'message' => lang('App.isRequired'),
                'data' => $this->validator->getErrors(),
            ];
            return $this->respond($response, 200);
        } else {
            //cari barang/barangnya apakah sudah ada di keranjang
            $cari_keranjang = $this->order->where(['id_barang' => $id_barang])->first();
            if ($cari_keranjang) {
                $id_keranjang = $cari_keranjang['id_order'];
                $beli = $cari_keranjang['harga_beli'];
                $jual = $cari_keranjang['harga_jual'];
                $qty = $cari_keranjang['qty'] + 1;
                $jumlah = $beli * $qty;
                $update = [
                    'qty' => $qty,
                    'jumlah' => $jumlah,
                ];
                //lalu update qty nya
                $this->order->update($id_keranjang, $update);
            } else {
                //simpan barang/barang yang belum ada di keranjang
                $this->order->save($data);
            }

            $response = [
                'status' => true,
                'message' => lang('App.itemSuccess'),
                'data' => [],
            ];
            return $this->respond($response, 200);
        }
    }

    public function update($id = NULL)
    {
        $input = $this->getRequestInput();
        $id_barang = $input['id_barang'];
        $qty = $input['qty'];

        // cari data barang
        $barang = $this->barang->where(['id_barang' => $id_barang])->first();

        if ($barang['stok'] >= $qty) {
            $beli = $barang['harga_beli'];
            $jual = $barang['harga_jual'];
            $diskon = $barang['diskon'];
            $diskonPersen = $barang['diskon_persen'];
            $hpp = $beli * $qty;
            $jumlah = ((int)$jual-(int)$diskon) * $qty;
            $data = [
                'qty' => $qty,
                'hpp' => $hpp,
                'jumlah' => $jumlah,
                'total_laba' => $jumlah-$hpp
            ];
            $this->model->update($id, $data);
            /* var_dump($this->model->getLastQuery()->getQuery());
            die; */
            $response = [
                'status' => true,
                'message' => lang('App.updSuccess'),
                'data' => [],
            ];
            return $this->respond($response, 200);
        } else {
            $response = [
                'status' => false,
                'message' => lang('App.stockLess'),
                'data' => [],
            ];
            return $this->respond($response, 200);
        }
    }

    public function update2($id = NULL)
    {
        $input = $this->getRequestInput();
        $id_barang = $input['id_barang'];
        $qty = $input['qty'];

        // cari data barang
        $barang = $this->barang->where(['id_barang' => $id_barang])->first();

        if ($qty <= 0) {
            $response = [
                'status' => false,
                'message' => lang('App.updFailed'),
                'data' => [],
            ];
            return $this->respond($response, 200);
        } else {
            $beli = $barang['harga_beli'];
            $jual = $barang['harga_jual'];
            $jumlah = $beli * $qty;
            $data = [
                'harga_beli' => $beli,
                'harga_jual' => $jual,
                'qty' => $qty,
                'jumlah' => $jumlah,
            ];
            $this->order->update($id, $data);
            /* var_dump($this->model->getLastQuery()->getQuery());
            die; */
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
        $hapus = $this->model->find($id);
        if ($hapus) {
            $this->model->delete($id);
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

    public function delete2($id = null)
    {
        $hapus = $this->order->find($id);
        if ($hapus) {
            $this->order->delete($id);
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

    public function truncate()
    {
        if ($this->model->truncate()) {
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

    public function truncate2()
    {
        if ($this->order->truncate()) {
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
}
