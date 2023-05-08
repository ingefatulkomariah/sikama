<?php

namespace App\Modules\Satuan\Controllers\Api;
/*
PT. GLOBAL ITSHOP PURWOKERTO
Toko Online: ITShop Purwokerto (Tokopedia, Shopee, Bukalapak, Blibli)
Dibuat oleh: Hari Wicaksono, S.Kom
02-2023
*/

use App\Controllers\BaseControllerApi;
use App\Modules\Satuan\Models\SatuanModel;
use App\Modules\Log\Models\LogModel;

class Satuan extends BaseControllerApi
{
    protected $format       = 'json';
    protected $modelName    = SatuanModel::class;
    protected $log;

    public function __construct()
    {
        $this->log = new LogModel();
    }

    public function index()
    {
        $data = $this->model->findAll();
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
        return $this->respond(['status' => true, 'message' => lang('App.getSuccess'), 'data' => $this->model->find($id)], 200);
    }

    public function create()
    {
        $rules = [
            'nama_satuan' => [
                'rules'  => 'required',
                'errors' => []
            ],
        ];

        if ($this->request->getJSON()) {
            $json = $this->request->getJSON();
            $namaSatuan = $json->nama_satuan;
            $data = [
                'nama_satuan' => $namaSatuan,
            ];
        } else {
            $namaSatuan = $this->request->getPost('nama_satuan');
            $data = [
                'nama_satuan' => $namaSatuan,
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
            $this->model->save($data);

            //Save Log
            $this->log->save(['keterangan' => session('nama') . '(' . session('email') . ') ' . strtolower(lang('App.do')) . ' Save Satuan: ' . $namaSatuan]);

            $response = [
                'status' => true,
                'message' => lang('App.saveSuccess'),
                'data' => [],
            ];
            return $this->respond($response, 200);
        }
    }

    public function update($id = NULL)
    {
        $rules = [
            'nama_satuan' => [
                'rules'  => 'required',
                'errors' => []
            ],
        ];

        if ($this->request->getJSON()) {
            $json = $this->request->getJSON();
            $data = [
                'nama_satuan' => $json->nama_satuan,
            ];
        } else {
            $data = $this->request->getRawInput();
        }

        if (!$this->validate($rules)) {
            $response = [
                'status' => false,
                'message' => lang('App.reqFailed'),
                'data' => $this->validator->getErrors(),
            ];
            return $this->respond($response, 200);
        } else {
            $this->model->update($id, $data);

            //Save Log
            $this->log->save(['keterangan' => session('nama') . '(' . session('email') . ') ' . strtolower(lang('App.do')) . ' Update Satuan: ' . $id]);

            $response = [
                'status' => true,
                'message' => lang('App.ItemUpdated'),
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

            //Save Log
            $this->log->save(['keterangan' => session('nama') . '(' . session('email') . ') ' . strtolower(lang('App.do')) . ' Delete Satuan: ' . $id]);

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

    public function updateSatuan($id = NULL)
    {
        if ($this->request->getJSON()) {
            $json = $this->request->getJSON();
            $data = [
                'id_satuan' => $json->id_satuan,
            ];
        } else {
            $input = $this->request->getRawInput();
            $data = [
                'id_satuan' => $input['id_satuan'],
            ];
        }

        if ($data > 0) {
            $this->model->update($id, $data);

            //Save Log
            $this->log->save(['keterangan' => session('nama') . '(' . session('email') . ') ' . strtolower(lang('App.do')) . ' Update Satuan: ' . $id]);
            
            $response = [
                'status' => true,
                'message' => lang('App.ItemUpdated'),
                'data' => []
            ];
            return $this->respond($response, 200);
        } else {
            $response = [
                'status' => false,
                'message' => lang('App.updFailed'),
                'data' => []
            ];
            return $this->respond($response, 200);
        }
    }
}
