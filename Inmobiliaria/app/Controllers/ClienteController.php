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

        // Facturas pendientes y pagadas
        $stmtFacturas = $this->db->prepare("SELECT f.*, i.direccion, i.tipo as tipo_inmueble, a.nombre as agente_nombre 
            FROM facturas f 
            JOIN inmuebles i ON f.id_inmueble = i.id_inmueble 
            LEFT JOIN agentes a ON f.id_agente = a.id_agente 
            WHERE f.id_cliente = ? ORDER BY f.fecha DESC");
        $stmtFacturas->execute([$id_cliente]);
        $data['facturas'] = $stmtFacturas->fetchAll();

        return $this->view('cliente.dashboard', $data);
    }

    public function pasarelaPago() {
        $id_factura = $_GET['id_factura'] ?? null;
        if (!$id_factura) {
            die("Factura no especificada.");
        }
        
        $id_cliente = $this->getClientId();

        $stmt = $this->db->prepare("SELECT f.*, i.direccion 
            FROM facturas f 
            JOIN inmuebles i ON f.id_inmueble = i.id_inmueble 
            WHERE f.id_factura = ? AND f.id_cliente = ? AND f.estado = 'pendiente'");
        $stmt->execute([$id_factura, $id_cliente]);
        $factura = $stmt->fetch();

        if (!$factura) {
            die("Factura no válida o ya pagada.");
        }

        return $this->view('cliente.pasarela_pago', ['factura' => $factura]);
    }

    public function procesarPago() {
        $id_factura = $_POST['id_factura'] ?? null;
        if (!$id_factura) {
            die("Llamada inválida.");
        }

        $id_cliente = $this->getClientId();

        // Verificar que la factura está pendiente y obtener datos
        $stmt = $this->db->prepare("SELECT f.* FROM facturas f WHERE f.id_factura = ? AND f.id_cliente = ? AND f.estado = 'pendiente'");
        $stmt->execute([$id_factura, $id_cliente]);
        $factura = $stmt->fetch();

        if (!$factura) {
            die("La factura no existe o ya fue pagada.");
        }

        try {
            $this->db->beginTransaction();

            // 1. Actualizar estado de la factura a pagada
            $stmtUpdate = $this->db->prepare("UPDATE facturas SET estado = 'pagada' WHERE id_factura = ?");
            $stmtUpdate->execute([$id_factura]);

            // 2. Obtener o crear la billetera del cliente
            $stmtBilletera = $this->db->prepare("SELECT id_billetera FROM billetera WHERE id_cliente = ?");
            $stmtBilletera->execute([$id_cliente]);
            $billeteraCliente = $stmtBilletera->fetch();
            
            if (!$billeteraCliente) {
                $stmtCreateB = $this->db->prepare("INSERT INTO billetera (id_cliente, saldo, estado) VALUES (?, 0.00, 'activa')");
                $stmtCreateB->execute([$id_cliente]);
                $id_billetera_cliente = $this->db->lastInsertId();
            } else {
                $id_billetera_cliente = $billeteraCliente['id_billetera'];
            }

            // 3. Registrar el movimiento de pago en la billetera del cliente
            $stmtMovClient = $this->db->prepare("INSERT INTO movimientos_billetera (id_billetera, tipo, monto) VALUES (?, 'pagado', ?)");
            $stmtMovClient->execute([$id_billetera_cliente, $factura['valor_total']]);

            // 4. Si hay agente asignado, calcular comisión (10%), actualizar su billetera y registrar movimiento
            if (!empty($factura['id_agente'])) {
                $id_agente = $factura['id_agente'];
                $comision = $factura['valor_total'] * 0.10; // 10% de comisión

                // Obtener o crear billetera del agente
                $stmtBilleteraAgente = $this->db->prepare("SELECT id_billetera FROM billetera_agente WHERE id_agente = ?");
                $stmtBilleteraAgente->execute([$id_agente]);
                $billeteraAgente = $stmtBilleteraAgente->fetch();

                if (!$billeteraAgente) {
                    $stmtCreateBA = $this->db->prepare("INSERT INTO billetera_agente (id_agente, saldo, estado) VALUES (?, 0.00, 'activa')");
                    $stmtCreateBA->execute([$id_agente]);
                    $id_billetera_agente = $this->db->lastInsertId();
                } else {
                    $id_billetera_agente = $billeteraAgente['id_billetera'];
                }

                // Incrementar saldo de la billetera del agente
                $stmtUpdateSaldoAgente = $this->db->prepare("UPDATE billetera_agente SET saldo = saldo + ? WHERE id_billetera = ?");
                $stmtUpdateSaldoAgente->execute([$comision, $id_billetera_agente]);

                // Registrar movimiento de comisión
                $stmtMovAgent = $this->db->prepare("INSERT INTO movimientos_billetera_agente (id_billetera, tipo, monto) VALUES (?, 'comision', ?)");
                $stmtMovAgent->execute([$id_billetera_agente, $comision]);
            }

            // 5. Actualizar estado del inmueble según tipo de operación
            $nuevo_estado_inmueble = ($factura['tipo'] === 'venta') ? 'vendido' : 'arrendado';
            $stmtInmueble = $this->db->prepare("UPDATE inmuebles SET estado = ? WHERE id_inmueble = ?");
            $stmtInmueble->execute([$nuevo_estado_inmueble, $factura['id_inmueble']]);

            // 6. Actualizar estado del inventario
            $nuevo_estado_inventario = ($factura['tipo'] === 'venta') ? 'vendido' : 'arrendado';
            $stmtInventario = $this->db->prepare("UPDATE inventario SET estado = ? WHERE id_inmueble = ?");
            $stmtInventario->execute([$nuevo_estado_inventario, $factura['id_inmueble']]);

            $this->db->commit();

            // Redirigir a descargar recibo
            header("Location: " . URL_ROOT . "/cliente/descargar-recibo?id_factura=" . $id_factura);
            exit;

        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log("[procesarPago] Excepción al procesar pago de factura {$id_factura}: " . $e->getMessage());
            die("Error interno al procesar el pago. Por favor intente más tarde.");
        }
    }

    public function descargarRecibo() {
        $id_factura = $_GET['id_factura'] ?? null;
        if (!$id_factura) {
            die("Factura no especificada.");
        }
        
        $id_cliente = $this->getClientId();

        $stmt = $this->db->prepare("SELECT f.*, i.direccion, c.nombre as cliente_nombre, c.numero_documento, a.nombre as agente_nombre 
            FROM facturas f 
            JOIN inmuebles i ON f.id_inmueble = i.id_inmueble 
            JOIN clientes c ON f.id_cliente = c.id_cliente
            LEFT JOIN agentes a ON f.id_agente = a.id_agente 
            WHERE f.id_factura = ? AND f.id_cliente = ? AND f.estado = 'pagada'");
        $stmt->execute([$id_factura, $id_cliente]);
        $factura = $stmt->fetch();

        if (!$factura) {
            die("Recibo no disponible.");
        }

        return $this->view('cliente.recibo', ['factura' => $factura]);
    }

    private function getClientId() {
        $id_usuario = $_SESSION['id_usuario'];
        $stmtClient = $this->db->prepare("SELECT id_cliente FROM clientes WHERE id_usuario = ?");
        $stmtClient->execute([$id_usuario]);
        $cliente = $stmtClient->fetch();
        if (!$cliente) die("Cliente no encontrado.");
        return $cliente['id_cliente'];
    }
}
