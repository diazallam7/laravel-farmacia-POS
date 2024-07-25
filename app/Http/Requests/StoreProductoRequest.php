<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductoRequest extends FormRequest
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
        return [
            'codigo'=> 'required|unique:productos,codigo|max:45',
            'nombre'=> 'required|unique:productos,nombre|max:45',
            'descripcion'=> 'nullable|max:255',
            'fecha_vencimiento'=> 'nullable|date',
            'image_path'=> 'nullable|image|mimes:png,jpg,jpeg,|max:2048',
            'marca_id'=> 'required|integer|exists:marcas,id',
            'presentacione_id'=> 'required|integer|exists:presentaciones,id',
            'categorias'=> 'required'
        ];
    }
    public function attributes(){
        return[
            'marca_id'=> 'marca',
            'presentacione_id'=> 'presentacion'
        ];
    }
    public function messages(){
        return[
            'codigo.required'=> 'Se necesita un campo codigo',
            'nombre.required'=> 'Se necesita un nombre',
            'marca_id.required'=> 'Se necesita una marca',
            'presentacione_id.required'=> 'Se necesita una presentacion',
            'categorias.required'=> 'Se necesita al menos una categoria'
        ];
    }
}
