<?php
include("../includes/sesion.php");
include("../includes/conexion.php");
verificar_rol('cliente');

$id_usuario = $_SESSION['id_usuario'];
$cliente = mysqli_fetch_assoc(mysqli_query($conex, "SELECT * FROM clientes WHERE id_usuario = $id_usuario"));
$id_cliente = $cliente['id_cliente'];

// Billetera
$billetera = mysqli_fetch_assoc(mysqli_query($conex, "SELECT * FROM billetera WHERE id_cliente = $id_cliente"));

// Stats
$mis_reservas = mysqli_fetch_assoc(mysqli_query($conex, "SELECT COUNT(*) as total FROM reservas WHERE id_cliente = $id_cliente"))['total'];

// Propiedades disponibles
$propiedades = mysqli_query($conex, "SELECT i.*, inv.tipo as tipo_operacion, inv.precio as precio_pub, a.nombre as agente_nombre, a.telefono as agente_tel
    FROM inmuebles i
    JOIN inventario inv ON i.id_inmueble = inv.id_inmueble
    LEFT JOIN agentes a ON i.id_agente = a.id_agente
    WHERE i.estado = 'disponible' AND inv.estado = 'activo'
    ORDER BY inv.fecha_publicacion DESC");

// Mis reservas
$reservas = mysqli_query($conex, "SELECT r.*, im.direccion, im.tipo as tipo_inmueble, im.precio, a.nombre as agente_nombre
    FROM reservas r
    JOIN inmuebles im ON r.id_inmueble = im.id_inmueble
    LEFT JOIN agentes a ON r.id_agente = a.id_agente
    WHERE r.id_cliente = $id_cliente
    ORDER BY r.fecha_reserva DESC");

// Movimientos billetera
$movimientos = null;
if ($billetera) {
    $id_billetera = $billetera['id_billetera'];
    $movimientos = mysqli_query($conex, "SELECT * FROM movimientos_billetera WHERE id_billetera = $id_billetera ORDER BY fecha DESC LIMIT 10");
}

include("index_view.html");
?>
