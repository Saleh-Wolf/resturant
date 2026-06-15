<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SubcategoryController;
use App\Http\Controllers\Admin\TableController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Waiter\OrderController;
//use App\Http\Controllers\Cashier\BillingController;
use App\Http\Controllers\Cashier\OrderController as CashierOrderController;
 use App\Http\Controllers\Kitchen\KitchenController;
use App\Http\Controllers\PublicMenuController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\MenuItemIngredientController;
use App\Http\Controllers\Kitchen\OrderController as KitchenOrderController;
use App\Http\Controllers\Admin\OfferController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\IngredientController;
use App\Http\Controllers\Cashier\BillController;

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


        // Menu Item Ingredient Routes

Route::get('/admin/menu-items/{menuItem}/ingredients',
    [MenuItemIngredientController::class, 'edit'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.menu-items.ingredients.edit');

Route::put('/admin/menu-items/{menuItem}/ingredients',
    [MenuItemIngredientController::class, 'update'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.menu-items.ingredients.update');

        //offers routes
Route::get('/admin/offers', [OfferController::class, 'index'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.offers.index');

Route::get('/admin/offers/create', [OfferController::class, 'create'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.offers.create');

Route::post('/admin/offers', [OfferController::class, 'store'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.offers.store');
    

    Route::get('/admin/offers/{offer}/edit', [OfferController::class, 'edit'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.offers.edit');

Route::put('/admin/offers/{offer}', [OfferController::class, 'update'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.offers.update');

Route::delete('/admin/offers/{offer}', [OfferController::class, 'destroy'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.offers.destroy');

// Section Routes

Route::get('/admin/sections', [SectionController::class, 'index'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.sections.index');

Route::get('/admin/sections/create', [SectionController::class, 'create'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.sections.create');


    Route::post('/admin/sections', [SectionController::class, 'store'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.sections.store');


    Route::get('/admin/sections/{section}/edit', [SectionController::class, 'edit'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.sections.edit');

Route::put('/admin/sections/{section}', [SectionController::class, 'update'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.sections.update');

Route::delete('/admin/sections/{section}', [SectionController::class, 'destroy'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.sections.destroy');






    Route::get('/admin/reports/sales', [ReportController::class, 'sales'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.reports.sales');

    Route::get('/admin/reports/orders', [ReportController::class, 'orders'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.reports.orders');



    Route::get('/admin/reports/reservations', [ReportController::class, 'reservations'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.reports.reservations');



    Route::get('/admin/reports/top-selling-items', [ReportController::class, 'topSellingItems'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.reports.top-selling-items');


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

    Route::get('/kitchen', function () {
    return redirect()->route('kitchen.orders.index');
})
    ->middleware(['auth', 'role:kitchen_staff'])
    ->name('kitchen.dashboard');

// Cashier Routes
Route::get('/cashier/dashboard', function () {
    return redirect()->route('cashier.orders.index');
})
    ->middleware(['auth', 'role:cashier'])
    ->name('cashier.dashboard');



// Cashier Orders Routes
Route::get('/cashier/orders', [CashierOrderController::class, 'index'])
    ->middleware(['auth', 'role:cashier'])
    ->name('cashier.orders.index');

Route::patch('/cashier/orders/{order}/complete', [CashierOrderController::class, 'complete'])
    ->middleware(['auth', 'role:cashier'])
    ->name('cashier.orders.complete');

// Cashier Order History Route

Route::get('/cashier/orders/history', [CashierOrderController::class, 'history'])
    ->middleware(['auth', 'role:cashier'])
    ->name('cashier.orders.history');

        // Cashier Bills Routes

//     Route::get('/cashier/bills', [BillingController::class, 'index'])
//     ->middleware(['auth', 'role:cashier'])
//     ->name('cashier.bills.index');
// Route::get('/cashier/bills/{bill}', [BillingController::class, 'show'])
//     ->middleware(['auth', 'role:cashier'])
//     ->name('cashier.bills.show');

                // Admin Bill Routes
    Route::get('/cashier/bills', [BillController::class, 'index'])
    ->middleware(['auth', 'role:cashier'])
    ->name('cashier.bills.index');

Route::get('/cashier/bills/{bill}', [BillController::class, 'show'])
    ->middleware(['auth', 'role:cashier'])
    ->name('cashier.bills.show');


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



                // Reservation Routes
    Route::get('/admin/reservations', [ReservationController::class, 'index'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.reservations.index');

Route::get('/admin/reservations/create', [ReservationController::class, 'create'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.reservations.create');

            // Store, Confirm, Cancel, Edit, Update, and Delete routes for Reservations
    Route::post('/admin/reservations', [ReservationController::class, 'store'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.reservations.store');
    
    Route::patch('/admin/reservations/{reservation}/confirm',
    [ReservationController::class, 'confirm'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.reservations.confirm');

Route::patch('/admin/reservations/{reservation}/cancel',
    [ReservationController::class, 'cancel'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.reservations.cancel');


Route::patch('/admin/reservations/{reservation}/arrived',
    [ReservationController::class, 'arrived'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.reservations.arrived');

Route::patch('/admin/reservations/{reservation}/no-show',
    [ReservationController::class, 'noShow'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.reservations.no-show');



    Route::get('/admin/reservations/{reservation}/edit', [ReservationController::class, 'edit'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.reservations.edit');

Route::put('/admin/reservations/{reservation}', [ReservationController::class, 'update'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.reservations.update');

Route::delete('/admin/reservations/{reservation}', [ReservationController::class, 'destroy'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.reservations.destroy');


    // QR Code Route for Restaurant Tables

    Route::get('/admin/tables/{table}/qr', [TableController::class, 'qr'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.tables.qr');



            // Subcategory Routes
    Route::get('/admin/subcategories', [SubcategoryController::class, 'index'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.subcategories.index');

Route::get('/admin/subcategories/create', [SubcategoryController::class, 'create'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.subcategories.create');


    Route::post('/admin/subcategories', [SubcategoryController::class, 'store'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.subcategories.store');

    Route::get('/admin/subcategories/{subcategory}/edit', [SubcategoryController::class, 'edit'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.subcategories.edit');

Route::put('/admin/subcategories/{subcategory}', [SubcategoryController::class, 'update'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.subcategories.update');

Route::delete('/admin/subcategories/{subcategory}', [SubcategoryController::class, 'destroy'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.subcategories.destroy');

            // Ingredient Routes
Route::resource('admin/ingredients', IngredientController::class)
    ->middleware(['auth', 'role:admin'])
    ->names('admin.ingredients');



            //stock report route
        
    Route::get('/admin/reports/low-stock',
    [ReportController::class, 'lowStock'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.reports.low-stock');


    Route::get('/admin/reports/stock-movements',
    [ReportController::class, 'stockMovements'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.reports.stock-movements');

            // Ingredient Restock Routes

    Route::get('/admin/ingredients/{ingredient}/restock',
    [IngredientController::class, 'restock'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.ingredients.restock');

    Route::post('/admin/ingredients/{ingredient}/restock',
    [IngredientController::class, 'storeRestock'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.ingredients.store-restock');

require __DIR__ . '/auth.php';
