<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo de item del carrito de la compra.
 *
 * Representa un producto añadido al carrito por un usuario.
 * El campo configuracion_id agrupa los productos de una misma
 * configuración del configurador de PC (UUID compartido).
 *
 * @property int $id
 * @property int $user_id ID del usuario propietario
 * @property int $producto_id ID del producto
 * @property int $cantidad Unidades en el carrito
 * @property string|null $configuracion_id UUID de la configuración de origen
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read float $subtotal Precio total del item (precio × cantidad)
 */
class CarritoItem extends Model
{
    /** @var array<string> Campos asignables masivamente */
    protected $fillable = [
        'user_id',
        'producto_id',
        'cantidad',
        'configuracion_id',
    ];

    /**
     * Usuario propietario del item.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Producto asociado al item del carrito.
     *
     * @return BelongsTo
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    /**
     * Calcula el subtotal del item (precio unitario × cantidad).
     *
     * @return float
     */
    public function getSubtotalAttribute(): float
    {
        return $this->cantidad * $this->producto->precio;
    }
}