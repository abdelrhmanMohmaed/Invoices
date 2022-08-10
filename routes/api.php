<?php

use App\Http\Controllers\Api\InvoicesController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('invoices', [InvoicesController::class, 'index']);
Route::get('invoices/show/{id}', [InvoicesController::class, 'show']);

Route::post('add_invoices', [InvoicesController::class, 'store']);
Route::post('update_invoices/{id}', [InvoicesController::class, 'update']);
Route::post('delete_invoices/{id}', [InvoicesController::class, 'delete']);
