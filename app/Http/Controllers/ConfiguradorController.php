<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Configuracion;
use App\Models\Producto;
use App\Services\CompatibilidadService;
use Illuminate\Http\Request;

/**
 * Controlador del configurador de PC.
 *
 * Gestiona el wizard de configuración de PC paso a paso.
 * El estado de la configuración se almacena en la sesión del usuario
 * bajo la clave SESSION_KEY ('configurador').
 *
 * Estructura de la sesión:
 * [
 *   'plataforma'          => 'AM5',
 *   'seleccion'           => ['Procesadores' => 8, 'Placas base' => 12, ...],
 *   'montaje'             => 'con_montaje' | 'sin_montaje',
 *   'montaje_producto_id' => 71 | null,
 * ]
 *
 * El wizard consta de 9 pasos:
 * - Pasos 1-8: Categorías de componentes (Procesadores, Placas base, etc.)
 * - Paso 9: Selección del servicio de montaje (obligatorio)
 *
 * Opciones especiales del wizard:
 * - producto_id = -1: Usar gráficos integrados del procesador (iGPU)
 * - producto_id = 0: Disipador de caja incluido con el procesador
 */
class ConfiguradorController extends Controller
{
    /** @var string Clave de sesión donde se almacena la configuración */
    const SESSION_KEY = 'configurador';

    /** @var int ID del producto "Servicio de montaje profesional" en la BD */
    const PRODUCTO_MONTAJE_ID = 71;

    /**
     * Plataformas disponibles con sus características de socket y RAM.
     *
     * @var array<string, array{socket: string, ram: string|string[], marca: string}>
     */
    const PLATAFORMAS = [
        'AM4'     => ['socket' => 'AM4',     'ram' => 'DDR4', 'marca' => 'AMD'],
        'AM5'     => ['socket' => 'AM5',     'ram' => 'DDR5', 'marca' => 'AMD'],
        'LGA1200' => ['socket' => 'LGA1200', 'ram' => 'DDR4', 'marca' => 'Intel'],
        'LGA1700' => ['socket' => 'LGA1700', 'ram' => ['DDR4', 'DDR5'], 'marca' => 'Intel'],
    ];

    /**
     * @param CompatibilidadService $compatibilidad Servicio de compatibilidades
     */
    public function __construct(private CompatibilidadService $compatibilidad) {}

    /**
     * Muestra la pantalla de selección de plataforma.
     *
     * @return \Illuminate\View\View
     */
    public function plataforma()
    {
        return view('configurador.plataforma');
    }

    /**
     * Muestra el paso actual del wizard de configuración.
     *
     * Si se recibe el parámetro 'plataforma' en la query string,
     * reinicia la configuración con la nueva plataforma seleccionada.
     * El último paso (totalPasos) muestra la pantalla de montaje.
     *
     * @param Request $request Parámetros: paso (int), plataforma (string, opcional)
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        if ($request->has('plataforma') && array_key_exists($request->plataforma, self::PLATAFORMAS)) {
            $config = $this->getConfig($request);
            $config['plataforma'] = $request->plataforma;
            $config['seleccion']  = [];
            $this->saveConfig($request, $config);
        }

        $config = $this->getConfig($request);

        if (empty($config['plataforma'])) {
            return redirect()->route('configurador.plataforma');
        }

        $plataforma = self::PLATAFORMAS[$config['plataforma']];
        $categorias = $this->getCategorias();
        $totalPasos = $categorias->count() + 1;
        $paso       = (int) $request->query('paso', 1);
        $paso       = max(1, min($paso, $totalPasos));

        if ($paso === $totalPasos) {
            $preciomontaje = (float) Configuracion::get('precio_montaje', '50.00');

            return view('configurador.wizard', [
                'categorias'               => $categorias,
                'categoriaActual'          => (object)['nombre' => 'Montaje'],
                'productos'                => collect(),
                'paso'                     => $paso,
                'totalPasos'               => $totalPasos,
                'config'                   => $config,
                'totalPrecio'              => $this->calcularTotal($config),
                'plataforma'               => $config['plataforma'],
                'plataformaInfo'           => $plataforma,
                'esPasoMontaje'            => true,
                'preciomontaje'            => $preciomontaje,
                'opcionDisipadorCaja'      => null,
                'opcionGraficosIntegrados' => null,
                'productosJson'            => '[]',
            ]);
        }

        $categoriaActual = $categorias->get($paso - 1);
        $productos       = $this->productosFiltrados($categoriaActual, $plataforma);
        $seleccionActual = $this->productosSeleccionados($config);
        $productos       = $this->compatibilidad->filtrar($productos, $seleccionActual);

        $opcionDisipadorCaja = null;
        if (str_contains(strtolower($categoriaActual->nombre), 'refriger')) {
            $opcionDisipadorCaja = true;
        }

        $opcionGraficosIntegrados = null;
        if (str_contains(strtolower($categoriaActual->nombre), 'gr') && str_contains(strtolower($categoriaActual->nombre), 'fica')) {
            $procesadorId = $config['seleccion']['Procesadores'] ?? null;
            if ($procesadorId) {
                $procesador = Producto::with('atributoValores.atributo')->find($procesadorId);
                if ($procesador) {
                    $igpu = $procesador->atributoValores
                        ->first(fn($av) => $av->atributo->nombre === 'Gráficos integrados');
                    if ($igpu && $igpu->valor !== 'No') {
                        $opcionGraficosIntegrados = $igpu->valor;
                    }
                }
            }
        }

        return view('configurador.wizard', [
            'categorias'               => $categorias,
            'categoriaActual'          => $categoriaActual,
            'productos'                => $productos,
            'paso'                     => $paso,
            'totalPasos'               => $totalPasos,
            'config'                   => $config,
            'totalPrecio'              => $this->calcularTotal($config),
            'plataforma'               => $config['plataforma'],
            'plataformaInfo'           => $plataforma,
            'opcionDisipadorCaja'      => $opcionDisipadorCaja,
            'opcionGraficosIntegrados' => $opcionGraficosIntegrados,
            'productosJson'            => $productos->map(function ($p) use ($config, $categoriaActual) {
                return [
                    'id'           => $p->id,
                    'nombre'       => $p->nombre,
                    'descripcion'  => $p->descripcion ?? 'Sin descripción disponible.',
                    'precio'       => number_format($p->precio, 2, ',', '.'),
                    'stock'        => $p->stock,
                    'imagen'       => $p->imagen ? $p->imagen_url : null,
                    'categoria'    => $categoriaActual->nombre,
                    'compatible'   => $p->compatibilidad['compatible'] ?? true,
                    'razones'      => $p->compatibilidad['razones'] ?? [],
                    'seleccionado' => ($config['seleccion'][$categoriaActual->nombre] ?? null) == $p->id,
                    'sinStock'     => !$p->enStock(),
                    'atributos'    => $p->atributoValores->map(function ($av) {
                        return ['nombre' => $av->atributo->nombre, 'valor' => $av->valor];
                    })->values(),
                ];
            })->values()->toJson(),
        ]);
    }

    /**
     * Procesa la selección del servicio de montaje.
     *
     * Guarda la opción de montaje en la sesión e incluye el ID del
     * producto de montaje si se eligió el servicio profesional.
     * Redirige al resumen de la configuración.
     *
     * @param Request $request Parámetros: montaje (con_montaje|sin_montaje)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function seleccionarMontaje(Request $request)
    {
        $request->validate([
            'montaje' => 'required|in:con_montaje,sin_montaje',
        ]);

        $config = $this->getConfig($request);
        $config['montaje']             = $request->montaje;
        $config['montaje_producto_id'] = $request->montaje === 'con_montaje'
            ? self::PRODUCTO_MONTAJE_ID
            : null;
        $this->saveConfig($request, $config);

        return redirect()->route('configurador.resumen');
    }

    /**
     * Procesa la selección de un componente en el wizard.
     *
     * Guarda el producto seleccionado en la sesión y avanza al
     * siguiente paso. Gestiona los casos especiales:
     * - producto_id = -1: gráficos integrados (iGPU)
     * - producto_id = 0: disipador de caja incluido
     *
     * @param Request $request Parámetros: producto_id (int), paso_actual (int)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function seleccionar(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|integer|min:-1',
            'paso_actual' => 'required|integer|min:1',
        ]);

        $config     = $this->getConfig($request);
        $categorias = $this->getCategorias();
        $totalPasos = $categorias->count() + 1;

        if ($request->producto_id == -1) {
            $categNombre = $categorias->get($request->paso_actual - 1)?->nombre ?? 'Tarjetas gráficas';
            $config['seleccion'][$categNombre] = 'igpu';
            $this->saveConfig($request, $config);
            $siguientePaso = $request->paso_actual + 1;
            if ($siguientePaso > $totalPasos) return redirect()->route('configurador.resumen');
            return redirect()->route('configurador.index', ['paso' => $siguientePaso]);
        }

        if ($request->producto_id == 0) {
            $categNombre = $categorias->get($request->paso_actual - 1)?->nombre ?? 'Refrigeración';
            $config['seleccion'][$categNombre] = 0;
            $this->saveConfig($request, $config);
            $siguientePaso = $request->paso_actual + 1;
            if ($siguientePaso > $totalPasos) return redirect()->route('configurador.resumen');
            return redirect()->route('configurador.index', ['paso' => $siguientePaso]);
        }

        $producto    = Producto::with('categoria')->findOrFail($request->producto_id);
        $categNombre = $producto->categoria->nombre;

        $config['seleccion'][$categNombre] = $request->producto_id;
        $this->saveConfig($request, $config);

        $siguientePaso = $request->paso_actual + 1;
        if ($siguientePaso > $totalPasos) return redirect()->route('configurador.resumen');
        return redirect()->route('configurador.index', ['paso' => $siguientePaso]);
    }

    /**
     * Omite el paso actual y avanza al siguiente.
     *
     * No guarda ninguna selección para la categoría del paso omitido.
     * El paso de montaje no puede omitirse (es obligatorio).
     *
     * @param Request $request Parámetros: paso_actual (int)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function omitir(Request $request)
    {
        $request->validate(['paso_actual' => 'required|integer|min:1']);

        $totalPasos    = Categoria::count() + 1;
        $siguientePaso = $request->paso_actual + 1;

        if ($siguientePaso > $totalPasos) {
            return redirect()->route('configurador.resumen');
        }

        return redirect()->route('configurador.index', ['paso' => $siguientePaso]);
    }

    /**
     * Muestra el resumen de la configuración actual.
     *
     * Si no se ha seleccionado ningún componente, redirige al wizard.
     * Si no se ha elegido la opción de montaje, redirige al paso 9.
     * Calcula el precio total incluyendo el montaje si se seleccionó.
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function resumen(Request $request)
    {
        $config    = $this->getConfig($request);
        $productos = $this->productosSeleccionados($config);

        if (empty($productos)) {
            return redirect()->route('configurador.index')
                ->with('warning', 'No has seleccionado ningún componente todavía.');
        }

        if (!isset($config['montaje'])) {
            $totalPasos = Categoria::count() + 1;
            return redirect()->route('configurador.index', ['paso' => $totalPasos]);
        }

        $preciomontaje = $config['montaje'] === 'con_montaje'
            ? (float) Configuracion::get('precio_montaje', '50.00')
            : 0.0;

        return view('configurador.resumen', [
            'productos'     => $productos,
            'total'         => $this->calcularTotal($config) + $preciomontaje,
            'config'        => $config,
            'plataforma'    => $config['plataforma'] ?? null,
            'montaje'       => $config['montaje'] ?? null,
            'preciomontaje' => $preciomontaje,
        ]);
    }

    /**
     * Reinicia la configuración eliminando la sesión del configurador.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reiniciar(Request $request)
    {
        $request->session()->forget(self::SESSION_KEY);

        return redirect()->route('configurador.plataforma')
            ->with('success', 'Configuración reiniciada.');
    }

    /**
     * Exporta la configuración actual como archivo de texto plano.
     *
     * Genera un archivo .txt descargable con el resumen de todos
     * los componentes seleccionados, el servicio de montaje y el
     * total de la configuración.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function exportar(Request $request)
    {
        $config    = $this->getConfig($request);
        $productos = $this->productosSeleccionados($config);
        $preciomontaje = ($config['montaje'] ?? '') === 'con_montaje'
            ? (float) Configuracion::get('precio_montaje', '50.00')
            : 0.0;
        $total = $this->calcularTotal($config) + $preciomontaje;

        $contenido  = "CONFIGURACIÓN RESET INFORMÁTICA\n";
        $contenido .= "================================\n";
        $contenido .= "Plataforma: " . ($config['plataforma'] ?? 'No especificada') . "\n\n";

        foreach ($productos as $nombreCateg => $producto) {
            $contenido .= "{$nombreCateg}:\n";
            if (is_object($producto) && $producto->id > 0) {
                $contenido .= "  → {$producto->nombre}  |  {$producto->precio} €\n\n";
            } elseif (is_object($producto) && $producto->id === -1) {
                $contenido .= "  → Gráficos integrados del procesador  |  0 €\n\n";
            } else {
                $contenido .= "  → Disipador de caja incluido  |  0 €\n\n";
            }
        }

        $montajeTxt = ($config['montaje'] ?? '') === 'con_montaje'
            ? "Con montaje ({$preciomontaje} €)"
            : 'Sin montaje';
        $contenido .= "Montaje: {$montajeTxt}\n";
        $contenido .= "--------------------------------\n";
        $contenido .= "TOTAL: {$total} €\n";
        $contenido .= "================================\n";
        $contenido .= "Generado el " . now()->format('d/m/Y H:i') . "\n";

        return response($contenido, 200, [
            'Content-Type'        => 'text/plain; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="configuracion-reset.txt"',
        ]);
    }

    /**
     * Obtiene las categorías del wizard en el orden correcto.
     *
     * Excluye la categoría 'Servicios' ya que no forma parte
     * de los pasos del configurador.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getCategorias()
    {
        return Categoria::whereNotIn('nombre', ['Servicios'])
            ->orderByRaw("FIELD(nombre, 'Procesadores', 'Placas base', 'Memoria RAM', 'Tarjetas gráficas', 'Fuentes de alimentación', 'Almacenamiento', 'Refrigeración', 'Cajas')")
            ->get();
    }

    /**
     * Filtra los productos de una categoría según la plataforma seleccionada.
     *
     * Aplica filtros específicos por categoría:
     * - Procesadores: filtra por Socket
     * - Placas base: filtra por Socket
     * - Memoria RAM: filtra por Tipo (DDR4/DDR5)
     * - Resto de categorías: sin filtro adicional
     *
     * @param object $categoria Categoría actual del wizard
     * @param array $plataforma Datos de la plataforma seleccionada
     * @return \Illuminate\Support\Collection
     */
    private function productosFiltrados(object $categoria, array $plataforma): \Illuminate\Support\Collection
    {
        $nombreCat = strtolower($categoria->nombre);
        $socket    = $plataforma['socket'];
        $ram       = $plataforma['ram'];

        $query = Producto::where('categoria_id', $categoria->id)
            ->with(['atributoValores.atributo', 'categoria']);

        if (str_contains($nombreCat, 'procesador')) {
            $query->whereHas('atributoValores.atributo', function ($q) {
                $q->where('nombre', 'Socket');
            })->whereHas('atributoValores', function ($q) use ($socket) {
                $q->where('valor', $socket)
                  ->whereHas('atributo', fn($q2) => $q2->where('nombre', 'Socket'));
            });
        } elseif (str_contains($nombreCat, 'placa')) {
            $query->whereHas('atributoValores', function ($q) use ($socket) {
                $q->where('valor', $socket)
                  ->whereHas('atributo', fn($q2) => $q2->where('nombre', 'Socket'));
            });
        } elseif (str_contains($nombreCat, 'ram') || str_contains($nombreCat, 'memoria')) {
            $tiposRam = is_array($ram) ? $ram : [$ram];
            $query->whereHas('atributoValores', function ($q) use ($tiposRam) {
                $q->whereIn('valor', $tiposRam)
                  ->whereHas('atributo', fn($q2) => $q2->where('nombre', 'Tipo'));
            });
        }

        return $query->get();
    }

    /**
     * Obtiene la configuración del wizard desde la sesión.
     *
     * @param Request $request
     * @return array Configuración actual con claves 'plataforma' y 'seleccion'
     */
    private function getConfig(Request $request): array
    {
        return $request->session()->get(self::SESSION_KEY, ['seleccion' => [], 'plataforma' => null]);
    }

    /**
     * Guarda la configuración del wizard en la sesión.
     *
     * @param Request $request
     * @param array $config Configuración a guardar
     * @return void
     */
    private function saveConfig(Request $request, array $config): void
    {
        $request->session()->put(self::SESSION_KEY, $config);
    }

    /**
     * Construye el array de productos seleccionados desde la sesión.
     *
     * Convierte los IDs almacenados en sesión en objetos Producto,
     * manejando los casos especiales (iGPU y disipador de caja)
     * como objetos anónimos con precio 0.
     *
     * @param array $config Configuración actual de la sesión
     * @return array Array [nombreCategoria => Producto|object]
     */
    private function productosSeleccionados(array $config): array
    {
        $seleccion = $config['seleccion'] ?? [];
        $result    = [];

        foreach ($seleccion as $nombreCateg => $productoId) {
            if ($productoId === 'igpu') {
                $result[$nombreCateg] = (object)[
                    'id'              => -1,
                    'nombre'          => 'Gráficos integrados del procesador',
                    'precio'          => 0,
                    'imagen'          => null,
                    'atributoValores' => collect([]),
                    'categoria'       => (object)['nombre' => $nombreCateg],
                ];
                continue;
            }
            if ($productoId == 0) {
                $result[$nombreCateg] = (object)[
                    'id'              => 0,
                    'nombre'          => 'Disipador de caja incluido',
                    'precio'          => 0,
                    'imagen'          => null,
                    'atributoValores' => collect([]),
                    'categoria'       => (object)['nombre' => $nombreCateg],
                ];
                continue;
            }
            $p = Producto::with(['atributoValores.atributo', 'categoria'])->find($productoId);
            if ($p) {
                $result[$nombreCateg] = $p;
            }
        }

        return $result;
    }

    /**
     * Calcula el precio total de los componentes seleccionados.
     *
     * No incluye el precio del montaje (se añade por separado
     * en el método resumen()).
     *
     * @param array $config Configuración actual de la sesión
     * @return float Precio total en euros
     */
    private function calcularTotal(array $config): float
    {
        $seleccion = $config['seleccion'] ?? [];
        if (empty($seleccion)) return 0.0;

        $ids = array_filter(array_values($seleccion), fn($id) => is_numeric($id) && $id > 0);
        if (empty($ids)) return 0.0;

        return Producto::whereIn('id', $ids)->sum('precio');
    }
}