<?php
$servidor = "localhost";
$usuario = "root";
$clave = "";
$base_datos = "inmobiliaria_db";

$conex = mysqli_connect($servidor, $usuario, $clave, $base_datos);

if (!$conex) {
    die("Error de conexión: " . mysqli_connect_error());
}

mysqli_set_charset($conex, "utf8mb4");
?>
