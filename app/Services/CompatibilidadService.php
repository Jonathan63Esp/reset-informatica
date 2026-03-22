<?php

namespace App\Services;

use App\Models\Producto;
use Illuminate\Support\Collection;

/**
 * Servicio de comprobación de compatibilidad de componentes.
 *
 * Encapsula las reglas de compatibilidad del configurador de PC.
 * Comprueba si un componente es compatible con los ya seleccionados
 * basándose en sus atributos técnicos (Socket, Tipo RAM, etc.).
 *
 * Reglas implementadas:
 * - CPU ↔ Placa base: el atributo Socket debe coincidir.
 * - Plataforma ↔ RAM: el atributo Tipo debe coincidir con el tipo
 *   de RAM soportado por la plataforma (DDR4/DDR5).
 */
class CompatibilidadService
{
    /**
     * Comprueba si un producto es compatible con los ya seleccionados.
     *
     * Analiza los atributos técnicos del producto y los compara con
     * los de los productos ya seleccionados en el configurador.
     *
     * @param Producto $producto Producto a comprobar
     * @param array $seleccionados Array de productos ya seleccionados [categoria => Producto]
     * @return array{compatible: bool, razones: string[]} Resultado con flag y lista de razones de incompatibilidad
     */
    public function comprobar(Producto $producto, array $seleccionados): array
    {
        $razones = [];

        $getAtributo = fn($p, $nombre) => $p->atributoValores
            ->first(fn($av) => $av->atributo->nombre === $nombre)?->valor;

        $socketProducto = $getAtributo($producto, 'Socket');
        $tipoRamProducto = $getAtributo($producto, 'Tipo RAM');

        foreach ($seleccionados as $nombreCateg => $seleccionado) {
            if (!is_object($seleccionado) || $seleccionado->id <= 0) continue;

            $socketSeleccionado  = $getAtributo($seleccionado, 'Socket');
            $tipoRamSeleccionado = $getAtributo($seleccionado, 'Tipo');

            // Regla: Socket CPU ↔ Placa base
            if ($socketProducto && $socketSeleccionado && $socketProducto !== $socketSeleccionado) {
                $razones[] = "Socket incompatible con {$seleccionado->nombre} ({$socketSeleccionado})";
            }

            // Regla: Tipo RAM ↔ Placa base / CPU
            if ($tipoRamProducto && $socketSeleccionado) {
                $tipoRamPlaca = $getAtributo($seleccionado, 'Tipo RAM');
                if ($tipoRamPlaca && $tipoRamProducto !== $tipoRamPlaca) {
                    $razones[] = "Tipo de RAM incompatible con {$seleccionado->nombre} (requiere {$tipoRamPlaca})";
                }
            }
        }

        return [
            'compatible' => empty($razones),
            'razones'    => $razones,
        ];
    }

    /**
     * Añade el resultado de compatibilidad a cada producto de una colección.
     *
     * Recorre la colección de productos y añade el atributo dinámico
     * 'compatibilidad' a cada uno con el resultado de comprobar().
     *
     * @param Collection $productos Colección de productos a evaluar
     * @param array $seleccionados Array de productos ya seleccionados [categoria => Producto]
     * @return Collection La misma colección con el atributo 'compatibilidad' añadido
     */
    public function filtrar(Collection $productos, array $seleccionados): Collection
    {
        return $productos->map(function ($producto) use ($seleccionados) {
            $producto->compatibilidad = $this->comprobar($producto, $seleccionados);
            return $producto;
        });
    }
}