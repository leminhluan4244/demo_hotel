<?php

namespace App\Http\Controllers;

use App\Calculators\Calculator;
use App\Http\Controllers\Base\Response;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    protected $ruleData = array();

    public function __construct()
    {
        $this->ruleData = array(
            'room_date' => array(
                'room' => 'required|max:255|string|exists:rooms,room_code',
                'date' => 'required|date|date_format:Y-m-d',
            ),
            'room_date_all' => array(
                'room' => 'required|max:255|string|exists:rooms,room_code'
            ),
            'room_date' => array(
                'room' => 'required|max:255|string|exists:rooms,room_code',
                'date' => 'required|date|date_format:Y-m-d',
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

    public function roomExportDate(Request $request){
        $this->config([
            'rule' => $this->ruleData['room_date'],
            'request' => $request,
        ]);
        // Run check validate if false
        $this->exam();
        if ($this->status == self::VALIDATE) {
            return Response::errors(
                $this->errors->all()
            );
        } else {
            $roomData = Calculator::calculateRoomExportDate($request->room, $request->date);
            return Response::response($roomData);
        }
    }

    public function roomExportDates(Request $request){
        $this->config([
            'rule' => $this->ruleData['room_date_all'],
            'request' => $request,
        ]);
        // Run check validate if false
        $this->exam();
        if ($this->status == self::VALIDATE) {
            return Response::errors(
                $this->errors->all()
            );
        } else {
            $roomData = Calculator::calculateRoomExportDates($request->room);
            return Response::response($roomData);
        }
    }

    public function exportDateAndRoom(Request $request){
        $this->config([
            'rule' => $this->ruleData['room_date_all'],
            'request' => $request,
        ]);
        // Run check validate if false
        $this->exam();
        if ($this->status == self::VALIDATE) {
            return Response::errors(
                $this->errors->all()
            );
        } else {
            $roomData = Calculator::calculateDateAndRoom($request->date, $request->room);
            return Response::response($roomData);
        }
    }
}
