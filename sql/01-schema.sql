-- Adminer 4.17.1 MySQL-MariaDB Dump Compatible

SET NAMES utf8mb4;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `box`;
CREATE TABLE `box` (
  `id` varchar(128) NOT NULL,
  `token` varchar(64) NOT NULL,
  `libelle` varchar(128) NOT NULL,
  `description` text DEFAULT NULL,
  `montant` decimal(12,2) DEFAULT 0.00,
  `kdo` tinyint(4) NOT NULL DEFAULT 0,
  `message_kdo` text DEFAULT '',
  `statut` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT '0000-00-00 00:00:00',
  `updated_at` datetime DEFAULT NULL,
  `createur_id` varchar(128) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `box2presta`;
CREATE TABLE `box2presta` (
  `box_id` varchar(128) NOT NULL,
  `presta_id` varchar(128) NOT NULL,
  `quantite` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `categorie`;
CREATE TABLE `categorie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(128) NOT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `coffret2presta`;
CREATE TABLE `coffret2presta` (
  `coffret_id` int(11) NOT NULL,
  `presta_id` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `coffret_type`;
CREATE TABLE `coffret_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(128) NOT NULL,
  `description` text NOT NULL,
  `theme_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `prestation`;
CREATE TABLE `prestation` (
  `id` varchar(128) NOT NULL,
  `libelle` varchar(128) NOT NULL,
  `description` text NOT NULL,
  `url` varchar(256) DEFAULT NULL,
  `unite` varchar(128) DEFAULT NULL,
  `tarif` decimal(10,2) NOT NULL,
  `img` varchar(128) NOT NULL,
  `cat_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `theme`;
CREATE TABLE `theme` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(128) NOT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` varchar(40) NOT NULL,
  `user_id` varchar(128) DEFAULT NULL,
  `password` varchar(256) DEFAULT NULL,
  `role` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_pk2` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Script corrigé le 2025-05-05
