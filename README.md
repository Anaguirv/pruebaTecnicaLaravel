# Manual de inicio Laravel

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

## 6. Base de datos

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

## Cambiar a base de datos MySQL

Modificar directorio `.env`

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravelapidb
DB_USERNAME=root
DB_PASSWORD=
```

### Script BD

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
(2, '12345678-9', 'Juan', 'Perez', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(3, '23456789-0', 'Maria', 'Gonzalez', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(4, '34567890-1', 'Pedro', 'Ramirez', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(5, '45678901-2', 'Ana', 'Lopez', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(6, '56789012-3', 'Luis', 'Martinez', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(7, '67890123-4', 'Carla', 'Fernandez', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(8, '78901234-5', 'Jose', 'Sanchez', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(9, '89012345-6', 'Laura', 'Diaz', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(10, '90123456-7', 'Miguel', 'Torres', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(11, '01234567-8', 'Sofia', 'Gutierrez', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(12, '11234567-9', 'Diego', 'Rojas', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(13, '21234567-0', 'Valentina', 'Castro', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(14, '31234567-1', 'Javier', 'Vega', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(15, '41234567-2', 'Camila', 'Mendoza', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(16, '51234567-3', 'Rodrigo', 'Silva', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(17, '61234567-4', 'Isabel', 'Morales', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(18, '71234567-5', 'Fernando', 'Herrera', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(19, '81234567-6', 'Daniela', 'Rios', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(20, '91234567-7', 'Ricardo', 'Cruz', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(21, '01345678-9', 'Patricia', 'Reyes', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(22, '11345678-0', 'Andres', 'Ortega', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(23, '21345678-1', 'Monica', 'Vargas', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(24, '31345678-2', 'Francisco', 'Navarro', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(25, '41345678-3', 'Gabriela', 'Pena', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(26, '51345678-4', 'Sebastian', 'Flores', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(27, '61345678-5', 'Paula', 'Cabrera', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(28, '71345678-6', 'Hector', 'Campos', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(29, '81345678-7', 'Natalia', 'Fuentes', '2025-01-11 01:23:28', '2025-01-11 01:23:28'),
(30, '91345678-8', 'Carlos', 'Espinoza', '2025-01-11 01:23:28', '2025-01-11 01:23:28');

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

### Migrar datos a MySQL

```php
php artisan migrate
```

# Frontend