<?php

namespace App\Core;

use PDO;
use Exception;

/**
 * Singleton de Base de Datos con PDO
 * Proporciona una conexión segura y única a la base de datos.
 */
class Database
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        $host = getenv('DB_HOST') ?: "localhost";
        $db   = getenv('DB_NAME') ?: "inmobiliaria_db";
        $user = getenv('DB_USER') ?: "root";
        $pass = getenv('DB_PASS') !== false ? getenv('DB_PASS') : "";
        $charset = getenv('DB_CHARSET') ?: 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . $charset
        ];

        try {
            $this->connection = new PDO($dsn, $user, $pass, $options);
        } catch (Exception $e) {
            error_log("Error de conexión a DB: " . $e->getMessage());
            throw new Exception("No se pudo conectar a la base de datos.");
        }
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance->connection;
    }
}
