-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 29-05-2018 a las 15:09:44
-- Versión del servidor: 5.6.39-83.1-log
-- Versión de PHP: 5.5.9-1ubuntu4.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `giphy`
--
CREATE DATABASE IF NOT EXISTS `giphy` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `giphy`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(50) NOT NULL,
  `category_desc` text NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gifs`
--

CREATE TABLE IF NOT EXISTS `gifs` (
  `gif_id` int(11) NOT NULL AUTO_INCREMENT,
  `gif_name` varchar(50) NOT NULL,
  `gif_link` varchar(255) NOT NULL,
  PRIMARY KEY (`gif_link`),
  KEY `gif_id` (`gif_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gifs_categories`
--

CREATE TABLE IF NOT EXISTS `gifs_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gif_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `gif_id_2` (`gif_id`,`category_id`),
  KEY `gif_id` (`gif_id`,`category_id`),
  KEY `gc.gif_id` (`gif_id`),
  KEY `gc.category_id` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `gifs_categories`
--
ALTER TABLE `gifs_categories`
  ADD CONSTRAINT `gifs_categories_ibfk_1` FOREIGN KEY (`gif_id`) REFERENCES `gifs` (`gif_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `gifs_categories_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
