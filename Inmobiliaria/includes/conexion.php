<?php
mysqli_report(MYSQLI_REPORT_OFF);

//Antes estaba $servidor = "localhost";
//  Si Docker pasa DB_HOST, usamos ese valor (ej: 'db'), sino usamos el valor por defecto ('localhost' para XAMPP).
$servidor = getenv('DB_HOST') ?: "localhost";
$usuario = "root";
$clave = "";
$base_datos = "inmobiliaria_db";

$conex = @mysqli_connect($servidor, $usuario, $clave, $base_datos);

if (!$conex) {
    error_log("Error de conexión: " . mysqli_connect_error());
    $conex = null; // no muere, solo marca como no disponible
} else {
    mysqli_set_charset($conex, "utf8mb4");
}
?>