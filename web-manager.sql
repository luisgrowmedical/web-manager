-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 07-05-2026 a las 21:23:28
-- Versión del servidor: 5.7.24
-- Versión de PHP: 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `web_manager`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `api_logs`
--

CREATE TABLE `api_logs` (
  `id` int(11) NOT NULL,
  `site_id` int(11) DEFAULT NULL,
  `action` varchar(100) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `response` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `api_logs`
--

INSERT INTO `api_logs` (`id`, `site_id`, `action`, `status`, `response`, `created_at`) VALUES
(1, 1, 'sync', 'success', '{\"site_name\":\"Dr. Marco Vilchis\",\"site_url\":\"https:\\/\\/urologoencuernavaca.com.mx\",\"pages\":110,\"posts\":6,\"drafts\":12,\"themes\":{\"active\":\"Kadence\",\"total\":5},\"plugins\":{\"active\":20,\"total\":20},\"weight\":\"135.75 MB\",\"images\":196}', '2026-05-06 00:09:18'),
(2, 2, 'sync', 'success', '{\"site_name\":\"Harmonic Face\",\"site_url\":\"https:\\/\\/harmonicfacemx.com\",\"pages\":56,\"posts\":3,\"drafts\":3,\"themes\":{\"active\":\"Kadence\",\"total\":2},\"plugins\":{\"active\":23,\"total\":23},\"weight\":\"146.59 MB\",\"images\":211}', '2026-05-06 00:23:11'),
(3, 3, 'sync', 'success', '{\"site_name\":\"MEDYSUR\",\"site_url\":\"http:\\/\\/medysur.com\",\"pages\":28,\"posts\":0,\"drafts\":1,\"themes\":{\"active\":\"Kadence\",\"total\":4},\"plugins\":{\"active\":21,\"total\":21},\"weight\":\"123.25 MB\",\"images\":116}', '2026-05-06 00:27:59'),
(4, 4, 'sync', 'success', '{\"site_name\":\"Grupo M\\u00e9dico Alma\",\"site_url\":\"https:\\/\\/grupomedicoalma.com\",\"pages\":89,\"posts\":0,\"drafts\":17,\"themes\":{\"active\":\"Kadence\",\"total\":3},\"plugins\":{\"active\":20,\"total\":20},\"weight\":\"116.28 MB\",\"images\":235}', '2026-05-06 00:34:56'),
(5, 5, 'sync', 'success', '{\"site_name\":\"Dra. Claudia Loredo\",\"site_url\":\"https:\\/\\/draclaudialoredo.com\",\"pages\":11,\"posts\":0,\"drafts\":0,\"themes\":{\"active\":\"Kadence\",\"total\":2},\"plugins\":{\"active\":22,\"total\":22},\"weight\":\"104.58 MB\",\"images\":53}', '2026-05-06 00:37:23'),
(6, 6, 'sync', 'success', '{\"site_name\":\"Eyeklinik\",\"site_url\":\"https:\\/\\/eyeklinik.com\",\"pages\":157,\"posts\":0,\"drafts\":107,\"themes\":{\"active\":\"Kadence\",\"total\":2},\"plugins\":{\"active\":2,\"total\":26},\"weight\":\"671.56 MB\",\"images\":719}', '2026-05-06 00:40:41'),
(7, 7, 'sync', 'success', '{\"site_name\":\"Dr. Juan Manuel Pool Herrera\",\"site_url\":\"https:\\/\\/cirujanoenmerida.com\",\"pages\":140,\"posts\":0,\"drafts\":24,\"themes\":{\"active\":\"Kadence\",\"total\":5},\"plugins\":{\"active\":17,\"total\":17},\"weight\":\"172.69 MB\",\"images\":233}', '2026-05-06 00:47:39'),
(8, 8, 'sync', 'success', '{\"site_name\":\"Dr. Enrique Vargas\",\"site_url\":\"https:\\/\\/oftalmologoentoluca.com.mx\",\"pages\":136,\"posts\":0,\"drafts\":14,\"themes\":{\"active\":\"Kadence\",\"total\":3},\"plugins\":{\"active\":21,\"total\":21},\"weight\":\"209.85 MB\",\"images\":251}', '2026-05-06 00:58:08'),
(9, 9, 'sync', 'success', '{\"site_name\":\"Dr. Antonio El\\u00ed God\\u00ednez\",\"site_url\":\"https:\\/\\/artrocare.com.mx\",\"pages\":173,\"posts\":0,\"drafts\":12,\"themes\":{\"active\":\"Kadence\",\"total\":5},\"plugins\":{\"active\":16,\"total\":16},\"weight\":\"252.68 MB\",\"images\":230}', '2026-05-06 01:01:01'),
(10, 10, 'sync', 'success', '{\"site_name\":\"Dra. Karen Cornejo\",\"site_url\":\"https:\\/\\/medicinadeldolorguadalajara.com\",\"pages\":133,\"posts\":0,\"drafts\":5,\"themes\":{\"active\":\"Kadence\",\"total\":2},\"plugins\":{\"active\":19,\"total\":19},\"weight\":\"114.75 MB\",\"images\":130}', '2026-05-06 01:08:03'),
(11, 11, 'sync', 'success', '{\"site_name\":\"Dr. David Escamilla\",\"site_url\":\"https:\\/\\/drdavidescamilla.com\",\"pages\":160,\"posts\":0,\"drafts\":4,\"themes\":{\"active\":\"Kadence\",\"total\":3},\"plugins\":{\"active\":16,\"total\":16},\"weight\":\"132.88 MB\",\"images\":168}', '2026-05-06 01:14:21'),
(12, 12, 'sync', 'success', '{\"site_name\":\"Neurocirujano\",\"site_url\":\"https:\\/\\/neurocirugiamonterrey.com\",\"pages\":136,\"posts\":0,\"drafts\":3,\"themes\":{\"active\":\"Kadence\",\"total\":5},\"plugins\":{\"active\":19,\"total\":19},\"weight\":\"155.38 MB\",\"images\":181}', '2026-05-06 01:17:34'),
(13, 14, 'sync', 'success', '{\"site_name\":\"Dr. Jorge Arag\\u00f3n Aguilar\",\"site_url\":\"https:\\/\\/drjorgearagontrauma.com\",\"pages\":164,\"posts\":0,\"drafts\":2,\"themes\":{\"active\":\"Kadence\",\"total\":3},\"plugins\":{\"active\":23,\"total\":22},\"weight\":\"146.4 MB\",\"images\":222}', '2026-05-06 01:26:12'),
(14, 13, 'sync', 'success', '{\"site_name\":\"Dr. Lauro Cruz\",\"site_url\":\"https:\\/\\/drlaurocruz.com.mx\",\"pages\":173,\"posts\":0,\"drafts\":9,\"themes\":{\"active\":\"Kadence\",\"total\":5},\"plugins\":{\"active\":19,\"total\":20},\"weight\":\"213.91 MB\",\"images\":256}', '2026-05-06 01:26:21'),
(15, 15, 'sync', 'success', '{\"site_name\":\"Dr. Ubaldo Pimentel\",\"site_url\":\"https:\\/\\/cirujanoubaldopimentel.com\",\"pages\":128,\"posts\":0,\"drafts\":7,\"themes\":{\"active\":\"Kadence\",\"total\":3},\"plugins\":{\"active\":19,\"total\":19},\"weight\":\"159.11 MB\",\"images\":242}', '2026-05-06 01:29:42'),
(16, 9, 'sync', 'success', '{\"site_name\":\"Dr. Antonio El\\u00ed God\\u00ednez\",\"site_url\":\"https:\\/\\/artrocare.com.mx\",\"pages\":173,\"posts\":0,\"drafts\":12,\"themes\":{\"active\":\"Kadence\",\"total\":5},\"plugins\":{\"active\":16,\"total\":16},\"weight\":\"252.68 MB\",\"images\":230}', '2026-05-06 02:26:02'),
(17, 10, 'sync', 'success', '{\"site_name\":\"Dra. Karen Cornejo\",\"site_url\":\"https:\\/\\/medicinadeldolorguadalajara.com\",\"pages\":133,\"posts\":0,\"drafts\":5,\"themes\":{\"active\":\"Kadence\",\"total\":2},\"plugins\":{\"active\":19,\"total\":19},\"weight\":\"114.75 MB\",\"images\":130}', '2026-05-06 03:09:11'),
(18, 9, 'sync', 'error', '{\"code\":\"rest_no_route\",\"message\":\"No se ha encontrado ninguna ruta que coincida con la URL y el m\\u00e9todo de la solicitud.\",\"data\":{\"status\":404}}', '2026-05-06 03:09:49'),
(19, 8, 'sync', 'success', '{\"site_name\":\"Dr. Enrique Vargas\",\"site_url\":\"https:\\/\\/oftalmologoentoluca.com.mx\",\"pages\":136,\"posts\":0,\"drafts\":14,\"themes\":{\"active\":\"Kadence\",\"total\":3},\"plugins\":{\"active\":21,\"total\":21},\"weight\":\"209.85 MB\",\"images\":251}', '2026-05-06 03:10:02'),
(20, 9, 'sync', 'success', '{\"site_name\":\"Dr. Antonio El\\u00ed God\\u00ednez\",\"site_url\":\"https:\\/\\/artrocare.com.mx\",\"pages\":173,\"posts\":0,\"drafts\":12,\"themes\":{\"active\":\"Kadence\",\"total\":5},\"plugins\":{\"active\":16,\"total\":16},\"weight\":\"206.71 MB\",\"images\":230}', '2026-05-06 03:13:30'),
(21, 9, 'sync', 'success', '{\"site_name\":\"Dr. Antonio El\\u00ed God\\u00ednez\",\"site_url\":\"https:\\/\\/artrocare.com.mx\",\"pages\":173,\"posts\":0,\"drafts\":12,\"themes\":{\"active\":\"Kadence\",\"total\":5},\"plugins\":{\"active\":16,\"total\":16},\"weight\":\"206.71 MB\",\"images\":230}', '2026-05-06 03:13:49'),
(22, 9, 'sync', 'success', '{\"site_name\":\"Dr. Antonio El\\u00ed God\\u00ednez\",\"site_url\":\"https:\\/\\/artrocare.com.mx\",\"pages\":173,\"posts\":0,\"drafts\":12,\"themes\":{\"active\":\"Kadence\",\"total\":5},\"plugins\":{\"active\":16,\"total\":16},\"weight\":\"206.71 MB\",\"images\":230}', '2026-05-06 03:17:03'),
(23, 11, 'sync', 'success', '{\"site_name\":\"Dr. David Escamilla\",\"site_url\":\"https:\\/\\/drdavidescamilla.com\",\"pages\":160,\"posts\":0,\"drafts\":4,\"themes\":{\"active\":\"Kadence\",\"total\":3},\"plugins\":{\"active\":16,\"total\":16},\"weight\":\"132.88 MB\",\"images\":168}', '2026-05-06 03:20:19'),
(24, 9, 'sync', 'success', '{\"site_name\":\"Dr. Antonio El\\u00ed God\\u00ednez\",\"site_url\":\"https:\\/\\/artrocare.com.mx\",\"pages\":173,\"posts\":0,\"drafts\":12,\"themes\":{\"active\":\"Kadence\",\"total\":5},\"plugins\":{\"active\":16,\"total\":16},\"weight\":\"206.71 MB\",\"images\":230}', '2026-05-06 03:24:50'),
(25, 9, 'sync', 'error', '{\"code\":\"rest_no_route\",\"message\":\"No se ha encontrado ninguna ruta que coincida con la URL y el m\\u00e9todo de la solicitud.\",\"data\":{\"status\":404}}', '2026-05-06 03:25:51'),
(26, 9, 'sync', 'success', '{\"site_name\":\"Dr. Antonio El\\u00ed God\\u00ednez\",\"site_url\":\"https:\\/\\/artrocare.com.mx\",\"pages\":173,\"posts\":0,\"drafts\":12,\"themes\":{\"active\":\"Kadence\",\"total\":5},\"plugins\":{\"active\":16,\"total\":16},\"weight\":\"211.05 MB\",\"images\":230}', '2026-05-06 16:58:02'),
(27, 9, 'sync', 'success', '{\"site_name\":\"Dr. Antonio El\\u00ed God\\u00ednez\",\"site_url\":\"https:\\/\\/artrocare.com.mx\",\"pages\":173,\"posts\":0,\"drafts\":12,\"themes\":{\"active\":\"Kadence\",\"total\":5},\"plugins\":{\"active\":16,\"total\":16},\"weight\":\"211.05 MB\",\"images\":230}', '2026-05-06 16:58:13'),
(28, 9, 'sync', 'success', '{\"site_name\":\"Dr. Antonio El\\u00ed God\\u00ednez\",\"site_url\":\"https:\\/\\/artrocare.com.mx\",\"pages\":173,\"posts\":0,\"drafts\":12,\"themes\":{\"active\":\"Kadence\",\"total\":5},\"plugins\":{\"active\":16,\"total\":16},\"weight\":\"206.72 MB\",\"images\":230,\"updates\":14}', '2026-05-07 00:59:41'),
(29, 9, 'sync', 'success', '{\"site_name\":\"Dr. Antonio El\\u00ed God\\u00ednez\",\"site_url\":\"https:\\/\\/artrocare.com.mx\",\"pages\":173,\"posts\":0,\"drafts\":12,\"themes\":{\"active\":\"Kadence\",\"total\":5},\"plugins\":{\"active\":16,\"total\":16},\"weight\":\"206.72 MB\",\"images\":230,\"updates\":{\"total\":14,\"plugins\":9,\"themes\":5,\"core\":0}}', '2026-05-07 03:59:45'),
(30, 9, 'sync', 'error', '{\"code\":\"rest_no_route\",\"message\":\"No se ha encontrado ninguna ruta que coincida con la URL y el m\\u00e9todo de la solicitud.\",\"data\":{\"status\":404}}', '2026-05-07 04:05:23'),
(31, 9, 'sync', 'success', '{\"site_name\":\"Dr. Antonio El\\u00ed God\\u00ednez\",\"site_url\":\"https:\\/\\/artrocare.com.mx\",\"pages\":173,\"posts\":0,\"drafts\":12,\"themes\":{\"active\":\"Kadence\",\"total\":5},\"plugins\":{\"active\":16,\"total\":16},\"weight\":\"207.47 MB\",\"images\":230,\"updates\":{\"total\":14,\"plugins\":9,\"themes\":5,\"core\":0}}', '2026-05-07 04:05:55'),
(32, 11, 'sync', 'error', '{\"code\":\"rest_no_route\",\"message\":\"No se ha encontrado ninguna ruta que coincida con la URL y el m\\u00e9todo de la solicitud.\",\"data\":{\"status\":404}}', '2026-05-07 04:17:18'),
(33, 11, 'sync', 'error', '{\"code\":\"rest_no_route\",\"message\":\"No se ha encontrado ninguna ruta que coincida con la URL y el m\\u00e9todo de la solicitud.\",\"data\":{\"status\":404}}', '2026-05-07 04:17:23'),
(34, 11, 'sync', 'success', '{\"site_name\":\"Dr. David Escamilla\",\"site_url\":\"https:\\/\\/drdavidescamilla.com\",\"pages\":160,\"posts\":0,\"drafts\":4,\"themes\":{\"active\":\"Kadence\",\"total\":3},\"plugins\":{\"active\":16,\"total\":16},\"weight\":\"132.89 MB\",\"images\":168,\"updates\":{\"total\":12,\"plugins\":9,\"themes\":3,\"core\":0}}', '2026-05-07 04:17:35'),
(35, 8, 'sync', 'success', '{\"site_name\":\"Dr. Enrique Vargas\",\"site_url\":\"https:\\/\\/oftalmologoentoluca.com.mx\",\"pages\":140,\"posts\":0,\"drafts\":14,\"themes\":{\"active\":\"Kadence\",\"total\":3},\"plugins\":{\"active\":21,\"total\":21},\"weight\":\"210.3 MB\",\"images\":255,\"updates\":{\"total\":14,\"plugins\":11,\"themes\":3,\"core\":0}}', '2026-05-07 04:17:41'),
(36, 14, 'sync', 'success', '{\"site_name\":\"Dr. Jorge Arag\\u00f3n Aguilar\",\"site_url\":\"https:\\/\\/drjorgearagontrauma.com\",\"pages\":164,\"posts\":0,\"drafts\":2,\"themes\":{\"active\":\"Kadence\",\"total\":3},\"plugins\":{\"active\":22,\"total\":22},\"weight\":\"146.4 MB\",\"images\":222,\"updates\":{\"total\":12,\"plugins\":9,\"themes\":3,\"core\":0}}', '2026-05-07 04:17:57'),
(37, 12, 'sync', 'success', '{\"site_name\":\"Neurocirujano\",\"site_url\":\"https:\\/\\/neurocirugiamonterrey.com\",\"pages\":136,\"posts\":0,\"drafts\":3,\"themes\":{\"active\":\"Kadence\",\"total\":5},\"plugins\":{\"active\":19,\"total\":19},\"weight\":\"155.38 MB\",\"images\":181,\"updates\":{\"total\":14,\"plugins\":9,\"themes\":5,\"core\":0}}', '2026-05-07 04:18:04'),
(38, 12, 'sync', 'success', '{\"site_name\":\"Neurocirujano\",\"site_url\":\"https:\\/\\/neurocirugiamonterrey.com\",\"pages\":136,\"posts\":0,\"drafts\":3,\"themes\":{\"active\":\"Kadence\",\"total\":5},\"plugins\":{\"active\":19,\"total\":19},\"weight\":\"155.38 MB\",\"images\":181,\"updates\":{\"total\":14,\"plugins\":9,\"themes\":5,\"core\":0}}', '2026-05-07 04:18:13'),
(39, 7, 'sync', 'success', '{\"site_name\":\"Dr. Juan Manuel Pool Herrera\",\"site_url\":\"https:\\/\\/cirujanoenmerida.com\",\"pages\":140,\"posts\":0,\"drafts\":24,\"themes\":{\"active\":\"Kadence\",\"total\":5},\"plugins\":{\"active\":17,\"total\":17},\"weight\":\"172.69 MB\",\"images\":233,\"updates\":{\"total\":14,\"plugins\":9,\"themes\":5,\"core\":0}}', '2026-05-07 04:19:19'),
(40, 13, 'sync', 'success', '{\"site_name\":\"Dr. Lauro Cruz\",\"site_url\":\"https:\\/\\/drlaurocruz.com.mx\",\"pages\":173,\"posts\":0,\"drafts\":9,\"themes\":{\"active\":\"Kadence\",\"total\":5},\"plugins\":{\"active\":19,\"total\":20},\"weight\":\"213.91 MB\",\"images\":256,\"updates\":{\"total\":9,\"plugins\":4,\"themes\":5,\"core\":0}}', '2026-05-07 04:19:27'),
(41, 1, 'sync', 'success', '{\"site_name\":\"Dr. Marco Vilchis\",\"site_url\":\"https:\\/\\/urologoencuernavaca.com.mx\",\"pages\":110,\"posts\":6,\"drafts\":12,\"themes\":{\"active\":\"Kadence\",\"total\":5},\"plugins\":{\"active\":20,\"total\":20},\"weight\":\"135.76 MB\",\"images\":196,\"updates\":{\"total\":15,\"plugins\":10,\"themes\":5,\"core\":0}}', '2026-05-07 04:19:33'),
(42, 15, 'sync', 'success', '{\"site_name\":\"Dr. Ubaldo Pimentel\",\"site_url\":\"https:\\/\\/cirujanoubaldopimentel.com\",\"pages\":128,\"posts\":0,\"drafts\":7,\"themes\":{\"active\":\"Kadence\",\"total\":3},\"plugins\":{\"active\":19,\"total\":19},\"weight\":\"159.11 MB\",\"images\":242,\"updates\":{\"total\":13,\"plugins\":10,\"themes\":3,\"core\":0}}', '2026-05-07 04:19:38'),
(43, 5, 'sync', 'success', '{\"site_name\":\"Dra. Claudia Loredo\",\"site_url\":\"https:\\/\\/draclaudialoredo.com\",\"pages\":11,\"posts\":0,\"drafts\":0,\"themes\":{\"active\":\"Kadence\",\"total\":2},\"plugins\":{\"active\":22,\"total\":22},\"weight\":\"104.58 MB\",\"images\":53,\"updates\":{\"total\":6,\"plugins\":6,\"themes\":0,\"core\":0}}', '2026-05-07 04:19:42'),
(44, 10, 'sync', 'success', '{\"site_name\":\"Dra. Karen Cornejo\",\"site_url\":\"https:\\/\\/medicinadeldolorguadalajara.com\",\"pages\":133,\"posts\":0,\"drafts\":5,\"themes\":{\"active\":\"Kadence\",\"total\":2},\"plugins\":{\"active\":19,\"total\":19},\"weight\":\"114.75 MB\",\"images\":130,\"updates\":{\"total\":11,\"plugins\":10,\"themes\":1,\"core\":0}}', '2026-05-07 04:24:31'),
(45, 6, 'sync', 'success', '{\"site_name\":\"Eyeklinik\",\"site_url\":\"https:\\/\\/eyeklinik.com\",\"pages\":157,\"posts\":0,\"drafts\":107,\"themes\":{\"active\":\"Kadence\",\"total\":2},\"plugins\":{\"active\":2,\"total\":26},\"weight\":\"671.56 MB\",\"images\":719,\"updates\":{\"total\":7,\"plugins\":7,\"themes\":0,\"core\":0}}', '2026-05-07 04:24:40'),
(46, 4, 'sync', 'success', '{\"site_name\":\"Grupo M\\u00e9dico Alma\",\"site_url\":\"https:\\/\\/grupomedicoalma.com\",\"pages\":89,\"posts\":0,\"drafts\":17,\"themes\":{\"active\":\"Kadence\",\"total\":3},\"plugins\":{\"active\":20,\"total\":20},\"weight\":\"116.28 MB\",\"images\":235,\"updates\":{\"total\":12,\"plugins\":9,\"themes\":3,\"core\":0}}', '2026-05-07 04:24:53'),
(47, 2, 'sync', 'success', '{\"site_name\":\"Harmonic Face\",\"site_url\":\"https:\\/\\/harmonicfacemx.com\",\"pages\":56,\"posts\":3,\"drafts\":3,\"themes\":{\"active\":\"Kadence\",\"total\":2},\"plugins\":{\"active\":23,\"total\":23},\"weight\":\"146.59 MB\",\"images\":211,\"updates\":{\"total\":13,\"plugins\":11,\"themes\":2,\"core\":0}}', '2026-05-07 04:25:00'),
(48, 3, 'sync', 'success', '{\"site_name\":\"MEDYSUR\",\"site_url\":\"http:\\/\\/medysur.com\",\"pages\":28,\"posts\":0,\"drafts\":1,\"themes\":{\"active\":\"Kadence\",\"total\":4},\"plugins\":{\"active\":21,\"total\":21},\"weight\":\"123.25 MB\",\"images\":116,\"updates\":{\"total\":14,\"plugins\":11,\"themes\":3,\"core\":0}}', '2026-05-07 04:25:08'),
(49, 10, 'sync', 'success', '{\"site_name\":\"Dra. Karen Cornejo\",\"site_url\":\"https:\\/\\/medicinadeldolorguadalajara.com\",\"pages\":133,\"posts\":0,\"drafts\":5,\"themes\":{\"active\":\"Kadence\",\"total\":2},\"plugins\":{\"active\":19,\"total\":19},\"weight\":\"115.18 MB\",\"images\":130,\"updates\":{\"total\":0,\"plugins\":0,\"themes\":0,\"core\":0}}', '2026-05-07 05:07:16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(1, 'admin'),
(2, 'collaborator'),
(3, 'viewer');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sites`
--

CREATE TABLE `sites` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `api_key` varchar(255) NOT NULL,
  `last_sync` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `country` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `specialty` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `sites`
--

INSERT INTO `sites` (`id`, `name`, `url`, `api_key`, `last_sync`, `created_at`, `country`, `state`, `city`, `specialty`) VALUES
(1, 'Dr. Marco Vilchis', 'https://urologoencuernavaca.com.mx/', 'c672be5f626658da0ae394b35c8cd0668a5f525ec0446442cd45b846b625f67d', '2026-05-07 04:19:33', '2026-05-06 00:09:12', 'Mexico', 'Morelos', 'Cuernavaca', 'Urology'),
(2, 'Harmonic Face', 'https://harmonicfacemx.com/', '5ea982dc048eb77d52dd2909c2262efd9eaef5dd82059a376055cbc5c5fc9f83', '2026-05-07 04:25:00', '2026-05-06 00:23:07', 'Mexico', 'México', 'Atizapán de Zaragoza', 'Dermatology'),
(3, 'Medysur', 'http://medysur.com/', 'e576312c90759a78312bf4a0866b5a0635beafa230af1e75148c49d94b04ea4c', '2026-05-07 04:25:08', '2026-05-06 00:27:54', 'Mexico', 'Querétaro', 'Querétaro', 'Radiology'),
(4, 'Grupo Médico Alma', 'https://grupomedicoalma.com/', '10958d3598a4c7295405348679b88ef94246865404c4efba3c3c3c088d332ca2', '2026-05-07 04:24:53', '2026-05-06 00:34:50', 'Ecuador', 'Pichincha', 'Quito', 'Obstetrics & Gynecology'),
(5, 'Dra. Claudia Loredo', 'https://draclaudialoredo.com/', 'befe8ca8e2df8296b9623a6536f6588864989d928bcc75396ec6ee666089942c', '2026-05-07 04:19:42', '2026-05-06 00:37:20', 'Mexico', 'Guanajuato', 'León', 'Rheumatology'),
(6, 'Eyeklinik', 'https://eyeklinik.com/', '53db068f0953b1236a93cd7b75a8c764f48b50c58e6573c52f83b3115677cbd2', '2026-05-07 04:24:40', '2026-05-06 00:40:37', 'Mexico', 'Nuevo León', 'Monterrey', 'Ophthalmology'),
(7, 'Dr. Juan Manuel Pool', 'https://cirujanoenmerida.com/', 'f390ef2ac353c142abb06030b6012b1c43969060a338a9a5df4bf8b2ba91641d', '2026-05-07 04:19:19', '2026-05-06 00:47:32', 'Mexico', 'Yucatán', 'Mérida', 'General Surgery'),
(8, 'Dr. Enrique Vargas', 'https://oftalmologoentoluca.com.mx/', '801856ea196a7719cf72717e3d5aa14432da3c13dc61c6bca3c2faa66ad049be', '2026-05-07 04:17:41', '2026-05-06 00:58:03', 'Mexico', 'México', 'Toluca', 'Ophthalmology'),
(9, 'Dr. Antonio Elí Godínez', 'https://artrocare.com.mx/', '9a71b6ef3beffe0a83eaac7264b6e4b92406a3f6676bc894e2cc46e907e8ca9c', '2026-05-07 04:05:55', '2026-05-06 01:00:58', 'Mexico', 'México', 'Toluca', 'Traumatology'),
(10, 'Dra. Karen Cornejo', 'https://medicinadeldolorguadalajara.com', '87265b7fac4554a3aba5356e047931011bfc3c121bc4cdd7519d024066ac9c06', '2026-05-07 05:07:16', '2026-05-06 01:07:09', 'Mexico', 'Jalisco', 'Guadalajara', 'General Medicine'),
(11, 'Dr. David Escamilla', 'https://drdavidescamilla.com', 'b8af9d37cb1c07b998173f9e4304050b18828f7a27622a51c165d7850f8f6595', '2026-05-07 04:17:35', '2026-05-06 01:14:18', 'Mexico', 'CDMX', 'Tlalpan', 'Nephrology'),
(12, 'Dr. José María García', 'https://neurocirugiamonterrey.com/', '3769f633e191bb1773544de00a8d4ffbff99f2b68b9e5ad81a5e9083c27ff934', '2026-05-07 04:18:13', '2026-05-06 01:17:23', 'Mexico', 'Nuevo León', 'Monterrey', 'Neurology'),
(13, 'Dr. Lauro Cruz', 'https://drlaurocruz.com.mx/', '18585942885f79cef98ad49dcdc63d282f1569238510bf57c9addeb85bd03e9b', '2026-05-07 04:19:27', '2026-05-06 01:21:56', 'Mexico', 'Hidalgo', 'Pachuca', 'Oncology'),
(14, 'Dr. Jorge Aragón', 'https://drjorgearagontrauma.com/', '1b3066c1ccdbdfe1c4b33eb5ec8796942e9bcb0658f3c14638ce3748d59fd0ac', '2026-05-07 04:17:57', '2026-05-06 01:26:09', 'Mexico', 'México', 'Cuautitlán Izcalli', 'Traumatology'),
(15, 'Dr. Ubaldo Pimentel', 'https://cirujanoubaldopimentel.com/', '2058bbb89eaa167782f19525764b8c01a5f96839035433a44c177a5761fdef21', '2026-05-07 04:19:38', '2026-05-06 01:29:37', 'Mexico', 'Sonora', 'Ciudad Obregón', 'General Surgery');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `site_metrics`
--

CREATE TABLE `site_metrics` (
  `id` int(11) NOT NULL,
  `site_id` int(11) DEFAULT NULL,
  `pages_count` int(11) DEFAULT '0',
  `posts_count` int(11) DEFAULT '0',
  `drafts_count` int(11) DEFAULT '0',
  `active_theme` varchar(100) DEFAULT NULL,
  `themes_count` int(11) DEFAULT '0',
  `active_plugins_count` int(11) DEFAULT '0',
  `total_plugins_count` int(11) DEFAULT '0',
  `site_weight` varchar(50) DEFAULT NULL,
  `images_count` int(11) DEFAULT '0',
  `sync_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updates_count` int(11) DEFAULT '0',
  `plugins_data` longtext,
  `themes_data` longtext,
  `pending_updates_data` longtext,
  `plugin_updates_count` int(11) DEFAULT '0',
  `theme_updates_count` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `site_metrics`
--

INSERT INTO `site_metrics` (`id`, `site_id`, `pages_count`, `posts_count`, `drafts_count`, `active_theme`, `themes_count`, `active_plugins_count`, `total_plugins_count`, `site_weight`, `images_count`, `sync_date`, `updates_count`, `plugins_data`, `themes_data`, `pending_updates_data`, `plugin_updates_count`, `theme_updates_count`) VALUES
(1, 1, 110, 6, 12, 'Kadence', 5, 20, 20, '135.75 MB', 196, '2026-05-06 00:09:18', 0, NULL, NULL, NULL, 0, 0),
(2, 2, 56, 3, 3, 'Kadence', 2, 23, 23, '146.59 MB', 211, '2026-05-06 00:23:11', 0, NULL, NULL, NULL, 0, 0),
(3, 3, 28, 0, 1, 'Kadence', 4, 21, 21, '123.25 MB', 116, '2026-05-06 00:27:59', 0, NULL, NULL, NULL, 0, 0),
(4, 4, 89, 0, 17, 'Kadence', 3, 20, 20, '116.28 MB', 235, '2026-05-06 00:34:56', 0, NULL, NULL, NULL, 0, 0),
(5, 5, 11, 0, 0, 'Kadence', 2, 22, 22, '104.58 MB', 53, '2026-05-06 00:37:23', 0, NULL, NULL, NULL, 0, 0),
(6, 6, 157, 0, 107, 'Kadence', 2, 2, 26, '671.56 MB', 719, '2026-05-06 00:40:41', 0, NULL, NULL, NULL, 0, 0),
(7, 7, 140, 0, 24, 'Kadence', 5, 17, 17, '172.69 MB', 233, '2026-05-06 00:47:39', 0, NULL, NULL, NULL, 0, 0),
(8, 8, 136, 0, 14, 'Kadence', 3, 21, 21, '209.85 MB', 251, '2026-05-06 00:58:08', 0, NULL, NULL, NULL, 0, 0),
(9, 9, 173, 0, 12, 'Kadence', 5, 16, 16, '252.68 MB', 230, '2026-05-06 01:01:01', 0, NULL, NULL, NULL, 0, 0),
(10, 10, 133, 0, 5, 'Kadence', 2, 19, 19, '114.75 MB', 130, '2026-05-06 01:08:03', 0, NULL, NULL, NULL, 0, 0),
(11, 11, 160, 0, 4, 'Kadence', 3, 16, 16, '132.88 MB', 168, '2026-05-06 01:14:21', 5, NULL, NULL, NULL, 0, 0),
(12, 12, 136, 0, 3, 'Kadence', 5, 19, 19, '155.38 MB', 181, '2026-05-06 01:17:34', 1, NULL, NULL, NULL, 0, 0),
(13, 14, 164, 0, 2, 'Kadence', 3, 23, 22, '146.4 MB', 222, '2026-05-06 01:26:11', 5, NULL, NULL, NULL, 0, 0),
(14, 13, 173, 0, 9, 'Kadence', 5, 19, 20, '213.91 MB', 256, '2026-05-06 01:26:21', 5, NULL, NULL, NULL, 0, 0),
(15, 15, 128, 0, 7, 'Kadence', 3, 19, 19, '159.11 MB', 242, '2026-05-06 01:29:42', 2, NULL, NULL, NULL, 0, 0),
(16, 9, 173, 0, 12, 'Kadence', 5, 16, 16, '252.68 MB', 230, '2026-05-06 02:26:02', 0, NULL, NULL, NULL, 0, 0),
(17, 10, 133, 0, 5, 'Kadence', 2, 19, 19, '114.75 MB', 130, '2026-05-06 03:09:11', 0, NULL, NULL, NULL, 0, 0),
(18, 8, 136, 0, 14, 'Kadence', 3, 21, 21, '209.85 MB', 251, '2026-05-06 03:10:02', 0, NULL, NULL, NULL, 0, 0),
(19, 9, 173, 0, 12, 'Kadence', 5, 16, 16, '206.71 MB', 230, '2026-05-06 03:13:30', 0, NULL, NULL, NULL, 0, 0),
(20, 9, 173, 0, 12, 'Kadence', 5, 16, 16, '206.71 MB', 230, '2026-05-06 03:13:49', 0, NULL, NULL, NULL, 0, 0),
(21, 9, 173, 0, 12, 'Kadence', 5, 16, 16, '206.71 MB', 230, '2026-05-06 03:17:03', 0, NULL, NULL, NULL, 0, 0),
(22, 11, 160, 0, 4, 'Kadence', 3, 16, 16, '132.88 MB', 168, '2026-05-06 03:20:19', 0, NULL, NULL, NULL, 0, 0),
(23, 9, 173, 0, 12, 'Kadence', 5, 16, 16, '206.71 MB', 230, '2026-05-06 03:24:50', 0, NULL, NULL, NULL, 0, 0),
(24, 9, 173, 0, 12, 'Kadence', 5, 16, 16, '211.05 MB', 230, '2026-05-06 16:58:02', 0, NULL, NULL, NULL, 0, 0),
(25, 9, 173, 0, 12, 'Kadence', 5, 16, 16, '211.05 MB', 230, '2026-05-06 16:58:13', 0, NULL, NULL, NULL, 0, 0),
(26, 9, 173, 0, 12, 'Kadence', 5, 16, 16, '206.72 MB', 230, '2026-05-07 00:59:41', 14, NULL, NULL, NULL, 0, 0),
(27, 9, 173, 0, 12, 'Kadence', 5, 16, 16, '206.72 MB', 230, '2026-05-07 03:59:45', 14, NULL, NULL, NULL, 9, 5),
(28, 9, 173, 0, 12, 'Kadence', 5, 16, 16, '207.47 MB', 230, '2026-05-07 04:05:55', 14, NULL, NULL, NULL, 9, 5),
(29, 11, 160, 0, 4, 'Kadence', 3, 16, 16, '132.89 MB', 168, '2026-05-07 04:17:35', 12, NULL, NULL, NULL, 9, 3),
(30, 8, 140, 0, 14, 'Kadence', 3, 21, 21, '210.3 MB', 255, '2026-05-07 04:17:41', 14, NULL, NULL, NULL, 11, 3),
(31, 14, 164, 0, 2, 'Kadence', 3, 22, 22, '146.4 MB', 222, '2026-05-07 04:17:57', 12, NULL, NULL, NULL, 9, 3),
(32, 12, 136, 0, 3, 'Kadence', 5, 19, 19, '155.38 MB', 181, '2026-05-07 04:18:04', 14, NULL, NULL, NULL, 9, 5),
(33, 12, 136, 0, 3, 'Kadence', 5, 19, 19, '155.38 MB', 181, '2026-05-07 04:18:13', 14, NULL, NULL, NULL, 9, 5),
(34, 7, 140, 0, 24, 'Kadence', 5, 17, 17, '172.69 MB', 233, '2026-05-07 04:19:19', 14, NULL, NULL, NULL, 9, 5),
(35, 13, 173, 0, 9, 'Kadence', 5, 19, 20, '213.91 MB', 256, '2026-05-07 04:19:27', 9, NULL, NULL, NULL, 4, 5),
(36, 1, 110, 6, 12, 'Kadence', 5, 20, 20, '135.76 MB', 196, '2026-05-07 04:19:33', 15, NULL, NULL, NULL, 10, 5),
(37, 15, 128, 0, 7, 'Kadence', 3, 19, 19, '159.11 MB', 242, '2026-05-07 04:19:38', 13, NULL, NULL, NULL, 10, 3),
(38, 5, 11, 0, 0, 'Kadence', 2, 22, 22, '104.58 MB', 53, '2026-05-07 04:19:42', 6, NULL, NULL, NULL, 6, 0),
(39, 10, 133, 0, 5, 'Kadence', 2, 19, 19, '114.75 MB', 130, '2026-05-07 04:24:31', 11, NULL, NULL, NULL, 10, 1),
(40, 6, 157, 0, 107, 'Kadence', 2, 2, 26, '671.56 MB', 719, '2026-05-07 04:24:40', 7, NULL, NULL, NULL, 7, 0),
(41, 4, 89, 0, 17, 'Kadence', 3, 20, 20, '116.28 MB', 235, '2026-05-07 04:24:53', 12, NULL, NULL, NULL, 9, 3),
(42, 2, 56, 3, 3, 'Kadence', 2, 23, 23, '146.59 MB', 211, '2026-05-07 04:25:00', 13, NULL, NULL, NULL, 11, 2),
(43, 3, 28, 0, 1, 'Kadence', 4, 21, 21, '123.25 MB', 116, '2026-05-07 04:25:08', 14, NULL, NULL, NULL, 11, 3),
(44, 10, 133, 0, 5, 'Kadence', 2, 19, 19, '115.18 MB', 130, '2026-05-07 05:07:16', 0, NULL, NULL, NULL, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `site_user_access`
--

CREATE TABLE `site_user_access` (
  `user_id` int(11) NOT NULL,
  `site_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `avatar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role_id`, `created_at`, `avatar`) VALUES
(1, 'admin', '$2y$10$rKVjVYTDj8ozv6NTygbW1.E1OqoohhbJ3ssyt.ciXjkikGZWHrqQq', 'admin@example.com', 1, '2026-05-05 23:44:19', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `api_logs`
--
ALTER TABLE `api_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `site_id` (`site_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indices de la tabla `sites`
--
ALTER TABLE `sites`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `site_metrics`
--
ALTER TABLE `site_metrics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `site_id` (`site_id`);

--
-- Indices de la tabla `site_user_access`
--
ALTER TABLE `site_user_access`
  ADD PRIMARY KEY (`user_id`,`site_id`),
  ADD KEY `site_id` (`site_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `api_logs`
--
ALTER TABLE `api_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `sites`
--
ALTER TABLE `sites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `site_metrics`
--
ALTER TABLE `site_metrics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `api_logs`
--
ALTER TABLE `api_logs`
  ADD CONSTRAINT `api_logs_ibfk_1` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `site_metrics`
--
ALTER TABLE `site_metrics`
  ADD CONSTRAINT `site_metrics_ibfk_1` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `site_user_access`
--
ALTER TABLE `site_user_access`
  ADD CONSTRAINT `site_user_access_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `site_user_access_ibfk_2` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
