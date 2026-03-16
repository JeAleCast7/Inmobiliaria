<?php
include("../includes/sesion.php");
include("../includes/conexion.php");
verificar_rol('agente');

$id_usuario = $_SESSION['id_usuario'];
$agente = mysqli_fetch_assoc(mysqli_query($conex, "SELECT * FROM agentes WHERE id_usuario = $id_usuario"));
$id_agente = $agente['id_agente'];

// Stats
$mis_inmuebles = mysqli_fetch_assoc(mysqli_query($conex, "SELECT COUNT(*) as total FROM inmuebles WHERE id_agente = $id_agente"))['total'];
$mis_reservas = mysqli_fetch_assoc(mysqli_query($conex, "SELECT COUNT(*) as total FROM reservas WHERE id_agente = $id_agente AND estado = 'pendiente'"))['total'];
$mis_ventas = mysqli_fetch_assoc(mysqli_query($conex, "SELECT COUNT(*) as total FROM facturas WHERE id_agente = $id_agente"))['total'];

// Billetera agente
$billetera = mysqli_fetch_assoc(mysqli_query($conex, "SELECT * FROM billetera_agente WHERE id_agente = $id_agente"));

$movimientos = null;
if ($billetera) {
    $id_billetera = $billetera['id_billetera'];
    $movimientos = mysqli_query($conex, "SELECT * FROM movimientos_billetera_agente WHERE id_billetera = $id_billetera ORDER BY fecha DESC LIMIT 10");
}

// Data
$inmuebles = mysqli_query($conex, "SELECT i.*, inv.tipo as tipo_operacion, inv.precio as precio_pub FROM inmuebles i LEFT JOIN inventario inv ON i.id_inmueble = inv.id_inmueble WHERE i.id_agente = $id_agente ORDER BY i.fecha_registro DESC");
$reservas = mysqli_query($conex, "SELECT r.*, c.nombre as cliente_nombre, c.telefono as cliente_tel, im.direccion, im.tipo as tipo_inmueble FROM reservas r JOIN clientes c ON r.id_cliente = c.id_cliente JOIN inmuebles im ON r.id_inmueble = im.id_inmueble WHERE r.id_agente = $id_agente ORDER BY r.fecha_reserva DESC");

include("index_view.html");
?>
