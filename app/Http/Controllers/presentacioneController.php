<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Http\Requests\StorePresentacioneRequest;
use App\Http\Requests\UpdatePresentacioneRequest;
use Illuminate\Http\Request;
use App\Models\Caracteristica;
use App\Models\Presentacione;
use Illuminate\Support\Facades\DB;

class presentacioneController extends Controller implements HasMiddleware
{


    public static function middleware(): array {

        return [
            
          new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('ver-presentacione|crear-presentacione|editar-presentacione|eliminar-presentacione'),only:['index']),
         new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('crear-presentacione'), only:['create','store']),
         new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('editar-presentacione'),only:['edit','update']),
         new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('eliminar-presentacione'), only:['destroy']),
        ];
     }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $presentaciones = Presentacione::with('caracteristica')->latest()->get();
        return view('presentacione.index', ['presentaciones' => $presentaciones]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('presentacione.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePresentacioneRequest $request)
    {
        try {
            DB::beginTransaction();
            $caracteristica = Caracteristica::create($request->validated());
            $caracteristica->presentacione()->create([
                'caracteristica_id' => $caracteristica->id
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return redirect()->route('presentaciones.index')->with('success', 'Presentacion Registrada');
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
    public function edit(Presentacione $presentacione)
    {
        return view('presentacione.edit', ['presentacione' => $presentacione]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePresentacioneRequest $request, Presentacione $presentacione)
    {
        Caracteristica::where('id', $presentacione->caracteristica->id)
            ->update($request->validated());
        return redirect()->route('presentaciones.index')->with('success', 'Presentacion Editada');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $message='';
        $presentacione = Presentacione::find($id);
        if ($presentacione->caracteristica->estado == 1) {
            Caracteristica::where('id', $presentacione->caracteristica->id)
                ->update([
                    'estado' => 0
                ]);
                $message = 'Presentacion Eliminada';
        } else {
            Caracteristica::where('id', $presentacione->caracteristica->id)
                ->update([
                    'estado' => 1
                ]);
                $message='Presentacion Restaurada';
        }
        return redirect()->route('presentaciones.index')->with('success', $message);
    }
}
