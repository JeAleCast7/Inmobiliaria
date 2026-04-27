<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Database;
use PDO;

class PropertyController extends BaseController
{
    /**
     * API: Obtiene las propiedades destacadas (Top 6)
     */
    public function getFeatured()
    {
        try {
            $db = Database::getInstance();
            
            $sql = "SELECT i.*, inv.tipo as tipo_operacion, inv.precio as precio_publicado, inv.fotos, a.telefono as agente_telefono 
                    FROM inmuebles i 
                    LEFT JOIN inventario inv ON i.id_inmueble = inv.id_inmueble 
                    LEFT JOIN agentes a ON i.id_agente = a.id_agente 
                    WHERE i.estado = 'disponible' AND inv.estado = 'activo' 
                    GROUP BY i.id_inmueble
                    ORDER BY inv.fecha_publicacion DESC LIMIT 6";

            $stmt = $db->query($sql);
            $propiedades = $stmt->fetchAll();

            return $this->json($propiedades);
            
        } catch (\Exception $e) {
            return $this->json(["error" => "Error al obtener propiedades: " . $e->getMessage()], 500);
        }
    }
}
