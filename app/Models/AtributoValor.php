<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo de valor de atributo técnico de un producto.
 *
 * Relaciona un producto con un atributo y almacena su valor concreto.
 * Por ejemplo: Ryzen 5 5600X → Socket → AM4.
 *
 * @property int $id
 * @property int $producto_id ID del producto
 * @property int $atributo_id ID del atributo
 * @property string $valor Valor del atributo (ej: AM4, 65W, 16GB)
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class AtributoValor extends Model
{
    /** @var string Nombre real de la tabla en la base de datos */
    protected $table = 'atributo_valores';

    /** @var array<string> Campos asignables masivamente */
    protected $fillable = ['producto_id', 'atributo_id', 'valor'];

    /**
     * Producto al que pertenece este valor de atributo.
     *
     * @return BelongsTo
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    /**
     * Atributo al que corresponde este valor.
     *
     * @return BelongsTo
     */
    public function atributo(): BelongsTo
    {
        return $this->belongsTo(Atributo::class);
    }
}