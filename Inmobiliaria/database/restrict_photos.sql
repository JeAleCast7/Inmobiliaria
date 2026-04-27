USE inmobiliaria_db;
UPDATE inventario SET fotos = NULL WHERE id_inventario != 1;
UPDATE inventario SET fotos = '["uploads/casa1.png","uploads/casa2.png","uploads/casa3.png","uploads/casa4.png","uploads/casa5.png"]' WHERE id_inventario = 1;
