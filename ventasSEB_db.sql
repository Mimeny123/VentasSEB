-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 20, 2026 at 03:58 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ventasSEB`
--

-- --------------------------------------------------------

--
-- Table structure for table `motivosReportes`
--

CREATE TABLE `motivosReportes` (
  `motivo_id` tinyint(3) UNSIGNED NOT NULL,
  `nombre` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `motivosReportes`
--

INSERT INTO `motivosReportes` (`motivo_id`, `nombre`) VALUES
(1, 'Posible Estafa/Fraude'),
(2, 'Contenido inapropiado/Ofensivo'),
(3, 'Producto prohibido o ilegal'),
(4, 'Spam'),
(99, 'Otro');

-- --------------------------------------------------------

--
-- Table structure for table `posteos`
--

CREATE TABLE `posteos` (
  `post_id` int(10) UNSIGNED NOT NULL,
  `titulo` varchar(320) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` int(10) UNSIGNED DEFAULT NULL,
  `imagen` varchar(400) DEFAULT NULL,
  `estado` enum('activo','pausado','sin_stock') DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `mp` varchar(60) DEFAULT NULL,
  `usuario_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posteos`
--

INSERT INTO `posteos` (`post_id`, `titulo`, `descripcion`, `precio`, `imagen`, `estado`, `fecha`, `mp`, `usuario_id`) VALUES
(1, 'Kasane Teto Plushie Mediano', '¿Tienes frio y ganas de compañia a la hora de dormir? Pues no te preocupes! Tu amiga Teto ahora en forma de peluche de 15 centimetros cubicos te hara compañia en las noches frias y solitarias con cariño y aprecio puro!', 23000, 'https://http2.mlstatic.com/D_NQ_NP_667726-MLC95604992320_102025-O.webp', NULL, '2026-10-16 03:59:06', 'gustavito.cebolla.mp', 1),
(2, 'Calculadora Científica Casio', 'Funciona de diez, casi sin uso, ideal para clases.', 18000, 'https://images.unsplash.com/photo-1587145820266-a5951ee6f620?w=600', 'activo', '2026-11-20 01:15:56', 'santi.seb.mp', 1),
(3, 'Auriculares Inalámbricos JBL Tune', 'Batería impecable y buen sonido, incluye cable.', 24500, 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600', 'activo', '2026-11-20 01:15:56', 'david.seb.mp', 2),
(4, 'Teclado Mecánico RGB 60%', 'Switches rojos silenciosos, muy cuidado.', 32000, 'https://images.unsplash.com/photo-1511467687858-23d96c32e4ae?w=600', 'activo', '2026-11-20 01:15:56', 'gus.seb.mp', 3),
(5, 'Mochila Urbana Reforzada Negra', 'Entra notebook y carpetas sin drama, cierres sanos.', 14000, 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=600', 'activo', '2026-11-20 01:15:56', 'santi.seb.mp', 1),
(6, 'Don pollo ejemplo borrar', 'pollo, y picante y no\' fuimo\', uuuummmm', 67000, 'imagenes/1795142084_donpollo.jpg', NULL, '2026-11-20 02:34:44', 'king.ohio.mp', 3);

-- --------------------------------------------------------

--
-- Table structure for table `reportes`
--

CREATE TABLE `reportes` (
  `reporte_id` int(10) UNSIGNED NOT NULL,
  `motivo_id` tinyint(3) UNSIGNED DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `usuario_id` int(10) UNSIGNED DEFAULT NULL,
  `post_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reportes`
--

INSERT INTO `reportes` (`reporte_id`, `motivo_id`, `descripcion`, `fecha`, `usuario_id`, `post_id`) VALUES
(1, 1, 'El peluche se ve demasiado bueno como para ser verdad!', '2000-06-07 09:00:07', 2, 1),
(2, 3, 'es ilegal', '2026-11-20 02:11:16', 3, 4);

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `usuario_id` int(10) UNSIGNED NOT NULL,
  `usuario` varchar(50) DEFAULT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `apellido` varchar(50) DEFAULT NULL,
  `edad` date DEFAULT NULL,
  `gmail` varchar(100) DEFAULT NULL,
  `telefono` varchar(25) DEFAULT NULL,
  `contraseña` varchar(255) DEFAULT NULL,
  `imagen` varchar(400) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`usuario_id`, `usuario`, `nombre`, `apellido`, `edad`, `gmail`, `telefono`, `contraseña`, `imagen`) VALUES
(1, 'XxGustavitoProxX', 'Gustavo', 'Sanchez', '2000-01-01', 'gustavoivansb@goodmail.com', '+54 9 11 2513-9452', 'admin1', NULL),
(2, 'XxHaterOfHapinessxX67', 'Rodolfo', 'Desgracia', '1999-12-31', 'rodolfohernesto3003@badmail.com', '+54 9 11 3296-7092', 'Anti.Felicidad221809*', NULL),
(3, 'santi_seb', 'Santiago', 'Bernal', '1967-07-06', 's.bernallugo0627@gmail.com', '1155443322', '123456', 'imagenes/perfil_3_1795140600_Ciroo.png'),
(4, 'david_seb', 'David', 'Erusalimsky', '2008-09-21', 'erusalimskydavid@gmail.com', '1166778899', '123456', NULL),
(5, 'gus_seb', 'Gustavo', 'Sanchez', '2009-07-08', 'gustavoivansb@gmail.com', '1144223311', '123456', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `motivosReportes`
--
ALTER TABLE `motivosReportes`
  ADD PRIMARY KEY (`motivo_id`);

--
-- Indexes for table `posteos`
--
ALTER TABLE `posteos`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indexes for table `reportes`
--
ALTER TABLE `reportes`
  ADD PRIMARY KEY (`reporte_id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `motivo_id` (`motivo_id`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`usuario_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `posteos`
--
ALTER TABLE `posteos`
  MODIFY `post_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `reportes`
--
ALTER TABLE `reportes`
  MODIFY `reporte_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `usuario_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `posteos`
--
ALTER TABLE `posteos`
  ADD CONSTRAINT `posteos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`usuario_id`);

--
-- Constraints for table `reportes`
--
ALTER TABLE `reportes`
  ADD CONSTRAINT `reportes_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`usuario_id`),
  ADD CONSTRAINT `reportes_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posteos` (`post_id`),
  ADD CONSTRAINT `reportes_ibfk_3` FOREIGN KEY (`motivo_id`) REFERENCES `motivosReportes` (`motivo_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
