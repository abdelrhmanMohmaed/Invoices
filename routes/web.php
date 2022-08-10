<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\InvoiceController;
use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\web\ReportController;
use App\Http\Controllers\Web\SectionController;
use App\Http\Controllers\web\UserController;
use GuzzleHttp\Middleware;
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




// Route::get('/register', 'LoginController@show_signup_form')->name('register');
// Route::post('/register', 'LoginController@process_signup');


// Route::get('/{page}', [AdminController::class, 'show']);








Route::get('login', [LoginController::class, 'index'])->name('login');
Route::post('/login/submit', [LoginController::class, 'process_login']);

Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index']);
    // Invoices
    Route::resource('invoices', InvoiceController::class);
    Route::get('invoicesDetails/{id} ', [InvoiceController::class, 'invoicesDetails']);
    Route::get('section/{id} ', [InvoiceController::class, 'getProducts']);
    Route::post('invoices/status', [InvoiceController::class, 'status'])->middleware('superadmin');
    Route::get('payment_show/{id}', [InvoiceController::class, 'payment_show']);
    Route::post('payment_update/{id}', [InvoiceController::class, 'payment_update']);

    Route::get('invoices_paid', [InvoiceController::class, 'invoices_paid']);
    Route::get('invoices_unPaid', [InvoiceController::class, 'invoices_unPaid']);
    Route::get('invoices_partial', [InvoiceController::class, 'invoices_partial']);
    Route::get('invoices_active', [InvoiceController::class, 'invoices_active']);
    Route::get('Print_invoice/{id}', [InvoiceController::class, 'Print_invoice']);


    
    Route::get('make_read_all', [InvoiceController::class, 'make_read_all']);
    
    // InvoiceAttachments
    Route::get('View_file/{invoice_number}/{file_name} ', [InvoiceController::class, 'open_file']);
    Route::post('invoiceAttachments', [InvoiceController::class, 'addAttachments'])->middleware(['superadmin', 'admin']);
    Route::get('download/{invoice_number}/{file_name} ', [InvoiceController::class, 'get_file']);
    Route::post('delete_file', [InvoiceController::class, 'delete_Attachment'])->middleware('superadmin');


    Route::resource('sections', SectionController::class);
    Route::post('sections/status', [SectionController::class, 'status'])->middleware('superadmin');
    Route::resource('product', ProductController::class);
    Route::post('product/status', [ProductController::class, 'status'])->middleware('superadmin');

    Route::resource('users', UserController::class);
    Route::get('users/status/{id}', [UserController::class, 'status'])->middleware('superadmin');



    Route::get('report', [ReportController::class, 'index']);
    Route::post('search', [ReportController::class, 'search']);

    Route::get('report_customers', [ReportController::class, 'report_customers']);
    Route::post('search_customers', [ReportController::class, 'search_customers']);
    

    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});
