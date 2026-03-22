<?php

namespace App\Http\Controllers;

use App\Models\CarritoItem;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Http\Controllers\ConfiguradorController;

/**
 * Controlador del carrito de la compra.
 *
 * Gestiona todas las operaciones del carrito: añadir productos
 * individuales o configuraciones completas, actualizar cantidades,
 * eliminar items y vaciar el carrito.
 *
 * Todas las rutas requieren autenticación (middleware auth).
 */
class CarritoController extends Controller
{
    /**
     * Muestra el carrito del usuario autenticado.
     *
     * Carga los items con sus productos y categorías mediante
     * eager loading para evitar el problema N+1.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $items = CarritoItem::where('user_id', Auth::id())
            ->with('producto.categoria')
            ->get();

        $total = $items->sum(fn($i) => $i->cantidad * $i->producto->precio);

        return view('carrito.index', compact('items', 'total'));
    }

    /**
     * Añade un producto individual al carrito.
     *
     * Si el producto ya existe en el carrito con el mismo
     * configuracion_id, incrementa la cantidad. Si no, crea
     * un nuevo item.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function añadir(Request $request)
    {
        $request->validate([
            'producto_id'      => 'required|exists:productos,id',
            'cantidad'         => 'integer|min:1|max:99',
            'configuracion_id' => 'nullable|string|max:64',
        ]);

        $producto = Producto::findOrFail($request->producto_id);

        if (!$producto->enStock()) {
            return back()->with('error', "'{$producto->nombre}' no tiene stock disponible.");
        }

        $item = CarritoItem::where([
            'user_id'          => Auth::id(),
            'producto_id'      => $request->producto_id,
            'configuracion_id' => $request->configuracion_id,
        ])->first();

        if ($item) {
            $item->increment('cantidad', $request->cantidad ?? 1);
        } else {
            CarritoItem::create([
                'user_id'          => Auth::id(),
                'producto_id'      => $request->producto_id,
                'configuracion_id' => $request->configuracion_id,
                'cantidad'         => $request->cantidad ?? 1,
            ]);
        }

        return back()->with('success', "'{$producto->nombre}' añadido al carrito.");
    }

    /**
     * Añade una configuración completa del configurador al carrito.
     *
     * Todos los productos de la configuración comparten el mismo
     * configuracion_id (UUID) para poder identificarlos como grupo.
     * Los productos sin stock se omiten y se notifica al usuario.
     * Los IDs especiales (0 para disipador de caja, -1 para iGPU)
     * se ignoran ya que no son productos reales.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function añadirConfiguracion(Request $request)
    {
        $request->validate([
            'productos'   => 'required|array',
            'productos.*' => 'integer',
        ]);

        $configId = Str::uuid()->toString();
        $añadidos = 0;
        $sinStock  = [];

        foreach ($request->productos as $productoId) {
            if ($productoId <= 0) continue;

            $producto = Producto::find($productoId);
            if (!$producto) continue;

            if (!$producto->enStock()) {
                $sinStock[] = $producto->nombre;
                continue;
            }

            CarritoItem::updateOrCreate(
                [
                    'user_id'          => Auth::id(),
                    'producto_id'      => $productoId,
                    'configuracion_id' => $configId,
                ],
                ['cantidad' => 1]
            );
            $añadidos++;
        }

        $request->session()->forget(ConfiguradorController::SESSION_KEY);

        $mensaje = "{$añadidos} componente(s) añadidos al carrito.";
        if (!empty($sinStock)) {
            $mensaje .= ' Sin stock: ' . implode(', ', $sinStock) . '.';
        }

        return redirect()->route('carrito.index')->with('success', $mensaje);
    }

    /**
     * Actualiza la cantidad de un item del carrito.
     *
     * Verifica que el item pertenece al usuario autenticado
     * antes de actualizar para evitar modificaciones no autorizadas.
     *
     * @param Request $request
     * @param CarritoItem $item Item del carrito a actualizar
     * @return \Illuminate\Http\RedirectResponse
     */
    public function actualizarCantidad(Request $request, CarritoItem $item)
    {
        if ($item->user_id !== Auth::id()) abort(403);

        $request->validate(['cantidad' => 'required|integer|min:1|max:99']);
        $item->update(['cantidad' => $request->cantidad]);

        return back()->with('success', 'Cantidad actualizada.');
    }

    /**
     * Elimina un item del carrito.
     *
     * Verifica que el item pertenece al usuario autenticado
     * antes de eliminar.
     *
     * @param CarritoItem $item Item del carrito a eliminar
     * @return \Illuminate\Http\RedirectResponse
     */
    public function eliminar(CarritoItem $item)
    {
        if ($item->user_id !== Auth::id()) abort(403);

        $item->delete();

        return back()->with('success', 'Producto eliminado del carrito.');
    }

    /**
     * Vacía completamente el carrito del usuario autenticado.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function vaciar(Request $request)
    {
        CarritoItem::where('user_id', Auth::id())->delete();

        return back()->with('success', 'Carrito vaciado.');
    }
}