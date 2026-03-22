<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Controlador de gestión de categorías del panel de administración.
 *
 * Permite al administrador listar, crear, editar y eliminar categorías
 * del catálogo. El slug se genera automáticamente a partir del nombre.
 * No se puede eliminar una categoría que tenga productos asociados.
 *
 * Todas las rutas requieren autenticación y rol administrador
 * (middleware auth + esadmin).
 */
class CategoriaController extends Controller
{
    /**
     * Lista todas las categorías con el contador de productos.
     *
     * Usa withCount() para obtener el número de productos de cada
     * categoría en una sola consulta SQL.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $categorias = Categoria::withCount('productos')
            ->orderBy('nombre')
            ->get();

        return view('admin.categorias.index', compact('categorias'));
    }

    /**
     * Crea una nueva categoría.
     *
     * El nombre debe ser único. El slug se genera automáticamente
     * mediante Str::slug() a partir del nombre.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:categorias,nombre',
        ]);

        Categoria::create([
            'nombre' => $request->nombre,
            'slug'   => Str::slug($request->nombre),
        ]);

        return back()->with('success', "Categoría '{$request->nombre}' creada.");
    }

    /**
     * Actualiza el nombre y slug de una categoría existente.
     *
     * El nombre debe ser único excluyendo la propia categoría
     * que se está editando. El slug se regenera a partir del
     * nuevo nombre.
     *
     * @param Request $request
     * @param Categoria $categoria Categoría a actualizar
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Categoria $categoria)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:categorias,nombre,' . $categoria->id,
        ]);

        $categoria->update([
            'nombre' => $request->nombre,
            'slug'   => Str::slug($request->nombre),
        ]);

        return back()->with('success', "Categoría actualizada.");
    }

    /**
     * Elimina una categoría del sistema.
     *
     * Solo permite eliminar categorías sin productos asociados.
     * Si la categoría tiene productos, devuelve un error y no
     * realiza ninguna acción.
     *
     * @param Categoria $categoria Categoría a eliminar
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Categoria $categoria)
    {
        if ($categoria->productos()->count() > 0) {
            return back()->with('error', "No se puede eliminar '{$categoria->nombre}' porque tiene productos asociados.");
        }

        $nombre = $categoria->nombre;
        $categoria->delete();

        return back()->with('success', "Categoría '{$nombre}' eliminada.");
    }
}