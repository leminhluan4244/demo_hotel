<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Product;
use App\Models\ProductEnter;
use App\Models\ProductSale;
use App\Models\Room;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportController extends Controller
{
    protected $spreadsheet = null;
    public function index(){
        $inputFileName = public_path().'\import.xlsx';
        $this->spreadsheet = IOFactory::load($inputFileName);
        $this->makeProduct();
        $this->makeRoom();
        $this->makeBill();
        // $this->inOutProductHistory();
    }

    private function makeProduct(){
        $sheet = $this->spreadsheet->getSheetByName('DMHH');
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
                    'product_first_amount' => $row[3],
                    'product_amount' => $row[3],
                    'product_input_price' => $row[4],
                    'product_sale_price' => $row[5],
                );
            }
        }
        DB::beginTransaction();
        try {
            Product::truncate();
            Product::insert($insertParams);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    private function makeRoom(){
        $sheet = $this->spreadsheet->getSheetByName('Check Phong');
        $insertParams = array();
        $rowData = $sheet->rangeToArray('J6:J1000', null, true, false);
        foreach($rowData as $row){
            if(null == $row[0]){
                break;
            } else {
                $insertParams[] = array(
                    'room_code' => $row[0]
                );
            }
        }
        DB::beginTransaction();
        try {
            Room::truncate();
            Room::insert($insertParams);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    private function makeBill(){
        $sheet = $this->spreadsheet->getSheetByName('Ghi So');
        $insertParams = array();
        $rowData = $sheet->rangeToArray('B14:T1000', null, true, false);
        foreach($rowData as $row){
            if(null == $row[0]){
                break;
            } else {
                if(null != $row[8]){
                    $billType = 1;
                } else if(null != $row[9]){
                    $billType = 2;
                } else {
                    $billType = 3;
                }

                $inDate = $row[1];
                $outDate = $row[7];
                $inHourse = $row[3];
                $outHourse = $row[5];
                $inMinute = $row[4];
                $outMinute = $row[6];
                $billStartTime = Carbon::parse("{$inDate} {$inHourse}:{$inMinute}:00");
                if(null == $outDate && null == $outHourse){
                    $billEndTime = null;
                } else if(null == $outDate && null != $outHourse) {
                    if(null == $outMinute){
                        $billEndTime = Carbon::parse("{$inDate} {$outHourse}:00:00");
                    } else {
                        $billEndTime = Carbon::parse("{$inDate} {$outHourse}:{$outMinute}:00");
                    }
                }

                $insertParams[] = array(
                    'bill_code' => $row[0],
                    'room_code' => $row[2],
                    'bill_type' => $billType,
                    'bill_start_time' => $billStartTime,
                    'bill_end_time' => $billEndTime,
                    'bill_total_time' => $row[12],
                    'bill_room_costs' => $row[13],
                    'bill_deposit_costs' => $row[14],
                    'bill_laundry_amount' => $row[16],
                    'bill_laundry_total' => $row[18],
                    'bill_total_service_cost' => $row[15] + $row[18],
                    'bill_total_cost' => 0,
                );
            }
        }
        DB::beginTransaction();
        try {
            Bill::truncate();
            Bill::insert($insertParams);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    private function inOutProductHistory(){
        $sheet = $this->spreadsheet->getSheetByName('NHAP XUAT');
        $insertProductEnterParams = array();
        $insertProductSaleParams = array();
        $updateChangeAmount = array();
        $rowData = $sheet->rangeToArray('C10:J1000', null, true, false);
        $inOutDate = '';
        foreach($rowData as $row){
            if(null == $row[1]){
                break;
            } else {
                if(null != $row[0]){
                    $inOutDate = $row[0];
                }
                $productCode = $row[1];
                $productInAmount = $row[3];
                $productOutAmount = $row[5];
                if(null != $row[3]){
                    $insertProductEnterParams[] = array(
                        'product_code' => $productCode,
                        'enter_amount' => $productInAmount,
                        'enter_date' => $inOutDate,
                    );
                    $updateChangeAmount[$productCode] += $productInAmount;
                }
                if(null != $productOutAmount){
                    $insertProductSaleParams[] = array(
                        'bill_code' => $row[4],
                        'product_code' => $productCode,
                        'sales_amount' => $productOutAmount,
                        'sales_date' => $inOutDate,
                    );
                    $updateChangeAmount[$productCode] -= $productInAmount;
                }
            }
        }
        DB::beginTransaction();
        try {
            ProductEnter::truncate();
            ProductEnter::insert($insertProductEnterParams);
            ProductSale::truncate();
            ProductSale::insert($insertProductSaleParams);
            if(!empty($updateChangeAmount)){
                foreach ($updateChangeAmount as $key => $value){
                    Product::where('product_code', '=', $key)->update(['product_amount' => DB::raw("product_amount + ({$value})")]);
                }
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}
