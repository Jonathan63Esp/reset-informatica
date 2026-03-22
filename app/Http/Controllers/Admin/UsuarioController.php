<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador de gestión de usuarios del panel de administración.
 *
 * Permite al administrador listar todos los usuarios con filtros,
 * cambiar el rol de un usuario (normal ↔ administrador) y eliminar
 * usuarios. El administrador no puede modificar ni eliminar su
 * propia cuenta desde este panel.
 *
 * Todas las rutas requieren autenticación y rol administrador
 * (middleware auth + esadmin).
 */
class UsuarioController extends Controller
{
    /**
     * Lista todos los usuarios con filtros opcionales.
     *
     * Permite filtrar por tipo de usuario (admin/usuario) y buscar
     * por nombre de usuario. Los resultados se paginan de 20 en 20
     * manteniendo los parámetros de búsqueda.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = User::orderByDesc('created_at');

        if ($request->filled('buscar')) {
            $query->where('username', 'like', '%' . $request->buscar . '%');
        }

        if ($request->filled('tipo')) {
            $query->where('is_admin', $request->tipo === 'admin' ? 1 : 0);
        }

        $usuarios = $query->paginate(20)->withQueryString();

        return view('admin.usuarios.index', compact('usuarios'));
    }

    /**
     * Cambia el rol del usuario entre normal y administrador.
     *
     * Si el usuario era normal pasa a administrador y viceversa.
     * No permite modificar la propia cuenta del administrador
     * autenticado para evitar que se quite sus propios permisos.
     *
     * @param User $usuario Usuario a modificar
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleAdmin(User $usuario)
    {
        if ($usuario->id === Auth::id()) {
            return back()->with('error', 'No puedes modificar tu propio rol.');
        }

        $usuario->update(['is_admin' => !$usuario->is_admin]);

        $rol = $usuario->is_admin ? 'administrador' : 'usuario';

        return back()->with('success', "{$usuario->username} ahora es {$rol}.");
    }

    /**
     * Elimina un usuario y todos sus datos asociados.
     *
     * No permite eliminar la propia cuenta del administrador
     * autenticado. La eliminación en cascada borra también
     * los items del carrito y los pedidos del usuario.
     *
     * @param User $usuario Usuario a eliminar
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $usuario)
    {
        if ($usuario->id === Auth::id()) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $nombre = $usuario->username;
        $usuario->delete();

        return back()->with('success', "Usuario {$nombre} eliminado.");
    }
}