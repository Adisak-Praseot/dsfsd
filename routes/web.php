<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoomController;

<<<<<<< HEAD

=======
>>>>>>> 1b2b43d9c610656a4dc770bb2016fbb0f80417f5
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

<<<<<<< HEAD
=======
Route::get('products', [ProductController::class, 'index']); // แสดงรายการสินค้าจาก ProductController

>>>>>>> 1b2b43d9c610656a4dc770bb2016fbb0f80417f5
Route::get('/rooms', [RoomController::class, 'index'])->middleware(['auth', 'verified'])->name("rooms.index");

Route::get('/rooms/create', [RoomController::class, 'create'])->middleware(['auth', 'verified'])->name('rooms.create');

Route::post('/bookings', [RoomController::class, 'store']);

Route::get('/rooms/{id}/edit', [RoomController::class, 'edit'])->name('rooms.edit');

Route::put('/rooms/{id}', [RoomController::class, 'update'])->name('rooms.update');

Route::delete('/rooms/{id}', [RoomController::class, 'destroy'])->name('rooms.destroy');

<<<<<<< HEAD
Route::get('/rooms/available', [RoomController::class, 'availableRooms'])->name('rooms.available');

Route::get('/dashboard', function () {
=======

Route::get('/dashboard', function () {  
>>>>>>> 1b2b43d9c610656a4dc770bb2016fbb0f80417f5
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
