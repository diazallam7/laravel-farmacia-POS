<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCompraRequest;
use App\Models\CierreCaja;
use App\Models\Compra;
use App\Models\Comprobante;
use App\Models\Producto;
use App\Models\Venta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;

class compraController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {

        return [

            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('ver-compra|crear-compra|mostrar-compra|eliminar-compra'), only: ['index']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('crear-compra'), only: ['create', 'store']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('mostrar-compra'), only: ['show']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('eliminar-compra'), only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $compras = Compra::with('comprobante')
            ->where('estado', 1)
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
        $venta = Venta::where('estado', 1)->get();
        return view('compra.create', compact('comprobantes', 'productos', 'venta'));
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
            $arrayVenta_id = $request->get('arrayidVenta');
            $arrayCantidad = $request->get('arrayCantidad');
            $arrayPrecioCompra = $request->get('arrayprecioCompra');
            $arrayPrecioVenta = $request->get('arrayprecioVenta');
            $cont = 0;

            if ($arrayProducto_id != '') {
                $siseArray = count($arrayProducto_id);
                while ($cont < $siseArray) {
                    $compra->productos()->syncWithoutDetaching([
                        $arrayProducto_id[$cont] => [
                            'cantidad' => $arrayCantidad[$cont],
                            'precio_compra' => $arrayPrecioCompra[$cont],
                            'precio_venta' => $arrayPrecioVenta[$cont],
                        ],
                    ]);

                    // Cambiar el estado del producto a 2 (vendido)
                    $producto = Producto::find($arrayProducto_id[$cont]);
                    $producto->estado = 2;
                    $producto->save();
                    $cont++;

                }
            } else {
                $siseArray = count($arrayVenta_id);
                while ($cont < $siseArray) {
                    $compra->ventas()->syncWithoutDetaching([
                        $arrayVenta_id[$cont] => [
                            'cantidad' => $arrayCantidad[$cont],
                            'precio_compra' => $arrayPrecioCompra[$cont],
                            'precio_venta' => $arrayPrecioVenta[$cont],
                        ],
                    ]);

                    // Cambiar el estado de la venta a 2 (vendido)
                    $venta = Venta::find($arrayVenta_id[$cont]);
                    $venta->estado = 0;
                    $venta->save();
                    $cont++;
                }
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
    $productos = collect();
    $ventas = collect();
    
    // Verifica si la compra tiene productos relacionados
    if ($compra->productos()->exists()) {
        $productos = $compra->productos()->select('nombre_del_producto')->get();
    }

    // Verifica si la compra tiene ventas relacionadas
    if ($compra->ventas()->exists()) {
        $ventas = $compra->ventas()->select('nombre_producto')->get();
    }
    
    // Combina productos y ventas en un solo array
    $items = $productos->merge($ventas);

    return view('compra.show', compact('compra', 'items'));
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
        Compra::where('id', $id)
            ->update([
                'estado' => 0,
            ]);

        return redirect()->route('compras.index')->with('success', 'Compra Eliminada');
    }

    public function cierre_caja(Request $request)
    {
        $productoTotal = session('producto_total', 0);

        // Buscar el último cierre de caja registrado
        $cierreHoy = CierreCaja::orderBy('created_at', 'desc')->first();

        // Calcular el tiempo desde el último cierre de caja
        $tiempoDesdeUltimoCierre = $cierreHoy ? Carbon::parse($cierreHoy->created_at)->diffInMinutes(Carbon::now()) : null;

        // Si no hay cierre de caja o si han pasado más de 10 minutos desde el último cierre
        if (!$cierreHoy || $tiempoDesdeUltimoCierre >= 2) {
            // Sumar las ventas de la tabla compras y el monto_interes de la tabla productos en los últimos 10 minutos
            $totalVentas = DB::table('compras')
                ->where('created_at', '>=', Carbon::now()->subMinutes(2))
                ->sum('total');

            $montoInteres = DB::table('productos')
                ->where('updated_at', '>=', Carbon::now()->subMinutes(2))
                ->sum('monto_interes');

            $totalVentas += $montoInteres + $productoTotal;

            // Sumar las compras de la tabla productos y de la tabla ventas en los últimos 2 minutos
            $totalComprasProductos = DB::table('productos')
                ->where('created_at', '>=', Carbon::now()->subMinutes(2))
                ->sum('precio_compra');

            $totalComprasVentas = DB::table('ventas')
                ->where('created_at', '>=', Carbon::now()->subMinutes(2))
                ->sum('precio_compra');

            $totalCompras = $totalComprasProductos + $totalComprasVentas;

            $montoExtra = $request->input('monto_extra', 0);

            // Crear un nuevo registro de cierre de caja
            $cierreHoy = CierreCaja::create([
                'total_ventas' => $totalVentas,
                'total_compras' => $totalCompras,
                'monto_extra' => $montoExtra,
            ]);

            session()->forget('producto_total');
        } else {
            // Si ya existe un cierre y no han pasado 10 minutos, solo actualizar el monto extra ingresado
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
