<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Customers_Report;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\Invoices_Report;
use App\Http\Controllers\InvoicesDetailsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes(['register'=>false]);
//kda elregister we2f

Route::resource('invoices',InvoiceController::class);
Route::resource('section',SectionController::class);
Route::resource('products',ProductController::class);
Route::resource('Archive', InvoiceAchiveController::class);

Route::get('/index', [AdminController::class, 'index']);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/section/{id}', [InvoiceController::class, 'getproducts']);

Route::get('/edit_invoice/{id}', [InvoiceController::class, 'edit']);

Route::get('/InvoicesDetails/{id}', [InvoicesDetailsController::class, 'edit']);

Route::get('/Status_show/{id}',[InvoiceController::class,'show'])->name('Status_show');

Route::post('/Status_Update/{id}',[InvoiceController::class,'Status_Update'])->name('Status_Update');

Route::get('download/{invoice_number}/{file_name}', [InvoicesDetailsController::class, 'get_file']);

Route::get('View_file/{invoice_number}/{file_name}', [InvoicesDetailsController::class, 'open_file']);

Route::get('paid_invoices',[InvoiceController::class,'invoice_paid']);

Route::get('unpaid_invoices',[InvoiceController::class,'Invoice_UnPaid']);

Route::get('partial_invoices',[InvoiceController::class,'Invoice_Partial']);

Route::get('Print_invoice/{id}',[InvoiceController::class,'Print_invoice']);

Route::post('delete_file', [InvoicesDetailsController::class, 'destroy'])->name('delete_file');

Route::delete('invoice/destroy', [InvoiceController::class, 'destroy']);

Route::group(['middleware' => ['auth']], function() {
    Route::resource('users',UserController::class);
    Route::resource('roles',RoleController::class);
    
});

Route::get('invoices_report',[Invoices_Report::class,'index']);

Route::post('Search_invoices',[Invoices_Report::class,'Search_invoices']);

Route::get('customers_report',[Customers_Report::class,'index'])->name("customers_report");

Route::post('Search_customers',[Customers_Report::class,'Search_customers']);

Route::get('MarkAsRead_all',[InvoiceController::class,'MarkAsRead_all'])->name("MarkAsRead_all");
