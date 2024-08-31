<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;

    protected $fillable = ['fecha_hora','numero_comprobante','total', 'comprobante_id'];

    public function comprobante(){
        return $this->belongsTo(Comprobante::class);
    }

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'compra_producto', 'compra_id', 'producto_id')
                    ->withTimestamps()
                    ->withPivot('cantidad', 'precio_compra', 'precio_venta');
    }
    
    public function ventas()
    {
        return $this->belongsToMany(Venta::class, 'compra_venta', 'compra_id', 'venta_id')
                    ->withTimestamps()
                    ->withPivot('cantidad', 'precio_compra', 'precio_venta');
    }
    
}
