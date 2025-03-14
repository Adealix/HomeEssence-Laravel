<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomerProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CustomerController; // Import CustomerController

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [ItemController::class, 'getItems'])->name('getItems');
Route::get('add-to-cart/{id}', [ItemController::class, 'addToCart'])->name('addToCart');
Route::get('/shopping-cart', [ItemController::class, 'getCart'])->name('getCart');

Route::get('/reduce/{id}', [ItemController::class, 'getReduceByOne'])->name('reduceByOne');
Route::get('/remove/{id}', [ItemController::class, 'getRemoveItem'])->name('removeItem');
Route::get('/checkout', [ItemController::class, 'postCheckout'])->name('checkout')->middleware('auth');

Route::post('/items-import', [ItemController::class, 'import'])->name('item.import');
Route::resource('items', ItemController::class);
Route::get('/logout', [UserController::class, 'logout'])->name('user.logout');

Route::prefix('admin')->group(function () {
    Route::get('/users', [DashboardController::class, 'getUsers'])->name('admin.users');
    Route::get('/orders', [DashboardController::class, 'getOrders'])->name('admin.orders');
    Route::get('/order/{id}', [OrderController::class, 'processOrder'])->name('admin.orderDetails');
    Route::post('/order/{id}', [OrderController::class, 'orderUpdate'])->name('admin.orderUpdate');

    // Add update route for users. This route handles PUT requests.
    Route::put('/users/{id}', [UserController::class, 'update_role'])->name('users.update');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Override the default home route if needed
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Define a route for the customer profile creation form using CustomerController's create() method
Route::get('/customerprofile/create', [CustomerController::class, 'create'])
    ->name('customerprofile.create')
    ->middleware('auth');

// Define a route for storing the customer profile using CustomerController's store() method
Route::post('/customerprofile', [CustomerController::class, 'store'])
    ->name('customerprofile.store')
    ->middleware('auth');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Define a search route for GET requests
Route::get('/search', [SearchController::class, 'search'])->name('search');

// ***** NEW ROUTE: Show Customer Profile *****
// This route is used by the Customer model's getSearchResult() method.
Route::get('/customers/{customer}', [CustomerController::class, 'show'])
    ->name('customers.show')
    ->middleware('auth');
