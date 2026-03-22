<?php

//Es el punto de arranque de tu aplicación. Cada vez que Laravel inicia, pasa por este archivo y ejecuta el método boot().
//"Oye, cuando alguien intente hacer algo con un CarritoItem, usa CarritoItemPolicy para decidir si tiene permiso o no."

namespace App\Providers;

use App\Models\CarritoItem;
use App\Policies\CarritoItemPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(CarritoItem::class, CarritoItemPolicy::class);
    }
}