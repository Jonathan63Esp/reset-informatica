<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;

/**
 * Controlador de gestión de pedidos del panel de administración.
 *
 * Permite al administrador listar todos los pedidos con filtros,
 * ver el detalle completo de cada pedido y cambiar su estado
 * a lo largo del ciclo de vida (pendiente → confirmado → enviado
 * → entregado, o cancelado en cualquier momento).
 *
 * Todas las rutas requieren autenticación y rol administrador
 * (middleware auth + esadmin).
 */
class PedidoController extends Controller
{
    /**
     * Lista todos los pedidos con filtros opcionales.
     *
     * Permite filtrar por estado del pedido y buscar por número
     * de pedido o nombre de usuario. Los resultados se paginan
     * de 20 en 20 manteniendo los parámetros de búsqueda.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Pedido::with('user')->orderByDesc('created_at');

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('buscar')) {
            $query->where(function ($q) use ($request) {
                $q->where('numero', 'like', '%' . $request->buscar . '%')
                  ->orWhereHas('user', fn($u) => $u->where('username', 'like', '%' . $request->buscar . '%'));
            });
        }

        $pedidos = $query->paginate(20)->withQueryString();

        return view('admin.pedidos.index', compact('pedidos'));
    }

    /**
     * Muestra el detalle completo de un pedido.
     *
     * Carga el pedido con todas sus relaciones mediante eager loading:
     * usuario, items con producto y categoría de cada producto.
     *
     * @param Pedido $pedido
     * @return \Illuminate\View\View
     */
    public function show(Pedido $pedido)
    {
        $pedido->load('user', 'items.producto.categoria');

        return view('admin.pedidos.show', compact('pedido'));
    }

    /**
     * Actualiza el estado de un pedido.
     *
     * Los estados válidos son: pendiente, confirmado, enviado,
     * entregado y cancelado. El cambio se refleja inmediatamente
     * en el historial de pedidos del usuario.
     *
     * @param Request $request
     * @param Pedido $pedido
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cambiarEstado(Request $request, Pedido $pedido)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,confirmado,enviado,entregado,cancelado',
        ]);

        $pedido->update(['estado' => $request->estado]);

        return back()->with('success', "Estado del pedido {$pedido->numero} actualizado a '{$request->estado}'.");
    }
}