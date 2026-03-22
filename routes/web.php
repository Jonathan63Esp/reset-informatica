<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ConfiguradorController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\BusquedaController;
use Illuminate\Support\Facades\Route;

// ── Públicas ──────────────────────────────────────────────
Route::get('/', fn() => view('pages.home'))->name('home');
Route::get('/buscar', [BusquedaController::class, 'index'])->name('buscar');
Route::get('/sobre-nosotros', fn() => view('pages.sobre-nosotros'))->name('sobre-nosotros');

// ── Auth ──────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',     [AuthController::class, 'showLogin'])   ->name('login');
    Route::post('/login',    [AuthController::class, 'login']);
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::get('/perfil',           [AuthController::class, 'showPerfil'])      ->name('perfil');
    Route::post('/perfil/password', [AuthController::class, 'cambiarPassword']) ->name('perfil.password');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ── Configurador ──────────────────────────────────────────
Route::prefix('configurador')->name('configurador.')->group(function () {
    Route::get('/',             [ConfiguradorController::class, 'plataforma'])  ->name('plataforma');
    Route::get('/pasos',        [ConfiguradorController::class, 'index'])       ->name('index');
    Route::post('/seleccionar', [ConfiguradorController::class, 'seleccionar']) ->name('seleccionar');
    Route::post('/omitir',      [ConfiguradorController::class, 'omitir'])      ->name('omitir');
    Route::get('/resumen',      [ConfiguradorController::class, 'resumen'])     ->name('resumen');
    Route::post('/reiniciar',   [ConfiguradorController::class, 'reiniciar'])   ->name('reiniciar');
    Route::get('/exportar',     [ConfiguradorController::class, 'exportar'])    ->name('exportar');
    Route::post('/montaje', [ConfiguradorController::class, 'seleccionarMontaje'])->name('seleccionarMontaje');
});

// ── Catálogo ──────────────────────────────────────────────
Route::get('/categorias/{slug}', [CatalogoController::class, 'categoria'])->name('catalogo.categoria');

// ── Carrito ───────────────────────────────────────────────
Route::middleware('auth')->prefix('carrito')->name('carrito.')->group(function () {
    Route::get('/',                      [CarritoController::class, 'index'])               ->name('index');
    Route::post('/anadir',               [CarritoController::class, 'añadir'])              ->name('anadir');
    Route::post('/anadir-configuracion', [CarritoController::class, 'añadirConfiguracion']) ->name('anadir-configuracion');
    Route::patch('/{item}/cantidad',     [CarritoController::class, 'actualizarCantidad'])  ->name('actualizar-cantidad');
    Route::delete('/{item}',             [CarritoController::class, 'eliminar'])            ->name('eliminar');
    Route::delete('/',                   [CarritoController::class, 'vaciar'])              ->name('vaciar');
});

// ── Pedidos ───────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/checkout',                    [PedidoController::class, 'checkout']) ->name('checkout');
    Route::post('/checkout',                   [PedidoController::class, 'confirmar'])->name('checkout.confirmar');
    Route::get('/pedidos',                     [PedidoController::class, 'historial'])->name('pedidos.historial');
    Route::get('/pedidos/{pedido}',            [PedidoController::class, 'show'])     ->name('pedidos.show');
    Route::get('/pedidos/{pedido}/gracias',    [PedidoController::class, 'gracias'])  ->name('pedidos.gracias');
    Route::get('/pedidos/{pedido}/factura', [PedidoController::class, 'factura'])->name('pedidos.factura');
});

// ── Admin ─────────────────────────────────────────────────
Route::middleware(['auth', 'esadmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Productos
    Route::resource('productos', \App\Http\Controllers\Admin\ProductoController::class);

    // Pedidos
    Route::get('/pedidos',                          [\App\Http\Controllers\Admin\PedidoController::class, 'index'])        ->name('pedidos.index');
    Route::get('/pedidos/{pedido}',                 [\App\Http\Controllers\Admin\PedidoController::class, 'show'])         ->name('pedidos.show');
    Route::patch('/pedidos/{pedido}/estado',        [\App\Http\Controllers\Admin\PedidoController::class, 'cambiarEstado'])->name('pedidos.estado');

    // Usuarios
    Route::get('/usuarios',                         [\App\Http\Controllers\Admin\UsuarioController::class, 'index'])       ->name('usuarios.index');
    Route::patch('/usuarios/{usuario}/toggle-admin',[\App\Http\Controllers\Admin\UsuarioController::class, 'toggleAdmin']) ->name('usuarios.toggle-admin');
    Route::delete('/usuarios/{usuario}',            [\App\Http\Controllers\Admin\UsuarioController::class, 'destroy'])     ->name('usuarios.destroy');

    // Categorías
    Route::get('/categorias',                       [\App\Http\Controllers\Admin\CategoriaController::class, 'index'])    ->name('categorias.index');
    Route::post('/categorias',                      [\App\Http\Controllers\Admin\CategoriaController::class, 'store'])    ->name('categorias.store');
    Route::patch('/categorias/{categoria}',         [\App\Http\Controllers\Admin\CategoriaController::class, 'update'])   ->name('categorias.update');
    Route::delete('/categorias/{categoria}',        [\App\Http\Controllers\Admin\CategoriaController::class, 'destroy'])  ->name('categorias.destroy');
});