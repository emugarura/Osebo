-- Adminer 3.7.0 MySQL dump

SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = '+02:00';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `indicatorpoints`;
CREATE TABLE `indicatorpoints` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `indicator` int(10) unsigned NOT NULL,
  `region` mediumint(8) unsigned NOT NULL,
  `year` year(4) NOT NULL,
  `gender` enum('M','F') COLLATE utf8_unicode_ci DEFAULT NULL,
  `value` decimal(10,2) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `indicator` (`indicator`),
  KEY `region` (`region`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `indicators`;
CREATE TABLE `indicators` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `category` int(10) unsigned DEFAULT NULL,
  `subcategory` int(10) unsigned DEFAULT NULL,
  `topic` smallint(5) unsigned DEFAULT '1',
  `active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `category` (`category`),
  KEY `subcategory` (`subcategory`),
  KEY `topic` (`topic`),
  CONSTRAINT `indicators_ibfk_1` FOREIGN KEY (`category`) REFERENCES `indicators` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `indicators_ibfk_2` FOREIGN KEY (`subcategory`) REFERENCES `indicators` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `indicators_ibfk_3` FOREIGN KEY (`topic`) REFERENCES `topics` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `legends`;
CREATE TABLE `legends` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `indicator` int(10) unsigned NOT NULL,
  `label` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `from` float unsigned NOT NULL,
  `to` float unsigned NOT NULL,
  `color` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `indicator` (`indicator`),
  CONSTRAINT `legends_ibfk_1` FOREIGN KEY (`indicator`) REFERENCES `indicators` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `legends_meta`;
CREATE TABLE `legends_meta` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `indicator` int(10) unsigned NOT NULL,
  `label` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `indicator` (`indicator`),
  CONSTRAINT `legends_meta_ibfk_1` FOREIGN KEY (`indicator`) REFERENCES `indicators` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `region_alias`;
CREATE TABLE `region_alias` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `region` mediumint(8) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `region` (`region`),
  CONSTRAINT `region_alias_ibfk_1` FOREIGN KEY (`region`) REFERENCES `regions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `regions`;
CREATE TABLE `regions` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `coordinates` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `topics`;
CREATE TABLE `topics` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- 2013-06-20 13:09:20
