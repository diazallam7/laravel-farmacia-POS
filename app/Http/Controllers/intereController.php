<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIntereRequest;
use App\Http\Requests\UpdateProductoRequest2;
use App\Models\Interes;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class intereController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    
    $producto = Producto::where('estado', 1)->get();
    return view('intere.index', compact('producto'));
}

    public function create()
{
    
    return view('intere.create');
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIntereRequest $request)
    {
       //
    }
    /**
     * Display the specified resource.
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $producto = Producto::find($id);
    
        if (!$producto) {
            return redirect()->route('producto.index')->withErrors('Producto no encontrado.');
        }
    
        return view('intere.edit', compact('producto'));
    }
    

    
    /**
     * Update the specified resource in storage.
     */
   



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $producto = Producto::find($id);
        if ($producto->estado == 1) {
            Producto::where('id', $producto->id)
                ->update([
                    'estado' => 2
                ]);
            }
        
        return redirect()->route('interes.index')->with('success', 'Pago Procesados');
    }

    public function mostrarInteres($id)
    {
        // Obtener el producto por su ID
        $producto = Producto::find($id);

        // Verificar si el producto existe
        if (!$producto) {
            return redirect()->back()->with('error', 'Producto no encontrado.');
        }

        // Calcular o recuperar el interés
        $interes = $producto->calcularInteres(); // Asumiendo que tienes un método para calcular el interés en el modelo

        // Retornar la vista con el interés
        return view('productos.interes', compact('producto', 'interes'));
    }

}
