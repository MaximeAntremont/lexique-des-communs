-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Sam 28 Septembre 2013 à 11:33
-- Version du serveur: 5.5.24-log
-- Version de PHP: 5.3.13

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
-- Structure de la table `bc_category`
--

CREATE TABLE IF NOT EXISTS `bc_category` (
  `category_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_val` varchar(25) NOT NULL,
  `category_create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `bc_entry`
--

CREATE TABLE IF NOT EXISTS `bc_entry` (
  `entry_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `entry_val` varchar(50) NOT NULL,
  `entry_create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`entry_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `bc_entry`
--

INSERT INTO `bc_entry` (`entry_id`, `entry_val`, `entry_create_date`) VALUES
(1, 'Test', '2013-09-27 18:54:26'),
(2, 'Test', '2013-09-27 18:54:55'),
(3, 'Deuxième ressource', '2013-09-27 18:56:39');

-- --------------------------------------------------------

--
-- Structure de la table `bc_link`
--

CREATE TABLE IF NOT EXISTS `bc_link` (
  `link_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `link_entry_id` int(11) unsigned NOT NULL,
  `link_from` int(10) unsigned NOT NULL,
  `link_to` int(10) unsigned NOT NULL,
  `link_val` varchar(20) DEFAULT NULL,
  `link_type` int(10) unsigned NOT NULL,
  `link_create_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `link_alert` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`link_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `bc_log`
--

CREATE TABLE IF NOT EXISTS `bc_log` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_type` int(10) unsigned DEFAULT NULL,
  `log_val` text,
  `log_create_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `log_ip` varchar(255) DEFAULT NULL,
  `log_entry_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Contenu de la table `bc_log`
--

INSERT INTO `bc_log` (`log_id`, `log_type`, `log_val`, `log_create_date`, `log_ip`, `log_entry_id`) VALUES
(1, 201, 'null', '2013-09-27 18:56:39', '::1', 3),
(2, 202, 'null', '2013-09-27 18:58:41', '::1', 2),
(3, 202, 'null', '2013-09-27 20:54:56', '::1', 2),
(4, 202, 'null', '2013-09-27 20:58:06', '::1', 2),
(5, 202, 'null', '2013-09-27 20:58:28', '::1', 2),
(6, 202, 'null', '2013-09-27 21:03:15', '::1', 2),
(7, 202, 'null', '2013-09-27 21:03:55', '::1', 2),
(8, 301, 'id$7', '2013-09-27 21:22:56', '::1', 2),
(9, 101, 'Ceci est censé être un rapport de bug ! :)', '2013-09-28 10:34:18', '::1', 2),
(10, 101, 'Lorsque le champs passe de "input" à "textarea": décalage ? -> vitesse de frappe trop rapide ?', '2013-09-28 10:47:05', '::1', 2);

-- --------------------------------------------------------

--
-- Structure de la table `bc_ressource`
--

CREATE TABLE IF NOT EXISTS `bc_ressource` (
  `ress_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ress_entry_id` int(10) unsigned NOT NULL,
  `ress_category_id` int(10) unsigned DEFAULT NULL,
  `ress_type` int(11) unsigned NOT NULL,
  `ress_val` text NOT NULL,
  `ress_trend` int(11) NOT NULL DEFAULT '0',
  `ress_create_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ress_alert` int(11) unsigned NOT NULL DEFAULT '0',
  `ress_titre` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`ress_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Contenu de la table `bc_ressource`
--

INSERT INTO `bc_ressource` (`ress_id`, `ress_entry_id`, `ress_category_id`, `ress_type`, `ress_val`, `ress_trend`, `ress_create_date`, `ress_alert`, `ress_titre`) VALUES
(1, 2, NULL, 950, 'nouvelle ressource', 0, '2013-09-27 18:55:54', 0, NULL),
(2, 2, NULL, 990, 'Pourquoi ça ne marche pas...', 0, '2013-09-27 18:58:41', 0, NULL),
(3, 2, NULL, 103, '<iframe frameborder="0" width="480" height="270" src="http://www.dailymotion.com/embed/video/x14vmrg"></iframe><br /><a href="http://www.dailymotion.com/video/x14vmrg_the-magician-when-the-night-is-over-feat-newtimers_music" target="_blank">The Magician - When The Night Is Over (feat...</a> <i>par <a href="http://www.dailymotion.com/TheMagician-Official" target="_blank">TheMagician-Official</a></i>', 0, '2013-09-27 20:54:56', 0, 'Super titre'),
(4, 2, NULL, 990, 'Ceci est un trés trés long texte sans titre.', 0, '2013-09-27 20:58:06', 0, NULL),
(5, 2, NULL, 990, 'Ceci est un texte très long mais', 0, '2013-09-27 20:58:28', 0, 'Avec une titre'),
(6, 2, NULL, 950, 'normal', 0, '2013-09-27 21:03:15', 0, NULL),
(7, 2, NULL, 950, 'normal', 1, '2013-09-27 21:03:55', 0, 'narmol');

-- --------------------------------------------------------

--
-- Structure de la table `lexique_admin_user`
--

CREATE TABLE IF NOT EXISTS `lexique_admin_user` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(20) NOT NULL,
  `user_pass` varchar(255) NOT NULL,
  `user_type` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `lexique_admin_user`
--

INSERT INTO `lexique_admin_user` (`user_id`, `user_name`, `user_pass`, `user_type`) VALUES
(1, 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 42),
(2, 'modo', 'b828c7599e286a4407084dcec654512489e29bda', 21);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
