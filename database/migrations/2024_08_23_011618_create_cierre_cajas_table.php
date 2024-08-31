<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_create_cierre_cajas_table.php

public function up()
{
    Schema::create('cierre_cajas', function (Blueprint $table) {
        $table->id();
        $table->decimal('total_ventas', 10, 2);
        $table->decimal('total_compras', 10, 2);
        $table->decimal('monto_extra', 10, 2)->default(0);
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cierre_cajas');
    }
};
