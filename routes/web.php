<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\categoriaController;
use App\Http\Controllers\clienteController;
use App\Http\Controllers\compraController;
use App\Http\Controllers\homeController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\logoutController;
use App\Http\Controllers\marcaController;
use App\Http\Controllers\presentacioneController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\profileController;
use App\Http\Controllers\proveedoreController;
use App\Http\Controllers\roleController;
use App\Http\Controllers\userController;
use App\Http\Controllers\ventaController;

Route::get('/',[homeController::class,'index'])->name('panel');


Route::view('/panel','panel.index')->name('panel');

Route::resource('categorias', categoriaController::class);
Route::resource('marcas', marcaController::class);
Route::resource('presentaciones', presentacioneController::class);
Route::resource('productos', ProductoController::class);
route::resource('clientes',clienteController::class);
route::resource('compras',compraController::class);
route::resource('proveedores',proveedoreController::class);
route::resource('ventas',ventaController::class);
route::resource('users',userController::class);
route::resource('roles',roleController::class);
route::resource('profiles',profileController::class);

Route::get('/login', [loginController::class, 'index'])->name('login');
Route::post('/login', [loginController::class, 'login']);
Route::get('/logout', [logoutController::class, 'logout'])->name('logout');

Route::get('/401', function () {
    return view('pages.401');
});

Route::get('/404', function () {
    return view('pages.404');
});

Route::get('/500', function () {
    return view('pages.500');
});

