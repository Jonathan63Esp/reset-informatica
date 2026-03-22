<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo de configuración del sistema.
 *
 * Almacena parámetros configurables de la tienda como el precio
 * del servicio de montaje, email de contacto, etc. Proporciona
 * métodos estáticos para acceder y modificar los parámetros
 * sin necesidad de instanciar el modelo.
 *
 * Parámetros disponibles:
 * - precio_montaje: Precio del servicio de montaje en euros
 * - montaje_activo: Si el servicio de montaje está disponible (1/0)
 * - envio_gratis_desde: Importe mínimo para envío gratuito
 * - email_contacto: Email de contacto de la tienda
 * - telefono_tienda: Teléfono de atención al cliente
 *
 * @property int $id
 * @property string $clave Identificador único del parámetro
 * @property string $valor Valor del parámetro
 * @property string|null $descripcion Descripción legible del parámetro
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Configuracion extends Model
{
    /** @var string Nombre real de la tabla en la base de datos */
    protected $table = 'configuraciones';

    /** @var array<string> Campos asignables masivamente */
    protected $fillable = ['clave', 'valor', 'descripcion'];

    /**
     * Obtiene el valor de un parámetro del sistema.
     *
     * @param string $clave Identificador del parámetro
     * @param string|null $default Valor por defecto si no existe
     * @return string Valor del parámetro o el valor por defecto
     */
    public static function get(string $clave, $default = null): string
    {
        $config = static::where('clave', $clave)->first();
        return $config ? $config->valor : $default;
    }

    /**
     * Crea o actualiza un parámetro del sistema.
     *
     * @param string $clave Identificador del parámetro
     * @param string $valor Nuevo valor del parámetro
     * @return void
     */
    public static function set(string $clave, string $valor): void
    {
        static::updateOrCreate(
            ['clave' => $clave],
            ['valor' => $valor]
        );
    }
}