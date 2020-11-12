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
use App\Http\Controllers\Base\Response;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ImportController extends Controller
{
    protected $ruleData = array();

    protected $spreadsheet = null;

    public function __construct()
    {
        $this->ruleData['upload'] = [
            'import_file' => 'required|max:10000|mimes:xlsx'
        ];
    }

    public function upload(Request $request)
    {
        // setting config
        $this->config([
            'rule' => $this->ruleData['upload'],
            'request' => $request,
        ]);
        // Run check validate if false
        $this->exam();
        if ($this->status == self::VALIDATE) {
            return Response::errors(
                $this->errors->all()
            );
        }

        $file = $request->file('import_file');
        $destinationPath = 'uploads';
        $file->move($destinationPath,'import.xlsx');
        return Response::response();
    }

    public function index(){
        try{
            $inputFileName = public_path().'\uploads\import.xlsx';
            $this->spreadsheet = IOFactory::load($inputFileName);
            $this->makeProduct();
            $this->makeRoom();
            $this->makeBill();
            $this->inOutProductHistory();
            return Response::response();
        } catch (Exception $e) {
            $message = $e->getMessage();
            return Response::errors(array('message' => $message));
        }

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
                $inDate = Date::excelToDateTimeObject($row[1]);
                $outDate = null == $row[7] ? null : Date::excelToDateTimeObject($row[7]);
                $inHour = $row[3];
                $outHour = $row[5];
                $inMinute = null == $row[4] ? "00" : $row[4];
                $outMinute = null == $row[6] ? "00" : $row[6];
                $billStartTime = Carbon::parse($inDate)->format('Y-m-d')." {$inHour}:{$inMinute}:00";

                if(null != $outDate){
                    if(null != $outHour){
                        $billEndTime = Carbon::parse($outDate)->format('Y-m-d')." {$outHour}:{$outMinute}:00";
                    } else {
                        $billEndTime = Carbon::parse($outDate)->format('Y-m-d')." 00:00}:00";
                    }
                } else {
                    if(null != $outHour){
                        $billEndTime = Carbon::parse($inDate)->format('Y-m-d')." {$outHour}:{$outMinute}:00";
                    } else {
                        $billEndTime = null;
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
                    'bill_laundry_amount' => null == $row[16] ? 0 : $row[16] ,
                    'bill_laundry_costs' => null == $row[18] ? 0 : $row[18] ,
                    'bill_total_service_cost' => $row[13],
                    'bill_total_cost' => $row[13],
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
                    $inOutDate = Carbon::parse(Date::excelToDateTimeObject($row[0]))->format('Y-m-d');
                }
                $productCode = $row[1];
                $productInAmount = $row[3];
                $productOutAmount = $row[5];
                if(null != $row[3]){
                    $insertProductEnterParams[] = array(
                        'product_code' => $productCode,
                        'enter_amount' => $productInAmount,
                        'enter_cost' => $row[7],
                        'enter_date' => $inOutDate,
                    );
                    if(!isset($updateChangeAmount[$productCode])){
                        $updateChangeAmount[$productCode] = 0;
                    }
                    $updateChangeAmount[$productCode] += $productInAmount;
                }
                if(null != $productOutAmount){
                    $insertProductSaleParams[] = array(
                        'bill_code' => $row[4],
                        'product_code' => $productCode,
                        'sales_amount' => $productOutAmount,
                        'sales_cost' => $row[7],
                        'sales_date' => $inOutDate,
                    );
                    if(!isset($updateChangeAmount[$productCode])){
                        $updateChangeAmount[$productCode] = 0;
                    }
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
            if(!empty($insertProductSaleParams)){
                foreach ($insertProductSaleParams as $key => $value){
                    Bill::where('bill_code', '=', $value['bill_code'])->update(['bill_total_cost' => DB::raw("bill_total_cost + {$value['sales_cost']}")]);
                }
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}
