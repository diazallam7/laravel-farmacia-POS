<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $producto = $this->route('producto');
        return [
            'codigo'=> 'required|unique:productos,codigo,'.$producto->id.'|max:45',
            'nombre'=> 'required',
            'precio_compra' => 'required',
            'descripcion'=> 'nullable|max:255',
            'fecha_vencimiento'=> 'nullable|date',
            'image_path'=> 'nullable|image|mimes:png,jpg,jpeg,|max:2048',
            'marca_id'=> 'required|integer|exists:marcas,id',
            'categorias'=> 'required',
            'numero_celular' => 'nullable',
            'cedula'=> 'nullable',
            'nombre_del_producto' => 'required',
            'monto_interes' => 'nullable'

        ];
    }
    public function attributes(){
        return[
            'marca_id'=> 'marca',
        ];
    }
    public function messages(){
        return[
            'codigo.required'=> 'Se necesita un campo codigo',
            'nombre.required'=> 'Se necesita un nombre',
            'marca_id.required'=> 'Se necesita una marca',
            'categorias.required'=> 'Se necesita al menos una categoria'
        ];
    }
}
