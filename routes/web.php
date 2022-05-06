<?php

use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Auth::routes();
Route::put('/profile/changeMode', 'ProfileController@changeMode')->name('profile.changeMode');
Route::post('/profile/changePassword', 'ProfileController@changePassword')->name('profile.changePassword');
Route::get('/profile/getBrands', 'ProfileController@getBrands')->name('profile.getBrands');
Route::get('/ams/dashboard', 'AmsController@index')->name('ams.dashBoard'); // ams dashboard
Route::get('/ams/code', 'AmsController@code')->name('ams.code'); // return url
Route::post('/profile/switchBrand', 'ProfileController@switchBrand');

Route::get('notification', 'Alerts\NotificationController@index')->name('notification.index');
Route::get('/notification/new', 'Alerts\NotificationController@getNewNotification')->name('notification.new');
Route::get('/notification/mark/read/all', 'Alerts\NotificationController@markAllAsRead')->name('notification.mark.read.all');
Route::get('/notification/show/{alert}', 'Alerts\NotificationController@show')->name('notification.show');
Route::post('/notification/mark/{alert}/disable', 'Alerts\NotificationController@disable')->name('notification.mark.disable');

Route::get('/home', 'ExecutiveDashboard\ExecutiveDashboard@index')->name('home');
Route::post('ed/report', 'ExecutiveDashboard\ExecutiveDashboard@getEDReport')->name('ed.report');
Route::post('ed/vendor/report', 'ExecutiveDashboard\ExecutiveDashboard@getVendorDetails')->name('ed.vendor.report');
Route::post('ed/vendor/trailing/sc', 'ExecutiveDashboard\ExecutiveDashboard@getShippedCogsTrailing')->name('ed.vendor.trailing.sc');
Route::post('ed/vendor/trailing/nr', 'ExecutiveDashboard\ExecutiveDashboard@getNetReceivedTrailing')->name('ed.vendor.trailing.nr');
Route::post('ed/vendor/store', 'ExecutiveDashboard\ExecutiveDashboard@setEDVendor')->name('ed.vendor.store');

Route::get('ed/confirmPO', 'ExecutiveDashboard\ConfirmPO@index')->name('ed.confirmPO');
Route::post('ed/confirmPO/report', 'ExecutiveDashboard\ConfirmPO@getEDConfirmPOReport')->name('ed.confirmPO.report');
Route::post('ed/confirmPO/report/vendor', 'ExecutiveDashboard\ConfirmPO@getEDConfirmPOVendorReport')->name('ed.confirmPO.report.vendor');
Route::post('ed/confirmPO/vendor/store', 'ExecutiveDashboard\ConfirmPO@setTopEDPOVendor')->name('ed.confirmPO.vendor.store');

Route::get('ed/newConfirmPO', 'ExecutiveDashboard\newConfirmPO@index')->name('ed.newConfirmPO');
Route::post('ed/newConfirmPO/report', 'ExecutiveDashboard\newConfirmPO@getEDConfirmPOReport')->name('ed.newConfirmPO.report');
Route::post('ed/newConfirmPO/report/vendor', 'ExecutiveDashboard\newConfirmPO@getEDConfirmPOVendorReport')->name('ed.newConfirmPO.report.vendor');

Route::get('po/plan', 'ExecutiveDashboard\ConfirmPO@getPOPlan')->name('po.plan');
Route::post('po/plan/store', 'ExecutiveDashboard\ConfirmPO@setPOPlan')->name('po.plan.store');

Route::get('threshold', 'Alerts\ThresholdController@index')->name('threshold');
Route::post('threshold/store', 'Alerts\ThresholdController@store')->name('threshold.store');
Route::delete('threshold/remove/{threshold}', 'Alerts\ThresholdController@destroy')->name('threshold.destroy');


Route::get('superadmin/restore', 'SuperAdminController@restore')->name('superadmin.restore');
Route::resource('/superadmin', 'SuperAdminController');
Route::put('superadmin/status/{user}', 'SuperAdminController@updateStatus');

Route::get('admin/restore', 'AdminController@restore')->name('admin.restore');
Route::resource('/admin', 'AdminController');
Route::put('admin/status/{user}', 'AdminController@updateStatus');

Route::get('operator/restore', 'OperatorController@restore')->name('operator.restore');
Route::resource('/operator', 'OperatorController');
Route::put('operator/status/{user}', 'OperatorController@updateStatus');


Route::get('user/restore', 'UserController@restore')->name('user.restore');
Route::resource('/user', 'UserController');
Route::put('user/status/{user}', 'UserController@updateStatus');

Route::get('user-vendors/restore', 'VendorsController@restore')->name('user-vendors.restore');
Route::resource('/user-vendors', 'VendorsController');
Route::put('user-vendors/status/{user}', 'VendorsController@updateStatus');

Route::get('brand/restore', 'BrandController@restore')->name('brand.restore');
Route::resource('/brand', 'BrandController');
Route::put('brand/status/{user}', 'BrandController@updateStatus');
Route::get('brand/unassignedUsers/{brand}', 'BrandController@getUnassignedUsers')->name('brand.unassignedUsers');
Route::get('brand/users/{brand}', 'BrandController@getAssignedUsers')->name('brand.users');
Route::put('brand/assign/{user}', 'BrandController@assignUser')->name('brand.assign');
Route::put('brand/unassign/{user}', 'BrandController@unassignUser')->name('brand.unassign');

Route::get('brand/vendors/{brand}', 'BrandController@getAssociatedVendors')->name('brand.vendors');
Route::get('brand/unassignedVendors/{brand}', 'BrandController@getUnassignedVendors')->name('brand.unassignedVendors');
Route::put('brand/assignVendor/{vendor}', 'BrandController@assignVendor')->name('brand.assignVendor');
Route::put('brand/unassignVendor/{vendor}', 'BrandController@unassignVendor')->name('brand.unassignVendor');

Route::get('sales/visual', 'Sales\SalesVisualController@index')->name('sales.visual');
Route::post('sales/visual/graph', 'Sales\SalesVisualController@getSaleGraph')->name('sales.visual.graph');
Route::get('sales/visual/new', 'Sales\NewSalesVisualController@index')->name('sales.visual.new');
Route::post('sales/visual/new/sale', 'Sales\NewSalesVisualController@getSales')->name('sales.visual.new.sale');
Route::post('sales/visual/new/getSubcategory', 'Sales\NewSalesVisualController@getSubcategory')->name('sales.visual.new.getSubcategory');
Route::post('sales/visual/new/subcategory_shipped_cogs', 'Sales\NewSalesVisualController@getSubcategoryShippedCOGS')->name('sales.visual.new.subcategory.shipped_cogs');
Route::post('sales/visual/new/subcategory_net_receipts', 'Sales\NewSalesVisualController@getSubcategoryNetReceipts')->name('sales.visual.new.subcategory.net_receipts');
Route::post('sales/visual/new/subcategory_po_confirmed_rate', 'Sales\NewSalesVisualController@getSubcategoryPoConfirmedRate')->name('sales.visual.new.subcategory.po_confirmed_rate');
Route::post('sales/visual/new/subcategory_sip', 'Sales\NewSalesVisualController@getSIPSubcategoryValue')->name('sales.visual.new.subcategory.sip');

Route::get('sales/load', 'Sales\LoadSalesController@index')->name('sales.load');
Route::post('sales/load/daily', 'Sales\LoadSalesController@loadDailySales')->name('sales.load.daily');
Route::post('sales/load/weekly', 'Sales\LoadSalesController@loadWeeklySales')->name('sales.load.weekly');
Route::post('sales/load/monthly', 'Sales\LoadSalesController@loadMonthlySales')->name('sales.load.monthly');

Route::get('inventory', 'Inventory\DailyInventoryController@index')->name('inventory');
Route::post('inventory/store', 'Inventory\DailyInventoryController@store')->name('inventory.store');
Route::post('inventory/store/vendor', 'Inventory\DailyInventoryController@storevendor')->name('inventory.store.vendor');
Route::get('inventory/verify_all', 'Inventory\DailyInventoryController@verifyAll')->name('inventory.verify_all');
Route::get('inventory/verify/{vendor}', 'Inventory\DailyInventoryController@verifyByVendor')->name('inventory.verify');
Route::put('inventory/destroyDate/{vendor}', 'Inventory\DailyInventoryController@destroyByDate')->name('inventory.destroyDate');
Route::put('inventory/destroy/{vendor}', 'Inventory\DailyInventoryController@destroy')->name('inventory.destroy');
Route::get('inventory/moveAllToCore', 'Inventory\DailyInventoryController@moveAllToCore')->name('inventory.moveAllToCore');
Route::get('inventory/moveToCore/{vendor}', 'Inventory\DailyInventoryController@moveToCore')->name('inventory.moveToCore');
Route::get('inventory/load', 'Inventory\LoadInventoryController@index')->name('inventory.load');
Route::post('inventory/load/daily', 'Inventory\LoadInventoryController@loadDailyInventory')->name('inventory.load.daily');
Route::post('inventory/load/weekly', 'Inventory\LoadInventoryController@loadWeeklyInventory')->name('inventory.load.weekly');
Route::post('inventory/load/monthly', 'Inventory\LoadInventoryController@loadMonthlyInventory')->name('inventory.load.monthly');

Route::resource('/sales', 'DailySalesController');
Route::resource('/category', 'CategoryController');
Route::resource('/detailsale', 'DetailSalesController');
Route::resource('/verify', 'VerifySalesController');
Route::get('verify/vendors/{verify}', 'VerifySalesController@AssociatedVendors')->name('verify.vendors');
Route::put('verify/destroy/{vendor}/{date}', 'VerifySalesController@destroy')->name('verify.destroy');
Route::put('verify/destroyVendor/{vendor}', 'VerifySalesController@destroyVendor')->name('verify.destroyVendor');
Route::get('verify/moveToCore/{vendor}', 'VerifySalesController@moveToCore')->name('verify.moveToCore');
Route::get('moveToCore', 'VerifySalesController@saleToCore')->name('verify.saleToCore');

Route::resource('/verifyPtp', 'PtpVerifyController');
Route::post('verifyPtp/store/{vendor}', 'PtpVerifyController@store')->name('verifyPtp.store');
Route::get('ptpMoveData', 'PtpVerifyController@ptpMoveData')->name('verifyPtp.ptpMoveData');

Route::resource('/verifyCategory', 'CategoryVerifyController');
Route::post('verifyCategory/store/{vendor}', 'CategoryVerifyController@store')->name('verifyCategory.store');
Route::get('categoryMoveData', 'CategoryVerifyController@categoryMoveData')->name('verifyCategory.categoryMoveData');

Route::group(['middleware' => ['superAdmin']], function () {
    Route::resource('/role', 'RoleController');
    Route::post('role/authorization/{role}', 'RoleController@updateAuthorization')->name('roleAuthorization');
});


Route::get('purchase/upload', 'PurchaseOrder\PurchaseOrderController@index')->name('purchase.upload');
Route::post('purchase/store/{vendor}', 'PurchaseOrder\PurchaseOrderController@purchaseOrderStoreRecords')->name('purchase.store');
Route::get('/purchaseVerify', 'PurchaseOrder\PurchaseOrderController@verify')->name('purchaseVerify.verify');
Route::get('purchaseVerify/moveToCore/{vendor}', 'PurchaseOrder\PurchaseOrderController@moveToCore')->name('purchaseVerify.moveToCore');
Route::get('purchaseVerify/vendors/{verify}', 'PurchaseOrder\PurchaseOrderController@AssociatedVendors')->name('purchaseVerify.vendors');
Route::put('purchaseVerify/destroy/{vendor}/{date}', 'PurchaseOrder\PurchaseOrderController@destroy')->name('purchaseVerify.destroy');
Route::put('purchaseVerify/destroyVendor/{vendor}', 'PurchaseOrder\PurchaseOrderController@destroyVendor')->name('purchaseVerify.destroyVendor');
Route::get('purchaseVerify/moveToCore/{vendor}', 'PurchaseOrder\PurchaseOrderController@moveToCore')->name('purchaseVerify.moveToCore');
Route::get('purchaseOrder/load', 'PurchaseOrder\LoadPurchaseOrderController@index')->name('purchaseOrder.load');
Route::post('purchaseOrder/load/daily', 'PurchaseOrder\LoadPurchaseOrderController@loadDailyPo')->name('purchaseOrder.load.daily');
Route::post('purchaseOrder/load/weekly', 'PurchaseOrder\LoadPurchaseOrderController@loadWeeklyPo')->name('purchaseOrder.load.weekly');
Route::post('purchaseOrder/load/monthly', 'PurchaseOrder\LoadPurchaseOrderController@loadMonthlyPo')->name('purchaseOrder.load.monthly');

Route::get('sellerCenter', 'SellerCenter\SellerCenterController@index')->name('sellerCenter.index');
Route::post('sellerCenter/store', 'SellerCenter\SellerCenterController@store')->name('sellerCenter.store');
Route::get('sellerCenter/verifyAll', 'SellerCenter\SellerCenterController@verifyAll')->name('sellerCenter.verifyAll');
Route::get('sellerCenter/verify/{vendor}', 'SellerCenter\SellerCenterController@verifyByVendor')->name('sellerCenter.verify');
Route::get('sellerCenter/moveAllToCore', 'SellerCenter\SellerCenterController@moveAllToCore')->name('sellerCenter.moveAllToCore');
Route::get('sellerCenter/moveToCore/{vendor}', 'SellerCenter\SellerCenterController@moveToCore')->name('sellerCenter.moveToCore');
Route::put('sellerCenter/destroyDate/{vendor}', 'SellerCenter\SellerCenterController@destroyByDate')->name('sellerCenter.destroyDate');
Route::put('sellerCenter/destroy/{vendor}', 'SellerCenter\SellerCenterController@destroy')->name('sellerCenter.destroy');
Route::get('sellerCenter/load', 'SellerCenter\LoadSellerCenterController@index')->name('sellerCenter.load');
Route::post('sellerCenter/load/daily', 'SellerCenter\LoadSellerCenterController@loadDailyDropship')->name('sellerCenter.load.daily');

Route::get('dropship', 'Dropship\DropshipController@index')->name('dropship.index');
Route::post('dropship/store', 'Dropship\DropshipController@store')->name('dropship.store');
Route::get('dropship/verifyAll', 'Dropship\DropshipController@verifyAll')->name('dropship.verifyAll');
Route::get('dropship/moveAllToCore', 'Dropship\DropshipController@moveAllToCore')->name('dropship.moveAllToCore');
Route::get('dropship/moveToCore/{vendor}', 'Dropship\DropshipController@moveToCore')->name('dropship.moveToCore');
Route::put('dropship/destroy/{vendor}', 'Dropship\DropshipController@destroy')->name('dropship.destroy');
Route::get('dropship/load', 'Dropship\LoadDropshipController@index')->name('dropship.load');
Route::post('dropship/load/daily', 'Dropship\LoadDropshipController@loadDailyDropship')->name('dropship.load.daily');
Route::post('dropship/load/weekly', 'Dropship\LoadDropshipController@loadWeeklyDropship')->name('dropship.load.weekly');
Route::post('dropship/load/monthly', 'Dropship\LoadDropshipController@loadMonthlyDropship')->name('dropship.load.monthly');
Route::put('dropship/removeDuplication', 'Dropship\DropshipController@removeDuplication')->name('dropship.removeDuplication');
