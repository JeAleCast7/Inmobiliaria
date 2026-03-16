<?php
include("includes/conexion.php");

// Cargar propiedades destacadas
$sql = "SELECT i.*, inv.tipo as tipo_operacion, inv.precio as precio_publicado 
        FROM inmuebles i 
        LEFT JOIN inventario inv ON i.id_inmueble = inv.id_inmueble 
        WHERE i.estado = 'disponible' AND inv.estado = 'activo' 
        ORDER BY inv.fecha_publicacion DESC LIMIT 6";
$propiedades = mysqli_query($conex, $sql);

include("index_view.html");
?>
