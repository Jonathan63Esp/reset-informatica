# Reset Informática

Tienda online de componentes de ordenador con configurador inteligente de PC y servicio de montaje profesional. Proyecto final del C.F.G.S. Desarrollo de Aplicaciones Web — I.E.S. La Marisma, Huelva.

---

## Características principales

- **Catálogo de productos** organizado por categorías con buscador y filtros
- **Configurador de PC** con wizard de 9 pasos y validación de compatibilidad en tiempo real
- **Servicio de montaje profesional** configurable desde el panel de administración
- **Carrito de la compra** con actualización de precios en tiempo real
- **Proceso de pedido** completo con formulario de envío y confirmación
- **Factura PDF** descargable desde el historial de pedidos
- **Panel de administración** con dashboard, gestión de productos, pedidos, usuarios y configuraciones del sistema
- **Autenticación** por nombre de usuario con cifrado bcrypt

---

## Stack tecnológico

| Tecnología | Versión |
|-----------|---------|
| PHP | 8.2.12 |
| Laravel | 12.12.1 |
| MariaDB | 10.4.32 |
| Node.js | 20.17.0 |
| Vite | 6.x |
| barryvdh/laravel-dompdf | 3.x |

---

## Requisitos

- PHP 8.2 o superior
- Composer 2.x
- Node.js 18 o superior
- MySQL / MariaDB
- XAMPP (entorno local) o servidor con Apache/Nginx

---

## Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/TU_USUARIO/reset-informatica.git
cd reset-informatica
```

### 2. Instalar dependencias PHP

```bash
composer install
```

### 3. Instalar dependencias Node.js

```bash
npm install
```

### 4. Configurar el entorno

```bash
cp .env.example .env
php artisan key:generate
```

Edita el archivo `.env` con los datos de tu base de datos:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=reset_informatica
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Crear la base de datos

Crea una base de datos llamada `reset_informatica` en phpMyAdmin o desde la terminal:

```sql
CREATE DATABASE reset_informatica CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 6. Ejecutar migraciones y seeders

```bash
php artisan migrate --seed
```

Esto crea todas las tablas y las pobla con:
- 8 categorías de componentes + categoría Servicios
- Más de 60 productos con atributos técnicos reales
- 1 producto de servicio de montaje profesional
- 5 configuraciones del sistema
- Usuario administrador por defecto

### 7. Crear enlace simbólico para imágenes

```bash
php artisan storage:link
```

### 8. Compilar assets

```bash
npm run dev
```

### 9. Iniciar el servidor

```bash
php artisan serve
```

Accede a [http://localhost:8000](http://localhost:8000)

---

## Credenciales por defecto

| Rol | Usuario | Contraseña |
|-----|---------|------------|
| Administrador | `cad123` | `password123` |

---

## Estructura del proyecto

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/AuthController.php
│   │   ├── Admin/
│   │   │   ├── DashboardController.php
│   │   │   ├── ProductoController.php
│   │   │   ├── PedidoController.php
│   │   │   ├── UsuarioController.php
│   │   │   └── CategoriaController.php
│   │   ├── ConfiguradorController.php
│   │   ├── CarritoController.php
│   │   ├── PedidoController.php
│   │   ├── CatalogoController.php
│   │   └── BusquedaController.php
│   └── Middleware/EsAdmin.php
├── Models/
│   ├── User.php
│   ├── Categoria.php
│   ├── Producto.php
│   ├── Atributo.php
│   ├── AtributoValor.php
│   ├── CarritoItem.php
│   ├── Pedido.php
│   ├── PedidoItem.php
│   └── Configuracion.php
└── Services/
    └── CompatibilidadService.php
database/
├── migrations/
└── seeders/
resources/views/
├── layouts/app.blade.php
├── admin/
├── auth/
├── carrito/
├── catalogo/
├── configurador/
├── pedidos/
├── busqueda/
├── pages/
└── errors/
routes/
└── web.php
docs/
├── api/                    ← Documentación PHPDoc generada
├── Reset_Informatica_Memoria_v2.docx
├── Reset_Informatica_Documentacion.docx
└── Reset_Informatica_Presentacion.pptx
```

---

## Configurador de PC

El configurador guía al usuario en 9 pasos:

| Paso | Categoría |
|------|-----------|
| 1 | Procesadores |
| 2 | Placas base |
| 3 | Memoria RAM |
| 4 | Tarjetas gráficas |
| 5 | Fuentes de alimentación |
| 6 | Almacenamiento |
| 7 | Refrigeración |
| 8 | Cajas |
| 9 | Montaje (obligatorio) |

**Plataformas disponibles:**
- AMD AM4 — Socket AM4, DDR4
- AMD AM5 — Socket AM5, DDR5
- Intel LGA1200 — Socket LGA1200, DDR4
- Intel LGA1700 — Socket LGA1700, DDR4/DDR5

**Reglas de compatibilidad:**
- CPU ↔ Placa base: el Socket debe coincidir
- Plataforma ↔ RAM: el tipo de RAM debe ser compatible

---

## Panel de administración

Accesible en `/admin` solo para usuarios con rol administrador.

| Sección | Funcionalidad |
|---------|--------------|
| Dashboard | Métricas, gráfico de ventas y top productos |
| Productos | CRUD completo con imagen y atributos |
| Categorías | Crear, editar y eliminar categorías |
| Pedidos | Listar, ver detalle y cambiar estado |
| Usuarios | Listar, cambiar rol y eliminar |

---

## Configuraciones del sistema

Parámetros editables desde la base de datos:

| Clave | Valor por defecto | Descripción |
|-------|------------------|-------------|
| `precio_montaje` | `50.00` | Precio del servicio de montaje (€) |
| `montaje_activo` | `1` | Servicio disponible (1=sí, 0=no) |
| `envio_gratis_desde` | `0` | Importe mínimo para envío gratuito |
| `email_contacto` | `info@resetinformatica.es` | Email de contacto |
| `telefono_tienda` | `959 000 000` | Teléfono de atención |

---

## Comandos útiles

```bash
# Resetear la base de datos con datos de ejemplo
php artisan migrate:fresh --seed

# Limpiar caché
php artisan cache:clear

# Ver todas las rutas
php artisan route:list

# Compilar assets para producción
npm run build

# Generar documentación PHPDoc
php phpDocumentor.phar run -d app -t docs/api
```

---

## Documentación

| Documento | Descripción |
|-----------|-------------|
| `docs/Reset_Informatica_Memoria_v2.docx` | Memoria del proyecto (anteproyecto, análisis, diseño, implementación) |
| `docs/Reset_Informatica_Documentacion.docx` | Documentación técnica, manual de usuario y manual de administración |
| `docs/Reset_Informatica_Presentacion.pptx` | Presentación del proyecto |
| `docs/api/` | Documentación del código fuente generada con PHPDocumentor |

---

## Autor

**Jonatan Cárdenas Gómez**
C.F.G.S. Desarrollo de Aplicaciones Web
I.E.S. La Marisma — Huelva
Marzo 2026

---

## Licencia

Este proyecto se publica bajo la licencia MIT.
