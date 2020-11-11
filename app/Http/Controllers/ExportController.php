<?php

namespace App\Http\Controllers;

use App\Calculators\Calculator;
use App\Http\Controllers\Base\Response;
use Illuminate\Http\Request;
use App\Models\Room;

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
}
