-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-03-2025 a las 03:09:13
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `gestion_usuarios`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cambios_precios`
--

CREATE TABLE `cambios_precios` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `precio_nuevo` decimal(10,2) NOT NULL,
  `aprobado` enum('pendiente','aprobado','rechazado') DEFAULT 'pendiente',
  `fecha_solicitud` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion_precios`
--

CREATE TABLE `configuracion_precios` (
  `id` int(11) NOT NULL,
  `precio_min` decimal(10,2) NOT NULL,
  `precio_max` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_externos`
--

CREATE TABLE `usuarios_externos` (
  `id` int(11) NOT NULL,
  `cedula` varchar(10) NOT NULL,
  `nombres` varchar(50) NOT NULL,
  `apellidos` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `tipo_usuario` enum('comprador','vendedor') NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios_externos`
--

INSERT INTO `usuarios_externos` (`id`, `cedula`, `nombres`, `apellidos`, `email`, `password`, `tipo_usuario`, `fecha_registro`) VALUES
(10, '1804936589', 'Edgar Rolando', 'Punina Pilamunga', 'edgarpunina20@gmail.com', '$2y$10$sKh3M2JFaHZGipCYxD2l8e61TMMpTemTxcgHV2Ga4VhJUc2MVq6S6', 'vendedor', '2025-03-14 01:49:36'),
(11, '1803697174', 'Juan Pablo', 'Perez Parra', 'juanparra@gmail.com', '$2y$10$XZnXD1/13K5j1Tp73tUzPOlYzdmw.lzANyrcvxC/SXHkylKl7cHza', 'comprador', '2025-03-14 01:50:29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_internos`
--

CREATE TABLE `usuarios_internos` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios_internos`
--

INSERT INTO `usuarios_internos` (`id`, `usuario`, `password`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cambios_precios`
--
ALTER TABLE `cambios_precios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `configuracion_precios`
--
ALTER TABLE `configuracion_precios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios_externos`
--
ALTER TABLE `usuarios_externos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `usuarios_internos`
--
ALTER TABLE `usuarios_internos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cambios_precios`
--
ALTER TABLE `cambios_precios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `configuracion_precios`
--
ALTER TABLE `configuracion_precios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios_externos`
--
ALTER TABLE `usuarios_externos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `usuarios_internos`
--
ALTER TABLE `usuarios_internos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
