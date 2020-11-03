<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class IndexImport implements WithMultipleSheets, WithCalculatedFormulas
{
    /**
    * @param Collection $row
    *
    * @return void
    */
    public function sheets(): array
    {
        return [
            0 => '',
            1 => '',
            2 => new InOutProductsImport(),
            3 => new OrdersImport(),
            4 => '',
            5 => '',
            6 => '',
            7 => new ProductsImport(),
        ];
    }
}
