<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;



class Producto extends Model
{
    use HasFactory;

    public function compras(){
        return $this->belongsToMany(Compra::class)->withTimestamps()->withPivot('cantidad','precio_compra','precio_venta');
    }

    public function ventas(){
        return $this->belongsToMany(Venta::class)->withTimestamps()->withPivot('cantidad','descuento','precio_venta');
    }

    public function categorias(){
        return $this->belongsToMany(Categoria::class)->withTimestamps();
    }

    public function marca(){
        return $this->belongsTo(Marca::class);
    }

    public function interes()
{
    return $this->hasOne(Interes::class);
}

public function calcularInteres()
    {
        // Supongamos que tienes una columna 'precio_compra' en tu tabla productos
        // Y calculas el interés como 25% del precio de compra
        return $this->precio_compra * 0.25;
    }


    protected $fillable = ['codigo','nombre','precio_compra','descripcion','fecha_vencimiento','marca_id','img_path','numero_celular','nombre_del_producto','cedula','monto_interes'];

    public function hableUploadImage($image){
        $file = $image;
        $name = time() . $file->getClientOriginalName();
        //$file->move(public_path().'/img/productos/',$name);
        Storage::putFileAs('public/productos/',$file,$name,'public');

        return $name;
    }
}



