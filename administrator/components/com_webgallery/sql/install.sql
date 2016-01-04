-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
-- PHP version: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `webgallery`
--

-- --------------------------------------------------------

--
-- Table`#__webgallery_items`
--

DROP TABLE IF EXISTS `#__webgallery_items`;
CREATE TABLE IF NOT EXISTS `#__webgallery_items` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `asset_id` int(11) NOT NULL,
  `catid` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `tags` varchar(255) NOT NULL,
  `introtext` mediumtext NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `ordering` int(11) NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `publish_up` datetime NOT NULL,
  `publish_down` datetime NOT NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `access` int(10) unsigned NOT NULL,
  `language` char(7) NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_access` (`access`),
  KEY `idx_alias` (`alias`),
  KEY `idx_createdby` (`catid`),
  KEY `idx_language` (`language`),
  KEY `idx_checkout` (`checked_out`),
  KEY `cat_index` (`published`,`access`,`catid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Table `#__webgallery_queues`
--

DROP TABLE IF EXISTS `#__webgallery_queues`;
CREATE TABLE IF NOT EXISTS `#__webgallery_queues` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Table `#__webgallery_thumbnails`
--

DROP TABLE IF EXISTS `#__webgallery_thumbnails`;
CREATE TABLE IF NOT EXISTS `#__webgallery_thumbnails` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `path` varchar(255) NOT NULL,
  `item_id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `scanned` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_path` (`path`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

INSERT IGNORE INTO `#__content_types` (`type_id`, `type_title`, `type_alias`, `table`, `rules`, `field_mappings`, `router`) VALUES
('', 'WebGallery Item', 'com_webgallery.item', '{"special":{"dbtable":"#__webgallery_items","key":"id","type":"Item","prefix":"WebgalleryTable","config":"array()"},"common":{"dbtable":"#__ucm_content","key":"ucm_id","type":"Corecontent","prefix":"JTable","config":"array()"}}', '', '{\r\n    "common": {\r\n        "core_content_item_id": "id",\r\n        "core_title": "title",\r\n        "core_state": "published",\r\n        "core_alias": "alias",\r\n        "core_created_time": "created",\r\n        "core_modified_time": "modified",\r\n        "core_body": "introtext",\r\n        "core_publish_up": "publish_up",\r\n        "core_publish_down": "publish_down",\r\n        "core_access": "access",\r\n        "core_params": "params",\r\n        "core_language": "language",\r\n        "core_images": "images",\r\n        "core_urls": "urls",\r\n        "core_version": "version",\r\n        "core_ordering": "ordering",\r\n        "core_catid": "catid",\r\n        "asset_id": "asset_id"\r\n    }\r\n}', 'WebgalleryHelperRoute::getItemRoute');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
