<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Waiter\OrderController;
use App\Http\Controllers\Cashier\BillingController;
use App\Http\Controllers\Kitchen\KitchenController;
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


Route::get('/admin/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.dashboard');

    Route::get('/admin/users', [UserController::class, 'index'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.users.index');

Route::get('/waiter/dashboard', [OrderController::class, 'index'])
    ->middleware(['auth', 'role:waiter'])
    ->name('waiter.dashboard');

Route::get('/cashier/dashboard', [BillingController::class, 'index'])
    ->middleware(['auth', 'role:cashier'])
    ->name('cashier.dashboard');

Route::get('/kitchen', [KitchenController::class, 'index'])
    ->middleware(['auth', 'role:kitchen_staff'])
    ->name('kitchen.dashboard');



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


require __DIR__.'/auth.php';
