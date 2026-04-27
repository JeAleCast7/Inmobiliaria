<?php

namespace App\Core;

use App\Core\Database;

/**
 * Modelo Base
 * Proporciona acceso a la conexión de base de datos a todas las entidades.
 */
abstract class BaseModel
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }
}
