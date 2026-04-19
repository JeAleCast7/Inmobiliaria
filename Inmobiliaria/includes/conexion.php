<?php
mysqli_report(MYSQLI_REPORT_OFF);

$servidor = "localhost";
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
