<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Http\Requests\StoreCompraRequest;
use App\Http\Controllers\UserController;
use App\Models\CierreCaja;
use App\Models\Compra;
use App\Models\Comprobante;
use App\Models\Producto;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  

class compraController extends Controller implements HasMiddleware
{

    public static function middleware(): array {

        return [
            
          new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('ver-compra|crear-compra|mostrar-compra|eliminar-compra'),only:['index']),
         new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('crear-compra'), only:['create','store']),
         new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('mostrar-compra'),only:['show']),
         new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('eliminar-compra'), only:['destroy']),
        ];
     }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $compras = Compra::with('comprobante')
        ->where('estado',1)
        ->latest()
        ->get();
        return view('compra.index', compact('compras'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $comprobantes = Comprobante::all();
        $productos = Producto::where('estado', 0)->get();
        return view('compra.create', compact('comprobantes', 'productos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompraRequest $request)
    {
        try {
            DB::beginTransaction();
            $compra = Compra::create($request->validated());
            $arrayProducto_id = $request->get('arrayidProducto');
            $arrayCantidad = $request->get('arrayCantidad');
            $arrayPrecioCompra = $request->get('arrayprecioCompra');
            $arrayPrecioVenta = $request->get('arrayprecioVenta');

            $siseArray = count($arrayProducto_id);
            $cont = 0;
            while($cont < $siseArray ){
                $compra->productos()->syncWithoutDetaching([
                $arrayProducto_id[$cont] => [
                    'cantidad' => $arrayCantidad[$cont],
                    'precio_compra' => $arrayPrecioCompra[$cont],
                    'precio_venta' => $arrayPrecioVenta[$cont]
                ]
                ]);

                  //stok
            $producto = Producto::find($arrayProducto_id[$cont]);
            $stockActual = $producto->stock;
            $stockNuevo = intval($arrayCantidad[$cont]);

            DB::table('productos')
            ->where('id', $producto->id)
            ->update([
                'stock' => $stockActual + $stockNuevo
            ]);

            $cont++; 
            }


            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return redirect()->route('compras.index')->with('success', 'Compra Exitosa');
    }

    /**
     * Display the specified resource.
     */
    public function show(Compra $compra)
    {
        return view('compra.show', compact('compra'));
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
        Compra::where('id',$id)
        ->update([
            'estado'=> 0
        ]);

        return redirect()->route('compras.index')->with('success', 'Compra Eliminada');
    }
    
    public function cierre_caja(Request $request)

    {
        $productoTotal = session('producto_total', 0);
        // Obtener el cierre de caja de los últimos 5 minutos, si existe
        $cierreHoy = CierreCaja::where('created_at', '>=', Carbon::now()->subMinutes(2))->first();
    
        if (!$cierreHoy) {
            // Si no hay un cierre registrado en los últimos 5 minutos, calcular y crear uno nuevo
            $totalVentas = DB::table('compras')
                ->where('created_at', '>=', Carbon::now()->subMinutes(2))
                ->sum('total');
    
            // Sumar el monto_interes a las ventas totales
            $montoInteres = DB::table('productos')
                ->where('created_at', '>=', Carbon::now()->subMinutes(2))
                ->sum('monto_interes');

                $totalVentas += $montoInteres + $productoTotal;

    
            // Sumar solo las compras realizadas en los últimos 5 minutos
            $totalCompras = DB::table('productos')
                ->where('created_at', '>=', Carbon::now()->subMinutes(2))
                ->sum('precio_compra');
    
            $montoExtra = $request->input('monto_extra', 0);
            
            $cierreHoy = CierreCaja::create([
                
                'total_ventas' => $totalVentas,
                'total_compras' => $totalCompras,
                'monto_extra' => $montoExtra,
            ]);
            session()->forget('producto_total');
        } else {
            // Si ya existe un cierre, actualizar el monto extra ingresado
            $montoExtra = $request->input('monto_extra', 0);
            $cierreHoy->monto_extra = $montoExtra;
            $cierreHoy->save();
        }
    
        $totalComprasConExtra = $cierreHoy->total_compras + $cierreHoy->monto_extra;
        
    
        return view('compra.cierre_caja', [
            'totalVentas' => $cierreHoy->total_ventas,
            'totalCompras' => $cierreHoy->total_compras,
            'montoExtra' => $cierreHoy->monto_extra,
            'totalComprasConExtra' => $totalComprasConExtra,
        ]);
    }
    
}
