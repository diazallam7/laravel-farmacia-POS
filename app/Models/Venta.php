<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    public function compras(){
        return $this->belongsToMany(Compra::class)->withTimestamps()->withPivot('cantidad','precio_compra','precio_venta');
    }

    protected $fillable = ['fecha_hora', 'precio_compra', 'nombre_producto', 'codigo'];
}
