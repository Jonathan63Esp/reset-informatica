<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

/**
 * Modelo de producto del catálogo.
 *
 * Representa cualquier componente de ordenador o servicio
 * disponible en la tienda. Los atributos técnicos (Socket,
 * TDP, VRAM, etc.) se almacenan en la tabla atributo_valores.
 *
 * @property int $id
 * @property int $categoria_id ID de la categoría
 * @property string $nombre Nombre del producto
 * @property string|null $descripcion Descripción detallada
 * @property float $precio Precio en euros
 * @property int $stock Unidades disponibles
 * @property string|null $imagen Ruta relativa de la imagen
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read string $imagen_url URL pública de la imagen
 * @property-read array $compatibilidad Resultado de compatibilidad del configurador
 */
class Producto extends Model
{
    /** @var array<string> Campos asignables masivamente */
    protected $fillable = [
        'categoria_id',
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'imagen',
    ];

    /**
     * Categoría a la que pertenece el producto.
     *
     * @return BelongsTo
     */
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    /**
     * Valores de atributos técnicos del producto.
     *
     * @return HasMany
     */
    public function atributoValores(): HasMany
    {
        return $this->hasMany(AtributoValor::class);
    }

    /**
     * Comprueba si el producto tiene stock disponible.
     *
     * @return bool True si stock > 0
     */
    public function enStock(): bool
    {
        return $this->stock > 0;
    }

    /**
     * Genera la URL pública de la imagen del producto.
     *
     * @return string URL completa de la imagen o cadena vacía si no tiene
     */
    public function getImagenUrlAttribute(): string
    {
        return $this->imagen ? Storage::url($this->imagen) : '';
    }
}