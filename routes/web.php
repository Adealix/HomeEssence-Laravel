<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomerProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CustomerController;

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

// Home page displaying items using the shop view:
Route::get('/', [ItemController::class, 'getItems'])->name('getItems');

// Cart routes
Route::get('add-to-cart/{id}', [ItemController::class, 'addToCart'])->name('addToCart');
Route::get('/shopping-cart', [ItemController::class, 'getCart'])->name('getCart');
Route::get('/reduce/{id}', [ItemController::class, 'getReduceByOne'])->name('reduceByOne');
Route::get('/remove/{id}', [ItemController::class, 'getRemoveItem'])->name('removeItem');
Route::get('/checkout', [ItemController::class, 'postCheckout'])->name('checkout')->middleware('auth');

// Import route
Route::post('/items-import', [ItemController::class, 'import'])->name('item.import');

// Public Items resource route (if needed for customer-facing CRUD)
Route::resource('items', ItemController::class);

// Other routes
Route::get('/logout', [UserController::class, 'logout'])->name('user.logout');

Route::prefix('admin')->group(function () {
    Route::get('/users', [DashboardController::class, 'getUsers'])->name('admin.users');
    Route::get('/orders', [DashboardController::class, 'getOrders'])->name('admin.orders');
    Route::get('/order/{id}', [OrderController::class, 'processOrder'])->name('admin.orderDetails');
    Route::post('/order/{id}', [OrderController::class, 'orderUpdate'])->name('admin.orderUpdate');

    // Update route for users (PUT request).
    Route::put('/users/{id}', [UserController::class, 'update_role'])->name('users.update');

    // New admin route for managing items (using DataTables)
    Route::get('/items', [ItemController::class, 'index'])->name('admin.items');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Override the default home route if needed.
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Customer profile routes
Route::get('/customerprofile/create', [CustomerController::class, 'create'])
    ->name('customerprofile.create')
    ->middleware('auth');

Route::post('/customerprofile', [CustomerController::class, 'store'])
    ->name('customerprofile.store')
    ->middleware('auth');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Search route
Route::get('/search', [SearchController::class, 'search'])->name('search');

// New route: Show Customer Profile (used by Customer model's getSearchResult method)
Route::get('/customers/{customer}', [CustomerController::class, 'show'])
    ->name('customers.show')
    ->middleware('auth');

    Route::delete('items/{item_id}/images/{image_id}', [\App\Http\Controllers\ItemController::class, 'destroyImage'])
    ->name('items.images.destroy');
