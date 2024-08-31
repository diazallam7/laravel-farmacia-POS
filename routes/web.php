<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\categoriaController;
use App\Http\Controllers\clienteController;
use App\Http\Controllers\compraController;
use App\Http\Controllers\homeController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\logoutController;
use App\Http\Controllers\marcaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\profileController;
use App\Http\Controllers\roleController;
use App\Http\Controllers\userController;
use App\Http\Controllers\ventaController;
use App\Http\Controllers\intereController;
use App\Models\Persona;

Route::get('/',[homeController::class,'index'])->name('panel');


Route::view('/panel','panel.index')->name('panel');

Route::resource('categorias', categoriaController::class);
Route::resource('marcas', marcaController::class);
Route::resource('productos', ProductoController::class);
route::resource('clientes',clienteController::class);
route::resource('compras',compraController::class);
route::resource('ventas',ventaController::class);
Route::resource('interes', intereController::class);
route::resource('users',userController::class);
route::resource('roles',roleController::class);
route::resource('profiles',profileController::class);
// web.php
Route::put('/productos/{producto}/update2', [ProductoController::class, 'update2'])->name('productos.update2');
Route::delete('/productos/{id}/destroy', [ProductoController::class, 'destroya'])->name('productos.destroya');
Route::get('/caja', [compraController::class, 'cierre_caja'])->name('compras.cierre_caja');
Route::post('/productos/{id}/showModal', [ProductoController::class, 'showModal'])->name('productos.showModal');


Route::get('/buscar-persona', function (Illuminate\Http\Request $request) {
    $cedula = $request->query('cedula');
    $persona = Persona::where('numero_documento', $cedula)->first();

    if ($persona) {
        return response()->json([
            'nombre' => $persona->razon_social,
            'descripcion' => $persona->direccion,
            'numero_celular' => $persona->numero_celular,
        ]);
    } else {
        return response()->json(null);
    }
});



Route::get('/forbidden', [loginController::class, 'index'])->name('login');



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

