<?php

namespace App\Core;

/**
 * Controlador Base
 * Todas las clases controladoras deben extender de esta.
 */
abstract class BaseController
{
    /**
     * Renderiza una vista inyectando datos
     */
    protected function view($path, $data = [])
    {
        // Convertir array asociativo en variables individuales
        extract($data);

        $viewFile = __DIR__ . "/../Views/" . str_replace('.', '/', $path) . ".php";

        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("Error: La vista '$path' no existe en $viewFile");
        }
    }

    /**
     * Devuelve una respuesta JSON estandarizada para la API
     */
    protected function json($data, $statusCode = 200)
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }

    /**
     * Redirección simple
     */
    protected function redirect($url)
    {
        // Si la URL empieza con /, le ponemos el URL_ROOT
        if (strpos($url, '/') === 0) {
            $url = URL_ROOT . $url;
        }
        header("Location: $url");
        exit;
    }
}
