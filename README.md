# Manual de inicio Laravel

## Ruta para visualizar lista de empleados en local

> http://localhost:8000/employees
> 

Precondiciones:

- Clonar repositorio
    
    ```
    git clone https://github.com/Anaguirv/pruebaTecnicaLaravel.git
    ```
    
- Crear base de datos en phpMyAdmin
    - Nombre = laravelapidb
- Crear tablas
    - Script en punto 10.2
- Ingresar al navegador en la ruta http://localhost:8000/employees

# Backend

## 1. Crear proyecto Laravel

```bash
composer create-project laravel/laravel laravel-crud-api
```

## 2. Arrancar servidor

```bash
php -S localhost:8000 -t public/
```

## 4. Instalar API

```bash
php artisan install:api
```

## 5. Probar rutas

Modificar modulo routes/api.php

```php
Route::get('/employee', function () {
    return 'Obteniendo lista de empleados';
});

Route::get('/employee/{id}', function () {
    return 'Obteniendo un empleado';
});
```

Probar rutas :

- http://localhost:8000/api/employee/
- http://localhost:8000/api/employee/1

## 6. Base de datos SQLite

### 6.1 Crear tabla

```php
 php artisan make:migration create_employee_table
```

### 6.2 Indicar atributos de la tabla

database/migrations

```php
        Schema::create('employee', function (Blueprint $table) {
            $table->id();
            $table->rut();
            $table->first_name();
            $table->last_name();
            $table->timestamps();
        });
```

### 6.3 Realizar migración para crear tabla en SQLite

```php
php artisan migrate
```

## 7. Crear Modelo

```php
php artisan make:model Employee
```

### Indicar nombre de tabla y campos

```php
class Employee extends Model
{
    //
    protected $table = 'employee';

    protected $fillable = [
        'rut',
        'first_name',
        'last_name'
    ];
}
```

## 8. Crear Controlador

```php
php artisan make:controller Api/employeeController
```

### 8.1 Crear método para mostrar registros de tabla `Employee`

```php
/**
 * Lista todos los empleados.
 * 
 * @return \Illuminate\Http\JsonResponse
*/
public function index()
{
    $employees = Employee::all();

    if ($employees->isEmpty()){
        $data = [
            'message' => 'No se encontraron empleados',
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    $data = [
        'employees' => $employees,
        'status' => 200 
    ];
    return response()->json($data, 200);
}
```

### 8.2 Crear método para **insertar** registro de tabla `Employee`

```php
/**
 * Crea un nuevo empleado.
 * 
 * @param  \Illuminate\Http\Request $request
 * @return \Illuminate\Http\JsonResponse
*/
public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'rut'           => 'required',
        'first_name'    => 'required',
        'last_name'     => 'required'
    ]);

    if ($validator->fails()){
        $data = [
            'message' => 'Error en la validación de los datos',
            'errors' => $validator->errors(),
            'status' => 400
        ];
        return response()->json($data, 400);
    }

    $employee = Employee::create([
        'rut'           => $request->rut,
        'first_name'    => $request->first_name,
        'last_name'     => $request->last_name
    ]);

    if (!$employee){
        $data=[
            'message' => 'Error al crear al empleado',
            'status' => 500
        ];
        return response()->json($data, 500);
    }

    $data = [
        'employee' => $employee,
        'status' => 201
    ];
    return response()->json($data, 201);
}
```

### 8.3 Actualizar rutas

```php

use App\Http\Controllers\Api\employeeController;

Route::get('/employee', [employeeController::class, 'index']);

Route::get('/employee/{id}', [employeeController::class, 'show']);

Route::post('/employee', [employeeController::class, 'store']);
```

## 9. Probar API con Thunder Client

`Metodo POST` http://localhost:8000/api/employee

`Body`

```bash
{
"rut" : "17885639-2",
"first_name" : "Arnoldo",
"last_name" : "Aguirre"
}
```

> **Presionar botón Send**
> 

`Response`

```bash
{
"employee": {
"rut": "17885639-2",
"first_name": "Arnoldo",
"last_name": "Aguirre",
"updated_at": "2025-01-10T23:35:07.000000Z",
"created_at": "2025-01-10T23:35:07.000000Z",
"id": 1
},
"status": 201
}
```

## 10. Cambiar a base de datos MySQL

### 10.1 Modificar directorio `.env`

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravelapidb
DB_USERNAME=root
DB_PASSWORD=
```

No se configuró usuario  y contraseña, ya que es un entorno de pruebas.

### 10.2 Script BD

```sql
-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 11-01-2025 a las 01:43:59
-- Versión del servidor: 8.0.31
-- Versión de PHP: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `laravelapidb`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `employee`
--

DROP TABLE IF EXISTS `employee`;
CREATE TABLE IF NOT EXISTS `employee` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `rut` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `employee`
--

INSERT INTO `employee` (`id`, `rut`, `first_name`, `last_name`, `created_at`, `updated_at`) VALUES
(1, '17885639-3', 'Nicolas', 'Valdovino', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(2, '23456789-0', 'Juan', 'Perez', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(3, '91234567-9', 'Maria', 'Gonzalez', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(4, '19901234-7', 'Pedro', 'Ramirez', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(5, '22345678-1', 'Ana', 'Lopez', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(6, '24567890-2', 'Luis', 'Martinez', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(7, '26789012-3', 'Carla', 'Fernandez', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(8, '28901234-4', 'Jose', 'Sanchez', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(9, '21012345-5', 'Laura', 'Diaz', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(10, '23234567-6', 'Miguel', 'Torres', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(11, '25456789-7', 'Sofia', 'Gutierrez', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(12, '27678901-8', 'Diego', 'Rojas', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(13, '29890123-9', 'Valentina', 'Castro', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(14, '22012345-0', 'Javier', 'Vega', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(15, '24234567-1', 'Camila', 'Mendoza', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(16, '26456789-2', 'Rodrigo', 'Silva', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(17, '28678901-3', 'Isabel', 'Morales', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(18, '20890123-4', 'Fernando', 'Herrera', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(19, '23012345-5', 'Daniela', 'Rios', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(20, '25234567-6', 'Ricardo', 'Cruz', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(21, '27456789-7', 'Patricia', 'Reyes', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(22, '29678901-8', 'Andres', 'Ortega', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(23, '21890123-9', 'Monica', 'Vargas', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(24, '24012345-0', 'Francisco', 'Navarro', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(25, '26234567-1', 'Gabriela', 'Pena', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(26, '28456789-2', 'Sebastian', 'Flores', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(27, '20678901-3', 'Paula', 'Cabrera', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(28, '22890123-4', 'Hector', 'Campos', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(29, '25012345-5', 'Natalia', 'Fuentes', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(30, '27234567-6', 'Carlos', 'Espinoza', '2025-01-11 01:23:28', '2025-01-11 01:23:28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb3_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_01_10_215832_create_personal_access_tokens_table', 1),
(5, '2025_01_10_220811_create_employee_table', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb3_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb3_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb3_unicode_ci,
  `payload` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

```

### 10.2 Migrar datos a MySQL

```php
php artisan migrate
```

# Frontend

Crear fichero base.blade.php en `resouces/views/layouts`

```php
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    
    <title>Empleados</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.2/font/bootstrap-icons.min.css">

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>

<body class="bg-dark text-white">
   
    <div class="container">
        <div class="content mt-5 row">
            <div class="col-12">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>
</html>
```

Crear fichero index.blade.php en `resouces/views/`  heredando maquetado y estilos de base.blade.php

```php
@extends('layouts.base')

@section('content')
<div class="row">

    <div class="col-12 text-center mb-5">
        <h1>Lista de Empleados</h1>
    </div>

    <div class="col-12">
        <table class="table table-striped table-hover table-bordered table-dark" id="employees-table" 
        style="box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.2)">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>RUT</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                </tr>
            </thead>
            <tbody>
                <!-- Los datos se insertarán aquí dinámicamente -->
            </tbody>
        </table>
    </div>

    <div class="row">
        <div class="col-6">
            <!-- Mostrar cantidad total de registros -->
            <div id="total-records" class="text-start mb-3"></div>
        </div>

        <div class="col-6">
            <!-- Paginador -->
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-end pagination-dark" id="pagination">
                    <!-- Los elementos de paginación se insertarán aquí dinámicamente -->
                </ul>
            </nav>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            const loadEmployees = async (url) => {
                try {
                    const response = await axios.get(url);
                    const employees = response.data.employees.data;
                    const pagination = response.data.employees.links;
                    const totalRecords = response.data.employees.total;
                    const currentPage = response.data.employees.current_page;
                    const perPage = response.data.employees.per_page;

                    // Limpiar la tabla y el paginador
                    const tableBody = document.querySelector('#employees-table tbody');
                    tableBody.innerHTML = '';
                    const paginationElement = document.querySelector('#pagination');
                    paginationElement.innerHTML = '';

                    // Insertar los datos en la tabla
                    employees.forEach(employee => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${employee.id}</td>
                            <td>${employee.rut}</td>
                            <td>${employee.first_name}</td>
                            <td>${employee.last_name}</td>
                        `;
                        tableBody.appendChild(row);
                    });

                    // Mostrar cantidad total de registros
                    const firstItem = (currentPage - 1) * perPage + 1;
                    const lastItem = firstItem + employees.length - 1;
                    document.querySelector('#total-records').innerText = `Mostrando ${firstItem} a ${lastItem} de un total de ${totalRecords} registros`;

                    // Generar paginación
                    pagination.forEach(link => {
                        const pageItem = document.createElement('li');
                        pageItem.classList.add('page-item');
                        if (link.active) {
                            pageItem.classList.add('active');
                        }

                        // Reemplazar texto "Previous" y "Next" por iconos
                        let label = link.label;
                        if (label.includes('Previous')) {
                            label = '<i class="bi bi-chevron-left"></i>';
                        } else if (label.includes('Next')) {
                            label = '<i class="bi bi-chevron-right"></i>';
                        }

                        pageItem.innerHTML = `
                            <a class="page-link bg-dark text-white" href="#" data-url="${link.url}">${label}</a>
                        `;
                        paginationElement.appendChild(pageItem);
                    });

                    // Agregar evento de clic a los enlaces de paginación
                    document.querySelectorAll('.page-link').forEach(link => {
                        link.addEventListener('click', (e) => {
                            e.preventDefault();
                            const url = e.target.closest('a').getAttribute('data-url');
                            if (url) {
                                loadEmployees(url);
                            }
                        });
                    });
                } catch (error) {
                    console.error('Error al consumir la API:', error);
                }
            };

            // Cargar la primera página de empleados
            loadEmployees('http://localhost:8000/api/employee');
        });
    </script>
</div>
@endsection

```

Crear ruta para acceder al front  en `routes/web.php`

```php
Route::view('/employees', 'index');
```