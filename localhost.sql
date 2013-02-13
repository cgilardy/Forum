-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Mer 13 Février 2013 à 12:08
-- Version du serveur: 5.5.8
-- Version de PHP: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `rpg`
--
CREATE DATABASE `rpg` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `rpg`;

-- --------------------------------------------------------

--
-- Structure de la table `forum_categories`
--

CREATE TABLE IF NOT EXISTS `forum_categories` (
  `id_categorie` int(11) NOT NULL AUTO_INCREMENT,
  `titre_cat` varchar(255) NOT NULL,
  PRIMARY KEY (`id_categorie`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `forum_categories`
--

INSERT INTO `forum_categories` (`id_categorie`, `titre_cat`) VALUES
(1, 'La communautÃ©');

-- --------------------------------------------------------

--
-- Structure de la table `forum_petitcat`
--

CREATE TABLE IF NOT EXISTS `forum_petitcat` (
  `id_petite` int(11) NOT NULL AUTO_INCREMENT,
  `titre_petite` varchar(255) NOT NULL,
  `sousTitre_petite` varchar(255) NOT NULL,
  `id_souscat` int(11) NOT NULL,
  PRIMARY KEY (`id_petite`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `forum_petitcat`
--

INSERT INTO `forum_petitcat` (`id_petite`, `titre_petite`, `sousTitre_petite`, `id_souscat`) VALUES
(1, 'Ollivander', 'Venez acheter mes baguettes, Ce sont les meilleurs !', 25);

-- --------------------------------------------------------

--
-- Structure de la table `forum_petitrep`
--

CREATE TABLE IF NOT EXISTS `forum_petitrep` (
  `id_petitrep` int(11) NOT NULL AUTO_INCREMENT,
  `message_petitrep` text NOT NULL,
  `id_membre` int(11) NOT NULL,
  `id_petitsuj` int(11) NOT NULL,
  `date_creation_petitrep` bigint(20) NOT NULL,
  `date_modification_petitrep` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id_petitrep`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `forum_petitrep`
--

INSERT INTO `forum_petitrep` (`id_petitrep`, `message_petitrep`, `id_membre`, `id_petitsuj`, `date_creation_petitrep`, `date_modification_petitrep`) VALUES
(1, 'oui !', 2, 2, 1360177673, NULL),
(2, '<color="red">coucou toi</color>', 8, 2, 1360177761, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `forum_petitsujet`
--

CREATE TABLE IF NOT EXISTS `forum_petitsujet` (
  `id_petitsuj` int(11) NOT NULL AUTO_INCREMENT,
  `titre_petitsuj` varchar(255) NOT NULL,
  `sousTitre_petitsuj` varchar(255) NOT NULL,
  `id_petitcat` int(11) NOT NULL,
  `annonce_petitsuj` tinyint(1) NOT NULL,
  `date_creation_petitsuj` bigint(20) NOT NULL,
  `date_modification_petitsuj` bigint(20) DEFAULT NULL,
  `date_derniere_reponse_petitsuj` bigint(20) NOT NULL,
  `id_membre` int(11) NOT NULL,
  `message_petitsuj` text NOT NULL,
  PRIMARY KEY (`id_petitsuj`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `forum_petitsujet`
--

INSERT INTO `forum_petitsujet` (`id_petitsuj`, `titre_petitsuj`, `sousTitre_petitsuj`, `id_petitcat`, `annonce_petitsuj`, `date_creation_petitsuj`, `date_modification_petitsuj`, `date_derniere_reponse_petitsuj`, `id_membre`, `message_petitsuj`) VALUES
(2, 'hey', 'test', 1, 0, 1360088774, NULL, 1360177761, 2, 'coucou');

-- --------------------------------------------------------

--
-- Structure de la table `forum_reponses`
--

CREATE TABLE IF NOT EXISTS `forum_reponses` (
  `id_reponse` int(11) NOT NULL AUTO_INCREMENT,
  `message_reponse` text NOT NULL,
  `id_membre` int(11) NOT NULL,
  `id_sujet` int(11) NOT NULL,
  `date_creation_reponse` bigint(20) NOT NULL,
  `date_modification_reponse` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id_reponse`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Contenu de la table `forum_reponses`
--

INSERT INTO `forum_reponses` (`id_reponse`, `message_reponse`, `id_membre`, `id_sujet`, `date_creation_reponse`, `date_modification_reponse`) VALUES
(19, 'coucou', 8, 5, 1359846382, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `forum_souscategories`
--

CREATE TABLE IF NOT EXISTS `forum_souscategories` (
  `id_souscategorie` int(11) NOT NULL AUTO_INCREMENT,
  `titre_souscat` varchar(255) NOT NULL,
  `id_categorie` int(11) NOT NULL,
  `sousTitre_souscat` varchar(255) NOT NULL,
  `place` int(11) NOT NULL,
  PRIMARY KEY (`id_souscategorie`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

--
-- Contenu de la table `forum_souscategories`
--

INSERT INTO `forum_souscategories` (`id_souscategorie`, `titre_souscat`, `id_categorie`, `sousTitre_souscat`, `place`) VALUES
(24, 'Salut', 1, 'Truc', 2),
(25, 'Le chemin de traverse', 1, 'Venez vous promenez et faire du shopping entre ami !', 1),
(26, 'Tatayoyo', 1, 'avec son grand chapeau', 3);

-- --------------------------------------------------------

--
-- Structure de la table `forum_sujets`
--

CREATE TABLE IF NOT EXISTS `forum_sujets` (
  `id_sujet` int(11) NOT NULL AUTO_INCREMENT,
  `titre_sujet` varchar(255) NOT NULL,
  `sousTitre_sujet` varchar(255) DEFAULT NULL,
  `id_souscat` int(11) NOT NULL,
  `annonce_sujet` tinyint(1) NOT NULL,
  `date_creation_sujet` bigint(20) NOT NULL,
  `date_modification_sujet` bigint(20) DEFAULT NULL,
  `date_derniere_reponse` bigint(20) DEFAULT NULL,
  `id_membre` int(11) NOT NULL,
  `message_sujet` text NOT NULL,
  PRIMARY KEY (`id_sujet`),
  UNIQUE KEY `date_creation_sujet` (`date_creation_sujet`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Contenu de la table `forum_sujets`
--

INSERT INTO `forum_sujets` (`id_sujet`, `titre_sujet`, `sousTitre_sujet`, `id_souscat`, `annonce_sujet`, `date_creation_sujet`, `date_modification_sujet`, `date_derniere_reponse`, `id_membre`, `message_sujet`) VALUES
(5, 'Salut', 'sa va', 25, 0, 1359733742, NULL, 1359846382, 2, 'Hey comment <gras>sa</gras> va ?'),
(6, 'euh', '', 24, 0, 1359817570, NULL, 1359817570, 2, 'salut'),
(7, 'tset', 'annonce', 25, 1, 1359910193, NULL, 1359910193, 2, 'coucou');

-- --------------------------------------------------------

--
-- Structure de la table `inventaires`
--

CREATE TABLE IF NOT EXISTS `inventaires` (
  `id_inventaire` int(11) NOT NULL AUTO_INCREMENT,
  `id_membre` int(11) NOT NULL,
  PRIMARY KEY (`id_inventaire`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Contenu de la table `inventaires`
--

INSERT INTO `inventaires` (`id_inventaire`, `id_membre`) VALUES
(1, 2),
(8, 8),
(9, 9),
(10, 10),
(11, 11),
(12, 12);

-- --------------------------------------------------------

--
-- Structure de la table `lus_petitsuj`
--

CREATE TABLE IF NOT EXISTS `lus_petitsuj` (
  `id_lut_petitsuj` int(11) NOT NULL AUTO_INCREMENT,
  `id_membre` int(11) NOT NULL,
  `id_petitsuj` int(11) NOT NULL,
  PRIMARY KEY (`id_lut_petitsuj`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=64 ;

--
-- Contenu de la table `lus_petitsuj`
--

INSERT INTO `lus_petitsuj` (`id_lut_petitsuj`, `id_membre`, `id_petitsuj`) VALUES
(60, 8, 2),
(61, 8, 2),
(62, 8, 2),
(63, 2, 2);

-- --------------------------------------------------------

--
-- Structure de la table `lus_sujets`
--

CREATE TABLE IF NOT EXISTS `lus_sujets` (
  `id_lut_sujet` int(11) NOT NULL AUTO_INCREMENT,
  `id_membre` int(11) NOT NULL,
  `id_sujet` int(11) NOT NULL,
  PRIMARY KEY (`id_lut_sujet`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=258 ;

--
-- Contenu de la table `lus_sujets`
--

INSERT INTO `lus_sujets` (`id_lut_sujet`, `id_membre`, `id_sujet`) VALUES
(1, 2, 1),
(2, 2, 1),
(3, 2, 1),
(4, 2, 1),
(5, 2, 1),
(6, 2, 1),
(7, 2, 1),
(8, 2, 1),
(9, 2, 1),
(10, 2, 1),
(11, 2, 1),
(12, 2, 1),
(13, 2, 1),
(14, 2, 1),
(15, 2, 1),
(16, 2, 1),
(17, 2, 1),
(18, 2, 1),
(19, 2, 1),
(20, 2, 1),
(21, 2, 1),
(22, 2, 1),
(23, 2, 1),
(24, 2, 1),
(25, 2, 1),
(26, 2, 1),
(27, 2, 1),
(28, 2, 1),
(29, 2, 1),
(30, 2, 1),
(31, 2, 1),
(32, 2, 1),
(33, 2, 1),
(34, 2, 1),
(35, 2, 1),
(36, 2, 1),
(37, 2, 1),
(38, 2, 1),
(39, 2, 1),
(40, 2, 1),
(41, 2, 1),
(42, 2, 1),
(43, 2, 1),
(44, 2, 1),
(45, 2, 1),
(46, 2, 1),
(47, 2, 1),
(48, 2, 1),
(49, 2, 1),
(50, 2, 1),
(51, 2, 1),
(52, 2, 1),
(53, 2, 1),
(54, 2, 1),
(55, 2, 1),
(56, 2, 1),
(57, 2, 1),
(58, 2, 1),
(59, 2, 1),
(60, 2, 1),
(61, 2, 1),
(62, 2, 1),
(63, 2, 1),
(64, 2, 1),
(65, 2, 1),
(66, 2, 1),
(67, 2, 1),
(68, 2, 1),
(69, 2, 1),
(70, 2, 1),
(71, 2, 1),
(72, 2, 1),
(73, 2, 1),
(74, 2, 1),
(75, 2, 1),
(76, 2, 1),
(77, 2, 1),
(78, 2, 1),
(79, 2, 1),
(80, 2, 1),
(81, 2, 1),
(82, 2, 1),
(83, 2, 1),
(84, 2, 1),
(85, 2, 1),
(86, 2, 1),
(87, 2, 1),
(88, 2, 1),
(89, 2, 1),
(90, 2, 1),
(91, 2, 1),
(92, 2, 1),
(93, 2, 1),
(94, 2, 1),
(95, 2, 1),
(96, 8, 1),
(97, 8, 1),
(98, 2, 1),
(99, 2, 1),
(100, 2, 1),
(101, 2, 1),
(102, 8, 1),
(103, 8, 1),
(104, 8, 1),
(105, 8, 1),
(106, 8, 1),
(107, 8, 1),
(108, 2, 1),
(109, 2, 1),
(110, 2, 1),
(111, 2, 1),
(112, 8, 1),
(113, 8, 1),
(114, 8, 1),
(115, 8, 1),
(116, 8, 1),
(117, 8, 1),
(118, 8, 1),
(119, 8, 1),
(120, 2, 1),
(121, 2, 1),
(122, 2, 1),
(123, 2, 1),
(124, 2, 1),
(125, 2, 1),
(126, 2, 1),
(127, 2, 1),
(134, 8, 4),
(245, 2, 6),
(248, 8, 5),
(249, 8, 5),
(250, 8, 5),
(251, 8, 5),
(252, 8, 5),
(253, 8, 5),
(254, 8, 5),
(255, 2, 5),
(256, 8, 2),
(257, 2, 0);

-- --------------------------------------------------------

--
-- Structure de la table `maisons`
--

CREATE TABLE IF NOT EXISTS `maisons` (
  `id_maison` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `point` int(11) NOT NULL,
  `blason` text NOT NULL,
  PRIMARY KEY (`id_maison`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `maisons`
--

INSERT INTO `maisons` (`id_maison`, `nom`, `point`, `blason`) VALUES
(1, 'Gryffondor', 0, 'images/gryffondor.gif'),
(2, 'Poufsouffle', 0, 'images/poufsouffle.gif'),
(3, 'Serdaigle', 0, 'images/serdaigle.gif'),
(4, 'Serpentard', 0, 'images/serpentard.gif');

-- --------------------------------------------------------

--
-- Structure de la table `membres`
--

CREATE TABLE IF NOT EXISTS `membres` (
  `id_membre` int(11) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(255) NOT NULL,
  `passe` varchar(255) NOT NULL,
  `description` text,
  `id_maison` int(11) NOT NULL,
  `age` int(11) NOT NULL,
  `sexe` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `afficherEmail` tinyint(1) NOT NULL,
  `date_inscription` date NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `signature` text,
  `id_rang` int(11) NOT NULL,
  `id_inventaire` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_membre`),
  UNIQUE KEY `id_inventaire` (`avatar`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Contenu de la table `membres`
--

INSERT INTO `membres` (`id_membre`, `pseudo`, `passe`, `description`, `id_maison`, `age`, `sexe`, `email`, `afficherEmail`, `date_inscription`, `avatar`, `signature`, `id_rang`, `id_inventaire`) VALUES
(2, 'Albus Dumbledore', '9463b024ddfe9d51dc28a0eb73e370c7', 'salut', 1, 105, 'Homme', 'eternel_espoir@hotmail.fr', 0, '2013-01-22', 'avatars/dumbledore.jpg', NULL, 1, 1),
(8, 'Manon', '3623c7adbaf2b3ee4d5c2281f5dc07bb', '', 3, 11, 'Femme', 'manon@jesaispas.com', 0, '2013-01-22', 'avatars/1f50b396ee7c9b5872277e13a57d25b2_5202.jpg', NULL, 12, 8),
(9, 'Minerva McGonagall', '9463b024ddfe9d51dc28a0eb73e370c7', '', 1, 50, 'Femme', 'gilardy.clement@gmail.com', 0, '2013-02-02', 'avatars/0af4413b4c48654b4130156c49809814_9851.jpg', '', 11, 9),
(10, 'Severus Rogue', '9463b024ddfe9d51dc28a0eb73e370c7', '', 4, 50, 'Homme', 'gilardy.clement@gmail.com', 0, '2013-02-02', 'avatars/83268d25163352169a7947768a8930cf_9883.jpg', '', 4, 10),
(11, 'Filius Flitwick', '9463b024ddfe9d51dc28a0eb73e370c7', '', 3, 50, 'Homme', 'gilardy.clement@gmail.com', 0, '2013-02-02', 'avatars/d8dfbf4fd66c67edc3fa2d0485781463_24033.jpg', '', 3, 11),
(12, 'Pomona Chourave', '9463b024ddfe9d51dc28a0eb73e370c7', '', 2, 50, 'Femme', 'gilardy.clement@gmail.com', 0, '2013-02-02', 'avatars/f3823d8ac3c45dad29e3b4e946d3f415_25589.jpg', '', 6, 12);

-- --------------------------------------------------------

--
-- Structure de la table `objets`
--

CREATE TABLE IF NOT EXISTS `objets` (
  `id_objet` int(11) NOT NULL AUTO_INCREMENT,
  `type_objet` varchar(255) NOT NULL,
  `nom_objet` varchar(255) NOT NULL,
  `image_objet` text NOT NULL,
  `caracteristique_objet` text NOT NULL,
  `force` int(11) NOT NULL,
  `agilite` int(11) NOT NULL,
  `intel` int(11) NOT NULL,
  `pouvoir` int(11) NOT NULL,
  PRIMARY KEY (`id_objet`),
  UNIQUE KEY `nom_objet` (`nom_objet`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `objets`
--


-- --------------------------------------------------------

--
-- Structure de la table `objets_membres`
--

CREATE TABLE IF NOT EXISTS `objets_membres` (
  `id_inventaire` int(11) NOT NULL,
  `id_objet` int(11) NOT NULL,
  PRIMARY KEY (`id_inventaire`,`id_objet`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `objets_membres`
--


-- --------------------------------------------------------

--
-- Structure de la table `rang`
--

CREATE TABLE IF NOT EXISTS `rang` (
  `id_rang` int(11) NOT NULL AUTO_INCREMENT,
  `nom_rang` varchar(255) NOT NULL,
  `place_rang` int(11) NOT NULL,
  PRIMARY KEY (`id_rang`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Contenu de la table `rang`
--

INSERT INTO `rang` (`id_rang`, `nom_rang`, `place_rang`) VALUES
(1, 'Directeur de l''école', 0),
(2, 'Directeur de Poufsouffle', 1),
(3, 'Directeur de Serdaigle', 1),
(4, 'Directeur de Serpentard', 1),
(5, 'Directeur de Gryffondor', 1),
(6, 'Directrice de Poufsouffle', 1),
(7, 'Collégien', 2),
(8, 'Collégienne', 2),
(9, 'Directrice de Serdaigle', 1),
(10, 'Directrice de Serpentard', 1),
(11, 'Directrice de Gryffondor', 1),
(12, 'Première année', 3),
(13, 'Deuxième années', 4),
(14, 'Troisième années', 5),
(15, 'Quatrième années', 6),
(16, 'Cinquième années', 7),
(17, 'Sixième années', 8),
(18, 'Septième années', 9);
