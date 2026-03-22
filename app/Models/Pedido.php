<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo de pedido.
 *
 * Representa la cabecera de un pedido realizado por un usuario,
 * incluyendo los datos de envío y el estado del mismo.
 * Los productos incluidos se almacenan en PedidoItem.
 *
 * @property int $id
 * @property int $user_id ID del usuario que realizó el pedido
 * @property string $numero Número único de pedido (RI-AAAA-NNNN)
 * @property string $estado Estado del pedido (pendiente/confirmado/enviado/entregado/cancelado)
 * @property float $total Importe total del pedido en euros
 * @property string $nombre_completo Nombre del destinatario
 * @property string $telefono Teléfono de contacto
 * @property string $direccion Dirección de envío
 * @property string $ciudad Ciudad de envío
 * @property string $codigo_postal Código postal
 * @property string $provincia Provincia
 * @property string $pais País de envío
 * @property string|null $notas Instrucciones adicionales
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read string $estado_badge Clase CSS del badge de estado
 * @property-read string $estado_label Etiqueta legible del estado con emoji
 */
class Pedido extends Model
{
    /** @var array<string> Campos asignables masivamente */
    protected $fillable = [
        'user_id', 'numero', 'estado', 'total',
        'nombre_completo', 'telefono', 'direccion',
        'ciudad', 'codigo_postal', 'provincia', 'pais', 'notas',
    ];

    /**
     * Usuario que realizó el pedido.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Items (productos) incluidos en el pedido.
     *
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(PedidoItem::class);
    }

    /**
     * Devuelve la clase CSS del badge según el estado del pedido.
     *
     * @return string Clase CSS (ej: badge-pendiente, badge-enviado)
     */
    public function getEstadoBadgeAttribute(): string
    {
        return match($this->estado) {
            'pendiente'   => 'badge-pendiente',
            'confirmado'  => 'badge-confirmado',
            'enviado'     => 'badge-enviado',
            'entregado'   => 'badge-entregado',
            'cancelado'   => 'badge-cancelado',
            default       => 'badge-pendiente',
        };
    }

    /**
     * Devuelve la etiqueta legible del estado con emoji.
     *
     * @return string Etiqueta con emoji (ej: "🚚 Enviado")
     */
    public function getEstadoLabelAttribute(): string
    {
        return match($this->estado) {
            'pendiente'   => '⏳ Pendiente',
            'confirmado'  => '✓ Confirmado',
            'enviado'     => '🚚 Enviado',
            'entregado'   => '✅ Entregado',
            'cancelado'   => '✕ Cancelado',
            default       => '⏳ Pendiente',
        };
    }

    /**
     * Genera un número de pedido único con formato RI-AAAA-NNNN.
     *
     * El número se compone del año actual y un contador secuencial
     * de 4 dígitos relleno con ceros a la izquierda.
     *
     * @return string Número de pedido (ej: RI-2026-0042)
     */
    public static function generarNumero(): string
    {
        $año    = date('Y');
        $ultimo = static::whereYear('created_at', $año)->count() + 1;
        return 'RI-' . $año . '-' . str_pad($ultimo, 4, '0', STR_PAD_LEFT);
    }
}