<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Controlador del dashboard del panel de administración.
 *
 * Calcula y muestra las métricas principales de la tienda:
 * pedidos, ingresos, usuarios y productos. También genera
 * los datos para el gráfico de ventas de los últimos 7 días
 * y el ranking de productos más vendidos.
 */
class DashboardController extends Controller
{
    /**
     * Muestra el dashboard del panel de administración.
     *
     * Calcula las siguientes métricas:
     * - Total de pedidos y pedidos del día actual
     * - Ingresos totales e ingresos del día actual
     * - Pedidos pendientes de gestionar
     * - Total de productos y usuarios registrados
     * - Ventas de los últimos 7 días para el gráfico
     * - Top 5 productos más vendidos por unidades
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $totalPedidos      = Pedido::count();
        $pedidosHoy        = Pedido::whereDate('created_at', today())->count();
        $ingresoTotal      = Pedido::whereNotIn('estado', ['cancelado'])->sum('total');
        $ingresoHoy        = Pedido::whereDate('created_at', today())->whereNotIn('estado', ['cancelado'])->sum('total');
        $totalProductos    = Producto::count();
        $totalUsuarios     = User::count();
        $pedidosPendientes = Pedido::where('estado', 'pendiente')->count();

        $pedidosRecientes = Pedido::with('user')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // Datos para el gráfico de barras de los últimos 7 días
        $ventasSemana = collect(range(6, 0))->map(function ($dias) {
            $fecha = now()->subDays($dias);
            return [
                'fecha'   => $fecha->format('d/m'),
                'total'   => Pedido::whereDate('created_at', $fecha)
                    ->whereNotIn('estado', ['cancelado'])
                    ->sum('total'),
                'pedidos' => Pedido::whereDate('created_at', $fecha)->count(),
            ];
        });

        // Top 5 productos más vendidos
        $productosTop = DB::table('pedido_items')
            ->join('productos', 'pedido_items.producto_id', '=', 'productos.id')
            ->select(
                'productos.nombre',
                DB::raw('SUM(pedido_items.cantidad) as total_vendido'),
                DB::raw('SUM(pedido_items.cantidad * pedido_items.precio_unitario) as ingresos')
            )
            ->groupBy('productos.id', 'productos.nombre')
            ->orderByDesc('total_vendido')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalPedidos', 'pedidosHoy', 'ingresoTotal', 'ingresoHoy',
            'totalProductos', 'totalUsuarios', 'pedidosPendientes',
            'pedidosRecientes', 'ventasSemana', 'productosTop'
        ));
    }
}