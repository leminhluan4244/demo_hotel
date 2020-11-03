<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportController extends Controller
{
    public function index(){
        $inputFileName = public_path().'\import.xlsx';
        $this->importProduct($inputFileName);
    }

    private function importProduct($inputFileName){
        $spreadsheet = IOFactory::load($inputFileName);
        $sheet = $spreadsheet->getSheetByName('DMHH');
        $insertParams = array();
        $rowData = $sheet->rangeToArray('C8:H1000', null, true, false);
        foreach($rowData as $row){
            if(null == $row[0]){
                break;
            } else {
                $insertParams[] = array(
                    'product_code' => $row[0],
                    'product_name' => $row[1],
                    'product_unit' => $row[2],
                    'product_amount' => $row[3],
                    'product_input_price' => $row[4],
                    'product_sale_price' => $row[5],
                );
            }
        }
        DB::transaction(function () use ($insertParams) {
            Product::truncate();
            Product::insert($insertParams);
        });
    }
}
