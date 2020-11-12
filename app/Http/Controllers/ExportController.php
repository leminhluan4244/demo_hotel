<?php

namespace App\Http\Controllers;

use App\Calculators\Calculator;
use App\Http\Controllers\Base\Response;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Product;

class ExportController extends Controller
{
    protected $ruleData = array();

    public function __construct()
    {
        $this->ruleData = array(
            'date_room' => array(
                'room' => 'required|max:255|string|exists:rooms,room_code',
                'date' => 'required|date|date_format:Y-m-d',
            ),
            'date_full' => array(
                'date' => 'required|date|date_format:Y-m-d',
            ),
            'room_export' => array(
                'start_date' => 'date|date_format:Y-m-d|before_or_equal:end_date',
                'end_date' => 'date|date_format:Y-m-d',
                'room_code' => 'string|max:20|exists:bills,room_code',
            ),
            'product_export' => array(
                'start_date' => 'date|date_format:Y-m-d|before_or_equal:end_date',
                'end_date' => 'date|date_format:Y-m-d',
                'product_code' => 'string|max:20|exists:products,product_code',
                'page' => 'required|numeric',
                'per_page' => 'required|numeric',
            ),
        )
       ;
    }

    public function roomExport(){
        $roomData = Calculator::calculateRoomExport();
        return Response::response($roomData);
    }

    public function productExport(){
        $productData = Calculator::calculateProductExport();
        return Response::response($productData);
    }

    public function allExport(){
        $roomData = Calculator::calculateRoomExport();
        $productData = Calculator::calculateProductExport();
        $allData = array(
            'sumCost' => $roomData['sumCost'] + $productData['sumCost'],
            'sumSales' => $roomData['sumSales'] + $productData['sumSales'],
            'sumProfit' => ($roomData['sumSales'] + $productData['sumSales']) - ($roomData['sumCost'] + $productData['sumCost']),
            'productDetail' => $productData,
            'roomDetail' => $roomData,
        );
        return Response::response($allData);
    }

    public function mobileRoomList(){
        $roomData = Room::select('id', 'room_code')->get();
        return Response::response($roomData);
    }

    public function mobileProductList(){
        $roomData = Product::get();
        return Response::response($roomData);
    }

    public function mobileDateRoom(Request $request){
        $this->config([
            'rule' => $this->ruleData['date_room'],
            'request' => $request,
        ]);
        // Run check validate if false
        $this->exam();
        if ($this->status == self::VALIDATE) {
            return Response::errors(
                $this->errors->all()
            );
        } else {
            $roomData = Calculator::calculateMobileDateRoom($request->date, $request->room);
            return Response::response($roomData);
        }
    }

    public function mobileDateFull(Request $request){
        $this->config([
            'rule' => $this->ruleData['date_full'],
            'request' => $request,
        ]);
        // Run check validate if false
        $this->exam();
        if ($this->status == self::VALIDATE) {
            return Response::errors(
                $this->errors->all()
            );
        } else {
            $roomData = Calculator::calculateMobileDateFull($request->date);
            return Response::response($roomData);
        }
    }

    public function mobileRoomExport(Request $request){
        $this->config([
            'rule' => $this->ruleData['room_export'],
            'request' => $request,
        ]);
        // Run check validate if false
        $this->exam();
        if ($this->status == self::VALIDATE) {
            return Response::errors(
                $this->errors->all()
            );
        } else {
            if(!isset($request->room_code) || null == $request->room_code){
                $roomData = Calculator::makeMultiRoom($request->start_date, $request->end_date, $request->per_page);
            } else {
                $roomData = Calculator::makeOnlyRoom($request->start_date, $request->end_date, $request->room_code, $request->per_page);
            }
            return Response::response($roomData);
        }
    }

    public function mobileProductExport(Request $request){
        $this->config([
            'rule' => $this->ruleData['product_export'],
            'request' => $request,
        ]);
        // Run check validate if false
        $this->exam();
        if ($this->status == self::VALIDATE) {
            return Response::errors(
                $this->errors->all()
            );
        } else {
            if(!isset($request->product_code) || null == $request->product_code){
                $roomData = Calculator::makeMultiProduct($request->start_date, $request->end_date, $request->per_page);
            } else {
                $roomData = Calculator::makeOnlyProduct($request->start_date, $request->end_date, $request->product_code, $request->per_page);
            }
            return Response::response($roomData);
        }
    }
}
