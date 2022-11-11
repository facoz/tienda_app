<?php

use App\Http\Controllers\OrderController;
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
Route::controller(OrderController::class)->group(function()
{
    Route::get('/', 'index')->name('home');
    Route::post('orders/create', 'saveCustomerOrder')->name('order.save');
    Route::get('orders/view/{order}', 'viewDetailedOrder')->name('order.view');
    Route::get('orders/all', 'viewAllOrders')->name('order.view.all');
    Route::put('orders/process/{order}', 'createCustomerSession')->name('order.process');
    Route::post('orders/view/{order}', 'checkOrderStatus')->name('order.validate');
    Route::put('orders/actions/{order}', 'makeAction')->name('order.execute_action');
});

route::fallback(function() 
{
    return redirect()->route('home');
});