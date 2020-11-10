<?php

use App\Http\Controllers\ExportController;
use App\Http\Controllers\ImportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('import', [ImportController::class, 'index']);
Route::post('upload', [ImportController::class, 'upload']);
Route::get('export/room', [ExportController::class, 'roomExport']);
Route::get('export/product', [ExportController::class, 'productExport']);
Route::get('export/all', [ExportController::class, 'allExport']);
Route::get('export/date/room', [ExportController::class, 'exportDateAndRoom']);
Route::get('export/room/date', [ExportController::class, 'roomExportDate']);
Route::get('export/room/date/all', [ExportController::class, 'roomExportDates']);
