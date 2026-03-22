<?php

namespace App\Http\Controllers;

use App\Models\CarritoItem;
use App\Models\Pedido;
use App\Models\PedidoItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Controlador de pedidos del usuario.
 *
 * Gestiona el proceso de checkout (formulario de envío y confirmación),
 * el historial de pedidos, el detalle de cada pedido y la generación
 * de facturas en formato PDF mediante barryvdh/laravel-dompdf.
 *
 * Todas las rutas requieren autenticación (middleware auth).
 */
class PedidoController extends Controller
{
    /**
     * Muestra el formulario de datos de envío (checkout).
     *
     * Redirige al carrito si está vacío.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function checkout()
    {
        $items = CarritoItem::where('user_id', Auth::id())
            ->with('producto')
            ->get();

        if ($items->isEmpty()) {
            return redirect()->route('carrito.index')
                ->with('error', 'Tu carrito está vacío.');
        }

        $total = $items->sum(fn($i) => $i->cantidad * $i->producto->precio);

        return view('pedidos.checkout', compact('items', 'total'));
    }

    /**
     * Procesa la confirmación del pedido.
     *
     * Valida los datos de envío, crea el pedido y sus items en una
     * transacción de base de datos para garantizar la integridad.
     * Vacía el carrito al finalizar y redirige a la página de
     * confirmación con el número de pedido generado.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirmar(Request $request)
    {
        $request->validate([
            'nombre_completo' => 'required|string|max:100',
            'telefono'        => 'required|string|max:20',
            'direccion'       => 'required|string|max:200',
            'ciudad'          => 'required|string|max:100',
            'codigo_postal'   => 'required|string|max:10',
            'provincia'       => 'required|string|max:100',
            'pais'            => 'required|string|max:100',
            'notas'           => 'nullable|string|max:500',
        ], [
            'nombre_completo.required' => 'El nombre completo es obligatorio.',
            'telefono.required'        => 'El teléfono es obligatorio.',
            'direccion.required'       => 'La dirección es obligatoria.',
            'ciudad.required'          => 'La ciudad es obligatoria.',
            'codigo_postal.required'   => 'El código postal es obligatorio.',
            'provincia.required'       => 'La provincia es obligatoria.',
        ]);

        $items = CarritoItem::where('user_id', Auth::id())
            ->with('producto')
            ->get();

        if ($items->isEmpty()) {
            return redirect()->route('carrito.index')
                ->with('error', 'Tu carrito está vacío.');
        }

        $total = $items->sum(fn($i) => $i->cantidad * $i->producto->precio);

        DB::transaction(function () use ($request, $items, $total, &$pedido) {
            $pedido = Pedido::create([
                'user_id'         => Auth::id(),
                'numero'          => Pedido::generarNumero(),
                'estado'          => 'pendiente',
                'total'           => $total,
                'nombre_completo' => $request->nombre_completo,
                'telefono'        => $request->telefono,
                'direccion'       => $request->direccion,
                'ciudad'          => $request->ciudad,
                'codigo_postal'   => $request->codigo_postal,
                'provincia'       => $request->provincia,
                'pais'            => $request->pais,
                'notas'           => $request->notas,
            ]);

            foreach ($items as $item) {
                PedidoItem::create([
                    'pedido_id'        => $pedido->id,
                    'producto_id'      => $item->producto_id,
                    'cantidad'         => $item->cantidad,
                    'precio_unitario'  => $item->producto->precio,
                    'configuracion_id' => $item->configuracion_id,
                ]);
            }

            CarritoItem::where('user_id', Auth::id())->delete();
        });

        return redirect()->route('pedidos.gracias', $pedido)
            ->with('success', '¡Pedido realizado correctamente!');
    }

    /**
     * Muestra la página de confirmación tras realizar el pedido.
     *
     * Verifica que el pedido pertenece al usuario autenticado.
     *
     * @param Pedido $pedido
     * @return \Illuminate\View\View
     */
    public function gracias(Pedido $pedido)
    {
        if ($pedido->user_id !== Auth::id()) {
            abort(403);
        }

        $pedido->load('items.producto');

        return view('pedidos.gracias', compact('pedido'));
    }

    /**
     * Muestra el historial de pedidos del usuario autenticado.
     *
     * Los pedidos se ordenan por fecha descendente y se paginan
     * de 10 en 10.
     *
     * @return \Illuminate\View\View
     */
    public function historial()
    {
        $pedidos = Pedido::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->with('items')
            ->paginate(10);

        return view('pedidos.historial', compact('pedidos'));
    }

    /**
     * Muestra el detalle de un pedido.
     *
     * Verifica que el pedido pertenece al usuario autenticado.
     * Carga los items con producto y categoría mediante eager loading.
     *
     * @param Pedido $pedido
     * @return \Illuminate\View\View
     */
    public function show(Pedido $pedido)
    {
        if ($pedido->user_id !== Auth::id()) {
            abort(403);
        }

        $pedido->load('items.producto.categoria');

        return view('pedidos.show', compact('pedido'));
    }

    /**
     * Genera y descarga la factura del pedido en formato PDF.
     *
     * Utiliza barryvdh/laravel-dompdf para convertir la vista
     * pedidos/factura en un PDF descargable.
     * Verifica que el pedido pertenece al usuario autenticado.
     *
     * @param Pedido $pedido
     * @return \Illuminate\Http\Response
     */
    public function factura(Pedido $pedido)
    {
        if ($pedido->user_id !== Auth::id()) {
            abort(403);
        }

        $pedido->load('items.producto.categoria', 'user');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pedidos.factura', compact('pedido'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('factura-' . $pedido->numero . '.pdf');
    }
}