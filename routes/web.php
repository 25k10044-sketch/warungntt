<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MenuController as AdminMenuController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/auth/google', [GoogleController::class, 'redirect'])
    ->middleware('guest')
    ->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])
    ->middleware('guest')
    ->name('auth.google.callback');

Route::get('/', function (MenuController $menus) {
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    return $menus->home();
})
    ->middleware('auth')
    ->name('home');
Route::get('/menu', [MenuController::class, 'index'])->name('menus.index');
Route::get('/menu/{menu:slug}', [MenuController::class, 'show'])->name('menus.show');
Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
Route::post('/keranjang/{menu}/items', [CartController::class, 'add'])->name('cart.add');
Route::patch('/keranjang', [CartController::class, 'update'])->name('cart.update');
Route::delete('/keranjang/{menu}', [CartController::class, 'remove'])->name('cart.remove');

Route::get('/dashboard', function () {
    return auth()->user()->role === 'admin'
        ? redirect()->route('admin.dashboard')
        : redirect()->route('home');
})->middleware('auth')->name('dashboard');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', AdminDashboardController::class)->name('dashboard');
    Route::resource('menus', AdminMenuController::class)->except('show');
    Route::resource('categories', AdminCategoryController::class)->except(['create', 'show', 'edit']);
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/role', [AdminUserController::class, 'updateRole'])->name('users.role');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
});

Route::middleware(['auth', 'role:pelanggan'])->group(function () {
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout.index');
    Route::post('/checkout', [CartController::class, 'placeOrder'])->name('checkout.store');
    Route::get('/pesanan', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/pesanan/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/menu/{menu}/ulasan', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/favorit', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/menu/{menu}/favorit', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
