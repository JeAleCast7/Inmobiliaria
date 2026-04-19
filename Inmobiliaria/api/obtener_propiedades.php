<?php
// api/obtener_propiedades.php

// Asegurar que la respuesta siempre sea en formato JSON y con codificación UTF-8
header('Content-Type: application/json; charset=utf-8');

// Incluir la conexión existente. Notar que estamos dentro de 'api', por lo que subimos un nivel '..'
include("../includes/conexion.php");

// Si falla la conexión, retornar un JSON vacío o con error para que el cliente lo maneje
if (!$conex) {
    echo json_encode(["error" => "No se pudo conectar a la base de datos"]);
    exit;
}

// Mantenemos la consulta original (Límite de 6 para la página principal)
$sql = "SELECT i.*, inv.tipo as tipo_operacion, inv.precio as precio_publicado, inv.fotos, a.telefono as agente_telefono 
        FROM inmuebles i 
        LEFT JOIN inventario inv ON i.id_inmueble = inv.id_inmueble 
        LEFT JOIN agentes a ON i.id_agente = a.id_agente 
        WHERE i.estado = 'disponible' AND inv.estado = 'activo' 
        ORDER BY inv.fecha_publicacion DESC LIMIT 6";

$data = [];
$propiedades = mysqli_query($conex, $sql);

if ($propiedades && mysqli_num_rows($propiedades) > 0) {
    while ($prop = mysqli_fetch_assoc($propiedades)) {
        $data[] = $prop;
    }
}

// Retornamos los datos limpios en JSON
echo json_encode($data);
?>
