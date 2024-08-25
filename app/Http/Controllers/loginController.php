<?php

namespace App\Http\Controllers;

use App\Http\Requests\loginRequest;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class loginController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('panel');
        }
        return view('auth.login');
    }

    public function login(loginRequest $request)
    {
        
        if (!Auth::validate($request->only('email', 'password'))) {

            return redirect()->to('login')->withErrors('Credenciales Inconrrectas');
        }

        $user = Auth::getProvider()->retrieveByCredentials($request->only('email', 'password'));
        Auth::login($user);
        $productos = Producto::where('estado', 1)->get();

            //recorrer lista productos para verificar la fecha de entrada "fecha_vencimiento" y si ya pasaron 3 meses cambiar el estado a 0
            //Producto::Update  etc etc
            foreach ($productos as $producto) {
                $fechaVencimiento = new \Carbon\Carbon($producto->fecha_vencimiento);
                $diferenciaMeses = $fechaVencimiento->diffInMonths(now());

                if ($diferenciaMeses >= 3) {
                    $producto->estado = 0;
                    $producto->save();
                }
            }


        return redirect()
            ->route('panel')
            ->with('success', 'Bienvenido      ' . $user->name . '!!');
    }
}
