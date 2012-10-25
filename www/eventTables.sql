-- phpMyAdmin SQL Dump
-- version 3.4.11.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 25, 2012 at 08:18 AM
-- Server version: 5.1.65
-- PHP Version: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `lumisens_nl`
--

-- --------------------------------------------------------

--
-- Table structure for table `novalabsDoorEvents`
--

CREATE TABLE IF NOT EXISTS `novalabsDoorEvents` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `doorValue` tinyint(1) NOT NULL,
  `author` varchar(512) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `timestamp` (`timestamp`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=116 ;

-- --------------------------------------------------------

--
-- Table structure for table `novalabsThermostatEvents`
--

CREATE TABLE IF NOT EXISTS `novalabsThermostatEvents` (
  `author` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `temp` float NOT NULL DEFAULT '0',
  `hold` tinyint(3) unsigned DEFAULT NULL,
  `tmode` tinyint(3) unsigned DEFAULT NULL,
  `fmode` tinyint(3) unsigned DEFAULT NULL,
  `override` tinyint(3) unsigned DEFAULT NULL,
  `t_heat` float unsigned DEFAULT NULL,
  `t_cool` float unsigned DEFAULT NULL,
  `a_cool` float unsigned DEFAULT NULL,
  `a_heat` float unsigned DEFAULT NULL,
  `tstate` tinyint(3) unsigned DEFAULT NULL,
  `fstate` tinyint(3) unsigned DEFAULT NULL,
  `t_type_post` tinyint(3) unsigned DEFAULT NULL,
  `day` tinyint(3) unsigned DEFAULT NULL,
  `hour` tinyint(3) unsigned DEFAULT NULL,
  `minute` tinyint(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
