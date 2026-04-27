<?php

namespace App\Models;

use App\Core\BaseModel;
use PDO;

class User extends BaseModel
{
    /**
     * Busca un usuario por email
     */
    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT id_usuario, email, password, rol, activo FROM usuarios WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    /**
     * Obtiene datos adicionales según el rol (Agente, Cliente, Admin)
     */
    public function getProfileData($id_usuario, $rol)
    {
        $table = "";
        switch ($rol) {
            case 'agente': $table = "agentes"; break;
            case 'cliente': $table = "clientes"; break;
            default: return ['nombre' => 'Administrador'];
        }

        $stmt = $this->db->prepare("SELECT nombre FROM $table WHERE id_usuario = :id LIMIT 1");
        $stmt->execute(['id' => $id_usuario]);
        return $stmt->fetch();
    }
}
