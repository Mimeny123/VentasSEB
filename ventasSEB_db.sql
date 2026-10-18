-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 16, 2026 at 03:03 AM
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
-- Table structure for table `posteos`
--

CREATE TABLE `posteos` (
  `post_id` int(10) UNSIGNED NOT NULL,
  `titulo` varchar(320) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` int(10) UNSIGNED DEFAULT NULL,
  `imagen` varchar(400) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `usuario_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posteos`
--

INSERT INTO `posteos` (`post_id`, `titulo`, `descripcion`, `precio`, `imagen`, `fecha`, `usuario_id`) VALUES
(1, 'Kasane Teto Plushie Mediano', '¿Tienes frio y ganas de compañia a la hora de dormir? Pues no te preocupes! Tu amiga Teto ahora en forma de peluche de 15 centimetros cubicos te hara compañia en las noches frias y solitarias con cariño y aprecio puro!', 23000, 'https://http2.mlstatic.com/D_NQ_NP_667726-MLC95604992320_102025-O.webp', '2026-10-16 00:59:06', 1);

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `usuario_id` int(10) UNSIGNED NOT NULL,
  `usuario` varchar(50) DEFAULT NULL,
  `gmail` varchar(320) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `edad` DATE DEFAULT NULL,
  `contraseña` varchar(255) DEFAULT NULL,
  `telefono` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`usuario_id`, `usuario`, `gmail`, `nombre`, `apellido`, `edad`, `contraseña`, `telefono`) VALUES
(1, 'XxGustavitoProxX', 'gustavoivansb@gmail.com', 'Gustavo', 'Sanchez', 67, 'admin1', '+54 9 11 2513-9452');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `posteos`
--
ALTER TABLE `posteos`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `usuario_id` (`usuario_id`);

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
  MODIFY `post_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `usuario_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `posteos`
--
ALTER TABLE `posteos`
  ADD CONSTRAINT `posteos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`usuario_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
