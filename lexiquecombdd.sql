-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Lun 16 Septembre 2013 à 22:11
-- Version du serveur: 5.5.31
-- Version de PHP: 5.4.6-1ubuntu1.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `lexiquecombdd`
--

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `category_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_val` varchar(25) NOT NULL,
  `category_create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `category`
--

INSERT INTO `category` (`category_id`, `category_val`, `category_create_date`) VALUES
(1, 'catégorie', '2013-09-15 06:55:51'),
(2, 'autre', '2013-09-15 06:55:51');

-- --------------------------------------------------------

--
-- Structure de la table `entry`
--

CREATE TABLE IF NOT EXISTS `entry` (
  `entry_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `entry_val` varchar(50) NOT NULL,
  `entry_create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`entry_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Contenu de la table `entry`
--

INSERT INTO `entry` (`entry_id`, `entry_val`, `entry_create_date`) VALUES
(1, 'test', '2013-09-13 17:02:52'),
(2, 'world', '2013-09-14 11:47:01'),
(3, 'hello', '2013-09-14 12:19:19'),
(4, 'yop', '2013-09-14 12:19:50'),
(5, 'yop2', '2013-09-14 12:28:45'),
(6, '&lt;br/&gt;', '2013-09-14 13:06:16'),
(7, 'break;', '2013-09-14 13:06:33'),
(8, 'NewEntry', '2013-09-15 08:05:13'),
(10, 'new', '2013-09-15 10:00:02'),
(11, 'help', '2013-09-15 10:00:44'),
(12, 'tagada', '2013-09-15 10:15:44'),
(13, 'Aaa', '2013-09-15 11:28:52'),
(14, 'Ccc', '2013-09-15 11:28:57'),
(15, 'Ceci est un test', '2013-09-15 17:15:10'),
(16, 'ou une pipe', '2013-09-15 17:15:23');

-- --------------------------------------------------------

--
-- Structure de la table `link`
--

CREATE TABLE IF NOT EXISTS `link` (
  `link_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `link_entry_id` int(11) unsigned NOT NULL,
  `link_from` int(10) unsigned NOT NULL,
  `link_to` int(10) unsigned NOT NULL,
  `link_val` varchar(20) DEFAULT NULL,
  `link_type` int(10) unsigned NOT NULL,
  `link_create_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `link_alert` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`link_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Contenu de la table `link`
--

INSERT INTO `link` (`link_id`, `link_entry_id`, `link_from`, `link_to`, `link_val`, `link_type`, `link_create_date`, `link_alert`) VALUES
(1, 2, 1, 2, 'travail pour', 400, '2013-09-14 14:09:46', 0),
(2, 2, 2, 1, 'doublon ?', 400, '2013-09-14 14:13:31', 0),
(3, 1, 1, 1, 'test link 1 vers 1', 500, '2013-09-14 14:45:59', 0),
(4, 2, 5, 4, 'test', 200, '2013-09-15 07:36:41', 0),
(5, 8, 8, 7, 'Implique entre autre', 100, '2013-09-15 08:42:18', 0),
(6, 12, 23, 24, 'il y a pas de bateau', 0, '2013-09-15 10:16:45', 0),
(7, 2, 4, 1, 'link', 100, '2013-09-16 18:00:20', 0),
(8, 2, 4, 26, 'link', 0, '2013-09-16 18:02:11', 0);

-- --------------------------------------------------------

--
-- Structure de la table `log`
--

CREATE TABLE IF NOT EXISTS `log` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_type` int(10) unsigned DEFAULT NULL,
  `log_val` text,
  `log_create_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `log_ip` varchar(255) DEFAULT NULL,
  `log_entry_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Contenu de la table `log`
--

INSERT INTO `log` (`log_id`, `log_type`, `log_val`, `log_create_date`, `log_ip`, `log_entry_id`) VALUES
(1, 202, 'NO IDEA OF THE CONTENT TO PUT HERE', '2013-09-15 09:56:34', '::1', 1),
(2, 202, 'NO IDEA OF THE CONTENT TO PUT HERE', '2013-09-15 09:57:03', '::1', 1),
(3, 202, 'NO IDEA OF THE CONTENT TO PUT HERE', '2013-09-15 09:58:56', '::1', 1),
(4, 201, 'NO IDEA OF THE CONTENT TO PUT HERE', '2013-09-15 10:00:44', '::1', 11),
(5, 201, 'NO IDEA OF THE CONTENT TO PUT HERE', '2013-09-15 17:15:10', '192.168.1.254', 15),
(6, 201, 'NO IDEA OF THE CONTENT TO PUT HERE', '2013-09-15 17:15:23', '192.168.1.254', 16),
(7, 202, 'NO IDEA OF THE CONTENT TO PUT HERE', '2013-09-15 17:33:36', '78.245.9.214', 4),
(8, 202, 'NO IDEA OF THE CONTENT TO PUT HERE', '2013-09-15 18:25:18', '82.224.91.103', 2),
(9, 202, 'NO IDEA OF THE CONTENT TO PUT HERE', '2013-09-16 17:58:54', '::1', 2),
(10, 202, 'NO IDEA OF THE CONTENT TO PUT HERE', '2013-09-16 18:03:11', '::1', 2),
(11, 202, 'NO IDEA OF THE CONTENT TO PUT HERE', '2013-09-16 18:57:13', '::1', 1);

-- --------------------------------------------------------

--
-- Structure de la table `ressource`
--

CREATE TABLE IF NOT EXISTS `ressource` (
  `ress_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ress_entry_id` int(10) unsigned NOT NULL,
  `ress_category_id` int(10) unsigned DEFAULT NULL,
  `ress_type` int(11) unsigned NOT NULL,
  `ress_val` varchar(255) NOT NULL,
  `ress_trend` int(11) NOT NULL DEFAULT '0',
  `ress_create_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ress_alert` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ress_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=30 ;

--
-- Contenu de la table `ressource`
--

INSERT INTO `ressource` (`ress_id`, `ress_entry_id`, `ress_category_id`, `ress_type`, `ress_val`, `ress_trend`, `ress_create_date`, `ress_alert`) VALUES
(1, 2, 1, 500, 'http://eliepse.fr', 0, '2013-09-14 13:56:43', 0),
(2, 2, 1, 500, 'http://78.239.49.8', 0, '2013-09-14 14:07:41', 0),
(3, 3, 1, 200, 'test', 0, '2013-09-14 14:39:36', 0),
(4, 2, 1, 500, 'http://www.google.fr', 0, '2013-09-15 07:33:53', 0),
(5, 2, 1, 100, 'http://www.youtube.com/watch?v=7Cv6Wo5EhzI', 0, '2013-09-15 07:35:24', 0),
(6, 3, 1, 400, 'test2', 0, '2013-09-15 07:52:08', 0),
(7, 8, 2, 100, 'Vidéo Autre', 0, '2013-09-15 08:41:51', 0),
(8, 8, 1, 400, 'Mort Catégorie', 0, '2013-09-15 08:42:02', 0),
(9, 1, 1, 400, 'hey', 0, '2013-09-15 09:41:53', 0),
(22, 1, 1, 400, 'hop', 0, '2013-09-15 09:58:55', 0),
(23, 12, 1, 400, 'bateau', 0, '2013-09-15 10:16:20', 0),
(24, 12, 1, 100, 'pokemon opening', 0, '2013-09-15 10:16:27', 0),
(25, 4, 1, 200, 'l', 0, '2013-09-15 17:33:36', 0),
(26, 2, 1, 400, 'test', 0, '2013-09-15 18:25:18', 0),
(27, 2, 1, 400, 'ressource', 0, '2013-09-16 17:58:54', 0),
(28, 2, 1, 100, 'ressource2', 0, '2013-09-16 18:03:11', 0),
(29, 1, 1, 100, 'test333', 0, '2013-09-16 18:57:13', 0);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(20) NOT NULL,
  `user_pass` varchar(255) NOT NULL,
  `user_type` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
