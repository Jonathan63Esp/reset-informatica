<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo de usuario del sistema.
 *
 * Representa tanto a usuarios normales como a administradores.
 * La autenticación se realiza por nombre de usuario (no por email).
 *
 * @property int $id
 * @property string $username Nombre de usuario único
 * @property string $password Contraseña cifrada con bcrypt
 * @property bool $is_admin Si el usuario es administrador
 * @property string|null $remember_token Token para "recordarme"
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /** @var array<string> Campos asignables masivamente */
    protected $fillable = [
        'username',
        'password',
        'is_admin',
    ];

    /** @var array<string> Campos ocultos en serialización */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /** @var array<string, string> Conversiones de tipos */
    protected $casts = [
        'is_admin' => 'boolean',
    ];

    /**
     * Items del carrito del usuario.
     *
     * @return HasMany
     */
    public function carritoItems(): HasMany
    {
        return $this->hasMany(CarritoItem::class);
    }

    /**
     * Pedidos realizados por el usuario.
     *
     * @return HasMany
     */
    public function pedidos(): HasMany
    {
        return $this->hasMany(Pedido::class);
    }

    /**
     * Comprueba si el usuario tiene rol de administrador.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->is_admin === true;
    }
}