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
    // Import Data
    Route::post('import', [ImportController::class, 'index']);
    Route::post('upload', [ImportController::class, 'upload']);
    // Statistics and chart
    // Route::get('export/room', [ExportController::class, 'roomExport']);
    // Route::get('export/product', [ExportController::class, 'productExport']);
    // Route::get('export/all', [ExportController::class, 'allExport']);

    Route::get('mobile/room', [ExportController::class, 'mobileRoomList']);
    Route::get('mobile/product', [ExportController::class, 'mobileProductList']);
    Route::get('mobile/date/room', [ExportController::class, 'mobileDateRoom']);
    Route::get('mobile/date/full', [ExportController::class, 'mobileDateFull']);
    //Table list pagination
    Route::get('mobile/room', [ExportController::class, 'mobileRoomExport']);
    Route::get('mobile/product', [ExportController::class, 'mobileProductExport']);
});
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
