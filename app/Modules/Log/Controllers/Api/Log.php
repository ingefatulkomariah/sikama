<?php

namespace App\Modules\Log\Controllers\Api;
/*
PT. GLOBAL ITSHOP PURWOKERTO
Toko Online: ITShop Purwokerto (Tokopedia, Shopee, Bukalapak, Blibli)
Dibuat oleh: Hari Wicaksono, S.Kom
02-2023
*/

use App\Controllers\BaseControllerApi;
use App\Modules\Log\Models\LogModel;
use App\Libraries\Settings;

class Log extends BaseControllerApi
{
    protected $format       = 'json';
    protected $modelName    = LogModel::class;
    protected $setting;

    public function __construct()
	{
		//memanggil Model
		$this->setting = new Settings();
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
        $data = $this->model->find($id);
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
}
