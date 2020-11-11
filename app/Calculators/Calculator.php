<?php

namespace App\Calculators;

use App\Models\Bill;
use App\Models\Product;
use App\Models\ProductEnter;
use App\Models\ProductSale;
use App\Models\Room;
use Illuminate\Support\Facades\DB;

class Calculator
{
    public static function calculateRoomExport(){
        $roomList = Room::all();
        $roomDataExport = array();
        foreach($roomList as $value){
            $roomDataExport[$value->room_code]['cost'] = 0;
            $roomDataExport[$value->room_code]['profit'] = 0;
        }
        $roomCostAndSales = Bill::select(
            DB::raw('SUM(bill_total_service_cost) as total_service_cost'),
            DB::raw('SUM(bill_laundry_costs) as total_laundry_costs'),
            'room_code'
        )
        ->groupBy('room_code')
        ->get();
        $sumCost = 0;
        $sumSales = 0;
        $sumProfit = 0;
        foreach($roomCostAndSales as $value){
            $roomDataExport[$value->room_code]['cost'] = (int)$value->total_laundry_costs;
            $roomDataExport[$value->room_code]['sales'] = (int)$value->total_service_cost;
            $roomDataExport[$value->room_code]['profit'] = $roomDataExport[$value->room_code]['sales'] - $roomDataExport[$value->room_code]['cost'];
            $sumCost += $roomDataExport[$value->room_code]['cost'];
            $sumSales += $roomDataExport[$value->room_code]['sales'];
            $sumProfit += $roomDataExport[$value->room_code]['profit'];
        }

        return array(
            'sumCost' => $sumCost,
            'sumSales' => $sumSales,
            'sumProfit' => $sumProfit,
            'detail' => $roomDataExport,
        );
    }

    public static function calculateProductExport(){
        $productList = Product::all();
        $productDataExport = array();
        foreach($productList as $value){
            $productDataExport[$value->product_code] = array(
                'product_code' => $value->product_code,
                'product_name' => $value->product_name,
                'product_unit' => $value->product_unit,
                'product_first_amount' => $value->product_first_amount,
                'product_amount' => $value->product_amount,
                'product_input_price' => $value->product_input_price,
                'product_sale_price' => $value->product_sale_price,
                'product_input' => 0,
                'product_out' => 0,
                'product_cost' => 0,
                'product_sales' => 0,
                'product_profit' => 0,
            );
        }
        $productSales = ProductSale::select(
            DB::raw('SUM(sales_amount) as total_sales_amount'),
            'product_code'
        )
        ->groupBy('product_code')
        ->get();
        $productCost = ProductEnter::select(
            DB::raw('SUM(enter_amount) as total_enter_amount'),
            'product_code'
        )
        ->groupBy('product_code')
        ->get();
        $sumSales = 0;
        $sumCost = 0;
        $sumProfit = 0;
        foreach($productCost as $value){
            $productDataExport[$value->product_code]['product_input'] = (int)$value->total_enter_amount;
            $productDataExport[$value->product_code]['product_cost'] = (int)$value->total_enter_amount * $productDataExport[$value->product_code]['product_input_price'];
            $sumCost += $productDataExport[$value->product_code]['product_cost'];
        }
        foreach($productSales as $value){
            $productDataExport[$value->product_code]['product_out'] = (int)$value->total_sales_amount;
            $productDataExport[$value->product_code]['product_sales'] = (int)$value->total_sales_amount * $productDataExport[$value->product_code]['product_sale_price'];
            $productDataExport[$value->product_code]['product_profit'] = ($productDataExport[$value->product_code]['product_sale_price'] - $productDataExport[$value->product_code]['product_input_price']) * $productDataExport[$value->product_code]['product_out'];
            $sumSales += $productDataExport[$value->product_code]['product_sales'];
            $sumProfit += $productDataExport[$value->product_code]['product_profit'];
        }

        return array(
            'sumCost' => $sumCost,
            'sumSales' => $sumSales,
            'sumProfit' => $sumProfit,
            'detail' => $productDataExport,
        );
    }


    public static function calculateMobileDateRoom($date, $room){
        $resultData = array(
            'transactions' => 0,
            'voided' => 0,
            'customers' => 0,
            'netsale' => 0,
            'taxes' => 0,
            'discounts' => 0,
            'sales_data' => array(
                'seriesName' => 'Sale Chart',
                'data' => array(),
                'color' => '#e8f0fc'
            ),
            'sales_by_order_type' => array(),
            'top_five_sales' => array()
        );
        // Service sale of date and room
        // Last 7 days
        $chartColumDate = Bill::select(DB::raw('DATE(bill_end_time) as end_time'))
            ->where('room_code', '=', $room)
            ->where(DB::raw('DATE(bill_end_time)'), '<=', $date)
            ->groupBy('end_time')
            ->orderBy('end_time', 'DESC')
            ->limit(7);

        $billColumData = Bill::where('room_code', '=', $room)
            ->whereIn(DB::raw('DATE(bill_end_time)'), $chartColumDate->pluck('end_time'))
            ->select(DB::raw('DATE(bill_end_time) as end_time'),'bill_total_service_cost')
            ->orderBy('end_time', 'DESC')
            ->get();
        $lastSevenDays = array();
        foreach($billColumData as $bill){
            if(!isset($lastSevenDays[$bill->end_time])){
                $lastSevenDays[$bill->end_time] = 0;

            }
            $lastSevenDays[$bill->end_time] = $lastSevenDays[$bill->end_time] + $bill->bill_total_service_cost;
        }
        $resultData['sales_data']['data'][]= array(
            'x' => $date,
            'y' => 0,
        );
        foreach($lastSevenDays as $dateKey => $salesValue){
            $resultData['sales_data']['data'][]= array(
                'x' => $dateKey,
                'y' => $salesValue,
            );
        }

        // Product sales
        $billCircleData = ProductSale::where('bill_code', 'like', "P{$room}%")
            ->where('sales_date', '=', $date)
            ->join('products', 'products.product_code', '=', 'product_sales.product_code')
            ->select(
                DB::raw('SUM(product_sales.sales_amount * product_sales.sales_cost) AS sales_value'),
                'product_sales.product_code',
                'products.product_name',
                'product_sales.sales_amount'
            )
            ->groupBy('product_sales.product_code')
            ->orderBy('sales_value', 'DESC')
            ->get();
        $colorArr = array(
            '#f5222d',
            '#389e0d',
            '#722ed1',
            '#262626',
            '#1890ff',
            '#2f54eb',
        );
        foreach($billCircleData as $indexKey => $productSaleValue){
            $colorKey = $indexKey % (sizeof($colorArr));
            $resultData['sales_by_order_type'][]= array(
                'value' => $productSaleValue->sales_value,
                'label' => $productSaleValue->product_name,
                'color' => $colorArr[$colorKey],
            );
        }
        // Top 5 sales
        $id = 1;
        foreach($billCircleData as $productSaleValue){
            if($id <= 5){
                $resultData['top_five_sales'][]= array(
                    'id' => $id,
                    'product' => $productSaleValue->product_name,
                    'total_sales' => $productSaleValue->sales_amount,
                    'total_revenue' => $productSaleValue->sales_value,
                );
                $id++;
            } else {
                break;
            }
        }
        return $resultData;
    }

    public static function calculateMobileDateFull($date){
        $resultData = array(
            'transactions' => 0,
            'voided' => 0,
            'customers' => 0,
            'netsale' => 0,
            'taxes' => 0,
            'discounts' => 0,
            'sales_data' => array(
                'seriesName' => 'Sale Chart',
                'data' => array(),
                'color' => '#e8f0fc'
            ),
            'sales_by_order_type' => array(),
            'top_five_sales' => array()
        );
        // Service sale of date and room
        // Last 7 days
        $chartColumDate = Bill::select(DB::raw('DATE(bill_end_time) as end_time'))
            ->where(DB::raw('DATE(bill_end_time)'), '<=', $date)
            ->groupBy('end_time')
            ->orderBy('end_time', 'DESC')
            ->limit(7);

        $billColumData = Bill::whereIn(DB::raw('DATE(bill_end_time)'), $chartColumDate->pluck('end_time'))
            ->select(DB::raw('DATE(bill_end_time) as end_time'),'bill_total_service_cost')
            ->orderBy('end_time', 'DESC')
            ->get();
        $lastSevenDays = array();
        foreach($billColumData as $bill){
            if(!isset($lastSevenDays[$bill->end_time])){
                $lastSevenDays[$bill->end_time] = 0;
            }
            $lastSevenDays[$bill->end_time] = $lastSevenDays[$bill->end_time] + $bill->bill_total_service_cost;
        }
        $resultData['sales_data']['data'][]= array(
            'x' => $date,
            'y' => 0,
        );
        foreach($lastSevenDays as $dateKey => $salesValue){
            $resultData['sales_data']['data'][]= array(
                'x' => $dateKey,
                'y' => $salesValue,
            );
        }

        // Product sales
        $billCircleData = ProductSale::where('sales_date', '=', $date)
            ->join('products', 'products.product_code', '=', 'product_sales.product_code')
            ->select(
                DB::raw('SUM(product_sales.sales_amount * product_sales.sales_cost) AS sales_value'),
                'product_sales.product_code',
                'products.product_name',
                'product_sales.sales_amount'
            )
            ->groupBy('product_sales.product_code')
            ->orderBy('sales_value', 'DESC')
            ->get();
        $colorArr = array(
            '#f5222d',
            '#389e0d',
            '#722ed1',
            '#262626',
            '#1890ff',
            '#2f54eb',
        );
        foreach($billCircleData as $indexKey => $productSaleValue){
            $colorKey = $indexKey % (sizeof($colorArr));
            $resultData['sales_by_order_type'][]= array(
                'value' => $productSaleValue->sales_value,
                'label' => $productSaleValue->product_name,
                'color' => $colorArr[$colorKey],
            );
        }
        // Top 5 sales
        $id = 1;
        foreach($billCircleData as $productSaleValue){
            if($id <= 5){
                $resultData['top_five_sales'][]= array(
                    'id' => $id,
                    'product' => $productSaleValue->product_name,
                    'total_sales' => $productSaleValue->sales_amount,
                    'total_revenue' => $productSaleValue->sales_value,
                );
                $id++;
            } else {
                break;
            }
        }
        return $resultData;
    }
}
