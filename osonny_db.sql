-- MySQL dump 10.19  Distrib 10.3.39-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: osonny_db
-- ------------------------------------------------------
-- Server version	10.3.39-MariaDB-0ubuntu0.20.04.2

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
-- Table structure for table `descuentos`
--

DROP TABLE IF EXISTS `descuentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `descuentos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `restaurante_id` int(11) NOT NULL,
  `producto` varchar(100) NOT NULL,
  `precio_original` decimal(10,2) NOT NULL,
  `precio_descuento` decimal(10,2) NOT NULL,
  `imagen` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `restaurante_id` (`restaurante_id`),
  CONSTRAINT `descuentos_ibfk_1` FOREIGN KEY (`restaurante_id`) REFERENCES `restaurantes` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `descuentos`
--

LOCK TABLES `descuentos` WRITE;
/*!40000 ALTER TABLE `descuentos` DISABLE KEYS */;
INSERT INTO `descuentos` VALUES (1,1,'Hamburguesa Doble',120.00,90.00,'img/descuentos/hamburguesa_doble.jpg'),(2,2,'Pizza Grande',150.00,120.00,'img/descuentos/pizza_grande.jpg'),(3,3,'Taco Especial',30.00,20.00,'img/descuentos/taco_especial.jpg'),(4,1,'Hot Dog Clsico',40.00,25.00,'img/descuentos/hotdog.jpg'),(5,3,'Enchiladas Verdes',100.00,70.00,'img/descuentos/enchiladas.jpg'),(6,2,'Pizza Familiar',200.00,150.00,'img/descuentos/pizza_familiar.jpg'),(7,4,'Taco al Pastor',30.00,20.00,'img/descuentos/taco_pastor.jpg'),(8,1,'Burrito de Res',90.00,60.00,'img/descuentos/burrito.jpg');
/*!40000 ALTER TABLE `descuentos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_pedidos`
--

DROP TABLE IF EXISTS `detalle_pedidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detalle_pedidos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pedido_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pedido_id` (`pedido_id`),
  KEY `producto_id` (`producto_id`),
  CONSTRAINT `detalle_pedidos_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`),
  CONSTRAINT `detalle_pedidos_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_pedidos`
--

LOCK TABLES `detalle_pedidos` WRITE;
/*!40000 ALTER TABLE `detalle_pedidos` DISABLE KEYS */;
/*!40000 ALTER TABLE `detalle_pedidos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalles_pedido`
--

DROP TABLE IF EXISTS `detalles_pedido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detalles_pedido` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pedido_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `subtotal` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalles_pedido`
--

LOCK TABLES `detalles_pedido` WRITE;
/*!40000 ALTER TABLE `detalles_pedido` DISABLE KEYS */;
/*!40000 ALTER TABLE `detalles_pedido` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menus`
--

DROP TABLE IF EXISTS `menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_restaurante` int(11) NOT NULL,
  `nombre_plato` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `horario_disponible` varchar(255) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `restaurante_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_restaurante` (`id_restaurante`),
  CONSTRAINT `menus_ibfk_1` FOREIGN KEY (`id_restaurante`) REFERENCES `restaurantes` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menus`
--

LOCK TABLES `menus` WRITE;
/*!40000 ALTER TABLE `menus` DISABLE KEYS */;
INSERT INTO `menus` VALUES (1,1,'Big Mac','Hamburguesa clasica con papas y refresco.',100.00,'12:00 PM - 10:00 PM','img/bigmac.jpg',NULL),(2,1,'McNuggets','10 piezas de nuggets con papas y refresco.',120.00,'12:00 PM - 10:00 PM','img/mcnuggets.jpg',NULL),(3,2,'Alaaaa Burger Especial','Hamburguesa doble con queso y papas.',130.00,'6:00 PM - 11:00 PM','img/hambu1.jpg',NULL),(4,3,'Pepperoni Pizza','Pizza clasica de pepperoni.',150.00,'11:00 AM - 10:00 PM','img/pizzacorner1.jpg',NULL),(5,4,'Taco de Pastor','Autentico taco de pastor con salsa.',40.00,'5:00 PM - 11:00 PM','img/tacodepastor.jpg',NULL);
/*!40000 ALTER TABLE `menus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `status` enum('pending','paid','cancelled') DEFAULT 'pending',
  `total` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pedidos`
--

DROP TABLE IF EXISTS `pedidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(255) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `repartidor_id` int(11) DEFAULT NULL,
  `restaurante_id` int(11) NOT NULL DEFAULT 1,
  `total` float NOT NULL,
  `comision` decimal(10,2) DEFAULT 0.00,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` enum('pendiente','en camino','entregado') NOT NULL DEFAULT 'pendiente',
  `metodo_pago` enum('efectivo','tarjeta','otros','paypal') NOT NULL,
  `direccion` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedidos`
--

LOCK TABLES `pedidos` WRITE;
/*!40000 ALTER TABLE `pedidos` DISABLE KEYS */;
INSERT INTO `pedidos` VALUES (3,'',1,NULL,0,254,0.00,'2024-11-23 21:54:16','pendiente','efectivo',''),(4,'',1,1,0,254,0.00,'2024-11-23 21:55:27','en camino','efectivo',''),(5,'',1,2,0,254,0.00,'2024-11-23 21:56:45','en camino','efectivo',''),(6,'',1,2,0,132.08,0.00,'2024-11-23 22:05:26','en camino','efectivo',''),(7,'',1,NULL,0,152.4,0.00,'2024-11-23 22:13:27','pendiente','efectivo',''),(8,'',1,NULL,0,132.08,0.00,'2024-11-24 07:53:46','pendiente','efectivo',''),(9,'',1,NULL,0,152.4,0.00,'2024-11-24 07:58:18','pendiente','efectivo',''),(10,'Pedido pendiente de asignacinnn',1,1,1,150,0.00,'2024-12-03 05:55:11','en camino','efectivo',''),(11,'Pedido realizado desde el carrito',1,NULL,1,20.32,0.00,'2024-12-03 07:01:21','en camino','efectivo',''),(12,'Nuevo pedido',1,2,1,500,0.00,'2024-12-04 07:23:09','en camino','paypal','Calle Falsa 123'),(13,'Nuevo pedido',1,2,1,500,0.00,'2024-12-04 07:23:40','en camino','paypal','Calle Falsa 123');
/*!40000 ALTER TABLE `pedidos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productos`
--

DROP TABLE IF EXISTS `productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `restaurante_id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `restaurante_id` (`restaurante_id`),
  CONSTRAINT `fk_restaurante` FOREIGN KEY (`restaurante_id`) REFERENCES `restaurantes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`restaurante_id`) REFERENCES `restaurantes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productos`
--

LOCK TABLES `productos` WRITE;
/*!40000 ALTER TABLE `productos` DISABLE KEYS */;
INSERT INTO `productos` VALUES (1,5,'Gordita De Asado','Gorditas de asado ',15.00,'uploads/673fe96f18640.jpg'),(3,7,'Flauta','Flauta de Frijoles',15.00,'uploads/674d3de09bddd.jpg'),(5,7,'taco de tostada','muy rico',120.03,'uploads/674f05aea78ba.jpg');
/*!40000 ALTER TABLE `productos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `repartidores`
--

DROP TABLE IF EXISTS `repartidores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `repartidores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `estado` enum('disponible','ocupado','inactivo') DEFAULT 'disponible',
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `repartidores`
--

LOCK TABLES `repartidores` WRITE;
/*!40000 ALTER TABLE `repartidores` DISABLE KEYS */;
INSERT INTO `repartidores` VALUES (1,'Christian Ramos','ocupado','2024-11-24 01:38:08'),(2,'Elias Hernndez','disponible','2024-11-24 01:38:08'),(3,'Paul Taboada','inactivo','2024-11-24 01:38:08');
/*!40000 ALTER TABLE `repartidores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reseñas`
--

DROP TABLE IF EXISTS `reseñas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reseñas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `restaurante_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `calificacion` int(11) NOT NULL CHECK (`calificacion` between 1 and 5),
  `comentario` text DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `restaurante_id` (`restaurante_id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `reseñas_ibfk_1` FOREIGN KEY (`restaurante_id`) REFERENCES `restaurantes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reseñas_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reseñas`
--

LOCK TABLES `reseñas` WRITE;
/*!40000 ALTER TABLE `reseñas` DISABLE KEYS */;
/*!40000 ALTER TABLE `reseñas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `restaurantes`
--

DROP TABLE IF EXISTS `restaurantes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `restaurantes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `categoria` varchar(255) DEFAULT 'General',
  `horario` varchar(255) DEFAULT NULL,
  `calificacion` decimal(3,2) DEFAULT NULL,
  `reseñas` int(11) DEFAULT NULL,
  `banner` varchar(255) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `restaurantes`
--

LOCK TABLES `restaurantes` WRITE;
/*!40000 ALTER TABLE `restaurantes` DISABLE KEYS */;
INSERT INTO `restaurantes` VALUES (1,0,'McDonald\'s','Hamburguesas, Americana','7:00 AM - 11:00 PM',4.20,801,'img/mc.png','Calle Cooperativa 514, Ciudad Victoria',NULL),(2,0,'Alaaaa Burger','Hamburguesas','6:00 PM - 11:00 PM',4.30,450,'img/alaburger.jpg',NULL,NULL),(3,0,'Pizza Corner','Pizzas','11:00 AM - 10:00 PM',4.80,1200,'img/pizzacorner.jpg',NULL,NULL),(4,0,'Tacos Locos','Mexicana','5:00 PM - 11:00 PM',4.70,650,'img/tacoslocos.jpg',NULL,NULL),(5,8,'Gorditas Tota','','9:00 AM - 9:00 PM',NULL,NULL,'uploads/673fe8ff4d11c.jpg','Manzana 24 lote 25 calle unificacion, unidad modelo','8342870923'),(7,25,'Cafeteria Jaguares 1','General','7:00 AM - 9:00 PM',NULL,NULL,'uploads/674d35fd83acf.png','Universidad Politecnica','8342710462');
/*!40000 ALTER TABLE `restaurantes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `soporte_cliente`
--

DROP TABLE IF EXISTS `soporte_cliente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `soporte_cliente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `mensaje` text NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` enum('pendiente','atendido') DEFAULT 'pendiente',
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `soporte_cliente_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `soporte_cliente`
--

LOCK TABLES `soporte_cliente` WRITE;
/*!40000 ALTER TABLE `soporte_cliente` DISABLE KEYS */;
INSERT INTO `soporte_cliente` VALUES (1,1,'aaaaaa','2024-12-02 17:50:30','pendiente'),(2,1,'aaaaaa','2024-12-02 17:50:35','pendiente'),(3,1,'Pruebaaa','2024-12-03 07:19:13','pendiente'),(4,34,'sus repartidores son medio homosexuales','2024-12-03 13:06:43','pendiente');
/*!40000 ALTER TABLE `soporte_cliente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contraseña` varchar(255) DEFAULT NULL,
  `tipo_usuario` enum('cliente','restaurante','repartidor','admin') DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'Mauricio','Mauricio@gmail.com','$2y$10$k179qd26nXerciT0G7jeYedRzRbkNKeDnVkEykaF9kB.2XgFEtAhe','cliente','2024-11-20 03:16:02'),(3,'Mauricio2','Mauriciorest@gmail.com','$2y$10$7qXZkU/62ETo7yD8jRjrJO56sN1PUu8AyPxCliupe5EjjR9on2It.','restaurante','2024-11-20 06:31:58'),(4,'Mauricio2','Mauricio2@gmail.com','$2y$10$I.Z5Vq5YfaLjznTEbdT7ke36PtieEuRO/lGopacxoTorIf12j.uLi','restaurante','2024-11-20 07:47:52'),(5,'mayte','mayte@gmail.com','$2y$10$wD6rWlnrPcEfWO/95eGLre2hN80vtdLO/MzUeBmR4h7lxTjV3hiJy','cliente','2024-11-20 15:33:43'),(6,'tacos','tacos@gmail.com','$2y$10$G1yKEBQ8Rn2Pw2RzwFHTU.Aa39R1TDro14glJYd1WUsQzyg/.U8Ra','restaurante','2024-11-20 15:35:32'),(7,'Luis','Luis@gmail.com','$2y$10$ZFZHVuOBi9APOT7zI4H.TeRlasVKpthsdAwUeG19KxhWkUGNU9sVm','cliente','2024-11-22 00:21:06'),(8,'Luis2','Luis2@gmail.com','$2y$10$YdxhoOf2m.YK5.WZ7sDHyO1r/XUo280Wm7FlcWdJJ3v6ObdfiJLrK','restaurante','2024-11-22 00:27:00'),(9,'Mauricio3','Mauricio3@gmail.com','$2y$10$fUgQnw8dkD8RjJpKTs/VUuBZDewXoYwVWBY4Xrf825g3ViycxCJka','cliente','2024-11-22 15:11:10'),(10,'Carreon','123@gmail.com','$2y$10$N7oxbmZd2MJSt4suBGIVvuZ22EkbjHSMbs38MQxuHYQ066W1RD2HC','cliente','2024-11-22 15:16:29'),(11,'','Mayte1@gmail.com','$2y$10$Fzx6b.H2UshE2hD6bmpdTOuvKzonKlN9GJJVt9WK81zr7yMcARuGS','cliente','2024-11-22 15:23:58'),(12,'demo demo','demo@gmail.com','$2y$10$Q0apgA/..K3/Rkkb9PvcbuEyOcw5yPFFhfUxPo/e2oAtBmrMLc3iC','cliente','2024-11-22 16:13:22'),(13,'demo2','demo2@gmail.com','$2y$10$TvJ7W0f9lOJ0VOUDLkAuA.2CSTAhPrDW9xRQvn4KTRa2h5NojXoMC','restaurante','2024-11-22 16:14:44'),(14,'Mauricio3@gmail.com','MauricioRep@gmail.com','$2y$10$vFYJVkHkfHsVBs1sBBI0AOYP9.g9aB0dIjB/Wk6bmxQ7y4dSxEFQe','repartidor','2024-11-23 20:18:40'),(15,'Homero','HomeroRep@gmail.com','$2y$10$8gDMcsK1B/VDXLOiBiibs.2ukoDvvnDZYxaTHJ/bBHnvugwKdIu4y','repartidor','2024-11-23 20:22:23'),(16,'root','admin@gmail.com','63a9f0ea7bb98050796b649e85481845','','2024-11-24 00:34:18'),(18,'root','admin1@gmail.com','$2y$10$gRbzvZYLgNTICwzgicrAzuKT7EWkWa3hJcBc2Y18IXDHPLsIITam6','admin','2024-11-24 00:36:18'),(20,'Mario','DrMario@gmail.com','63a9f0ea7bb98050796b649e85481845','admin','2024-11-24 07:47:48'),(21,'Mauricio5','Mauricio5@gmail.com','$2y$10$1rGZWTFzAdtRZtkPsUT5bubsvwIn/UGhsC16MGPi6lxu8gTy1ALri','repartidor','2024-11-24 07:52:44'),(22,'Jonathan','velez@gmail.com','$2y$10$hx1EBhj9OC98bEqNDVexkua4rulNQHcWfg1fklunVFw.zl7xNBdpu','cliente','2024-11-29 20:02:58'),(23,'Velez','velezzunigajonathan17@gmail.com','$2y$10$yIXQ.HXH4RFMzqaI7F6Om.4urN2OV5ueZsfjzCVWbDdgJvikGB0xC','repartidor','2024-11-29 20:07:13'),(24,'Velez','2230234@upv.edu.mx','$2y$10$P2jQT6NxzFb8kp8qrZCM3.kmu4H8gPvPTeQ02qXlt7dBUdr7ldYL2','restaurante','2024-11-29 20:09:14'),(25,'Luis5','Luis5Rest@gmail.com','$2y$10$tM1QbpwUnEXap.dJPoBRaOCbKZl.t/IvK/6U3qWZxSdDTgVqKBBzq','restaurante','2024-11-29 20:13:45'),(26,'Erick','mataveraerik@gmail.com','$2y$10$18z7DnPYRl0ztFGuyQBpAex5VFHhmM28td7XYHzBRQtVNlUpyCu7.','repartidor','2024-11-29 21:45:17'),(27,'Fer','fer@gmail.com','$2y$10$pc911C1N41qctMXXtf2.vu3jdtXvkNe.N5/mh.mGy5qf7hZhVqhhy','cliente','2024-11-29 21:49:16'),(28,'Angela','angelamacarenavs@gmail.com','$2y$10$G5xBsSldT7puUxpBjnvMV.Ki/snu74GL8ACIB1eAYbhVAcmIirlLC','cliente','2024-11-30 03:42:52'),(29,'Oliver ulises','oliver684@hotmail.com','$2y$10$XNBIBkYbNgashxvKX6P8qO9MYm8ksvoQ2IQg0//HNDdjHw5Os5uG6','cliente','2024-12-01 23:18:33'),(30,'fernando paulin','03snowflakesfakes@gmail.com','$2y$10$ksvg2Lu5SAAPXcf/25TV5.LYFX24aQr53/Yl8F6QIQ1fYsPsZKcPK','cliente','2024-12-02 06:09:50'),(31,'Mauricio1','Mauricio1@gmail.com','$2y$10$k2brAvcciKh28ZXhua3mOOFYMe/ccZGn3Zouh9jTpvy0.OQwJPPYa','cliente','2024-12-03 05:27:17'),(32,'MauricioRepartidor','Mauricio31@gmail.com','$2y$10$NxG.30r0xDFHXfPHN3o0eeH401yJ.agl3e0paVTrsN../vVUiRLom','repartidor','2024-12-03 05:33:39'),(33,'cinthia','cinthia@gmail.com','$2y$10$u.c0SOZxOXDncM1H1TiSd.3MSv9a9emdKOVsham9/s3Jxg44A7vZO','repartidor','2024-12-03 13:03:19'),(34,'osiel','osiel@gmail.com','$2y$10$lwl8GLnNnUw.ZXhonHqQUeM7GddYFhhRdgqAjY/Mw9zX.yA4GhYly','cliente','2024-12-03 13:04:32'),(35,'osie1','osie1@gmail.com','$2y$10$HQQqeOL4Pk5shPVtKJ9OrOmbjTxVNY//ZqBu0f6qGQXUI/AURmbbO','restaurante','2024-12-03 13:14:45');
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

-- Dump completed on 2024-12-04 22:00:59
