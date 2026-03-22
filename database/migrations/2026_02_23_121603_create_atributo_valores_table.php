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
       Schema::create('atributo_valores', function (Blueprint $table) {
    $table->id();

    $table->foreignId('producto_id')->constrained('productos')->cascadeOnDelete();
    $table->foreignId('atributo_id')->constrained('atributos')->cascadeOnDelete();

    $table->string('valor'); // AM5, 12GB, ATX, 2TB…

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atributo_valores');
    }
};
