USE inmobiliaria_db;
-- Step 1: Remove duplicate inventario rows.
-- For each property, keep the row with photos if one exists,
-- otherwise keep the row with the lowest id_inventario.
DELETE inv FROM inventario inv
INNER JOIN (
    SELECT 
        id_inmueble,
        CASE 
            WHEN MAX(CASE WHEN fotos IS NOT NULL THEN id_inventario END) IS NOT NULL 
            THEN MAX(CASE WHEN fotos IS NOT NULL THEN id_inventario END)
            ELSE MIN(id_inventario)
        END as keep_id
    FROM inventario
    GROUP BY id_inmueble
) best ON inv.id_inmueble = best.id_inmueble AND inv.id_inventario != best.keep_id;
