<?php

/**
 * Autoloader Manual PSR-4
 * Registra una función para cargar clases automáticamente basada en el namespace 'App'.
 */
spl_autoload_register(function ($class) {
    // Prefijo de namespace para el proyecto
    $prefix = 'App\\';

    // Directorio base para el prefijo de namespace
    $base_dir = __DIR__ . '/../';

    // ¿La clase usa el prefijo del namespace?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // No, pasar al siguiente autoloader registrado
        return;
    }

    // Obtener el nombre relativo de la clase
    $relative_class = substr($class, $len);

    // Reemplazar el prefijo del namespace con el directorio base,
    // reemplazar los separadores de namespace con separadores de directorio,
    // y añadir .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // Si el archivo existe, cargarlo
    if (file_exists($file)) {
        require $file;
    }
});
