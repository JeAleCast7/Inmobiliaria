-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 01, 2026 at 02:42 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inmobiliaria_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `administrador`
--

CREATE TABLE `administrador` (
  `id_administrador` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `administrador`
--

INSERT INTO `administrador` (`id_administrador`, `id_usuario`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `agentes`
--

CREATE TABLE `agentes` (
  `id_agente` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `tipo_documento` enum('CC','CE','Pasaporte','CERL') NOT NULL DEFAULT 'CC',
  `numero_documento` bigint(20) NOT NULL,
  `telefono` bigint(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `cargo` varchar(100) DEFAULT NULL,
  `fecha_ingreso` datetime NOT NULL DEFAULT current_timestamp(),
  `id_inmobiliaria` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `agentes`
--

INSERT INTO `agentes` (`id_agente`, `id_usuario`, `nombre`, `tipo_documento`, `numero_documento`, `telefono`, `email`, `cargo`, `fecha_ingreso`, `id_inmobiliaria`) VALUES
(1, 2, 'Carlos Méndez', 'CC', 1020345678, 3105550001, 'carlos@agentebre.com', 'Agente Senior', '2026-05-25 18:34:23', 1),
(2, 3, 'Laura García', 'CC', 1030456789, 3205550002, 'laura@agentebre.com', 'Agente de Ventas', '2026-05-25 18:34:23', 1);

-- --------------------------------------------------------

--
-- Table structure for table `billetera`
--

CREATE TABLE `billetera` (
  `id_billetera` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `saldo` decimal(15,2) NOT NULL DEFAULT 0.00,
  `estado` enum('activa','bloqueada') NOT NULL DEFAULT 'activa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `billetera_agente`
--

CREATE TABLE `billetera_agente` (
  `id_billetera` int(11) NOT NULL,
  `id_agente` int(11) NOT NULL,
  `saldo` decimal(15,2) NOT NULL DEFAULT 0.00,
  `estado` enum('activa','bloqueada') NOT NULL DEFAULT 'activa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `billetera_agente`
--

INSERT INTO `billetera_agente` (`id_billetera`, `id_agente`, `saldo`, `estado`) VALUES
(1, 1, 0.00, 'activa'),
(2, 2, 40000000.00, 'activa');

-- --------------------------------------------------------

--
-- Table structure for table `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `tipo_documento` enum('CC','CE','Pasaporte','CERL') NOT NULL DEFAULT 'CC',
  `numero_documento` bigint(20) NOT NULL,
  `telefono` bigint(20) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `facturas`
--

CREATE TABLE `facturas` (
  `id_factura` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_inmueble` int(11) NOT NULL,
  `id_agente` int(11) DEFAULT NULL,
  `id_reserva` int(11) DEFAULT NULL,
  `tipo` enum('venta','arriendo') NOT NULL,
  `valor_total` decimal(15,2) NOT NULL,
  `estado` enum('pendiente','pagada') NOT NULL DEFAULT 'pendiente',
  `fecha` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gobierno`
--

CREATE TABLE `gobierno` (
  `id_permiso` int(11) NOT NULL,
  `id_inmueble` int(11) NOT NULL,
  `id_inmobiliaria` int(11) DEFAULT NULL,
  `tipo_permiso` varchar(150) DEFAULT NULL,
  `estado` enum('aprobado','pendiente','rechazado') NOT NULL DEFAULT 'pendiente',
  `fecha_solicitud` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_aprobacion` datetime DEFAULT NULL,
  `fecha_vencimiento` datetime DEFAULT NULL,
  `documento` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inmobiliaria`
--

CREATE TABLE `inmobiliaria` (
  `id_inmobiliaria` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `NIT` varchar(30) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inmobiliaria`
--

INSERT INTO `inmobiliaria` (`id_inmobiliaria`, `nombre`, `NIT`, `telefono`, `direccion`, `email`, `fecha_registro`) VALUES
(1, 'Boyaca Real Estate', '900123456-7', '601-555-1234', 'Calle 100 #15-25, Bogotá', 'contacto@bre.com', '2026-05-25 18:34:23');

-- --------------------------------------------------------

--
-- Table structure for table `inmuebles`
--

CREATE TABLE `inmuebles` (
  `id_inmueble` int(11) NOT NULL,
  `id_agente` int(11) DEFAULT NULL,
  `tipo` enum('casa','apartamento','lote','local','bodega','oficina') NOT NULL,
  `precio` decimal(15,2) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `area_m2` decimal(10,2) DEFAULT NULL,
  `habitaciones` int(11) DEFAULT 0,
  `banos` int(11) DEFAULT 0,
  `estrato` int(11) DEFAULT 0,
  `estado` enum('disponible','vendido','arrendado','mantenimiento','reservado') NOT NULL DEFAULT 'disponible',
  `descripcion` text DEFAULT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp(),
  `id_inmobiliaria` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inmuebles`
--

INSERT INTO `inmuebles` (`id_inmueble`, `id_agente`, `tipo`, `precio`, `direccion`, `area_m2`, `habitaciones`, `banos`, `estrato`, `estado`, `descripcion`, `fecha_registro`, `id_inmobiliaria`) VALUES
(6, 2, 'casa', 300000000.00, 'suba calle 100', 120.00, 4, 2, 3, 'disponible', 'Casa de Suba completamente amoblada', '2026-05-29 18:36:43', 1),
(7, 1, 'apartamento', 400000000.00, 'Chapinero', 200.00, 5, 3, 3, 'disponible', 'Apartamento de dos pisos con escaleras en espiral y balcon, no viene amoblado', '2026-05-29 18:38:48', 1),
(8, 2, 'apartamento', 1000000.00, 'Chico Norte Ii Sector, Bogotá', 60.00, 2, 1, 3, 'disponible', 'Apartamento para estudiantes, viene completamente amoblado', '2026-05-29 18:41:47', 1);

-- --------------------------------------------------------

--
-- Table structure for table `inventario`
--

CREATE TABLE `inventario` (
  `id_inventario` int(11) NOT NULL,
  `id_inmueble` int(11) NOT NULL,
  `id_inmobiliaria` int(11) DEFAULT NULL,
  `estado` enum('activo','pausado','vendido','arrendado') NOT NULL DEFAULT 'activo',
  `tipo` enum('venta','arriendo') NOT NULL,
  `precio` decimal(15,2) NOT NULL,
  `fecha_publicacion` datetime NOT NULL DEFAULT current_timestamp(),
  `descripcion` text DEFAULT NULL,
  `fotos` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventario`
--

INSERT INTO `inventario` (`id_inventario`, `id_inmueble`, `id_inmobiliaria`, `estado`, `tipo`, `precio`, `fecha_publicacion`, `descripcion`, `fotos`) VALUES
(6, 6, 1, 'activo', 'venta', 300000000.00, '2026-05-29 18:36:49', 'Casa de Suba completamente amoblada', '[\"https:\\/\\/res.cloudinary.com\\/dywlt1dcs\\/image\\/upload\\/v1780097804\\/inmobiliaria\\/fotos\\/fvgbsjvokd5sa2p4flhf.png\",\"https:\\/\\/res.cloudinary.com\\/dywlt1dcs\\/image\\/upload\\/v1780097806\\/inmobiliaria\\/fotos\\/prht8shccpnciqnwlzgc.png\",\"https:\\/\\/res.cloudinary.com\\/dywlt1dcs\\/image\\/upload\\/v1780097808\\/inmobiliaria\\/fotos\\/mq2liiyu7otjugwb6d7y.png\"]'),
(7, 7, 1, 'activo', 'venta', 400000000.00, '2026-05-29 18:38:54', 'Apartamento de dos pisos con escaleras en espiral y balcon, no viene amoblado', '[\"https:\\/\\/res.cloudinary.com\\/dywlt1dcs\\/image\\/upload\\/v1780097929\\/inmobiliaria\\/fotos\\/qzu7u56zcxagyfssa1cy.png\",\"https:\\/\\/res.cloudinary.com\\/dywlt1dcs\\/image\\/upload\\/v1780097931\\/inmobiliaria\\/fotos\\/yjwaqjobkhb2mirkut8i.png\",\"https:\\/\\/res.cloudinary.com\\/dywlt1dcs\\/image\\/upload\\/v1780097933\\/inmobiliaria\\/fotos\\/zvdqgkm3lmxahwgm4keg.png\"]'),
(8, 8, 1, 'activo', 'arriendo', 1000000.00, '2026-05-29 18:41:52', 'Apartamento para estudiantes, viene completamente amoblado', '[\"https:\\/\\/res.cloudinary.com\\/dywlt1dcs\\/image\\/upload\\/v1780098108\\/inmobiliaria\\/fotos\\/j5yrgheftzjr1fdxge1e.png\",\"https:\\/\\/res.cloudinary.com\\/dywlt1dcs\\/image\\/upload\\/v1780098110\\/inmobiliaria\\/fotos\\/vu6x6evbdnifjl7lzvhg.png\",\"https:\\/\\/res.cloudinary.com\\/dywlt1dcs\\/image\\/upload\\/v1780098112\\/inmobiliaria\\/fotos\\/ycat33k2cil19qlhpngi.png\"]');

-- --------------------------------------------------------

--
-- Table structure for table `movimientos_billetera`
--

CREATE TABLE `movimientos_billetera` (
  `id_movimiento` int(11) NOT NULL,
  `id_billetera` int(11) NOT NULL,
  `tipo` enum('pagado','reversado','fondos_insuficientes') NOT NULL,
  `monto` decimal(15,2) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `movimientos_billetera_agente`
--

CREATE TABLE `movimientos_billetera_agente` (
  `id_movimiento` int(11) NOT NULL,
  `id_billetera` int(11) NOT NULL,
  `tipo` enum('comision','retiro','bonificacion') NOT NULL,
  `monto` decimal(15,2) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movimientos_billetera_agente`
--

INSERT INTO `movimientos_billetera_agente` (`id_movimiento`, `id_billetera`, `tipo`, `monto`, `fecha`) VALUES
(1, 2, 'comision', 40000000.00, '2026-05-29 18:24:34');

-- --------------------------------------------------------

--
-- Table structure for table `reservas`
--

CREATE TABLE `reservas` (
  `id_reserva` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_inmueble` int(11) NOT NULL,
  `id_agente` int(11) DEFAULT NULL,
  `estado` enum('pendiente','confirmada','cancelada','finalizada') NOT NULL DEFAULT 'pendiente',
  `fecha_reserva` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_visita` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('admin','agente','cliente') NOT NULL DEFAULT 'cliente',
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `email`, `password`, `rol`, `activo`, `fecha_registro`) VALUES
(1, 'jesus@adminbre.com', 'admin123', 'admin', 1, '2026-05-25 18:34:23'),
(2, 'carlos@agentebre.com', 'agente123', 'agente', 1, '2026-05-25 18:34:23'),
(3, 'laura@agentebre.com', 'agente123', 'agente', 1, '2026-05-25 18:34:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`id_administrador`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `agentes`
--
ALTER TABLE `agentes`
  ADD PRIMARY KEY (`id_agente`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_inmobiliaria` (`id_inmobiliaria`);

--
-- Indexes for table `billetera`
--
ALTER TABLE `billetera`
  ADD PRIMARY KEY (`id_billetera`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Indexes for table `billetera_agente`
--
ALTER TABLE `billetera_agente`
  ADD PRIMARY KEY (`id_billetera`),
  ADD KEY `id_agente` (`id_agente`);

--
-- Indexes for table `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`id_factura`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_inmueble` (`id_inmueble`),
  ADD KEY `id_agente` (`id_agente`),
  ADD KEY `id_reserva` (`id_reserva`);

--
-- Indexes for table `gobierno`
--
ALTER TABLE `gobierno`
  ADD PRIMARY KEY (`id_permiso`),
  ADD KEY `id_inmueble` (`id_inmueble`),
  ADD KEY `id_inmobiliaria` (`id_inmobiliaria`);

--
-- Indexes for table `inmobiliaria`
--
ALTER TABLE `inmobiliaria`
  ADD PRIMARY KEY (`id_inmobiliaria`);

--
-- Indexes for table `inmuebles`
--
ALTER TABLE `inmuebles`
  ADD PRIMARY KEY (`id_inmueble`),
  ADD KEY `id_agente` (`id_agente`),
  ADD KEY `id_inmobiliaria` (`id_inmobiliaria`);

--
-- Indexes for table `inventario`
--
ALTER TABLE `inventario`
  ADD PRIMARY KEY (`id_inventario`),
  ADD KEY `id_inmueble` (`id_inmueble`),
  ADD KEY `id_inmobiliaria` (`id_inmobiliaria`);

--
-- Indexes for table `movimientos_billetera`
--
ALTER TABLE `movimientos_billetera`
  ADD PRIMARY KEY (`id_movimiento`),
  ADD KEY `id_billetera` (`id_billetera`);

--
-- Indexes for table `movimientos_billetera_agente`
--
ALTER TABLE `movimientos_billetera_agente`
  ADD PRIMARY KEY (`id_movimiento`),
  ADD KEY `id_billetera` (`id_billetera`);

--
-- Indexes for table `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id_reserva`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_inmueble` (`id_inmueble`),
  ADD KEY `id_agente` (`id_agente`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `administrador`
--
ALTER TABLE `administrador`
  MODIFY `id_administrador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `agentes`
--
ALTER TABLE `agentes`
  MODIFY `id_agente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `billetera`
--
ALTER TABLE `billetera`
  MODIFY `id_billetera` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `billetera_agente`
--
ALTER TABLE `billetera_agente`
  MODIFY `id_billetera` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `facturas`
--
ALTER TABLE `facturas`
  MODIFY `id_factura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `gobierno`
--
ALTER TABLE `gobierno`
  MODIFY `id_permiso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inmobiliaria`
--
ALTER TABLE `inmobiliaria`
  MODIFY `id_inmobiliaria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `inmuebles`
--
ALTER TABLE `inmuebles`
  MODIFY `id_inmueble` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `inventario`
--
ALTER TABLE `inventario`
  MODIFY `id_inventario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `movimientos_billetera`
--
ALTER TABLE `movimientos_billetera`
  MODIFY `id_movimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `movimientos_billetera_agente`
--
ALTER TABLE `movimientos_billetera_agente`
  MODIFY `id_movimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `administrador`
--
ALTER TABLE `administrador`
  ADD CONSTRAINT `administrador_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Constraints for table `agentes`
--
ALTER TABLE `agentes`
  ADD CONSTRAINT `agentes_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `agentes_ibfk_2` FOREIGN KEY (`id_inmobiliaria`) REFERENCES `inmobiliaria` (`id_inmobiliaria`) ON DELETE SET NULL;

--
-- Constraints for table `billetera`
--
ALTER TABLE `billetera`
  ADD CONSTRAINT `billetera_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE;

--
-- Constraints for table `billetera_agente`
--
ALTER TABLE `billetera_agente`
  ADD CONSTRAINT `billetera_agente_ibfk_1` FOREIGN KEY (`id_agente`) REFERENCES `agentes` (`id_agente`) ON DELETE CASCADE;

--
-- Constraints for table `clientes`
--
ALTER TABLE `clientes`
  ADD CONSTRAINT `clientes_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Constraints for table `facturas`
--
ALTER TABLE `facturas`
  ADD CONSTRAINT `facturas_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE,
  ADD CONSTRAINT `facturas_ibfk_2` FOREIGN KEY (`id_inmueble`) REFERENCES `inmuebles` (`id_inmueble`) ON DELETE CASCADE,
  ADD CONSTRAINT `facturas_ibfk_3` FOREIGN KEY (`id_agente`) REFERENCES `agentes` (`id_agente`) ON DELETE SET NULL,
  ADD CONSTRAINT `facturas_ibfk_4` FOREIGN KEY (`id_reserva`) REFERENCES `reservas` (`id_reserva`) ON DELETE SET NULL;

--
-- Constraints for table `gobierno`
--
ALTER TABLE `gobierno`
  ADD CONSTRAINT `gobierno_ibfk_1` FOREIGN KEY (`id_inmueble`) REFERENCES `inmuebles` (`id_inmueble`) ON DELETE CASCADE,
  ADD CONSTRAINT `gobierno_ibfk_2` FOREIGN KEY (`id_inmobiliaria`) REFERENCES `inmobiliaria` (`id_inmobiliaria`) ON DELETE SET NULL;

--
-- Constraints for table `inmuebles`
--
ALTER TABLE `inmuebles`
  ADD CONSTRAINT `inmuebles_ibfk_1` FOREIGN KEY (`id_agente`) REFERENCES `agentes` (`id_agente`) ON DELETE SET NULL,
  ADD CONSTRAINT `inmuebles_ibfk_2` FOREIGN KEY (`id_inmobiliaria`) REFERENCES `inmobiliaria` (`id_inmobiliaria`) ON DELETE SET NULL;

--
-- Constraints for table `inventario`
--
ALTER TABLE `inventario`
  ADD CONSTRAINT `inventario_ibfk_1` FOREIGN KEY (`id_inmueble`) REFERENCES `inmuebles` (`id_inmueble`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventario_ibfk_2` FOREIGN KEY (`id_inmobiliaria`) REFERENCES `inmobiliaria` (`id_inmobiliaria`) ON DELETE SET NULL;

--
-- Constraints for table `movimientos_billetera`
--
ALTER TABLE `movimientos_billetera`
  ADD CONSTRAINT `movimientos_billetera_ibfk_1` FOREIGN KEY (`id_billetera`) REFERENCES `billetera` (`id_billetera`) ON DELETE CASCADE;

--
-- Constraints for table `movimientos_billetera_agente`
--
ALTER TABLE `movimientos_billetera_agente`
  ADD CONSTRAINT `movimientos_billetera_agente_ibfk_1` FOREIGN KEY (`id_billetera`) REFERENCES `billetera_agente` (`id_billetera`) ON DELETE CASCADE;

--
-- Constraints for table `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`id_inmueble`) REFERENCES `inmuebles` (`id_inmueble`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservas_ibfk_3` FOREIGN KEY (`id_agente`) REFERENCES `agentes` (`id_agente`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
