<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Database;
use PDO;

class AdminController extends BaseController
{
    private $db;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        if (($_SESSION['rol'] ?? '') !== 'admin') {
            return $this->redirect('/login');
        }

        $this->db = Database::getInstance();
    }

    public function dashboard()
    {
        $data = [];

        // Stats
        $data['total_clientes'] = $this->db->query("SELECT COUNT(*) FROM clientes")->fetchColumn();
        $data['total_agentes'] = $this->db->query("SELECT COUNT(*) FROM agentes")->fetchColumn();
        $data['total_inmuebles'] = $this->db->query("SELECT COUNT(*) FROM inmuebles")->fetchColumn();
        $data['total_reservas'] = $this->db->query("SELECT COUNT(*) FROM reservas WHERE estado='pendiente'")->fetchColumn();
        $data['total_facturas'] = $this->db->query("SELECT COUNT(*) FROM facturas")->fetchColumn();
        $data['total_disponibles'] = $this->db->query("SELECT COUNT(*) FROM inmuebles WHERE estado='disponible'")->fetchColumn();

        // Recent data
        $data['ultimos_clientes'] = $this->db->query("SELECT c.*, u.email FROM clientes c JOIN usuarios u ON c.id_usuario = u.id_usuario ORDER BY c.fecha_registro DESC LIMIT 5")->fetchAll();
        $data['ultimos_inmuebles'] = $this->db->query("SELECT i.*, a.nombre as agente_nombre FROM inmuebles i LEFT JOIN agentes a ON i.id_agente = a.id_agente ORDER BY i.fecha_registro DESC LIMIT 5")->fetchAll();
        $data['ultimas_reservas'] = $this->db->query("SELECT r.*, c.nombre as cliente_nombre, im.direccion, im.tipo as tipo_inmueble FROM reservas r JOIN clientes c ON r.id_cliente = c.id_cliente JOIN inmuebles im ON r.id_inmueble = im.id_inmueble ORDER BY r.fecha_reserva DESC LIMIT 5")->fetchAll();

        // All data
        $data['agentes'] = $this->db->query("SELECT a.*, u.email as user_email, u.activo FROM agentes a JOIN usuarios u ON a.id_usuario = u.id_usuario ORDER BY a.fecha_ingreso DESC")->fetchAll();
        $data['clientes_all'] = $this->db->query("SELECT c.*, u.email as user_email, u.activo FROM clientes c JOIN usuarios u ON c.id_usuario = u.id_usuario ORDER BY c.fecha_registro DESC")->fetchAll();
        $data['inmuebles_all'] = $this->db->query("SELECT i.*, a.nombre as agente_nombre, a.telefono as agente_telefono, inv.fotos, inv.precio as precio_pub FROM inmuebles i LEFT JOIN agentes a ON i.id_agente = a.id_agente LEFT JOIN inventario inv ON i.id_inmueble = inv.id_inmueble AND inv.estado = 'activo' GROUP BY i.id_inmueble ORDER BY i.fecha_registro DESC")->fetchAll();
        $data['reservas_all'] = $this->db->query("SELECT r.*, c.nombre as cliente_nombre, im.direccion, im.tipo as tipo_inmueble, a.nombre as agente_nombre FROM reservas r JOIN clientes c ON r.id_cliente = c.id_cliente JOIN inmuebles im ON r.id_inmueble = im.id_inmueble LEFT JOIN agentes a ON r.id_agente = a.id_agente ORDER BY r.fecha_reserva DESC")->fetchAll();
        $data['facturas_all'] = $this->db->query("SELECT f.*, c.nombre as cliente_nombre FROM facturas f JOIN clientes c ON f.id_cliente = c.id_cliente ORDER BY f.fecha DESC")->fetchAll();
        $data['inventario_all'] = $this->db->query("SELECT inv.*, im.direccion, im.tipo as tipo_inmueble FROM inventario inv JOIN inmuebles im ON inv.id_inmueble = im.id_inmueble ORDER BY inv.fecha_publicacion DESC")->fetchAll();
        $data['permisos_all'] = $this->db->query("SELECT g.*, im.direccion, im.tipo as tipo_inmueble FROM gobierno g JOIN inmuebles im ON g.id_inmueble = im.id_inmueble ORDER BY g.fecha_solicitud DESC")->fetchAll();

        return $this->view('admin.dashboard', $data);
    }
}
