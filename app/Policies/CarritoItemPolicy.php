<?php
//Esta Policy se encarga de que un usuario solo pueda modificar o eliminar sus propios productos del carrito, no los de otros usuarios.
//Sin esto, cualquier usuario que supiera el ID de un item del carrito de otro usuario podría eliminarlo o cambiar la cantidad simplemente haciendo una petición manual
namespace App\Policies;

use App\Models\CarritoItem;
use App\Models\User;

class CarritoItemPolicy
{
    public function update(User $user, CarritoItem $item): bool
    {
        return $user->id === $item->user_id;
    }

    public function delete(User $user, CarritoItem $item): bool
    {
        return $user->id === $item->user_id;
    }
}