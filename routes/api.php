<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;






Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group( function(){
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::post('/user', [UserController::class, 'store'])->name('user.post');
    Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update');
    Route::get('/user/{id}', [UserController::class, 'show'])->name('user.show');
    Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('user.destroy');
});
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('user.login');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('user.logout');

Route::middleware( ['auth:sanctum'])->group( function() {
    Route::get('/client', [ClientController::class, 'index'])->name('client.index');
    Route::post('/client', [ClientController::class,'store'])->name('client.store'); 
    Route::put('/client/{id}', [ClientController::class, 'update'])->name('client.update');
    Route::get('/client/{id}', [ClientController::class, 'show'])->name('client.show');
    Route::delete('/client/{id}', [ClientController::class, 'destroy'])->name('client.destroy');
});

Route::middleware(['auth:sanctum'])->group( function () {
    Route::get('/car', [CarController::class, 'index'])->name('car.index');
    Route::post('/car', [CarController::class, 'store'])->name('car.store');
    Route::put('/car/{id}', [CarController::class, 'update'])->name('car.update');
    Route::get('/car/{id}', [CarController::class,'show'])->name('car.show');
    Route::delete('/car/{id}', [CarController::class, 'destroy'])->name('car.destroy');
});

Route::middleware(['auth:sanctum'])->group( function () {
    Route::get('/brand', [BrandController::class, 'index'])->name('brand.index');
    Route::post('/brand', [BrandController::class, 'store'])->name('brand.store');
    Route::put('/brand/{id}', [BrandController::class, 'update'])->name('brand.update');
    Route::get('/brand/{id}', [BrandController::class, 'show'])->name('brand.show');
    Route::delete('/brand/{id}', [BrandController::class, 'destroy'])->name('brand.destroy');
});