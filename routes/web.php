<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TableController;
use App\Http\Controllers\Waiter\OrderController;
use App\Http\Controllers\Cashier\BillingController;
use App\Http\Controllers\Kitchen\KitchenController;
use App\Http\Controllers\PublicMenuController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Kitchen\OrderController as KitchenOrderController;

Route::get('/', function () {

    if (! Auth::check()) {
        return redirect('/login');
    }

    return redirect('/dashboard');

});

Route::get('/dashboard', function () {

    $user = Auth::user();

    return match ($user->role) {
        'admin' => redirect('/admin/dashboard'),
        'waiter' => redirect('/waiter/dashboard'),
        'cashier' => redirect('/cashier/dashboard'),
        'kitchen_staff' => redirect('/kitchen'),
        default => redirect('/'),
    };

})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::get('/admin/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.dashboard');

Route::get('/admin/users', [UserController::class, 'index'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.users.index');

// Waiter Routes
Route::get('/waiter/dashboard', [OrderController::class, 'index'])
    ->middleware(['auth', 'role:waiter'])
    ->name('waiter.dashboard');

Route::get('/waiter/orders', [OrderController::class, 'index'])
    ->middleware(['auth', 'role:waiter'])
    ->name('waiter.orders.index');

Route::get('/waiter/orders/create', [OrderController::class, 'create'])
    ->middleware(['auth', 'role:waiter'])
    ->name('waiter.orders.create');

Route::post('/waiter/orders', [OrderController::class, 'store'])
    ->middleware(['auth', 'role:waiter'])
    ->name('waiter.orders.store');

Route::get('/waiter/orders/{order}', [OrderController::class, 'show'])
    ->middleware(['auth', 'role:waiter'])
    ->name('waiter.orders.show');

// Kitchen Routes
Route::get('/kitchen/orders', [KitchenOrderController::class, 'index'])
    ->middleware(['auth', 'role:kitchen_staff'])
    ->name('kitchen.orders.index');

Route::patch('/kitchen/orders/{order}/preparing', [KitchenOrderController::class, 'startPreparing'])
    ->middleware(['auth', 'role:kitchen_staff'])
    ->name('kitchen.orders.preparing');

Route::patch('/kitchen/orders/{order}/ready', [KitchenOrderController::class, 'markReady'])
    ->middleware(['auth', 'role:kitchen_staff'])
    ->name('kitchen.orders.ready');

// Cashier Routes
Route::get('/cashier/dashboard', [BillingController::class, 'index'])
    ->middleware(['auth', 'role:cashier'])
    ->name('cashier.dashboard');

Route::get('/kitchen', [KitchenController::class, 'index'])
    ->middleware(['auth', 'role:kitchen_staff'])
    ->name('kitchen.dashboard');

// User Management Routes
Route::get('/admin/users/create', [UserController::class, 'create'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.users.create');

Route::post('/admin/users', [UserController::class, 'store'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.users.store');

Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.users.edit');

Route::put('/admin/users/{user}', [UserController::class, 'update'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.users.update');

Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.users.destroy');

// Menu Item Routes
Route::get('/admin/menu-items', [MenuItemController::class, 'index'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.menu-items.index');

// Category Routes
Route::get('/admin/categories', [CategoryController::class, 'index'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.categories.index');

Route::get('/admin/categories/create', [CategoryController::class, 'create'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.categories.create');

Route::post('/admin/categories', [CategoryController::class, 'store'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.categories.store');

Route::get('/admin/categories/{category}/edit', [CategoryController::class, 'edit'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.categories.edit');

Route::put('/admin/categories/{category}', [CategoryController::class, 'update'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.categories.update');

Route::delete('/admin/categories/{category}', [CategoryController::class, 'destroy'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.categories.destroy');

// Menu Item Routes
Route::get('/admin/menu-items/create', [MenuItemController::class, 'create'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.menu-items.create');

Route::post('/admin/menu-items', [MenuItemController::class, 'store'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.menu-items.store');

// Edit and Delete routes for Menu Items
Route::get('/admin/menu-items/{menuItem}/edit', [MenuItemController::class, 'edit'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.menu-items.edit');

Route::put('/admin/menu-items/{menuItem}', [MenuItemController::class, 'update'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.menu-items.update');

Route::delete('/admin/menu-items/{menuItem}', [MenuItemController::class, 'destroy'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.menu-items.destroy');

// Restaurant Table Routes
Route::get('/admin/tables', [TableController::class, 'index'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.tables.index');

// Create, Edit, and Delete routes for Restaurant Tables
Route::get('/admin/tables/create', [TableController::class, 'create'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.tables.create');

Route::post('/admin/tables', [TableController::class, 'store'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.tables.store');

Route::get('/admin/tables/{restaurantTable}/edit', [TableController::class, 'edit'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.tables.edit');

Route::put('/admin/tables/{restaurantTable}', [TableController::class, 'update'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.tables.update');

Route::delete('/admin/tables/{restaurantTable}', [TableController::class, 'destroy'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.tables.destroy');

// Public Menu Route
Route::get('/scan/{token}', [PublicMenuController::class, 'index'])
    ->name('scan.menu');

require __DIR__.'/auth.php';