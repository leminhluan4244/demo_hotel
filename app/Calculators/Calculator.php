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
    public static function makeOnlyRoom($startDate, $endDate, $roomCode ,$perPage){
        $responseData = array(
            'sum_cost' => 0,
            'sum_sales' => 0,
            'sum_profit' => 0,
            'detail' => array(),
            'pagination' => array(
                'total' => 1,
                'lastPage' => 1,
                'perPage' => $perPage,
                'currentPage' => 1,
            )
        );
        $roomCostAndSales = Bill::where('room_code', $roomCode);
        if(null != $startDate){
            $roomCostAndSales = $roomCostAndSales->where(DB::raw('DATE(bill_end_time)'), '>=', $startDate);
        }
        if(null != $endDate){
            $roomCostAndSales = $roomCostAndSales->where(DB::raw('DATE(bill_end_time)'), '<=', $endDate);
        }
        $roomCostAndSales = $roomCostAndSales->select(
            DB::raw('SUM(bill_total_service_cost) as total_service_cost'),
            DB::raw('SUM(bill_laundry_costs) as total_laundry_costs'),
            'room_code'
        )
        ->groupBy('room_code')
        ->first();
        $total_laundry_costs = isset($roomCostAndSales->total_laundry_costs) ? (int)$roomCostAndSales->total_laundry_costs : 0;
        $total_service_cost = isset($roomCostAndSales->total_service_cost) ? (int)$roomCostAndSales->total_service_cost : 0;
        $responseData['detail'][] = array(
            'room_code' => $roomCode,
            'cost' => $total_laundry_costs,
            'sales' => $total_service_cost,
            'profit' => $total_service_cost - $total_laundry_costs,
        );
        $responseData['sum_cost'] = $total_laundry_costs;
        $responseData['sum_sales'] = $total_service_cost;
        $responseData['sum_profit'] = $total_service_cost - $total_laundry_costs;
        return $responseData;
    }

    public static function makeMultiRoom($startDate, $endDate, $perPage){
        $sumSales = 0;
        $sumCost = 0;
        $sumProfit = 0;
        $roomObject = Room::paginate($perPage);
        $responseData = array(
            'sum_cost' => 0,
            'sum_sales' => 0,
            'sum_profit' => 0,
            'detail' => array(),
            'pagination' => array(
                'total' => $roomObject->total(),
                'lastPage' => $roomObject->lastPage(),
                'perPage' => $roomObject->perPage(),
                'currentPage' => $roomObject->currentPage(),
            )
        );
        $roomList = $roomObject->items();
        $whereInArr = array();
        foreach($roomList as $value){
            $whereInArr[] = $value->room_code;
        }
        $roomCostAndSales = Bill::whereIn('room_code', $whereInArr);
        if(null != $startDate){
            $roomCostAndSales = $roomCostAndSales->where(DB::raw('DATE(bill_end_time)'), '>=', $startDate);
        }
        if(null != $endDate){
            $roomCostAndSales = $roomCostAndSales->where(DB::raw('DATE(bill_end_time)'), '<=', $endDate);
        }
        $roomCostAndSales = $roomCostAndSales->select(
            DB::raw('SUM(bill_total_service_cost) as total_service_cost'),
            DB::raw('SUM(bill_laundry_costs) as total_laundry_costs'),
            'room_code'
        )
        ->groupBy('room_code')
        ->get();
        $roomDataExport = array();
        foreach($roomList as $value){
            $roomDataExport[$value->room_code]['cost'] = 0;
            $roomDataExport[$value->room_code]['profit'] = 0;
        }
        foreach($roomCostAndSales as $value){
            $roomDataExport[$value->room_code]['room_code'] = $value->room_code;
            $roomDataExport[$value->room_code]['cost'] = (int)$value->total_laundry_costs;
            $roomDataExport[$value->room_code]['sales'] = (int)$value->total_service_cost;
            $roomDataExport[$value->room_code]['profit'] = $roomDataExport[$value->room_code]['sales'] - $roomDataExport[$value->room_code]['cost'];
            $sumCost += $roomDataExport[$value->room_code]['cost'];
            $sumSales += $roomDataExport[$value->room_code]['sales'];
            $sumProfit += $roomDataExport[$value->room_code]['profit'];
        }
        foreach($roomDataExport as $value){
            $responseData['detail'][] = $value;
        }
        $responseData['sum_cost'] = $sumCost;
        $responseData['sum_sales'] = $sumSales;
        $responseData['sum_profit'] = $sumProfit;
        return $responseData;
    }

    public static function makeOnlyProduct($startDate, $endDate, $productCode ,$perPage){
        $productObject = Product::where('product_code', $productCode)->first();
        $responseData = array(
            'sum_cost' => 0,
            'sum_sales' => 0,
            'sum_profit' => 0,
            'detail' => array(),
            'pagination' => array(
                'total' => 1,
                'lastPage' => 1,
                'perPage' => $perPage,
                'currentPage' => 1,
            )
        );
        $productSales = ProductSale::where('product_code', $productCode);
        $productCost = ProductEnter::where('product_code', $productCode);
        if(null != $startDate){
            $productSales = $productSales->where('sales_date', '>=', $startDate);
            $productCost = $productCost->where('enter_date', '>=', $startDate);
        }
        if(null != $endDate){
            $productSales = $productSales->where('sales_date', '<=', $endDate);
            $productCost = $productCost->where('enter_date', '<=', $endDate);
        }
        $productCost = $productCost->groupBy('product_code')->select(
            DB::raw('SUM(enter_amount) as total_enter_amount'),
            'product_code'
        )->first();
        $productSales = $productSales->groupBy('product_code')->select(
            DB::raw('SUM(sales_amount) as total_sales_amount'),
            'product_code'
        )->first();
        $total_enter_amount = isset($productCost->total_enter_amount) ? (int)$productCost->total_enter_amount : 0;
        $total_sales_amount = isset($productSales->total_sales_amount) ? (int)$productSales->total_sales_amount : 0;
        $responseData['detail'][] = array(
            'product_code' => $productObject->product_code,
            'product_name' => $productObject->product_name,
            'product_unit' => $productObject->product_unit,
            'product_first_amount' => $productObject->product_first_amount,
            'product_amount' => $productObject->product_amount,
            'product_input_price' => $productObject->product_input_price,
            'product_sale_price' => $productObject->product_sale_price,
            'product_input' => $total_enter_amount,
            'product_out' => $total_sales_amount,
            'product_cost' => $total_enter_amount * $productObject->product_input_price,
            'product_sales' => $total_sales_amount * $productObject->product_sale_price,
            'product_profit' => 0,
        );
        $responseData['sum_cost'] = $total_enter_amount * $productObject->product_input_price;
        $responseData['sum_sales'] = $total_sales_amount * $productObject->product_sale_price;
        $responseData['sum_profit'] = ($productObject->product_sale_price -  $productObject->product_input_price) * $total_sales_amount;

        return $responseData;
    }

    public static function makeMultiProduct($startDate, $endDate, $perPage){
        $sumSales = 0;
        $sumCost = 0;
        $sumProfit = 0;
        $productObject = Product::paginate($perPage);
        $responseData = array(
            'sum_cost' => 0,
            'sum_sales' => 0,
            'sum_profit' => 0,
            'detail' => array(),
            'pagination' => array(
                'total' => $productObject->total(),
                'lastPage' => $productObject->lastPage(),
                'perPage' => $productObject->perPage(),
                'currentPage' => $productObject->currentPage(),
            )
        );
        $productList = $productObject->items();
        $whereInArr = array();
        foreach($productList as $value){
            $whereInArr[] = $value->product_code;
        }
        $productSales = ProductSale::whereIn('product_code', $whereInArr);
        $productCost = ProductEnter::whereIn('product_code', $whereInArr);
        if(null != $startDate){
            $productSales = $productSales->where('sales_date', '>=', $startDate);
            $productCost = $productCost->where('enter_date', '>=', $startDate);
        }
        if(null != $endDate){
            $productSales = $productSales->where('sales_date', '<=', $endDate);
            $productCost = $productCost->where('enter_date', '<=', $endDate);
        }
        $productCost = $productCost->groupBy('product_code')->select(
            DB::raw('SUM(enter_amount) as total_enter_amount'),
            'product_code'
        )->get();
        $productSales = $productSales->groupBy('product_code')->select(
            DB::raw('SUM(sales_amount) as total_sales_amount'),
            'product_code'
        )->get();
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
        // Cost
        foreach($productCost as $value){
            $productDataExport[$value->product_code]['product_input'] = (int)$value->total_enter_amount;
            $productDataExport[$value->product_code]['product_cost'] = (int)$value->total_enter_amount * $productDataExport[$value->product_code]['product_input_price'];
            $sumCost += $productDataExport[$value->product_code]['product_cost'];
        }
        // Sales
        foreach($productSales as $value){
            $productDataExport[$value->product_code]['product_out'] = (int)$value->total_sales_amount;
            $productDataExport[$value->product_code]['product_sales'] = (int)$value->total_sales_amount * $productDataExport[$value->product_code]['product_sale_price'];
            $productDataExport[$value->product_code]['product_profit'] = ($productDataExport[$value->product_code]['product_sale_price'] - $productDataExport[$value->product_code]['product_input_price']) * $productDataExport[$value->product_code]['product_out'];
            $sumSales += $productDataExport[$value->product_code]['product_sales'];
            $sumProfit += $productDataExport[$value->product_code]['product_profit'];
        }
        foreach($productDataExport as $value){
            $responseData['detail'][] = $value;
        }
        $responseData['sum_cost'] = $sumCost;
        $responseData['sum_sales'] = $sumSales;
        $responseData['sum_profit'] = $sumProfit;
        return $responseData;
    }

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
                    'color' => $colorArr[$id-1],
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
                    'color' => $colorArr[$id-1],
                );
                $id++;
            } else {
                break;
            }
        }
        return $resultData;
    }
    public static function calculateMobileRoomExport($startDate, $endDate, $roomCode){
        $roomDataExport = array();
        $sumCost = 0;
        $sumSales = 0;
        $sumProfit = 0;
        $roomCostAndSales = Bill::select(
            DB::raw('SUM(bill_total_service_cost) as total_service_cost'),
            DB::raw('SUM(bill_laundry_costs) as total_laundry_costs'),
            'room_code'
        );
        if(null != $startDate){
            $roomCostAndSales = $roomCostAndSales->where(DB::raw('DATE(bill_end_time)'), '>=', $startDate);
        }
        if(null != $endDate){
            $roomCostAndSales = $roomCostAndSales->where(DB::raw('DATE(bill_end_time)'), '<=', $endDate);
        }
        if(null != $roomCode){
            $roomCostAndSales = $roomCostAndSales
            ->where('room_code', '=', $roomCode)
            ->groupBy('room_code')
            ->get();
            if(isset($roomCostAndSales[0]->room_code ))
            {
                $roomDataExport[$roomCostAndSales[0]->room_code]['cost'] = 0;
                $roomDataExport[$roomCostAndSales[0]->room_code]['profit'] = 0;
                $roomDataExport[$roomCostAndSales[0]->room_code]['cost'] = (int)$roomCostAndSales[0]->total_laundry_costs;
                $roomDataExport[$roomCostAndSales[0]->room_code]['sales'] = (int)$roomCostAndSales[0]->total_service_cost;
                $roomDataExport[$roomCostAndSales[0]->room_code]['profit'] = $roomDataExport[$roomCostAndSales[0]->room_code]['sales'] - $roomDataExport[$roomCostAndSales[0]->room_code]['cost'];
                $sumCost += $roomDataExport[$roomCostAndSales[0]->room_code]['cost'];
                $sumSales += $roomDataExport[$roomCostAndSales[0]->room_code]['sales'];
                $sumProfit += $roomDataExport[$roomCostAndSales[0]->room_code]['profit'];
            } else {
                $roomDataExport[$roomCode]['cost'] = 0;
                $roomDataExport[$roomCode]['profit'] = 0;
                $roomDataExport[$roomCode]['sales'] = 0;
            }
        } else {
            $roomCostAndSales = $roomCostAndSales->groupBy('room_code')->get();
            $roomList = Room::all();
            foreach($roomList as $value){
                $roomDataExport[$value->room_code]['cost'] = 0;
                $roomDataExport[$value->room_code]['profit'] = 0;
            }
            foreach($roomCostAndSales as $value){
                $roomDataExport[$value->room_code]['cost'] = (int)$value->total_laundry_costs;
                $roomDataExport[$value->room_code]['sales'] = (int)$value->total_service_cost;
                $roomDataExport[$value->room_code]['profit'] = $roomDataExport[$value->room_code]['sales'] - $roomDataExport[$value->room_code]['cost'];
                $sumCost += $roomDataExport[$value->room_code]['cost'];
                $sumSales += $roomDataExport[$value->room_code]['sales'];
                $sumProfit += $roomDataExport[$value->room_code]['profit'];
            }
        }
        return array(
            'sum_cost' => $sumCost,
            'sum_sales' => $sumSales,
            'sum_profit' => $sumProfit,
            'detail' => $roomDataExport,
        );
    }
}
