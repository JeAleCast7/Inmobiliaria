-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: inmobiliaria_db
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `administrador`
--

DROP TABLE IF EXISTS `administrador`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `administrador` (
  `id_administrador` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  PRIMARY KEY (`id_administrador`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `administrador_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `administrador`
--

LOCK TABLES `administrador` WRITE;
/*!40000 ALTER TABLE `administrador` DISABLE KEYS */;
INSERT INTO `administrador` VALUES (1,1);
/*!40000 ALTER TABLE `administrador` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `agentes`
--

DROP TABLE IF EXISTS `agentes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agentes` (
  `id_agente` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `tipo_documento` enum('CC','CE','Pasaporte','CERL') NOT NULL DEFAULT 'CC',
  `numero_documento` bigint(20) NOT NULL,
  `telefono` bigint(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `cargo` varchar(100) DEFAULT NULL,
  `fecha_ingreso` datetime NOT NULL DEFAULT current_timestamp(),
  `id_inmobiliaria` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_agente`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_inmobiliaria` (`id_inmobiliaria`),
  CONSTRAINT `agentes_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  CONSTRAINT `agentes_ibfk_2` FOREIGN KEY (`id_inmobiliaria`) REFERENCES `inmobiliaria` (`id_inmobiliaria`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agentes`
--

LOCK TABLES `agentes` WRITE;
/*!40000 ALTER TABLE `agentes` DISABLE KEYS */;
INSERT INTO `agentes` VALUES (1,2,'Carlos M├⌐ndez','CC',1020345678,3105550001,'carlos@agentebre.com','Agente Senior','2026-03-14 20:21:47',1),(2,3,'Laura Garc├¡a','CC',1030456789,3205550002,'laura@agentebre.com','Agente de Ventas','2026-03-14 20:21:47',1);
/*!40000 ALTER TABLE `agentes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `billetera`
--

DROP TABLE IF EXISTS `billetera`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `billetera` (
  `id_billetera` int(11) NOT NULL AUTO_INCREMENT,
  `id_cliente` int(11) NOT NULL,
  `saldo` decimal(15,2) NOT NULL DEFAULT 0.00,
  `estado` enum('activa','bloqueada') NOT NULL DEFAULT 'activa',
  PRIMARY KEY (`id_billetera`),
  KEY `id_cliente` (`id_cliente`),
  CONSTRAINT `billetera_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `billetera`
--

LOCK TABLES `billetera` WRITE;
/*!40000 ALTER TABLE `billetera` DISABLE KEYS */;
INSERT INTO `billetera` VALUES (1,1,0.00,'activa'),(2,2,0.00,'activa');
/*!40000 ALTER TABLE `billetera` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `billetera_agente`
--

DROP TABLE IF EXISTS `billetera_agente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `billetera_agente` (
  `id_billetera` int(11) NOT NULL AUTO_INCREMENT,
  `id_agente` int(11) NOT NULL,
  `saldo` decimal(15,2) NOT NULL DEFAULT 0.00,
  `estado` enum('activa','bloqueada') NOT NULL DEFAULT 'activa',
  PRIMARY KEY (`id_billetera`),
  KEY `id_agente` (`id_agente`),
  CONSTRAINT `billetera_agente_ibfk_1` FOREIGN KEY (`id_agente`) REFERENCES `agentes` (`id_agente`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `billetera_agente`
--

LOCK TABLES `billetera_agente` WRITE;
/*!40000 ALTER TABLE `billetera_agente` DISABLE KEYS */;
INSERT INTO `billetera_agente` VALUES (1,1,0.00,'activa'),(2,2,0.00,'activa');
/*!40000 ALTER TABLE `billetera_agente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clientes`
--

DROP TABLE IF EXISTS `clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `tipo_documento` enum('CC','CE','Pasaporte','CERL') NOT NULL DEFAULT 'CC',
  `numero_documento` bigint(20) NOT NULL,
  `telefono` bigint(20) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_cliente`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `clientes_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes`
--

LOCK TABLES `clientes` WRITE;
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
INSERT INTO `clientes` VALUES (1,4,'larva','CC',123456789,123456789,'calle 100 suba','2026-03-22 17:09:29'),(2,5,'papu','CC',1023456789,123456789,'calle 100 suba GOD','2026-04-06 16:48:46');
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `facturas`
--

DROP TABLE IF EXISTS `facturas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `facturas` (
  `id_factura` int(11) NOT NULL AUTO_INCREMENT,
  `id_cliente` int(11) NOT NULL,
  `id_inmueble` int(11) NOT NULL,
  `id_agente` int(11) DEFAULT NULL,
  `id_reserva` int(11) DEFAULT NULL,
  `tipo` enum('venta','arriendo') NOT NULL,
  `valor_total` decimal(15,2) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_factura`),
  KEY `id_cliente` (`id_cliente`),
  KEY `id_inmueble` (`id_inmueble`),
  KEY `id_agente` (`id_agente`),
  KEY `id_reserva` (`id_reserva`),
  CONSTRAINT `facturas_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE,
  CONSTRAINT `facturas_ibfk_2` FOREIGN KEY (`id_inmueble`) REFERENCES `inmuebles` (`id_inmueble`) ON DELETE CASCADE,
  CONSTRAINT `facturas_ibfk_3` FOREIGN KEY (`id_agente`) REFERENCES `agentes` (`id_agente`) ON DELETE SET NULL,
  CONSTRAINT `facturas_ibfk_4` FOREIGN KEY (`id_reserva`) REFERENCES `reservas` (`id_reserva`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `facturas`
--

LOCK TABLES `facturas` WRITE;
/*!40000 ALTER TABLE `facturas` DISABLE KEYS */;
/*!40000 ALTER TABLE `facturas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gobierno`
--

DROP TABLE IF EXISTS `gobierno`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gobierno` (
  `id_permiso` int(11) NOT NULL AUTO_INCREMENT,
  `id_inmueble` int(11) NOT NULL,
  `id_inmobiliaria` int(11) DEFAULT NULL,
  `tipo_permiso` varchar(150) DEFAULT NULL,
  `estado` enum('aprobado','pendiente','rechazado') NOT NULL DEFAULT 'pendiente',
  `fecha_solicitud` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_aprobacion` datetime DEFAULT NULL,
  `fecha_vencimiento` datetime DEFAULT NULL,
  `documento` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_permiso`),
  KEY `id_inmueble` (`id_inmueble`),
  KEY `id_inmobiliaria` (`id_inmobiliaria`),
  CONSTRAINT `gobierno_ibfk_1` FOREIGN KEY (`id_inmueble`) REFERENCES `inmuebles` (`id_inmueble`) ON DELETE CASCADE,
  CONSTRAINT `gobierno_ibfk_2` FOREIGN KEY (`id_inmobiliaria`) REFERENCES `inmobiliaria` (`id_inmobiliaria`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gobierno`
--

LOCK TABLES `gobierno` WRITE;
/*!40000 ALTER TABLE `gobierno` DISABLE KEYS */;
/*!40000 ALTER TABLE `gobierno` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inmobiliaria`
--

DROP TABLE IF EXISTS `inmobiliaria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inmobiliaria` (
  `id_inmobiliaria` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) NOT NULL,
  `NIT` varchar(30) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_inmobiliaria`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inmobiliaria`
--

LOCK TABLES `inmobiliaria` WRITE;
/*!40000 ALTER TABLE `inmobiliaria` DISABLE KEYS */;
INSERT INTO `inmobiliaria` VALUES (1,'Boyaca Real Estate','900123456-7','601-555-1234','Calle 100 #15-25, Bogot├í','contacto@bre.com','2026-03-14 20:21:47');
/*!40000 ALTER TABLE `inmobiliaria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inmuebles`
--

DROP TABLE IF EXISTS `inmuebles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inmuebles` (
  `id_inmueble` int(11) NOT NULL AUTO_INCREMENT,
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
  `id_inmobiliaria` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_inmueble`),
  KEY `id_agente` (`id_agente`),
  KEY `id_inmobiliaria` (`id_inmobiliaria`),
  CONSTRAINT `inmuebles_ibfk_1` FOREIGN KEY (`id_agente`) REFERENCES `agentes` (`id_agente`) ON DELETE SET NULL,
  CONSTRAINT `inmuebles_ibfk_2` FOREIGN KEY (`id_inmobiliaria`) REFERENCES `inmobiliaria` (`id_inmobiliaria`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inmuebles`
--

LOCK TABLES `inmuebles` WRITE;
/*!40000 ALTER TABLE `inmuebles` DISABLE KEYS */;

/*!40000 ALTER TABLE `inmuebles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventario`
--

DROP TABLE IF EXISTS `inventario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventario` (
  `id_inventario` int(11) NOT NULL AUTO_INCREMENT,
  `id_inmueble` int(11) NOT NULL,
  `id_inmobiliaria` int(11) DEFAULT NULL,
  `estado` enum('activo','pausado','vendido','arrendado') NOT NULL DEFAULT 'activo',
  `tipo` enum('venta','arriendo') NOT NULL,
  `precio` decimal(15,2) NOT NULL,
  `fecha_publicacion` datetime NOT NULL DEFAULT current_timestamp(),
  `descripcion` text DEFAULT NULL,
  `fotos` text DEFAULT NULL,
  PRIMARY KEY (`id_inventario`),
  KEY `id_inmueble` (`id_inmueble`),
  KEY `id_inmobiliaria` (`id_inmobiliaria`),
  CONSTRAINT `inventario_ibfk_1` FOREIGN KEY (`id_inmueble`) REFERENCES `inmuebles` (`id_inmueble`) ON DELETE CASCADE,
  CONSTRAINT `inventario_ibfk_2` FOREIGN KEY (`id_inmobiliaria`) REFERENCES `inmobiliaria` (`id_inmobiliaria`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventario`
--

LOCK TABLES `inventario` WRITE;
/*!40000 ALTER TABLE `inventario` DISABLE KEYS */;

/*!40000 ALTER TABLE `inventario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `movimientos_billetera`
--

DROP TABLE IF EXISTS `movimientos_billetera`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `movimientos_billetera` (
  `id_movimiento` int(11) NOT NULL AUTO_INCREMENT,
  `id_billetera` int(11) NOT NULL,
  `tipo` enum('pagado','reversado','fondos_insuficientes') NOT NULL,
  `monto` decimal(15,2) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_movimiento`),
  KEY `id_billetera` (`id_billetera`),
  CONSTRAINT `movimientos_billetera_ibfk_1` FOREIGN KEY (`id_billetera`) REFERENCES `billetera` (`id_billetera`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `movimientos_billetera`
--

LOCK TABLES `movimientos_billetera` WRITE;
/*!40000 ALTER TABLE `movimientos_billetera` DISABLE KEYS */;
/*!40000 ALTER TABLE `movimientos_billetera` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `movimientos_billetera_agente`
--

DROP TABLE IF EXISTS `movimientos_billetera_agente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `movimientos_billetera_agente` (
  `id_movimiento` int(11) NOT NULL AUTO_INCREMENT,
  `id_billetera` int(11) NOT NULL,
  `tipo` enum('comision','retiro','bonificacion') NOT NULL,
  `monto` decimal(15,2) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_movimiento`),
  KEY `id_billetera` (`id_billetera`),
  CONSTRAINT `movimientos_billetera_agente_ibfk_1` FOREIGN KEY (`id_billetera`) REFERENCES `billetera_agente` (`id_billetera`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `movimientos_billetera_agente`
--

LOCK TABLES `movimientos_billetera_agente` WRITE;
/*!40000 ALTER TABLE `movimientos_billetera_agente` DISABLE KEYS */;
/*!40000 ALTER TABLE `movimientos_billetera_agente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reservas`
--

DROP TABLE IF EXISTS `reservas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reservas` (
  `id_reserva` int(11) NOT NULL AUTO_INCREMENT,
  `id_cliente` int(11) NOT NULL,
  `id_inmueble` int(11) NOT NULL,
  `id_agente` int(11) DEFAULT NULL,
  `estado` enum('pendiente','confirmada','cancelada','finalizada') NOT NULL DEFAULT 'pendiente',
  `fecha_reserva` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_visita` datetime DEFAULT NULL,
  PRIMARY KEY (`id_reserva`),
  KEY `id_cliente` (`id_cliente`),
  KEY `id_inmueble` (`id_inmueble`),
  KEY `id_agente` (`id_agente`),
  CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE,
  CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`id_inmueble`) REFERENCES `inmuebles` (`id_inmueble`) ON DELETE CASCADE,
  CONSTRAINT `reservas_ibfk_3` FOREIGN KEY (`id_agente`) REFERENCES `agentes` (`id_agente`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reservas`
--

LOCK TABLES `reservas` WRITE;
/*!40000 ALTER TABLE `reservas` DISABLE KEYS */;
/*!40000 ALTER TABLE `reservas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('admin','agente','cliente') NOT NULL DEFAULT 'cliente',
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'jesus@adminbre.com','admin123','admin',1,'2026-03-14 20:21:47'),(2,'carlos@agentebre.com','agente123','agente',1,'2026-03-14 20:21:47'),(3,'laura@agentebre.com','agente123','agente',1,'2026-03-14 20:21:47'),(4,'larva@gmail.com','1234567','cliente',1,'2026-03-22 17:09:29'),(5,'papu@gmail.com','papu777','cliente',1,'2026-04-06 16:48:46');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-04-19 20:28:58
