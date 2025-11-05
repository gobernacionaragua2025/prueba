-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-06-2025 a las 16:45:24
-- Versión del servidor: 10.4.11-MariaDB
-- Versión de PHP: 7.4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `correspondencia`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `UserName` varchar(100) DEFAULT NULL,
  `Password` varchar(100) DEFAULT NULL,
  `updationDate` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `admin`
--

INSERT INTO `admin` (`id`, `UserName`, `Password`, `updationDate`) VALUES
(1, 'administrador@gmail.com', '0192023a7bbd73250516f069df18b500', '2022-09-04 10:30:57'),
(2, 'adelsomermejogb@gmail.com', '202cb962ac59075b964b07152d234b70', NULL),
(3, 'correspondencia@gmail.com', '088e4d2f90223b08c9029bdf4cc9762a', '2025-04-11 14:31:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contador`
--

CREATE TABLE `contador` (
  `id` int(11) NOT NULL,
  `tipo` varchar(20) DEFAULT NULL,
  `cantidad` int(11) DEFAULT 0,
  `fecha` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `contador`
--

INSERT INTO `contador` (`id`, `tipo`, `cantidad`, `fecha`) VALUES
(1, 'diario', 0, '2025-03-11'),
(2, 'mensual', 0, '2025-03-11'),
(3, 'anual', 0, '2025-03-11'),
(4, 'diario', 0, '2025-03-11'),
(5, 'mensual', 0, '2025-03-11'),
(6, 'anual', 0, '2025-03-11'),
(7, 'diario', 0, '2025-03-11'),
(8, 'mensual', 0, '2025-03-11'),
(9, 'anual', 0, '2025-03-11');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_correspondencia_entradas`
--

CREATE TABLE `registro_correspondencia_entradas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `departamento` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `asunto` varchar(255) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `respuesta` varchar(255) NOT NULL,
  `fecha_entrada` datetime DEFAULT current_timestamp(),
  `archivo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `registro_correspondencia_entradas`
--

INSERT INTO `registro_correspondencia_entradas` (`id`, `nombre`, `apellido`, `departamento`, `descripcion`, `asunto`, `tipo`, `respuesta`, `fecha_entrada`, `archivo`) VALUES
(1, 'CARLOS HERNANDEZ', 'DIRECTOT GENRAL', 'DIR. GRAL. DE PLANIFICACIÓN, PRESUPUESTO Y OPTIMIZACIÓN ORGANIZACIONAL', 'FFDF', 'GGGG', '', 'revisar', '2025-05-07 10:33:28', 'uploads/registro_bienes.xls');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_correspondencia_salidas`
--

CREATE TABLE `registro_correspondencia_salidas` (
  `id` int(11) NOT NULL,
  `departamento` varchar(255) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `destinatario` varchar(255) DEFAULT NULL,
  `correlativo` varchar(255) DEFAULT NULL,
  `fecha_salida` datetime DEFAULT NULL,
  `archivo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `registro_correspondencia_salidas`
--

INSERT INTO `registro_correspondencia_salidas` (`id`, `departamento`, `descripcion`, `destinatario`, `correlativo`, `fecha_salida`, `archivo`) VALUES
(1, 'DIR. GRAL. DE ADMINISTRACIÓN', '.KMHKJJB', 'JGJGKJ', '0001', '2025-05-07 00:00:00', 'uploads/x-Photoroom (1).png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblusuarios`
--

CREATE TABLE `tblusuarios` (
  `id` int(11) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `cedula` varchar(100) DEFAULT NULL,
  `indicador` varchar(100) NOT NULL,
  `negocio` varchar(100) NOT NULL,
  `Creationdate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tblusuarios`
--

INSERT INTO `tblusuarios` (`id`, `nombres`, `cedula`, `indicador`, `negocio`, `Creationdate`, `UpdationDate`) VALUES
(0, '123', '123', '123', '123', '2025-06-20 14:09:53', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `contador`
--
ALTER TABLE `contador`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `registro_correspondencia_entradas`
--
ALTER TABLE `registro_correspondencia_entradas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `registro_correspondencia_salidas`
--
ALTER TABLE `registro_correspondencia_salidas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `contador`
--
ALTER TABLE `contador`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `registro_correspondencia_entradas`
--
ALTER TABLE `registro_correspondencia_entradas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `registro_correspondencia_salidas`
--
ALTER TABLE `registro_correspondencia_salidas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
