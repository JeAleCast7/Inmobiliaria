<?php
include("../includes/sesion.php");
include("../includes/conexion.php");
verificar_rol('admin');

// Stats
$total_clientes = mysqli_fetch_assoc(mysqli_query($conex, "SELECT COUNT(*) as total FROM clientes"))['total'];
$total_agentes = mysqli_fetch_assoc(mysqli_query($conex, "SELECT COUNT(*) as total FROM agentes"))['total'];
$total_inmuebles = mysqli_fetch_assoc(mysqli_query($conex, "SELECT COUNT(*) as total FROM inmuebles"))['total'];
$total_reservas = mysqli_fetch_assoc(mysqli_query($conex, "SELECT COUNT(*) as total FROM reservas WHERE estado='pendiente'"))['total'];
$total_facturas = mysqli_fetch_assoc(mysqli_query($conex, "SELECT COUNT(*) as total FROM facturas"))['total'];
$total_disponibles = mysqli_fetch_assoc(mysqli_query($conex, "SELECT COUNT(*) as total FROM inmuebles WHERE estado='disponible'"))['total'];

// Recent data
$ultimos_clientes = mysqli_query($conex, "SELECT c.*, u.email FROM clientes c JOIN usuarios u ON c.id_usuario = u.id_usuario ORDER BY c.fecha_registro DESC LIMIT 5");
$ultimos_inmuebles = mysqli_query($conex, "SELECT i.*, a.nombre as agente_nombre FROM inmuebles i LEFT JOIN agentes a ON i.id_agente = a.id_agente ORDER BY i.fecha_registro DESC LIMIT 5");
$ultimas_reservas = mysqli_query($conex, "SELECT r.*, c.nombre as cliente_nombre, im.direccion, im.tipo as tipo_inmueble FROM reservas r JOIN clientes c ON r.id_cliente = c.id_cliente JOIN inmuebles im ON r.id_inmueble = im.id_inmueble ORDER BY r.fecha_reserva DESC LIMIT 5");

// All data for sections
$agentes = mysqli_query($conex, "SELECT a.*, u.email as user_email, u.activo FROM agentes a JOIN usuarios u ON a.id_usuario = u.id_usuario ORDER BY a.fecha_ingreso DESC");
$clientes_all = mysqli_query($conex, "SELECT c.*, u.email as user_email, u.activo FROM clientes c JOIN usuarios u ON c.id_usuario = u.id_usuario ORDER BY c.fecha_registro DESC");
$inmuebles_all = mysqli_query($conex, "SELECT i.*, a.nombre as agente_nombre FROM inmuebles i LEFT JOIN agentes a ON i.id_agente = a.id_agente ORDER BY i.fecha_registro DESC");
$reservas_all = mysqli_query($conex, "SELECT r.*, c.nombre as cliente_nombre, im.direccion, im.tipo as tipo_inmueble, a.nombre as agente_nombre FROM reservas r JOIN clientes c ON r.id_cliente = c.id_cliente JOIN inmuebles im ON r.id_inmueble = im.id_inmueble LEFT JOIN agentes a ON r.id_agente = a.id_agente ORDER BY r.fecha_reserva DESC");
$facturas_all = mysqli_query($conex, "SELECT f.*, c.nombre as cliente_nombre FROM facturas f JOIN clientes c ON f.id_cliente = c.id_cliente ORDER BY f.fecha DESC");
$inventario_all = mysqli_query($conex, "SELECT inv.*, im.direccion, im.tipo as tipo_inmueble FROM inventario inv JOIN inmuebles im ON inv.id_inmueble = im.id_inmueble ORDER BY inv.fecha_publicacion DESC");
$permisos_all = mysqli_query($conex, "SELECT g.*, im.direccion, im.tipo as tipo_inmueble FROM gobierno g JOIN inmuebles im ON g.id_inmueble = im.id_inmueble ORDER BY g.fecha_solicitud DESC");

include("index_view.html");
?>
