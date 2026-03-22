<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;

/**
 * Controlador del buscador de productos.
 *
 * Gestiona la búsqueda de productos por nombre y descripción,
 * con filtros opcionales por categoría, precio máximo y ordenación.
 * La búsqueda requiere un mínimo de 2 caracteres para ejecutarse.
 */
class BusquedaController extends Controller
{
    /**
     * Muestra los resultados de búsqueda de productos.
     *
     * Busca en los campos nombre y descripción de los productos.
     * Permite filtrar los resultados por categoría y precio máximo,
     * y ordenarlos por nombre, precio ascendente o precio descendente.
     * Los resultados se paginan de 12 en 12 manteniendo los filtros.
     *
     * Si la consulta tiene menos de 2 caracteres, devuelve una
     * colección vacía sin realizar ninguna consulta a la base de datos.
     *
     * @param Request $request Parámetros de búsqueda:
     *   - q: Término de búsqueda (mínimo 2 caracteres)
     *   - categoria: ID de categoría para filtrar (opcional)
     *   - precio_max: Precio máximo en euros (opcional)
     *   - orden: Criterio de ordenación (nombre/precio-asc/precio-desc)
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $q = trim($request->input('q', ''));

        if (strlen($q) < 2) {
            return view('busqueda.resultados', [
                'productos'  => collect(),
                'query'      => $q,
                'total'      => 0,
                'categorias' => collect(),
            ]);
        }

        $productos = Producto::with(['categoria', 'atributoValores.atributo'])
            ->where(function ($query) use ($q) {
                $query->where('nombre', 'like', "%{$q}%")
                      ->orWhere('descripcion', 'like', "%{$q}%");
            })
            ->when($request->filled('categoria'), function ($query) use ($request) {
                $query->where('categoria_id', $request->categoria);
            })
            ->when($request->filled('precio_max'), function ($query) use ($request) {
                $query->where('precio', '<=', $request->precio_max);
            })
            ->when($request->filled('orden'), function ($query) use ($request) {
                match($request->orden) {
                    'precio-asc'  => $query->orderBy('precio', 'asc'),
                    'precio-desc' => $query->orderBy('precio', 'desc'),
                    default       => $query->orderBy('nombre', 'asc'),
                };
            }, function ($query) {
                $query->orderBy('nombre', 'asc');
            })
            ->paginate(12)->withQueryString();

        $categorias = Categoria::orderBy('nombre')->get();

        return view('busqueda.resultados', [
            'productos'  => $productos,
            'query'      => $q,
            'total'      => $productos->total(),
            'categorias' => $categorias,
        ]);
    }
}