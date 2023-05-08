<?php

namespace App\Modules\Excel\Controllers\Api;

use App\Controllers\BaseControllerApi;
use App\Modules\Barang\Models\BarangModel;
use App\Modules\Log\Models\LogModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Excel extends BaseControllerApi
{
    protected $format       = 'json';
    protected $modelName    = BarangModel::class;
    protected $log;

    public function __construct()
    {
        $this->log = new LogModel();
    }

    public function excelExport()
    {
        $input = $this->request->getVar('data');
        $data = json_decode($input, true);

        $spreadsheet = new Spreadsheet();

        // tulis header/nama kolom 
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'No')
            ->setCellValue('B1', 'ID Barang')
            ->setCellValue('C1', 'Barcode')
            ->setCellValue('D1', 'Nama Barang')
            ->setCellValue('E1', 'Merk')
            ->setCellValue('F1', 'Harga Beli')
            ->setCellValue('G1', 'Harga Jual')
            ->setCellValue('H1', 'Satuan')
            ->setCellValue('I1', 'Deskripsi')
            ->setCellValue('J1', 'Stok')
            ->setCellValue('K1', 'Stok Min')
            ->setCellValue('L1', 'Aktif')
            ->setCellValue('M1', 'Vendor/Supplier')
            ->setCellValue('N1', 'Expired')
            ->setCellValue('O1', 'SKU')
            ->setCellValue('P1', 'Tgl Input')
            ->setCellValue('Q1', 'Tgl Update');
        $column = 2;
        // tulis data ke cell
        $no = 1;
        foreach ($data as $data) {
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $column, $no++)
                ->setCellValue('B' . $column, $data['id_barang'])
                ->setCellValue('C' . $column, $data['barcode'])
                ->setCellValue('D' . $column, $data['nama_barang'])
                ->setCellValue('E' . $column, $data['merk'])
                ->setCellValue('F' . $column, $data['harga_beli'])
                ->setCellValue('G' . $column, $data['harga_jual'])
                ->setCellValue('H' . $column, $data['satuan_barang'])
                ->setCellValue('I' . $column, $data['deskripsi'])
                ->setCellValue('J' . $column, $data['stok'])
                ->setCellValue('K' . $column, $data['stok_min'])
                ->setCellValue('L' . $column, $data['active'])
                ->setCellValue('M' . $column, $data['vendor_supplier'] . ' ' . $data['perusahaan'])
                ->setCellValue('N' . $column, $data['expired'])
                ->setCellValue('O' . $column, $data['sku'])
                ->setCellValue('P' . $column, $data['created_at'])
                ->setCellValue('Q' . $column, $data['updated_at']);
            $column++;
        }
        // tulis dalam format .xlsx
        $writer = new Xlsx($spreadsheet);
        $fileName = 'ExportData-' . getdate()[0] . '.xlsx';
        $writer->save('files/export/' . $fileName);
        $fileXlsx = base_url('files/export/' . $fileName);

        //Save Log
        $this->log->save(['keterangan' => session('nama') . '(' . session('email') . ') ' . strtolower(lang('App.do')) . ' Export Barang Excel']);

        $response = [
            'status' => true,
            'message' => lang('App.getSuccess'),
            'data' => ['filename' => $fileName, 'url' => $fileXlsx],
        ];
        return $this->respond($response, 200);
    }

    
}
