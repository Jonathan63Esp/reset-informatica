<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo de atributo técnico.
 *
 * Define el nombre de un atributo técnico (ej: Socket, TDP, VRAM).
 * Los valores concretos de cada atributo por producto se almacenan
 * en la tabla atributo_valores a través del modelo AtributoValor.
 *
 * @property int $id
 * @property string $nombre Nombre del atributo (ej: Socket, TDP, VRAM)
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Atributo extends Model
{
    /** @var array<string> Campos asignables masivamente */
    protected $fillable = ['nombre'];

    /**
     * Valores de este atributo asignados a productos.
     *
     * @return HasMany
     */
    public function valores(): HasMany
    {
        return $this->hasMany(AtributoValor::class);
    }
}