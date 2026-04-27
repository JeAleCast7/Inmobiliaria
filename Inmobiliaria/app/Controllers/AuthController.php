<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\User;

class AuthController extends BaseController
{
    /**
     * Muestra el formulario de registro
     */
    public function showRegister()
    {
        return $this->view('auth.register');
    }

    /**
     * Muestra el formulario de login
     */
    public function showLogin()
    {
        // En un sistema real, aquí cargaríamos la vista app/Views/auth/login.php
        // Por ahora, para no romper nada, podemos redirigir al Login viejo o mostrar un mensaje
        return $this->view('auth.login');
    }

    /**
     * Procesa el intento de login
     */
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('/login');
        }

        $email = trim($_POST['correo'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($email) || empty($password)) {
            return $this->redirect('/login?error=campos');
        }

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if ($user) {
            // REGLA DEL USUARIO: Comparación en texto plano (Inseguro, pero solicitado)
            if ($password === $user['password']) {
                
                if ($user['activo'] == 0) {
                    return $this->redirect('/login?error=inactivo');
                }

                // Iniciar Sesión Profesional
                $_SESSION['id_usuario'] = $user['id_usuario'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['rol'] = $user['rol'];

                $profile = $userModel->getProfileData($user['id_usuario'], $user['rol']);
                $_SESSION['nombre'] = $profile['nombre'] ?? 'Usuario';

                // Redirección profesional según rol
                switch ($user['rol']) {
                    case 'admin': return $this->redirect('/admin/dashboard');
                    case 'agente': return $this->redirect('/agente/dashboard');
                    case 'cliente': return $this->redirect('/cliente/dashboard');
                    default: return $this->redirect('/');
                }
            }
        }

        return $this->redirect('/login?error=credenciales');
    }

    /**
     * Procesa el registro de un nuevo cliente
     */
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('/register');
        }

        $nombre = trim($_POST['nombre'] ?? '');
        $tipo_doc = trim($_POST['tipo_documento'] ?? '');
        $num_doc = trim($_POST['numero_documento'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $direccion = trim($_POST['direccion'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($nombre) || empty($correo) || empty($password)) {
            return $this->redirect('/register?error=campos');
        }

        $db = \App\Core\Database::getInstance();

        // Verificar si el correo ya existe
        $stmt = $db->prepare("SELECT id_usuario FROM usuarios WHERE email = :email");
        $stmt->execute(['email' => $correo]);
        if ($stmt->fetch()) {
            return $this->redirect('/register?error=email');
        }

        try {
            $db->beginTransaction();

            $stmt1 = $db->prepare("INSERT INTO usuarios (email, password, rol) VALUES (?, ?, 'cliente')");
            $stmt1->execute([$correo, $password]);
            $id_usuario = $db->lastInsertId();

            $stmt2 = $db->prepare("INSERT INTO clientes (id_usuario, nombre, tipo_documento, numero_documento, telefono, direccion) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt2->execute([$id_usuario, $nombre, $tipo_doc, $num_doc, $telefono, $direccion]);
            $id_cliente = $db->lastInsertId();

            $stmt3 = $db->prepare("INSERT INTO billetera (id_cliente, saldo, estado) VALUES (?, 0, 'activa')");
            $stmt3->execute([$id_cliente]);

            $db->commit();
            return $this->redirect('/login?registro=exitoso');

        } catch (\Exception $e) {
            $db->rollBack();
            return $this->redirect('/register?error=db');
        }
    }

    /**
     * Cierra la sesión
     */
    public function logout()
    {
        session_destroy();
        return $this->redirect('/login');
    }
}
