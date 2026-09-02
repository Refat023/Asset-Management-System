<?php

use App\Http\Controllers\BasicinfoController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ConsumableController;
use App\Http\Controllers\DesktopController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\PeopleController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserLogController;
use App\Models\Desktop;
use App\Models\Employee;
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
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/master', [HomeController::class, 'master'])->name('master');


//User
Route::get('/users', [UserController::class, 'users'])->name('users');
Route::get('/users/create', [UserController::class, 'create'])->name('create');
Route::post('/users/store', [UserController::class, 'users_store'])->name('users_store');
Route::get('/users/delete/{user_id}', [UserController::class, 'users_delete'])->name('users.delete');
Route::get('/users/export', [UserController::class, 'export'])->name('export');
Route::get('/profile', [UserController::class, 'profile'])->name('profile');
Route::post('/name/change', [UserController::class, 'name_change'])->name('name.change');
Route::post('/password/change', [UserController::class, 'password_change'])->name('password.change');
Route::post('/profile/photo/change', [UserController::class, 'profile_photo_change'])->name('profile.photo.change');


// AJAX User Management Routes
Route::get('/users/{user_id}/view', [UserController::class, 'users_view'])->name('users.view');
Route::get('/users/{user_id}/edit', [UserController::class, 'users_edit'])->name('users.edit');
Route::put('/users/{user_id}/update', [UserController::class, 'users_update'])->name('users.update');
Route::delete('/users/{user_id}/delete', [UserController::class, 'users_delete'])->name('users.ajax.delete');

// Profile Management Routes
Route::get('/users/{user_id}/profile/edit', [UserController::class, 'users_profile_edit'])->name('users.profile.edit');
Route::post('/users/{user_id}/profile/update', [UserController::class, 'users_profile_update'])->name('users.profile.update');

// Password Management Routes
Route::get('/users/{user_id}/password/edit', [UserController::class, 'users_password_edit'])->name('users.password.edit');
Route::post('/users/{user_id}/password/update', [UserController::class, 'users_password_update'])->name('users.password.update');

// User Login Logs Routes
Route::get('/user-logs', [UserLogController::class, 'index'])->name('user.logs.index')->middleware('role_or_permission:admin|view-logs');
Route::get('/user-logs/my-logs', [UserLogController::class, 'myLogs'])->name('user.logs.my')->middleware('auth');
Route::get('/user-logs/user/{userId}', [UserLogController::class, 'userLogs'])->name('user.logs.user')->middleware('role_or_permission:admin|view-logs');
Route::get('/user-logs/export', [UserLogController::class, 'export'])->name('user.logs.export')->middleware('role_or_permission:admin|view-logs');
Route::get('/login-dashboard', [UserLogController::class, 'dashboard'])->name('login.dashboard')->middleware('auth');

//Role
Route::get('/roles', [RoleController::class, 'roles'])->name('roles.index')->middleware('role_or_permission:admin|role-list');
Route::get('/roles/create', [RoleController::class, 'roles_create'])->name('roles.create')->middleware('role_or_permission:admin|role-create');
Route::post('/roles/store', [RoleController::class, 'roles_store'])->name('roles_store')->middleware('role_or_permission:admin|role-create');
Route::get('/roles/edit/{role_id}', [RoleController::class, 'edit'])->name('roles_edit')->middleware(['role_or_permission:admin|role-edit']);
Route::put('/roles/update/{id}', [RoleController::class, 'roles_update'])->name('roles_update')->middleware('role_or_permission:admin|role-edit');
Route::get('/roles/destroy/{role_id}', [RoleController::class, 'destroy'])->name('destroy')->middleware('role_or_permission:admin|role-delete');

Route::get('/permissions', [RoleController::class, 'permissions'])->name('permissions.index')->middleware('role_or_permission:admin|role-list');
Route::get('/permissions/create', [RoleController::class, 'permissions_create'])->name('permissions.create')->middleware('role_or_permission:admin|role-create');
Route::post('/permissions/store', [RoleController::class, 'permissions_store'])->name('permissions.store')->middleware('role_or_permission:admin|role-create');
Route::get('/permissions/edit/{id}', [RoleController::class, 'permissions_edit'])->name('permissions.edit')->middleware('role_or_permission:admin|role-edit');
Route::put('/permissions/update/{id}', [RoleController::class, 'permissions_update'])->name('permissions.update')->middleware('role_or_permission:admin|role-edit');
Route::get('/permissions/destroy/{id}', [RoleController::class, 'permissions_destroy'])->name('permissions.destroy')->middleware('role_or_permission:admin|role-delete');


//Basic Info
Route::get('/basic_info', [BasicinfoController::class, 'basic_info'])->name('basicinfo');

//Department
Route::get('/department', [CategoryController::class, 'department'])->name('department');
Route::post('/department/store', [CategoryController::class, 'department_store'])->name('department.store');
Route::get('/department/delete/{department_id}', [CategoryController::class, 'department_delete'])->name('department.delete');
Route::post('/department/update', [CategoryController::class, 'department_update'])->name('department.update');
Route::get('/department/edit/{department_id}', [CategoryController::class, 'department_edit'])->name('department_edit');
Route::get('/departments/asset/{department_id}', [CategoryController::class, 'departments_asset'])->name('departments_asset');


//designation
Route::get('/designation', [CategoryController::class, 'designation'])->name('designation');
Route::post('/designation/store', [CategoryController::class, 'designation_store'])->name('designation.store');
Route::get('/designation/delete/{designation_id}', [CategoryController::class, 'designation_delete'])->name('designation.delete');
Route::get('/designation/edit/{designation_id}', [CategoryController::class, 'designation_edit'])->name('designation.edit');
Route::post('/designation/update', [CategoryController::class, 'designation_update'])->name('designation.update');

//product
Route::get('/producttype', [CategoryController::class, 'product_type'])->name('product_type');
Route::post('/producttype/store', [CategoryController::class, 'product_type_store'])->name('product.store');
Route::get('/producttype/delete/{ProductType_id}', [CategoryController::class, 'product_type_delete'])->name('product.delete');

//supplier
Route::get('/supplier', [CategoryController::class, 'supplier'])->name('supplier');
Route::post('/supplier/store', [CategoryController::class, 'supplier_store'])->name('supplier.store');
Route::get('/supplier/delete/{supplier_id}', [CategoryController::class, 'supplier_delete'])->name('supplier.delete');

//brand
Route::get('/brand', [CategoryController::class, 'brand'])->name('brand');
Route::post('/brand/store', [CategoryController::class, 'brand_store'])->name('brand.store');
Route::get('/brand/delete{brand_id}', [CategoryController::class, 'brand_delete'])->name('brand.delete');

//status
Route::get('/status', [CategoryController::class, 'status'])->name('status');
Route::post('/status/store', [CategoryController::class, 'status_store'])->name('status.store');
Route::get('/status/delete{status_id}', [CategoryController::class, 'status_delete'])->name('status.delete');

//Size Mesurment
Route::get('/size_mesurment', [CategoryController::class, 'size_mesurment'])->name('size');
Route::post('/size_mesurment/store', [CategoryController::class, 'size_mesurment_store'])->name('size.store');
Route::get('/size_mesurment/delete{sizemesurment_id}', [CategoryController::class, 'size_mesurment_delete'])->name('size.delete');

//color
Route::get('/color', [CategoryController::class, 'color'])->name('color');
Route::post('/color/store', [CategoryController::class, 'color_store'])->name('color.store');
Route::get('/color/delete/{color_id}', [CategoryController::class, 'color_delete'])->name('color.delete');

//Company
Route::get('/company', [CategoryController::class, 'company_list'])->name('company.list');
Route::post('/company/store', [CategoryController::class, 'company_store'])->name('company.store');
Route::get('/company/delete/{company_id}', [CategoryController::class, 'company_delete'])->name('company.delete');
Route::get('/company/search_by_id/{company_id}', [CategoryController::class, 'search_by_id'])->name('search.company');

//store
Route::get('/store', [StoreController::class, 'store'])->name('store');
Route::get('/store/product', [StoreController::class, 'add_product'])->name('add_product');
Route::post('/store/store', [StoreController::class, 'store_store'])->name('store.store');
Route::get('/store/clone/{stores_id}', [StoreController::class, 'store_clone'])->name('store.clone');
Route::post('/store/clone/save', [StoreController::class, 'store_clone_save'])->name('store.clone.save');
Route::get('/store/delete/{stores_id}', [StoreController::class, 'store_delete'])->name('store.delete');
Route::post('/store/delete/{stores_id}', [StoreController::class, 'store_delete'])->name('store.delete.post');
Route::get('/store/delete', [StoreController::class, 'store_delete_list'])->name('store_delete_list'); //delete_list
Route::get('/store/archive/{stores_id}', [StoreController::class, 'store_archive'])->name('store.archive');
Route::post('/store/archive/{stores_id}', [StoreController::class, 'store_archive'])->name('store.archive.post');
Route::get('/store/archive', [StoreController::class, 'store_archive_list'])->name('store_archive_list'); //archive_list
Route::get('/store/instock/list', [StoreController::class, 'instock_list'])->name('instock_list'); //INSTOCK_list
Route::get('/store/issued/list', [StoreController::class, 'issue_list'])->name('issue_list'); //ISSUED_list
Route::get('/store/stock_summary', [StoreController::class, 'stock_summary'])->name('stock_summary'); //ISSUED_list
Route::get('/store/stock_summary/export/excel', [StoreController::class, 'stock_summary_export_excel'])->name('stock_summary.export.excel');
Route::get('/store/stock_summary/export/pdf', [StoreController::class, 'stock_summary_export_pdf'])->name('stock_summary.export.pdf');



Route::get('/store/edit/{stores_id}', [StoreController::class, 'store_edit'])->name('store.edit');
Route::post('/store/update', [StoreController::class, 'store_update'])->name('store.update');
Route::get('/store/view/{stores_id}', [StoreController::class, 'store_view'])->name('store.view');

Route::get('/store/issue/{stores_id}', [StoreController::class, 'issue'])->name('issue');
Route::post('/store/issue/store', [StoreController::class, 'issue_store'])->name('issue.store');
Route::get('/store/search_by_id/{store_id}', [StoreController::class, 'search_by_id'])->name('search.product');

Route::get('/store/return/form', [StoreController::class, 'return_form'])->name('return.form');
Route::get('/store/return_search_by_id/{store_id}', [StoreController::class, 'return_search_by_id'])->name('return.search.product');
Route::post('/store/return/update', [StoreController::class, 'return_update'])->name('return_update');

Route::get('/store/status/{stores_id}', [StoreController::class, 'store_status'])->name('store.status');
Route::get('/store/invoice/{stores_id}', [StoreController::class, 'invoice'])->name('invoice');
Route::get('/store/search', [StoreController::class, 'store_search'])->name('store_search');

Route::get('/history/{asset_tag?}', [StoreController::class, 'history'])->name('history');
Route::get('/history/export', [StoreController::class, 'history_export'])->name('history_export');
Route::get('/history/generatePDF', [StoreController::class, 'history_generatePDF'])->name('pdf.history');

Route::get('/store/export', [StoreController::class, 'store_export'])->name('store_export');
Route::get('/store/QrCode/{stores_id}', [StoreController::class, 'qr_code'])->name('qr_code');
Route::get('/store/qr_code_view/{stores_id}', [StoreController::class, 'qr_code_view'])->name('qr_code_view');
Route::get('/store/import', [StoreController::class, 'store_import'])->name('store_import');
Route::post('/store/importexceldata', [StoreController::class, 'store_importexceldata'])->name('store_importexceldata');
Route::get('/store/info/{store_id}', [StoreController::class, 'store_info'])->name('store_info');
Route::get('/store/file/{stores_id}', [StoreController::class, 'store_file'])->name('store_file');
Route::post('/store/savefile', [StoreController::class, 'store_file_save'])->name('store_file_save');
Route::get('/store/file/delete/{file_id}', [StoreController::class, 'store_file_delete'])->name('store_file_delete');


//Transfer start
Route::get('/store/transfer', [StoreController::class, 'store_transfer'])->name('transfer');
Route::post('/transfer/store', [StoreController::class, 'transfer_store'])->name('transfer.store');
Route::get('/transfer/list', [StoreController::class, 'transfer_list'])->name('transfer_list');
Route::get('/transfer/export', [StoreController::class, 'transfer_export'])->name('transfer_export');
Route::get('/transfer/edit/{id}', [StoreController::class, 'transfer_edit'])->name('transfer_edit');
Route::post('/transfer/update', [StoreController::class, 'transfer_update'])->name('transfer_update');
Route::post('/transfer/transfer_search_by_id/{id}', [StoreController::class, 'transfer_search_by_id'])->name('transfer_search');
Route::get('/transfer/return', [StoreController::class, 'transfer_return'])->name('transfer_return');
Route::get('/transfer/return/search_by_id/{id}', [StoreController::class, 'transfer_return_search_id'])->name('transfer_return_search_id');
Route::post('/transfer/return/update', [StoreController::class, 'transfer_return_update'])->name('transfer_return_update');

// Transfer Request Approval Routes
Route::get('/transfer/requests', [StoreController::class, 'transfer_requests'])->name('transfer_requests');
Route::get('/transfer/requests/pending', [StoreController::class, 'pending_transfer_requests'])->name('pending_transfer_requests');
Route::post('/transfer/requests/{id}/approve', [StoreController::class, 'approve_transfer_request'])->name('approve_transfer_request');
Route::post('/transfer/requests/{id}/reject', [StoreController::class, 'reject_transfer_request'])->name('reject_transfer_request');
Route::get('/transfer/borrowed-items', [StoreController::class, 'borrowed_items'])->name('borrowed_items');



//Transfer end

//Maintenance start
Route::get('/store/maintenance/{stores_id}', [StoreController::class, 'maintenance_form'])->name('maintenance_form');
Route::get('/maintenance/get', [StoreController::class, 'maintenance_get'])->name('maintenance_get');
Route::post('/maintenance/store', [StoreController::class, 'maintenance_store'])->name('maintenance_store');
Route::get('/maintenance/list/{store_id?}', [StoreController::class, 'maintenance_list'])->name('maintenance_list'); // maintenance list by id
Route::get('/maintenance/view', [StoreController::class, 'maintenance_view'])->name('maintenance_view'); // all maintenance list

Route::get('/maintenance/return', [StoreController::class, 'maintenance_return'])->name('maintenance_return');
Route::post('/maintenance/return/update', [StoreController::class, 'ma_return_update'])->name('ma_return_update');
Route::get('/maintenance/maintenance_search_id/{store_id}', [StoreController::class, 'maintenance_search_id'])->name('maintenance_search_id');
Route::get('/maintenance/export', [StoreController::class, 'maintenance_export'])->name('maintenance_export');
Route::get('/maintenance/edit/{id}', [StoreController::class, 'maintenance_edit'])->name('maintenance_edit');
Route::post('/maintenance/update/{id}', [StoreController::class, 'maintenance_update'])->name('maintenance_update');

//Maintenance end

//wastproduct start
Route::get('/store/wastproduct', [StoreController::class, 'wastproduct'])->name('wastproduct');
Route::post('/wastproduct/store', [StoreController::class, 'wastproduct_store'])->name('wastproduct_store');
Route::get('/wastproduct/list', [StoreController::class, 'wastproduct_list'])->name('wastproduct_list');
Route::get('/wastproduct/edit/{id}', [StoreController::class, 'wastproduct_edit'])->name('wastproduct_edit');
Route::post('/wastproduct/update', [StoreController::class, 'wastproduct_update'])->name('wastproduct_update');
Route::get('/wastproduct/delete/{id}', [StoreController::class, 'wastproduct_delete'])->name('wastproduct_delete');
Route::get('/wastproduct/export', [StoreController::class, 'wastproduct_export'])->name('wastproduct_export');
//wastproductend
    

//password
Route::get('/computer_pass', [PasswordController::class, 'computer_pass'])->name('computer_pass');
Route::post('/computer_pass/store', [PasswordController::class, 'computer_pass_store'])->name('computer_pass_store');
Route::get('/computer_pass/delete/{id}', [PasswordController::class, 'computer_pass_delete'])->name('computer_pass_delete');
Route::get('/computer_pass/edit/{id}', [PasswordController::class, 'computer_pass_edit'])->name('computer_pass_edit');
Route::post('/computer_pass/update', [PasswordController::class, 'computer_update'])->name('computer_update');
Route::get('/search', [PasswordController::class, 'search'])->name('search');
Route::get('/computer_pass/export', [PasswordController::class, 'computer_pass_export'])->name('computer_export');

Route::get('/mail_pass', [PasswordController::class, 'mail_pass'])->name('mail_pass');
Route::post('/mail_pass/store', [PasswordController::class, 'mail_pass_store'])->name('mail_pass_store');
Route::get('/mail_pass/delete/{mail_id}', [PasswordController::class, 'mail_pass_delete'])->name('mail_pass_delete');
Route::get('/mail_pass/edit/{mail_id}', [PasswordController::class, 'mail_pass_edit'])->name('mail_pass_edit');
Route::post('/mail_pass/update', [PasswordController::class, 'mail_pass_update'])->name('mail_pass_update');
Route::get('/mail_pass/mail_export', [PasswordController::class, 'mail_export'])->name('mail_export');

Route::get('/camera_pass', [PasswordController::class, 'camera_pass'])->name('camera_pass');
Route::post('/camera_pass/store', [PasswordController::class, 'camera_pass_store'])->name('camera_pass_store');
Route::get('/camera_pass/delete/{camera_id}', [PasswordController::class, 'camera_pass_delete'])->name('camera_pass_delete');
Route::get('/camera_pass/edit/{camera_id}', [PasswordController::class, 'camera_edit'])->name('camera_edit');
Route::post('/camera_pass/update', [PasswordController::class, 'camera_update'])->name('camera_update');
Route::get('/camera_pass/export', [PasswordController::class, 'camera_export'])->name('camera_export');

Route::get('/internet_pass', [PasswordController::class, 'internet_pass'])->name('internet_pass');
Route::post('/internet_pass/store', [PasswordController::class, 'internet_pass_store'])->name('internet_pass_store');
Route::get('/internet_pass/delete/{internet_id}', [PasswordController::class, 'internet_pass_delete'])->name('internet_pass_delete');
Route::get('/internet_pass/edit/{internet_id}', [PasswordController::class, 'internet_edit'])->name('internet_edit');
Route::post('/internet_pass/update', [PasswordController::class, 'internet_update'])->name('internet_update');
Route::get('/internet_pass/export', [PasswordController::class, 'internet_export'])->name('internet_export');

Route::get('/ding_pass', [PasswordController::class, 'ding_pass'])->name('ding_pass');
Route::post('/ding_pass/store', [PasswordController::class, 'ding_pass_store'])->name('ding_pass_store');
Route::get('/ding_pass/delete/{ding_id}', [PasswordController::class, 'ding_pass_delete'])->name('ding_pass_delete');
Route::get('/ding_pass/edit/{ding_id}', [PasswordController::class, 'ding_edit'])->name('ding_edit');
Route::post('/ding_pass/update', [PasswordController::class, 'ding_update'])->name('ding_update');
Route::get('/ding_pass/export', [PasswordController::class, 'ding_export'])->name('ding_export');

Route::get('/others_pass', [PasswordController::class, 'others_pass'])->name('others_pass');



//employee Managment
Route::get('/employee/active/list', [EmployeeController::class, 'employee_list'])->name('employee_list');
Route::get('/employee/inactive/list', [EmployeeController::class, 'inactive_list'])->name('inactive_list');
Route::get('/employee/delete/list', [EmployeeController::class, 'delete_list'])->name('delete_list');
Route::post('/employee/clone/{id}', [EmployeeController::class, 'cloneEmployee'])->name('employee.clone');
Route::post('/employee/store', [EmployeeController::class, 'employee_store'])->name('employee.store');
Route::get('/employee/delete/{employee_id}', [EmployeeController::class, 'employee_delete'])->name('employee.delete');
Route::get('/employee/edit/{employee_id}', [EmployeeController::class, 'employee_edit'])->name('employee_edit');
Route::post('/employee/update', [EmployeeController::class, 'employee_update'])->name('employee_update');
Route::get('/employee/search_by_id/{employee_id}', [EmployeeController::class, 'search_by_id'])->name('search.empl');
Route::get('/search', [EmployeeController::class, 'search'])->name('search');
Route::get('/employee/export', [EmployeeController::class, 'export'])->name('export');
Route::get('/employee/import', [EmployeeController::class, 'employee_import'])->name('employee_import');
Route::post('/employee/importexceldata', [EmployeeController::class, 'employee_importexceldata'])->name('employee_importexceldata');
Route::get('/employee/info/{id}', [EmployeeController::class, 'employee_info'])->name('employee_info');
Route::get('/employee/users/asset/{emp_id}', [EmployeeController::class, 'employee_assets'])->name('employee_assets');
Route::get('/employee/users/consumable/{emp_id}', [EmployeeController::class, 'employee_consumable'])->name('employee_consumable');
Route::get('/employee/users/file/{emp_id}', [EmployeeController::class, 'employee_file'])->name('employee_file');
Route::post('/employee/storeOtherFile/{id}', [EmployeeController::class, 'storeOtherFile'])->name('employee.storeOtherFile');
Route::get('/employee/storeOtherFile/delete/{id}', [EmployeeController::class, 'employee_file_delete'])->name('employee_file_delete');
Route::get('/employee/generatePDF/{emp_id}', [EmployeeController::class, 'history_generatePDF'])->name('pdf_history');
Route::post('/employee/generatePDF/selected/{emp_id}', [EmployeeController::class, 'history_generatePDF_selected'])->name('pdf_history_selected');

//Employee Mail signature QR Code
Route::get('/employee/view/{employee_id}', [EmployeeController::class, 'employee_view'])->name('employee_view');
Route::get('/employee/qrcode/{employee_id}', [EmployeeController::class, 'qrcode'])->name('employee.qrcode');



//desktop
Route::get('/desktop', [DesktopController::class, 'desktop'])->name('desktop');
Route::post('/desktop/store/', [DesktopController::class, 'desktop_store'])->name('desktop.store');
Route::get('/desktop/view/{desktop_id}', [DesktopController::class, 'desktop_view'])->name('desktop.view');

//consumableController
Route::get('/productdetails', [ConsumableController::class, 'productdetails'])->name('productdetails');
Route::post('/productdetails/store', [ConsumableController::class, 'productdetails_store'])->name('productdetails_store');
Route::get('/productdetails/delete/{id}', [ConsumableController::class, 'productdetails_delete'])->name('productdetails_delete');
Route::get('/Inventory', [ConsumableController::class, 'Inventory'])->name('Inventory');
Route::get('/inventory/getStockQty', [ConsumableController::class, 'getStockQty'])->name('get.stock.qty');
Route::get('/consumableIssue', [ConsumableController::class, 'consumableIssue'])->name('consumableIssue');
Route::post('/consumableIssue/store', [ConsumableController::class, 'consumableIssue_store'])->name('consumableIssue_store');
Route::get('/consumableIssue/delete/{id}', [ConsumableController::class, 'consumableIssue_delete'])->name('consumableIssue_delete');
Route::get('/product', [ConsumableController::class, 'product'])->name('product');
Route::post('/product/store', [ConsumableController::class, 'product_store'])->name('product_store');

//Database Backup Routes
Route::get('/backup', [App\Http\Controllers\DatabaseBackupController::class, 'index'])->name('backup.index');
Route::post('/backup/full', [App\Http\Controllers\DatabaseBackupController::class, 'fullBackup'])->name('backup.full');
Route::post('/backup/table', [App\Http\Controllers\DatabaseBackupController::class, 'tableBackup'])->name('backup.table');
Route::get('/backup/download/{filename}', [App\Http\Controllers\DatabaseBackupController::class, 'downloadBackup'])->name('backup.download');
Route::delete('/backup/delete/{filename}', [App\Http\Controllers\DatabaseBackupController::class, 'deleteBackup'])->name('backup.delete');
Route::get('/backup/detect-path', [App\Http\Controllers\DatabaseBackupController::class, 'detectMysqldumpPath'])->name('backup.detect.path');

//
