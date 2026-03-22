<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar antes de insertar
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('atributo_valores')->truncate();
        DB::table('productos')->truncate();
        DB::table('atributos')->truncate();
        DB::table('categorias')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ── Categorías ────────────────────────────────────────
        $categorias = [
            'Procesadores',
            'Placas base',
            'Memoria RAM',
            'Tarjetas gráficas',
            'Fuentes de alimentación',
            'Almacenamiento',
            'Refrigeración',
            'Cajas',
        ];

        foreach ($categorias as $nombre) {
            DB::table('categorias')->insert([
                'nombre'     => $nombre,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $catId = fn($nombre) => DB::table('categorias')->where('nombre', $nombre)->value('id');

        // ── Atributos ─────────────────────────────────────────
       $atributos = [
    'Socket', 'TDP', 'Núcleos', 'Frecuencia base', 'Frecuencia turbo',
    'Tipo RAM', 'Factor de forma', 'Chipset',
    'VRAM', 'Tipo memoria', 'Frecuencia', 'Conectores',
    'Capacidad', 'Tipo', 'Velocidad lectura', 'Velocidad escritura', 'Interfaz',
    'Disipador', 'RPM', 'Nivel ruido',
    'Potencia', 'Certificación', 'Modular',
    'Bahías', 'Formato', 'Ventiladores incluidos',
    'Gráficos integrados',
];

        foreach ($atributos as $nombre) {
            DB::table('atributos')->insert([
                'nombre'     => $nombre,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $atrId = fn($nombre) => DB::table('atributos')->where('nombre', $nombre)->value('id');

        // ── Helper para insertar producto con atributos ───────
        $insertar = function (string $categoria, string $nombre, string $descripcion, float $precio, int $stock, array $attrs) use ($catId, $atrId) {
            $id = DB::table('productos')->insertGetId([
                'categoria_id' => $catId($categoria),
                'nombre'       => $nombre,
                'descripcion'  => $descripcion,
                'precio'       => $precio,
                'stock'        => $stock,
                'imagen'       => null,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);

            foreach ($attrs as $atributo => $valor) {
                DB::table('atributo_valores')->insert([
                    'producto_id' => $id,
                    'atributo_id' => $atrId($atributo),
                    'valor'       => $valor,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        };

      // ── PROCESADORES ──────────────────────────────────────────

// AMD AM4 con gráficos (serie G)
$insertar('Procesadores', 'AMD Ryzen 5 5600G', 'Procesador AM4 con gráficos integrados Radeon Vega 7.', 129.99, 15, [
    'Socket' => 'AM4', 'TDP' => '65W', 'Núcleos' => '6', 'Frecuencia base' => '3.9 GHz', 'Frecuencia turbo' => '4.4 GHz', 'Gráficos integrados' => 'Radeon Vega 7',
]);
$insertar('Procesadores', 'AMD Ryzen 7 5700G', 'Procesador AM4 con gráficos integrados Radeon Vega 8.', 179.99, 12, [
    'Socket' => 'AM4', 'TDP' => '65W', 'Núcleos' => '8', 'Frecuencia base' => '3.8 GHz', 'Frecuencia turbo' => '4.6 GHz', 'Gráficos integrados' => 'Radeon Vega 8',
]);

// AMD AM4 sin gráficos
$insertar('Procesadores', 'AMD Ryzen 5 3600', 'Procesador de 6 núcleos AM4 económico.', 99.99, 20, [
    'Socket' => 'AM4', 'TDP' => '65W', 'Núcleos' => '6', 'Frecuencia base' => '3.6 GHz', 'Frecuencia turbo' => '4.2 GHz', 'Gráficos integrados' => 'No',
]);
$insertar('Procesadores', 'AMD Ryzen 5 5600X', 'Procesador AM4 sin gráficos integrados.', 149.99, 10, [
    'Socket' => 'AM4', 'TDP' => '65W', 'Núcleos' => '6', 'Frecuencia base' => '3.7 GHz', 'Frecuencia turbo' => '4.6 GHz', 'Gráficos integrados' => 'No',
]);
$insertar('Procesadores', 'AMD Ryzen 7 5800X', 'Procesador AM4 sin gráficos integrados.', 199.99, 8, [
    'Socket' => 'AM4', 'TDP' => '105W', 'Núcleos' => '8', 'Frecuencia base' => '3.8 GHz', 'Frecuencia turbo' => '4.7 GHz', 'Gráficos integrados' => 'No',
]);
$insertar('Procesadores', 'AMD Ryzen 9 5900X', 'Procesador AM4 sin gráficos integrados.', 299.99, 6, [
    'Socket' => 'AM4', 'TDP' => '105W', 'Núcleos' => '12', 'Frecuencia base' => '3.7 GHz', 'Frecuencia turbo' => '4.8 GHz', 'Gráficos integrados' => 'No',
]);
$insertar('Procesadores', 'AMD Ryzen 9 5950X', 'Procesador AM4 sin gráficos integrados.', 399.99, 4, [
    'Socket' => 'AM4', 'TDP' => '105W', 'Núcleos' => '16', 'Frecuencia base' => '3.4 GHz', 'Frecuencia turbo' => '4.9 GHz', 'Gráficos integrados' => 'No',
]);

// AMD AM5 serie 7000 (iGPU básica)
$insertar('Procesadores', 'AMD Ryzen 5 7600', 'Procesador AM5 con gráficos integrados básicos.', 199.99, 14, [
    'Socket' => 'AM5', 'TDP' => '65W', 'Núcleos' => '6', 'Frecuencia base' => '3.8 GHz', 'Frecuencia turbo' => '5.1 GHz', 'Gráficos integrados' => 'Radeon Graphics (2 CUs)',
]);
$insertar('Procesadores', 'AMD Ryzen 5 7600X', 'Procesador de 6 núcleos AM5 para gaming y productividad.', 229.99, 15, [
    'Socket' => 'AM5', 'TDP' => '105W', 'Núcleos' => '6', 'Frecuencia base' => '4.7 GHz', 'Frecuencia turbo' => '5.3 GHz', 'Gráficos integrados' => 'Radeon Graphics (2 CUs)',
]);
$insertar('Procesadores', 'AMD Ryzen 7 7700', 'Procesador AM5 con gráficos integrados básicos.', 289.99, 10, [
    'Socket' => 'AM5', 'TDP' => '65W', 'Núcleos' => '8', 'Frecuencia base' => '3.8 GHz', 'Frecuencia turbo' => '5.3 GHz', 'Gráficos integrados' => 'Radeon Graphics (2 CUs)',
]);
$insertar('Procesadores', 'AMD Ryzen 7 7700X', 'Procesador de 8 núcleos AM5 de alto rendimiento.', 329.99, 10, [
    'Socket' => 'AM5', 'TDP' => '105W', 'Núcleos' => '8', 'Frecuencia base' => '4.5 GHz', 'Frecuencia turbo' => '5.4 GHz', 'Gráficos integrados' => 'Radeon Graphics (2 CUs)',
]);
$insertar('Procesadores', 'AMD Ryzen 9 7900X', 'Procesador de 12 núcleos AM5 para creadores de contenido.', 449.99, 8, [
    'Socket' => 'AM5', 'TDP' => '170W', 'Núcleos' => '12', 'Frecuencia base' => '4.7 GHz', 'Frecuencia turbo' => '5.6 GHz', 'Gráficos integrados' => 'Radeon Graphics (2 CUs)',
]);

// AMD AM5 serie 8000G (iGPU potente)
$insertar('Procesadores', 'AMD Ryzen 5 8600G', 'Procesador AM5 con gráficos integrados Radeon 760M.', 249.99, 10, [
    'Socket' => 'AM5', 'TDP' => '65W', 'Núcleos' => '6', 'Frecuencia base' => '4.3 GHz', 'Frecuencia turbo' => '5.0 GHz', 'Gráficos integrados' => 'Radeon 760M',
]);
$insertar('Procesadores', 'AMD Ryzen 7 8700G', 'Procesador AM5 con gráficos integrados Radeon 780M.', 319.99, 8, [
    'Socket' => 'AM5', 'TDP' => '65W', 'Núcleos' => '8', 'Frecuencia base' => '4.2 GHz', 'Frecuencia turbo' => '5.1 GHz', 'Gráficos integrados' => 'Radeon 780M',
]);

// AMD AM5 X3D (iGPU básica)
$insertar('Procesadores', 'AMD Ryzen 5 7600X3D', 'Procesador AM5 con 3D V-Cache y gráficos básicos.', 299.99, 8, [
    'Socket' => 'AM5', 'TDP' => '105W', 'Núcleos' => '6', 'Frecuencia base' => '4.7 GHz', 'Frecuencia turbo' => '5.3 GHz', 'Gráficos integrados' => 'Radeon Graphics (2 CUs)',
]);
$insertar('Procesadores', 'AMD Ryzen 9 7900X3D', 'Procesador AM5 con 3D V-Cache y gráficos básicos.', 499.99, 5, [
    'Socket' => 'AM5', 'TDP' => '120W', 'Núcleos' => '12', 'Frecuencia base' => '4.4 GHz', 'Frecuencia turbo' => '5.6 GHz', 'Gráficos integrados' => 'Radeon Graphics (2 CUs)',
]);

// AMD AM5 serie F (sin gráficos)
$insertar('Procesadores', 'AMD Ryzen 5 7500F', 'Procesador AM5 sin gráficos integrados.', 169.99, 12, [
    'Socket' => 'AM5', 'TDP' => '65W', 'Núcleos' => '6', 'Frecuencia base' => '3.7 GHz', 'Frecuencia turbo' => '5.0 GHz', 'Gráficos integrados' => 'No',
]);
$insertar('Procesadores', 'AMD Ryzen 5 8400F', 'Procesador AM5 sin gráficos integrados.', 149.99, 10, [
    'Socket' => 'AM5', 'TDP' => '65W', 'Núcleos' => '6', 'Frecuencia base' => '4.2 GHz', 'Frecuencia turbo' => '4.7 GHz', 'Gráficos integrados' => 'No',
]);
$insertar('Procesadores', 'AMD Ryzen 7 8700F', 'Procesador AM5 sin gráficos integrados.', 279.99, 8, [
    'Socket' => 'AM5', 'TDP' => '65W', 'Núcleos' => '8', 'Frecuencia base' => '4.2 GHz', 'Frecuencia turbo' => '5.0 GHz', 'Gráficos integrados' => 'No',
]);

// Intel LGA1700 con iGPU
$insertar('Procesadores', 'Intel Core i5-13600K', 'Procesador Intel de 14 núcleos para gaming.', 289.99, 12, [
    'Socket' => 'LGA1700', 'TDP' => '125W', 'Núcleos' => '14', 'Frecuencia base' => '3.5 GHz', 'Frecuencia turbo' => '5.1 GHz', 'Gráficos integrados' => 'Intel UHD 770',
]);
$insertar('Procesadores', 'Intel Core i7-13700K', 'Procesador Intel de 16 núcleos para gaming y trabajo.', 389.99, 9, [
    'Socket' => 'LGA1700', 'TDP' => '125W', 'Núcleos' => '16', 'Frecuencia base' => '3.4 GHz', 'Frecuencia turbo' => '5.4 GHz', 'Gráficos integrados' => 'Intel UHD 770',
]);
$insertar('Procesadores', 'Intel Core i9-13900K', 'Procesador Intel flagship de 24 núcleos.', 589.99, 5, [
    'Socket' => 'LGA1700', 'TDP' => '125W', 'Núcleos' => '24', 'Frecuencia base' => '3.0 GHz', 'Frecuencia turbo' => '5.8 GHz', 'Gráficos integrados' => 'Intel UHD 770',
]);

// Intel LGA1700 sin iGPU (serie F)
$insertar('Procesadores', 'Intel Core i5-13600KF', 'Procesador Intel sin gráficos integrados.', 269.99, 10, [
    'Socket' => 'LGA1700', 'TDP' => '125W', 'Núcleos' => '14', 'Frecuencia base' => '3.5 GHz', 'Frecuencia turbo' => '5.1 GHz', 'Gráficos integrados' => 'No',
]);
$insertar('Procesadores', 'Intel Core i7-13700KF', 'Procesador Intel sin gráficos integrados.', 369.99, 7, [
    'Socket' => 'LGA1700', 'TDP' => '125W', 'Núcleos' => '16', 'Frecuencia base' => '3.4 GHz', 'Frecuencia turbo' => '5.4 GHz', 'Gráficos integrados' => 'No',
]);

        // ── PLACAS BASE ───────────────────────────────────────
        $insertar('Placas base', 'ASUS ROG Strix B550-F', 'Placa base AM4 con DDR4 y PCIe 4.0.', 179.99, 10, [
            'Socket' => 'AM4', 'Tipo RAM' => 'DDR4', 'Factor de forma' => 'ATX', 'Chipset' => 'B550',
        ]);
        $insertar('Placas base', 'MSI MAG X570S Tomahawk', 'Placa base AM4 de gama alta con X570.', 229.99, 8, [
            'Socket' => 'AM4', 'Tipo RAM' => 'DDR4', 'Factor de forma' => 'ATX', 'Chipset' => 'X570',
        ]);
        $insertar('Placas base', 'Gigabyte B550M DS3H AM4', 'Placa base AM4 Micro ATX económica.', 99.99, 15, [
            'Socket' => 'AM4', 'Tipo RAM' => 'DDR4', 'Factor de forma' => 'Micro ATX', 'Chipset' => 'B550',
        ]);
        $insertar('Placas base', 'ASRock X570 Phantom Gaming', 'Placa base AM4 X570 para overclock.', 199.99, 7, [
            'Socket' => 'AM4', 'Tipo RAM' => 'DDR4', 'Factor de forma' => 'ATX', 'Chipset' => 'X570',
        ]);
        $insertar('Placas base', 'ASUS ROG Strix B650-A', 'Placa base AM5 con DDR5 y PCIe 5.0.', 249.99, 10, [
            'Socket' => 'AM5', 'Tipo RAM' => 'DDR5', 'Factor de forma' => 'ATX', 'Chipset' => 'B650',
        ]);
        $insertar('Placas base', 'MSI MAG X670E Tomahawk', 'Placa base AM5 de gama alta con X670E.', 329.99, 7, [
            'Socket' => 'AM5', 'Tipo RAM' => 'DDR5', 'Factor de forma' => 'ATX', 'Chipset' => 'X670E',
        ]);
        $insertar('Placas base', 'Gigabyte B650M DS3H AM5', 'Placa base AM5 Micro ATX económica.', 149.99, 14, [
            'Socket' => 'AM5', 'Tipo RAM' => 'DDR5', 'Factor de forma' => 'Micro ATX', 'Chipset' => 'B650',
        ]);
        $insertar('Placas base', 'ASUS PRIME Z790-P', 'Placa base LGA1700 con DDR5 y PCIe 5.0.', 219.99, 11, [
            'Socket' => 'LGA1700', 'Tipo RAM' => 'DDR5', 'Factor de forma' => 'ATX', 'Chipset' => 'Z790',
        ]);
        $insertar('Placas base', 'MSI PRO B760M-A DDR4', 'Placa base LGA1700 económica con DDR4.', 129.99, 16, [
            'Socket' => 'LGA1700', 'Tipo RAM' => 'DDR4', 'Factor de forma' => 'Micro ATX', 'Chipset' => 'B760',
        ]);
        $insertar('Placas base', 'Gigabyte Z790 AORUS Elite', 'Placa base LGA1700 de gama alta para overclock.', 379.99, 6, [
            'Socket' => 'LGA1700', 'Tipo RAM' => 'DDR5', 'Factor de forma' => 'ATX', 'Chipset' => 'Z790',
        ]);

        // ── MEMORIA RAM ───────────────────────────────────────
        $insertar('Memoria RAM', 'Kingston Fury Beast DDR4 16GB', 'Kit 2x8GB DDR4 3200MHz para plataformas AM4/LGA1700.', 44.99, 25, [
            'Capacidad' => '16GB', 'Tipo' => 'DDR4', 'Frecuencia' => '3200MHz',
        ]);
        $insertar('Memoria RAM', 'Corsair Vengeance DDR4 32GB', 'Kit 2x16GB DDR4 3600MHz de alto rendimiento.', 69.99, 18, [
            'Capacidad' => '32GB', 'Tipo' => 'DDR4', 'Frecuencia' => '3600MHz',
        ]);
        $insertar('Memoria RAM', 'G.Skill Ripjaws V DDR4 64GB', 'Kit 2x32GB DDR4 3200MHz para workstations.', 109.99, 8, [
            'Capacidad' => '64GB', 'Tipo' => 'DDR4', 'Frecuencia' => '3200MHz',
        ]);
        $insertar('Memoria RAM', 'Kingston Fury Beast DDR5 32GB', 'Kit 2x16GB DDR5 5200MHz para plataformas AM5/LGA1700.', 89.99, 20, [
            'Capacidad' => '32GB', 'Tipo' => 'DDR5', 'Frecuencia' => '5200MHz',
        ]);
        $insertar('Memoria RAM', 'Corsair Vengeance DDR5 64GB', 'Kit 2x32GB DDR5 6000MHz de alto rendimiento.', 159.99, 12, [
            'Capacidad' => '64GB', 'Tipo' => 'DDR5', 'Frecuencia' => '6000MHz',
        ]);
        $insertar('Memoria RAM', 'G.Skill Trident Z5 32GB', 'Kit 2x16GB DDR5 6400MHz con RGB.', 119.99, 10, [
            'Capacidad' => '32GB', 'Tipo' => 'DDR5', 'Frecuencia' => '6400MHz',
        ]);

        // ── TARJETAS GRÁFICAS ─────────────────────────────────
        $insertar('Tarjetas gráficas', 'NVIDIA RTX 4060', 'GPU para 1080p gaming de alto rendimiento.', 299.99, 12, [
            'VRAM' => '8GB', 'Tipo memoria' => 'GDDR6', 'Frecuencia' => '1830 MHz', 'Conectores' => '1x HDMI 2.1, 3x DP 1.4',
        ]);
        $insertar('Tarjetas gráficas', 'NVIDIA RTX 4070', 'GPU para 1440p gaming de alta calidad.', 599.99, 8, [
            'VRAM' => '12GB', 'Tipo memoria' => 'GDDR6X', 'Frecuencia' => '1920 MHz', 'Conectores' => '1x HDMI 2.1, 3x DP 1.4',
        ]);
        $insertar('Tarjetas gráficas', 'NVIDIA RTX 4080', 'GPU para 4K gaming y creación de contenido.', 1199.99, 4, [
            'VRAM' => '16GB', 'Tipo memoria' => 'GDDR6X', 'Frecuencia' => '2205 MHz', 'Conectores' => '1x HDMI 2.1, 3x DP 1.4',
        ]);
        $insertar('Tarjetas gráficas', 'AMD RX 7600', 'GPU AMD para 1080p gaming a buen precio.', 269.99, 15, [
            'VRAM' => '8GB', 'Tipo memoria' => 'GDDR6', 'Frecuencia' => '2655 MHz', 'Conectores' => '1x HDMI 2.1, 3x DP 2.1',
        ]);
        $insertar('Tarjetas gráficas', 'AMD RX 7700 XT', 'GPU AMD para 1440p gaming.', 449.99, 9, [
            'VRAM' => '12GB', 'Tipo memoria' => 'GDDR6', 'Frecuencia' => '2544 MHz', 'Conectores' => '1x HDMI 2.1, 3x DP 2.1',
        ]);
        $insertar('Tarjetas gráficas', 'AMD RX 7900 XTX', 'GPU AMD flagship para 4K gaming.', 999.99, 3, [
            'VRAM' => '24GB', 'Tipo memoria' => 'GDDR6', 'Frecuencia' => '2500 MHz', 'Conectores' => '1x HDMI 2.1, 2x DP 2.1',
        ]);

        // ── FUENTES DE ALIMENTACIÓN ───────────────────────────
        $insertar('Fuentes de alimentación', 'Corsair RM850x', 'Fuente 850W 80+ Gold totalmente modular.', 139.99, 12, [
            'Potencia' => '850W', 'Certificación' => '80+ Gold', 'Modular' => 'Totalmente modular',
        ]);
        $insertar('Fuentes de alimentación', 'be quiet! Straight Power 11 750W', 'Fuente 750W 80+ Gold de alta calidad.', 119.99, 10, [
            'Potencia' => '750W', 'Certificación' => '80+ Gold', 'Modular' => 'Totalmente modular',
        ]);
        $insertar('Fuentes de alimentación', 'Seasonic Focus GX-1000', 'Fuente 1000W 80+ Gold para builds de alta gama.', 179.99, 7, [
            'Potencia' => '1000W', 'Certificación' => '80+ Gold', 'Modular' => 'Totalmente modular',
        ]);
        $insertar('Fuentes de alimentación', 'EVGA SuperNOVA 650 G6', 'Fuente 650W 80+ Gold compacta y eficiente.', 99.99, 14, [
            'Potencia' => '650W', 'Certificación' => '80+ Gold', 'Modular' => 'Totalmente modular',
        ]);
        $insertar('Fuentes de alimentación', 'Corsair CV550', 'Fuente 550W 80+ Bronze económica.', 59.99, 18, [
            'Potencia' => '550W', 'Certificación' => '80+ Bronze', 'Modular' => 'No modular',
        ]);
        $insertar('Fuentes de alimentación', 'Thermaltake Toughpower GF3 1200W', 'Fuente 1200W 80+ Gold para workstations extremas.', 219.99, 5, [
            'Potencia' => '1200W', 'Certificación' => '80+ Gold', 'Modular' => 'Totalmente modular',
        ]);

        // ── ALMACENAMIENTO ────────────────────────────────────
        $insertar('Almacenamiento', 'Samsung 990 Pro 1TB', 'SSD NVMe PCIe 4.0 de alto rendimiento.', 109.99, 20, [
            'Capacidad' => '1TB', 'Tipo' => 'SSD NVMe', 'Velocidad lectura' => '7450 MB/s', 'Velocidad escritura' => '6900 MB/s', 'Interfaz' => 'PCIe 4.0',
        ]);
        $insertar('Almacenamiento', 'Samsung 990 Pro 2TB', 'SSD NVMe PCIe 4.0 de 2TB.', 199.99, 14, [
            'Capacidad' => '2TB', 'Tipo' => 'SSD NVMe', 'Velocidad lectura' => '7450 MB/s', 'Velocidad escritura' => '6900 MB/s', 'Interfaz' => 'PCIe 4.0',
        ]);
        $insertar('Almacenamiento', 'WD Black SN850X 1TB', 'SSD NVMe PCIe 4.0 optimizado para gaming.', 119.99, 16, [
            'Capacidad' => '1TB', 'Tipo' => 'SSD NVMe', 'Velocidad lectura' => '7300 MB/s', 'Velocidad escritura' => '6600 MB/s', 'Interfaz' => 'PCIe 4.0',
        ]);
        $insertar('Almacenamiento', 'Crucial MX500 1TB', 'SSD SATA económico para almacenamiento secundario.', 59.99, 22, [
            'Capacidad' => '1TB', 'Tipo' => 'SSD SATA', 'Velocidad lectura' => '560 MB/s', 'Velocidad escritura' => '510 MB/s', 'Interfaz' => 'SATA III',
        ]);
        $insertar('Almacenamiento', 'Seagate Barracuda 2TB', 'Disco duro HDD para almacenamiento masivo.', 49.99, 18, [
            'Capacidad' => '2TB', 'Tipo' => 'HDD', 'Velocidad lectura' => '190 MB/s', 'Velocidad escritura' => '190 MB/s', 'Interfaz' => 'SATA III',
        ]);
        $insertar('Almacenamiento', 'Kingston KC3000 2TB', 'SSD NVMe PCIe 4.0 de 2TB para workstations.', 179.99, 10, [
            'Capacidad' => '2TB', 'Tipo' => 'SSD NVMe', 'Velocidad lectura' => '7000 MB/s', 'Velocidad escritura' => '7000 MB/s', 'Interfaz' => 'PCIe 4.0',
        ]);

        // ── REFRIGERACIÓN ─────────────────────────────────────
        $insertar('Refrigeración', 'Noctua NH-D15', 'Disipador torre doble de referencia para CPU.', 99.99, 10, [
            'Disipador' => 'Torre doble', 'RPM' => '300-1500', 'Nivel ruido' => '24.6 dB',
        ]);
        $insertar('Refrigeración', 'be quiet! Dark Rock Pro 4', 'Disipador torre doble silencioso de alta gama.', 89.99, 8, [
            'Disipador' => 'Torre doble', 'RPM' => '200-1500', 'Nivel ruido' => '24.3 dB',
        ]);
        $insertar('Refrigeración', 'Corsair H100i Elite 240mm', 'Refrigeración líquida AIO de 240mm con RGB.', 149.99, 12, [
            'Disipador' => 'Líquida AIO 240mm', 'RPM' => '400-2400', 'Nivel ruido' => '37 dB',
        ]);
        $insertar('Refrigeración', 'NZXT Kraken X63 280mm', 'Refrigeración líquida AIO de 280mm premium.', 179.99, 7, [
            'Disipador' => 'Líquida AIO 280mm', 'RPM' => '500-2800', 'Nivel ruido' => '38 dB',
        ]);
        $insertar('Refrigeración', 'DeepCool AK620', 'Disipador torre doble de alto rendimiento y precio ajustado.', 59.99, 15, [
            'Disipador' => 'Torre doble', 'RPM' => '500-1850', 'Nivel ruido' => '28 dB',
        ]);
        $insertar('Refrigeración', 'Arctic Freezer 36', 'Disipador económico con buena relación calidad-precio.', 34.99, 20, [
            'Disipador' => 'Torre simple', 'RPM' => '200-1700', 'Nivel ruido' => '25 dB',
        ]);

        // ── CAJAS ─────────────────────────────────────────────
        $insertar('Cajas', 'Fractal Design Define 7', 'Caja ATX silenciosa y elegante con panel sólido.', 159.99, 8, [
            'Bahías' => '2x 3.5", 4x 2.5"', 'Formato' => 'ATX', 'Ventiladores incluidos' => '2x 140mm',
        ]);
        $insertar('Cajas', 'NZXT H510 Flow', 'Caja ATX compacta con buena refrigeración.', 99.99, 12, [
            'Bahías' => '2x 3.5", 2x 2.5"', 'Formato' => 'ATX', 'Ventiladores incluidos' => '2x 120mm',
        ]);
        $insertar('Cajas', 'Lian Li PC-O11 Dynamic', 'Caja ATX con panel de cristal templado y gran espacio.', 139.99, 9, [
            'Bahías' => '2x 3.5", 4x 2.5"', 'Formato' => 'ATX', 'Ventiladores incluidos' => '3x 120mm',
        ]);
        $insertar('Cajas', 'Corsair 4000D Airflow', 'Caja ATX con excelente flujo de aire.', 104.99, 11, [
            'Bahías' => '2x 3.5", 2x 2.5"', 'Formato' => 'ATX', 'Ventiladores incluidos' => '2x 120mm',
        ]);
        $insertar('Cajas', 'be quiet! Pure Base 500DX', 'Caja ATX con ventanas de cristal y buena insonorización.', 119.99, 7, [
            'Bahías' => '2x 3.5", 3x 2.5"', 'Formato' => 'ATX', 'Ventiladores incluidos' => '3x 140mm',
        ]);
        $insertar('Cajas', 'Phanteks Eclipse P400A', 'Caja ATX con malla frontal y gran airflow a buen precio.', 89.99, 13, [
            'Bahías' => '2x 3.5", 2x 2.5"', 'Formato' => 'ATX', 'Ventiladores incluidos' => '3x 120mm',
        ]);

        // Configuraciones del sistema
DB::table('configuraciones')->insert([
    ['clave' => 'precio_montaje',      'valor' => '50.00', 'descripcion' => 'Precio del servicio de montaje del PC (€)', 'created_at' => now(), 'updated_at' => now()],
    ['clave' => 'montaje_activo',      'valor' => '1',     'descripcion' => 'Servicio de montaje disponible (1=sí, 0=no)', 'created_at' => now(), 'updated_at' => now()],
    ['clave' => 'envio_gratis_desde',  'valor' => '0',     'descripcion' => 'Importe mínimo para envío gratuito (0=siempre gratis)', 'created_at' => now(), 'updated_at' => now()],
    ['clave' => 'email_contacto',      'valor' => 'info@resetinformatica.es', 'descripcion' => 'Email de contacto de la tienda', 'created_at' => now(), 'updated_at' => now()],
    ['clave' => 'telefono_tienda',     'valor' => '959 000 000', 'descripcion' => 'Teléfono de atención al cliente', 'created_at' => now(), 'updated_at' => now()],
]);
    }
}