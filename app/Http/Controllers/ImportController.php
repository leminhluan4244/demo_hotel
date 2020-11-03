<?php

namespace App\Http\Controllers;

use App\Imports\IndexImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function index(){

        Excel::import(new IndexImport, 'import.xlsx');
        dd('import success');
        // return redirect('/')->with('success', 'All good!');
    }
}
