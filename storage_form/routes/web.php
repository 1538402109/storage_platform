<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Home\MainMenuController;
use App\Http\Controllers\Home\UserController;
use App\Http\Controllers\Home\SaleContractController;
use App\Http\Controllers\Home\CustomerController;
use App\Http\Controllers\Home\GoodsController;
use App\Http\Controllers\Home\SaleController;
use App\Http\Controllers\Home\PurchaseController;
use App\Http\Controllers\Home\SupplierController;
use App\Http\Controllers\Home\WarehouseController;
use App\Http\Controllers\Home\FundsController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    return view('auth/login');
});
Route::get('mainMenu/navigateTo/{fid}',[MainMenuController::class,'navigateTo'])->name('mainMenu/navigateTo');
Route::get('user/changePassword',[UserController::class,'changePassword'])->name('user/changePassword');

Route::get('home', [HomeController::class, 'index']);

//Route::middleware(['auth:sanctum', 'verified'])->get('/home', function () {
//    return view('dashboard');
//})->name('dashboard');

Route::get('saleContract/index', [SaleContractController::class, 'index']);
Route::post('saleContract/scbillList', [SaleContractController::class, 'scbillList']);
Route::post('saleContract/scBillInfo', [SaleContractController::class, 'scBillInfo']);
Route::post('saleContract/editSCBill', [SaleContractController::class, 'editSCBill']);
Route::post('saleContract/scBillDetailList', [SaleContractController::class, 'scBillDetailList']);
Route::post('Home/SaleContract/commitSCBill', [SaleContractController::class, 'commitSCBill']);
Route::post('Home/SaleContract/deleteSCBill', [SaleContractController::class, 'deleteSCBill']);
Route::post('Home/SaleContract/cancelConfirmSCBill', [SaleContractController::class, 'cancelConfirmSCBill']);
Route::get('Home/SaleContract/scBillPdf', [SaleContractController::class, 'scBillPdf']);
Route::post('Home/User/getPrintUrl', [UserController::class, 'getPrintUrl']);
Route::post('Home/Customer/queryData', [CustomerController::class, 'queryData']);
Route::post('Home/Customer/categoryList', [CustomerController::class, 'categoryList']);
Route::post('Home/User/orgWithDataOrg', [UserController::class, 'orgWithDataOrg']);
Route::post('Home/Goods/queryDataWithSalePrice', [GoodsController::class, 'queryDataWithSalePrice']);
Route::post('Home/Sale/soBillInfo', [SaleController::class, 'soBillInfo']);
Route::post('Home/Sale/editSOBill', [SaleController::class, 'editSOBill']);
Route::post('Home/Sale/commitSOBill', [SaleController::class, 'commitSOBill']);
Route::post('Home/Sale/cancelConfirmSOBill', [SaleController::class, 'cancelConfirmSOBill']);
Route::post('Home/Sale/wsBillInfo', [SaleController::class, 'wsBillInfo']);
Route::post('Home/Sale/editWSBill', [SaleController::class, 'editWSBill']);
Route::post('Home/Sale/closeSOBill', [SaleController::class, 'closeSOBill']);
Route::post('Home/Sale/cancelClosedSOBill', [SaleController::class, 'cancelClosedSOBill']);
Route::post('Home/Purchase/poBillInfo', [PurchaseController::class, 'poBillInfo']);
Route::post('Home/Purchase/editPOBill', [PurchaseController::class, 'editPOBill']);
Route::post('Home/Supplier/queryData', [SupplierController::class, 'queryData']);
Route::post('Home/Supplier/categoryList', [SupplierController::class, 'categoryList']);
Route::get('Home/Sale/wsIndex', [SaleController::class, 'wsIndex']);
Route::get('Home/Sale/soIndex', [SaleController::class, 'soIndex']);
Route::get('Home/Sale/GetTmsUrl', [SaleController::class, 'GetTmsUrl']);
Route::post('Home/Sale/wsbillList', [SaleController::class, 'wsbillList']);
Route::post('Home/Sale/wsBillDetailList', [SaleController::class, 'wsBillDetailList']);
Route::post('Home/Sale/commitWSBill', [SaleController::class, 'commitWSBill']);
Route::post('Home/Sale/sobillList', [SaleController::class, 'sobillList']);
Route::get('Home/Sale/srIndex', [SaleController::class, 'srIndex']);
Route::post('Home/Sale/srbillList', [SaleController::class, 'srbillList']);
Route::post('Home/Sale/srBillInfo', [SaleController::class, 'srBillInfo']);
Route::post('Home/Sale/soBillDetailList', [SaleController::class, 'soBillDetailList']);
Route::post('Home/Sale/soBillWSBillList', [SaleController::class, 'soBillWSBillList']);
Route::post('Home/Sale/editSRBill', [SaleController::class, 'editSRBill']);
Route::post('Home/Warehouse/queryData', [WarehouseController::class, 'queryData']);

Route::get('Home/Funds/payIndex', [FundsController::class, 'payIndex']);
Route::post('Home/Funds/payCategoryList', [FundsController::class, 'payCategoryList']);
Route::post('Home/Funds/payList', [FundsController::class, 'payList']);
Route::post('Home/Funds/payDetailList', [FundsController::class, 'payDetailList']);
Route::post('Home/Funds/payRecordList', [FundsController::class, 'payRecordList']);
Route::post('Home/Funds/payRecInfo', [FundsController::class, 'payRecInfo']);
Route::post('Home/Funds/addPayment', [FundsController::class, 'addPayment']);
Route::post('Home/Funds/refreshPayInfo', [FundsController::class, 'refreshPayInfo']);
Route::post('Home/Funds/refreshPayDetailInfo', [FundsController::class, 'refreshPayDetailInfo']);
Route::get('Home/Funds/rvIndex', [FundsController::class, 'rvIndex']);
Route::post('Home/Funds/rvCategoryList', [FundsController::class, 'rvCategoryList']);
Route::post('Home/Funds/rvList', [FundsController::class, 'rvList']);
Route::post('Home/Funds/rvDetailList2', [FundsController::class, 'rvDetailList2']);
Route::post('Home/Funds/rvDetailList', [FundsController::class, 'rvDetailList']);
Route::post('Home/Funds/changeReceivable', [FundsController::class, 'changeReceivable']);
Route::post('Home/Funds/rvRecordList', [FundsController::class, 'rvRecordList']);
Route::post('Home/Funds/rvRecInfo', [FundsController::class, 'rvRecInfo']);
Route::post('Home/Funds/addRvRecord', [FundsController::class, 'addRvRecord']);
Route::post('Home/Funds/refreshRvInfo', [FundsController::class, 'refreshRvInfo']);
Route::post('Home/Funds/refreshRvDetailInfo', [FundsController::class, 'refreshRvDetailInfo']);
Route::get('Home/Funds/cashIndex', [FundsController::class, 'cashIndex']);
Route::post('Home/Funds/cashList', [FundsController::class, 'cashList']);
Route::post('Home/Funds/cashDetailList', [FundsController::class, 'cashDetailList']);
Route::get('Home/Funds/prereceivingIndex', [FundsController::class, 'prereceivingIndex']);
Route::post('Home/Funds/addPreReceivingInfo', [FundsController::class, 'addPreReceivingInfo']);
Route::post('Home/Funds/returnPreReceivingInfo', [FundsController::class, 'returnPreReceivingInfo']);
Route::post('Home/Funds/addPreReceiving', [FundsController::class, 'addPreReceiving']);
Route::post('Home/Funds/returnPreReceiving', [FundsController::class, 'returnPreReceiving']);
Route::post('Home/Funds/prereceivingList', [FundsController::class, 'prereceivingList']);
Route::post('Home/Funds/prereceivingDetailList', [FundsController::class, 'prereceivingDetailList']);
Route::get('Home/Funds/prepaymentIndex', [FundsController::class, 'prepaymentIndex']);
Route::post('Home/Funds/addPrePaymentInfo', [FundsController::class, 'addPrePaymentInfo']);
Route::post('Home/Funds/addPrePayment', [FundsController::class, 'addPrePayment']);
Route::post('Home/Funds/prepaymentList', [FundsController::class, 'prepaymentList']);
Route::post('Home/Funds/prepaymentDetailList', [FundsController::class, 'prepaymentDetailList']);
Route::post('Home/Funds/returnPrePaymentInfo', [FundsController::class, 'returnPrePaymentInfo']);
Route::post('Home/Funds/returnPrePayment', [FundsController::class, 'returnPrePayment']);
Route::get('Home/Funds/detailIndex', [FundsController::class, 'detailIndex']);
Route::get('Home/Funds/getOrgCode', [FundsController::class, 'getOrgCode']);
Route::get('Home/Funds/diRvIndex', [FundsController::class, 'diRvIndex']);
Route::get('Home/Funds/diPayIndex', [FundsController::class, 'diPayIndex']);