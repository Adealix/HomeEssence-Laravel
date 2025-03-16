<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomerProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ReviewController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will be
| assigned to the "web" middleware group. Make something great!
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

// Admin routes protected by auth, verified, and admin middleware
Route::prefix('admin')->middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/users', [DashboardController::class, 'getUsers'])->name('admin.users');
    Route::get('/orders', [DashboardController::class, 'getOrders'])->name('admin.orders');
    Route::get('/order/{id}', [OrderController::class, 'processOrder'])->name('admin.orderDetails');
    Route::post('/order/{id}', [OrderController::class, 'orderUpdate'])->name('admin.orderUpdate');

    // Update route for users (PUT request)
    Route::put('/users/{id}', [UserController::class, 'update_role'])->name('users.update');

    // New admin route for managing items (using DataTables)
    Route::get('/items', [ItemController::class, 'index'])->name('admin.items');
});

// Dashboard route protected by auth and verified middleware
Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard.index');

// Enable authentication routes with email verification enabled
Auth::routes(['verify' => true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Override the default home route if needed.
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Customer profile routes for creation (only for authenticated and verified users)
Route::get('/customerprofile/create', [CustomerController::class, 'create'])
    ->name('customerprofile.create')
    ->middleware(['auth', 'verified']);

Route::post('/customerprofile', [CustomerController::class, 'store'])
    ->name('customerprofile.store')
    ->middleware(['auth', 'verified']);

// New routes for editing and updating the customer profile.
// These routes will use the same form for editing the profile.
Route::get('/customerprofile/edit', [CustomerController::class, 'edit'])
    ->name('customerprofile.edit')
    ->middleware(['auth', 'verified']);

Route::put('/customerprofile', [CustomerController::class, 'update'])
    ->name('customerprofile.update')
    ->middleware(['auth', 'verified']);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Search route
Route::get('/search', [SearchController::class, 'search'])->name('search');

// New route: Show Customer Profile (used by Customer model's getSearchResult method)
Route::get('/customers/{customer}', [CustomerController::class, 'show'])
    ->name('customers.show')
    ->middleware('auth');

// Route to delete a product image
Route::delete('items/{item_id}/images/{image_id}', [\App\Http\Controllers\ItemController::class, 'destroyImage'])
    ->name('items.images.destroy');

// Routes for product reviews (only for authenticated users)
Route::middleware('auth')->group(function () {
    // Store a new review for a product
    Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    
    // Update an existing review for a product
    Route::put('/products/{product}/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    
    // Delete a review (user or admin)
    Route::delete('/products/{product}/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    
    // Display reviews for a product
    Route::get('/products/{product}/reviews', [ReviewController::class, 'index'])->name('reviews.index');

    // Display products that the user can review
    Route::get('/reviewable-products', [ReviewController::class, 'reviewableProducts'])->name('reviews.reviewable_products');
});