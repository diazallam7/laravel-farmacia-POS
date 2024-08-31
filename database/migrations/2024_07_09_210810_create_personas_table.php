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
        Schema::create('personas', function (Blueprint $table) {
            $table->id();
            $table->string('razon_social',45);  
            $table->string('direccion',45);
            $table->string('tipo_persona',45);
            $table->tinyInteger('estado')->default(1);
            $table->foreignId('documento_id')->unique()->constrained('documentos')->onDelete('cascade');
            $table->string('numero_celular',20)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};
