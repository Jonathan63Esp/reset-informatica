<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carrito_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('producto_id')->constrained('productos')->cascadeOnDelete();
            $table->integer('cantidad')->default(1);
            // Si viene del configurador, guardamos el ID de sesión de configuración
            $table->string('configuracion_id')->nullable()->index();
            $table->timestamps();

            // Un usuario no puede tener el mismo producto dos veces en el carrito
            // (se actualiza la cantidad en su lugar)
            $table->unique(['user_id', 'producto_id', 'configuracion_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carrito_items');
    }
};
