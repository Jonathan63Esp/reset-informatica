<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo de línea de pedido.
 *
 * Representa un producto incluido en un pedido. Almacena el precio
 * unitario en el momento de la compra para preservar el histórico
 * aunque el precio del producto cambie posteriormente.
 *
 * @property int $id
 * @property int $pedido_id ID del pedido
 * @property int $producto_id ID del producto
 * @property int $cantidad Unidades pedidas
 * @property float $precio_unitario Precio unitario en el momento de la compra
 * @property string|null $configuracion_id UUID de la configuración de origen
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read float $subtotal Subtotal de la línea (precio_unitario × cantidad)
 */
class PedidoItem extends Model
{
    /** @var array<string> Campos asignables masivamente */
    protected $fillable = [
        'pedido_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'configuracion_id',
    ];

    /**
     * Pedido al que pertenece esta línea.
     *
     * @return BelongsTo
     */
    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class);
    }

    /**
     * Producto incluido en esta línea del pedido.
     *
     * @return BelongsTo
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    /**
     * Calcula el subtotal de la línea (precio_unitario × cantidad).
     *
     * @return float
     */
    public function getSubtotalAttribute(): float
    {
        return $this->cantidad * $this->precio_unitario;
    }
}