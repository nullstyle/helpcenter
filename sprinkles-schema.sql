-- MySQL dump 10.10
--
-- Host: localhost    Database: sprinkles
-- ------------------------------------------------------
-- Server version	5.0.27

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `http_cache`
--

DROP TABLE IF EXISTS `http_cache`;
CREATE TABLE `http_cache` (
  `url` varchar(1024) default NULL,
  `headers` blob,
  `content` blob,
  `fetched_on` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `token` varchar(32) default NULL,
  `token_secret` varchar(128) default NULL,
  `username` varchar(255) default NULL,
  `user_url` varchar(1024) default NULL,
  `user_photo` varchar(1024) default NULL,
  `user_fn` varchar(64) default NULL,
  `user_sprinkles_admin` varchar(1) default NULL,
  `modified_on` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `site_links`
--

DROP TABLE IF EXISTS `site_links`;
CREATE TABLE `site_links` (
  `url` text,
  `text` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `site_settings`
--

DROP TABLE IF EXISTS `site_settings`;
CREATE TABLE `site_settings` (
  `background_color` varchar(255) default NULL,
  `contact_email` varchar(255) default NULL,
  `contact_phone` varchar(255) default NULL,
  `contact_address` text,
  `map_url` text,
  `faq_type` varchar(255) default NULL,
  `logo_data` blob,
  `logo_link` varchar(1024) default NULL,
  `configured` char(1) default NULL,
  `company_id` varchar(255) default NULL,
  `oauth_consumer_key` varchar(12) default NULL,
  `oauth_consumer_secret` varchar(32) default NULL,
  `sprinkles_root_url` varchar(1024) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `site_settings`
--

LOCK TABLES `site_settings` WRITE;
/*!40000 ALTER TABLE `site_settings` DISABLE KEYS */;
INSERT INTO `site_settings` VALUES ('#86fff6',null,null,null,null,NULL,
null,null,null,null,null,null,null);
/*!40000 ALTER TABLE `site_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
  `username` varchar(255) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admins`
--


