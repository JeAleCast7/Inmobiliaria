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

    public function createInmueble()
    {
        // Obtener agentes activos para el selector
        $agentes = $this->db->query("SELECT id_agente, nombre, cargo FROM agentes")->fetchAll();
        return $this->view('admin.inmueble_form', ['agentes' => $agentes]);
    }

    public function storeInmueble()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('/admin/dashboard');
        }

        try {
            $this->db->beginTransaction();

            $tipo             = $_POST['tipo']             ?? '';
            $direccion        = $_POST['direccion']        ?? '';
            $estrato          = $_POST['estrato']          ?? 0;
            $area_m2          = $_POST['area_m2']          ?? 0;
            $habitaciones     = $_POST['habitaciones']     ?? 0;
            $banos            = $_POST['banos']            ?? 0;
            $tipo_operacion   = $_POST['tipo_operacion']   ?? 'venta';
            $precio           = $_POST['precio']           ?? 0;
            $estado_inmueble  = $_POST['estado_inmueble']  ?? 'disponible';
            $estado_inventario= $_POST['estado_inventario']?? 'activo';
            $id_agente        = !empty($_POST['id_agente']) ? $_POST['id_agente'] : null;
            $descripcion      = $_POST['descripcion']      ?? '';

            // Insertar Inmueble
            $stmt = $this->db->prepare(
                "INSERT INTO inmuebles (id_agente, tipo, precio, direccion, area_m2, habitaciones, banos, estrato, estado, descripcion, id_inmobiliaria)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)"
            );
            $stmt->execute([$id_agente, $tipo, $precio, $direccion, $area_m2, $habitaciones, $banos, $estrato, $estado_inmueble, $descripcion]);
            $id_inmueble = $this->db->lastInsertId();

            // ── Subida a Cloudinary ──────────────────────────────────────────
            $fotosUrls = [];

            $cloudName = getenv('CLOUDINARY_CLOUD_NAME') ?: ($_ENV['CLOUDINARY_CLOUD_NAME'] ?? '');
            $apiKey    = getenv('CLOUDINARY_API_KEY')    ?: ($_ENV['CLOUDINARY_API_KEY']    ?? '');
            $apiSecret = getenv('CLOUDINARY_API_SECRET') ?: ($_ENV['CLOUDINARY_API_SECRET'] ?? '');
            $folder    = getenv('CLOUDINARY_UPLOAD_FOLDER') ?: ($_ENV['CLOUDINARY_UPLOAD_FOLDER'] ?? 'inmobiliaria/fotos');

            $tieneArchivos = !empty($_FILES['fotos']['tmp_name'][0]);

            if ($tieneArchivos && $cloudName && $apiKey && $apiSecret) {
                foreach ($_FILES['fotos']['tmp_name'] as $index => $tmpName) {
                    if ($_FILES['fotos']['error'][$index] !== UPLOAD_ERR_OK) {
                        error_log("[Cloudinary] Error en archivo #{$index}: código " . $_FILES['fotos']['error'][$index]);
                        continue;
                    }

                    $timestamp = time();

                    // Firma correcta: parámetros ordenados alfabéticamente → "folder=...&timestamp=...{apiSecret}"
                    $paramsToSign = [
                        'folder'    => $folder,
                        'timestamp' => $timestamp,
                    ];
                    ksort($paramsToSign);
                    $signString = '';
                    foreach ($paramsToSign as $k => $v) {
                        $signString .= ($signString ? '&' : '') . "{$k}={$v}";
                    }
                    $signString .= $apiSecret;
                    $signature = sha1($signString);

                    $ch = curl_init("https://api.cloudinary.com/v1_1/{$cloudName}/image/upload");
                    curl_setopt_array($ch, [
                        CURLOPT_POST           => true,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_POSTFIELDS     => [
                            'file'      => new \CURLFile($tmpName, $_FILES['fotos']['type'][$index], $_FILES['fotos']['name'][$index]),
                            'api_key'   => $apiKey,
                            'timestamp' => $timestamp,
                            'folder'    => $folder,
                            'signature' => $signature,
                        ],
                    ]);

                    $response = curl_exec($ch);
                    $curlErr  = curl_error($ch);
                    curl_close($ch);

                    if ($curlErr) {
                        error_log("[Cloudinary] cURL error #{$index}: {$curlErr}");
                        continue;
                    }

                    $result = json_decode($response, true);

                    if (isset($result['secure_url'])) {
                        $fotosUrls[] = $result['secure_url'];
                        error_log("[Cloudinary] ✓ Imagen #{$index} subida: " . $result['secure_url']);
                    } else {
                        $errorMsg = $result['error']['message'] ?? $response;
                        error_log("[Cloudinary] ✗ Error en imagen #{$index}: {$errorMsg}");
                    }
                }
            } else {
                if (!$tieneArchivos)  error_log("[storeInmueble] No se recibieron archivos.");
                if (!$cloudName)      error_log("[storeInmueble] CLOUDINARY_CLOUD_NAME vacío.");
                if (!$apiKey)         error_log("[storeInmueble] CLOUDINARY_API_KEY vacío.");
                if (!$apiSecret)      error_log("[storeInmueble] CLOUDINARY_API_SECRET vacío.");
            }

            // Insertar en Inventario
            $fotosJson = empty($fotosUrls) ? null : json_encode($fotosUrls);
            $stmtInv = $this->db->prepare(
                "INSERT INTO inventario (id_inmueble, id_inmobiliaria, estado, tipo, precio, descripcion, fotos)
                 VALUES (?, 1, ?, ?, ?, ?, ?)"
            );
            $stmtInv->execute([$id_inmueble, $estado_inventario, $tipo_operacion, $precio, $descripcion, $fotosJson]);

            $this->db->commit();
            return $this->redirect('/admin/dashboard?success=1');

        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log("[storeInmueble] Excepción: " . $e->getMessage());
            return $this->redirect('/admin/inmuebles/crear?error=1');
        }
    }

    public function editInmueble()
    {
        $id = (int)($_GET['id'] ?? 0);
        $inmueble = $this->db->query("SELECT i.*, inv.tipo as tipo_operacion, inv.precio as precio_inventario, inv.estado as estado_inventario FROM inmuebles i LEFT JOIN inventario inv ON i.id_inmueble = inv.id_inmueble WHERE i.id_inmueble = {$id}")->fetch();
        
        if (!$inmueble) return $this->redirect('/admin/dashboard');

        $agentes = $this->db->query("SELECT id_agente, nombre, cargo FROM agentes")->fetchAll();
        return $this->view('admin.inmueble_edit', ['inmueble' => $inmueble, 'agentes' => $agentes]);
    }

    public function updateInmueble()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return $this->redirect('/admin/dashboard');
        
        try {
            $this->db->beginTransaction();

            $id               = (int)($_POST['id_inmueble'] ?? 0);
            $tipo             = $_POST['tipo']             ?? '';
            $direccion        = $_POST['direccion']        ?? '';
            $estrato          = $_POST['estrato']          ?? 0;
            $area_m2          = $_POST['area_m2']          ?? 0;
            $habitaciones     = $_POST['habitaciones']     ?? 0;
            $banos            = $_POST['banos']            ?? 0;
            $tipo_operacion   = $_POST['tipo_operacion']   ?? 'venta';
            $precio           = $_POST['precio']           ?? 0;
            $estado_inmueble  = $_POST['estado_inmueble']  ?? 'disponible';
            $estado_inventario= $_POST['estado_inventario']?? 'activo';
            $id_agente        = !empty($_POST['id_agente']) ? $_POST['id_agente'] : null;
            $descripcion      = $_POST['descripcion']      ?? '';

            $stmt = $this->db->prepare(
                "UPDATE inmuebles SET id_agente=?, tipo=?, precio=?, direccion=?, area_m2=?, habitaciones=?, banos=?, estrato=?, estado=?, descripcion=? WHERE id_inmueble=?"
            );
            $stmt->execute([$id_agente, $tipo, $precio, $direccion, $area_m2, $habitaciones, $banos, $estrato, $estado_inmueble, $descripcion, $id]);

            $stmtInv = $this->db->prepare(
                "UPDATE inventario SET estado=?, tipo=?, precio=?, descripcion=? WHERE id_inmueble=?"
            );
            $stmtInv->execute([$estado_inventario, $tipo_operacion, $precio, $descripcion, $id]);

            $this->db->commit();
            return $this->redirect('/admin/dashboard?success=1');
        } catch (\Exception $e) {
            $this->db->rollBack();
            return $this->redirect('/admin/dashboard?error=1');
        }
    }

    public function deleteInmueble()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return $this->redirect('/admin/dashboard');
        try {
            $id = (int)($_POST['id_inmueble'] ?? 0);
            $this->db->query("DELETE FROM inventario WHERE id_inmueble = {$id}");
            $this->db->query("DELETE FROM gobierno WHERE id_inmueble = {$id}");
            $this->db->query("DELETE FROM reservas WHERE id_inmueble = {$id}");
            $this->db->query("DELETE FROM inmuebles WHERE id_inmueble = {$id}");
            
            return $this->redirect('/admin/dashboard?success=1');
        } catch (\Exception $e) {
            return $this->redirect('/admin/dashboard?error=1');
        }
    }

    public function createAgente()
    {
        return $this->view('admin.agente_form');
    }

    public function storeAgente()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('/admin/dashboard');
        }

        $nombre = trim($_POST['nombre'] ?? '');
        $tipo_documento = trim($_POST['tipo_documento'] ?? 'CC');
        $numero_documento = trim($_POST['numero_documento'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $cargo = trim($_POST['cargo'] ?? '');

        if (empty($nombre) || empty($email) || empty($password) || empty($numero_documento)) {
            return $this->redirect('/admin/agentes/crear?error=campos');
        }

        // Verificar si el correo ya existe
        $stmt = $this->db->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            return $this->redirect('/admin/agentes/crear?error=email');
        }

        try {
            $this->db->beginTransaction();

            $stmt1 = $this->db->prepare("INSERT INTO usuarios (email, password, rol, activo) VALUES (?, ?, 'agente', 1)");
            $stmt1->execute([$email, $password]);
            $id_usuario = $this->db->lastInsertId();

            $stmt2 = $this->db->prepare("INSERT INTO agentes (id_usuario, nombre, tipo_documento, numero_documento, telefono, email, cargo, id_inmobiliaria) VALUES (?, ?, ?, ?, ?, ?, ?, 1)");
            $stmt2->execute([$id_usuario, $nombre, $tipo_documento, $numero_documento, $telefono, $email, $cargo]);
            $id_agente = $this->db->lastInsertId();

            $stmt3 = $this->db->prepare("INSERT INTO billetera_agente (id_agente, saldo, estado) VALUES (?, 0.00, 'activa')");
            $stmt3->execute([$id_agente]);

            $this->db->commit();
            return $this->redirect('/admin/dashboard?success=1');

        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log("[storeAgente] Excepción: " . $e->getMessage());
            return $this->redirect('/admin/agentes/crear?error=db');
        }
    }

    public function editAgente()
    {
        $id = (int)($_GET['id'] ?? 0);
        
        $agente = $this->db->prepare("SELECT a.*, u.email as user_email, u.activo FROM agentes a JOIN usuarios u ON a.id_usuario = u.id_usuario WHERE a.id_agente = ?");
        $agente->execute([$id]);
        $agenteData = $agente->fetch();

        if (!$agenteData) {
            return $this->redirect('/admin/dashboard');
        }

        return $this->view('admin.agente_edit', ['agente' => $agenteData]);
    }

    public function updateAgente()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('/admin/dashboard');
        }

        $id = (int)($_POST['id_agente'] ?? 0);
        $nombre = trim($_POST['nombre'] ?? '');
        $tipo_documento = trim($_POST['tipo_documento'] ?? 'CC');
        $numero_documento = trim($_POST['numero_documento'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $activo = isset($_POST['activo']) ? (int)$_POST['activo'] : 1;
        $cargo = trim($_POST['cargo'] ?? '');

        if (empty($nombre) || empty($email) || empty($numero_documento)) {
            return $this->redirect('/admin/agentes/editar?id=' . $id . '&error=campos');
        }

        // Obtener el agente actual para ver el id_usuario
        $agente = $this->db->prepare("SELECT id_usuario FROM agentes WHERE id_agente = ?");
        $agente->execute([$id]);
        $agenteData = $agente->fetch();

        if (!$agenteData) {
            return $this->redirect('/admin/dashboard');
        }

        $id_usuario = $agenteData['id_usuario'];

        // Verificar si el correo ya existe en otros usuarios
        $stmt = $this->db->prepare("SELECT id_usuario FROM usuarios WHERE email = ? AND id_usuario != ?");
        $stmt->execute([$email, $id_usuario]);
        if ($stmt->fetch()) {
            return $this->redirect('/admin/agentes/editar?id=' . $id . '&error=email');
        }

        try {
            $this->db->beginTransaction();

            // Actualizar tabla usuarios
            $stmt1 = $this->db->prepare("UPDATE usuarios SET email = ?, activo = ? WHERE id_usuario = ?");
            $stmt1->execute([$email, $activo, $id_usuario]);

            // Actualizar tabla agentes
            $stmt2 = $this->db->prepare("UPDATE agentes SET nombre = ?, tipo_documento = ?, numero_documento = ?, telefono = ?, email = ?, cargo = ? WHERE id_agente = ?");
            $stmt2->execute([$nombre, $tipo_documento, $numero_documento, $telefono, $email, $cargo, $id]);

            $this->db->commit();
            return $this->redirect('/admin/dashboard?success=1');

        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log("[updateAgente] Excepción: " . $e->getMessage());
            return $this->redirect('/admin/agentes/editar?id=' . $id . '&error=db');
        }
    }

    public function deleteAgente()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('/admin/dashboard');
        }

        $id = (int)($_POST['id_agente'] ?? 0);

        // Obtener el agente para saber su id_usuario
        $agente = $this->db->prepare("SELECT id_usuario FROM agentes WHERE id_agente = ?");
        $agente->execute([$id]);
        $agenteData = $agente->fetch();

        if (!$agenteData) {
            return $this->redirect('/admin/dashboard');
        }

        $id_usuario = $agenteData['id_usuario'];

        try {
            $this->db->beginTransaction();

            // Desvincular de inmuebles
            $this->db->prepare("UPDATE inmuebles SET id_agente = NULL WHERE id_agente = ?")->execute([$id]);
            
            // Eliminar billetera
            $this->db->prepare("DELETE FROM billetera_agente WHERE id_agente = ?")->execute([$id]);

            // Eliminar de usuarios (esto borra en cascada la tabla agentes)
            $stmt = $this->db->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
            $stmt->execute([$id_usuario]);

            $this->db->commit();
            return $this->redirect('/admin/dashboard?success=1');

        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log("[deleteAgente] Excepción: " . $e->getMessage());
            return $this->redirect('/admin/dashboard?error=1');
        }
    }

    public function deleteCliente()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('/admin/dashboard');
        }

        $id = (int)($_POST['id_cliente'] ?? 0);

        // Obtener el cliente para saber su id_usuario
        $cliente = $this->db->prepare("SELECT id_usuario FROM clientes WHERE id_cliente = ?");
        $cliente->execute([$id]);
        $clienteData = $cliente->fetch();

        if (!$clienteData) {
            return $this->redirect('/admin/dashboard');
        }

        $id_usuario = $clienteData['id_usuario'];

        try {
            $this->db->beginTransaction();

            // Eliminar de usuarios (esto borra en cascada la tabla clientes, billetera, reservas, facturas)
            $stmt = $this->db->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
            $stmt->execute([$id_usuario]);

            $this->db->commit();
            return $this->redirect('/admin/dashboard?success=1');

        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log("[deleteCliente] Excepción: " . $e->getMessage());
            return $this->redirect('/admin/dashboard?error=1');
        }
    }

    public function descargarRecibo()
    {
        $id_factura = $_GET['id_factura'] ?? null;
        if (!$id_factura) {
            die("Factura no especificada.");
        }

        $stmt = $this->db->prepare("SELECT f.*, i.direccion, c.nombre as cliente_nombre, c.numero_documento, a.nombre as agente_nombre 
            FROM facturas f 
            JOIN inmuebles i ON f.id_inmueble = i.id_inmueble 
            JOIN clientes c ON f.id_cliente = c.id_cliente
            LEFT JOIN agentes a ON f.id_agente = a.id_agente 
            WHERE f.id_factura = ? AND f.estado = 'pagada'");
        $stmt->execute([$id_factura]);
        $factura = $stmt->fetch();

        if (!$factura) {
            die("Recibo no disponible o la factura no ha sido pagada.");
        }

        return $this->view('cliente.recibo', ['factura' => $factura]);
    }
}
