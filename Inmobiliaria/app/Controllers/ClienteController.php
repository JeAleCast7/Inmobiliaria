<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Database;
use PDO;

class ClienteController extends BaseController
{
    private $db;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        if (($_SESSION['rol'] ?? '') !== 'cliente') {
            return $this->redirect('/login');
        }

        $this->db = Database::getInstance();
    }

    public function dashboard()
    {
        $id_usuario = $_SESSION['id_usuario'];
        
        $stmtClient = $this->db->prepare("SELECT * FROM clientes WHERE id_usuario = ?");
        $stmtClient->execute([$id_usuario]);
        $cliente = $stmtClient->fetch();
        
        if (!$cliente) die("Cliente no encontrado.");
        $id_cliente = $cliente['id_cliente'];

        $data = ['cliente' => $cliente];

        // Billetera
        $stmt = $this->db->prepare("SELECT * FROM billetera WHERE id_cliente = ?");
        $stmt->execute([$id_cliente]);
        $data['billetera'] = $stmt->fetch();

        // Stats
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM reservas WHERE id_cliente = ?");
        $stmt->execute([$id_cliente]);
        $data['mis_reservas'] = $stmt->fetchColumn();

        // Propiedades disponibles
        $data['propiedades'] = $this->db->query("SELECT i.*, inv.tipo as tipo_operacion, inv.precio as precio_pub, inv.fotos, a.nombre as agente_nombre, a.telefono as agente_tel
            FROM inmuebles i
            JOIN inventario inv ON i.id_inmueble = inv.id_inmueble
            LEFT JOIN agentes a ON i.id_agente = a.id_agente
            WHERE i.estado = 'disponible' AND inv.estado = 'activo'
            GROUP BY i.id_inmueble
            ORDER BY inv.fecha_publicacion DESC")->fetchAll();

        // Mis reservas
        $stmt = $this->db->prepare("SELECT r.*, im.direccion, im.tipo as tipo_inmueble, im.precio, a.nombre as agente_nombre
            FROM reservas r
            JOIN inmuebles im ON r.id_inmueble = im.id_inmueble
            LEFT JOIN agentes a ON r.id_agente = a.id_agente
            WHERE r.id_cliente = ?
            ORDER BY r.fecha_reserva DESC");
        $stmt->execute([$id_cliente]);
        $data['reservas'] = $stmt->fetchAll();

        // Movimientos
        if ($data['billetera']) {
            $stmt = $this->db->prepare("SELECT * FROM movimientos_billetera WHERE id_billetera = ? ORDER BY fecha DESC LIMIT 10");
            $stmt->execute([$data['billetera']['id_billetera']]);
            $data['movimientos'] = $stmt->fetchAll();
        }

        return $this->view('cliente.dashboard', $data);
    }
}
