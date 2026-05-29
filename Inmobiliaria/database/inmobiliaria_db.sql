-- ============================================
-- Base de Datos: inmobiliaria_db
-- Proyecto Boyaca Real Estate (BRE) - XAMPP Local
-- ============================================

CREATE DATABASE IF NOT EXISTS inmobiliaria_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE inmobiliaria_db;

-- ============================================
-- Tabla: usuarios (autenticación unificada)
-- Contraseñas en texto plano para gestión desde XAMPP
-- ============================================
CREATE TABLE IF NOT EXISTS usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'agente', 'cliente') NOT NULL DEFAULT 'cliente',
    activo TINYINT(1) NOT NULL DEFAULT 1,
    fecha_registro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================
-- Tabla: inmobiliaria (datos de la empresa)
-- ============================================
CREATE TABLE IF NOT EXISTS inmobiliaria (
    id_inmobiliaria INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    NIT VARCHAR(30) NOT NULL,
    telefono VARCHAR(20),
    direccion VARCHAR(255),
    email VARCHAR(150),
    fecha_registro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================
-- Tabla: clientes
-- ============================================
CREATE TABLE IF NOT EXISTS clientes (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    nombre VARCHAR(150) NOT NULL,
    tipo_documento ENUM('CC', 'CE', 'Pasaporte', 'CERL') NOT NULL DEFAULT 'CC',
    numero_documento BIGINT NOT NULL,
    telefono BIGINT,
    direccion VARCHAR(255),
    fecha_registro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================
-- Tabla: agentes
-- ============================================
CREATE TABLE IF NOT EXISTS agentes (
    id_agente INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    nombre VARCHAR(150) NOT NULL,
    tipo_documento ENUM('CC', 'CE', 'Pasaporte', 'CERL') NOT NULL DEFAULT 'CC',
    numero_documento BIGINT NOT NULL,
    telefono BIGINT,
    email VARCHAR(150),
    cargo VARCHAR(100),
    fecha_ingreso DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_inmobiliaria INT,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_inmobiliaria) REFERENCES inmobiliaria(id_inmobiliaria) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================================
-- Tabla: administrador
-- ============================================
CREATE TABLE IF NOT EXISTS administrador (
    id_administrador INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================
-- Tabla: inmuebles
-- ============================================
CREATE TABLE IF NOT EXISTS inmuebles (
    id_inmueble INT AUTO_INCREMENT PRIMARY KEY,
    id_agente INT,
    tipo ENUM('casa', 'apartamento', 'lote', 'local', 'bodega', 'oficina') NOT NULL,
    precio DECIMAL(15,2) NOT NULL,
    direccion VARCHAR(255) NOT NULL,
    area_m2 DECIMAL(10,2),
    habitaciones INT DEFAULT 0,
    banos INT DEFAULT 0,
    estrato INT DEFAULT 0,
    estado ENUM('disponible', 'vendido', 'arrendado', 'mantenimiento', 'reservado') NOT NULL DEFAULT 'disponible',
    descripcion TEXT,
    fecha_registro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_inmobiliaria INT,
    FOREIGN KEY (id_agente) REFERENCES agentes(id_agente) ON DELETE SET NULL,
    FOREIGN KEY (id_inmobiliaria) REFERENCES inmobiliaria(id_inmobiliaria) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================================
-- Tabla: inventario
-- ============================================
CREATE TABLE IF NOT EXISTS inventario (
    id_inventario INT AUTO_INCREMENT PRIMARY KEY,
    id_inmueble INT NOT NULL,
    id_inmobiliaria INT,
    estado ENUM('activo', 'pausado', 'vendido', 'arrendado') NOT NULL DEFAULT 'activo',
    tipo ENUM('venta', 'arriendo') NOT NULL,
    precio DECIMAL(15,2) NOT NULL,
    fecha_publicacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    descripcion TEXT,
    fotos TEXT,
    FOREIGN KEY (id_inmueble) REFERENCES inmuebles(id_inmueble) ON DELETE CASCADE,
    FOREIGN KEY (id_inmobiliaria) REFERENCES inmobiliaria(id_inmobiliaria) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================================
-- Tabla: billetera (clientes)
-- ============================================
CREATE TABLE IF NOT EXISTS billetera (
    id_billetera INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    saldo DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    estado ENUM('activa', 'bloqueada') NOT NULL DEFAULT 'activa',
    FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================
-- Tabla: movimientos_billetera (clientes)
-- ============================================
CREATE TABLE IF NOT EXISTS movimientos_billetera (
    id_movimiento INT AUTO_INCREMENT PRIMARY KEY,
    id_billetera INT NOT NULL,
    tipo ENUM('pagado', 'reversado', 'fondos_insuficientes') NOT NULL,
    monto DECIMAL(15,2) NOT NULL,
    fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_billetera) REFERENCES billetera(id_billetera) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================
-- Tabla: billetera_agente
-- ============================================
CREATE TABLE IF NOT EXISTS billetera_agente (
    id_billetera INT AUTO_INCREMENT PRIMARY KEY,
    id_agente INT NOT NULL,
    saldo DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    estado ENUM('activa', 'bloqueada') NOT NULL DEFAULT 'activa',
    FOREIGN KEY (id_agente) REFERENCES agentes(id_agente) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================
-- Tabla: movimientos_billetera_agente
-- ============================================
CREATE TABLE IF NOT EXISTS movimientos_billetera_agente (
    id_movimiento INT AUTO_INCREMENT PRIMARY KEY,
    id_billetera INT NOT NULL,
    tipo ENUM('comision', 'retiro', 'bonificacion') NOT NULL,
    monto DECIMAL(15,2) NOT NULL,
    fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_billetera) REFERENCES billetera_agente(id_billetera) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================
-- Tabla: reservas
-- ============================================
CREATE TABLE IF NOT EXISTS reservas (
    id_reserva INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    id_inmueble INT NOT NULL,
    id_agente INT,
    estado ENUM('pendiente', 'confirmada', 'cancelada', 'finalizada') NOT NULL DEFAULT 'pendiente',
    fecha_reserva DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_visita DATETIME,
    FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente) ON DELETE CASCADE,
    FOREIGN KEY (id_inmueble) REFERENCES inmuebles(id_inmueble) ON DELETE CASCADE,
    FOREIGN KEY (id_agente) REFERENCES agentes(id_agente) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================================
-- Tabla: facturas
-- ============================================
CREATE TABLE IF NOT EXISTS facturas (
    id_factura INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    id_inmueble INT NOT NULL,
    id_agente INT,
    id_reserva INT,
    tipo ENUM('venta', 'arriendo') NOT NULL,
    valor_total DECIMAL(15,2) NOT NULL,
    estado ENUM('pendiente', 'pagada') NOT NULL DEFAULT 'pendiente',
    fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente) ON DELETE CASCADE,
    FOREIGN KEY (id_inmueble) REFERENCES inmuebles(id_inmueble) ON DELETE CASCADE,
    FOREIGN KEY (id_agente) REFERENCES agentes(id_agente) ON DELETE SET NULL,
    FOREIGN KEY (id_reserva) REFERENCES reservas(id_reserva) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================================
-- Tabla: gobierno (permisos legales)
-- ============================================
CREATE TABLE IF NOT EXISTS gobierno (
    id_permiso INT AUTO_INCREMENT PRIMARY KEY,
    id_inmueble INT NOT NULL,
    id_inmobiliaria INT,
    tipo_permiso VARCHAR(150),
    estado ENUM('aprobado', 'pendiente', 'rechazado') NOT NULL DEFAULT 'pendiente',
    fecha_solicitud DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_aprobacion DATETIME,
    fecha_vencimiento DATETIME,
    documento VARCHAR(255),
    FOREIGN KEY (id_inmueble) REFERENCES inmuebles(id_inmueble) ON DELETE CASCADE,
    FOREIGN KEY (id_inmobiliaria) REFERENCES inmobiliaria(id_inmobiliaria) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================================
-- DATOS INICIALES
-- ============================================

-- Inmobiliaria principal
INSERT INTO inmobiliaria (nombre, NIT, telefono, direccion, email)
VALUES ('Boyaca Real Estate', '900123456-7', '601-555-1234', 'Calle 100 #15-25, Bogotá', 'contacto@bre.com');

-- Usuario administrador (password: admin123) — texto plano
INSERT INTO usuarios (email, password, rol)
VALUES ('jesus@adminbre.com', 'admin123', 'admin');

INSERT INTO administrador (id_usuario) VALUES (1);

-- Usuario agente 1 (password: agente123) — texto plano
INSERT INTO usuarios (email, password, rol)
VALUES ('carlos@agentebre.com', 'agente123', 'agente');

INSERT INTO agentes (id_usuario, nombre, tipo_documento, numero_documento, telefono, email, cargo, id_inmobiliaria)
VALUES (2, 'Carlos Méndez', 'CC', 1020345678, 3105550001, 'carlos@agentebre.com', 'Agente Senior', 1);

-- Billetera agente 1
INSERT INTO billetera_agente (id_agente, saldo, estado) VALUES (1, 0, 'activa');

-- Usuario agente 2 (password: agente123) — texto plano
INSERT INTO usuarios (email, password, rol)
VALUES ('laura@agentebre.com', 'agente123', 'agente');

INSERT INTO agentes (id_usuario, nombre, tipo_documento, numero_documento, telefono, email, cargo, id_inmobiliaria)
VALUES (3, 'Laura García', 'CC', 1030456789, 3205550002, 'laura@agentebre.com', 'Agente de Ventas', 1);

-- Billetera agente 2
INSERT INTO billetera_agente (id_agente, saldo, estado) VALUES (2, 0, 'activa');
