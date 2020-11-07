<?php

namespace App\Http\Controllers;

use App\Calculators\Calculator;
use App\Http\Controllers\Base\Response;

class ExportController extends Controller
{
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
}
