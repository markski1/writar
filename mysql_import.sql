-- --------------------------------------------------------
-- Host:                         localhost
-- Versión del servidor:         10.9.8-MariaDB-1:10.9.8+maria~ubu2204-log - mariadb.org binary distribution
-- SO del servidor:              debian-linux-gnu
-- HeidiSQL Versión:             12.5.0.6677
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para writar
CREATE DATABASE IF NOT EXISTS `writar` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `writar`;

-- Volcando estructura para tabla writar.documents
CREATE TABLE IF NOT EXISTS `documents` (
                                           `id` varchar(16) NOT NULL,
                                           `user_id` int(11) NOT NULL DEFAULT -1,
                                           `title` varchar(64) NOT NULL DEFAULT 'untitled',
                                           `content` text NOT NULL,
                                           `password` varchar(255) DEFAULT NULL,
                                           `visits` int(11) NOT NULL DEFAULT 0,
                                           `created_at` datetime NOT NULL DEFAULT current_timestamp(),
                                           `privacy` tinyint(4) DEFAULT NULL,
                                           UNIQUE KEY `url_id` (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla writar.ip_log
CREATE TABLE IF NOT EXISTS `ip_log` (
                                        `ip_addr` varchar(50) NOT NULL DEFAULT '',
                                        `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla writar.users
CREATE TABLE IF NOT EXISTS `users` (
                                       `id` int(11) NOT NULL AUTO_INCREMENT,
                                       `username` varchar(128) NOT NULL DEFAULT '0',
                                       `password` varchar(255) NOT NULL DEFAULT '0',
                                       `session_token` varchar(64) DEFAULT NULL,
                                       `email` varchar(128) DEFAULT NULL,
                                       `api_key` varchar(32) DEFAULT NULL,
                                       `created_at` datetime NOT NULL DEFAULT current_timestamp(),
                                       `last_op` datetime NOT NULL DEFAULT current_timestamp(),
                                       PRIMARY KEY (`id`),
                                       UNIQUE KEY `username` (`username`),
                                       KEY `email` (`username`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- La exportación de datos fue deseleccionada.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
