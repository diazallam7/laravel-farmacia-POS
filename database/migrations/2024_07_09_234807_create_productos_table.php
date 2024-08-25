<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo',45);
            $table->string('nombre',45);
            $table->decimal('precio_compra',10,0,true);
            $table->integer('stock')->unsigned()->default(0);
            $table->string('descripcion',255)->nullable();
            $table->date('fecha_vencimiento')->nullable();
            $table->string('img_path',255)->nullable();
            $table->tinyInteger('estado')->default(1);
            $table->foreignId('marca_id')->constrained('marcas')->onDelete('cascade');
            $table->string('numero_celular')->nullable();
            $table->string('nombre_del_producto')->nullable();
            $table->decimal('monto_interes',10,0,true)->nullable();
            $table->string('cedula')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
