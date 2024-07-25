<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedore extends Model
{
    use HasFactory;

    public function persona(){
        return $this->belongsTo(Persona::class);
    }

    public function compras(){
        return $this->hasMany(Compra::class);
    }
    public function proveedore(){
        return $this->belongsTo(Proveedore::class);
    }

    protected $fillable = ['persona_id'];
}
