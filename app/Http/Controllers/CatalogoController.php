<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\Request;

class CatalogoController extends Controller
{
    public function categoria(Request $request, string $slug)
    {
        $categoria = Categoria::where('slug', $slug)->firstOrFail();

        $query = Producto::where('categoria_id', $categoria->id)
            ->with(['atributoValores.atributo']);

        // Ordenación
        $orden     = $request->input('orden', 'nombre');
        $direccion = $request->input('dir', 'asc');
        $columnasPermitidas = ['nombre', 'precio', 'stock'];
        if (!in_array($orden, $columnasPermitidas)) $orden = 'nombre';
        if (!in_array($direccion, ['asc', 'desc'])) $direccion = 'asc';

        $productos  = $query->orderBy($orden, $direccion)->paginate(12)->withQueryString();
        $categorias = Categoria::orderByRaw("FIELD(nombre,
            'Procesadores','Placas base','Memoria RAM','Tarjetas gráficas',
            'Fuentes de alimentación','Almacenamiento','Refrigeración','Cajas')"
        )->get();

        return view('catalogo.categoria', compact('categoria', 'productos', 'categorias', 'orden', 'direccion'));
    }
}