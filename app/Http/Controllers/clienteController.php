<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Http\Requests\StorePersonaRequest;
use App\Http\Requests\UpdateClienteRequest;
use App\Models\Cliente;
use App\Models\Documento;
use App\Models\Persona;
use Illuminate\Support\Facades\DB;

class clienteController extends Controller implements HasMiddleware
{
    public static function middleware(): array {

        return [
            
          new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('ver-cliente|crear-cliente|editar-cliente|eliminar-cliente'),only:['index']),
         new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('crear-cliente'), only:['create','store']),
         new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('editar-cliente'),only:['edit','update']),
         new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('eliminar-cliente'), only:['destroy']),
        ];
     }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clientes = Cliente::with('persona.documento')->get();
        return view('cliente.index', compact('clientes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $documentos = Documento::all();
        return view('cliente.create', compact('documentos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePersonaRequest $request)
    {
        try {

            DB::beginTransaction();
            $persona = Persona::create($request->validated());
            $persona->cliente()->create([
                'persona_id' => $persona->id,
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
        return redirect()->route('clientes.index')->with('success', 'Cliente Registrado');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cliente $cliente)
    {
        $cliente->load('persona.documento');
        $documentos = Documento::all();
        return view('cliente.edit', compact('cliente', 'documentos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClienteRequest $request, Cliente $cliente)
    {
        try {
            DB::beginTransaction();

            Persona::where('id',$cliente->persona->id)
            ->update($request->validated());

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
        return redirect()->route('clientes.index')->with('success', 'Cliente Modificado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $message='';
        $persona = Persona::find($id);
        if ($persona->estado == 1) {
            Persona::where('id', $persona->id)
                ->update([
                    'estado' => 0
                ]);
                $message = 'Cliente Eliminado';
        } else {
            Persona::where('id', $persona->id)
                ->update([
                    'estado' => 1
                ]);
                $message='Cliente Restaurado';
        }
        return redirect()->route('clientes.index')->with('success', $message);
    }
}
