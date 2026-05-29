<?php

/**
 * Front Controller Profesional - Inmobiliaria Pro
 * Punto de entrada único que gestiona todas las peticiones.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Definir la raíz de la URL dinámicamente
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$scriptName = $_SERVER['SCRIPT_NAME']; // ej: /Inmobiliaria/Inmobiliaria/public/index.php
$baseUrl = str_replace('\\', '/', dirname($scriptName)); // /Inmobiliaria/Inmobiliaria/public
// Asegurarse de que no termine en /
$baseUrl = rtrim($baseUrl, '/');
define('URL_ROOT', $baseUrl);

// Cargar .env manualmente (Simple Loader)
$envPath = __DIR__ . '/../.env';
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
        [$name, $value] = explode('=', $line, 2);
        $name  = trim($name);
        $value = trim($value, " \t\n\r\0\x0B\"\'`");
        putenv("{$name}={$value}");
        $_ENV[$name]   = $value;
        $_SERVER[$name] = $value;
    }
}

require_once __DIR__ . '/../app/Core/Autoloader.php';

use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\AdminController;
use App\Controllers\AgenteController;
use App\Controllers\ClienteController;
use App\Controllers\PropertyController;

$router = new Router();

// --- RUTAS PÚBLICAS ---
$router->get('/', [HomeController::class, 'index']);

// --- AUTENTICACIÓN ---
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/logout', [AuthController::class, 'logout']);

// --- API ---
$router->get('/api/properties/featured', [PropertyController::class, 'getFeatured']);

// --- DASHBOARDS PROTEGIDOS ---
$router->get('/admin/dashboard', [AdminController::class, 'dashboard']);
$router->get('/admin/inmuebles/crear', [AdminController::class, 'createInmueble']);
$router->post('/admin/inmuebles/store', [AdminController::class, 'storeInmueble']);
$router->get('/admin/inmuebles/editar', [AdminController::class, 'editInmueble']);
$router->post('/admin/inmuebles/update', [AdminController::class, 'updateInmueble']);
$router->post('/admin/inmuebles/eliminar', [AdminController::class, 'deleteInmueble']);
$router->get('/admin/agentes/crear', [AdminController::class, 'createAgente']);
$router->post('/admin/agentes/store', [AdminController::class, 'storeAgente']);
$router->get('/admin/agentes/editar', [AdminController::class, 'editAgente']);
$router->post('/admin/agentes/update', [AdminController::class, 'updateAgente']);
$router->post('/admin/agentes/eliminar', [AdminController::class, 'deleteAgente']);
$router->post('/admin/clientes/eliminar', [AdminController::class, 'deleteCliente']);
$router->get('/admin/facturas/recibo', [AdminController::class, 'descargarRecibo']);
$router->get('/agente/dashboard', [AgenteController::class, 'dashboard']);
$router->post('/agente/habilitar-pago', [AgenteController::class, 'storeHabilitarPago']);
$router->post('/agente/agendar-reserva', [AgenteController::class, 'storeReserva']);
$router->get('/cliente/dashboard', [ClienteController::class, 'dashboard']);
$router->get('/cliente/pasarela-pago', [ClienteController::class, 'pasarelaPago']);
$router->post('/cliente/procesar-pago', [ClienteController::class, 'procesarPago']);
$router->get('/cliente/descargar-recibo', [ClienteController::class, 'descargarRecibo']);

// Ejecutar el Router
$router->resolve();
