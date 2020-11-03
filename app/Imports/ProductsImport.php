<?php
namespace App\Imports;

use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class ProductsImport implements ToCollection
{
    protected $insertParams = array();
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row){
            if(7 <= $index && null != $row[2]){
                $this->insertParams[] = array(
                    'product_code' => $row[2],
                    'product_name' => $row[3],
                    'product_unit' => $row[4],
                    'product_amount' => $row[5],
                    'product_input_price' => $row[6],
                    'product_sale_price' => $row[7],
                );
            }
        }
        return $this->insertParams;
    }

    public function saveData(){
        $insertParams = $this->insertParams;
        dd($insertParams);
        DB::transaction(function () use ($insertParams) {
            Product::truncate();
            Product::insert($insertParams);
        });
    }
}
