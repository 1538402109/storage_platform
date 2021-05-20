<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\MenuController;
use App\Http\Controllers\API\PortalController;
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

Route::post('mainMenu/mainMenuItems',[MenuController::class,'mainMenuItems'])->name('mainMenu/mainMenuItems');
Route::post('mainMenu/recentFid',[MenuController::class,'recentFid'])->name('mainMenu/recentFid');
Route::post('portal/inventoryPortal',[PortalController::class,'getInventoryPortal'])->name('portal/inventoryPortal');
Route::post('portal/salePortal',[PortalController::class,'getSalePortal'])->name('portal/salePortal');
Route::post('portal/purchasePortal',[PortalController::class,'getPurchasePortal'])->name('portal/purchasePortal');
Route::post('portal/moneyPortal',[PortalController::class,'getMoneyPortal'])->name('portal/moneyPortal');


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
