/*
Navicat MySQL Data Transfer

Source Server         : XAMPP
Source Server Version : 100427
Source Host           : localhost:3306
Source Database       : db_cotizador2024

Target Server Type    : MYSQL
Target Server Version : 100427
File Encoding         : 65001

Date: 2024-01-23 10:41:51
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for bee_permisos
-- ----------------------------
DROP TABLE IF EXISTS `bee_permisos`;
CREATE TABLE `bee_permisos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `creado` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- ----------------------------
-- Records of bee_permisos
-- ----------------------------
INSERT INTO `bee_permisos` VALUES ('1', 'Acceso de administrador', 'admin-access', 'Acceso general de administración', '2023-09-08 11:55:59');
INSERT INTO `bee_permisos` VALUES ('2', 'Listar productos', 'list-all-products', 'Listar todos los productos de la base de datos.', '2023-09-08 12:01:07');
INSERT INTO `bee_permisos` VALUES ('3', 'Agregar nuevos productos', 'add-products', 'Agregar productos a la base de datos.', '2023-09-08 12:28:40');

-- ----------------------------
-- Table structure for bee_roles
-- ----------------------------
DROP TABLE IF EXISTS `bee_roles`;
CREATE TABLE `bee_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `creado` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- ----------------------------
-- Records of bee_roles
-- ----------------------------
INSERT INTO `bee_roles` VALUES ('1', 'Administrador general', 'admin', '2023-09-08 11:55:12');
INSERT INTO `bee_roles` VALUES ('2', 'Trabajador', 'worker', '2023-09-08 11:55:22');
INSERT INTO `bee_roles` VALUES ('3', 'Role de prueba', 'test', '2023-09-08 12:38:32');

-- ----------------------------
-- Table structure for bee_roles_permisos
-- ----------------------------
DROP TABLE IF EXISTS `bee_roles_permisos`;
CREATE TABLE `bee_roles_permisos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_role` int(11) NOT NULL,
  `id_permiso` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- ----------------------------
-- Records of bee_roles_permisos
-- ----------------------------
INSERT INTO `bee_roles_permisos` VALUES ('1', '1', '1');
INSERT INTO `bee_roles_permisos` VALUES ('2', '2', '2');
INSERT INTO `bee_roles_permisos` VALUES ('3', '2', '3');

-- ----------------------------
-- Table structure for bee_users
-- ----------------------------
DROP TABLE IF EXISTS `bee_users`;
CREATE TABLE `bee_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `auth_token` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- ----------------------------
-- Records of bee_users
-- ----------------------------
INSERT INTO `bee_users` VALUES ('1', '$2y$10$JFzQk8xuuvdk/R.YyT2PduFHHAkPx6DcLqPPOpnNAvQD42vw/VM1G', 'bee', '$2y$10$xHEI5cJ3q7rBJaL.M9qBRe909ahHvIZVTfRRxlLqfnWwAYwWQE/Wu', 'jslocal@localhost.com', '2021-12-05 15:52:17');

-- ----------------------------
-- Table structure for cotizaciones
-- ----------------------------
DROP TABLE IF EXISTS `cotizaciones`;
CREATE TABLE `cotizaciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numero` varchar(50) DEFAULT NULL,
  `cliente` varchar(100) DEFAULT NULL,
  `empresa` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `subtotal` decimal(10,0) DEFAULT NULL,
  `impuestos` decimal(10,0) DEFAULT NULL,
  `envio` decimal(10,0) DEFAULT NULL,
  `total` decimal(10,0) DEFAULT NULL,
  `pdf` varchar(255) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `creado` datetime DEFAULT NULL,
  `actualizado` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- ----------------------------
-- Records of cotizaciones
-- ----------------------------
INSERT INTO `cotizaciones` VALUES ('2', '838192', 'Juanito Doe', 'Joystick', 'jslocal@localhost.com', '300', '48', '150', '498', 'Cotización-838192.pdf', 'completed', '2024-01-16 23:01:37', '2024-01-23 10:33:22');
INSERT INTO `cotizaciones` VALUES ('3', '785973', 'John Doe Editado', 'Doe Inc', 'jslocal@localhost.com', '7372', '1180', '0', '8552', 'Cotización-785973.pdf', 'sent', '2024-01-16 23:22:23', '2024-01-23 10:21:39');
INSERT INTO `cotizaciones` VALUES ('4', '572483', 'Walter White Editado', 'Breaking Bad', 'jslocal@localhost.com', '4395', '703', '0', '5098', 'Cotización-572483.pdf', 'completed', '2024-01-22 16:16:41', '2024-01-23 10:36:45');

-- ----------------------------
-- Table structure for items_cotizaciones
-- ----------------------------
DROP TABLE IF EXISTS `items_cotizaciones`;
CREATE TABLE `items_cotizaciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cotizacion` int(11) DEFAULT NULL,
  `concepto` varchar(100) DEFAULT NULL,
  `tipo` varchar(20) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `impuestos` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- ----------------------------
-- Records of items_cotizaciones
-- ----------------------------
INSERT INTO `items_cotizaciones` VALUES ('2', '3', 'Zapatos', 'producto', '4', '1500.00', '960.00', '6960.00');
INSERT INTO `items_cotizaciones` VALUES ('3', '3', 'Chamarra', 'producto', '5', '24.00', '19.00', '139.00');
INSERT INTO `items_cotizaciones` VALUES ('10', '3', 'Guitarra editada', 'producto', '1', '52.00', '8.00', '60.00');
INSERT INTO `items_cotizaciones` VALUES ('11', '3', 'Diseño web editado', 'servicio', '1', '1200.00', '192.00', '1392.00');
INSERT INTO `items_cotizaciones` VALUES ('12', '2', 'Producto cool', 'producto', '2', '150.00', '48.00', '348.00');
INSERT INTO `items_cotizaciones` VALUES ('14', '4', 'Diseño de sitios web', 'servicio', '1', '2577.12', '412.34', '2989.46');
INSERT INTO `items_cotizaciones` VALUES ('15', '4', 'Hosting', 'servicio', '1', '899.00', '144.00', '1043.00');
INSERT INTO `items_cotizaciones` VALUES ('16', '4', 'Dominio', 'producto', '1', '550.00', '88.00', '638.00');
INSERT INTO `items_cotizaciones` VALUES ('17', '4', 'Nuevo concepto cool', 'producto', '3', '123.00', '59.04', '428.04');

-- ----------------------------
-- Table structure for options
-- ----------------------------
DROP TABLE IF EXISTS `options`;
CREATE TABLE `options` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `option` varchar(255) DEFAULT NULL,
  `val` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- ----------------------------
-- Records of options
-- ----------------------------

-- ----------------------------
-- Table structure for posts
-- ----------------------------
DROP TABLE IF EXISTS `posts`;
CREATE TABLE `posts` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `tipo` varchar(100) DEFAULT '',
  `id_padre` bigint(20) DEFAULT NULL,
  `id_usuario` bigint(20) DEFAULT NULL,
  `id_ref` bigint(20) DEFAULT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `permalink` varchar(255) DEFAULT NULL,
  `contenido` text DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `mime_type` varchar(255) DEFAULT NULL,
  `creado` datetime DEFAULT NULL,
  `actualizado` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of posts
-- ----------------------------

-- ----------------------------
-- Table structure for posts_meta
-- ----------------------------
DROP TABLE IF EXISTS `posts_meta`;
CREATE TABLE `posts_meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_post` int(11) NOT NULL,
  `meta` varchar(255) DEFAULT NULL,
  `valor` text DEFAULT NULL,
  `creado` datetime DEFAULT NULL,
  `actualizado` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- ----------------------------
-- Records of posts_meta
-- ----------------------------

-- ----------------------------
-- Table structure for productos
-- ----------------------------
DROP TABLE IF EXISTS `productos`;
CREATE TABLE `productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sku` varchar(100) DEFAULT NULL,
  `nombre` varchar(255) DEFAULT '',
  `slug` varchar(255) DEFAULT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `precio_comparacion` decimal(10,2) DEFAULT NULL,
  `stock` int(10) DEFAULT NULL,
  `rastrear_stock` tinyint(5) DEFAULT 0,
  `imagen` varchar(255) DEFAULT NULL,
  `creado` datetime DEFAULT NULL,
  `actualizado` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- ----------------------------
-- Records of productos
-- ----------------------------
INSERT INTO `productos` VALUES ('1', null, 'Pack de desarrollo web Full Stack', 'pack-de-desarrollo-full-stack', 'Un paquete con más de 20 cursos premium.', '300.00', '1000.00', '10', '1', 'packfullstack.png', '2023-08-10 07:52:50', '2023-08-11 09:37:31');
INSERT INTO `productos` VALUES ('2', null, 'Emprendepack', 'emprendepack', 'Paquete de cursos para emprendedores', '199.00', '500.00', null, '0', 'testimage.jpg', '2023-08-10 08:18:34', '2023-08-11 09:36:06');
INSERT INTO `productos` VALUES ('3', null, 'Curso crea un sistema escolar con PHP y MySQL', 'curso-crea-un-sistema-escolar', 'Lorel ipsum dolorem etsem.', '150.00', '799.00', null, '0', 'sistemaescolar.jpg', '2023-08-11 09:40:26', '2023-08-11 09:42:50');

-- ----------------------------
-- Table structure for pruebas
-- ----------------------------
DROP TABLE IF EXISTS `pruebas`;
CREATE TABLE `pruebas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) DEFAULT '',
  `titulo` varchar(255) DEFAULT NULL,
  `contenido` text DEFAULT NULL,
  `creado` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- ----------------------------
-- Records of pruebas
-- ----------------------------
INSERT INTO `pruebas` VALUES ('1', 'John Doe', 'Un post de prueba', 'Lorem ipsum dolorem.', '2021-12-10 10:55:41');
INSERT INTO `pruebas` VALUES ('2', 'Pancho Villa', 'Otro post nuevo', 'Lorem ipsum dolorem.', '2021-12-10 11:02:01');
