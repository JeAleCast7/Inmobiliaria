<?php

namespace App\Core;

/**
 * Router Minimalista Profesional
 * Se encarga de mapear URLs a Controladores y Métodos.
 */
class Router
{
    protected $routes = [];

    /**
     * Registra una ruta GET
     */
    public function get($path, $callback)
    {
        $this->routes['GET'][$this->formatPath($path)] = $callback;
    }

    /**
     * Registra una ruta POST
     */
    public function post($path, $callback)
    {
        $this->routes['POST'][$this->formatPath($path)] = $callback;
    }

    /**
     * Resuelve la petición actual
     */
    public function resolve()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        
        // Limpiar query strings de la URL
        $position = strpos($path, '?');
        if ($position !== false) {
            $path = substr($path, 0, $position);
        }

        // Manejar subcarpeta si estamos en localhost/Inmobiliaria/public/
        $basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        if ($basePath !== '/') {
            $path = str_replace($basePath, '', $path);
        }

        $path = $this->formatPath($path);
        $callback = $this->routes[$method][$path] ?? false;

        if ($callback === false) {
            http_response_code(404);
            echo "404 - Ruta no encontrada en el nuevo sistema MVC. Path: $path";
            return;
        }

        if (is_array($callback)) {
            $controller = new $callback[0]();
            $methodName = $callback[1];
            return $controller->$methodName();
        }

        if (is_callable($callback)) {
            return call_user_func($callback);
        }
    }

    protected function formatPath($path)
    {
        $path = trim($path, '/');
        return $path === '' ? '/' : '/' . $path;
    }
}
