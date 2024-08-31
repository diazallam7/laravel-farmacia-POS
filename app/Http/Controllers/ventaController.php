<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Http\Requests\ventaRequest;
use App\Models\Cliente;
use App\Models\Comprobante;
use App\Models\Producto;
use App\Models\Venta;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ventaController extends Controller implements HasMiddleware
{
    public static function middleware(): array {

        return [
            
          new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('ver-venta|crear-venta|editar-venta|eliminar-venta'),only:['index']),
         new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('crear-venta'), only:['create','store']),
         new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('mostrar-venta'),only:['show']),
         new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('eliminar-venta'), only:['destroy']),
        ];
     }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ventas = Venta::all();
        return view('venta.index', compact('ventas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('venta.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ventaRequest $request)
    {
        try {
            DB::beginTransaction();
            $venta = new Venta();

            $venta->fill([
                'codigo' => $request->codigo,
                'nombre_producto' => $request->nombre_producto,
                'precio_compra' => $request->precio_compra,
                'fecha_hora' => $request->fecha_hora,
                
            ]);
            $venta->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }


        return redirect()->route('ventas.index')->with('success', 'Compra Realizada');
    }

    /**
     * Display the specified resource.
     */
    public function show(Venta $venta)
    {
        return view('venta.show', compact('venta'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $message='';
        $venta = Venta::find($id);
        if ($venta->estado == 1) {
            Venta::where('id', $venta->id)
                ->update([
                    'estado' => 0
                ]);
                $message = 'Vendido';
        } else {
            Venta::where('id', $venta->id)
                ->update([
                    'estado' => 1
                ]);
                $message='Restaurar';
        }
        return redirect()->route('ventas.index')->with('success', $message);
    }
}