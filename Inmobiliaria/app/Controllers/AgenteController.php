<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Database;
use PDO;

class AgenteController extends BaseController
{
    private $db;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        if (($_SESSION['rol'] ?? '') !== 'agente') {
            return $this->redirect('/login');
        }

        $this->db = Database::getInstance();
    }

    public function dashboard()
    {
        $id_usuario = $_SESSION['id_usuario'];
        
        $stmtAgent = $this->db->prepare("SELECT * FROM agentes WHERE id_usuario = ?");
        $stmtAgent->execute([$id_usuario]);
        $agente = $stmtAgent->fetch();
        
        if (!$agente) die("Agente no encontrado.");
        $id_agente = $agente['id_agente'];

        $data = ['agente' => $agente];

        // Stats
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM inmuebles WHERE id_agente = ?");
        $stmt->execute([$id_agente]);
        $data['mis_inmuebles'] = $stmt->fetchColumn();

        $stmt = $this->db->prepare("SELECT COUNT(*) FROM reservas WHERE id_agente = ? AND estado = 'pendiente'");
        $stmt->execute([$id_agente]);
        $data['mis_reservas'] = $stmt->fetchColumn();

        $stmt = $this->db->prepare("SELECT COUNT(*) FROM facturas WHERE id_agente = ?");
        $stmt->execute([$id_agente]);
        $data['mis_ventas'] = $stmt->fetchColumn();

        // Billetera
        $stmt = $this->db->prepare("SELECT * FROM billetera_agente WHERE id_agente = ?");
        $stmt->execute([$id_agente]);
        $data['billetera'] = $stmt->fetch();

        if ($data['billetera']) {
            $stmt = $this->db->prepare("SELECT * FROM movimientos_billetera_agente WHERE id_billetera = ? ORDER BY fecha DESC LIMIT 10");
            $stmt->execute([$data['billetera']['id_billetera']]);
            $data['movimientos'] = $stmt->fetchAll();
        }

        // Data
        $stmt = $this->db->prepare("SELECT i.*, inv.tipo as tipo_operacion, inv.precio as precio_pub, inv.fotos FROM inmuebles i LEFT JOIN inventario inv ON i.id_inmueble = inv.id_inmueble AND inv.estado = 'activo' WHERE i.id_agente = ? GROUP BY i.id_inmueble ORDER BY i.fecha_registro DESC");
        $stmt->execute([$id_agente]);
        $data['inmuebles'] = $stmt->fetchAll();

        $stmt = $this->db->prepare("SELECT r.*, c.nombre as cliente_nombre, c.telefono as cliente_tel, im.direccion, im.tipo as tipo_inmueble FROM reservas r JOIN clientes c ON r.id_cliente = c.id_cliente JOIN inmuebles im ON r.id_inmueble = im.id_inmueble WHERE r.id_agente = ? ORDER BY r.fecha_reserva DESC");
        $stmt->execute([$id_agente]);
        $data['reservas'] = $stmt->fetchAll();

        return $this->view('agente.dashboard', $data);
    }
}
