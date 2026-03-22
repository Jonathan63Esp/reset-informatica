<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\Categoria;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categorias', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('nombre');
        });

        // Generar slugs para categorías existentes
        foreach (Categoria::all() as $categoria) {
            $categoria->slug = Str::slug($categoria->nombre);
            $categoria->save();
        }
    }

    public function down(): void
    {
        Schema::table('categorias', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};