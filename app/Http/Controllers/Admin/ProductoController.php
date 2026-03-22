<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Atributo;
use App\Models\AtributoValor;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $query = Producto::with('categoria');

        if ($request->filled('buscar')) {
            $query->where('nombre', 'like', '%' . $request->buscar . '%');
        }

        if ($request->filled('categoria')) {
            $query->where('categoria_id', $request->categoria);
        }

        $orden     = $request->input('orden', 'nombre');
$direccion = $request->input('dir', 'asc');

$columnasPermitidas = ['nombre', 'precio', 'stock', 'categoria_id'];
if (!in_array($orden, $columnasPermitidas)) $orden = 'nombre';
if (!in_array($direccion, ['asc', 'desc'])) $direccion = 'asc';

$productos = $query->orderBy($orden, $direccion)->paginate(20)->withQueryString();
        $categorias = Categoria::orderBy('nombre')->get();

        return view('admin.productos.index', compact('productos', 'categorias'));
    }

    public function create()
    {
        $categorias = Categoria::orderBy('nombre')->get();
        $atributos  = Atributo::orderBy('nombre')->get();
        return view('admin.productos.form', compact('categorias', 'atributos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'       => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'descripcion'  => 'nullable|string',
            'precio'       => 'required|numeric|min:0',
            'stock'        => 'required|integer|min:0',
            'imagen'       => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $producto = Producto::create($data);

        $this->guardarAtributos($producto, $request);

        return redirect()->route('admin.productos.index')
            ->with('success', "Producto \"{$producto->nombre}\" creado correctamente.");
    }

    public function edit(Producto $producto)
    {
        $categorias = Categoria::orderBy('nombre')->get();
        $atributos  = Atributo::orderBy('nombre')->get();
        $producto->load('atributoValores.atributo');
        return view('admin.productos.form', compact('producto', 'categorias', 'atributos'));
    }

    public function update(Request $request, Producto $producto)
    {
        $data = $request->validate([
            'nombre'       => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'descripcion'  => 'nullable|string',
            'precio'       => 'required|numeric|min:0',
            'stock'        => 'required|integer|min:0',
            'imagen'       => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('imagen')) {
            if ($producto->imagen) {
                Storage::disk('public')->delete($producto->imagen);
            }
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        if ($request->boolean('eliminar_imagen') && $producto->imagen) {
            Storage::disk('public')->delete($producto->imagen);
            $data['imagen'] = null;
        }

        $producto->update($data);

        $this->guardarAtributos($producto, $request);

        return redirect()->route('admin.productos.index')
            ->with('success', "Producto \"{$producto->nombre}\" actualizado correctamente.");
    }

    public function destroy(Producto $producto)
    {
        if ($producto->imagen) {
            Storage::disk('public')->delete($producto->imagen);
        }
        $nombre = $producto->nombre;
        $producto->delete();

        return redirect()->route('admin.productos.index')
            ->with('success', "Producto \"{$nombre}\" eliminado.");
    }

    private function guardarAtributos(Producto $producto, Request $request): void
    {
        $producto->atributoValores()->delete();

        $atributoIds = $request->input('atributo_ids', []);
        $valores     = $request->input('atributo_valores', []);

        foreach ($atributoIds as $i => $atributoId) {
            $valor = trim($valores[$i] ?? '');
            if ($atributoId && $valor !== '') {
                AtributoValor::create([
                    'producto_id' => $producto->id,
                    'atributo_id' => $atributoId,
                    'valor'       => $valor,
                ]);
            }
        }
    }
}