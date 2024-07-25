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
        $ventas = Venta::with(['comprobante','cliente.persona','user'])
        ->where('estado',1)
        ->latest()
        ->get();

        return view('venta.index', compact('ventas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $subquery = DB::table('compra_producto')
        ->select('producto_id', DB::raw('MAX(created_at) as max_created_at'))
        ->groupBy('producto_id');

        $productos= Producto::join('compra_producto as cpr', function($join) use ($subquery){
            $join->on('cpr.producto_id', '=', 'productos.id')
            ->whereIn('cpr.created_at', function($query) use ($subquery){
                $query->select('max_created_at')
                ->fromSub($subquery, 'subquery')
                ->whereRaw('subquery.producto_id = cpr.producto_id');
            });
        })
        ->select('productos.nombre', 'productos.id', 'productos.stock', 'cpr.precio_venta')
        ->where('productos.estado', 1)
        ->where('productos.stock','>',0)
        ->get();

        $clientes = Cliente::whereHas('persona', function ($query){
            $query->where('estado', 1);
        })->get();
        $comprobantes = Comprobante::all();


        return view('venta.create',compact('productos', 'clientes', 'comprobantes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ventaRequest $request)
    {
        try {
            DB::beginTransaction();
            
            $venta = Venta::create($request->validated());

            $arrayProducto_id = $request->get('arrayidProducto');
            $arrayCantidad = $request->get('arrayCantidad');
            $arrayprecio_venta = $request->get('arrayprecioVenta');
            $arraydescuento = $request->get('arrayDescuento');

            $siseArray = count($arrayProducto_id);
            $cont = 0;

            while($cont < $siseArray){
                $venta->productos()->syncWithoutDetaching([
                    $arrayProducto_id[$cont] => [
                          'cantidad' => $arrayCantidad[$cont],
                          'precio_venta' => $arrayprecio_venta[$cont],
                          'descuento' => $arraydescuento[$cont]
                    ]
                ]);

                $producto = Producto::find($arrayProducto_id[$cont]);
                $stockActual = $producto->stock;
                $cantidad = intval($arrayCantidad[$cont]);

                DB::table('productos')
                ->where('id', $producto->id)
                ->update([
                    'stock' => $stockActual - $cantidad
                ]);
                $cont++;
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return redirect()->route('ventas.index')->with('success', 'Venta Realizada');
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
        Venta::where('id',$id)
        ->update([
            'estado'=> 0
        ]);

        return redirect()->route('ventas.index')->with('success', 'Venta Eliminada');
    }
}