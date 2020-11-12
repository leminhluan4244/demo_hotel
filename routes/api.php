<?php

use App\Http\Controllers\AuthController;
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
Route::post('login', [AuthController::class, 'login']);
Route::post('signup', [AuthController::class, 'signup']);

Route::middleware('auth:api')->group(function () {

    // User action
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'user']);

    // Import Data
    Route::post('import', [ImportController::class, 'index']);
    Route::post('upload', [ImportController::class, 'upload']);
    // Statistics and chart
    // Route::get('export/room', [ExportController::class, 'roomExport']);
    // Route::get('export/product', [ExportController::class, 'productExport']);
    // Route::get('export/all', [ExportController::class, 'allExport']);

    Route::get('room/list', [ExportController::class, 'mobileRoomList']);
    Route::get('product/list', [ExportController::class, 'mobileProductList']);
    Route::get('date/room', [ExportController::class, 'mobileDateRoom']);
    Route::get('date/full', [ExportController::class, 'mobileDateFull']);
    //Table list pagination
    Route::get('room/export', [ExportController::class, 'mobileRoomExport']);
    Route::get('product/export', [ExportController::class, 'mobileProductExport']);
});
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
