-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 30-11-2016 a las 11:59:37
-- Versión del servidor: 5.5.53-0+deb8u1
-- Versión de PHP: 5.6.27-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `test`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `varios`
--

CREATE TABLE `varios` (
  `codigo` int(11) NOT NULL,
  `texto` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `varios`
--

INSERT INTO `varios` (`codigo`, `texto`) VALUES
(1, 'probando 1'),
(2, 'probando otro'),
(3, 'y otro massss'),
(4, 'camión'),
(5, 'epa');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `varios`
--
ALTER TABLE `varios`
  ADD PRIMARY KEY (`codigo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `varios`
--
ALTER TABLE `varios`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
