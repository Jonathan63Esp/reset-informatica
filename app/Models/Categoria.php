<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Modelo de categoría de productos.
 *
 * Las categorías organizan el catálogo y determinan los pasos
 * del configurador de PC. El slug se genera automáticamente
 * a partir del nombre al crear la categoría.
 *
 * @property int $id
 * @property string $nombre Nombre de la categoría (ej: Procesadores)
 * @property string|null $slug URL amigable (ej: procesadores)
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Categoria extends Model
{
    /** @var array<string> Campos asignables masivamente */
    protected $fillable = ['nombre', 'slug'];

    /**
     * Genera el slug automáticamente al crear una categoría.
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::creating(function ($categoria) {
            if (empty($categoria->slug)) {
                $categoria->slug = Str::slug($categoria->nombre);
            }
        });
    }

    /**
     * Productos pertenecientes a esta categoría.
     *
     * @return HasMany
     */
    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class);
    }
}